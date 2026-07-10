<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\SiteSetting;
use App\Support\UserPermissions;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('site', SiteSetting::current());
            $view->with('can', new UserPermissions(auth()->user()));
        });

        Gate::before(function ($user, string $ability) {
            if ($user?->isSuperAdmin()) {
                return true;
            }
        });

        if (\Illuminate\Support\Facades\Schema::hasTable('permissions')) {
            foreach (Permission::all() as $permission) {
                Gate::define($permission->key, fn ($user) => $user->hasPermission($permission->key));
            }
        }

        Blade::if('perm', function (string $permission) {
            return auth()->user()?->hasPermission($permission) ?? false;
        });
    }
}
