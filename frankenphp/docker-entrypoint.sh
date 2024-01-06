#!/bin/sh
set -e

# Function to install castor
install_castor() {
    curl "$1" -Lfso "$2/castor" && \
    chmod u+x "$2/castor" && \
    "$2/castor" --version || \
    (echo "Could not install castor. Is the target directory writable?" && (exit 1))
}


if [ "$1" = 'frankenphp' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
	# Install the project the first time PHP is started
	# After the installation, the following block can be deleted
	if [ ! -f composer.json ]; then
		rm -Rf tmp/
		composer create-project "symfony/skeleton $SYMFONY_VERSION" tmp --stability="$STABILITY" --prefer-dist --no-progress --no-interaction --no-install

		cd tmp
		cp -Rp . ..
		cd -
		rm -Rf tmp/

		composer require "php:>=$PHP_VERSION" runtime/frankenphp-symfony
		composer config --json extra.symfony.docker 'true'

		if grep -q ^DATABASE_URL= .env; then
			echo "To finish the installation please press Ctrl+C to stop Docker Compose and run: docker compose up --build -d --wait"
			sleep infinity
		fi

		# Define the target directory
		TARGET_DIR="$HOME/.local/bin"

		# Check if the target directory exists, create it if it doesn't
		if [ ! -d "$TARGET_DIR" ]; then
			echo "Creating directory $TARGET_DIR..."
			mkdir -p "$TARGET_DIR"
		fi

		# Detect the operating system
		OS="$(uname -s)"
		case "$OS" in
			Linux*|Darwin*)
				# Use /usr/local/bin if writable, else use $HOME/.local/bin
				if [ -w "/usr/local/bin" ]; then
					TARGET_DIR="/usr/local/bin"
				else
					TARGET_DIR="$HOME/.local/bin"
					# Add $HOME/.local/bin to PATH if it's not already there
					case ":$PATH:" in
						*":$HOME/.local/bin:"*) ;;
						*) PATH="$HOME/.local/bin:$PATH" ;;
					esac
				fi
				URL="https://github.com/jolicode/castor/releases/latest/download/castor.$(echo $OS | tr '[:upper:]' '[:lower:]')-amd64.phar"
				;;
			CYGWIN*|MINGW32*|MSYS*|MINGW*)
				# For Windows, specify the directory in PATH where you want to place the executable
				TARGET_DIR="/c/some_directory_in_path"  # Replace with an actual directory in PATH
				URL="https://github.com/jolicode/castor/releases/latest/download/castor.windows-amd64.phar"
				curl.exe "$URL" -Lso "$TARGET_DIR/castor"
				exit 0
				;;
			*)
				echo "Unknown operating system."
				exit 1
				;;
		esac


		# Install castor
		install_castor "$URL" "$TARGET_DIR"


	fi

	if [ -z "$(ls -A 'vendor/' 2>/dev/null)" ]; then
		composer install --prefer-dist --no-progress --no-interaction
	fi

	if grep -q ^DATABASE_URL= .env; then
		echo "Waiting for database to be ready..."
		ATTEMPTS_LEFT_TO_REACH_DATABASE=60
		until [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ] || DATABASE_ERROR=$(php bin/console dbal:run-sql -q "SELECT 1" 2>&1); do
			if [ $? -eq 255 ]; then
				# If the Doctrine command exits with 255, an unrecoverable error occurred
				ATTEMPTS_LEFT_TO_REACH_DATABASE=0
				break
			fi
			sleep 1
			ATTEMPTS_LEFT_TO_REACH_DATABASE=$((ATTEMPTS_LEFT_TO_REACH_DATABASE - 1))
			echo "Still waiting for database to be ready... Or maybe the database is not reachable. $ATTEMPTS_LEFT_TO_REACH_DATABASE attempts left."
		done

		if [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ]; then
			echo "The database is not up or not reachable:"
			echo "$DATABASE_ERROR"
			exit 1
		else
			echo "The database is now ready and reachable"
		fi

		if [ "$( find ./migrations -iname '*.php' -print -quit )" ]; then
			php bin/console doctrine:migrations:migrate --no-interaction
		fi
	fi

	setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var
	setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var
fi

exec docker-php-entrypoint "$@"
