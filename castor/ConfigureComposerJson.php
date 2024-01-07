<?php

namespace Optional;

use Castor\Attribute\AsTask;

use function Castor\io;
use function Castor\run;

/**
 * @throws \JsonException
 */
#[AsTask(name: 'configure-composer', description: 'Configure composer.json!')]
function configureComposer(): void
{
    $io = io();
    io()->info('Configuring composer.json...\n');
    // we need to create an array that will be used in a foreach to configure composer.json
    $configuration = [
        'config' => [
            'sort-packages' => true,
            'allow-plugins' => [
                'composer/package-versions-deprecated' => true,
                'dealerdirect/phpcodesniffer-composer-installer' => true,
                'symfony/flex' => true,
            ],
        ],
    ];

    // we need to loop recursively through the array and call composer config for each key/value pair, previous key should be concatenated with the current one except the config key we need  to check if is_array to apply recursively
    extracted($configuration['config']);


    io()->info('Configuration complete.\n');
}

/**
 * @param array $configuration
 * @return void
 */
function extracted(array $configuration, $previousKey = null): void
{
    var_dump($configuration);

    foreach ($configuration as $key => $value) {
        if (is_array($value)) {
            extracted($value, $key);
        }
        if (null !== $previousKey) {
            run('composer config ' . $previousKey . '.' . $key . ' ' . $value);
        } else {
            var_dump($key);
            run('composer config ' . $key . ' ' . $value);
        }
        continue;
    }
}
