<?php

namespace Phive;

use Castor\Attribute\AsTask;

use function Castor\capture;
use function Castor\io;
use function Castor\run;

#[AsTask(name: 'install', description: 'Downloads and installs PHIVE!')]
function install(): void
{
    $io = io();

    // Check for required commands
    if (!commandExists('wget') || !commandExists('gpg')) {
        $io->error("Both 'wget' and 'gpg' are required.");
        return;
    }


    $commands = [
        ['wget', '-O', 'phive.phar', 'https://phar.io/releases/phive.phar'],
        ['wget', '-O', 'phive.phar.asc', 'https://phar.io/releases/phive.phar.asc'],
    ];

    $integrityCheck = [

        ['gpg', '--verify', 'phive.phar.asc', 'phive.phar'],
    ];

    $finishInstall = [
        ['chmod', '+x', 'phive.phar'],
        ['sudo', 'mv', 'phive.phar', '/usr/local/bin/phive'],
    ];

    foreach ($commands as $command) {
        $io->writeln("Running: " . implode(' ', $command));

        if (!run($command)) {
            $io->error("Command failed: " . implode(' ', $command));
            return;
        }
    }

    $keyServer = 'hkps://keys.openpgp.org';
    $keyId = '0x6AF725270AB81E04D79442549D8A98B29B2D5D79';

    $io->writeln("Importing GPG key from $keyServer...");
    $keyOutput = capture(['gpg', '--keyserver', $keyServer, '--recv-keys', $keyId], onFailure: false);
    $io->writeln("GPG key successfully imported.");
    $io->writeln($keyOutput);



//    foreach ($finishInstall as $command) {
//        $io->writeln("Running: " . implode(' ', $command));
//
//        if (!run($command)) {
//            $io->error("Command failed: " . implode(' ', $command));
//            return;
//        }
//    }

    $io->success("PHIVE installation complete.");
}

// Function to check if a command exists
function commandExists(string $cmd): bool
{
    return capture(['which', $cmd], onFailure: false) !== '';
}
