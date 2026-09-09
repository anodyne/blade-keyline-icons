<?php

use Anodyne\KeylineIcons\BladeKeylineIconsServiceProvider;
use Anodyne\KeylineIcons\KeylineIcon;
use BladeUI\Icons\Exceptions\SvgNotFound;
use BladeUI\Icons\Factory;
use BladeUI\Icons\IconsManifest;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

it('renders each variant through the SVG helper', function (KeylineIcon $icon, string $name) {
    $svg = svg($icon->value, 'size-6', ['data-test' => 'keyline']);

    expect($svg->name())->toBe($name)
        ->and($svg->contents())->toBe(trim(file_get_contents(__DIR__.'/../../resources/svg/'.$name.'.svg')))
        ->and($svg->toHtml())->toContain('keyline-icon', 'size-6', 'data-test="keyline"');
})->with('icon variants');

it('renders each variant as a Blade component', function (KeylineIcon $icon, string $name) {
    $html = Blade::render('<x-'.$icon->value.' class="size-8" style="color: red" />');

    expect($html)->toContain('<svg', 'keyline-icon', 'size-8', 'style="color: red"', 'viewBox="0 0 24 24"');
})->with('icon variants');

it('supports the Blade SVG directive with an enum value', function () {
    expect(Blade::render('@svg($icon->value)', ['icon' => KeylineIcon::AppCheck]))
        ->toContain('<svg', 'keyline-icon');
});

it('reports an unknown icon', function () {
    svg('keyline-this-icon-does-not-exist');
})->throws(SvgNotFound::class);

it('registers the icon set even when the factory was already resolved', function () {
    $app = clone $this->app;
    $factory = app(Factory::class);
    // Use a fresh factory so the package set has not already been registered.
    $fresh = new Factory(new Filesystem, app(IconsManifest::class));
    $app->instance(Factory::class, $fresh);
    (new BladeKeylineIconsServiceProvider($app))->register();

    expect($fresh->svg(KeylineIcon::AppCheck->value)->contents())->toBe($factory->svg(KeylineIcon::AppCheck->value)->contents());
});

it('publishes the generated SVG directory under the package tag', function () {
    $paths = ServiceProvider::pathsToPublish(BladeKeylineIconsServiceProvider::class, 'blade-keyline-icons');

    expect($paths)->toHaveCount(1);
    foreach ($paths as $source => $destination) {
        expect(realpath($source))->toBe(realpath(__DIR__.'/../../resources/svg'))
            ->and($destination)->toBe(public_path('vendor/blade-keyline-icons'))
            ->and(is_file($source.'/app-check-rounded.svg'))->toBeTrue();
    }
});
