<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\TestResponse;
use Inertia\Testing\AssertableInertia;

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
        if ($this->app->runningUnitTests()) {
            AssertableInertia::macro('hasResource', function (string $key, \Illuminate\Http\Resources\Json\JsonResource $resource): self {
                $this->has($key);
                $jsonResource = $resource->response()->getData(true);

                expect($this->prop($key))
                    ->toEqual($jsonResource);

                return $this;
            });

            AssertableInertia::macro('hasPaginatedResource', function (string $key, \Illuminate\Http\Resources\Json\ResourceCollection $resource): self {
                $this->hasResource("$key.data", $resource);

                expect($this->prop($key))
                    ->toHaveKeys(['data', 'links', 'meta']);

                return $this;
            });

            TestResponse::macro('assertHasResource', function (string $key, JsonResource $resource): self {
                return $this->assertInertia(fn(AssertableInertia $inertia) => $inertia->hasResource($key, $resource));
            });

            TestResponse::macro('assertHasPaginatedResource', function (string $key, JsonResource $resource): self {
                return $this->assertInertia(fn(AssertableInertia $inertia) => $inertia->hasPaginatedResource($key, $resource));
            });

            TestResponse::macro('assertComponent', function (string $key): self {
                return $this->assertInertia(fn(AssertableInertia $inertia) => $inertia->component($key,true));
            });
        }

    }
}
