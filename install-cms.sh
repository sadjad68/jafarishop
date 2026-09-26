#!/bin/bash

 LOG_FILE="/var/log/install_script.log"
 exec > $LOG_FILE 2>&1

echo "checking variables";

echo "<-------------------------Database settings------------------------->"
docker exec cms-template_app-core_1 php artisan database:create $APP_NAME
docker exec cms-template_app-core_1 php artisan config:sites
echo "<-------------------------Database settings------------------------->"

echo "<-------------------------Nginx settings------------------------->"
echo "nginx set up"
NGINX_CONFIG_FILE="/etc/nginx/sites-enabled/${APP_NAME}"

if [ "$HAS_WWW" == "true" ]; then
    cat <<EOL > $NGINX_CONFIG_FILE
server {
    server_name www.${SITE_URL} ${SITE_URL};
    client_max_body_size 512M;
    location /uploads {
        alias /var/www/cms-template/public/sites/${APP_NAME}/uploads;
    }
    location / {
        if (\$request_uri ~ ^/(.*)/$) {
            return 301 $scheme://\$host/$1;
        }
        proxy_pass http://127.0.0.1:7070;
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto \$scheme;
        if (\$request_uri ~* "/index\.php(.*)") {
            return 301 \$scheme://\$host$1;
        }
    }
    if (\$host = www.${SITE_URL}) {
        return 301 https://${SITE_URL}\$request_uri;
    }
}
EOL
else
    cat <<EOL > $NGINX_CONFIG_FILE
server {
    server_name ${SITE_URL};
    client_max_body_size 512M;
    location /uploads {
        alias /var/www/cms-template/public/sites/${APP_NAME}/uploads;
    }
    location / {
        if (\$request_uri ~ ^/(.*)/$) {
            return 301 $scheme://\$host/$1;
        }
        proxy_pass http://127.0.0.1:7070;
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto \$scheme;
        if (\$request_uri ~* "/index\.php(.*)") {
            return 301 \$scheme://\$host$1;
        }
    }
}
EOL
fi

sudo chown root:root $NGINX_CONFIG_FILE
sudo chmod 644 $NGINX_CONFIG_FILE
nginx -s reload

echo "ssl config"

if [ "$HAS_WWW" == "true" ]; then
    expect << EOF
set timeout -1
spawn sudo certbot --nginx -d $SITE_URL -d www.$SITE_URL
expect "What would you like to do?"
send "2\r"
expect eof
EOF
else
   expect << EOF
set timeout -1
spawn sudo certbot --nginx -d $SITE_URL
expect "What would you like to do?"
send "2\r"
expect eof
EOF
fi

echo "<-------------------------Nginx settings------------------------->"

echo "Installation completed."

