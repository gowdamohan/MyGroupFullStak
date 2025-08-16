// PM2 Ecosystem Configuration for Production Deployment

module.exports = {
  apps: [
    {
      name: 'apphub-backend',
      script: './backend/app.js',
      cwd: '/home/ubuntu/MyGroupFullStack',
      instances: 'max', // Use all available CPU cores
      exec_mode: 'cluster',
      env: {
        NODE_ENV: 'development',
        PORT: 3000
      },
      env_production: {
        NODE_ENV: 'production',
        PORT: 3000,
        DB_HOST: 'localhost',
        DB_PORT: 3306,
        DB_USER: 'appuser',
        DB_PASSWORD: 'MyGroup@2025',
        DB_NAME: 'my_group'
      },
      // Logging
      log_file: '/var/log/pm2/apphub-backend.log',
      out_file: '/var/log/pm2/apphub-backend-out.log',
      error_file: '/var/log/pm2/apphub-backend-error.log',
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
  ],

  deploy: {
    production: {
      user: 'ubuntu',
      host: 'your-ec2-public-ip',
      ref: 'origin/main',
      repo: 'https://github.com/your-username/your-repo.git',
      path: '/home/ubuntu/MyGroupFullStack',
      'pre-deploy-local': '',
      'post-deploy': 'npm install && pm2 reload ecosystem.config.js --env production',
      'pre-setup': ''
    }
  }
};
