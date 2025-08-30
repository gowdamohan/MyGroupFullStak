# EC2 Deployment Configuration Guide

## Issue Resolution: 404 API Errors

The 404 errors on your EC2 server were caused by port mismatches between nginx and your Express.js application.

### Fixed Configuration

**✅ Corrected Port Settings:**
- Express.js server: Port 5000 (server/index.ts)
- Nginx proxy: Port 5000 (deployment/nginx.conf) 
- PM2 ecosystem: Port 5000 (deployment/ecosystem.config.js)
- Production env: Port 5000 (deployment/production.env)

## Deployment Steps for EC2

### 1. Update Your Server Files

Copy these updated configuration files to your EC2 server:

```bash
# On your EC2 server
cd /home/ubuntu/MyGroupFullStack

# Pull latest changes
git pull origin main

# Update nginx configuration
sudo cp deployment/nginx.conf /etc/nginx/sites-available/mygroup-app
sudo ln -sf /etc/nginx/sites-available/mygroup-app /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx

# Build the application
npm install
npm run build
```

### 2. Update Environment Variables

```bash
# Copy production environment file
cp deployment/production.env .env

# Make sure these environment variables are set:
export NODE_ENV=production
export PORT=5000
export DB_HOST=localhost
export DB_PORT=3306
export DB_USER=root
export DB_PASSWORD=MyGroup@2025
export DB_NAME=my_group
```

### 3. Start/Restart the Application

```bash
# Using PM2 (recommended)
pm2 delete mygroup-app || true
pm2 start deployment/ecosystem.config.js --env production

# Or using npm directly
npm run start
```

### 4. Verify the Fix

Test your API endpoints:

```bash
# Test API connectivity
curl -I http://3.110.218.73/api/test
# Should return: HTTP/1.1 200 OK

# Test auth endpoint
curl -X POST http://3.110.218.73/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"test","password":"test123"}'
```

## Nginx Configuration Details

The key fix was updating the proxy pass in nginx.conf:

```nginx
# API routes - proxy to backend
location /api/ {
    proxy_pass http://localhost:5000;  # Changed from 3000 to 5000
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection 'upgrade';
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_cache_bypass $http_upgrade;
}
```

## Troubleshooting Commands

If you still encounter issues:

```bash
# Check if the application is running
pm2 status
pm2 logs mygroup-app

# Check nginx status
sudo systemctl status nginx
sudo nginx -t

# Check which process is using port 5000
sudo netstat -tlnp | grep :5000

# Check application logs
tail -f /var/log/pm2/apphub-backend.log
```

## Security Notes

- Update the JWT_SECRET and SESSION_SECRET in production.env with secure random values
- Consider setting up SSL/HTTPS for production
- Configure firewall rules to only allow necessary ports (80, 443, 22)

## Next Steps

1. Apply these configuration changes on your EC2 server
2. Restart nginx and your application
3. Test the API endpoints to confirm they're working
4. Set up SSL certificate for HTTPS (optional but recommended)