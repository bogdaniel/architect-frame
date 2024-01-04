#!/bin/sh

# Navigate to the repository directory
# cd /path/to/your/repository

# Add the original repository as a remote named 'upstream'
git remote add upstream https://github.com/dunglas/symfony-docker.git

# Fetch the latest changes from the original repository
git fetch upstream

printf "Upstream added and changes fetched."

