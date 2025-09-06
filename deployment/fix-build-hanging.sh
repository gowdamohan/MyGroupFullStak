#!/bin/bash

# EC2 Build Hanging Fix Script
# This script addresses npm build hanging issues on EC2 instances

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

# Function to check system resources
check_system_resources() {
    print_header "Checking System Resources"
    
    # Check memory
    TOTAL_MEM=$(free -m | awk 'NR==2{printf "%.0f", $2}')
    AVAILABLE_MEM=$(free -m | awk 'NR==2{printf "%.0f", $7}')
    
    print_info "Total Memory: ${TOTAL_MEM}MB"
    print_info "Available Memory: ${AVAILABLE_MEM}MB"
    
    if [ "$AVAILABLE_MEM" -lt 1024 ]; then
        print_warning "Low memory detected (${AVAILABLE_MEM}MB). Build may hang."
        print_info "Consider upgrading EC2 instance or enabling swap."
        return 1
    fi
    
    # Check disk space
    DISK_USAGE=$(df -h . | awk 'NR==2 {print $5}' | sed 's/%//')
    print_info "Disk Usage: ${DISK_USAGE}%"
    
    if [ "$DISK_USAGE" -gt 85 ]; then
        print_warning "High disk usage (${DISK_USAGE}%). This may cause build issues."
        return 1
    fi
    
    print_success "System resources check passed"
    return 0
}

# Function to create swap file if needed
create_swap_if_needed() {
    print_header "Checking Swap Configuration"
    
    SWAP_SIZE=$(free -m | awk 'NR==3{printf "%.0f", $2}')
    
    if [ "$SWAP_SIZE" -eq 0 ]; then
        print_warning "No swap detected. Creating 2GB swap file..."
        
        # Create swap file
        sudo fallocate -l 2G /swapfile || sudo dd if=/dev/zero of=/swapfile bs=1024 count=2097152
        sudo chmod 600 /swapfile
        sudo mkswap /swapfile
        sudo swapon /swapfile
        
        # Make swap permanent
        echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
        
        print_success "Swap file created and activated"
    else
        print_success "Swap already configured (${SWAP_SIZE}MB)"
    fi
}

# Function to clean up before build
cleanup_before_build() {
    print_header "Cleaning Up Before Build"
    
    # Clear npm cache
    print_info "Clearing npm cache..."
    npm cache clean --force
    
    # Remove existing dist directory
    print_info "Removing existing build files..."
    rm -rf dist/
    rm -rf node_modules/.vite/
    rm -rf client/dist/
    
    # Clear PM2 logs to free space
    print_info "Clearing PM2 logs..."
    pm2 flush || true
    
    print_success "Cleanup completed"
}

# Function to set Node.js memory limits
set_node_memory_limits() {
    print_header "Setting Node.js Memory Limits"
    
    # Set memory limits based on available memory
    TOTAL_MEM=$(free -m | awk 'NR==2{printf "%.0f", $2}')
    
    if [ "$TOTAL_MEM" -lt 2048 ]; then
        export NODE_OPTIONS="--max-old-space-size=1024"
        print_info "Set Node.js memory limit to 1024MB (low memory system)"
    else
        export NODE_OPTIONS="--max-old-space-size=2048"
        print_info "Set Node.js memory limit to 2048MB"
    fi
    
    print_success "Memory limits configured"
}

# Function to build with retry mechanism
build_with_retry() {
    print_header "Building Application with Retry Mechanism"
    
    local max_attempts=3
    local attempt=1
    
    while [ $attempt -le $max_attempts ]; do
        print_info "Build attempt $attempt of $max_attempts"
        
        if npm run build:production; then
            print_success "Build completed successfully on attempt $attempt"
            return 0
        else
            print_warning "Build failed on attempt $attempt"
            
            if [ $attempt -lt $max_attempts ]; then
                print_info "Cleaning up and retrying in 10 seconds..."
                sleep 10
                
                # Clean up and try again
                rm -rf dist/ node_modules/.vite/ client/dist/
                npm cache clean --force
            fi
        fi
        
        ((attempt++))
    done
    
    print_error "Build failed after $max_attempts attempts"
    return 1
}

# Function to build in parts if full build fails
build_in_parts() {
    print_header "Attempting Partial Build Strategy"
    
    print_info "Building client only..."
    if npm run build:client; then
        print_success "Client build completed"
    else
        print_error "Client build failed"
        return 1
    fi
    
    print_info "Waiting 30 seconds before server build..."
    sleep 30
    
    print_info "Building server only..."
    if npm run build:server; then
        print_success "Server build completed"
    else
        print_error "Server build failed"
        return 1
    fi
    
    print_success "Partial build strategy completed successfully"
}

# Function to verify build output
verify_build() {
    print_header "Verifying Build Output"
    
    if [ ! -f "dist/index.js" ]; then
        print_error "Server build file not found: dist/index.js"
        return 1
    fi
    
    if [ ! -d "dist/public" ]; then
        print_error "Client build directory not found: dist/public"
        return 1
    fi
    
    if [ ! -f "dist/public/index.html" ]; then
        print_error "Client index.html not found: dist/public/index.html"
        return 1
    fi
    
    # Check file sizes
    SERVER_SIZE=$(du -sh dist/index.js | cut -f1)
    CLIENT_SIZE=$(du -sh dist/public | cut -f1)
    
    print_success "Build verification passed:"
    print_info "  Server bundle: $SERVER_SIZE"
    print_info "  Client bundle: $CLIENT_SIZE"
    
    return 0
}

# Main execution
main() {
    print_header "EC2 Build Hanging Fix Script"
    print_info "This script will attempt to fix npm build hanging issues on EC2"
    
    # Check if we're in the right directory
    if [ ! -f "package.json" ]; then
        print_error "package.json not found. Please run this script from the project root."
        exit 1
    fi
    
    # Step 1: Check system resources
    if ! check_system_resources; then
        print_warning "System resource issues detected. Proceeding with caution..."
    fi
    
    # Step 2: Create swap if needed (requires sudo)
    if command -v sudo >/dev/null 2>&1; then
        create_swap_if_needed
    else
        print_warning "sudo not available. Skipping swap creation."
    fi
    
    # Step 3: Set memory limits
    set_node_memory_limits
    
    # Step 4: Clean up
    cleanup_before_build
    
    # Step 5: Install dependencies
    print_header "Installing Dependencies"
    npm install --no-optional --production=false
    
    # Step 6: Try normal build first
    if build_with_retry; then
        print_success "Normal build strategy succeeded"
    else
        print_warning "Normal build failed. Trying partial build strategy..."
        if build_in_parts; then
            print_success "Partial build strategy succeeded"
        else
            print_error "All build strategies failed"
            exit 1
        fi
    fi
    
    # Step 7: Verify build
    if verify_build; then
        print_success "Build completed and verified successfully!"
        print_info "You can now start your application with: pm2 start deployment/ecosystem.config.cjs --env production"
    else
        print_error "Build verification failed"
        exit 1
    fi
}

# Run main function
main "$@"
