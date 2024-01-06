#!/bin/sh

# Function to install castor
install_castor() {
    curl "$1" -Lfso "$2/castor" && \
    chmod u+x "$2/castor" && \
    "$2/castor" --version || \
    (echo "Could not install castor. Is the target directory writable?" && (exit 1))
}

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
