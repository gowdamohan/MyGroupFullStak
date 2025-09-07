#!/bin/bash

# Quick Fix Script - Works with existing root user setup

echo "🚀 Quick Fix for EC2 Deployment"
echo "==============================="

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

print_status() { echo -e "${GREEN}✅ $1${NC}"; }
print_error() { echo -e "${RED}❌ $1${NC}"; }
print_warning() { echo -e "${YELLOW}⚠️  $1${NC}"; }

APP_DIR="/home/ubuntu/MyGroupFullStack"
cd $APP_DIR

# Test MySQL connection first
print_status "Testing MySQL connection..."
mysql -u root -pMyGroup@2025 -e "SELECT 'MySQL connection successful' as status;" 2>/dev/null
if [ $? -eq 0 ]; then
    print_status "MySQL connection works"
else
    print_error "MySQL connection failed - check password"
    exit 1
fi

# Create database if it doesn't exist
print_status "Setting up database..."
mysql -u root -p"MyGroup@2025!" << 'EOF'
CREATE DATABASE IF NOT EXISTS my_group;
SHOW DATABASES LIKE 'my_group';
EOF

# Update environment file
print_status "Updating environment configuration..."
cp deployment/production.env backend/.env

# Update database config
print_status "Updating database configuration..."
cp deployment/mysql-database.js backend/config/database.js

# Install dependencies
print_status "Installing backend dependencies..."
cd backend
npm install --production

# Build frontend
print_status "Building frontend..."
cd ../client
npm install
npm run build

# Copy to web directory
print_status "Deploying frontend..."
sudo mkdir -p /var/www/html
sudo rm -rf /var/www/html/*
sudo cp -r dist/* /var/www/html/
sudo chown -R www-data:www-data /var/www/html

# Configure Nginx
print_status "Configuring Nginx..."
sudo cp ../deployment/nginx.conf /etc/nginx/sites-available/apphub
sudo ln -sf /etc/nginx/sites-available/apphub /etc/nginx/sites-enabled/apphub
sudo rm -f /etc/nginx/sites-enabled/default

# Test and restart Nginx
sudo nginx -t && sudo systemctl restart nginx
if [ $? -eq 0 ]; then
    print_status "Nginx configured successfully"
else
    print_error "Nginx configuration failed"
fi

# Create directories
sudo mkdir -p /var/log/pm2 /var/www/uploads
sudo chown -R ubuntu:ubuntu /var/log/pm2
sudo chown -R www-data:www-data /var/www/uploads

# Initialize database
print_status "Initializing database tables..."
cd ../backend
node -e "
const db = require('./config/database.js');
db.initializeDatabase().then(() => {
    console.log('✅ Database initialized successfully');
    process.exit(0);
}).catch(err => {
    console.error('❌ Database initialization failed:', err.message);
    process.exit(1);
});
" || print_warning "Database initialization had issues - check manually"

# Start with PM2
print_status "Starting application..."
cd ..
pm2 stop all 2>/dev/null || true
pm2 delete all 2>/dev/null || true
pm2 start deployment/ecosystem.config.js --env production

# Save PM2 config
pm2 save

# Configure firewall
print_status "Configuring firewall..."
sudo ufw --force enable
sudo ufw allow ssh
sudo ufw allow 80
sudo ufw allow 443
sudo ufw allow 3000

# Get public IP
PUBLIC_IP=$(curl -s http://checkip.amazonaws.com 2>/dev/null || echo "YOUR_EC2_IP")

echo ""
print_status "Deployment completed!"
echo ""
echo "🌐 Access your application:"
echo "   Frontend: http://$PUBLIC_IP"
echo "   Backend: http://$PUBLIC_IP:3000"
echo ""
echo "📊 Check status:"
echo "   pm2 status"
echo "   sudo systemctl status nginx"
echo ""
echo "📝 View logs:"
echo "   pm2 logs"
echo "   sudo tail -f /var/log/nginx/error.log"
