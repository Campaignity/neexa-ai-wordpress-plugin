#!/bin/bash
# Publish the current source to WordPress.org SVN.
#
# Usage:
#   ./release.sh           — uses the git tag on HEAD
#   ./release.sh 2.1.1     — targets a specific version
#
# To fix a small issue and re-publish the same version:
#   git add . && git commit -m "fix: ..."   (hook syncs trunk)
#   git tag -f 2.1.1                        (move tag to new commit)
#   ./release.sh                            (re-publish)
set -e

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
SVN_DIR="$SCRIPT_DIR/../svn"
SVN_TRUNK="$SVN_DIR/trunk"

# Resolve version: argument → tag on HEAD → error
if [ -n "$1" ]; then
    VERSION="$1"
else
    VERSION=$(git -C "$SCRIPT_DIR" tag --points-at HEAD | head -n1)
fi

if [ -z "$VERSION" ]; then
    echo "ERROR: no version specified and no git tag on HEAD."
    echo "Usage: ./release.sh 2.1.1"
    echo "   or: git tag 2.1.1 && ./release.sh"
    exit 1
fi

if [ ! -d "$SVN_TRUNK" ]; then
    echo "ERROR: svn/trunk not found at $SVN_TRUNK"
    exit 1
fi

SVN_TAG_DIR="$SVN_DIR/tags/$VERSION"

echo "Publishing neexa-ai $VERSION to WordPress.org SVN..."

# ---- Ensure trunk is up to date ----
echo "[1/3] Syncing trunk..."
rsync -a --delete \
    --exclude-from="$SCRIPT_DIR/.distignore" \
    "$SCRIPT_DIR/" "$SVN_TRUNK/"

svn add --force "$SVN_TRUNK" > /dev/null 2>&1
svn status "$SVN_TRUNK" | grep '^!' | awk '{print $2}' | xargs -r svn delete --force > /dev/null 2>&1

# ---- Create or refresh the SVN tag ----
echo "[2/3] Preparing tag $VERSION..."
if [ -d "$SVN_TAG_DIR" ]; then
    # Tag directory exists — update it in place
    rsync -a --delete \
        --exclude-from="$SCRIPT_DIR/.distignore" \
        "$SCRIPT_DIR/" "$SVN_TAG_DIR/"
    svn add --force "$SVN_TAG_DIR" > /dev/null 2>&1
    svn status "$SVN_TAG_DIR" | grep '^!' | awk '{print $2}' | xargs -r svn delete --force > /dev/null 2>&1
else
    svn copy "$SVN_TRUNK" "$SVN_TAG_DIR"
fi

# ---- Commit to SVN ----
echo "[3/3] Committing to SVN..."
svn commit "$SVN_TRUNK" "$SVN_TAG_DIR" -m "Release $VERSION"

echo ""
echo "Done. Version $VERSION is live on WordPress.org."
