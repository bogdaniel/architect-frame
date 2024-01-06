<?php

namespace Phive;

use Castor\Attribute\AsTask;

use function Castor\capture;
use function Castor\io;
use function Castor\run;

#[AsTask(name: 'install', description: 'Downloads and installs PHIVE!')]
function install(): void
{
    $downloadPath = 'tmp/'; // Specify the directory path here
    $io = io();

    // Check for required commands
    if (!commandExists('wget') || !commandExists('gpg')) {
        $io->error("Both 'wget' and 'gpg' are required.");
        return;
    }

    // Create the download path if it does not exist
    if (!is_dir($downloadPath) && !mkdir($downloadPath, 0777, true) && !is_dir($downloadPath)) {
        $io->error("Failed to create directory: $downloadPath");
        return;
    }


    $commands = [
        ['wget', '-O', $downloadPath . 'phive.phar', 'https://phar.io/releases/phive.phar'],
        ['wget', '-O', $downloadPath . 'phive.phar.asc', 'https://phar.io/releases/phive.phar.asc'],
    ];

    $finishInstall = [
        ['rm', $downloadPath. 'phive.phar.asc'],
        ['chmod', '+x', $downloadPath . 'phive.phar'],
        ['mv', $downloadPath . 'phive.phar', '/usr/local/bin/phive'],
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
    $keyOutput = capture(['gpg', '--keyserver', $keyServer, '--recv-keys', $keyId]);
    $io->writeln("GPG key successfully imported.");
    $io->writeln($keyOutput);

    $io->writeln("Verifying PHIVE signature...");
    $verifyOutput = capture(['gpg', '--verify', $downloadPath . 'phive.phar.asc', $downloadPath. 'phive.phar']);
    $io->writeln($verifyOutput);
    if(str_contains($verifyOutput, 'Good signature from "phar.io <team@phar.io>"')) {
        $io->success("PHIVE signature verified.");
    } else {
        $io->error("PHIVE signature could not be verified.");
        return;
    }


    foreach ($finishInstall as $command) {
        $io->writeln("Running: " . implode(' ', $command));

        if (!run($command)) {
            $io->error("Command failed: " . implode(' ', $command));
            return;
        }
    }

    $io->success("PHIVE installation complete.");
}

// Function to check if a command exists
function commandExists(string $cmd): bool
{
    return capture(['which', $cmd], onFailure: false) !== '';
}
