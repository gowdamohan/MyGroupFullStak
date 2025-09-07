#!/bin/bash

# Start Application Script
# This script will start your application properly

echo "🚀 Starting MyGroupFullStack Application"
echo "========================================"

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

print_status() { echo -e "${GREEN}✅ $1${NC}"; }
print_error() { echo -e "${RED}❌ $1${NC}"; }
print_warning() { echo -e "${YELLOW}⚠️  $1${NC}"; }
print_info() { echo -e "${BLUE}ℹ️  $1${NC}"; }

APP_DIR="/home/ubuntu/MyGroupFullStack"
cd $APP_DIR

# Check if we're in the right directory
if [ ! -f "package.json" ]; then
    print_error "Not in the correct directory. Please run from MyGroupFullStack root."
    exit 1
fi

print_info "Step 1: Checking Prerequisites"
echo "==============================="

# Check Node.js
if command -v node &> /dev/null; then
    NODE_VERSION=$(node --version)
    print_status "Node.js is installed: $NODE_VERSION"
else
    print_error "Node.js is not installed"
    exit 1
fi

# Check MySQL
if sudo systemctl is-active --quiet mysql; then
    print_status "MySQL is running"
else
    print_warning "MySQL is not running, starting it..."
    sudo systemctl start mysql
fi

# Test database connection
mysql -u root -p"MyGroup@2025!" -e "SELECT 'Database connection successful' as status;" 2>/dev/null
if [ $? -eq 0 ]; then
    print_status "Database connection works"
else
    print_error "Database connection failed"
    exit 1
fi

print_info "Step 2: Installing Dependencies"
echo "==============================="

# Install root dependencies
print_status "Installing root dependencies..."
npm install

# Install backend dependencies (if separate)
if [ -d "backend" ] && [ -f "backend/package.json" ]; then
    print_status "Installing backend dependencies..."
    cd backend
    npm install
    cd ..
fi

print_info "Step 3: Building Application"
echo "============================"

# Build the application
print_status "Building application..."
npm run build

if [ $? -eq 0 ]; then
    print_status "Build completed successfully"
else
    print_error "Build failed"
    exit 1
fi

print_info "Step 4: Setting up Environment"
echo "=============================="

# Copy environment file
if [ -f "deployment/production.env" ]; then
    cp deployment/production.env .env
    print_status "Environment file copied"
else
    print_warning "No production.env found, creating basic .env"
    cat > .env << 'EOF'
NODE_ENV=production
PORT=3000
DB_HOST=localhost
DB_PORT=3306
DB_USER=root
DB_PASSWORD=MyGroup@2025
DB_NAME=my_group
EOF
fi

print_info "Step 5: Starting Application with PM2"
echo "====================================="

# Stop any existing processes
pm2 stop all 2>/dev/null || true
pm2 delete all 2>/dev/null || true

# Start the application
print_status "Starting application..."

# Method 1: Try with ecosystem config if it exists
if [ -f "deployment/ecosystem.config.js" ]; then
    print_info "Using PM2 ecosystem configuration..."
    pm2 start deployment/ecosystem.config.js --env production
elif [ -f "dist/index.js" ]; then
    # Method 2: Start built application directly
    print_info "Starting built application..."
    pm2 start dist/index.js --name "mygroup-app" --instances 1
else
    # Method 3: Start with npm script
    print_info "Starting with npm script..."
    pm2 start npm --name "mygroup-app" -- start
fi

# Save PM2 configuration
pm2 save

# Setup PM2 startup
pm2 startup | grep -E '^sudo' | bash || print_warning "PM2 startup setup may need manual configuration"

print_info "Step 6: Configuring Nginx"
echo "=========================="

# Configure Nginx if config exists
if [ -f "deployment/nginx.conf" ]; then
    print_status "Configuring Nginx..."
    sudo cp deployment/nginx.conf /etc/nginx/sites-available/mygroup
    sudo ln -sf /etc/nginx/sites-available/mygroup /etc/nginx/sites-enabled/mygroup
    sudo rm -f /etc/nginx/sites-enabled/default
    
    # Test and reload Nginx
    sudo nginx -t
    if [ $? -eq 0 ]; then
        sudo systemctl reload nginx
        print_status "Nginx configured successfully"
    else
        print_error "Nginx configuration failed"
    fi
else
    print_warning "No Nginx configuration found"
fi

print_info "Step 7: Final Status Check"
echo "=========================="

# Wait a moment for services to start
sleep 3

# Check PM2 status
print_status "PM2 Status:"
pm2 status

# Check if application is responding
print_status "Testing application..."
if curl -s http://localhost:3000 > /dev/null; then
    print_status "Backend is responding on port 3000"
else
    print_warning "Backend may not be responding on port 3000"
fi

if curl -s http://localhost > /dev/null; then
    print_status "Frontend is accessible"
else
    print_warning "Frontend may not be accessible"
fi

# Get public IP
PUBLIC_IP=$(curl -s http://checkip.amazonaws.com 2>/dev/null || echo "YOUR_EC2_IP")

echo ""
print_status "Application Started Successfully!"
echo ""
echo "🌐 Access URLs:"
echo "   Frontend: http://$PUBLIC_IP"
echo "   Backend API: http://$PUBLIC_IP:3000"
echo ""
echo "📊 Management Commands:"
echo "   Check status: pm2 status"
echo "   View logs: pm2 logs"
echo "   Restart: pm2 restart all"
echo "   Stop: pm2 stop all"
echo ""
echo "🔧 If you have issues:"
echo "   1. Check logs: pm2 logs"
echo "   2. Check Nginx: sudo systemctl status nginx"
echo "   3. Check database: mysql -u root -pMyGroup@2025 my_group"
echo "   4. Check ports: sudo netstat -tlnp | grep -E ':80|:3000'"
