#!/bin/bash
# Fix for EC2 Server - Force Production Mode

echo "=== Production Mode Fix for EC2 Server ==="
echo "Your API is responding (200 OK) but serving development HTML instead of JSON"
echo ""

echo "Run these commands on your EC2 server:"
echo ""

echo "1. Stop all Node.js processes:"
echo "   pm2 delete all"
echo "   pkill -f 'node'"
echo ""

echo "2. Set production environment variables:"
echo "   export NODE_ENV=production"
echo "   export PORT=5000"
echo "   export DB_HOST=localhost"
echo "   export DB_PORT=3306"
echo "   export DB_USER=root"
echo "   export DB_PASSWORD=MyGroup@2025"
echo "   export DB_NAME=my_group"
echo ""

echo "3. Navigate to project and rebuild:"
echo "   cd /home/ubuntu/MyGroupFullStak"
echo "   npm install"
echo "   NODE_ENV=production npm run build"
echo ""

echo "4. Verify build output:"
echo "   ls -la dist/"
echo "   ls -la dist/public/"
echo "   ls -la dist/index.js"
echo ""

echo "5. Start in production mode with environment variables:"
echo "   NODE_ENV=production PORT=5000 pm2 start dist/index.js --name mygroup-app"
echo ""

echo "6. Or use the ecosystem config for proper production setup:"
echo "   pm2 start /home/ubuntu/MyGroupFullStak/deployment/ecosystem.config.js --env production"
echo ""

echo "7. Verify production mode:"
echo "   pm2 logs mygroup-app | grep 'Environment:'"
echo "   curl -X POST http://localhost:5000/api/auth/login -H 'Content-Type: application/json' -d '{\"username\":\"admin\",\"password\":\"password\"}'"
echo ""

echo "=== Expected Result ==="
echo "You should get JSON response like:"
echo '{"error":"Invalid username or password"}'
echo "Instead of HTML with Vite development scripts"