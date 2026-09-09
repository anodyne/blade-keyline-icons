<?php

use Anodyne\KeylineIcons\KeylineIcon;
use Tests\TestCase;

uses(TestCase::class)->in('Feature');

dataset('icon variants', [
    'duotone' => [KeylineIcon::AppCheckDuotone, 'app-check-duotone'],
    'fill' => [KeylineIcon::AppCheckFill, 'app-check-fill'],
    'rounded' => [KeylineIcon::AppCheck, 'app-check-rounded'],
    'sharp' => [KeylineIcon::AppCheckSharp, 'app-check-sharp'],
    'sharp duotone' => [KeylineIcon::AppCheckSharpDuotone, 'app-check-sharp-duotone'],
    'sharp fill' => [KeylineIcon::AppCheckSharpFill, 'app-check-sharp-fill'],
]);
