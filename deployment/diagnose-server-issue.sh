#!/bin/bash

# Server Issue Diagnostic Script
# This script helps diagnose why updated code isn't reflecting in the UI

echo "🔍 Server Issue Diagnostic Report"
echo "=================================="
echo "Timestamp: $(date)"
echo ""

# Check current directory
echo "📁 Current Directory:"
pwd
echo ""

# Check if we're in the right location
if [ ! -f "package.json" ]; then
    echo "❌ ERROR: package.json not found in current directory"
    echo "   Please run this from: /home/ubuntu/MyGroupFullStak"
    echo ""
    echo "   Try: cd /home/ubuntu/MyGroupFullStak && ./deployment/diagnose-server-issue.sh"
    exit 1
fi

echo "✅ Found package.json - in correct directory"
echo ""

# Check Git status
echo "📊 Git Status:"
echo "-------------"
git status --porcelain || echo "Git not available or not a git repository"
echo ""

echo "📅 Last Git Commit:"
echo "------------------"
git log -1 --oneline || echo "No git history available"
echo ""

# Check build files
echo "🔨 Build Files Status:"
echo "---------------------"
echo "dist/ directory:"
if [ -d "dist" ]; then
    ls -la dist/
    echo ""
    echo "dist/public/ directory:"
    if [ -d "dist/public" ]; then
        ls -la dist/public/ | head -10
        echo ""
        echo "dist/public/index.html exists: $([ -f "dist/public/index.html" ] && echo "✅ YES" || echo "❌ NO")"
        if [ -f "dist/public/index.html" ]; then
            echo "dist/public/index.html size: $(du -sh dist/public/index.html | cut -f1)"
            echo "dist/public/index.html modified: $(stat -c %y dist/public/index.html 2>/dev/null || stat -f %Sm dist/public/index.html 2>/dev/null || echo "unknown")"
        fi
    else
        echo "❌ dist/public/ directory not found"
    fi
    echo ""
    echo "dist/index.js exists: $([ -f "dist/index.js" ] && echo "✅ YES" || echo "❌ NO")"
    if [ -f "dist/index.js" ]; then
        echo "dist/index.js size: $(du -sh dist/index.js | cut -f1)"
        echo "dist/index.js modified: $(stat -c %y dist/index.js 2>/dev/null || stat -f %Sm dist/index.js 2>/dev/null || echo "unknown")"
    fi
else
    echo "❌ dist/ directory not found - application not built"
fi
echo ""

# Check source files
echo "📝 Source Files Status:"
echo "----------------------"
echo "client/src/index.css exists: $([ -f "client/src/index.css" ] && echo "✅ YES" || echo "❌ NO")"
if [ -f "client/src/index.css" ]; then
    echo "client/src/index.css modified: $(stat -c %y client/src/index.css 2>/dev/null || stat -f %Sm client/src/index.css 2>/dev/null || echo "unknown")"
    echo "client/src/index.css size: $(du -sh client/src/index.css | cut -f1)"
fi
echo ""

echo "server/index.ts exists: $([ -f "server/index.ts" ] && echo "✅ YES" || echo "❌ NO")"
if [ -f "server/index.ts" ]; then
    echo "server/index.ts modified: $(stat -c %y server/index.ts 2>/dev/null || stat -f %Sm server/index.ts 2>/dev/null || echo "unknown")"
fi
echo ""

# Check PM2 status
echo "🚀 PM2 Process Status:"
echo "---------------------"
if command -v pm2 >/dev/null 2>&1; then
    pm2 status
    echo ""
    echo "PM2 Logs (last 10 lines):"
    pm2 logs --lines 10 2>/dev/null || echo "No PM2 logs available"
else
    echo "❌ PM2 not installed or not in PATH"
fi
echo ""

# Check running processes
echo "🏃 Running Node Processes:"
echo "-------------------------"
ps aux | grep -E 'node|npm' | grep -v grep || echo "No Node.js processes found"
echo ""

# Check ports
echo "🔌 Port Status:"
echo "--------------"
echo "Port 5000 status:"
if command -v netstat >/dev/null 2>&1; then
    netstat -tlnp | grep :5000 || echo "Nothing listening on port 5000"
elif command -v ss >/dev/null 2>&1; then
    ss -tlnp | grep :5000 || echo "Nothing listening on port 5000"
else
    echo "netstat/ss not available"
fi
echo ""

# Check environment
echo "🌍 Environment Variables:"
echo "------------------------"
echo "NODE_ENV: ${NODE_ENV:-not set}"
echo "PORT: ${PORT:-not set}"
echo "NODE_OPTIONS: ${NODE_OPTIONS:-not set}"
echo ""

# Check package.json scripts
echo "📦 Available Scripts:"
echo "-------------------"
if [ -f "package.json" ]; then
    grep -A 10 '"scripts"' package.json | head -15
else
    echo "package.json not found"
fi
echo ""

# Check disk space
echo "💾 Disk Space:"
echo "-------------"
df -h . | head -2
echo ""

# Check memory
echo "🧠 Memory Usage:"
echo "---------------"
free -h 2>/dev/null || echo "free command not available"
echo ""

# Test connectivity
echo "🌐 Connectivity Test:"
echo "--------------------"
echo "Testing localhost:5000..."
if command -v curl >/dev/null 2>&1; then
    if curl -f -s -m 5 http://localhost:5000/ > /dev/null; then
        echo "✅ localhost:5000 is responding"
    else
        echo "❌ localhost:5000 is not responding"
    fi
    
    echo "Testing localhost:5000/api/test..."
    if curl -f -s -m 5 http://localhost:5000/api/test > /dev/null; then
        echo "✅ API endpoint is responding"
    else
        echo "❌ API endpoint is not responding"
    fi
else
    echo "curl not available for testing"
fi
echo ""

# Summary and recommendations
echo "📋 DIAGNOSTIC SUMMARY:"
echo "====================="

BUILD_EXISTS=$([ -f "dist/index.js" ] && [ -d "dist/public" ] && echo "true" || echo "false")
PM2_RUNNING=$(pm2 status 2>/dev/null | grep -q "online" && echo "true" || echo "false")
PORT_LISTENING=$(netstat -tlnp 2>/dev/null | grep -q ":5000" && echo "true" || echo "false")

echo "Build files exist: $([ "$BUILD_EXISTS" = "true" ] && echo "✅ YES" || echo "❌ NO")"
echo "PM2 processes running: $([ "$PM2_RUNNING" = "true" ] && echo "✅ YES" || echo "❌ NO")"
echo "Port 5000 listening: $([ "$PORT_LISTENING" = "true" ] && echo "✅ YES" || echo "❌ NO")"
echo ""

echo "🔧 RECOMMENDED ACTIONS:"
echo "======================"

if [ "$BUILD_EXISTS" = "false" ]; then
    echo "1. ❗ Build files missing - run the build fix script:"
    echo "   chmod +x deployment/fix-server-build.sh"
    echo "   ./deployment/fix-server-build.sh"
    echo ""
fi

if [ "$PM2_RUNNING" = "false" ]; then
    echo "2. ❗ No PM2 processes running - start the application:"
    echo "   pm2 start deployment/ecosystem.config.cjs --env production"
    echo ""
fi

if [ "$PORT_LISTENING" = "false" ]; then
    echo "3. ❗ Nothing listening on port 5000 - check application startup:"
    echo "   pm2 logs --lines 20"
    echo ""
fi

echo "🚀 QUICK FIX COMMAND:"
echo "===================="
echo "Run this to fix most issues:"
echo "chmod +x deployment/fix-server-build.sh && ./deployment/fix-server-build.sh"
echo ""

echo "Diagnostic complete! $(date)"
