<?php

namespace DepTrac;

use Castor\Attribute\AsTask;

use function Castor\capture;
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

    $captured = capture(['deptrac', 'init']);

    $io->success($captured);
    $io->success("Deptrac has been successfully installed.");
}
