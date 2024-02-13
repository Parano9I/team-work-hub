<?php

namespace TeamWorkHub\Modules\Auth\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use TeamWorkHub\Modules\Auth\Contracts\AccountService;
use TeamWorkHub\Modules\Auth\Contracts\AuthService;
use TeamWorkHub\Modules\Auth\Contracts\AvatarService;
use TeamWorkHub\Modules\Auth\Contracts\RoleService;
use TeamWorkHub\Modules\Auth\Services\AccountServiceImpl;
use TeamWorkHub\Modules\Auth\Services\AuthServiceImpl;
use TeamWorkHub\Modules\Auth\Services\AvatarServiceImpl;
use TeamWorkHub\Modules\Auth\Services\RoleServiceImpl;

class AuthModuleServiceProvider extends ServiceProvider
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
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        AuthService::class    => AuthServiceImpl::class,
        AccountService::class => AccountServiceImpl::class,
        RoleService::class    => RoleServiceImpl::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }

    public function register(): void
    {
        $this->app->bind(AvatarService::class, function (Application $app) {
            $fileManager = $app->make(FilesystemManager::class);

            return new AvatarServiceImpl($fileManager->disk('avatars'));
        });
    }
}
