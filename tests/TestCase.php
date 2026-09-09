<?php

namespace Tests;

use Anodyne\KeylineIcons\BladeKeylineIconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [BladeIconsServiceProvider::class, BladeKeylineIconsServiceProvider::class];
    }
}
