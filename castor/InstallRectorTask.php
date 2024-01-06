<?php

namespace Rector;

use Castor\Attribute\AsTask;

use function Castor\io;
use function Castor\run;

#[AsTask(name: 'install', description: 'Installs rector/rector and related packages as development dependencies!')]
function install(): void
{
    io()->info('Installing rector/rector and related packages...\n');
    $packages = [
        "rector/rector",
    ];

    $command = "composer require --dev " . implode(' ', $packages);

    io()->info('Running command: $command\n');
    $output = run($command, tty: true);

    io()->info($output->getOutput());
    io()->info('Installation complete.\n');
}

#[AsTask(name: 'run-dry', description: 'Runs rector/rector in dry mode!')]
function runDry()
{
    io()->info('Running rector/rector in dry mode...\n');
    $command = "vendor/bin/rector process src --dry-run";

    io()->info('Running command: $command\n');
    $output = run($command, tty: true);

    io()->info($output->getOutput());
    io()->info('Rector run complete.\n');
}

#[AsTask(name: 'init', description: 'Runs rector/rector in dry mode!')]
function init()
{
    io()->info('Running rector/rector in dry mode...\n');
    $command = "vendor/bin/rector process src --dry-run";

    io()->info('Running command: $command\n');
    $output = run($command, tty: true);

    io()->info($output->getOutput());
    io()->info('Rector run complete.\n');
}

#[AsTask(name: 'run-rector', description: 'Runs rector/rector')]
function runRector()
{
    io()->info('Running rector/rector...\n');
    $command = "vendor/bin/rector process src";

    io()->info('Running command: $command\n');
    $output = run($command, tty: true);

    io()->info($output->getOutput());
    io()->info('Rector run complete.\n');
}

// create a task that will modify rector.php to add symfony rules
#[AsTask(name: 'add-symfony-rules', description: 'Adds symfony rules to rector.php')]
function addSymfonyRules()
{
    io()->info('Adding symfony rules to rector.php...\n');
    $rectorConfig = file_get_contents('rector.php');

    $rectorConfig = str_replace(
        ["// register a single rule", "use Rector\Set\ValueObject\LevelSetList;", "// define sets of rules"],
        [
            "\$rectorConfig->symfonyContainerXml(__DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml');\n    // register a single rule",
            "use Rector\Set\ValueObject\LevelSetList;\nuse Rector\Symfony\Set\SymfonySetList;\n",
            "\$rectorConfig->sets([
        SymfonySetList::SYMFONY_62,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
    ]);\n// define sets of rules",
        ],
        $rectorConfig,
    );
    file_put_contents('rector.php', $rectorConfig);
    io()->info('Symfony rules added to rector.php.\n');
}
