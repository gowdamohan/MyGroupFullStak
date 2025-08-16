#!/bin/bash

# Troubleshooting Script for EC2 Deployment Issues

echo "🔍 EC2 Deployment Troubleshooting"
echo "=================================="

# Check if services are running
echo ""
echo "📊 Service Status:"
echo "-------------------"
echo "PM2 Status:"
pm2 status 2>/dev/null || echo "❌ PM2 not running or no processes"

echo ""
echo "Nginx Status:"
sudo systemctl status nginx --no-pager -l || echo "❌ Nginx not running"

echo ""
echo "MySQL Status:"
sudo systemctl status mysql --no-pager -l || echo "❌ MySQL not running"

# Check ports
echo ""
echo "🔌 Port Status:"
echo "---------------"
echo "Checking if ports are listening:"
sudo netstat -tlnp | grep -E ':80|:443|:3000|:3306' || echo "❌ No services listening on expected ports"

# Check firewall
echo ""
echo "🔥 Firewall Status:"
echo "-------------------"
sudo ufw status || echo "UFW not configured"

# Check processes
echo ""
echo "🏃 Running Processes:"
echo "--------------------"
ps aux | grep -E 'node|nginx|mysql' | grep -v grep || echo "❌ No relevant processes found"

# Check logs
echo ""
echo "📝 Recent Logs:"
echo "---------------"
echo "PM2 Logs (last 10 lines):"
pm2 logs --lines 10 2>/dev/null || echo "❌ No PM2 logs available"

echo ""
echo "Nginx Error Log (last 5 lines):"
sudo tail -5 /var/log/nginx/error.log 2>/dev/null || echo "❌ No Nginx error log"

# Check disk space
echo ""
echo "💾 Disk Usage:"
echo "---------------"
df -h

# Check memory
echo ""
echo "🧠 Memory Usage:"
echo "----------------"
free -h

echo ""
echo "🔧 Quick Fixes:"
echo "==============="
echo "1. Start services:"
echo "   sudo systemctl start nginx"
echo "   sudo systemctl start mysql"
echo "   pm2 start all"
echo ""
echo "2. Check Security Group in AWS Console:"
echo "   - Port 80 (HTTP): 0.0.0.0/0"
echo "   - Port 443 (HTTPS): 0.0.0.0/0"
echo "   - Port 3000 (API): 0.0.0.0/0"
echo ""
echo "3. Restart deployment:"
echo "   cd /home/ubuntu/MyGroupFullStack"
echo "   ./deployment/deploy.sh"
