<?php

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

it('rebuilds optimized artifacts reproducibly and removes only stale SVGs', function () {
    $root = dirname(__DIR__, 2);
    $bun = (new ExecutableFinder)->find('bun');
    expect($bun)->not->toBeNull('Install Bun and run bun install before running the compilation tests.');
    expect(is_dir($root.'/node_modules/@keyline-icons/react'))->toBeTrue('Run bun install first.');

    $temporary = sys_get_temp_dir().'/keyline-tests-'.bin2hex(random_bytes(8));
    $filesystem = new Filesystem;

    try {
        $filesystem->makeDirectory($temporary.'/bin', 0755, true);
        $filesystem->makeDirectory($temporary.'/resources/svg', 0755, true);
        $filesystem->copy($root.'/bin/compile-icons.mjs', $temporary.'/bin/compile-icons.mjs');
        symlink($root.'/node_modules', $temporary.'/node_modules');
        file_put_contents($temporary.'/resources/svg/stale-rounded.svg', '<svg/>');
        file_put_contents($temporary.'/resources/svg/keep.txt', 'keep me');

        $build = new Process([$bun, $temporary.'/bin/compile-icons.mjs'], $temporary, null, null, 120);
        $build->mustRun();

        expect(file_exists($temporary.'/resources/svg/stale-rounded.svg'))->toBeFalse()
            ->and(file_get_contents($temporary.'/resources/svg/keep.txt'))->toBe('keep me');
        $generated = glob($temporary.'/resources/svg/*.svg');
        $committed = glob($root.'/resources/svg/*.svg');
        expect(array_map('basename', $generated))->toBe(array_map('basename', $committed));
        foreach ($generated as $file) {
            expect(hash_file('sha256', $file))->toBe(hash_file('sha256', $root.'/resources/svg/'.basename($file)), basename($file));
        }
        expect(file_get_contents($temporary.'/src/KeylineIcon.php'))->toBe(file_get_contents($root.'/src/KeylineIcon.php'));

        // Check actual aggregate optimization rather than a version-specific byte count.
        expect(preg_match('/SVGO: (\d+) → (\d+) bytes/', $build->getOutput(), $sizes))->toBe(1);
        expect((int) $sizes[2])->toBeLessThan((int) $sizes[1]);

        $hashes = array_map(fn ($file) => hash_file('sha256', $file), $generated);
        $enum = file_get_contents($temporary.'/src/KeylineIcon.php');
        $build->mustRun();
        expect(array_map(fn ($file) => hash_file('sha256', $file), $generated))->toBe($hashes)
            ->and(file_get_contents($temporary.'/src/KeylineIcon.php'))->toBe($enum);
    } finally {
        // Unlink dependencies before recursively deleting the isolated build.
        if (is_link($temporary.'/node_modules')) {
            unlink($temporary.'/node_modules');
        }
        $filesystem->deleteDirectory($temporary);
    }
})->group('compilation');
