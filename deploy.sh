#!/usr/bin/env bash
# Compila el sitio y publica el resultado en la rama `deploy`, que es la que
# cPanel (Git Version Control) clona y copia a public_html.
#
#   ./deploy.sh
#
# Después, en cPanel: Git Version Control > Manage > Pull or Deploy >
# "Update from Remote" y luego "Deploy HEAD Commit".
set -euo pipefail

cd "$(dirname "$0")"

if [ -n "$(git status --porcelain -- source config.php config.production.php deploy)" ]; then
    echo "Aviso: hay cambios sin commit en el código; el despliegue los incluirá." >&2
fi

npm run build

REMOTE="$(git remote get-url origin)"
TMP="$(mktemp -d)"
trap 'rm -rf "$TMP"' EXIT

if git ls-remote --exit-code --heads origin deploy >/dev/null 2>&1; then
    git clone --quiet --branch deploy --single-branch "$REMOTE" "$TMP"
else
    git -C "$TMP" init --quiet --initial-branch=deploy
    git -C "$TMP" remote add origin "$REMOTE"
fi

rsync -a --delete --exclude='.git' build_production/ "$TMP/"
cp deploy/.htaccess deploy/.cpanel.yml deploy/enviar.php "$TMP/"

cd "$TMP"
git add -A

if git diff --cached --quiet; then
    echo "No hay cambios que desplegar."
    exit 0
fi

git commit --quiet -m "Despliegue de $(git -C "$OLDPWD" rev-parse --short HEAD)"
git push --quiet origin deploy
echo "Rama deploy actualizada. Ahora actualiza y despliega desde cPanel."
