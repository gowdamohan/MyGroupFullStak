#!/bin/bash

# Deployment Script for EC2 Server
# Run this script on your EC2 instance after initial setup

set -e  # Exit on any error

echo "🚀 Starting deployment process..."

# Configuration
APP_DIR="/home/ubuntu/MyGroupFullStack"
NGINX_CONFIG="/etc/nginx/sites-available/apphub"
LOG_DIR="/var/log/pm2"
UPLOAD_DIR="/var/www/uploads"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Check if running as correct user
if [ "$USER" != "ubuntu" ]; then
    print_error "This script should be run as ubuntu user"
    exit 1
fi

# Create necessary directories
print_status "Creating necessary directories..."
sudo mkdir -p $LOG_DIR
sudo mkdir -p $UPLOAD_DIR
sudo chown -R ubuntu:ubuntu $LOG_DIR
sudo chown -R www-data:www-data $UPLOAD_DIR
sudo chmod 755 $UPLOAD_DIR

# Navigate to app directory
cd $APP_DIR

# Pull latest code
print_status "Pulling latest code from repository..."
git pull origin main

# Install/update backend dependencies
print_status "Installing backend dependencies..."
cd backend
npm install --production

# Install/update frontend dependencies and build
print_status "Building frontend..."
cd ../client
npm install
npm run build

# Copy built files to nginx directory
print_status "Copying built files..."
sudo rm -rf /var/www/html/*
sudo cp -r dist/* /var/www/html/

# Update database schema (if needed)
print_status "Updating database..."
cd ../backend
npm run migrate 2>/dev/null || print_warning "Migration script not found or failed"

# Configure Nginx
print_status "Configuring Nginx..."
sudo cp ../deployment/nginx.conf $NGINX_CONFIG
sudo ln -sf $NGINX_CONFIG /etc/nginx/sites-enabled/apphub
sudo rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
sudo nginx -t
if [ $? -eq 0 ]; then
    print_status "Nginx configuration is valid"
    sudo systemctl reload nginx
else
    print_error "Nginx configuration is invalid"
    exit 1
fi

# Start/restart application with PM2
print_status "Starting application with PM2..."
cd $APP_DIR

# Stop existing processes
pm2 stop all 2>/dev/null || true
pm2 delete all 2>/dev/null || true

# Start with ecosystem file
pm2 start deployment/ecosystem.config.js --env production

# Save PM2 configuration
pm2 save
pm2 startup

print_status "Deployment completed successfully!"

# Display status
echo ""
echo "📊 Application Status:"
pm2 status

echo ""
echo "🌐 Your application should be available at:"
echo "   Backend API: http://$(curl -s http://checkip.amazonaws.com):3000"
echo "   Frontend: http://$(curl -s http://checkip.amazonaws.com)"

echo ""
echo "📝 Useful commands:"
echo "   View logs: pm2 logs"
echo "   Restart app: pm2 restart all"
echo "   Stop app: pm2 stop all"
echo "   Nginx status: sudo systemctl status nginx"
echo "   MySQL status: sudo systemctl status mysql"
