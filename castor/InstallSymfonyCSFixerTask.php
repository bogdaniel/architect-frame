<?php

namespace Symfony;

use Castor\Attribute\AsTask;

use function Castor\io;
use function Castor\run;

#[AsTask(name: 'cs-fixer', description: 'Install friendsofphp/php-cs-fixer!')]
function installCSFixer(): void
{
    $downloadPath = 'tools/php-cs-fixer'; // Specify the directory path here
    $io = io();

    // Create the download path if it does not exist
    if (!is_dir($downloadPath) && !mkdir($downloadPath, 0777, true) && !is_dir($downloadPath)) {
        $io->error("Failed to create directory: $downloadPath");
        return;
    }

    io()->info('Installing symfony/debug-pack and related packages...\n');
    $packages = [
        "friendsofphp/php-cs-fixer",
    ];

    $command = "composer require --dev --working-dir=tools/php-cs-fixer " . implode(' ', $packages);

    io()->info('Running command: $command\n');
    $output = run($command, tty: true);

    io()->info($output->getOutput());
    io()->info('Installation complete.\n');
}
