<?php

namespace LiraUi\Team\Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LiraUi\Team\RouteServiceProvider;
use LiraUi\Team\TeamServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Permission\PermissionServiceProvider;

abstract class TestCase extends Orchestra
{
    use InteractsWithAuthentication;
    use RefreshDatabase;

    /**
     * The team.
     *
     * @var \LiraUi\Team\Models\Team
     */
    protected $team;

    /**
     * Define environment setup.
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Define database migrations.
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $permissionConfig = require base_path('../../../../vendor/spatie/laravel-permission/config/permission.php');

        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));

        $app['config']->set('route-attributes.enabled', true);

        $app['config']->set('auth.providers.users.model', User::class);

        $app['config']->set('permission', $permissionConfig);

        $app['config']->set('permission.teams', true);
    }

    /**
     * Get package providers.
     */
    protected function getPackageProviders($app)
    {
        return [
            RouteServiceProvider::class,
            TeamServiceProvider::class,
            PermissionServiceProvider::class,
        ];
    }
}
