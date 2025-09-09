// PM2 Ecosystem Configuration for Production Deployment (CommonJS format)

module.exports = {
  apps: [
    {
      name: 'mygroup-app',
      script: './dist/index.js',
      cwd: '/home/ubuntu/MyGroupFullStak',
      instances: 1,
      exec_mode: 'fork',
      env: {
        NODE_ENV: 'development',
        PORT: 5000
      },
      env_production: {
        NODE_ENV: 'production',
        PORT: 5000,
        DB_HOST: 'localhost',
        DB_PORT: 3306,
        DB_USER: 'mygroup',
        DB_PASSWORD: 'MyGroup@2025!',
        DB_NAME: 'my_group'
      },
      // Logging - use home directory to avoid permission issues
      log_file: '/home/ubuntu/MyGroupFullStak/logs/apphub-backend.log',
      out_file: '/home/ubuntu/MyGroupFullStak/logs/apphub-backend-out.log',
      error_file: '/home/ubuntu/MyGroupFullStak/logs/apphub-backend-error.log',
      log_date_format: 'YYYY-MM-DD HH:mm:ss Z',
      
      // Auto-restart configuration
      watch: false,
      max_memory_restart: '1G',
      restart_delay: 4000,
      max_restarts: 10,
      min_uptime: '10s',
      
      // Health monitoring
      health_check_grace_period: 3000,
      health_check_fatal_exceptions: true
    }
  ]
};