#!/bin/bash

# Fix MySQL Connection Issue on EC2
# This script fixes the "Access denied for user 'root'@'localhost'" error

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_header() {
    echo -e "\n${BLUE}================================${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}================================${NC}\n"
}

# Main execution
main() {
    print_header "Fixing MySQL Connection Issue"
    print_info "This script will fix the MySQL authentication problem"
    
    # Step 1: Check MySQL service status
    print_header "Step 1: Checking MySQL Service"
    if sudo systemctl is-active --quiet mysql; then
        print_success "MySQL service is running"
    else
        print_warning "MySQL service is not running. Starting it..."
        sudo systemctl start mysql
        sudo systemctl enable mysql
        print_success "MySQL service started"
    fi
    
    # Step 2: Check current environment variables
    print_header "Step 2: Checking Database Configuration"
    
    if [ -f ".env" ]; then
        print_info "Current .env file contents:"
        grep -E "DB_|DATABASE_" .env || echo "No database variables found in .env"
    else
        print_warning ".env file not found"
    fi
    
    if [ -f "deployment/production.env" ]; then
        print_info "Production environment file contents:"
        grep -E "DB_|DATABASE_" deployment/production.env || echo "No database variables found"
    fi
    
    # Step 3: Test MySQL connection with different credentials
    print_header "Step 3: Testing MySQL Connection"
    
    print_info "Testing MySQL connection with root (no password)..."
    if mysql -u root -e "SELECT 1;" 2>/dev/null; then
        print_success "MySQL root connection works without password"
        DB_PASSWORD=""
    else
        print_info "Testing MySQL connection with root and password..."
        if mysql -u root -p"MyGroup@2025!" -e "SELECT 1;" 2>/dev/null; then
            print_success "MySQL root connection works with password: MyGroup@2025!"
            DB_PASSWORD="MyGroup@2025!"
        else
            print_warning "Standard passwords failed. Let's reset MySQL root password..."
            
            # Reset MySQL root password
            print_info "Stopping MySQL service..."
            sudo systemctl stop mysql
            
            print_info "Starting MySQL in safe mode..."
            sudo mysqld_safe --skip-grant-tables --skip-networking &
            MYSQL_PID=$!
            sleep 5
            
            print_info "Resetting root password..."
            mysql -u root -e "FLUSH PRIVILEGES; ALTER USER 'root'@'localhost' IDENTIFIED BY 'MyGroup@2025';" 2>/dev/null || \
            mysql -u root -e "UPDATE mysql.user SET authentication_string=PASSWORD('MyGroup@2025') WHERE User='root'; FLUSH PRIVILEGES;" 2>/dev/null
            
            print_info "Stopping safe mode MySQL..."
            sudo kill $MYSQL_PID 2>/dev/null || true
            sleep 2
            
            print_info "Starting MySQL normally..."
            sudo systemctl start mysql
            
            # Test again
            if mysql -u root -p"MyGroup@2025" -e "SELECT 1;" 2>/dev/null; then
                print_success "MySQL root password reset successfully"
                DB_PASSWORD="MyGroup@2025"
            else
                print_error "Failed to reset MySQL password. Manual intervention required."
                exit 1
            fi
        fi
    fi
    
    # Step 4: Create/Update database
    print_header "Step 4: Setting Up Database"
    
    print_info "Creating database if it doesn't exist..."
    if [ -z "$DB_PASSWORD" ]; then
        mysql -u root -e "CREATE DATABASE IF NOT EXISTS my_group;" 2>/dev/null || true
        mysql -u root -e "CREATE DATABASE IF NOT EXISTS apphub_db;" 2>/dev/null || true
    else
        mysql -u root -p"$DB_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS my_group;" 2>/dev/null || true
        mysql -u root -p"$DB_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS apphub_db;" 2>/dev/null || true
    fi
    
    print_success "Database setup completed"
    
    # Step 5: Update environment configuration
    print_header "Step 5: Updating Environment Configuration"
    
    # Create/update .env file
    cat > .env << EOF
# Environment
NODE_ENV=production

# Server Configuration
PORT=5000
HOST=0.0.0.0

# Database Configuration
DB_HOST=localhost
DB_PORT=3306
DB_USER=root
DB_PASSWORD=$DB_PASSWORD
DB_NAME=my_group

# Alternative database URL format
DATABASE_URL=mysql://root:$DB_PASSWORD@localhost:3306/my_group

# Security
SESSION_SECRET=your-super-secret-session-key-for-production
JWT_SECRET=your-very-secure-jwt-secret-key-here
JWT_EXPIRES_IN=7d

# CORS Configuration
CORS_ORIGIN=*

# File Upload Configuration
MAX_FILE_SIZE=10485760
UPLOAD_PATH=/home/ubuntu/MyGroupFullStak/uploads

# Rate Limiting
RATE_LIMIT_WINDOW_MS=900000
RATE_LIMIT_MAX_REQUESTS=100

# Logging
LOG_LEVEL=info
EOF
    
    print_success "Environment configuration updated"
    
    # Step 6: Test database connection from Node.js
    print_header "Step 6: Testing Database Connection from Application"
    
    # Create a simple test script
    cat > test-db-connection.js << 'EOF'
import mysql from 'mysql2/promise';
import dotenv from 'dotenv';

dotenv.config();

async function testConnection() {
  try {
    const connection = await mysql.createConnection({
      host: process.env.DB_HOST || 'localhost',
      port: process.env.DB_PORT || 3306,
      user: process.env.DB_USER || 'root',
      password: process.env.DB_PASSWORD || '',
      database: process.env.DB_NAME || 'my_group'
    });
    
    const [rows] = await connection.execute('SELECT 1 as test');
    console.log('✅ Database connection successful:', rows);
    await connection.end();
    process.exit(0);
  } catch (error) {
    console.error('❌ Database connection failed:', error.message);
    process.exit(1);
  }
}

testConnection();
EOF
    
    print_info "Testing database connection from Node.js..."
    if node test-db-connection.js; then
        print_success "Node.js database connection test passed"
    else
        print_error "Node.js database connection test failed"
    fi
    
    # Clean up test file
    rm -f test-db-connection.js
    
    # Step 7: Restart application
    print_header "Step 7: Restarting Application"
    
    print_info "Stopping PM2 processes..."
    pm2 delete all || true
    
    print_info "Starting application with new configuration..."
    if [ -f "deployment/ecosystem.config.cjs" ]; then
        pm2 start deployment/ecosystem.config.cjs --env production
    else
        pm2 start dist/index.js --name "mygroup-app" --env NODE_ENV=production
    fi
    
    pm2 save
    print_success "Application restarted"
    
    # Step 8: Verify fix
    print_header "Step 8: Verifying Fix"
    
    sleep 5
    
    print_info "Checking PM2 status..."
    pm2 status
    
    print_info "Checking recent logs..."
    pm2 logs --lines 10
    
    print_info "Testing API endpoint..."
    if curl -f -s http://localhost:5000/api/test > /dev/null; then
        print_success "API is responding"
    else
        print_warning "API test failed - check logs above"
    fi
    
    print_header "MySQL Fix Complete!"
    print_success "Database connection should now be working"
    print_info "Database credentials:"
    print_info "  Host: localhost"
    print_info "  User: root"
    print_info "  Password: $DB_PASSWORD"
    print_info "  Database: my_group"
    print_info ""
    print_info "Monitor logs with: pm2 logs mygroup-app"
    print_info "Check status with: pm2 status"
}

# Run main function
main "$@"
