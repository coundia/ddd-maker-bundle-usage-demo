#!/bin/bash

# Configuration
PROJECT_NAME="ddd_demo"
DOMAIN="groupe2cs.com"
DB_NAME="${PROJECT_NAME}_db"
DB_USER="${PROJECT_NAME}_user"
DB_PASS="password_secure"
PROJECT_DIR="/var/www/$PROJECT_NAME"
GIT_REPO="git@github.com:coundia/ddd-maker-bundle-usage-demo.git"
 
echo "🛠️ Deploying $PROJECT_DIR..."

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
