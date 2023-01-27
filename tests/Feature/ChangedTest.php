<?php

use App\Contracts\PathsRepository;

it('determines changed files', function () {
    $paths = Mockery::mock(PathsRepository::class);

    $paths
        ->shouldReceive('changed')
        ->once()
        ->andReturn([
            base_path('tests/Fixtures/without-issues/file.php'),
        ]);

    $this->swap(PathsRepository::class, $paths);

    [$statusCode, $output] = run('default', ['--changed' => true]);

    expect($statusCode)->toBe(0)
        ->and($output)
        ->toContain('── Laravel', ' 1 file');
});

it('ignores the path argument', function () {
    $paths = Mockery::mock(PathsRepository::class);

    $paths
        ->shouldReceive('changed')
        ->once()
        ->andReturn([
            base_path('tests/Fixtures/without-issues/file.php'),
        ]);

    $this->swap(PathsRepository::class, $paths);

    [$statusCode, $output] = run('default', [
        '--changed' => true,
        'path' => base_path(),
    ]);

    expect($statusCode)->toBe(0)
        ->and($output)
        ->toContain('── Laravel', ' 1 file');
});

it('does not abort when there are no changed files', function () {
    $paths = Mockery::mock(PathsRepository::class);

    $paths
        ->shouldReceive('changed')
        ->once()
        ->andReturn([]);

    $this->swap(PathsRepository::class, $paths);

    [$statusCode, $output] = run('default', [
        '--changed' => true,
    ]);

    expect($statusCode)->toBe(0)
        ->and($output)
        ->toContain('── Laravel', ' 0 files');
});
