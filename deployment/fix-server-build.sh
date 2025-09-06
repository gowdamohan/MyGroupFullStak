#!/bin/bash

# Fix Server Build Script - Force rebuild and restart on EC2
# This script fixes the issue where updated code is on server but build isn't reflecting

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_header() {
    echo -e "\n${BLUE}================================${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}================================${NC}\n"
}

# Main execution
main() {
    print_header "Fixing Server Build Issue"
    print_info "This script will force rebuild your application with latest code changes"
    
    # Check if we're in the right directory
    if [ ! -f "package.json" ]; then
        print_error "package.json not found. Please run this script from the project root."
        print_info "Expected location: /home/ubuntu/MyGroupFullStak"
        exit 1
    fi
    
    # Step 1: Stop all running processes
    print_header "Step 1: Stopping Running Processes"
    pm2 delete all || true
    pkill -f "node.*index.js" || true
    pkill -f "npm.*dev" || true
    print_success "All processes stopped"
    
    # Step 2: Clean up old build files
    print_header "Step 2: Cleaning Old Build Files"
    rm -rf dist/
    rm -rf node_modules/.vite/
    rm -rf client/dist/
    rm -rf server/dist/
    print_success "Old build files cleaned"
    
    # Step 3: Clear caches
    print_header "Step 3: Clearing Caches"
    npm cache clean --force
    print_success "NPM cache cleared"
    
    # Step 4: Set environment variables
    print_header "Step 4: Setting Environment Variables"
    export NODE_ENV=production
    export NODE_OPTIONS="--max-old-space-size=2048"
    print_info "NODE_ENV=production"
    print_info "NODE_OPTIONS=--max-old-space-size=2048"
    
    # Step 5: Install dependencies
    print_header "Step 5: Installing Dependencies"
    npm install --no-optional
    print_success "Dependencies installed"
    
    # Step 6: Force rebuild with latest code
    print_header "Step 6: Force Rebuilding Application"
    print_info "Building client (React frontend)..."
    
    # Try different build approaches
    if npm run build:production; then
        print_success "Production build completed successfully"
    elif npm run build; then
        print_success "Standard build completed successfully"
    elif npm run build:client && npm run build:server; then
        print_success "Separate client/server build completed successfully"
    else
        print_error "All build attempts failed. Trying manual build..."
        
        # Manual build as last resort
        print_info "Attempting manual Vite build..."
        npx vite build --mode production
        
        print_info "Attempting manual server build..."
        npx esbuild server/index.ts --platform=node --packages=external --bundle --format=esm --outdir=dist
        
        print_success "Manual build completed"
    fi
    
    # Step 7: Verify build output
    print_header "Step 7: Verifying Build Output"
    
    if [ ! -f "dist/index.js" ]; then
        print_error "Server build file not found: dist/index.js"
        exit 1
    fi
    
    if [ ! -d "dist/public" ]; then
        print_error "Client build directory not found: dist/public"
        exit 1
    fi
    
    if [ ! -f "dist/public/index.html" ]; then
        print_error "Client index.html not found: dist/public/index.html"
        exit 1
    fi
    
    # Show build file sizes
    SERVER_SIZE=$(du -sh dist/index.js | cut -f1)
    CLIENT_SIZE=$(du -sh dist/public | cut -f1)
    
    print_success "Build verification passed:"
    print_info "  Server bundle: $SERVER_SIZE"
    print_info "  Client bundle: $CLIENT_SIZE"
    print_info "  Build timestamp: $(date)"
    
    # Step 8: Start application
    print_header "Step 8: Starting Application"
    
    # Copy production environment if it exists
    if [ -f "deployment/production.env" ]; then
        cp deployment/production.env .env
        print_info "Production environment variables loaded"
    fi
    
    # Start with PM2 using ecosystem config
    if [ -f "deployment/ecosystem.config.cjs" ]; then
        print_info "Starting with PM2 ecosystem config..."
        pm2 start deployment/ecosystem.config.cjs --env production
    elif [ -f "deployment/ecosystem.config.js" ]; then
        print_info "Starting with PM2 ecosystem config (JS)..."
        pm2 start deployment/ecosystem.config.js --env production
    else
        print_info "Starting with direct PM2 command..."
        pm2 start dist/index.js \
          --name "mygroup-app" \
          --env NODE_ENV=production \
          --env PORT=5000
    fi
    
    # Save PM2 configuration
    pm2 save
    
    print_success "Application started successfully"
    
    # Step 9: Verify application is running
    print_header "Step 9: Verifying Application"
    
    sleep 5  # Wait for app to start
    
    # Check PM2 status
    print_info "PM2 Status:"
    pm2 status
    
    # Test local API
    print_info "Testing local API..."
    if curl -f -s http://localhost:5000/api/test > /dev/null; then
        print_success "Local API is responding"
    else
        print_warning "Local API test failed - checking logs..."
        pm2 logs --lines 10
    fi
    
    # Test frontend
    print_info "Testing frontend..."
    if curl -f -s http://localhost:5000/ > /dev/null; then
        print_success "Frontend is responding"
    else
        print_warning "Frontend test failed"
    fi
    
    print_header "Build Fix Complete!"
    print_success "Your application has been rebuilt with the latest code changes"
    print_info "You can now access your updated UI at: http://your-server-ip:5000"
    print_info "To monitor logs: pm2 logs mygroup-app"
    print_info "To restart if needed: pm2 restart mygroup-app"
}

# Run main function
main "$@"
