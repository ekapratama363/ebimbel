<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenjangs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('status')->default('aktif');
            $table->timestamps();
        });

        $defaults = [
            ['code' => 'SD', 'name' => 'Sekolah Dasar', 'sort_order' => 1, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'SMP', 'name' => 'SMP', 'sort_order' => 2, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'SMA', 'name' => 'SMA', 'sort_order' => 3, 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('jenjangs')->insert($defaults);

        Schema::table('programs', function (Blueprint $table) {
            $table->foreignId('jenjang_id')->nullable()->after('name')->constrained()->nullOnDelete();
        });

        $jenjangMap = DB::table('jenjangs')->pluck('id', 'code');
        foreach (DB::table('programs')->get() as $program) {
            $jenjangId = $jenjangMap[$program->jenjang] ?? $jenjangMap->first();
            if ($jenjangId) {
                DB::table('programs')->where('id', $program->id)->update(['jenjang_id' => $jenjangId]);
            }
        }

        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn('jenjang');
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->string('jenjang')->nullable()->after('name');
        });

        $jenjangNames = DB::table('jenjangs')->pluck('code', 'id');
        foreach (DB::table('programs')->get() as $program) {
            DB::table('programs')->where('id', $program->id)->update([
                'jenjang' => $jenjangNames[$program->jenjang_id] ?? 'SMA',
            ]);
        }

        Schema::table('programs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('jenjang_id');
        });

        Schema::dropIfExists('jenjangs');
    }
};
