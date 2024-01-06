<?php
namespace PhpStan;

use Castor\Attribute\AsTask;

use function Castor\io;
use function Castor\run;

#[AsTask(name: 'install', description: 'Installs PHPStan and related packages as development dependencies!')]
function install(): void {
    io()->info('Installing PHPStan and related packages...\n');
    $packages = [
        "phpstan/phpstan",
        "phpstan/phpstan-doctrine",
        "phpstan/phpstan-phpunit",
        "phpstan/phpstan-strict-rules",
        "phpstan/phpstan-symfony",
        "phpstan/phpstan-webmozart-assert"
    ];

    $command = "composer require --dev " . implode(' ', $packages);

    io()->info('Running command: $command\n');
    $output = run($command, tty: true);

    io()->info($output->getOutput());
    io()->info('Installation complete.\n');
}

