const { body, validationResult } = require('express-validator');

class ValidationMiddleware {
  // Export express-validator functions
  static body = body;

  // Handle validation errors
  static handleValidationErrors(req, res, next) {
    const errors = validationResult(req);
    
    if (!errors.isEmpty()) {
      const errorMessages = errors.array().map(error => ({
        field: error.path || error.param,
        message: error.msg,
        value: error.value
      }));
      
      return res.status(400).json({
        error: 'Validation failed',
        details: errorMessages
      });
    }
    
    next();
  }

  // Sanitize input data
  static sanitizeInput(req, res, next) {
    // Remove any potential XSS attempts
    const sanitizeString = (str) => {
      if (typeof str !== 'string') return str;
      return str
        .replace(/[<>]/g, '') // Remove < and > characters
        .trim(); // Remove leading/trailing whitespace
    };

    const sanitizeObject = (obj) => {
      for (const key in obj) {
        if (typeof obj[key] === 'string') {
          obj[key] = sanitizeString(obj[key]);
        } else if (typeof obj[key] === 'object' && obj[key] !== null) {
          sanitizeObject(obj[key]);
        }
      }
    };

    if (req.body) {
      sanitizeObject(req.body);
    }
    
    if (req.query) {
      sanitizeObject(req.query);
    }

    next();
  }

  // Validate file uploads
  static validateFileUpload(allowedTypes = [], maxSize = 5 * 1024 * 1024) {
    return (req, res, next) => {
      if (!req.files || Object.keys(req.files).length === 0) {
        return next();
      }

      const file = req.files.file || req.files[Object.keys(req.files)[0]];
      
      // Check file size
      if (file.size > maxSize) {
        return res.status(400).json({
          error: 'File too large',
          maxSize: `${maxSize / (1024 * 1024)}MB`
        });
      }

      // Check file type
      if (allowedTypes.length > 0 && !allowedTypes.includes(file.mimetype)) {
        return res.status(400).json({
          error: 'Invalid file type',
          allowedTypes
        });
      }

      next();
    };
  }

  // Validate pagination parameters
  static validatePagination(req, res, next) {
    const page = parseInt(req.query.page) || 1;
    const limit = parseInt(req.query.limit) || 10;
    
    // Ensure reasonable limits
    req.pagination = {
      page: Math.max(1, page),
      limit: Math.min(Math.max(1, limit), 100), // Max 100 items per page
      offset: (Math.max(1, page) - 1) * Math.min(Math.max(1, limit), 100)
    };
    
    next();
  }

  // Validate ID parameters
  static validateId(paramName = 'id') {
    return (req, res, next) => {
      const id = parseInt(req.params[paramName]);
      
      if (isNaN(id) || id <= 0) {
        return res.status(400).json({
          error: `Invalid ${paramName}`,
          message: `${paramName} must be a positive integer`
        });
      }
      
      req.params[paramName] = id;
      next();
    };
  }

  // Custom validation for specific business rules
  static validateBusinessRules = {
    // Username validation
    username: body('username')
      .isLength({ min: 3, max: 50 })
      .withMessage('Username must be between 3 and 50 characters')
      .matches(/^[a-zA-Z0-9_]+$/)
      .withMessage('Username can only contain letters, numbers, and underscores'),

    // Email validation
    email: body('email')
      .isEmail()
      .withMessage('Please provide a valid email address')
      .normalizeEmail(),

    // Strong password validation
    strongPassword: body('password')
      .isLength({ min: 8 })
      .withMessage('Password must be at least 8 characters long')
      .matches(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/)
      .withMessage('Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character'),

    // Phone number validation
    phone: body('phone')
      .optional()
      .isMobilePhone()
      .withMessage('Please provide a valid phone number'),

    // Role validation
    role: body('roleId')
      .isInt({ min: 1 })
      .withMessage('Role ID must be a positive integer')
  };
}

module.exports = ValidationMiddleware;