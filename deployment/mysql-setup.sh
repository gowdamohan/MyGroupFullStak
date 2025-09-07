#!/bin/bash

# MySQL Database Setup Script for MyGroup Application
# Run this script after MySQL is installed and secured

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

echo "=========================================="
echo "🗄️ MyGroup MySQL Database Setup"
echo "=========================================="
echo ""

# Database configuration
DB_NAME="my_group"
DB_USER="mygroup_user"
DB_PASSWORD="MyGroup@2025!"
ROOT_PASSWORD="MyGroup@2025!"

print_status "Setting up MySQL database for MyGroup application..."
echo ""

# Check if MySQL is running
if ! systemctl is-active --quiet mysql; then
    print_error "MySQL is not running. Please start MySQL first:"
    echo "sudo systemctl start mysql"
    exit 1
fi

print_status "MySQL is running. Proceeding with database setup..."

# Create SQL script for database setup
cat > /tmp/setup_database.sql << EOF
-- Create database
CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user and grant privileges
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';

-- Grant privileges to root for application access
GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO 'root'@'localhost';

-- Flush privileges
FLUSH PRIVILEGES;

-- Show databases to verify
SHOW DATABASES;

-- Use the database
USE ${DB_NAME};

-- Create basic tables for MyGroup application
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    role ENUM('admin', 'user') DEFAULT 'user',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_by INT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS group_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    user_id INT NOT NULL,
    role ENUM('admin', 'member') DEFAULT 'member',
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_membership (group_id, user_id)
);

CREATE TABLE IF NOT EXISTS sessions (
    session_id VARCHAR(128) COLLATE utf8mb4_bin NOT NULL,
    expires INT(11) UNSIGNED NOT NULL,
    data MEDIUMTEXT COLLATE utf8mb4_bin,
    PRIMARY KEY (session_id)
);

-- Insert default admin user (password: admin123)
INSERT IGNORE INTO users (username, email, password_hash, first_name, last_name, role) 
VALUES ('admin', 'admin@mygroup.com', '\$2a\$10\$rOzJqQjQjQjQjQjQjQjQjOzJqQjQjQjQjQjQjQjQjQjQjQjQjQjQjQ', 'Admin', 'User', 'admin');

-- Show tables to verify
SHOW TABLES;

-- Show user count
SELECT COUNT(*) as user_count FROM users;

EOF

print_status "Executing database setup script..."

# Execute the SQL script
if mysql -u root -p${ROOT_PASSWORD} < /tmp/setup_database.sql; then
    print_success "Database setup completed successfully!"
else
    print_error "Database setup failed. Please check MySQL credentials."
    echo ""
    echo "If this is the first time setting up MySQL, you may need to:"
    echo "1. Run: sudo mysql_secure_installation"
    echo "2. Set root password to: ${ROOT_PASSWORD}"
    echo "3. Run this script again"
    exit 1
fi

# Clean up temporary file
rm -f /tmp/setup_database.sql

# Test database connection
print_status "Testing database connection..."

if mysql -u root -p${ROOT_PASSWORD} -e "USE ${DB_NAME}; SELECT 'Connection successful' as status;" > /dev/null 2>&1; then
    print_success "Database connection test passed!"
else
    print_error "Database connection test failed!"
    exit 1
fi

# Test application user connection
if mysql -u ${DB_USER} -p${DB_PASSWORD} -e "USE ${DB_NAME}; SELECT 'App user connection successful' as status;" > /dev/null 2>&1; then
    print_success "Application user connection test passed!"
else
    print_warning "Application user connection test failed, but root connection works."
fi

echo ""
echo "=========================================="
echo "📊 Database Information"
echo "=========================================="
echo ""
echo "Database Name: ${DB_NAME}"
echo "Database User: ${DB_USER}"
echo "Database Password: ${DB_PASSWORD}"
echo "Root Password: ${ROOT_PASSWORD}"
echo ""
echo "Connection Details for Application:"
echo "DB_HOST=localhost"
echo "DB_PORT=3306"
echo "DB_USER=root"
echo "DB_PASSWORD=${ROOT_PASSWORD}"
echo "DB_NAME=${DB_NAME}"
echo ""

# Show database status
print_status "Database Status:"
mysql -u root -p${ROOT_PASSWORD} -e "
SELECT 
    SCHEMA_NAME as 'Database',
    DEFAULT_CHARACTER_SET_NAME as 'Charset',
    DEFAULT_COLLATION_NAME as 'Collation'
FROM information_schema.SCHEMATA 
WHERE SCHEMA_NAME = '${DB_NAME}';
"

echo ""
print_status "Tables Created:"
mysql -u root -p${ROOT_PASSWORD} -e "USE ${DB_NAME}; SHOW TABLES;"

echo ""
print_status "User Count:"
mysql -u root -p${ROOT_PASSWORD} -e "USE ${DB_NAME}; SELECT COUNT(*) as total_users FROM users;"

echo ""
echo "=========================================="
echo "🎉 MySQL Database Setup Complete!"
echo "=========================================="
echo ""
echo "Your MyGroup database is ready for use!"
echo ""
echo "Next steps:"
echo "1. Update your application's .env file with the database credentials"
echo "2. Test your application's database connection"
echo "3. Run any additional migrations if needed"
echo ""
echo "To connect to the database manually:"
echo "mysql -u root -p${ROOT_PASSWORD} ${DB_NAME}"
echo ""
print_success "Database setup completed successfully!"
