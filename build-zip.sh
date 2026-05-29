#!/bin/bash
# Build a distributable zip from the current source.
# Usage:
#   ./build-zip.sh          → production zip  (config-prod.php baked in)
#   ./build-zip.sh staging  → staging zip
set -e

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
ENV="${1:-prod}"
VERSION=$(grep -m1 "NEEXA_AI_VERSION'" "$SCRIPT_DIR/neexa-ai.php" | grep -oP "[\d.]+")
OUTPUT="$SCRIPT_DIR/neexa-ai-${VERSION}-${ENV}.zip"
TMP_DIR=$(mktemp -d)
PLUGIN_DIR="$TMP_DIR/neexa-ai"

echo "Building neexa-ai v${VERSION} [${ENV}]..."

rsync -a \
    --exclude-from="$SCRIPT_DIR/.distignore" \
    "$SCRIPT_DIR/" "$PLUGIN_DIR/"

# Bake the chosen environment config in as config.php
cp "$PLUGIN_DIR/includes/config-${ENV}.php" "$PLUGIN_DIR/includes/config.php"

cd "$TMP_DIR"
zip -r "$OUTPUT" neexa-ai/ -x "*.DS_Store"
rm -rf "$TMP_DIR"

echo "Created: $OUTPUT"
