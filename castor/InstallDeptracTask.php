<?php

namespace DeepTrack;

use Castor\Attribute\AsTask;

use function Castor\io;
use function Castor\run;

#[AsTask(name: 'install', description: 'Installs Deptrac using Phive')]
function install(): void {
    $io = io();

    // Command to install Deptrac
    $command = ['phive', 'install', '-g', 'qossmic/deptrac'];

    $io->writeln("Installing Deptrac using Phive...");

    if (!run($command)) {
        $io->error("Failed to install Deptrac.");
        return;
    }

    $command = ['deptrack', 'init'];

    if (!run($command)) {
        $io->error("Failed to install init Deptrac.");
        return;
    }


    $io->success("Deptrac has been successfully installed.");
}

