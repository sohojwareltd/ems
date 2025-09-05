<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Register your policies here
        // User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Register gates for each permission
        $this->registerPermissionGates();

        // Register admin gate
        Gate::define('admin', function (User $user) {
            return $user->isAdmin();
        });

        // Register super admin gate (can do everything)
        Gate::define('super-admin', function (User $user) {
            return $user->isAdmin() && $user->hasPermission('super-admin');
        });
    }

    /**
     * Register gates for all permissions.
     */
    protected function registerPermissionGates(): void
    {
        try {
            // Get all permissions from database
            $permissions = Permission::all();

            foreach ($permissions as $permission) {
                Gate::define($permission->name, function (User $user) use ($permission) {
                    // Super admin can do everything
                    if ($user->hasPermission('super-admin')) {
                        return true;
                    }

                    return $user->hasPermission($permission->name);
                });
            }
        } catch (\Exception $e) {
            // Table might not exist yet during migrations
            // This is normal during the initial setup
        }
    }

    /**
     * Register a new permission gate dynamically.
     */
    public static function registerPermissionGate(string $permissionName): void
    {
        Gate::define($permissionName, function (User $user) use ($permissionName) {
            // Super admin can do everything
            if ($user->hasPermission('super-admin')) {
                return true;
            }

            return $user->hasPermission($permissionName);
        });
    }
}
