<?php

namespace LiraUi\Team\Tests;

use LiraUi\Team\TeamServiceProvider;
use LiraUi\Team\RouteServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * Define environment setup.
     */
    protected function setUp(): void
    {
        parent::setUp();
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
