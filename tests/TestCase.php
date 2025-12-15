<?php

namespace LiraUi\Team\Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LiraUi\Team\TeamServiceProvider;
use LiraUi\Team\RouteServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use InteractsWithAuthentication;
    use RefreshDatabase;

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
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));

        $app['config']->set('route-attributes.enabled', true);

        $app['config']->set('auth.providers.users.model', User::class);
    }

    /**
     * Get package providers.
     */
    protected function getPackageProviders($app)
    {
        return [
            RouteServiceProvider::class,
            TeamServiceProvider::class,
        ];
    }
}
