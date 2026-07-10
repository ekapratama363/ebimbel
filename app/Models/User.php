<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

#[Fillable(['name', 'email', 'password', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    private ?Collection $permissionCache = null;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role?->slug === 'super_admin';
    }

    public function permissionKeys(): Collection
    {
        if ($this->permissionCache !== null) {
            return $this->permissionCache;
        }

        if ($this->isSuperAdmin()) {
            return $this->permissionCache = Permission::query()
                ->get()
                ->map(fn (Permission $permission) => $permission->key);
        }

        $this->loadMissing('role.permissions');

        return $this->permissionCache = $this->role?->permissions
            ->map(fn (Permission $permission) => $permission->key) ?? collect();
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->permissionKeys()->contains($permission);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function firstAccessibleRoute(): ?string
    {
        foreach (config('permissions.modules', []) as $module => $meta) {
            if ($this->hasPermission($module.'.view')) {
                return $meta['route'];
            }
        }

        return null;
    }

    public function clearPermissionCache(): void
    {
        $this->permissionCache = null;
    }
}
