#!/bin/sh

# Function to check if a command exists
command_exists() {
    type "$1" &>/dev/null
}

# Check if PHP is installed
if command_exists php; then
    echo "PHP is installed."
else
    echo "PHP is not installed. Please install PHP."
    exit 1
fi

# Check if Composer is installed
if command_exists composer; then
    echo "Composer is installed."
else
    echo "Composer is not installed. Please install Composer."
    exit 1
fi

# Install the required packages using Composer
composer require --dev \
    friendsofphp/php-cs-fixer \
    phpstan/extension-installer \
    phpstan/phpstan-symfony \
    symfony/browser-kit \
    phpunit/phpunit \
    symfony/css-selector \
    symfony/requirements-checker \
    symfony/stopwatch \
    symfony/web-profiler-bundle

echo "Development dependencies installed."
