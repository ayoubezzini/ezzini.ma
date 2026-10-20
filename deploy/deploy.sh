#!/bin/bash
# Deploys ezzini.ma with zero downtime. Installed as /usr/local/bin/deploy-ezzini by deploy/provision.sh.
#
#   deploy-ezzini              deploy the latest commit of main
#   deploy-ezzini <commit>     deploy that commit (it must be on main)
#   deploy-ezzini --rollback   switch back to the previous release
#
# GitHub Actions calls it through a forced SSH command, so the commit arrives in $SSH_ORIGINAL_COMMAND.
#
# Layout: /var/www/ezzini.ma/{repository.git, releases/<date>-<sha>, current -> releases/…, .env, storage}
set -euo pipefail

APP=/var/www/ezzini.ma
REPOSITORY=$APP/repository.git
KEEP_RELEASES=5
PHP_FPM=php8.4-fpm
export COMPOSER_ALLOW_SUPERUSER=1

step() { echo -e "\n\033[1;34m==> $*\033[0m"; }
fail() { echo -e "\033[1;31m$*\033[0m" >&2; exit 1; }

as_www_data() {
    sudo -u www-data env HOME=/var/www/.chrome-home XDG_CONFIG_HOME=/var/www/.chrome-home/.config XDG_CACHE_HOME=/var/www/.chrome-home/.cache "$@"
}

switch_to() {
    ln -sfn "$1" "$APP/current.next"
    mv -Tf "$APP/current.next" "$APP/current"
    # OPcache never re-reads files (validate_timestamps=0): a reload makes the new release live.
    systemctl reload "$PHP_FPM"
}

healthy() {
    if [ -f /etc/letsencrypt/live/ezzini.ma/fullchain.pem ]; then
        curl -fsS -o /dev/null --max-time 10 --resolve ezzini.ma:443:127.0.0.1 https://ezzini.ma/up
    else
        curl -fsS -o /dev/null --max-time 10 --resolve ezzini.ma:80:127.0.0.1 http://ezzini.ma/up
    fi
}

previous_release() {
    local current
    current=$(readlink -f "$APP/current")

    find "$APP/releases" -mindepth 1 -maxdepth 1 -type d | sort | grep -B1 -Fx "$current" | grep -v -Fx "$current" || true
}

exec 9>/run/lock/deploy-ezzini.lock
flock -n 9 || fail "Another deploy is running."

# -p: keep going (and logging) even if the SSH connection drops mid-deploy.
exec > >(tee -p -a "$APP/storage/logs/deploy.log") 2>&1
echo -e "\n=== $(date -Is) ${SSH_ORIGINAL_COMMAND:+(GitHub Actions) }${SSH_ORIGINAL_COMMAND:-${1:-}}"

argument=${SSH_ORIGINAL_COMMAND:-${1:-}}

if [ "$argument" = "--rollback" ]; then
    previous=$(previous_release)
    [ -n "$previous" ] || fail "There is no previous release."

    switch_to "$previous"
    step "Rolled back to $(basename "$previous")"
    exit 0
fi

[[ -z "$argument" || "$argument" =~ ^[0-9a-f]{7,40}$ ]] || fail "Usage: deploy-ezzini [commit | --rollback]"

step "Fetching main"
git -C "$REPOSITORY" fetch --quiet --prune origin
commit=$(git -C "$REPOSITORY" rev-parse --verify "${argument:-origin/main}^{commit}")
git -C "$REPOSITORY" merge-base --is-ancestor "$commit" origin/main || fail "$commit is not on main."

release="$APP/releases/$(date +%Y%m%d-%H%M%S)-${commit:0:7}"
trap 'rm -rf "$release"; echo -e "\033[1;31mDeploy failed: the site still serves the previous release.\033[0m" >&2' ERR

step "Preparing $(basename "$release")"
mkdir -p "$release"
git -C "$REPOSITORY" archive "$commit" | tar -x -C "$release"
echo "$commit" > "$release/REVISION"
ln -s "$APP/.env" "$release/.env"
rm -rf "$release/storage"
ln -s "$APP/storage" "$release/storage"
cd "$release"

step "Composer"
composer install --no-dev --classmap-authoritative --no-interaction --no-progress

step "Assets"
npm ci --no-audit --no-fund --loglevel=error
npm run build
rm -rf node_modules

step "Documents (CV PDFs and social cards)"
install -d -o www-data -g www-data public/documents
chown -R www-data:www-data bootstrap/cache
as_www_data php artisan render-documents

step "Caches"
as_www_data php artisan optimize

step "Going live"
previous=$(readlink -f "$APP/current" 2>/dev/null || true)
switch_to "$release"
trap - ERR

if ! healthy; then
    [ -n "$previous" ] && switch_to "$previous"
    fail "$(basename "$release") failed its health check: switched back to $(basename "${previous:-nothing}")."
fi

step "Cleaning up"
find "$APP/releases" -mindepth 1 -maxdepth 1 -type d | sort | head -n -"$KEEP_RELEASES" | xargs -r rm -rf

step "Deployed ${commit:0:7}: $(git -C "$REPOSITORY" log -1 --format=%s "$commit")"
