const express = require('express');
const AuthController = require('../controllers/AuthController');
const authMiddleware = require('../middleware/authMiddleware');
const validationMiddleware = require('../middleware/validationMiddleware');

const router = express.Router();

// Login validation rules
const loginValidation = [
  validationMiddleware.body('username').notEmpty().withMessage('Username is required'),
  validationMiddleware.body('password').notEmpty().withMessage('Password is required')
];

// Registration validation rules
const registrationValidation = [
  validationMiddleware.body('username')
    .isLength({ min: 3, max: 50 })
    .withMessage('Username must be between 3 and 50 characters')
    .matches(/^[a-zA-Z0-9_]+$/)
    .withMessage('Username can only contain letters, numbers, and underscores'),
  
  validationMiddleware.body('email')
    .isEmail()
    .withMessage('Please provide a valid email address')
    .normalizeEmail(),
  
  validationMiddleware.body('password')
    .isLength({ min: 8 })
    .withMessage('Password must be at least 8 characters long')
    .matches(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/)
    .withMessage('Password must contain at least one uppercase letter, one lowercase letter, and one number'),
  
  validationMiddleware.body('firstName')
    .optional()
    .isLength({ max: 100 })
    .withMessage('First name cannot exceed 100 characters'),
  
  validationMiddleware.body('lastName')
    .optional()
    .isLength({ max: 100 })
    .withMessage('Last name cannot exceed 100 characters'),
  
  validationMiddleware.body('phone')
    .optional()
    .isMobilePhone()
    .withMessage('Please provide a valid phone number')
];

// Public routes
router.post('/login', loginValidation, validationMiddleware.handleValidationErrors, AuthController.adminLogin);
router.post('/register', registrationValidation, validationMiddleware.handleValidationErrors, AuthController.register);

// Protected routes
router.get('/me', authMiddleware.requireAuth, AuthController.getCurrentUser);
router.post('/logout', authMiddleware.requireAuth, AuthController.logout);

// Admin only routes
router.get('/users', authMiddleware.requireAuth, authMiddleware.requireRole(['admin']), async (req, res) => {
  try {
    const User = require('../models/User');
    const users = await User.getAllUsers();
    res.json({ users });
  } catch (error) {
    console.error('Get users error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
});

// Test route
router.get('/test', (req, res) => {
  res.json({ 
    message: 'Auth API is working!',
    timestamp: new Date().toISOString()
  });
});

module.exports = router;