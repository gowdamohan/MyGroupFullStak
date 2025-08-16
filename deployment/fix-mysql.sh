#!/bin/bash

# MySQL Configuration Fix Script

echo "🔧 Fixing MySQL Configuration"
echo "=============================="

# Check MySQL status
echo "Checking MySQL status..."
sudo systemctl status mysql --no-pager

if ! sudo systemctl is-active --quiet mysql; then
    echo "Starting MySQL..."
    sudo systemctl start mysql
fi

echo ""
echo "🔐 Configuring MySQL Authentication"
echo "-----------------------------------"

# Create SQL commands file
cat > /tmp/mysql_fix.sql << 'EOF'
-- Fix root user authentication
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'MyGroup@2025';

-- Create application user (recommended approach)
CREATE USER IF NOT EXISTS 'appuser'@'localhost' IDENTIFIED BY 'MyGroup@2025';
GRANT ALL PRIVILEGES ON my_group.* TO 'appuser'@'localhost';

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS my_group;

-- Show users
SELECT user, host, plugin FROM mysql.user WHERE user IN ('root', 'appuser');

FLUSH PRIVILEGES;
EOF

echo "Executing MySQL configuration..."
sudo mysql < /tmp/mysql_fix.sql

if [ $? -eq 0 ]; then
    echo "✅ MySQL configuration updated successfully"
    echo ""
    echo "📋 Database Access Information:"
    echo "------------------------------"
    echo "Database: my_group"
    echo "Root User: root / MyGroup@2025"
    echo "App User: appuser / MyGroup@2025"
    echo ""
    echo "🧪 Testing connections:"
    echo "----------------------"
    
    echo "Testing root connection:"
    mysql -u root -pMyGroup@2025 -e "SELECT 'Root connection successful' as status;" 2>/dev/null && echo "✅ Root connection works" || echo "❌ Root connection failed"
    
    echo "Testing appuser connection:"
    mysql -u appuser -pMyGroup@2025 -e "SELECT 'App user connection successful' as status;" 2>/dev/null && echo "✅ App user connection works" || echo "❌ App user connection failed"
    
    echo "Testing database access:"
    mysql -u appuser -pMyGroup@2025 my_group -e "SELECT 'Database access successful' as status;" 2>/dev/null && echo "✅ Database access works" || echo "❌ Database access failed"
    
else
    echo "❌ MySQL configuration failed"
    echo "Trying alternative approach..."
    
    # Alternative: Use sudo mysql (auth_socket)
    echo "Using sudo mysql approach..."
    sudo mysql -e "
        ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'MyGroup@2025';
        CREATE USER IF NOT EXISTS 'appuser'@'localhost' IDENTIFIED BY 'MyGroup@2025';
        CREATE DATABASE IF NOT EXISTS my_group;
        GRANT ALL PRIVILEGES ON my_group.* TO 'appuser'@'localhost';
        FLUSH PRIVILEGES;
    "
fi

# Clean up
rm -f /tmp/mysql_fix.sql

echo ""
echo "🔄 Recommended Next Steps:"
echo "-------------------------"
echo "1. Update your application to use 'appuser' instead of 'root'"
echo "2. Test database connection:"
echo "   mysql -u appuser -pMyGroup@2025 my_group"
echo "3. Update environment files if needed"
