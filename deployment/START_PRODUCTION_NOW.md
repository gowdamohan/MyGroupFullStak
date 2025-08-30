# Fix PM2 Module Error and Start Production

## Problem
The ecosystem.config.js file has a module format conflict with your ES module project setup.

## Quick Fix - Run These Commands on EC2

```bash
# Navigate to your project
cd /home/ubuntu/MyGroupFullStak

# Stop everything and clean up
pm2 delete all || true
pm2 kill || true

# Set production environment
export NODE_ENV=production
export PORT=5000
export DB_HOST=localhost
export DB_PORT=3306
export DB_USER=root
export DB_PASSWORD=MyGroup@2025
export DB_NAME=my_group

# Build for production
NODE_ENV=production npm run build

# Check build output
ls -la dist/index.js

# Start with PM2 using environment variables
pm2 start dist/index.js \
  --name "mygroup-app" \
  --env NODE_ENV=production \
  --env PORT=5000 \
  --env DB_HOST=localhost \
  --env DB_PORT=3306 \
  --env DB_USER=root \
  --env DB_PASSWORD=MyGroup@2025 \
  --env DB_NAME=my_group

# Check status
pm2 status
pm2 logs mygroup-app --lines 10
```

## Alternative: Use Fixed CommonJS Config

```bash
# Use the new CommonJS ecosystem file
pm2 start deployment/ecosystem.config.cjs --env production
```

## Test Production Mode

```bash
# Local test
curl -X POST http://localhost:5000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password"}'

# External test
curl -X POST http://13.203.76.188/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password"}'
```

## Expected Results

✅ **Before fix**: HTML with Vite development scripts
✅ **After fix**: JSON response like `{"error":"Invalid username or password"}`

The key is ensuring `NODE_ENV=production` is properly set when starting the application.