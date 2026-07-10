<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('module');
            $table->string('action');
            $table->string('name');
            $table->timestamps();

            $table->unique(['module', 'action']);
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();

            $table->primary(['permission_id', 'role_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('password')->constrained()->nullOnDelete();
        });

        $this->seedPermissions();
        $this->seedRoles();
        $this->migrateUserRoles();

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('staff')->after('password');
        });

        $roleSlugs = DB::table('roles')->pluck('slug', 'id');
        foreach (DB::table('users')->get() as $user) {
            $slug = $roleSlugs[$user->role_id] ?? 'staff';
            $legacy = match ($slug) {
                'super_admin', 'admin' => 'admin',
                'guardian' => 'guardian',
                default => 'staff',
            };
            DB::table('users')->where('id', $user->id)->update(['role' => $legacy]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
        });

        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }

    private function seedPermissions(): void
    {
        $modules = config('permissions.modules', []);
        $actionLabels = config('permissions.actions', []);
        $now = now();

        foreach ($modules as $module => $meta) {
            foreach ($meta['actions'] as $action) {
                $actionLabel = $actionLabels[$action] ?? ucfirst($action);
                DB::table('permissions')->insert([
                    'module' => $module,
                    'action' => $action,
                    'name' => $actionLabel.' '.$meta['label'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    private function seedRoles(): void
    {
        $now = now();
        $allPermissionIds = DB::table('permissions')->pluck('id');

        $superAdminId = DB::table('roles')->insertGetId([
            'name' => 'Super Admin',
            'slug' => 'super_admin',
            'description' => 'Akses penuh ke seluruh modul.',
            'is_system' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        foreach ($allPermissionIds as $permissionId) {
            DB::table('permission_role')->insert([
                'permission_id' => $permissionId,
                'role_id' => $superAdminId,
            ]);
        }

        $staffModules = ['akademik', 'kesiswaan'];
        $staffPermissionIds = DB::table('permissions')
            ->whereIn('module', $staffModules)
            ->pluck('id');

        $staffId = DB::table('roles')->insertGetId([
            'name' => 'Staff',
            'slug' => 'staff',
            'description' => 'Akses akademik dan kesiswaan.',
            'is_system' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        foreach ($staffPermissionIds as $permissionId) {
            DB::table('permission_role')->insert([
                'permission_id' => $permissionId,
                'role_id' => $staffId,
            ]);
        }

        $guardianViewId = DB::table('permissions')
            ->where('module', 'kesiswaan')
            ->where('action', 'view')
            ->value('id');

        $guardianId = DB::table('roles')->insertGetId([
            'name' => 'Wali siswa',
            'slug' => 'guardian',
            'description' => 'Hanya dapat melihat data terbatas.',
            'is_system' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        if ($guardianViewId) {
            DB::table('permission_role')->insert([
                'permission_id' => $guardianViewId,
                'role_id' => $guardianId,
            ]);
        }
    }

    private function migrateUserRoles(): void
    {
        $map = [
            'admin' => 'super_admin',
            'staff' => 'staff',
            'guardian' => 'guardian',
        ];

        $roleIds = DB::table('roles')->pluck('id', 'slug');
        $defaultRoleId = $roleIds['staff'] ?? null;

        foreach (DB::table('users')->get() as $user) {
            $slug = $map[$user->role] ?? 'staff';
            $roleId = $roleIds[$slug] ?? $defaultRoleId;
            DB::table('users')->where('id', $user->id)->update(['role_id' => $roleId]);
        }
    }
};
