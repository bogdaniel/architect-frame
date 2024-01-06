#!/bin/sh

# List of files and directories to clean up, space-separated
FILES_AND_DIRS="
.env
.env.test
.gitignore
.php-cs-fixer.dist.php
LICENSE
bin
composer.json
composer.lock
config
phpstan.dist.neon
phpunit.xml.dist
public/index.php
src
symfony.lock
templates
tests
vendor
castor.php
.castor.stub.php
"

# Loop through the list and remove each file/directory if it exists
for item in $FILES_AND_DIRS; do
    if [ -e "$item" ] || [ -d "$item" ]; then
        echo "Removing $item..."
        rm -rf "$item"
    else
        echo "$item does not exist, skipping..."
    fi
done

echo "Cleanup completed."
