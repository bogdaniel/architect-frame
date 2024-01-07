<?php
namespace Optional;

use Castor\Attribute\AsTask;

use function Castor\io;
use function Castor\run;

#[AsTask(name: 'security-advisories', description: 'Install roave/security-advisories!')]
function installSecurityAdvisories(): void
{
    $io = io();
        io()->info('Installing roave/security-advisories and related packages...\n');
    $packages = [
        "roave/security-advisories:dev-latest",
    ];

    $command = "composer require --dev " . implode(' ', $packages);

    io()->info('Running command: $command\n');
    $output = run($command, tty: true);

    io()->info($output->getOutput());
    io()->info('Installation complete.\n');
}
