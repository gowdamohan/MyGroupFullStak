# MySQL Connection Fix Guide

Your application is running correctly, but failing due to MySQL authentication issues. Here's how to fix it.

## 🔍 Problem Identified

From your logs:
```
Access denied for user 'root'@'localhost'
sqlMessage: "Access denied for user 'root'@'localhost'"
```

This means:
- ✅ Your code is updated and built correctly
- ✅ PM2 is running the application
- ❌ MySQL authentication is failing

## 🚀 Quick Fix (Automated)

Run this script on your EC2 server:

```bash
# SSH to your EC2 server
ssh -i your-key.pem ubuntu@your-ec2-ip

# Navigate to project
cd /home/ubuntu/MyGroupFullStak

# Run the MySQL fix script
chmod +x deployment/fix-mysql-connection.sh
./deployment/fix-mysql-connection.sh
```

## 🔧 Manual Fix Steps

If you prefer to fix it manually:

### Step 1: Check MySQL Service
```bash
sudo systemctl status mysql
sudo systemctl start mysql
sudo systemctl enable mysql
```

### Step 2: Test MySQL Connection
```bash
# Try connecting without password
mysql -u root -e "SELECT 1;"

# If that fails, try with password
mysql -u root -p"MyGroup@2025" -e "SELECT 1;"
```

### Step 3: Reset MySQL Root Password (if needed)
```bash
# Stop MySQL
sudo systemctl stop mysql

# Start in safe mode
sudo mysqld_safe --skip-grant-tables --skip-networking &

# Reset password
mysql -u root -e "FLUSH PRIVILEGES; ALTER USER 'root'@'localhost' IDENTIFIED BY 'MyGroup@2025';"

# Kill safe mode and restart normally
sudo pkill mysqld_safe
sudo systemctl start mysql

# Test connection
mysql -u root -p"MyGroup@2025" -e "SELECT 1;"
```

### Step 4: Create Database
```bash
mysql -u root -p"MyGroup@2025" -e "CREATE DATABASE IF NOT EXISTS my_group;"
mysql -u root -p"MyGroup@2025" -e "CREATE DATABASE IF NOT EXISTS apphub_db;"
```

### Step 5: Update Environment Variables
```bash
# Create/update .env file
cat > .env << 'EOF'
NODE_ENV=production
PORT=5000
HOST=0.0.0.0

# Database Configuration
DB_HOST=localhost
DB_PORT=3306
DB_USER=root
DB_PASSWORD=MyGroup@2025
DB_NAME=my_group

# Alternative format
DATABASE_URL=mysql://root:MyGroup@2025@localhost:3306/my_group

# Security
SESSION_SECRET=your-super-secret-session-key-for-production
JWT_SECRET=your-very-secure-jwt-secret-key-here
EOF
```

### Step 6: Restart Application
```bash
# Stop PM2
pm2 delete all

# Start with new environment
pm2 start deployment/ecosystem.config.cjs --env production
pm2 save

# Check status
pm2 status
pm2 logs --lines 10
```

## 🔍 Verification

After fixing, you should see:

### ✅ Successful Logs:
```bash
pm2 logs --lines 10
# Should show successful database connections instead of errors
```

### ✅ Working API:
```bash
curl -I http://localhost:5000/api/test
# Should return 200 OK
```

### ✅ Working Frontend:
```bash
curl -I http://your-server-ip:5000/
# Should return 200 OK and serve your updated UI
```

## 🎯 Common MySQL Issues on EC2

### Issue 1: MySQL not installed
```bash
sudo apt update
sudo apt install mysql-server -y
sudo systemctl start mysql
sudo systemctl enable mysql
```

### Issue 2: Root password not set
```bash
sudo mysql_secure_installation
# Follow prompts to set root password
```

### Issue 3: Authentication plugin issues
```bash
mysql -u root -p -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'MyGroup@2025';"
```

### Issue 4: Database doesn't exist
```bash
mysql -u root -p -e "CREATE DATABASE my_group;"
mysql -u root -p -e "SHOW DATABASES;"
```

## 📊 Environment Variables Reference

Your application expects these database variables:

```bash
# Primary format (used by your app)
DB_HOST=localhost
DB_PORT=3306
DB_USER=root
DB_PASSWORD=MyGroup@2025
DB_NAME=my_group

# Alternative format (for some libraries)
DATABASE_URL=mysql://root:MyGroup@2025@localhost:3306/my_group
```

## 🚨 Troubleshooting

### If MySQL connection still fails:

1. **Check MySQL error logs:**
   ```bash
   sudo tail -f /var/log/mysql/error.log
   ```

2. **Check if MySQL is listening:**
   ```bash
   sudo netstat -tlnp | grep :3306
   ```

3. **Test connection manually:**
   ```bash
   mysql -u root -p"MyGroup@2025" -h localhost -P 3306 -e "SELECT 1;"
   ```

4. **Check your application's database configuration:**
   ```bash
   grep -r "DB_" server/
   cat .env | grep DB_
   ```

### If application still shows errors:

1. **Check PM2 logs for specific errors:**
   ```bash
   pm2 logs mygroup-app --lines 50
   ```

2. **Restart with verbose logging:**
   ```bash
   pm2 delete all
   DEBUG=* pm2 start dist/index.js --name mygroup-app
   ```

3. **Test database connection from Node.js:**
   ```bash
   node -e "
   const mysql = require('mysql2/promise');
   mysql.createConnection({
     host: 'localhost',
     user: 'root',
     password: 'MyGroup@2025',
     database: 'my_group'
   }).then(() => console.log('✅ Connected')).catch(err => console.error('❌', err.message));
   "
   ```

## ✅ Success Indicators

After the fix:
- ✅ PM2 logs show no MySQL errors
- ✅ API endpoints return data instead of 500 errors
- ✅ Frontend loads with your updated UI
- ✅ Database queries work correctly

Your updated code should now be visible in the UI once the database connection is fixed! 🚀
