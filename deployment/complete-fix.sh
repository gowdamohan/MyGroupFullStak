#!/bin/bash

# Complete Fix Script for EC2 Deployment Issues
# This script addresses both connection and MySQL issues

set -e  # Exit on any error

echo "🚀 Complete EC2 Deployment Fix"
echo "==============================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

# Check if running as ubuntu user
if [ "$USER" != "ubuntu" ]; then
    print_error "This script should be run as ubuntu user"
    exit 1
fi

APP_DIR="/home/ubuntu/MyGroupFullStack"
cd $APP_DIR

print_info "Step 1: Fixing MySQL Configuration"
echo "===================================="

# Fix MySQL authentication
print_status "Configuring MySQL users and database..."

# Create SQL commands using root password
mysql -u root -pMyGroup@2025 << 'EOF'
-- Create application user
DROP USER IF EXISTS 'appuser'@'localhost';
CREATE USER 'appuser'@'localhost' IDENTIFIED BY 'MyGroup@2025';

-- Create database
CREATE DATABASE IF NOT EXISTS my_group;

-- Grant privileges
GRANT ALL PRIVILEGES ON my_group.* TO 'appuser'@'localhost';
FLUSH PRIVILEGES;

-- Show users for verification
SELECT user, host, plugin FROM mysql.user WHERE user IN ('root', 'appuser');
EOF

if [ $? -eq 0 ]; then
    print_status "MySQL configuration completed successfully"
else
    print_error "MySQL configuration failed"
    exit 1
fi

# Test MySQL connections
print_info "Testing MySQL connections..."
mysql -u appuser -pMyGroup@2025 -e "SELECT 'Connection successful' as status;" 2>/dev/null && print_status "App user connection works" || print_error "App user connection failed"

print_info "Step 2: Updating Application Configuration"
echo "==========================================="

# Copy and update environment file
print_status "Updating environment configuration..."
cp deployment/production.env backend/.env

# Update backend database configuration to use MySQL
if [ -f "backend/config/database.js" ]; then
    print_status "Backing up existing database config..."
    cp backend/config/database.js backend/config/database.js.backup
    
    print_status "Updating database configuration to use MySQL..."
    cp deployment/mysql-database.js backend/config/database.js
fi

print_info "Step 3: Installing and Building Application"
echo "============================================="

# Install backend dependencies
print_status "Installing backend dependencies..."
cd backend
npm install --production

# Install frontend dependencies and build
print_status "Building frontend..."
cd ../client
npm install
npm run build

# Copy built files
print_status "Copying built files to web directory..."
sudo mkdir -p /var/www/html
sudo rm -rf /var/www/html/*
sudo cp -r dist/* /var/www/html/
sudo chown -R www-data:www-data /var/www/html

print_info "Step 4: Configuring Web Server"
echo "==============================="

# Configure Nginx
print_status "Configuring Nginx..."
sudo cp ../deployment/nginx.conf /etc/nginx/sites-available/apphub
sudo ln -sf /etc/nginx/sites-available/apphub /etc/nginx/sites-enabled/apphub
sudo rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
sudo nginx -t
if [ $? -eq 0 ]; then
    print_status "Nginx configuration is valid"
    sudo systemctl enable nginx
    sudo systemctl restart nginx
else
    print_error "Nginx configuration is invalid"
    exit 1
fi

print_info "Step 5: Starting Application Services"
echo "====================================="

# Create necessary directories
sudo mkdir -p /var/log/pm2
sudo mkdir -p /var/www/uploads
sudo chown -R ubuntu:ubuntu /var/log/pm2
sudo chown -R www-data:www-data /var/www/uploads
sudo chmod 755 /var/www/uploads

# Initialize database
print_status "Initializing database..."
cd ../backend
node -e "
const db = require('./config/database.js');
db.initializeDatabase().then(() => {
    console.log('Database initialized successfully');
    process.exit(0);
}).catch(err => {
    console.error('Database initialization failed:', err);
    process.exit(1);
});
"

# Start application with PM2
print_status "Starting application with PM2..."
cd ..

# Stop existing processes
pm2 stop all 2>/dev/null || true
pm2 delete all 2>/dev/null || true

# Start with ecosystem file
pm2 start deployment/ecosystem.config.js --env production

# Save PM2 configuration and setup startup
pm2 save
pm2 startup | grep -E '^sudo' | bash || print_warning "PM2 startup configuration may need manual setup"

print_info "Step 6: Configuring Security"
echo "============================="

# Configure UFW firewall
print_status "Configuring firewall..."
sudo ufw --force enable
sudo ufw allow ssh
sudo ufw allow 80
sudo ufw allow 443
sudo ufw allow 3000

print_info "Step 7: Final Verification"
echo "=========================="

# Check service status
print_status "Checking service status..."
echo ""
echo "PM2 Status:"
pm2 status

echo ""
echo "Nginx Status:"
sudo systemctl status nginx --no-pager -l | head -10

echo ""
echo "MySQL Status:"
sudo systemctl status mysql --no-pager -l | head -10

# Check ports
echo ""
print_status "Checking listening ports..."
sudo netstat -tlnp | grep -E ':80|:443|:3000|:3306'

# Get public IP
PUBLIC_IP=$(curl -s http://checkip.amazonaws.com 2>/dev/null || echo "Unable to get public IP")

echo ""
print_status "Deployment completed successfully!"
echo ""
echo "🌐 Your application should be available at:"
echo "   Frontend: http://$PUBLIC_IP"
echo "   Backend API: http://$PUBLIC_IP:3000"
echo ""
echo "📊 Database Information:"
echo "   Database: my_group"
echo "   User: appuser"
echo "   Password: MyGroup@2025"
echo "   Test connection: mysql -u appuser -pMyGroup@2025 my_group"
echo ""
echo "📝 Useful commands:"
echo "   View logs: pm2 logs"
echo "   Restart app: pm2 restart all"
echo "   Check status: pm2 status"
echo "   Nginx reload: sudo systemctl reload nginx"
echo ""
echo "🔧 If you still can't connect:"
echo "   1. Check AWS Security Group settings"
echo "   2. Verify EC2 instance is running"
echo "   3. Check firewall: sudo ufw status"
echo "   4. View logs: pm2 logs"
