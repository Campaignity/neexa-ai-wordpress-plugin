#!/bin/bash
# Deploy plugin source to a local WordPress installation for testing.
# This keeps git/ out of wp-content/plugins — WordPress can delete the
# deployed copy without touching your source or git history.
#
# Usage:
#   ./local-deploy.sh /path/to/wordpress
#   ./local-deploy.sh /path/to/wordpress staging
#
# Set WP_PATH permanently by adding this to your shell profile:
#   export NEEXA_WP_PATH=/path/to/wordpress
set -e

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
WP_PATH="${1:-$NEEXA_WP_PATH}"
ENV="${2:-local}"

if [ -z "$WP_PATH" ]; then
    echo "ERROR: WordPress path required."
    echo "Usage: ./local-deploy.sh /path/to/wordpress [env]"
    echo "   or: export NEEXA_WP_PATH=/path/to/wordpress && ./local-deploy.sh"
    exit 1
fi

DEST="$WP_PATH/wp-content/plugins/neexa-ai"

echo "Deploying neexa-ai [$ENV] → $DEST"

rsync -a --delete \
    --exclude-from="$SCRIPT_DIR/.distignore" \
    "$SCRIPT_DIR/" "$DEST/"

# Bake the chosen environment config
cp "$DEST/includes/config-${ENV}.php" "$DEST/includes/config.php"

echo "Done. Plugin deployed to $DEST"
echo "Activate/reload it in your WordPress admin if needed."
