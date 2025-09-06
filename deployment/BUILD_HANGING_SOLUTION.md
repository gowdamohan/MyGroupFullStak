# npm build hanging on EC2 - Complete Solution

This document provides a comprehensive solution for fixing npm build hanging issues on AWS EC2 instances.

## Problem Analysis

The npm build hanging issue typically occurs due to:
- **Memory constraints** on EC2 instances (especially t2.micro/t2.small)
- **No swap space** configured
- **Large dependency tree** requiring significant memory during build
- **Concurrent build processes** (vite + esbuild) overwhelming resources

## Quick Fix (Recommended)

### Option 1: Automated Fix Script
```bash
# On your EC2 server
cd /home/ubuntu/MyGroupFullStak
chmod +x deployment/quick-build-fix.sh
./deployment/quick-build-fix.sh
```

### Option 2: Manual Steps
```bash
# Set memory limits
export NODE_OPTIONS="--max-old-space-size=2048"

# Clean up
rm -rf dist/ node_modules/.vite/ client/dist/
npm cache clean --force

# Install and build
npm install --no-optional
npm run build:production
```

## Comprehensive Solution

### 1. System Preparation
```bash
# Check system resources
free -h
df -h

# Create swap if memory < 2GB (requires sudo)
sudo fallocate -l 2G /swapfile
sudo chmod 600 /swapfile
sudo mkswap /swapfile
sudo swapon /swapfile
echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
```

### 2. Use the Comprehensive Fix Script
```bash
chmod +x deployment/fix-build-hanging.sh
./deployment/fix-build-hanging.sh
```

## What's Changed

### 1. Optimized vite.config.ts
- Added memory optimization settings
- Configured chunk splitting to reduce memory usage
- Disabled sourcemaps in production
- Limited parallel file operations

### 2. Updated package.json Scripts
- Split build into separate client/server builds
- Added memory limits via NODE_OPTIONS
- Created production-specific build command

### 3. New Build Scripts
- `deployment/fix-build-hanging.sh` - Comprehensive solution
- `deployment/quick-build-fix.sh` - Quick fix for immediate use

## Testing the Solution

### 1. Test Local Build (if possible)
```bash
# Test the new build commands
npm run build:client
npm run build:server
npm run build:production
```

### 2. Test on EC2
```bash
# SSH to your EC2 instance
ssh -i your-key.pem ubuntu@your-ec2-ip

# Navigate to project
cd /home/ubuntu/MyGroupFullStak

# Pull latest changes
git pull origin main

# Test the quick fix
./deployment/quick-build-fix.sh
```

### 3. Verify Build Output
```bash
# Check build files exist
ls -la dist/
ls -la dist/index.js
ls -la dist/public/
ls -la dist/public/index.html

# Check file sizes
du -sh dist/index.js
du -sh dist/public/
```

### 4. Test Application Start
```bash
# Start with PM2
pm2 start deployment/ecosystem.config.cjs --env production

# Check status
pm2 status
pm2 logs mygroup-app --lines 20

# Test API
curl -I http://localhost:5000/api/test
```

## Deployment Commands

### Complete Deployment Process
```bash
# On EC2 server
cd /home/ubuntu/MyGroupFullStak
git pull origin main

# Use the automated fix
chmod +x deployment/quick-build-fix.sh
./deployment/quick-build-fix.sh

# Start application
pm2 delete all || true
pm2 start deployment/ecosystem.config.cjs --env production
pm2 save
```

### Alternative Manual Process
```bash
# Set environment
export NODE_ENV=production
export NODE_OPTIONS="--max-old-space-size=2048"

# Clean and build
rm -rf dist/ node_modules/.vite/
npm cache clean --force
npm install --no-optional
npm run build:production

# Start application
pm2 delete all || true
pm2 start deployment/ecosystem.config.cjs --env production
```

## Troubleshooting

### If Build Still Hangs
1. **Check memory usage during build:**
   ```bash
   # In another terminal, monitor memory
   watch -n 1 'free -h && echo "---" && ps aux --sort=-%mem | head -10'
   ```

2. **Try building in parts:**
   ```bash
   npm run build:client
   # Wait for completion, then:
   npm run build:server
   ```

3. **Increase EC2 instance size temporarily:**
   - Stop instance
   - Change instance type to t3.small or t3.medium
   - Start instance and build
   - Change back to smaller instance if needed

### If Memory Issues Persist
```bash
# Check swap
free -h

# Create larger swap file
sudo swapoff /swapfile
sudo rm /swapfile
sudo fallocate -l 4G /swapfile
sudo chmod 600 /swapfile
sudo mkswap /swapfile
sudo swapon /swapfile
```

### Build Verification Checklist
- [ ] `dist/index.js` exists and is > 100KB
- [ ] `dist/public/index.html` exists
- [ ] `dist/public/assets/` contains CSS and JS files
- [ ] PM2 can start the application without errors
- [ ] API endpoints respond correctly
- [ ] Frontend loads without errors

## Performance Monitoring

### Monitor Build Process
```bash
# Check memory usage
free -h

# Check disk space
df -h

# Monitor build in real-time
npm run build:production 2>&1 | tee build.log
```

### Monitor Running Application
```bash
# PM2 monitoring
pm2 monit

# System resources
htop
```

## Success Indicators

✅ **Build completes without hanging**  
✅ **Both client and server bundles are created**  
✅ **Application starts successfully with PM2**  
✅ **API endpoints return JSON responses**  
✅ **Frontend loads correctly**  

## Support

If you continue to experience issues:
1. Check the build logs: `cat build.log`
2. Monitor system resources during build
3. Consider upgrading EC2 instance temporarily
4. Use the comprehensive fix script: `./deployment/fix-build-hanging.sh`
