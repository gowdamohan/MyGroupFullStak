# EC2 Server 404 API Error Troubleshooting

## Problem
Your EC2 server at `13.203.76.188` is returning 404 errors for API requests like `/api/auth/login`.

## Root Cause
The nginx configuration is still proxying API requests to port 3000, but your Express.js application runs on port 5000.

## Immediate Fix Steps

### Step 1: Check Current Configuration
```bash
# SSH into your EC2 server
ssh -i your-key.pem ubuntu@13.203.76.188

# Check current nginx config
sudo cat /etc/nginx/sites-enabled/default | grep -A 10 "location /api"
```

### Step 2: Update Nginx Configuration
```bash
# Edit the nginx configuration
sudo nano /etc/nginx/sites-enabled/default

# Find this section:
location /api/ {
    proxy_pass http://localhost:3000;  # ← Change this line
    ...
}

# Change it to:
location /api/ {
    proxy_pass http://localhost:5000;  # ← Fixed port
    ...
}
```

### Step 3: Test and Apply Changes
```bash
# Test nginx configuration
sudo nginx -t

# If test passes, reload nginx
sudo systemctl reload nginx
```

### Step 4: Ensure Your App Runs on Port 5000
```bash
# Check what's currently running
ps aux | grep node
pm2 list

# Stop any existing processes
pm2 delete all

# Start your app on the correct port
export NODE_ENV=production
export PORT=5000
cd /home/ubuntu/MyGroupFullStack
pm2 start dist/index.js --name mygroup-app
```

### Step 5: Test the Fix
```bash
# Test locally first
curl -I http://localhost:5000/api/test

# Then test externally
curl -I http://13.203.76.188/api/test

# Test your specific endpoint
curl -X POST http://13.203.76.188/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password"}'
```

## Complete Nginx Configuration Template

If your nginx config is missing or broken, replace it with this:

```nginx
server {
    listen 80;
    server_name 13.203.76.188;
    
    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    
    # API routes - proxy to backend
    location /api/ {
        proxy_pass http://localhost:5000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
        
        # Timeout settings
        proxy_connect_timeout 60s;
        proxy_send_timeout 60s;
        proxy_read_timeout 60s;
    }
    
    # Static files
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        root /home/ubuntu/MyGroupFullStack/dist/public;
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
    
    # Frontend routes - serve index.html for SPA
    location / {
        root /home/ubuntu/MyGroupFullStack/dist/public;
        try_files $uri $uri/ /index.html;
        index index.html;
    }
}
```

## Verification Commands

After making changes, verify everything works:

```bash
# Check nginx status
sudo systemctl status nginx

# Check your app status
pm2 status
pm2 logs mygroup-app

# Check port usage
sudo netstat -tlnp | grep :5000
sudo netstat -tlnp | grep :80

# Test API endpoints
curl http://13.203.76.188/api/test
curl -X POST http://13.203.76.188/api/auth/login -H "Content-Type: application/json" -d '{"username":"admin","password":"password"}'
```

## Common Issues

1. **Port mismatch**: Make sure both nginx and your app use port 5000
2. **App not running**: Ensure your Node.js app is running via pm2
3. **Firewall**: Make sure ports 80 and 5000 are open in your security group
4. **File permissions**: Ensure nginx can read your static files

The fix should resolve your 404 errors immediately.