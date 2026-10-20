#!/bin/bash
# Sets up ezzini.ma on the server. Idempotent: run it again whenever something in deploy/ changes.
# Run as root from a copy of this folder:
#
#   scp -r deploy root@<server>:/tmp/ezzini-deploy && ssh root@<server> bash /tmp/ezzini-deploy/provision.sh [options]
#
#   --certificate            issue the Let's Encrypt certificate (ezzini.ma + www) and switch nginx to HTTPS
#   --authorize-key "<key>"  allow this public key (GitHub Actions) to run deploy-ezzini, and nothing else
set -euo pipefail

HERE=$(cd "$(dirname "$0")" && pwd)
APP=/var/www/ezzini.ma
REMOTE=git@github-ezzini:ayoubezzini/ezzini.ma.git
CERTIFICATE=/etc/letsencrypt/live/ezzini.ma/fullchain.pem

step() { echo -e "\n\033[1;34m==> $*\033[0m"; }

issue_certificate=false
authorized_key=""

while [ $# -gt 0 ]; do
    case "$1" in
        --certificate) issue_certificate=true ;;
        --authorize-key) authorized_key=$2; shift ;;
        *) echo "Unknown option: $1" >&2; exit 1 ;;
    esac
    shift
done

step "Folders"
install -d "$APP" "$APP/releases"
install -d -o www-data -g www-data \
    "$APP/storage" "$APP/storage/app" "$APP/storage/app/public" "$APP/storage/framework" \
    "$APP/storage/framework/cache" "$APP/storage/framework/cache/data" "$APP/storage/framework/views" "$APP/storage/logs"

step "Repository"
if [ -d "$APP/repository.git" ]; then
    echo "Already cloned."
elif git ls-remote "$REMOTE" >/dev/null 2>&1; then
    git clone --bare --quiet "$REMOTE" "$APP/repository.git"
    git -C "$APP/repository.git" config remote.origin.fetch '+refs/heads/*:refs/remotes/origin/*'
    git -C "$APP/repository.git" fetch --quiet origin
    echo "Cloned $REMOTE."
else
    echo -e "\033[1;33m$REMOTE isn't reachable yet (push the repository and add the server's id_ezzini.pub as a deploy key), then run this again.\033[0m"
fi

step "Environment"
if [ -f "$APP/.env" ]; then
    echo "Keeping the existing $APP/.env."
else
    install -m 640 -o root -g www-data "$HERE/production.env" "$APP/.env"
    sed -i "s|^APP_KEY=$|APP_KEY=base64:$(openssl rand -base64 32)|" "$APP/.env"
    echo -e "\033[1;33mCreated $APP/.env: fill in MAIL_USERNAME, MAIL_PASSWORD, TURNSTILE_SITE_KEY and TURNSTILE_SECRET_KEY.\033[0m"
fi

step "Deploy command"
install -m 755 "$HERE/deploy.sh" /usr/local/bin/deploy-ezzini
echo "/usr/local/bin/deploy-ezzini"

step "PHP-FPM pool"
install -m 644 "$HERE/php-fpm/ezzini.conf" /etc/php/8.4/fpm/pool.d/ezzini.conf
php-fpm8.4 -t
systemctl reload php8.4-fpm

step "Nginx"
install -m 644 "$HERE/nginx/ezzini.ma" "$HERE/nginx/ezzini.ma.ssl" /etc/nginx/sites-available/

if $issue_certificate && [ ! -f "$CERTIFICATE" ]; then
    ln -sfn /etc/nginx/sites-available/ezzini.ma /etc/nginx/sites-enabled/ezzini.ma
    nginx -t && systemctl reload nginx
    certbot certonly --webroot -w /var/www/letsencrypt -d ezzini.ma -d www.ezzini.ma \
        --register-unsafely-without-email --agree-tos --no-eff-email -n
fi

site=ezzini.ma
[ -f "$CERTIFICATE" ] && site=ezzini.ma.ssl
ln -sfn "/etc/nginx/sites-available/$site" /etc/nginx/sites-enabled/ezzini.ma
nginx -t && systemctl reload nginx
echo "Enabled $site."

if [ -n "$authorized_key" ]; then
    step "GitHub Actions key"
    [[ "$authorized_key" =~ ^ssh-(ed25519|rsa)\ [A-Za-z0-9+/=]+(\ .*)?$ ]] || { echo "That doesn't look like a public key." >&2; exit 1; }
    key_body=$(awk '{print $2}' <<< "$authorized_key")

    if grep -qF "$key_body" /root/.ssh/authorized_keys; then
        echo "Already authorized."
    else
        echo "command=\"/usr/local/bin/deploy-ezzini\",restrict $authorized_key" >> /root/.ssh/authorized_keys
        echo "Authorized: this key can only run deploy-ezzini."
    fi
fi

step "Done"
echo "Deploy with: deploy-ezzini   (or push to main)"
