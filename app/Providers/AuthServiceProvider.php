<?php

namespace App\Providers;

use App\Models\Answer;
use App\Models\Channel;
use App\Models\Permission;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // User Roles and Permissions
        foreach (Permission::all() as $permission){
            Gate::define( $permission->title ,function () use($permission){
                return auth()->user()->hasRoleGate($permission->roles()->pluck('title'));
            });
        }

        // check if user can update or delete thread
        Gate::define('checkUserCanUpdateOrDeleteThread',function (User $user,Thread $thread){
            return $user->id == $thread->user_id || $user->hasRoleGate('Super_Admin');
        });

        // check if user can update or delete answer
        Gate::define('checkUserCanUpdateOrDeleteAnswer',function (User $user,Answer $answer){
            return $user->id == $answer->user_id || $user->hasRoleGate('Super_Admin');
        });
    }
}
