const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const User = require('../models/User');
const Role = require('../models/Role');
const UserRegistrationData = require('../models/UserRegistrationData');

class AuthController {
  // Admin Login
  static async adminLogin(req, res) {
    try {
      const { username, password } = req.body;

      // Validate input
      if (!username || !password) {
        return res.status(400).json({
          error: 'Username and password are required'
        });
      }

      // Find user with role information
      const user = await User.findByUsername(username);
      if (!user) {
        return res.status(401).json({
          error: 'Invalid credentials'
        });
      }

      // Check if user has admin role
      const userRole = await Role.findById(user.role_id);
      if (!userRole || userRole.name !== 'admin') {
        return res.status(403).json({
          error: 'Access denied. Admin privileges required.'
        });
      }

      // Verify password
      const isValidPassword = await bcrypt.compare(password, user.password);
      if (!isValidPassword) {
        return res.status(401).json({
          error: 'Invalid credentials'
        });
      }

      // Generate JWT token
      const token = jwt.sign(
        { 
          userId: user.id, 
          username: user.username, 
          role: userRole.name 
        },
        process.env.JWT_SECRET || 'your-secret-key',
        { expiresIn: '24h' }
      );

      // Update last login
      await User.updateLastLogin(user.id);

      // Set session
      req.session.userId = user.id;
      req.session.userRole = userRole.name;

      res.json({
        message: 'Login successful',
        user: {
          id: user.id,
          username: user.username,
          firstName: user.first_name,
          lastName: user.last_name,
          email: user.email,
          phone: user.phone,
          role: userRole.name,
          isVerified: user.is_verified,
          createdAt: user.created_at
        },
        token
      });

    } catch (error) {
      console.error('Login error:', error);
      res.status(500).json({ error: 'Internal server error' });
    }
  }

  // User Registration
  static async register(req, res) {
    try {
      const {
        username,
        password,
        firstName,
        lastName,
        email,
        phone,
        roleId = 4 // Default to 'user' role
      } = req.body;

      // Validate required fields
      if (!username || !password || !email) {
        return res.status(400).json({
          error: 'Username, password, and email are required'
        });
      }

      // Check if user already exists
      const existingUser = await User.findByUsername(username);
      if (existingUser) {
        return res.status(409).json({
          error: 'Username already exists'
        });
      }

      const existingEmail = await User.findByEmail(email);
      if (existingEmail) {
        return res.status(409).json({
          error: 'Email already exists'
        });
      }

      // Hash password
      const saltRounds = 12;
      const hashedPassword = await bcrypt.hash(password, saltRounds);

      // Create user
      const newUser = await User.create({
        username,
        password: hashedPassword,
        first_name: firstName,
        last_name: lastName,
        email,
        phone,
        role_id: roleId
      });

      // Create registration data entry
      await UserRegistrationData.create({
        user_id: newUser.id,
        registration_ip: req.ip,
        user_agent: req.get('User-Agent')
      });

      res.status(201).json({
        message: 'User registered successfully',
        user: {
          id: newUser.id,
          username: newUser.username,
          email: newUser.email
        }
      });

    } catch (error) {
      console.error('Registration error:', error);
      res.status(500).json({ error: 'Internal server error' });
    }
  }

  // Get current user info
  static async getCurrentUser(req, res) {
    try {
      if (!req.session.userId) {
        return res.status(401).json({ error: 'Not authenticated' });
      }

      res.json({
        userId: req.session.userId,
        userRole: req.session.userRole
      });

    } catch (error) {
      console.error('Get current user error:', error);
      res.status(500).json({ error: 'Internal server error' });
    }
  }

  // Logout
  static async logout(req, res) {
    try {
      req.session.destroy((err) => {
        if (err) {
          return res.status(500).json({ error: 'Could not log out' });
        }
        res.json({ message: 'Logged out successfully' });
      });
    } catch (error) {
      console.error('Logout error:', error);
      res.status(500).json({ error: 'Internal server error' });
    }
  }
}

module.exports = AuthController;