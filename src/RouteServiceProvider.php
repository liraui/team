<?php

namespace LiraUi\Team;

use Illuminate\Support\ServiceProvider;
use Spatie\RouteAttributes\RouteRegistrar;

class RouteServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if (! $this->shouldRegisterRoutes()) {
            return;
        }

        $routeRegistrar = $this->app->make(RouteRegistrar::class, [
            $this->app->router,
        ]);

        $routeRegistrar->useBasePath(__DIR__.'/Http/Controllers');
        $routeRegistrar->useRootNamespace('LiraUi\Team\Http\Controllers');
        $routeRegistrar->registerDirectory(__DIR__.'/Http/Controllers');
    }

    private function shouldRegisterRoutes(): bool
    {
        if (! config('route-attributes.enabled')) {
            return false;
        }

        return true;
    }
}
