# ezzini.ma, HTTP only: used until the certificate exists (deploy/provision.sh then switches to ezzini.ma.ssl)
server {
    listen 80;
    listen [::]:80;
    server_name www.ezzini.ma;
    location ^~ /.well-known/acme-challenge/ { root /var/www/letsencrypt; }
    location / { return 301 http://ezzini.ma$request_uri; }
}

server {
    listen 80;
    listen [::]:80;
    server_name ezzini.ma;
    root /var/www/ezzini.ma/current/public;
    set $php_socket /run/php/php8.4-fpm-ezzini.sock;

    access_log /var/log/nginx/ezzini.ma.access.log;
    error_log  /var/log/nginx/ezzini.ma.error.log warn;

    include snippets/security-headers.conf;
    include snippets/laravel.conf;
}
