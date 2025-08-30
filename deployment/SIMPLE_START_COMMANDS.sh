#!/bin/bash
# Simple commands to start your app in production mode on EC2

echo "=== Simple Production Startup for EC2 ==="
echo "Use these commands instead of the ecosystem config"
echo ""

echo "1. Navigate to project directory:"
echo "   cd /home/ubuntu/MyGroupFullStak"
echo ""

echo "2. Stop existing processes:"
echo "   pm2 delete all || true"
echo ""

echo "3. Set environment and build:"
echo "   export NODE_ENV=production"
echo "   export PORT=5000"
echo "   npm run build"
echo ""

echo "4. Start with PM2 using direct command:"
echo "   pm2 start dist/index.js \\"
echo "     --name 'mygroup-app' \\"
echo "     --env NODE_ENV=production \\"
echo "     --env PORT=5000 \\"
echo "     --env DB_HOST=localhost \\"
echo "     --env DB_PORT=3306 \\"
echo "     --env DB_USER=root \\"
echo "     --env DB_PASSWORD=MyGroup@2025 \\"
echo "     --env DB_NAME=my_group"
echo ""

echo "5. Alternative: Use the CommonJS ecosystem file:"
echo "   pm2 start deployment/ecosystem.config.cjs --env production"
echo ""

echo "6. Check status:"
echo "   pm2 status"
echo "   pm2 logs mygroup-app"
echo ""

echo "7. Test API:"
echo "   curl -X POST http://localhost:5000/api/auth/login \\"
echo "     -H 'Content-Type: application/json' \\"
echo "     -d '{\"username\":\"admin\",\"password\":\"password\"}'"
echo ""

echo "Expected result: JSON response, not HTML"