<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\TestResponse;
use Inertia\Testing\AssertableInertia as Assert;

class TestServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (!$this->app->runningUnitTests()) {
            return;
        }

        Assert::macro('hasResource', function (string $key, JsonResource $resource) {
            $this->has($key);

            expect($this->prop($key))->toEqual($resource->response()->getData(true));

            return $this;
        });

        Assert::macro('hasPaginatedResource', function (string $key, ResourceCollection $resource) {
            $this->hasResource("{$key}.data", $resource);

            expect($this->prop($key))->toHaveKeys(['data', 'links', 'meta']);

            return $this;
        });

        TestResponse::macro('assertHasResource', function (string $key, JsonResource $resource) {
            return $this->assertInertia(fn(Assert $inertia) => $inertia->hasResource($key, $resource));
        });

        TestResponse::macro('assertHasPaginatedResource', function (string $key, ResourceCollection $resource) {
            return $this->assertInertia(fn(Assert $inertia) => $inertia->hasPaginatedResource($key, $resource));
        });

        TestResponse::macro('assertComponent', function (string $component) {
            return $this->assertInertia(fn(Assert $inertia) => $inertia->component($component, true));
        });
    }
}
