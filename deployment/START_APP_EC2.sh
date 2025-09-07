#!/bin/bash
# Complete script to build and start your app on EC2

echo "=== Starting MyGroup App on EC2 ==="
echo "Run this script on your EC2 server at: 13.203.76.188"
echo ""

# Navigate to project directory
cd /home/ubuntu/MyGroupFullStak

echo "1. Setting environment variables..."
export NODE_ENV=production
export PORT=5000
export DB_HOST=localhost
export DB_PORT=3306
export DB_USER=root
export DB_PASSWORD=MyGroup@2025!
export DB_NAME=my_group

echo "2. Installing dependencies..."
npm install

echo "3. Building the application with memory optimization..."
# Use the build hanging fix script
if [ -f "deployment/fix-build-hanging.sh" ]; then
    chmod +x deployment/fix-build-hanging.sh
    ./deployment/fix-build-hanging.sh
else
    # Fallback to manual build with memory limits
    export NODE_OPTIONS="--max-old-space-size=2048"
    npm run build:production || npm run build
fi

echo "4. Checking build output..."
ls -la dist/
ls -la dist/index.js

echo "5. Stopping any existing processes..."
pm2 delete all || true
pkill -f "node.*index.js" || true

echo "6. Starting the application with PM2..."
pm2 start dist/index.js \
  --name "mygroup-app" \
  --env NODE_ENV=production \
  --env PORT=5000 \
  --env DB_HOST=localhost \
  --env DB_PORT=3306 \
  --env DB_USER=root \
  --env DB_PASSWORD=MyGroup@2025 \
  --env DB_NAME=my_group

echo "7. Checking PM2 status..."
pm2 status
pm2 logs mygroup-app --lines 10

echo "8. Testing local connectivity..."
sleep 3
curl -I http://localhost:5000/api/test || echo "Local API test failed"

echo "9. Testing external connectivity..."
curl -I http://13.203.76.188/api/test || echo "External API test failed"

echo ""
echo "=== Troubleshooting ==="
echo "If tests fail, check:"
echo "- pm2 logs mygroup-app"
echo "- sudo tail -f /var/log/nginx/error.log"
echo "- sudo netstat -tlnp | grep :5000"
echo ""
echo "Manual test command:"
echo "curl -X POST http://13.203.76.188/api/auth/login -H 'Content-Type: application/json' -d '{\"username\":\"admin\",\"password\":\"password\"}'"