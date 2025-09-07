#!/bin/bash

# EC2 Initial Setup Script for MyGroup Full Stack Application
# Run this script on a fresh Ubuntu 22.04 EC2 instance

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

# Function to check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

echo "=========================================="
echo "🚀 MyGroup EC2 Initial Setup Script"
echo "=========================================="
echo ""

# Check if running as ubuntu user
if [ "$USER" != "ubuntu" ]; then
    print_error "This script should be run as the ubuntu user"
    exit 1
fi

# Update system
print_status "Updating system packages..."
sudo apt update && sudo apt upgrade -y
print_success "System updated successfully"

# Install essential packages
print_status "Installing essential packages..."
sudo apt install -y curl wget git unzip software-properties-common apt-transport-https ca-certificates gnupg lsb-release htop
print_success "Essential packages installed"

# Install Node.js 18 LTS
print_status "Installing Node.js 18 LTS..."
if ! command_exists node; then
    curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
    sudo apt install -y nodejs
    print_success "Node.js installed: $(node --version)"
else
    print_warning "Node.js already installed: $(node --version)"
fi

# Install PM2
print_status "Installing PM2 process manager..."
if ! command_exists pm2; then
    sudo npm install -g pm2
    print_success "PM2 installed: $(pm2 --version)"
else
    print_warning "PM2 already installed: $(pm2 --version)"
fi

# Install Nginx
print_status "Installing Nginx..."
if ! command_exists nginx; then
    sudo apt install -y nginx
    sudo systemctl start nginx
    sudo systemctl enable nginx
    print_success "Nginx installed and started"
else
    print_warning "Nginx already installed"
    sudo systemctl start nginx
    sudo systemctl enable nginx
fi

# Install MySQL
print_status "Installing MySQL Server..."
if ! command_exists mysql; then
    sudo apt install -y mysql-server
    sudo systemctl start mysql
    sudo systemctl enable mysql
    print_success "MySQL installed and started"
else
    print_warning "MySQL already installed"
    sudo systemctl start mysql
    sudo systemctl enable mysql
fi

# Create necessary directories
print_status "Creating necessary directories..."
sudo mkdir -p /var/www/uploads
sudo mkdir -p /var/www/html
sudo chown -R www-data:www-data /var/www/uploads
sudo chown -R www-data:www-data /var/www/html
sudo chmod 755 /var/www/uploads
sudo chmod 755 /var/www/html
print_success "Directories created"

# Setup firewall (UFW)
print_status "Configuring firewall..."
sudo ufw --force enable
sudo ufw allow ssh
sudo ufw allow 'Nginx Full'
sudo ufw allow 5000  # Temporary for testing
print_success "Firewall configured"

# Create monitoring script
print_status "Creating monitoring script..."
cat > /home/ubuntu/monitor.sh << 'EOF'
#!/bin/bash
echo "=== System Status ==="
date
echo ""

echo "=== PM2 Status ==="
pm2 status
echo ""

echo "=== Nginx Status ==="
sudo systemctl status nginx --no-pager -l
echo ""

echo "=== MySQL Status ==="
sudo systemctl status mysql --no-pager -l
echo ""

echo "=== Disk Usage ==="
df -h
echo ""

echo "=== Memory Usage ==="
free -h
echo ""

echo "=== Recent Logs ==="
pm2 logs --lines 10 --nostream 2>/dev/null || echo "No PM2 processes running"
EOF

chmod +x /home/ubuntu/monitor.sh
print_success "Monitoring script created at /home/ubuntu/monitor.sh"

# Create maintenance script
print_status "Creating maintenance script..."
cat > /home/ubuntu/maintenance.sh << 'EOF'
#!/bin/bash
echo "=== Weekly Maintenance ==="
date

# Update system packages
sudo apt update && sudo apt upgrade -y

# Clean package cache
sudo apt autoremove -y
sudo apt autoclean

# Restart services
sudo systemctl restart nginx
pm2 restart all 2>/dev/null || echo "No PM2 processes to restart"

# Check disk space
df -h

echo "Maintenance completed at $(date)"
EOF

chmod +x /home/ubuntu/maintenance.sh
print_success "Maintenance script created at /home/ubuntu/maintenance.sh"

# Setup PM2 startup
print_status "Setting up PM2 startup..."
pm2 startup > /tmp/pm2_startup.txt 2>&1
if grep -q "sudo env" /tmp/pm2_startup.txt; then
    STARTUP_CMD=$(grep "sudo env" /tmp/pm2_startup.txt)
    eval $STARTUP_CMD
    print_success "PM2 startup configured"
else
    print_warning "PM2 startup configuration may need manual setup"
fi

# Display service status
echo ""
echo "=========================================="
echo "📊 Service Status"
echo "=========================================="

print_status "Checking service status..."
echo ""

echo "Node.js: $(node --version)"
echo "NPM: $(npm --version)"
echo "PM2: $(pm2 --version)"

echo ""
echo "Service Status:"
sudo systemctl is-active nginx && echo "✅ Nginx: Running" || echo "❌ Nginx: Not running"
sudo systemctl is-active mysql && echo "✅ MySQL: Running" || echo "❌ MySQL: Not running"

echo ""
echo "=========================================="
echo "🎉 Initial Setup Complete!"
echo "=========================================="
echo ""
echo "Next steps:"
echo "1. Configure MySQL database:"
echo "   sudo mysql_secure_installation"
echo ""
echo "2. Clone your repository:"
echo "   git clone https://github.com/your-username/MyGroupFullStack.git"
echo "   mv MyGroupFullStack MyGroupFullStak"
echo "   cd MyGroupFullStak"
echo ""
echo "3. Follow the deployment guide for application setup"
echo ""
echo "4. Run monitoring script anytime:"
echo "   ./monitor.sh"
echo ""
echo "5. Run maintenance script weekly:"
echo "   ./maintenance.sh"
echo ""
print_success "Setup completed successfully!"
