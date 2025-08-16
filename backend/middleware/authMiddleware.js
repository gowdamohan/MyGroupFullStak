const jwt = require('jsonwebtoken');
const User = require('../models/User');
const Role = require('../models/Role');

class AuthMiddleware {
  // Check if user is authenticated
  static async requireAuth(req, res, next) {
    try {
      // Check session first
      if (req.session && req.session.userId) {
        const user = await User.findById(req.session.userId);
        if (user) {
          req.user = user;
          return next();
        }
      }

      // Check JWT token
      const authHeader = req.headers.authorization;
      if (authHeader && authHeader.startsWith('Bearer ')) {
        const token = authHeader.substring(7);
        
        try {
          const decoded = jwt.verify(token, process.env.JWT_SECRET || 'your-secret-key');
          const user = await User.findById(decoded.userId);
          
          if (user) {
            req.user = user;
            req.session.userId = user.id;
            req.session.userRole = decoded.role;
            return next();
          }
        } catch (jwtError) {
          console.log('JWT verification failed:', jwtError.message);
        }
      }

      return res.status(401).json({ error: 'Authentication required' });
      
    } catch (error) {
      console.error('Auth middleware error:', error);
      return res.status(500).json({ error: 'Internal server error' });
    }
  }

  // Check if user has required role
  static requireRole(allowedRoles) {
    return async (req, res, next) => {
      try {
        if (!req.user) {
          return res.status(401).json({ error: 'Authentication required' });
        }

        const userRole = await Role.findById(req.user.role_id);
        if (!userRole) {
          return res.status(403).json({ error: 'Invalid user role' });
        }

        if (!allowedRoles.includes(userRole.name)) {
          return res.status(403).json({ 
            error: `Access denied. Required role: ${allowedRoles.join(' or ')}` 
          });
        }

        req.userRole = userRole;
        next();
        
      } catch (error) {
        console.error('Role middleware error:', error);
        return res.status(500).json({ error: 'Internal server error' });
      }
    };
  }

  // Check if user has specific permission
  static requirePermission(permission) {
    return async (req, res, next) => {
      try {
        if (!req.user) {
          return res.status(401).json({ error: 'Authentication required' });
        }

        const userRole = await Role.findById(req.user.role_id);
        if (!userRole) {
          return res.status(403).json({ error: 'Invalid user role' });
        }

        const permissions = await Role.getRolePermissions(userRole.id);
        
        if (!permissions.includes('all') && !permissions.includes(permission)) {
          return res.status(403).json({ 
            error: `Access denied. Required permission: ${permission}` 
          });
        }

        req.userRole = userRole;
        next();
        
      } catch (error) {
        console.error('Permission middleware error:', error);
        return res.status(500).json({ error: 'Internal server error' });
      }
    };
  }

  // Optional authentication (sets user if authenticated, but doesn't require it)
  static optionalAuth(req, res, next) {
    AuthMiddleware.requireAuth(req, res, (error) => {
      // Continue regardless of authentication status
      next();
    });
  }

  // Rate limiting for login attempts
  static loginRateLimit(req, res, next) {
    const maxAttempts = 5;
    const windowMs = 15 * 60 * 1000; // 15 minutes
    
    const key = `login_attempts_${req.ip}`;
    const attempts = req.session[key] || { count: 0, resetTime: Date.now() + windowMs };
    
    if (Date.now() > attempts.resetTime) {
      attempts.count = 0;
      attempts.resetTime = Date.now() + windowMs;
    }
    
    if (attempts.count >= maxAttempts) {
      return res.status(429).json({ 
        error: 'Too many login attempts. Please try again later.',
        retryAfter: Math.ceil((attempts.resetTime - Date.now()) / 1000)
      });
    }
    
    req.session[key] = attempts;
    next();
  }
}

module.exports = AuthMiddleware;