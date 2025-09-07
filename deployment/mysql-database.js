require('dotenv').config();
const mysql = require('mysql2/promise');

// MySQL database configuration for production
const dbConfig = {
  host: process.env.DB_HOST || 'localhost',
  port: process.env.DB_PORT || 3306,
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD || 'MyGroup@2025!',
  database: process.env.DB_NAME || 'my_group',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0,
  acquireTimeout: 60000,
  timeout: 60000,
  reconnect: true,
  charset: 'utf8mb4'
};

// Create connection pool
const pool = mysql.createPool(dbConfig);

// Test database connection
async function testConnection() {
  try {
    const connection = await pool.getConnection();
    console.log('✅ MySQL Database connected successfully');
    console.log(`   Host: ${dbConfig.host}:${dbConfig.port}`);
    console.log(`   Database: ${dbConfig.database}`);
    console.log(`   User: ${dbConfig.user}`);
    
    // Test basic query
    const [rows] = await connection.execute('SELECT 1 as test');
    console.log('✅ Test query successful');
    
    connection.release();
    return true;
  } catch (error) {
    console.error('❌ Database connection failed:', error.message);
    return false;
  }
}

// Initialize database with tables
async function initializeDatabase() {
  try {
    console.log('🔄 Initializing MySQL database...');
    
    await testConnection();
    await createTables();
    await seedDefaultData();
    
    console.log('✅ Database initialized successfully');
  } catch (error) {
    console.error('❌ Database initialization failed:', error);
    throw error;
  }
}

// Create database tables
async function createTables() {
  try {
    console.log('🔄 Creating database tables...');
    
    // Create roles table
    await pool.execute(`
      CREATE TABLE IF NOT EXISTS roles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) UNIQUE NOT NULL,
        description TEXT,
        hierarchy_level INT DEFAULT 0,
        permissions JSON,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    `);

    // Create users table
    await pool.execute(`
      CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        first_name VARCHAR(100),
        last_name VARCHAR(100),
        email VARCHAR(255) UNIQUE NOT NULL,
        phone VARCHAR(20),
        role_id INT,
        is_verified BOOLEAN DEFAULT FALSE,
        is_active BOOLEAN DEFAULT TRUE,
        last_login TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL,
        FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL,
        INDEX idx_username (username),
        INDEX idx_email (email),
        INDEX idx_role_id (role_id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    `);

    // Create user_registration_data table
    await pool.execute(`
      CREATE TABLE IF NOT EXISTS user_registration_data (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        registration_ip VARCHAR(45),
        user_agent TEXT,
        referral_source VARCHAR(255),
        utm_source VARCHAR(100),
        utm_medium VARCHAR(100),
        utm_campaign VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_user_id (user_id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    `);

    // Create sessions table for express-session
    await pool.execute(`
      CREATE TABLE IF NOT EXISTS sessions (
        session_id VARCHAR(128) COLLATE utf8mb4_bin NOT NULL,
        expires INT(11) UNSIGNED NOT NULL,
        data MEDIUMTEXT COLLATE utf8mb4_bin,
        PRIMARY KEY (session_id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    `);

    console.log('✅ Database tables created successfully');
    
  } catch (error) {
    console.error('❌ Error creating tables:', error);
    throw error;
  }
}

// Seed default data
async function seedDefaultData() {
  try {
    console.log('🔄 Seeding default data...');
    
    // Check if roles already exist
    const [roleRows] = await pool.execute('SELECT COUNT(*) as count FROM roles');
    
    if (roleRows[0].count === 0) {
      // Insert default roles
      const roles = [
        ['admin', 'System Administrator', 1, JSON.stringify(['all'])],
        ['corporate', 'Corporate Manager', 2, JSON.stringify(['manage_company', 'view_reports', 'manage_users'])],
        ['regional', 'Regional Manager', 3, JSON.stringify(['manage_region', 'view_reports'])],
        ['branch', 'Branch Manager', 4, JSON.stringify(['manage_branch', 'view_local_reports'])],
        ['user', 'Regular User', 5, JSON.stringify(['view_profile', 'update_profile'])]
      ];
      
      for (const role of roles) {
        await pool.execute(
          'INSERT INTO roles (name, description, hierarchy_level, permissions) VALUES (?, ?, ?, ?)',
          role
        );
      }
      
      console.log('✅ Default roles seeded successfully');
    }

    // Check if admin user exists
    const [userRows] = await pool.execute('SELECT COUNT(*) as count FROM users WHERE username = ?', ['admin']);
    
    if (userRows[0].count === 0) {
      const bcrypt = require('bcryptjs');
      const hashedPassword = await bcrypt.hash('password', 12);
      
      // Insert default admin user
      await pool.execute(`
        INSERT INTO users (username, password, first_name, last_name, email, phone, role_id, is_verified, is_active) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
      `, ['admin', hashedPassword, 'System', 'Administrator', 'admin@apphub.com', '1234567890', 1, true, true]);
      
      console.log('✅ Default admin user created successfully');
      console.log('   Username: admin');
      console.log('   Password: password');
    }
    
  } catch (error) {
    console.error('❌ Error seeding default data:', error);
    throw error;
  }
}

module.exports = pool;
module.exports.initializeDatabase = initializeDatabase;
module.exports.testConnection = testConnection;
