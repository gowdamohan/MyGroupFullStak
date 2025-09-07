#!/bin/bash

# Manual deployment fix script
# Run this on your EC2 server if GitHub Actions deployment still doesn't work

echo "🔧 Manual Deployment Fix Script"
echo "================================"

# Navigate to project directory
cd /home/ubuntu/MyGroupFullStak

echo "1. Pulling latest code..."
git pull origin main

echo "2. Installing dependencies..."
npm install

echo "3. Building application..."
export NODE_OPTIONS="--max-old-space-size=2048"
npm run build:client

echo "4. Checking build output..."
ls -la dist/public/

echo "5. Stopping existing processes..."
pm2 delete all || true

echo "6. Deploying frontend..."
sudo rm -rf /var/www/html/*
sudo cp -r dist/public/* /var/www/html/
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html

echo "7. Starting backend..."
pm2 start dist/index.js --name "mygroup-app" --env NODE_ENV=production

echo "8. Reloading Nginx..."
sudo nginx -t && sudo nginx -s reload

echo "9. Verification..."
echo "Frontend files:"
ls -la /var/www/html/
echo ""
echo "PM2 status:"
pm2 status
echo ""
echo "Testing frontend:"
curl -I http://localhost/
echo ""
echo "Testing backend:"
curl -I http://localhost:5000/api/

echo "✅ Manual deployment completed!"
echo "🌐 Your app should be available at: http://$(curl -s http://checkip.amazonaws.com)"
