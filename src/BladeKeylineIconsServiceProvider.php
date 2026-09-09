<?php

declare(strict_types=1);

namespace Anodyne\KeylineIcons;

use BladeUI\Icons\Factory;
use Illuminate\Support\ServiceProvider;

final class BladeKeylineIconsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->callAfterResolving(Factory::class, function (Factory $factory) {
            $factory->add('keyline', [
                'path' => __DIR__.'/../resources/svg',
                'prefix' => 'keyline',
                'class' => 'keyline-icon',
            ]);
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/svg' => public_path('vendor/blade-keyline-icons'),
            ], 'blade-keyline-icons');
        }
    }
}
