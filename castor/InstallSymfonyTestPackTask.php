<?php
namespace Symfony;

use Castor\Attribute\AsTask;

use function Castor\io;
use function Castor\run;

#[AsTask(name: 'test-pack', description: 'Install symfony/test-pack!')]
function installTestPack(): void {
    io()->info('Installing symfony/test-pack and related packages...\n');
    $packages = [
        "symfony/test-pack",
    ];

    $command = "composer require --dev " . implode(' ', $packages);

    io()->info('Running command: $command\n');
    $output = run($command, tty: true);

    io()->info($output->getOutput());
    io()->info('Installation complete.\n');
}

