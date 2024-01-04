#!/bin/sh

# Navigate to the repository directory
# cd /path/to/your/repository

# Checkout your main branch
git checkout main

# Merge changes from the upstream's main branch into your main branch
git merge upstream/main

# Push the updates to your fork on GitHub
git push origin main

echo "Updates merged into main and pushed to fork."

