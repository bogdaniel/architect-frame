#!/bin/sh

# Get the absolute path to the script's directory
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" &>/dev/null && pwd)"
PROJECT_ROOT="$(dirname "$0")/.."

# Define the paths of .gitignore and .gitignore-editor relative to the script's directory
# Assuming the script is located in a 'scripts' folder in the project root
GITIGNORE="${SCRIPT_DIR}/../.gitignore"
GITIGNORE_EDITOR="${PROJECT_ROOT}/files/.gitignore-editor"

# Check if both .gitignore and .gitignore-editor exist
if [ -f "${GITIGNORE}" ] && [ -f "${GITIGNORE_EDITOR}" ]; then
    # Append the contents of .gitignore-editor to .gitignore
    cat "${GITIGNORE_EDITOR}" >> "${GITIGNORE}"
    echo "Contents of .gitignore-editor appended to .gitignore."
else
    # Display an error message if either file is missing
    echo "Error: Either .gitignore or .gitignore-editor does not exist."
fi
