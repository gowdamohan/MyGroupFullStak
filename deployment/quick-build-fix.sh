#!/bin/bash

# Quick Build Fix for npm build hanging on EC2
# This is a simplified version of the comprehensive fix script

set -e

echo "🔧 Quick Build Fix for EC2 npm build hanging"
echo "=============================================="

# Check if we're in the right directory
if [ ! -f "package.json" ]; then
    echo "❌ Error: package.json not found. Run this from project root."
    exit 1
fi

# Step 1: Set memory limits
echo "📊 Setting Node.js memory limits..."
TOTAL_MEM=$(free -m | awk 'NR==2{printf "%.0f", $2}')
if [ "$TOTAL_MEM" -lt 2048 ]; then
    export NODE_OPTIONS="--max-old-space-size=1024"
    echo "   Set to 1024MB (low memory system: ${TOTAL_MEM}MB total)"
else
    export NODE_OPTIONS="--max-old-space-size=2048"
    echo "   Set to 2048MB (sufficient memory: ${TOTAL_MEM}MB total)"
fi

# Step 2: Clean up
echo "🧹 Cleaning up build artifacts..."
rm -rf dist/ node_modules/.vite/ client/dist/
npm cache clean --force

# Step 3: Check for swap
SWAP_SIZE=$(free -m | awk 'NR==3{printf "%.0f", $2}')
if [ "$SWAP_SIZE" -eq 0 ] && [ "$TOTAL_MEM" -lt 2048 ]; then
    echo "⚠️  Warning: No swap detected on low memory system"
    echo "   Consider creating swap: sudo fallocate -l 2G /swapfile && sudo mkswap /swapfile && sudo swapon /swapfile"
fi

# Step 4: Install dependencies
echo "📦 Installing dependencies..."
npm install --no-optional

# Step 5: Build with retry
echo "🔨 Building application..."
MAX_ATTEMPTS=3
ATTEMPT=1

while [ $ATTEMPT -le $MAX_ATTEMPTS ]; do
    echo "   Attempt $ATTEMPT of $MAX_ATTEMPTS"
    
    if npm run build:production 2>/dev/null || npm run build; then
        echo "✅ Build completed successfully on attempt $ATTEMPT"
        break
    else
        echo "❌ Build failed on attempt $ATTEMPT"
        
        if [ $ATTEMPT -lt $MAX_ATTEMPTS ]; then
            echo "   Cleaning up and retrying in 5 seconds..."
            sleep 5
            rm -rf dist/ node_modules/.vite/
            npm cache clean --force
        else
            echo "❌ Build failed after $MAX_ATTEMPTS attempts"
            echo ""
            echo "🔍 Troubleshooting suggestions:"
            echo "1. Check available memory: free -h"
            echo "2. Create swap file if memory < 2GB"
            echo "3. Try building client and server separately:"
            echo "   npm run build:client"
            echo "   npm run build:server"
            echo "4. Use the comprehensive fix script: ./deployment/fix-build-hanging.sh"
            exit 1
        fi
    fi
    
    ((ATTEMPT++))
done

# Step 6: Verify build
echo "🔍 Verifying build output..."
if [ ! -f "dist/index.js" ]; then
    echo "❌ Server build file not found: dist/index.js"
    exit 1
fi

if [ ! -d "dist/public" ] || [ ! -f "dist/public/index.html" ]; then
    echo "❌ Client build files not found in dist/public/"
    exit 1
fi

echo "✅ Build verification passed!"
echo ""
echo "📊 Build Summary:"
echo "   Server bundle: $(du -sh dist/index.js | cut -f1)"
echo "   Client bundle: $(du -sh dist/public | cut -f1)"
echo ""
echo "🚀 Ready to start application:"
echo "   pm2 start deployment/ecosystem.config.cjs --env production"
