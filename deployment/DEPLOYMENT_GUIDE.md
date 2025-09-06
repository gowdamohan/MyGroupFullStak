# Complete EC2 Deployment Guide

This guide provides the complete process to deploy your React + Express.js application to an EC2 server.

## Prerequisites

- EC2 instance running Ubuntu
- Nginx installed
- PM2 installed globally
- Node.js and npm installed
- MySQL server running (if using database)

## Quick Start Deployment Process

### 1. Prepare Your Application

On your local machine or in Replit:

```bash
# Build the application
npm install
npm run build

# Verify build output
ls -la dist/
ls -la dist/public/
ls -la dist/index.js
```

### 2. Deploy to EC2 Server

SSH into your EC2 server and run these commands:

```bash
# Navigate to project directory
cd /home/ubuntu/MyGroupFullStak

# Pull latest changes
git pull origin main

# Install dependencies and build (with build hanging fix)
npm install

# Option 1: Use the automated fix script (recommended)
chmod +x deployment/fix-build-hanging.sh
./deployment/fix-build-hanging.sh

# Option 2: Manual build with memory optimization
export NODE_OPTIONS="--max-old-space-size=2048"
npm run build:production

# Set up environment variables
cp deployment/production.env .env

# Create logs directory for PM2
mkdir -p logs

# Update nginx configuration
sudo cp deployment/nginx.conf /etc/nginx/sites-available/mygroup
sudo ln -sf /etc/nginx/sites-available/mygroup /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx

# Stop existing processes
pm2 delete all || true

# Start application with PM2
pm2 start deployment/ecosystem.config.cjs --env production

# Save PM2 configuration
pm2 save
pm2 startup
```

### 3. Verify Deployment

Test your application:

```bash
# Check PM2 status
pm2 status
pm2 logs mygroup-app --lines 20

# Test local API
curl -I http://localhost:5000/api/test

# Test external API
curl -I http://13.203.76.188/api/test

# Test authentication endpoint
curl -X POST http://13.203.76.188/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password"}'
```

## Configuration Files

### Key Configuration Details

**Port Configuration:**
- Express.js server: Port 5000
- Nginx proxy: Port 5000 
- PM2 ecosystem: Port 5000

**Root Directory:**
- Static files served from: `/home/ubuntu/MyGroupFullStak/dist/public`
- Express.js entry point: `/home/ubuntu/MyGroupFullStak/dist/index.js`

## Troubleshooting

### Common Issues

**Build Hanging Issues:**
- Run the automated fix: `./deployment/fix-build-hanging.sh`
- Check available memory: `free -h`
- Create swap file if memory < 2GB: `sudo fallocate -l 2G /swapfile && sudo mkswap /swapfile && sudo swapon /swapfile`
- Use memory-optimized build: `NODE_OPTIONS="--max-old-space-size=2048" npm run build:production`

**404 API Errors:**
- Check nginx configuration has `proxy_pass http://localhost:5000`
- Verify Express.js is running on port 5000
- Ensure PM2 is using correct environment variables

**Production Mode Issues:**
- Verify `NODE_ENV=production` is set
- Check that `npm run build` completed successfully
- Ensure PM2 is using production environment

**Useful Debugging Commands:**

```bash
# Check what's running on port 5000
sudo netstat -tlnp | grep :5000

# Check nginx configuration
sudo nginx -t
sudo cat /etc/nginx/sites-enabled/mygroup | grep proxy_pass

# Check PM2 logs
pm2 logs mygroup-app

# Check nginx logs
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/log/nginx/access.log

# Restart services
sudo systemctl restart nginx
pm2 restart mygroup-app
```

## Environment Variables

The following environment variables are configured in `deployment/production.env`:

```bash
NODE_ENV=production
PORT=5000
HOST=0.0.0.0
DB_HOST=localhost
DB_PORT=3306
DB_USER=root
DB_PASSWORD=MyGroup@2025
DB_NAME=my_group
```

## Manual Startup (Alternative)

If PM2 ecosystem config doesn't work, use direct commands:

```bash
# Set environment variables
export NODE_ENV=production
export PORT=5000
export DB_HOST=localhost
export DB_PORT=3306
export DB_USER=root
export DB_PASSWORD=MyGroup@2025
export DB_NAME=my_group

# Start with PM2
pm2 start dist/index.js \
  --name "mygroup-app" \
  --env NODE_ENV=production \
  --env PORT=5000 \
  --env DB_HOST=localhost \
  --env DB_PORT=3306 \
  --env DB_USER=root \
  --env DB_PASSWORD=MyGroup@2025 \
  --env DB_NAME=my_group
```

## Expected Results

After successful deployment:

✅ **API endpoints respond with JSON** (not HTML)  
✅ **Frontend loads correctly** at http://13.203.76.188  
✅ **Authentication works** with proper error messages  
✅ **Static files cached** with appropriate headers  
✅ **Nginx proxy** correctly forwards /api requests to backend  

## Support

If you encounter issues:

1. Check PM2 logs: `pm2 logs mygroup-app`
2. Check nginx logs: `sudo tail -f /var/log/nginx/error.log`
3. Verify port configuration matches across all files
4. Ensure NODE_ENV=production is set correctly