#!/bin/bash

# Complete Deployment Script for MyGroup Full Stack Application
# This script handles the entire deployment process on EC2

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Configuration
APP_DIR="/home/ubuntu/MyGroupFullStak"
NGINX_ROOT="/var/www/html"
UPLOAD_DIR="/var/www/uploads"

echo "=========================================="
echo "🚀 MyGroup Full Stack Deployment"
echo "=========================================="
echo ""
echo "Project Structure:"
echo "- client/: React frontend (Vite)"
echo "- server/: Node.js backend (TypeScript)"
echo "- Build output: dist/public/ (frontend), dist/index.js (backend)"
echo ""

# Check if we're in the right directory
if [ ! -f "package.json" ] || [ ! -d "client" ] || [ ! -d "server" ]; then
    print_error "Please run this script from the MyGroupFullStak root directory"
    print_error "Expected structure: client/, server/, package.json"
    exit 1
fi

print_status "Starting deployment process..."

# Step 1: Stop existing processes
print_status "Step 1: Stopping existing processes..."
pm2 delete all || true
pkill -f "node.*index.js" || true
print_success "Existing processes stopped"

# Step 2: Pull latest code
print_status "Step 2: Pulling latest code..."
git pull origin main
print_success "Code updated"

# Step 3: Install dependencies
print_status "Step 3: Installing dependencies..."
npm install
print_success "Dependencies installed"

# Step 4: Set environment variables for build
print_status "Step 4: Setting build environment..."
export NODE_ENV=production
export NODE_OPTIONS="--max-old-space-size=2048"
print_success "Environment configured"

# Step 5: Clean previous build
print_status "Step 5: Cleaning previous build..."
rm -rf dist/
rm -rf node_modules/.vite/ || true
print_success "Build cache cleaned"

# Step 6: Build application
print_status "Step 6: Building application..."
print_status "Building React frontend (client/) → dist/public/"
print_status "Building Node.js backend (server/) → dist/index.js"

if npm run build:production; then
    print_success "Production build completed successfully"
elif npm run build; then
    print_success "Standard build completed successfully"
else
    print_error "Build failed. Trying alternative build methods..."
    
    # Try building client and server separately
    if npm run build:client && npm run build:server; then
        print_success "Separate build completed successfully"
    else
        print_error "All build methods failed"
        exit 1
    fi
fi

# Step 7: Verify build output
print_status "Step 7: Verifying build output..."
if [ ! -f "dist/index.js" ]; then
    print_error "Server build failed - dist/index.js not found"
    exit 1
fi

if [ ! -f "dist/public/index.html" ]; then
    print_error "Client build failed - dist/public/index.html not found"
    exit 1
fi

print_success "Build verification passed"
echo "  ✅ Server bundle: dist/index.js"
echo "  ✅ Frontend files: dist/public/"

# Step 8: Deploy frontend files
print_status "Step 8: Deploying frontend files..."
sudo mkdir -p $NGINX_ROOT
sudo rm -rf $NGINX_ROOT/*
sudo cp -r dist/public/* $NGINX_ROOT/
sudo chown -R www-data:www-data $NGINX_ROOT
sudo chmod -R 755 $NGINX_ROOT
print_success "Frontend deployed to $NGINX_ROOT"

# Step 9: Setup directories
print_status "Step 9: Setting up directories..."
sudo mkdir -p $UPLOAD_DIR
sudo chown -R www-data:www-data $UPLOAD_DIR
sudo chmod 755 $UPLOAD_DIR
mkdir -p logs
print_success "Directories configured"

# Step 10: Configure Nginx
print_status "Step 10: Configuring Nginx..."
if [ -f "deployment/nginx.conf" ]; then
    sudo cp deployment/nginx.conf /etc/nginx/sites-available/mygroup
    sudo ln -sf /etc/nginx/sites-available/mygroup /etc/nginx/sites-enabled/mygroup
    sudo rm -f /etc/nginx/sites-enabled/default
    
    # Test Nginx configuration
    if sudo nginx -t; then
        sudo systemctl reload nginx
        print_success "Nginx configured and reloaded"
    else
        print_error "Nginx configuration test failed"
        exit 1
    fi
else
    print_warning "No nginx.conf found, skipping Nginx configuration"
fi

# Step 11: Start application with PM2
print_status "Step 11: Starting application with PM2..."

if [ -f "deployment/ecosystem.config.cjs" ]; then
    print_status "Using PM2 ecosystem configuration (CommonJS)..."
    pm2 start deployment/ecosystem.config.cjs --env production
elif [ -f "deployment/ecosystem.config.js" ]; then
    print_status "Using PM2 ecosystem configuration (ES6)..."
    pm2 start deployment/ecosystem.config.js --env production
else
    print_status "Using direct PM2 start..."
    pm2 start dist/index.js \
      --name "mygroup-app" \
      --env NODE_ENV=production \
      --env PORT=5000 \
      --env DB_HOST=localhost \
      --env DB_PORT=3306 \
      --env DB_USER=root \
      --env DB_PASSWORD=MyGroup@2025! \
      --env DB_NAME=my_group
fi

# Save PM2 configuration
pm2 save
print_success "Application started with PM2"

# Step 12: Verify deployment
print_status "Step 12: Verifying deployment..."
sleep 3

# Check PM2 status
echo ""
echo "PM2 Status:"
pm2 status

# Check if application is responding
echo ""
print_status "Testing application endpoints..."

# Test local API
if curl -f -s http://localhost:5000/api/test > /dev/null 2>&1; then
    print_success "✅ Local API responding"
else
    print_warning "⚠️  Local API not responding (this might be normal if no /api/test endpoint exists)"
fi

# Test frontend
if curl -f -s http://localhost/ > /dev/null 2>&1; then
    print_success "✅ Frontend responding"
else
    print_warning "⚠️  Frontend not responding"
fi

# Show recent logs
echo ""
print_status "Recent application logs:"
pm2 logs --lines 10 --nostream

echo ""
echo "=========================================="
echo "🎉 Deployment Complete!"
echo "=========================================="
echo ""
echo "Application Status:"
echo "- Frontend: http://$(curl -s http://checkip.amazonaws.com)/"
echo "- API: http://$(curl -s http://checkip.amazonaws.com)/api/"
echo "- PM2 Status: pm2 status"
echo "- Logs: pm2 logs mygroup-app"
echo ""
echo "File Locations:"
echo "- Frontend files: $NGINX_ROOT"
echo "- Server bundle: $APP_DIR/dist/index.js"
echo "- Upload directory: $UPLOAD_DIR"
echo "- Application logs: $APP_DIR/logs"
echo ""
echo "Useful Commands:"
echo "- View logs: pm2 logs mygroup-app --lines 50"
echo "- Restart app: pm2 restart mygroup-app"
echo "- Monitor app: pm2 monit"
echo "- Check Nginx: sudo nginx -t && sudo systemctl status nginx"
echo ""
print_success "Deployment completed successfully!"
