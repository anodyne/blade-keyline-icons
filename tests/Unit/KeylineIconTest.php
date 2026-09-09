<?php

use Anodyne\KeylineIcons\KeylineIcon;

it('maps the public enum cases to the expected icon names', function (KeylineIcon $icon, string $name) {
    expect($icon->value)->toBe('keyline-'.$name)
        ->and(KeylineIcon::from('keyline-'.$name))->toBe($icon);
})->with('icon variants');

it('has exactly one enum case for every generated SVG', function () {
    $values = array_map(fn (KeylineIcon $icon) => $icon->value, KeylineIcon::cases());
    $files = glob(__DIR__.'/../../resources/svg/*.svg');
    $expected = array_map(fn (string $file) => 'keyline-'.basename($file, '.svg'), $files);
    sort($values);
    sort($expected);

    expect($values)->not->toBeEmpty()->toBe($expected)
        ->and(array_unique($values))->toHaveCount(count($values));
});

it('ships valid scalable SVG documents without React attributes', function () {
    foreach (glob(__DIR__.'/../../resources/svg/*.svg') as $file) {
        $document = new DOMDocument;
        expect($document->load($file, LIBXML_NONET))->toBeTrue($file);
        $svg = $document->documentElement;
        expect($svg->localName)->toBe('svg', $file)
            ->and($svg->namespaceURI)->toBe('http://www.w3.org/2000/svg', $file)
            ->and($svg->getAttribute('viewBox'))->toBe('0 0 24 24', $file)
            ->and($svg->childNodes->length)->toBeGreaterThan(0, $file);
        expect(file_get_contents($file))->not->toMatch('/(?:strokeWidth|strokeLinecap|fillOpacity|className)=/');
    }
});

it('preserves duotone opacity and fill colors', function () {
    foreach (['duotone', 'sharp-duotone'] as $variant) {
        expect(file_get_contents(__DIR__.'/../../resources/svg/app-check-'.$variant.'.svg'))
            ->toContain('opacity=".4"', 'currentColor');
    }
    foreach (['fill', 'sharp-fill'] as $variant) {
        expect(file_get_contents(__DIR__.'/../../resources/svg/app-check-'.$variant.'.svg'))
            ->toContain('fill="currentColor"');
    }
});
