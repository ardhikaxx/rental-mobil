<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
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
        Gate::define('manage-users', fn (User $user) => $user->hasRole(UserRole::SuperAdmin));
        Gate::define('manage-settings', fn (User $user) => $user->hasRole(UserRole::SuperAdmin));
        Gate::define('view-reports', fn (User $user) => $user->hasRole(UserRole::SuperAdmin));
        Gate::define('view-audit-log', fn (User $user) => $user->hasRole(UserRole::SuperAdmin));
        Gate::define('manage-vehicles', fn (User $user) => $user->hasRole(UserRole::SuperAdmin));
        Gate::define('manage-customers', fn (User $user) => $user->hasRole(UserRole::SuperAdmin, UserRole::Admin));
        Gate::define('manage-transactions', fn (User $user) => $user->hasRole(UserRole::SuperAdmin, UserRole::Admin));
        Gate::define('manage-payments', fn (User $user) => $user->hasRole(UserRole::SuperAdmin, UserRole::Admin));
        Gate::define('view-calendar', fn (User $user) => $user->hasRole(UserRole::SuperAdmin, UserRole::Admin));
        Gate::define('process-handover', fn (User $user) => $user->hasRole(UserRole::SuperAdmin, UserRole::Admin, UserRole::Staff));
        Gate::define('process-return', fn (User $user) => $user->hasRole(UserRole::SuperAdmin, UserRole::Admin, UserRole::Staff));
        Gate::define('create-maintenance', fn (User $user) => $user->hasRole(UserRole::SuperAdmin, UserRole::Staff));
        Gate::define('manage-maintenance', fn (User $user) => $user->hasRole(UserRole::SuperAdmin));
        Gate::define('update-vehicle-status', fn (User $user) => $user->hasRole(UserRole::SuperAdmin, UserRole::Staff));
        Gate::define('edit-vehicle', fn (User $user) => $user->hasRole(UserRole::SuperAdmin));
    }
}
