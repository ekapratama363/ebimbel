#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
PUBLIC_VENDOR="$ROOT/public/vendor"

mkdir -p \
  "$PUBLIC_VENDOR/bootstrap/css" \
  "$PUBLIC_VENDOR/bootstrap/js" \
  "$PUBLIC_VENDOR/bootstrap-icons/fonts" \
  "$PUBLIC_VENDOR/fonts/plus-jakarta-sans"

cp node_modules/bootstrap/dist/css/bootstrap.min.css "$PUBLIC_VENDOR/bootstrap/css/"
cp node_modules/bootstrap/dist/js/bootstrap.bundle.min.js "$PUBLIC_VENDOR/bootstrap/js/"
cp node_modules/bootstrap-icons/font/bootstrap-icons.min.css "$PUBLIC_VENDOR/bootstrap-icons/"
cp node_modules/bootstrap-icons/font/fonts/* "$PUBLIC_VENDOR/bootstrap-icons/fonts/"

for weight in 400 500 600 700; do
  cp "node_modules/@fontsource/plus-jakarta-sans/files/plus-jakarta-sans-latin-${weight}-normal.woff2" \
    "$PUBLIC_VENDOR/fonts/plus-jakarta-sans/"
done

cp -R "$PUBLIC_VENDOR" "$ROOT/vendor"
