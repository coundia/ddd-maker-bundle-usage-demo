#!/bin/bash

# Configuration
PROJECT_NAME="ddd_demo"
DOMAIN="groupe2cs.com"
DB_NAME="${PROJECT_NAME}_db"
DB_USER="${PROJECT_NAME}_user"
DB_PASS="password_secure"
PROJECT_DIR="/var/www/$PROJECT_NAME"
GIT_REPO="git@github.com:coundia/ddd-maker-bundle-usage-demo.git"

echo "🚀 Updating system packages..."
sudo apt update && sudo apt upgrade -y

echo "📦 Installing required packages..."
sudo apt install -y curl software-properties-common unzip git

echo "🛠️ Installing PHP 8.3 and necessary extensions..."
sudo apt install -y php8.3 php8.3-cli php8.3-common php8.3-mbstring php8.3-xml php8.3-bcmath php8.3-intl php8.3-zip php8.3-curl php8.3-mysql php8.3-fpm php8.3-gd

echo "✅ Verifying PHP installation..."
php -v

echo "🎵 Installing Composer..."
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

echo "✅ Verifying Composer installation..."
composer -V

echo "💾 Installing and configuring MySQL..."
sudo apt install -y mysql-server
sudo systemctl start mysql
sudo systemctl enable mysql

echo "🔧 Running MySQL secure installation (requires interaction)..."
sudo mysql_secure_installation

echo "🔎 Creating database and user for $PROJECT_NAME..."
sudo mysql -u root -p -e "
    CREATE DATABASE $DB_NAME;
    CREATE USER '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';
    GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';
    FLUSH PRIVILEGES;"

echo "🌐 Installing Nginx..."
sudo apt install -y nginx
sudo systemctl start nginx
sudo systemctl enable nginx

# Remove old configurations if they exist
sudo rm -f /etc/nginx/sites-available/$PROJECT_NAME
sudo rm -f /etc/nginx/sites-enabled/$PROJECT_NAME

echo "🔧 Configuring Nginx for $PROJECT_NAME..."
sudo bash -c "cat > /etc/nginx/sites-available/$PROJECT_NAME <<EOF
server {
    listen 80;
    server_name $DOMAIN www.$DOMAIN;
    root $PROJECT_DIR/public;
    index index.php index.html;

    location / {
        try_files \\\$uri /index.php?\\\$query_string;
    }

    location ~ ^/index\.php(/|$) {
        include fastcgi_params;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \\\$realpath_root\\\$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT \\\$realpath_root;
        internal;
    }

    location ~ \.php$ {
        return 404;
    }

    error_log /var/log/nginx/$PROJECT_NAME.error.log;
    access_log /var/log/nginx/$PROJECT_NAME.access.log;

    client_max_body_size 100M;
}
EOF"

echo "✅ Checking Nginx configuration..."
sudo nginx -t

echo "🔗 Enabling Nginx configuration..."
sudo ln -s /etc/nginx/sites-available/$PROJECT_NAME /etc/nginx/sites-enabled/
sudo systemctl restart nginx

echo "🔐 Setting up SSL with Let's Encrypt..."
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d $DOMAIN -d www.$DOMAIN --non-interactive --agree-tos -m admin@$DOMAIN

echo "🛠️ Deploying $PROJECT_NAME..."
sudo mkdir -p $PROJECT_DIR
sudo chown -R $USER:$USER /var/www

cd $PROJECT_DIR

echo "💾 Installing dependencies..."
composer install --no-dev --optimize-autoloader

echo "🔧 Setting correct permissions..."
sudo chown -R www-data:www-data $PROJECT_DIR
sudo chmod -R 775 $PROJECT_DIR/var $PROJECT_DIR/public

echo "📂 Configuring environment file..."
sudo cp .env .env.prod
sudo sed -i "s|DATABASE_URL=.*|DATABASE_URL=\"mysql://$DB_USER:$DB_PASS@127.0.0.1:3306/$DB_NAME\"|g" .env.prod

echo "📦 Running database migrations..."
php bin/console doctrine:migrations:migrate --no-interaction --env=prod

echo "🧹 Cleaning and optimizing..."
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod

echo "✅ Finalizing deployment..."
mkdir -p $PROJECT_DIR/var/cache/prod $PROJECT_DIR/var/log
sudo chown -R www-data:www-data $PROJECT_DIR/var
sudo chmod -R 775 $PROJECT_DIR/var

echo "🚀 Restarting services..."
sudo systemctl restart php8.3-fpm
sudo systemctl restart nginx

echo "🌍 Deployment completed successfully! Access your site at http://$DOMAIN"

echo "📜 Monitoring logs..."
sudo tail -f /var/log/nginx/error.log /var/log/php8.3-fpm.log $PROJECT_DIR/var/log/prod.log
