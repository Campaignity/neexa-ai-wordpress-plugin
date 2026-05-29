#!/bin/bash
# Deploy plugin source to the local WordPress Docker test environment.
# The Docker container mounts wp-test/plugins/neexa-ai/ — this script
# pushes a clean copy there. WordPress can delete that copy freely;
# your git source is never touched.
#
# Usage:
#   ./local-deploy.sh           → deploy with local config (default)
#   ./local-deploy.sh staging   → deploy with staging config
#
# Override the deploy path:
#   NEEXA_DEPLOY_PATH=/custom/path ./local-deploy.sh
set -e

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
ENV="${1:-local}"
DEPLOY_PATH="${NEEXA_DEPLOY_PATH:-/home/simon/work-station/repos/simon/wp-test/plugins/neexa-ai}"

echo "Deploying neexa-ai [$ENV] → $DEPLOY_PATH"

mkdir -p "$DEPLOY_PATH"

rsync -a --delete \
    --exclude-from="$SCRIPT_DIR/.distignore" \
    "$SCRIPT_DIR/" "$DEPLOY_PATH/"

cp "$DEPLOY_PATH/includes/config-${ENV}.php" "$DEPLOY_PATH/includes/config.php"

echo "Done."
