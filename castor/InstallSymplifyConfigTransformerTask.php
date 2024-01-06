<?php

namespace Rector;

use Castor\Attribute\AsTask;

use function Castor\io;
use function Castor\run;

#[AsTask(name: 'config-transformer', description: 'Install symplify/config-transformer!')]
function installConfigTransformer(): void
{
    io()->info('Installing symfony/test-pack and related packages...\n');
    $packages = [
        "symplify/config-transformer",
    ];

    $command = "composer require --dev " . implode(' ', $packages);

    io()->info('Running command: $command\n');
    $output = run($command, tty: true);

    io()->info($output->getOutput());
    io()->info('Installation complete.\n');
}
