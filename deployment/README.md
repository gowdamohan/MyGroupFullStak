# EC2 Deployment Guide

This guide will help you deploy your full-stack application to AWS EC2 with MySQL database.

## Prerequisites

- AWS Account with EC2 access
- Domain name (optional, but recommended)
- SSH key pair for EC2 access

## Step-by-Step Deployment

### 1. Launch EC2 Instance

1. **Go to AWS Console** → EC2 → Launch Instance
2. **Choose AMI**: Ubuntu Server 22.04 LTS (Free Tier eligible)
3. **Instance Type**: t2.micro (Free Tier) or t3.small for better performance
4. **Key Pair**: Create or select existing key pair for SSH access
5. **Security Group**: Configure with these rules:
   - SSH (22) - Your IP
   - HTTP (80) - Anywhere (0.0.0.0/0)
   - HTTPS (443) - Anywhere (0.0.0.0/0)
   - Custom TCP (3000) - Anywhere (0.0.0.0/0) - for backend API
   - MySQL (3306) - Your IP only (for security)

### 2. Connect to EC2 Instance

```bash
# Connect via SSH (replace with your key and IP)
ssh -i your-key.pem ubuntu@your-ec2-public-ip
```

### 3. Initial Server Setup

Run these commands on your EC2 instance:

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Node.js 18.x
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Install MySQL Server
sudo apt install mysql-server -y

# Install PM2 for process management
sudo npm install -g pm2

# Install Nginx
sudo apt install nginx -y

# Install Git
sudo apt install git -y
```

### 4. Configure MySQL

```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Follow the prompts:
# - Set root password: YES (choose a strong password)
# - Remove anonymous users: YES
# - Disallow root login remotely: YES
# - Remove test database: YES
# - Reload privilege tables: YES

# Login to MySQL as root
sudo mysql -u root -p

# Create database and user for your application
CREATE DATABASE my_group;
CREATE USER 'appuser'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON my_group.* TO 'appuser'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 5. Deploy Your Application

```bash
# Clone your repository
git clone https://github.com/your-username/your-repo.git MyGroupFullStack
cd MyGroupFullStack

# Make deployment script executable
chmod +x deployment/deploy.sh

# Copy environment file and update it
cp deployment/production.env backend/.env

# Edit the environment file with your actual values
nano backend/.env
```

**Important**: Update the following values in `backend/.env`:
- `DB_PASSWORD`: Use the password you set for 'appuser'
- `JWT_SECRET`: Generate a secure random string
- `SESSION_SECRET`: Generate another secure random string
- `CORS_ORIGIN`: Your domain or EC2 public IP

### 6. Run Deployment Script

```bash
# Run the deployment script
./deployment/deploy.sh
```

This script will:
- Install dependencies
- Build the frontend
- Configure Nginx
- Start the application with PM2
- Set up auto-restart on server reboot

### 7. Configure Domain (Optional)

If you have a domain name:

1. **Point your domain to EC2**:
   - Create an A record pointing to your EC2 public IP
   - Update `CORS_ORIGIN` in your `.env` file

2. **Update Nginx configuration**:
   ```bash
   sudo nano /etc/nginx/sites-available/apphub
   # Replace 'your-domain.com' with your actual domain
   sudo nginx -t
   sudo systemctl reload nginx
   ```

### 8. Set Up SSL (Recommended)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Get SSL certificate (replace with your domain)
sudo certbot --nginx -d your-domain.com -d www.your-domain.com

# Test auto-renewal
sudo certbot renew --dry-run
```

## Post-Deployment

### Verify Everything is Working

1. **Check application status**:
   ```bash
   pm2 status
   sudo systemctl status nginx
   sudo systemctl status mysql
   ```

2. **Test your application**:
   - Frontend: `http://your-ec2-ip` or `https://your-domain.com`
   - Backend API: `http://your-ec2-ip:3000/api` or `https://your-domain.com/api`

3. **Check logs**:
   ```bash
   pm2 logs
   sudo tail -f /var/log/nginx/error.log
   ```

### Useful Commands

```bash
# PM2 commands
pm2 restart all          # Restart application
pm2 stop all            # Stop application
pm2 logs                # View logs
pm2 monit               # Monitor resources

# Nginx commands
sudo systemctl reload nginx    # Reload configuration
sudo nginx -t                 # Test configuration
sudo systemctl status nginx   # Check status

# MySQL commands
sudo systemctl status mysql   # Check MySQL status
mysql -u appuser -p my_group  # Connect to database
```

## Troubleshooting

### Common Issues

1. **Application not starting**:
   - Check PM2 logs: `pm2 logs`
   - Verify environment variables in `.env`
   - Check database connection

2. **Nginx 502 Bad Gateway**:
   - Ensure backend is running: `pm2 status`
   - Check Nginx error logs: `sudo tail -f /var/log/nginx/error.log`

3. **Database connection issues**:
   - Verify MySQL is running: `sudo systemctl status mysql`
   - Check database credentials in `.env`
   - Test connection: `mysql -u appuser -p my_group`

4. **Permission issues**:
   - Check file permissions: `ls -la`
   - Ensure upload directory is writable: `sudo chown -R www-data:www-data /var/www/uploads`

### Security Recommendations

1. **Firewall**: Configure UFW firewall
2. **Updates**: Regularly update system packages
3. **Monitoring**: Set up CloudWatch or other monitoring
4. **Backups**: Regular database backups
5. **SSL**: Always use HTTPS in production

## Maintenance

### Regular Tasks

1. **Update application**:
   ```bash
   cd MyGroupFullStack
   git pull origin main
   ./deployment/deploy.sh
   ```

2. **Database backup**:
   ```bash
   mysqldump -u appuser -p my_group > backup_$(date +%Y%m%d).sql
   ```

3. **Monitor logs**:
   ```bash
   pm2 logs --lines 100
   sudo tail -f /var/log/nginx/access.log
   ```
