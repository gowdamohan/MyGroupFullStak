#!/bin/bash
# Run these diagnostic commands on your EC2 server to identify the issue

echo "=== Nginx Configuration Analysis ==="
echo "✓ Nginx is correctly configured to proxy /api/ to localhost:5000"
echo "✓ Server name matches your IP: 13.203.76.188"
echo ""

echo "=== Diagnostic Commands - Run these on your EC2 server ==="
echo ""

echo "1. Check if Node.js app is running on port 5000:"
echo "   sudo netstat -tlnp | grep :5000"
echo "   ps aux | grep node"
echo "   pm2 list"
echo ""

echo "2. Check if port 5000 is accessible locally:"
echo "   curl -v http://localhost:5000/api/test"
echo "   curl -v http://localhost:5000/"
echo ""

echo "3. Check if your app build exists:"
echo "   ls -la /home/ubuntu/MyGroupFullStak/dist/"
echo "   ls -la /home/ubuntu/MyGroupFullStak/dist/index.js"
echo ""

echo "4. Check nginx error logs:"
echo "   sudo tail -f /var/log/nginx/error.log"
echo "   sudo tail -f /var/log/nginx/access.log"
echo ""

echo "5. If app is not running, start it:"
echo "   cd /home/ubuntu/MyGroupFullStak"
echo "   export NODE_ENV=production"
echo "   export PORT=5000"
echo "   npm run build"
echo "   pm2 delete all || true"
echo "   pm2 start dist/index.js --name mygroup-app"
echo ""

echo "6. Test the fix:"
echo "   curl -I http://localhost:5000/api/test"
echo "   curl -X POST http://localhost:5000/api/auth/login -H 'Content-Type: application/json' -d '{\"username\":\"admin\",\"password\":\"password\"}'"
echo ""

echo "=== Most Likely Issues ==="
echo "1. Node.js app not running on port 5000"
echo "2. Build files missing in dist/ directory"
echo "3. Environment variables not set correctly"
echo "4. PM2 process crashed or not started"