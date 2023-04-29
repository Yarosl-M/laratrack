<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $perms = Permission::get();
        $permNames = [];

        foreach ($perms as $p) {
            $permNames[] = $p->name;
        }

        foreach ($permNames as $p) {
            Gate::define($p, function(User $u) {
                return $u->hasPermission('superuser') || $u->hasPermission($p);
            });
        }
    }
}
