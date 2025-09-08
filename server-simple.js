// Simple JavaScript server for testing registration
import express from 'express';
import mysql from 'mysql2/promise';
import bcrypt from 'bcryptjs';
import jwt from 'jsonwebtoken';
import cors from 'cors';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();
const PORT = process.env.PORT || 5000;
const JWT_SECRET = process.env.JWT_SECRET || 'your-secret-key-here';

// Middleware
app.use(express.json());
app.use(cors({
  origin: true,
  credentials: true
}));

// Database connection
const dbConfig = {
  host: process.env.DB_HOST || 'localhost',
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD || '',
  database: process.env.DB_NAME || 'apphub_db',
  port: process.env.DB_PORT || 3306
};

let db;

async function initDatabase() {
  try {
    db = await mysql.createConnection(dbConfig);
    console.log('✅ Connected to MySQL database');
  } catch (error) {
    console.error('❌ Database connection failed:', error);
    process.exit(1);
  }
}

// Registration endpoint
app.post('/api/users/register', async (req, res) => {
  try {
    console.log('📝 Registration request received:', req.body);
    
    const { step1, step2 } = req.body;
    
    if (!step1) {
      return res.status(400).json({ error: 'Step 1 data is required' });
    }

    // Validate required fields
    const { username, firstName, lastName, email, phone, password, confirmPassword } = step1;
    
    if (!username || !firstName || !lastName || !email || !phone || !password) {
      return res.status(400).json({ error: 'All required fields must be filled' });
    }

    if (password !== confirmPassword) {
      return res.status(400).json({ error: 'Passwords do not match' });
    }

    // Check if user already exists
    const [existingUsers] = await db.execute(
      'SELECT id FROM users WHERE username = ? OR email = ?',
      [username, email]
    );

    if (existingUsers.length > 0) {
      return res.status(400).json({ error: 'Username or email already exists' });
    }

    // Hash password
    const saltRounds = 12;
    const hashedPassword = await bcrypt.hash(password, saltRounds);

    // Insert new user
    const [result] = await db.execute(
      `INSERT INTO users (
        ip_address, username, password, email, first_name, last_name, 
        phone, company, created_on, active, group_id
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [
        req.ip || '127.0.0.1',
        username,
        hashedPassword,
        email,
        firstName,
        lastName,
        phone,
        step2?.company || null,
        Math.floor(Date.now() / 1000),
        1,
        1
      ]
    );

    const userId = result.insertId;

    // Generate JWT token
    const token = jwt.sign(
      { 
        userId: userId,
        username: username,
        email: email 
      },
      JWT_SECRET,
      { expiresIn: '24h' }
    );

    // Get the created user
    const [users] = await db.execute(
      'SELECT id, username, email, first_name, last_name, phone, company FROM users WHERE id = ?',
      [userId]
    );

    const user = users[0];

    console.log('✅ User created successfully:', { userId, username, email });

    res.json({
      message: 'User registered successfully',
      user: {
        id: user.id,
        username: user.username,
        email: user.email,
        firstName: user.first_name,
        lastName: user.last_name,
        phone: user.phone,
        company: user.company
      },
      token
    });

  } catch (error) {
    console.error('❌ Registration error:', error);
    res.status(500).json({ 
      error: 'Internal server error',
      details: error.message 
    });
  }
});

// Login endpoint
app.post('/api/users/login', async (req, res) => {
  try {
    const { username, password } = req.body;

    if (!username || !password) {
      return res.status(400).json({ error: 'Username and password are required' });
    }

    // Get user from database
    const [users] = await db.execute(
      'SELECT * FROM users WHERE username = ? OR email = ?',
      [username, username]
    );

    if (users.length === 0) {
      return res.status(401).json({ error: 'Invalid credentials' });
    }

    const user = users[0];

    // Verify password
    const isValidPassword = await bcrypt.compare(password, user.password);
    if (!isValidPassword) {
      return res.status(401).json({ error: 'Invalid credentials' });
    }

    // Generate JWT token
    const token = jwt.sign(
      { 
        userId: user.id,
        username: user.username,
        email: user.email 
      },
      JWT_SECRET,
      { expiresIn: '24h' }
    );

    res.json({
      message: 'Login successful',
      user: {
        id: user.id,
        username: user.username,
        email: user.email,
        firstName: user.first_name,
        lastName: user.last_name,
        phone: user.phone,
        company: user.company
      },
      token
    });

  } catch (error) {
    console.error('❌ Login error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
});

// Test endpoint
app.get('/api/test', (req, res) => {
  res.json({ message: 'API is working!', timestamp: new Date().toISOString() });
});

// Add database columns endpoint
app.get('/api/add-registration-columns', async (req, res) => {
  try {
    const alterQueries = [
      "ALTER TABLE users ADD COLUMN IF NOT EXISTS role varchar(20) DEFAULT 'user'",
      "ALTER TABLE users ADD COLUMN IF NOT EXISTS gender varchar(10) DEFAULT NULL",
      "ALTER TABLE users ADD COLUMN IF NOT EXISTS date_of_birth varchar(10) DEFAULT NULL",
      "ALTER TABLE users ADD COLUMN IF NOT EXISTS country varchar(50) DEFAULT NULL",
      "ALTER TABLE users ADD COLUMN IF NOT EXISTS state varchar(50) DEFAULT NULL",
      "ALTER TABLE users ADD COLUMN IF NOT EXISTS district varchar(50) DEFAULT NULL",
      "ALTER TABLE users ADD COLUMN IF NOT EXISTS education varchar(100) DEFAULT NULL",
      "ALTER TABLE users ADD COLUMN IF NOT EXISTS profession varchar(100) DEFAULT NULL"
    ];

    for (const query of alterQueries) {
      try {
        await db.execute(query);
        console.log(`✅ Executed: ${query}`);
      } catch (error) {
        console.log(`⚠️ Column might already exist: ${query}`);
      }
    }

    res.json({ message: "Registration columns added successfully" });
  } catch (error) {
    console.error("Error adding registration columns:", error);
    res.status(500).json({ error: "Failed to add registration columns" });
  }
});

// Serve static files in production
if (process.env.NODE_ENV === 'production') {
  app.use(express.static(path.join(__dirname, '../client/dist')));
  
  app.get('*', (req, res) => {
    res.sendFile(path.join(__dirname, '../client/dist/index.html'));
  });
}

// Start server
async function startServer() {
  await initDatabase();
  
  app.listen(PORT, () => {
    console.log(`🚀 Server running on port ${PORT}`);
    console.log(`📍 API available at http://localhost:${PORT}/api`);
    console.log(`🔧 Test endpoint: http://localhost:${PORT}/api/test`);
    console.log(`🗄️ Add columns: http://localhost:${PORT}/api/add-registration-columns`);
  });
}

startServer().catch(console.error);
