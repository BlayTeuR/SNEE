#!/bin/sh

# Génère le fichier du certificat depuis la variable Render
echo "$CA_CERT" > /etc/ssl/certs/ca-cert.pem
chmod 644 /etc/ssl/certs/ca-cert.pem

# Démarre les services
php-fpm -D
nginx -g "daemon off;"
