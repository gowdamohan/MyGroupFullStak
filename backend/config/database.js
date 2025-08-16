require('dotenv').config();
const sqlite3 = require('sqlite3').verbose();
const path = require('path');

// SQLite database for development since MySQL is not available
const dbPath = process.env.NODE_ENV === 'production' 
  ? path.join(__dirname, '../data/apphub.db')
  : ':memory:'; // Use in-memory database for development

let db = null;

// SQLite connection wrapper to mimic MySQL pool interface
const pool = {
  async execute(query, params = []) {
    return new Promise((resolve, reject) => {
      if (!db) {
        reject(new Error('Database not initialized'));
        return;
      }

      // Convert MySQL-style placeholders (?) to SQLite
      const sqliteQuery = query.replace(/\?/g, () => '?');
      
      if (query.trim().toUpperCase().startsWith('SELECT')) {
        db.all(sqliteQuery, params, (err, rows) => {
          if (err) {
            reject(err);
          } else {
            resolve([rows]); // Return in MySQL format [rows, fields]
          }
        });
      } else {
        db.run(sqliteQuery, params, function(err) {
          if (err) {
            reject(err);
          } else {
            resolve([{ insertId: this.lastID, affectedRows: this.changes }]);
          }
        });
      }
    });
  },

  async getConnection() {
    return {
      execute: pool.execute,
      release: () => {} // No-op for SQLite
    };
  }
};

// Test database connection
async function testConnection() {
  try {
    if (!db) {
      throw new Error('Database not initialized');
    }
    console.log('✅ SQLite Database connected successfully');
    return true;
  } catch (error) {
    console.error('❌ Database connection failed:', error.message);
    return false;
  }
}

// Initialize database with tables
async function initializeDatabase() {
  try {
    // Initialize SQLite database
    db = new sqlite3.Database(dbPath, (err) => {
      if (err) {
        console.error('❌ SQLite connection failed:', err.message);
        throw err;
      }
    });

    await testConnection();
    
    // Create tables
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
    // Create roles table
    await pool.execute(`
      CREATE TABLE IF NOT EXISTS roles (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT UNIQUE NOT NULL,
        description TEXT,
        hierarchy_level INTEGER DEFAULT 0,
        permissions TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
      )
    `);

    // Create users table
    await pool.execute(`
      CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        first_name TEXT,
        last_name TEXT,
        email TEXT UNIQUE NOT NULL,
        phone TEXT,
        role_id INTEGER,
        is_verified INTEGER DEFAULT 0,
        is_active INTEGER DEFAULT 1,
        last_login DATETIME NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        deleted_at DATETIME NULL,
        FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL
      )
    `);

    // Create user_registration_data table
    await pool.execute(`
      CREATE TABLE IF NOT EXISTS user_registration_data (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        registration_ip TEXT,
        user_agent TEXT,
        referral_source TEXT,
        utm_source TEXT,
        utm_medium TEXT,
        utm_campaign TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
      )
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
    // Check if roles already exist
    const [roleRows] = await pool.execute('SELECT COUNT(*) as count FROM roles');
    
    if (!roleRows || roleRows.length === 0 || roleRows[0].count === 0) {
      // Insert default roles
      await pool.execute(`
        INSERT INTO roles (name, description, hierarchy_level, permissions) VALUES
        ('admin', 'System Administrator', 1, '["all"]')
      `);
      await pool.execute(`
        INSERT INTO roles (name, description, hierarchy_level, permissions) VALUES
        ('corporate', 'Corporate Manager', 2, '["manage_company", "view_reports", "manage_users"]')
      `);
      await pool.execute(`
        INSERT INTO roles (name, description, hierarchy_level, permissions) VALUES
        ('regional', 'Regional Manager', 3, '["manage_region", "view_reports"]')
      `);
      await pool.execute(`
        INSERT INTO roles (name, description, hierarchy_level, permissions) VALUES
        ('branch', 'Branch Manager', 4, '["manage_branch", "view_local_reports"]')
      `);
      await pool.execute(`
        INSERT INTO roles (name, description, hierarchy_level, permissions) VALUES
        ('user', 'Regular User', 5, '["view_profile", "update_profile"]')
      `);
      
      console.log('✅ Default roles seeded successfully');
    }

    // Check if admin user exists
    const [userRows] = await pool.execute('SELECT COUNT(*) as count FROM users WHERE username = ?', ['admin']);
    
    if (!userRows || userRows.length === 0 || userRows[0].count === 0) {
      const bcrypt = require('bcryptjs');
      const hashedPassword = await bcrypt.hash('password', 12);
      
      // Insert default admin user
      await pool.execute(`
        INSERT INTO users (username, password, first_name, last_name, email, phone, role_id, is_verified, is_active) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
      `, ['admin', hashedPassword, 'System', 'Administrator', 'admin@apphub.com', '1234567890', 1, 1, 1]);
      
      console.log('✅ Default admin user created successfully');
    }
    
  } catch (error) {
    console.error('❌ Error seeding default data:', error);
    throw error;
  }
}

module.exports = pool;
module.exports.initializeDatabase = initializeDatabase;
module.exports.testConnection = testConnection;