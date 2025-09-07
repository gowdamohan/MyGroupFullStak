#!/bin/bash

# Fix MySQL Password Issue Script
# This script fixes the database password inconsistency

echo "🔧 Fixing MySQL Password Issue"
echo "==============================="

# Navigate to project directory
cd /home/ubuntu/MyGroupFullStak

echo "1. Testing current MySQL connection..."

# Test with correct password
if mysql -u root -p"MyGroup@2025!" -e "SELECT 'Connection successful' as status;" 2>/dev/null; then
    echo "✅ MySQL connection works with correct password: MyGroup@2025!"
    CORRECT_PASSWORD="MyGroup@2025!"
else
    echo "❌ MySQL connection failed with MyGroup@2025!"
    
    # Try without exclamation mark
    if mysql -u root -p"MyGroup@2025" -e "SELECT 'Connection successful' as status;" 2>/dev/null; then
        echo "⚠️  MySQL is using old password: MyGroup@2025"
        echo "🔄 Updating MySQL password to: MyGroup@2025!"
        
        # Update root password
        mysql -u root -p"MyGroup@2025" -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'MyGroup@2025!';" 2>/dev/null
        
        if [ $? -eq 0 ]; then
            echo "✅ MySQL root password updated successfully"
            CORRECT_PASSWORD="MyGroup@2025!"
        else
            echo "❌ Failed to update MySQL password"
            exit 1
        fi
    else
        echo "❌ Cannot connect to MySQL with any known password"
        echo "Please check MySQL installation and root password"
        exit 1
    fi
fi

echo ""
echo "2. Updating application configuration files..."

# Update .env file
cat > .env << EOF
NODE_ENV=production
PORT=5000
HOST=0.0.0.0
DB_HOST=localhost
DB_PORT=3306
DB_USER=root
DB_PASSWORD=${CORRECT_PASSWORD}
DB_NAME=my_group
SESSION_SECRET=your-super-secret-session-key-for-production
JWT_SECRET=your-very-secure-jwt-secret-key-here
JWT_EXPIRES_IN=7d
CORS_ORIGIN=*
MAX_FILE_SIZE=10485760
UPLOAD_PATH=/home/ubuntu/MyGroupFullStak/uploads
RATE_LIMIT_WINDOW_MS=900000
RATE_LIMIT_MAX_REQUESTS=100
EOF

echo "✅ Updated .env file with correct password"

echo ""
echo "3. Creating/updating database and user..."

# Create database and user with correct password
mysql -u root -p"${CORRECT_PASSWORD}" << EOF
CREATE DATABASE IF NOT EXISTS my_group;
DROP USER IF EXISTS 'appuser'@'localhost';
CREATE USER 'appuser'@'localhost' IDENTIFIED BY '${CORRECT_PASSWORD}';
GRANT ALL PRIVILEGES ON my_group.* TO 'appuser'@'localhost';
FLUSH PRIVILEGES;
EOF

if [ $? -eq 0 ]; then
    echo "✅ Database and user configured successfully"
else
    echo "❌ Failed to configure database and user"
    exit 1
fi

echo ""
echo "4. Testing database connection..."

# Test connection with Node.js
node -e "
const mysql = require('mysql2/promise');
async function test() {
  try {
    const connection = await mysql.createConnection({
      host: 'localhost',
      user: 'root',
      password: '${CORRECT_PASSWORD}',
      database: 'my_group'
    });
    console.log('✅ Node.js MySQL connection successful');
    await connection.end();
  } catch (error) {
    console.log('❌ Node.js MySQL connection failed:', error.message);
    process.exit(1);
  }
}
test();
" 2>/dev/null

if [ $? -eq 0 ]; then
    echo "✅ Node.js database connection test passed"
else
    echo "❌ Node.js database connection test failed"
fi

echo ""
echo "5. Restarting application..."

# Stop existing processes
pm2 delete all 2>/dev/null || true

# Start application with correct environment
pm2 start dist/index.js --name "mygroup-app" --env NODE_ENV=production

echo ""
echo "✅ Password fix completed!"
echo ""
echo "📋 Database Configuration:"
echo "  Host: localhost"
echo "  User: root"
echo "  Password: ${CORRECT_PASSWORD}"
echo "  Database: my_group"
echo ""
echo "🔍 Check application status:"
echo "  pm2 status"
echo "  pm2 logs mygroup-app --lines 20"
