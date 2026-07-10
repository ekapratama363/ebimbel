<?php

namespace App\Support;

use App\Models\User;

class UserPermissions
{
    public function __construct(private ?User $user) {}

    public function __invoke(string $permission): bool
    {
        return $this->user?->hasPermission($permission) ?? false;
    }

    public function any(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this($permission)) {
                return true;
            }
        }

        return false;
    }

    public function module(string $module, string $action = 'view'): bool
    {
        return $this($module.'.'.$action);
    }
}
