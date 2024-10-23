<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\LeaveApplication;
use App\Policies\LeaveApplicationPolicy;
use Illuminate\Support\Facades\Gate; // Import the Gate facade


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Register the leave application policy
        Gate::define('edit-leave', [LeaveApplicationPolicy::class, 'editLeave']);
    }
}
