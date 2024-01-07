<?php
namespace Symfony;

use Castor\Attribute\AsTask;

use function Castor\io;
use function Castor\run;

#[AsTask(name: 'debug-pack', description: 'Install symfony/debug-pack!')]
function installDebugPack(): void {
    io()->info('Installing symfony/debug-pack and related packages...\n');
    $packages = [
        "symfony/debug-pack",
        "symfony/thanks",
    ];

    $command = "composer require --dev " . implode(' ', $packages);

    io()->info('Running command: $command\n');
    $output = run($command, tty: true);

    io()->info($output->getOutput());
    io()->info('Installation complete.\n');
}
