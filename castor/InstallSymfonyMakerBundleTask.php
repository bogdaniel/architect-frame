<?php
namespace Symfony;

use Castor\Attribute\AsTask;

use function Castor\io;
use function Castor\run;

#[AsTask(name: 'maker-bundle', description: 'Install symfony/maker-bundle!')]
function installMakerBundle(): void {
    io()->info('Installing symfony/maker-bundle and related packages...\n');
    $packages = [
        "symfony/maker-bundle",
    ];

    $command = "composer require --dev " . implode(' ', $packages);

    io()->info('Running command: $command\n');
    $output = run($command, tty: true);

    io()->info($output->getOutput());
    io()->info('Installation complete.\n');
}
