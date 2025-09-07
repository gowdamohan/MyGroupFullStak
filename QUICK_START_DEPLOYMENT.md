# 🚀 Quick Start Deployment Guide - MyGroup Full Stack

This is a condensed deployment guide for your MyGroup application with the correct project structure.

## 📁 Project Structure

```
MyGroupFullStak/
├── client/                 # React frontend (Vite)
│   ├── src/
│   ├── index.html
│   └── ...
├── server/                 # Node.js backend (TypeScript)
│   ├── index.ts
│   ├── package.json
│   └── ...
├── shared/                 # Shared types/schemas
├── deployment/             # Deployment configs
├── package.json           # Root package with build scripts
└── vite.config.ts         # Vite configuration
```

## 🏗️ Build Process

- **Client**: `npm run build:client` → `dist/public/` (React app)
- **Server**: `npm run build:server` → `dist/index.js` (Node.js bundle)
- **Production**: `npm run build:production` → Builds both

## ⚡ Quick Deployment Steps

### 1. Launch EC2 Instance
- Ubuntu 22.04 LTS
- t2.micro or t3.small
- Security groups: SSH (22), HTTP (80), HTTPS (443), Custom TCP (5000)

### 2. Initial Server Setup
```bash
# SSH to EC2
ssh -i "your-key.pem" ubuntu@your-ec2-ip

# Run automated setup
wget https://raw.githubusercontent.com/your-repo/MyGroupFullStack/main/deployment/ec2-initial-setup.sh
chmod +x ec2-initial-setup.sh
./ec2-initial-setup.sh
```

### 3. Setup MySQL
```bash
# Secure MySQL installation
sudo mysql_secure_installation
# Password: MyGroup@2025

# Setup database
wget https://raw.githubusercontent.com/your-repo/MyGroupFullStack/main/deployment/mysql-setup.sh
chmod +x mysql-setup.sh
./mysql-setup.sh
```

### 4. Clone and Deploy Application
```bash
# Clone repository
git clone https://github.com/your-username/MyGroupFullStack.git
mv MyGroupFullStack MyGroupFullStak
cd MyGroupFullStak

# Run deployment script
chmod +x deployment/deploy-mygroup.sh
./deployment/deploy-mygroup.sh
```

### 5. Setup GitHub Actions (Optional)
1. Go to GitHub → Settings → Secrets → Actions
2. Add secrets:
   - `EC2_HOST`: Your EC2 public IP
   - `EC2_SSH_KEY`: Your private SSH key content
3. Push code to trigger automated deployment

## 🔧 Manual Deployment Commands

```bash
# Navigate to project
cd /home/ubuntu/MyGroupFullStak

# Pull latest code
git pull origin main

# Install dependencies
npm install

# Build application
export NODE_OPTIONS="--max-old-space-size=2048"
npm run build:production

# Deploy frontend
sudo cp -r dist/public/* /var/www/html/

# Restart backend
pm2 restart mygroup-app

# Check status
pm2 status
pm2 logs mygroup-app --lines 20
```

## 📊 Service Architecture

```
Internet → Nginx (Port 80/443) → {
  Static Files: /var/www/html/ (React app from dist/public/)
  API Requests: → Node.js (Port 5000) (from dist/index.js)
}
```

## 🗄️ Database Configuration

```env
DB_HOST=localhost
DB_PORT=3306
DB_USER=root
DB_PASSWORD=MyGroup@2025!
DB_NAME=my_group
```

## 🔍 Troubleshooting

### Build Issues
```bash
# Clear cache and rebuild
rm -rf dist/ node_modules/.vite/
npm cache clean --force
export NODE_OPTIONS="--max-old-space-size=2048"
npm run build:production
```

### Application Not Starting
```bash
# Check PM2 logs
pm2 logs mygroup-app --lines 50

# Check if port is in use
sudo netstat -tlnp | grep :5000

# Restart application
pm2 restart mygroup-app
```

### Frontend Not Loading
```bash
# Check Nginx status
sudo systemctl status nginx

# Check frontend files
ls -la /var/www/html/

# Check Nginx logs
sudo tail -f /var/log/nginx/error.log
```

### Database Connection Issues
```bash
# Test MySQL connection
mysql -u root -p my_group

# Check MySQL status
sudo systemctl status mysql

# Restart MySQL
sudo systemctl restart mysql
```

## 📱 Application URLs

After successful deployment:
- **Frontend**: `http://your-ec2-ip/`
- **API**: `http://your-ec2-ip/api/`
- **Admin**: `http://your-ec2-ip/admin/`

## 🎯 Key Files & Locations

| Component | Source | Build Output | Deployment Location |
|-----------|--------|--------------|-------------------|
| React Frontend | `client/` | `dist/public/` | `/var/www/html/` |
| Node.js Backend | `server/` | `dist/index.js` | Runs via PM2 |
| Nginx Config | `deployment/nginx.conf` | - | `/etc/nginx/sites-available/` |
| PM2 Config | `deployment/ecosystem.config.cjs` | - | Used by PM2 |

## ✅ Verification Checklist

- [ ] EC2 instance running
- [ ] Node.js 18, PM2, Nginx, MySQL installed
- [ ] Repository cloned to `/home/ubuntu/MyGroupFullStak`
- [ ] Dependencies installed (`npm install`)
- [ ] Application built (`npm run build:production`)
- [ ] Frontend files in `/var/www/html/`
- [ ] Backend running via PM2 on port 5000
- [ ] Nginx configured and running
- [ ] Database created and accessible
- [ ] Application accessible via browser

## 🚀 Success Indicators

✅ **PM2 Status**: `pm2 status` shows running process  
✅ **Frontend**: Browser loads React app at `http://your-ec2-ip`  
✅ **API**: API endpoints respond at `http://your-ec2-ip/api/`  
✅ **Database**: MySQL connection working  
✅ **Logs**: No errors in `pm2 logs mygroup-app`  

## 📞 Support Commands

```bash
# Full system status
./monitor.sh

# Application logs
pm2 logs mygroup-app --lines 50

# System services
sudo systemctl status nginx mysql

# Disk space
df -h

# Memory usage
free -h
```

---

**Your MyGroup Full Stack application is now live! 🎉**

For detailed instructions, see the complete `AWS_EC2_DEPLOYMENT_GUIDE.md`.
