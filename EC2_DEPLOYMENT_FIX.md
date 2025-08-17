# EC2 Deployment Configuration Fix

## Problem Identified
Your nginx is proxying API requests to port 3000, but your Node.js application runs on port 5000. This causes 404 errors for all `/api/*` requests.

## Files Updated in This Project
✅ **deployment/nginx.conf** - Fixed proxy_pass from localhost:3000 to localhost:5000  
✅ **deployment/ecosystem.config.js** - Updated PORT from 3000 to 5000  
✅ **deployment/production.env** - Updated PORT from 3000 to 5000  

## Steps to Fix Your EC2 Server

### 1. Update nginx Configuration
Replace your current nginx configuration with the updated one:

```bash
sudo cp /home/ubuntu/MyGroupFullStack/deployment/nginx.conf /etc/nginx/sites-available/default
sudo nginx -t  # Test configuration
sudo systemctl reload nginx
```

### 2. Update Environment Variables
Copy the updated environment file:

```bash
cd /home/ubuntu/MyGroupFullStack
cp deployment/production.env .env
source .env
```

### 3. Rebuild and Restart Application
```bash
cd /home/ubuntu/MyGroupFullStack
npm run build  # Build both client and server
pm2 delete all  # Stop all current processes
pm2 start deployment/ecosystem.config.js --env production
pm2 save  # Save current process list
```

### 4. Verify the Fix
Test your API endpoints:

```bash
# Test locally on the server
curl -I http://localhost:5000/api/test

# Test through nginx
curl -I http://localhost/api/test

# Test from external (replace with your EC2 IP)
curl -I http://3.110.218.73/api/auth/login
```

## Key Changes Made

### nginx.conf
- **OLD**: `proxy_pass http://localhost:3000;`
- **NEW**: `proxy_pass http://localhost:5000;`

### ecosystem.config.js
- **OLD**: `PORT: 3000`  
- **NEW**: `PORT: 5000`

### production.env
- **OLD**: `PORT=3000`
- **NEW**: `PORT=5000`

## Additional Troubleshooting

### Check if Node.js is running on correct port:
```bash
sudo netstat -tlnp | grep :5000
ps aux | grep node
pm2 status
pm2 logs
```

### Check nginx status:
```bash
sudo systemctl status nginx
sudo nginx -t
tail -f /var/log/nginx/error.log
```

### Verify API endpoints work:
```bash
# Should return API test response
curl http://localhost:5000/api/test

# Should return login form or auth response
curl -X POST http://localhost:5000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"test","password":"test"}'
```

## Security Notes
- The application includes proper CORS, helmet, and rate limiting middleware
- Session management is configured with secure settings
- File upload limits are set to 50MB with proper validation
- All API routes include authentication middleware where appropriate

After applying these changes, your API endpoints should work correctly at:
- `http://3.110.218.73/api/auth/login`
- `http://3.110.218.73/api/test` 
- `http://3.110.218.73/api/auth/me`
- etc.