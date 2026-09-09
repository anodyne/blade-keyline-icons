# Blade Keyline Icons

<a href="https://github.com/anodyne/blade-keyline-icons/actions?query=workflow%3ATests"><img src="https://github.com/anodyne/blade-keyline-icons/workflows/Tests/badge.svg" alt="Tests"></a>
<a href="https://packagist.org/packages/anodyne/blade-keyline-icons"><img src="https://poser.pugx.org/anodyne/blade-keyline-icons/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/anodyne/blade-keyline-icons"><img src="https://poser.pugx.org/anodyne/blade-keyline-icons/d/total.svg" alt="Total Downloads"></a>

A package to easily make use of [Keyline Icons](https://keylineicons.com/) in your Laravel Blade views.

For a full list of available icons see [the SVG directory](svg/resources) or preview them on the [web](https://keylineicons.com/icons).

## Requirements

- PHP 8.1 or higher
- Laravel 9.0 or higher

## Installation

```bash
composer require anodyne/blade-keyline-icons
```

## Usage

Icons can be used a self-closing Blade components which will be compiled to SVG icons:

```blade
<x-keyline-abacus />
```

You can also pass classes to your icon components:

```blade
<x-keyline-abacus class="size-6 text-gray-500"/>
```

And even use inline styles:

```blade
<x-keyline-abacus style="color: #555"/>
```

### Raw SVG Icons

If you want to use the raw SVG icons as assets, you can publish them using:

```bash
php artisan vendor:publish --tag=blade-keyline-icons --force
```

Then use them in your views like:

```blade
<img src="{{ asset('vendor/blade-keyline-icons/abacus.svg') }}" width="10" height="10"/>
```

### Blade Icons

Blade Keyline Icons uses Blade Icons under the hood. Please refer to [the Blade Icons readme](https://github.com/blade-ui-kit/blade-icons) for additional functionality.

### Enum

Blade Keyline Icons includes an enum that maps every icon to an enum case. This allows for easily referencing specific icons from PHP. This is also helpful when using Keyline Icons with a system like [Filament](https://filamentphp.com/) for referencing icons.

```php
use Anodyne\KeylineIcons\KeylineIcon;

svg(KeylineIcon::AlertCircle->value);
```

## Compiling icons

Run `bun install --frozen-lockfile`, then `bun run build` to render every icon from
`@keyline-icons/react/dist`, optimize the SVGs with SVGO, and regenerate
`src/KeylineIcon.php`. Generated SVGs live in `svg/resources`; rebuilding removes
SVGs in that directory that are no longer exported by the source package.

| Source module | SVG suffix | Enum case suffix |
| --- | --- | --- |
| duotone | -duotone.svg | Duotone |
| fill | -fill.svg | Fill |
| index | -rounded.svg | (none) |
| sharp | -sharp.svg | Sharp |
| sharp-duotone | -sharp-duotone.svg | SharpDuotone |
| sharp-fill | -sharp-fill.svg | SharpFill |

For example, `KeylineIcon::AppCheck->value` is `keyline-app-check-rounded`.
Use `composer update-icons` to update the source package and rebuild.

## Changelog


Check out the [CHANGELOG](CHANGELOG.md) in this repository for all the recent changes.

## Maintainers

Blade Keyline Icons was developed by [Anodyne Productions](https://anodyne-productions.com).

## License

Blade Keyline Icons is open-sourced software licensed under [the MIT license](LICENSE.md).
