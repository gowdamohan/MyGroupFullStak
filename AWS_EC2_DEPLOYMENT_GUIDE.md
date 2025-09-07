# 🚀 Complete AWS EC2 Deployment Guide for MyGroup Full Stack Application

This comprehensive guide will walk you through deploying your MyGroup Full Stack application on AWS EC2 with automated CI/CD using GitHub Actions.

## 🏗️ Project Architecture

Your application follows this structure:
- **Root**: Main project with build scripts and dependencies
- **client/**: React frontend application (built with Vite to `dist/public/`)
- **server/**: Node.js backend application (TypeScript, built to `dist/index.js`)
- **shared/**: Shared types and schemas
- **deployment/**: Deployment configurations and scripts

### Build Process
- **Client Build**: `npm run build:client` → Vite builds React app to `dist/public/`
- **Server Build**: `npm run build:server` → esbuild bundles TypeScript server to `dist/index.js`
- **Production Build**: `npm run build:production` → Builds both client and server for production

## 📋 Table of Contents

1. [Prerequisites](#prerequisites)
2. [AWS EC2 Setup](#aws-ec2-setup)
3. [Server Initial Configuration](#server-initial-configuration)
4. [MySQL Database Setup](#mysql-database-setup)
5. [Clone and Setup Application](#clone-and-setup-application)
6. [PM2 Process Manager Setup](#pm2-process-manager-setup)
7. [Nginx Web Server Configuration](#nginx-web-server-configuration)
8. [GitHub Actions CI/CD Setup](#github-actions-cicd-setup)
9. [SSL Certificate Setup (Optional)](#ssl-certificate-setup-optional)
10. [Monitoring and Maintenance](#monitoring-and-maintenance)
11. [Troubleshooting](#troubleshooting)

---

## 🔧 Prerequisites

Before starting, ensure you have:

- ✅ AWS Account with EC2 access
- ✅ GitHub repository with your code
- ✅ SSH key pair for EC2 access
- ✅ Domain name (optional, for SSL)
- ✅ Basic knowledge of Linux commands

---

## 🖥️ AWS EC2 Setup

### Step 1: Launch EC2 Instance

1. **Login to AWS Console** → Navigate to EC2 Dashboard

2. **Launch Instance**:
   - **Name**: `MyGroup-Production-Server`
   - **AMI**: Ubuntu Server 22.04 LTS (Free tier eligible)
   - **Instance Type**: `t2.micro` (Free tier) or `t3.small` (Recommended for production)
   - **Key Pair**: Create new or select existing SSH key pair
   - **Security Group**: Create new with following rules:

   | Type | Protocol | Port Range | Source | Description |
   |------|----------|------------|--------|-------------|
   | SSH | TCP | 22 | Your IP | SSH access |
   | HTTP | TCP | 80 | 0.0.0.0/0 | Web traffic |
   | HTTPS | TCP | 443 | 0.0.0.0/0 | Secure web traffic |
   | Custom TCP | TCP | 5000 | 0.0.0.0/0 | Node.js app (temporary) |
   | MySQL/Aurora | TCP | 3306 | Security Group ID | Database access |

3. **Storage**: 20 GB gp3 (minimum recommended)

4. **Launch Instance** and wait for it to be in "running" state

### Step 2: Connect to EC2 Instance

```bash
# Replace with your key file and EC2 public IP
ssh -i "your-key-file.pem" ubuntu@your-ec2-public-ip

# Example:
ssh -i "mygroup-key.pem" ubuntu@13.203.76.188
```

---

## ⚙️ Server Initial Configuration

### Step 1: Update System

```bash
# Update package lists and upgrade system
sudo apt update && sudo apt upgrade -y

# Install essential packages
sudo apt install -y curl wget git unzip software-properties-common apt-transport-https ca-certificates gnupg lsb-release
```

### Step 2: Install Node.js (v18 LTS)

```bash
# Install Node.js 18 LTS using NodeSource repository
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Verify installation
node --version  # Should show v18.x.x
npm --version   # Should show 9.x.x or higher
```

### Step 3: Install PM2 Process Manager

```bash
# Install PM2 globally
sudo npm install -g pm2

# Verify installation
pm2 --version

# Setup PM2 startup script
pm2 startup
# Follow the instructions shown (run the sudo command it displays)
```

### Step 4: Install Nginx

```bash
# Install Nginx
sudo apt install -y nginx

# Start and enable Nginx
sudo systemctl start nginx
sudo systemctl enable nginx

# Check status
sudo systemctl status nginx

# Test Nginx (should show default page)
curl http://localhost
```

---

## 🗄️ MySQL Database Setup

### Step 1: Install MySQL Server

```bash
# Install MySQL Server
sudo apt install -y mysql-server

# Start and enable MySQL
sudo systemctl start mysql
sudo systemctl enable mysql

# Check status
sudo systemctl status mysql
```

### Step 2: Secure MySQL Installation

```bash
# Run MySQL security script
sudo mysql_secure_installation

# Follow prompts:
# - Set root password: MyGroup@2025
# - Remove anonymous users: Y
# - Disallow root login remotely: Y
# - Remove test database: Y
# - Reload privilege tables: Y
```

### Step 3: Create Database and User

```bash
# Login to MySQL as root
sudo mysql -u root -p
# Enter password: MyGroup@2025
```

```sql
-- Create database
CREATE DATABASE my_group CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user and grant privileges
CREATE USER 'mygroup_user'@'localhost' IDENTIFIED BY 'MyGroup@2025';
GRANT ALL PRIVILEGES ON my_group.* TO 'mygroup_user'@'localhost';

-- Grant privileges to root for application access
GRANT ALL PRIVILEGES ON my_group.* TO 'root'@'localhost';
FLUSH PRIVILEGES;

-- Verify database creation
SHOW DATABASES;

-- Exit MySQL
EXIT;
```

### Step 4: Test Database Connection

```bash
# Test connection with application credentials
mysql -u root -p my_group
# Enter password: MyGroup@2025

# If successful, you should see MySQL prompt
# Type EXIT; to exit
```

---

## 📁 Clone and Setup Application

### Step 1: Clone Repository

```bash
# Navigate to home directory
cd /home/ubuntu

# Clone your repository (replace with your actual repo URL)
git clone https://github.com/your-username/MyGroupFullStack.git

# If repository name is different, rename it
mv MyGroupFullStack MyGroupFullStak

# Navigate to project directory
cd MyGroupFullStak

# Verify project structure
ls -la
```

### Step 2: Install Dependencies

```bash
# Install root dependencies (includes both client and server dependencies)
npm install

# Note: Your project uses a monorepo structure where:
# - Root package.json contains all dependencies for both client and server
# - client/ contains React frontend source code
# - server/ contains Node.js backend source code
# - server/package.json is for server-specific configuration only
```

### Step 3: Setup Environment Variables

```bash
# Copy production environment file
cp deployment/production.env .env

# Edit environment variables if needed
nano .env
```

Ensure your `.env` file contains:
```env
# Database Configuration
DB_HOST=localhost
DB_PORT=3306
DB_USER=root
DB_PASSWORD=MyGroup@2025
DB_NAME=my_group

# Server Configuration
NODE_ENV=production
PORT=5000
HOST=0.0.0.0

# JWT Configuration
JWT_SECRET=your_very_secure_jwt_secret_key_here
JWT_EXPIRES_IN=7d

# Session Configuration
SESSION_SECRET=your_very_secure_session_secret_here
```

### Step 4: Build Application

```bash
# Set Node.js memory limit for build
export NODE_OPTIONS="--max-old-space-size=2048"

# Build the application (builds both client and server)
npm run build:production

# Verify build output
ls -la dist/
ls -la dist/index.js        # Server bundle
ls -la dist/public/         # Client static files
ls -la dist/public/index.html

# Alternative build commands if needed:
# npm run build:client       # Build only React frontend
# npm run build:server       # Build only Node.js backend
# npm run build              # Build both (development mode)
```

### Step 5: Create Required Directories

```bash
# Create upload directory
sudo mkdir -p /var/www/uploads
sudo chown -R www-data:www-data /var/www/uploads
sudo chmod 755 /var/www/uploads

# Create logs directory
mkdir -p /home/ubuntu/MyGroupFullStak/logs
```

---

## 🔄 PM2 Process Manager Setup

### Step 1: Configure PM2 Ecosystem

Your project already has PM2 configuration files. Let's use the CommonJS version:

```bash
# Navigate to project directory
cd /home/ubuntu/MyGroupFullStak

# View the PM2 configuration
cat deployment/ecosystem.config.cjs
```

### Step 2: Start Application with PM2

```bash
# Stop any existing processes
pm2 delete all || true

# Start application using ecosystem config
pm2 start deployment/ecosystem.config.cjs --env production

# Check PM2 status
pm2 status

# View logs
pm2 logs mygroup-app --lines 20

# Save PM2 configuration
pm2 save
```

### Step 3: Setup PM2 Auto-restart

```bash
# Generate startup script
pm2 startup

# The command will show a sudo command to run, execute it
# Example: sudo env PATH=$PATH:/usr/bin /usr/lib/node_modules/pm2/bin/pm2 startup systemd -u ubuntu --hp /home/ubuntu

# Save current PM2 processes
pm2 save
```

### Step 4: Test Application

```bash
# Test local connection
curl -I http://localhost:5000/api/test

# Test external connection (replace with your EC2 IP)
curl -I http://your-ec2-public-ip:5000/api/test
```

---

## 🌐 Nginx Web Server Configuration

### Step 1: Configure Nginx

```bash
# Copy Nginx configuration
sudo cp /home/ubuntu/MyGroupFullStak/deployment/nginx.conf /etc/nginx/sites-available/mygroup

# Update the configuration for correct paths and server name
sudo nano /etc/nginx/sites-available/mygroup
```

Update these lines in the nginx configuration:
```nginx
# Change server_name to your EC2 IP or domain
server_name your-ec2-public-ip;  # Replace with your actual IP or domain

# Ensure root path points to the correct location
root /var/www/html;  # This should serve your React app from dist/public/

# The configuration should proxy /api/ requests to your Node.js server on port 5000
```

**Important**: Your nginx.conf has the wrong path. It should point to `/var/www/html` (where we copy the built files) not `/home/ubuntu/MyGroupFullStack/dist/public`.

### Step 2: Enable Site

```bash
# Create symbolic link to enable site
sudo ln -sf /etc/nginx/sites-available/mygroup /etc/nginx/sites-enabled/mygroup

# Remove default site
sudo rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
sudo nginx -t

# If test passes, reload Nginx
sudo systemctl reload nginx
```

### Step 3: Setup Static File Serving

```bash
# Create web root directory
sudo mkdir -p /var/www/html

# Copy built React frontend files from dist/public/
sudo cp -r /home/ubuntu/MyGroupFullStak/dist/public/* /var/www/html/

# Set proper permissions
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html

# Verify frontend files are copied correctly
ls -la /var/www/html/
# Should show: index.html, assets/, and other React build files
```

### Step 4: Test Complete Setup

```bash
# Test API endpoint
curl -I http://your-ec2-public-ip/api/test

# Test frontend
curl -I http://your-ec2-public-ip/

# Check Nginx logs if issues
sudo tail -f /var/log/nginx/error.log
```

---

## 🔄 GitHub Actions CI/CD Setup

Your project already has GitHub Actions configured. Here's how to complete the setup:

### Step 1: Configure GitHub Secrets

Go to your GitHub repository → Settings → Secrets and variables → Actions

Add these secrets:

1. **EC2_HOST**
   - Value: Your EC2 public IP address (e.g., `13.203.76.188`)

2. **EC2_SSH_KEY**
   - Value: Your complete private SSH key content
   ```
   -----BEGIN RSA PRIVATE KEY-----
   [Your private key content here]
   -----END RSA PRIVATE KEY-----
   ```

### Step 2: Update Workflow Configuration

Your workflow file `.github/workflows/build-frontend.yml` is already configured. Here's what it does:

```yaml
# The workflow will:
# 1. Install dependencies (npm ci)
# 2. Build React frontend (npm run build:web) → creates dist/public/
# 3. Upload server/ directory as artifact
# 4. Upload dist/ directory (containing built files) as artifact
# 5. Deploy server/ to /home/ubuntu/MyGroupFullStak/server
# 6. Deploy dist/ to /var/www/html (for React frontend)
# 7. Restart PM2 processes on the server
```

**Note**: The workflow builds the frontend using `npm run build:web` which uses Vite to build the React app from `client/` to `dist/public/`.

### Step 3: Test Automated Deployment

```bash
# Make a small change to your code
echo "// Updated $(date)" >> README.md

# Commit and push
git add .
git commit -m "Test automated deployment"
git push origin main

# Monitor GitHub Actions
# Go to GitHub → Your Repo → Actions tab
```

### Step 4: Verify Deployment

After GitHub Actions completes:

```bash
# SSH to your EC2 instance
ssh -i "your-key.pem" ubuntu@your-ec2-ip

# Check deployed files
ls -la /home/ubuntu/MyGroupFullStak/server/
ls -la /var/www/html/

# Check PM2 status
pm2 status
pm2 logs --lines 20

# Test application
curl -I http://your-ec2-ip/api/test
```

---

## 🔒 SSL Certificate Setup (Optional)

### Step 1: Install Certbot

```bash
# Install Certbot for Let's Encrypt
sudo apt install -y certbot python3-certbot-nginx
```

### Step 2: Obtain SSL Certificate

```bash
# Replace with your domain name
sudo certbot --nginx -d your-domain.com -d www.your-domain.com

# Follow prompts:
# - Enter email address
# - Agree to terms
# - Choose redirect option (recommended: 2)
```

### Step 3: Test SSL Renewal

```bash
# Test automatic renewal
sudo certbot renew --dry-run

# Setup automatic renewal (already configured by default)
sudo systemctl status certbot.timer
```

---

## 📊 Monitoring and Maintenance

### Step 1: Setup Log Rotation

```bash
# Create logrotate configuration
sudo nano /etc/logrotate.d/mygroup
```

Add this content:
```
/home/ubuntu/MyGroupFullStak/logs/*.log {
    daily
    missingok
    rotate 52
    compress
    delaycompress
    notifempty
    create 644 ubuntu ubuntu
    postrotate
        pm2 reloadLogs
    endscript
}
```

### Step 2: Setup Monitoring Commands

Create a monitoring script:
```bash
# Create monitoring script
nano /home/ubuntu/monitor.sh
```

Add this content:
```bash
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
pm2 logs --lines 10 --nostream
```

Make it executable:
```bash
chmod +x /home/ubuntu/monitor.sh
```

### Step 3: Regular Maintenance Tasks

```bash
# Weekly maintenance script
nano /home/ubuntu/maintenance.sh
```

Add this content:
```bash
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
pm2 restart all

# Check disk space
df -h

echo "Maintenance completed at $(date)"
```

Make it executable:
```bash
chmod +x /home/ubuntu/maintenance.sh
```

---

## 🔧 Troubleshooting

### Common Issues and Solutions

#### 1. Application Not Starting

```bash
# Check PM2 logs
pm2 logs mygroup-app --lines 50

# Check if port is in use
sudo netstat -tlnp | grep :5000

# Restart application
pm2 restart mygroup-app
```

#### 2. Database Connection Issues

```bash
# Test MySQL connection
mysql -u root -p my_group

# Check MySQL status
sudo systemctl status mysql

# Restart MySQL
sudo systemctl restart mysql

# Check MySQL logs
sudo tail -f /var/log/mysql/error.log
```

#### 3. Nginx Issues

```bash
# Test Nginx configuration
sudo nginx -t

# Check Nginx logs
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/log/nginx/access.log

# Restart Nginx
sudo systemctl restart nginx
```

#### 4. Build Issues

```bash
# Clear cache and rebuild
rm -rf node_modules/.vite/
rm -rf dist/
npm cache clean --force
export NODE_OPTIONS="--max-old-space-size=2048"

# Try different build approaches
npm run build:production  # Build both client and server for production
# OR
npm run build:client && npm run build:server  # Build separately
# OR
npm run build:web  # Build only React frontend (used by GitHub Actions)

# Verify build output
ls -la dist/
ls -la dist/index.js        # Server bundle should exist
ls -la dist/public/         # Client files should exist
ls -la dist/public/index.html  # React app entry point
```

#### 5. GitHub Actions Deployment Issues

```bash
# Check SSH connection
ssh -i "your-key.pem" ubuntu@your-ec2-ip

# Verify file permissions
ls -la /home/ubuntu/MyGroupFullStak/
sudo chown -R ubuntu:ubuntu /home/ubuntu/MyGroupFullStak/

# Check PM2 processes
pm2 status
pm2 restart all
```

### Emergency Recovery Commands

```bash
# Complete application restart
pm2 delete all
cd /home/ubuntu/MyGroupFullStak
pm2 start deployment/ecosystem.config.cjs --env production
pm2 save

# Complete server restart
sudo systemctl restart nginx
sudo systemctl restart mysql
pm2 restart all

# Check all services
sudo systemctl status nginx mysql
pm2 status
```

### Performance Monitoring

```bash
# Check system resources
htop  # Install with: sudo apt install htop

# Check application performance
pm2 monit

# Check database performance
mysql -u root -p -e "SHOW PROCESSLIST;"

# Check Nginx performance
sudo tail -f /var/log/nginx/access.log | grep -E "(POST|GET)"
```

---

## 🎯 Quick Reference Commands

### Daily Operations

```bash
# Check application status
pm2 status

# View recent logs
pm2 logs --lines 20

# Restart application
pm2 restart mygroup-app

# Deploy latest code (manual)
cd /home/ubuntu/MyGroupFullStak
git pull origin main
export NODE_OPTIONS="--max-old-space-size=2048"
npm run build:production
sudo cp -r dist/public/* /var/www/html/  # Update frontend files
pm2 restart mygroup-app
```

### Backup Commands

```bash
# Backup database
mysqldump -u root -p my_group > backup_$(date +%Y%m%d).sql

# Backup application files
tar -czf mygroup_backup_$(date +%Y%m%d).tar.gz /home/ubuntu/MyGroupFullStak
```

### Service Management

```bash
# Restart all services
sudo systemctl restart nginx mysql
pm2 restart all

# Check service status
sudo systemctl status nginx mysql
pm2 status

# View service logs
sudo journalctl -u nginx -f
sudo journalctl -u mysql -f
pm2 logs --lines 50
```

---

## ✅ Deployment Checklist

- [ ] EC2 instance launched and configured
- [ ] SSH access working
- [ ] Node.js 18 installed
- [ ] PM2 installed and configured
- [ ] Nginx installed and configured
- [ ] MySQL installed and database created
- [ ] Repository cloned and dependencies installed
- [ ] Application built successfully
- [ ] PM2 processes running
- [ ] Nginx serving frontend and proxying API
- [ ] GitHub Actions secrets configured
- [ ] Automated deployment working
- [ ] SSL certificate installed (optional)
- [ ] Monitoring setup
- [ ] Backup strategy in place

---

## 🚀 Success!

Your MyGroup Full Stack application is now successfully deployed on AWS EC2 with:

- ✅ **Automated CI/CD** with GitHub Actions
- ✅ **Production-ready** Node.js backend with PM2
- ✅ **High-performance** Nginx web server
- ✅ **Reliable** MySQL database
- ✅ **Scalable** infrastructure on AWS EC2
- ✅ **Secure** SSL encryption (if configured)

### Application URLs

- **Frontend**: `http://your-ec2-public-ip` or `https://your-domain.com`
- **API**: `http://your-ec2-public-ip/api` or `https://your-domain.com/api`
- **Admin Panel**: `http://your-ec2-public-ip/admin` or `https://your-domain.com/admin`

### Key Configuration Details

- **Database**: MySQL running on localhost:3306
- **Backend**: Node.js server (from `server/`) running on port 5000 via PM2
- **Frontend**: React app (from `client/`) served by Nginx on port 80/443
- **Build Output**:
  - Server bundle: `/home/ubuntu/MyGroupFullStak/dist/index.js`
  - Frontend files: `/var/www/html/` (copied from `dist/public/`)
- **Uploads**: File uploads stored in `/var/www/uploads`
- **Logs**: Application logs in `/home/ubuntu/MyGroupFullStak/logs`

### Next Steps

1. **Configure your domain** (if you have one) by updating DNS records
2. **Setup SSL certificate** using Let's Encrypt for HTTPS
3. **Configure monitoring** and alerting for production
4. **Setup automated backups** for database and files
5. **Optimize performance** based on usage patterns

### Support

For support or issues, refer to the troubleshooting section or check the application logs using:

```bash
# Application logs
pm2 logs mygroup-app --lines 50

# System monitoring
/home/ubuntu/monitor.sh

# Service status
sudo systemctl status nginx mysql
```

---

**Happy Deploying! 🎉**

Your MyGroup Full Stack application is now live and ready for production use!
