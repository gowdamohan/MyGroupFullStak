# GitHub CI/CD Setup Guide

This guide walks you through setting up automated deployment from GitHub to your EC2 server.

## ✅ Step 1: Workflow File (COMPLETED)

The workflow file `.github/workflows/build-frontend.yml` has been created and configured to:
- Build React frontend with `npm run build:web`
- Move dist into server directory
- Deploy to EC2 via SSH
- Restart PM2 processes

## 🔑 Step 2: Configure GitHub Secrets

### Required Secrets

Go to **GitHub → MyGroupFullStak → Settings → Secrets and variables → Actions → New repository secret**

Add these two secrets:

#### 1. EC2_HOST
- **Name:** `EC2_HOST`
- **Value:** Your EC2 public IP address or domain
- **Example:** `13.203.76.188` or `your-domain.com`

#### 2. EC2_SSH_KEY
- **Name:** `EC2_SSH_KEY`
- **Value:** Contents of your private key (.pem file)

**To get your private key contents:**
```bash
# On your local machine, display the key contents
cat path/to/your-key.pem

# Copy the ENTIRE output including:
# -----BEGIN RSA PRIVATE KEY-----
# ... key content ...
# -----END RSA PRIVATE KEY-----
```

⚠️ **Security Note:** Keep your private key safe and never share it publicly.

## 📁 Step 3: Server Structure After Deployment

After successful deployment, your EC2 will have this structure:

```
/home/ubuntu/MyGroupFullStak/server/
├── dist/                    # React build (from CI/CD)
│   ├── index.html
│   ├── assets/
│   │   ├── index-[hash].js
│   │   └── index-[hash].css
│   └── ...
├── index.ts                 # Express backend source
├── index.js                 # Compiled Express backend
├── routes.ts
├── vite.ts
├── mysql-connection.ts
├── mysql-storage.ts
├── storage.ts
├── package.json             # Server dependencies
├── node_modules/            # Server dependencies
└── ...
```

## 🔧 Step 4: Express Static File Serving (COMPLETED)

The `server/vite.ts` file has been updated to serve static files from the correct location:

```typescript
export function serveStatic(app: Express) {
  // Check for CI/CD deployment structure first (server/dist)
  let distPath = path.resolve(__dirname, "dist");
  
  // Fallback to local development structure (dist/public)
  if (!fs.existsSync(distPath)) {
    distPath = path.resolve(process.cwd(), "dist", "public");
  }
  
  // Serve static files and handle SPA routing
  app.use(express.static(distPath));
  app.use("*", (_req, res) => {
    res.sendFile(path.resolve(distPath, "index.html"));
  });
}
```

## 🚀 Step 5: Testing the Deployment

### 1. Commit and Push Changes
```bash
git add .
git commit -m "Add CI/CD workflow for automated deployment"
git push origin main
```

### 2. Monitor GitHub Actions
- Go to **GitHub → MyGroupFullStak → Actions**
- Watch the "CI - Build & Deploy" workflow run
- Check for any errors in the build or deploy steps

### 3. Verify Deployment on EC2
```bash
# SSH to your EC2 instance
ssh -i your-key.pem ubuntu@your-ec2-ip

# Check the deployed files
ls -la /home/ubuntu/MyGroupFullStak/server/
ls -la /home/ubuntu/MyGroupFullStak/server/dist/

# Check PM2 status
pm2 status
pm2 logs --lines 20
```

### 4. Test the Application
```bash
# Test API endpoint
curl -I http://your-ec2-ip:5000/api/test

# Test frontend (should serve React app)
curl -I http://your-ec2-ip:5000/
```

## 🔄 Step 6: Workflow Process

Here's what happens when you push code:

1. **Build Job:**
   - Checkout code from GitHub
   - Install dependencies
   - Build React frontend (`npm run build:web`)
   - Move `dist/` into `server/` directory
   - Upload `server/` as artifact

2. **Deploy Job:**
   - Download the server artifact
   - Copy entire `server/` directory to EC2
   - Install production dependencies on EC2
   - Restart PM2 processes

## 🛠️ Troubleshooting

### Common Issues

**1. SSH Connection Failed**
- Verify `EC2_HOST` secret is correct
- Ensure `EC2_SSH_KEY` contains the complete private key
- Check EC2 security group allows SSH (port 22)

**2. Permission Denied**
- Ensure the SSH key has correct permissions on EC2
- Verify the ubuntu user has access to the target directory

**3. PM2 Restart Failed**
- Check if PM2 is installed globally on EC2: `npm install -g pm2`
- Verify PM2 processes exist: `pm2 status`

**4. Build Artifacts Not Found**
- Check if the build step completed successfully
- Verify `dist/` directory was created in the build job

### Debug Commands

```bash
# On EC2, check deployment
ls -la /home/ubuntu/MyGroupFullStak/server/
cat /home/ubuntu/MyGroupFullStak/server/package.json

# Check PM2 status
pm2 status
pm2 logs --lines 50

# Test server manually
cd /home/ubuntu/MyGroupFullStak/server
npm install --production
node index.js
```

## ✅ Success Indicators

After successful deployment:
- ✅ GitHub Actions workflow completes without errors
- ✅ Files are deployed to `/home/ubuntu/MyGroupFullStak/server/`
- ✅ `server/dist/` contains React build files
- ✅ PM2 shows running processes
- ✅ API endpoints respond correctly
- ✅ Frontend loads at `http://your-ec2-ip:5000`

## 🎯 Next Steps

Once this is working:
1. **Test the full workflow** by making a small change and pushing
2. **Monitor logs** to ensure everything deploys correctly
3. **Set up domain/SSL** if needed
4. **Configure environment variables** for production

Your CI/CD pipeline is now ready for automated deployments! 🚀
