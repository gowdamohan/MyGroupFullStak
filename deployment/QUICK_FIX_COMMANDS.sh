#!/bin/bash
# Quick Fix Commands for EC2 Server - Run these on your EC2 server

echo "=== EC2 Server API 404 Fix ==="
echo "Run these commands on your EC2 server (IP: 13.203.76.188)"
echo ""

echo "1. Check current nginx configuration:"
echo "   sudo cat /etc/nginx/sites-enabled/default | grep proxy_pass"
echo ""

echo "2. Check if your app is running and on which port:"
echo "   ps aux | grep node"
echo "   sudo netstat -tlnp | grep :3000"
echo "   sudo netstat -tlnp | grep :5000"
echo ""

echo "3. Update nginx configuration (CRITICAL FIX):"
echo "   sudo nano /etc/nginx/sites-enabled/default"
echo ""
echo "   Find the location /api/ block and change:"
echo "   FROM: proxy_pass http://localhost:3000;"
echo "   TO:   proxy_pass http://localhost:5000;"
echo ""

echo "4. Test and reload nginx:"
echo "   sudo nginx -t"
echo "   sudo systemctl reload nginx"
echo ""

echo "5. Make sure your app runs on port 5000:"
echo "   export PORT=5000"
echo "   export NODE_ENV=production"
echo "   pm2 delete all || true"
echo "   pm2 start dist/index.js --name mygroup-app"
echo ""

echo "6. Test the API:"
echo "   curl -I http://localhost:5000/api/test"
echo "   curl -I http://13.203.76.188/api/test"
echo ""

echo "=== Alternative Manual nginx Config ==="
cat << 'EOF'

Add this to your nginx configuration file:

server {
    listen 80;
    server_name 13.203.76.188;
    
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
    }
    
    # Serve frontend files
    location / {
        root /home/ubuntu/MyGroupFullStack/dist/public;
        try_files $uri $uri/ /index.html;
        index index.html;
    }
}

EOF