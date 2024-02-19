<?php

namespace TeamWorkHub\Modules\Invitation\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use TeamWorkHub\Modules\Invitation\Contracts\InvitationService;
use TeamWorkHub\Modules\Invitation\Services\InvitationServiceImpl;

class InvitationModuleServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [];

    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        InvitationService::class => InvitationServiceImpl::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'invitation_module');
    }

    public function register(): void
    {
    }
}
