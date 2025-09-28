import type { Express, Request } from "express";
import { createServer, type Server } from "http";
import { z } from "zod";
import jwt from "jsonwebtoken";
import bcrypt from "bcrypt";
import path from "path";
import fs from "fs";
import multer from "multer";
import { storage } from "./storage";
import { mysqlStorage } from "./mysql-storage";
import {
  loginSchema,
  adminLoginSchema,
  registrationSchema,
  registrationStep1Schema,
  registrationStep2Schema,
  groupCreateSchema,
  createDetailsSchema,
  changePasswordSchema,
  continentSchema,
  countrySchema,
  stateSchema,
  districtSchema
} from "@shared/schema";

// JWT Secret - In production, this should be in environment variables
const JWT_SECRET = process.env.JWT_SECRET || 'your-super-secret-jwt-key-change-in-production';

// Uploads directory setup
const uploadsDir = path.join(process.cwd(), 'uploads');
if (!fs.existsSync(uploadsDir)) {
  fs.mkdirSync(uploadsDir, { recursive: true });
}

// Multer configuration for file uploads
const upload = multer({
  dest: uploadsDir,
  limits: {
    fileSize: 10 * 1024 * 1024, // 10MB limit
  },
  fileFilter: (req, file, cb) => {
    // Allow only image files
    if (file.mimetype.startsWith('image/')) {
      cb(null, true);
    } else {
      cb(new Error('Only image files are allowed'));
    }
  },
});

// Extend Express Request type to include session and user
declare module 'express-session' {
  interface SessionData {
    userId?: string;
    userRole?: string;
  }
}

// Extend Express Request type to include files from express-fileupload and user
declare module 'express' {
  interface Request {
    files?: any;
    user?: any;
    userRole?: string;
  }
}

// JWT Authentication Middleware
const authenticateJWT = async (req: any, res: any, next: any) => {
  try {
    // Check for JWT token in Authorization header
    const authHeader = req.headers.authorization;
    if (!authHeader || !authHeader.startsWith('Bearer ')) {
      return res.status(401).json({ error: "Access token required" });
    }

    const token = authHeader.substring(7);

    try {
      const decoded = jwt.verify(token, JWT_SECRET) as any;

      // Get user from database to ensure they still exist and are active
      let user;

      // Check if userId is a number (MySQL) or string UUID (in-memory)
      const isNumericId = !isNaN(Number(decoded.userId)) && Number.isInteger(Number(decoded.userId));

      if (isNumericId) {
        // Try MySQL storage first for numeric IDs
        try {
          user = await mysqlStorage.getUser(Number(decoded.userId));
        } catch (error) {
          console.log("❌ MySQL getUser failed for numeric ID:", error instanceof Error ? error.message : String(error));
        }
      }

      // If MySQL failed or if it's a UUID, try in-memory storage
      if (!user) {
        try {
          user = await storage.getUser(String(decoded.userId));
          console.log("✅ Found user in in-memory storage for JWT validation");
        } catch (error) {
          console.log("❌ In-memory getUser failed:", error instanceof Error ? error.message : String(error));
        }
      }

      if (!user) {
        return res.status(401).json({ error: "Invalid token - user not found" });
      }

      // Attach user info to request
      req.user = user;
      req.userId = decoded.userId;
      req.userRole = decoded.role;

      // Also set session for backward compatibility
      req.session.userId = decoded.userId.toString();
      req.session.userRole = decoded.role;

      next();
    } catch (jwtError) {
      console.log('JWT verification failed:', jwtError);
      return res.status(401).json({ error: "Invalid or expired token" });
    }
  } catch (error) {
    console.error('Authentication middleware error:', error);
    return res.status(500).json({ error: "Internal server error" });
  }
};

// Optional JWT Authentication (doesn't fail if no token)
const optionalAuthenticateJWT = async (req: any, res: any, next: any) => {
  try {
    const authHeader = req.headers.authorization;
    if (authHeader && authHeader.startsWith('Bearer ')) {
      const token = authHeader.substring(7);

      try {
        const decoded = jwt.verify(token, JWT_SECRET) as any;
        const user = await mysqlStorage.getUser(decoded.userId);

        if (user) {
          req.user = user;
          req.userId = decoded.userId;
          req.userRole = decoded.role;
          req.session.userId = decoded.userId.toString();
          req.session.userRole = decoded.role;
        }
      } catch (jwtError) {
        // Ignore JWT errors for optional auth
        console.log('Optional JWT verification failed:', jwtError);
      }
    }
    next();
  } catch (error) {
    console.error('Optional authentication middleware error:', error);
    next(); // Continue even if there's an error
  }
};

// Role-based authorization middleware
const requireRole = (allowedRoles: string[]) => {
  return async (req: any, res: any, next: any) => {
    try {
      if (!req.user) {
        return res.status(401).json({ error: "Authentication required" });
      }

      if (!allowedRoles.includes(req.userRole)) {
        return res.status(403).json({
          error: `Access denied. Required role: ${allowedRoles.join(' or ')}`
        });
      }

      next();
    } catch (error) {
      console.error('Role authorization error:', error);
      return res.status(500).json({ error: "Internal server error" });
    }
  };
};

// Admin-only middleware
const requireAdmin = requireRole(['admin']);

export async function registerRoutes(app: Express): Promise<Server> {
  // Initialize groups and users_groups tables
  try {
    await mysqlStorage.seedGroupsAndUsers();
    console.log("✅ Groups and users seeded successfully");
  } catch (error) {
    console.error("❌ Error seeding groups and users:", error);
    console.log("🔄 Continuing with in-memory storage fallback...");
  }

  // Initialize app categories and mappings
  try {
    await mysqlStorage.seedAppCategoriesAndMappings();
    console.log("✅ App categories and mappings seeded successfully");
  } catch (error) {
    console.error("❌ Error seeding app categories and mappings:", error);
    console.log("🔄 Continuing without app categories...");
  }

  // Test route
  app.get("/api/test", (req, res) => {
    res.json({ message: "API is working!" });
  });

  // Admin Authentication Routes - Using MySQL
  app.post("/api/auth/admin/login", async (req, res) => {
    try {
      const { username, password } = adminLoginSchema.parse(req.body);

      // Authenticate using MySQL storage
      const user = await mysqlStorage.authenticateUser(username, password);
      if (!user) {
        return res.status(401).json({ error: "Invalid username or password" });
      }

      // Check if user is admin
      const isAdmin = await mysqlStorage.isAdmin(user.id);
      if (!isAdmin) {
        return res.status(403).json({ error: "Access denied. Admin privileges required." });
      }

      // Generate JWT token
      const token = jwt.sign(
        {
          userId: user.id,
          username: user.username,
          email: user.email,
          role: 'admin'
        },
        JWT_SECRET,
        { expiresIn: '24h' }
      );

      // Store user session for backward compatibility
      req.session.userId = user.id.toString();
      req.session.userRole = 'admin';

      // Return user data (excluding password) with JWT token
      const { password: _, salt: __, ...userWithoutPassword } = user;
      res.json({
        user: {
          ...userWithoutPassword,
          role: 'admin'
        },
        token,
        message: "Admin login successful",
        isAdmin: true
      });
    } catch (error) {
      if (error instanceof z.ZodError) {
        return res.status(400).json({ error: error.errors[0].message });
      }
      console.error("Admin login error:", error);
      res.status(500).json({ error: "Internal server error" });
    }
  });

  // Regular user authentication with group-based role determination
  app.post("/api/auth/login", async (req, res) => {
    try {
      const { username, password } = loginSchema.parse(req.body);
      console.log("🔐 Login attempt for username:", username);

      // Try MySQL storage first with group-based authentication
      let mysqlUser;
      try {
        mysqlUser = await mysqlStorage.authenticateUser(username, password);
      } catch (error) {
        console.log("❌ MySQL authentication failed, trying in-memory storage...");
        mysqlUser = null;
      }

      if (mysqlUser) {
        // Get user role based on group membership using the SQL query provided
        const userWithRole = await mysqlStorage.getUserWithRole(mysqlUser.id);

        if (userWithRole) {
          // Generate JWT token
          const token = jwt.sign(
            {
              userId: mysqlUser.id,
              username: mysqlUser.username,
              email: mysqlUser.email,
              role: userWithRole.role || 'user'
            },
            JWT_SECRET,
            { expiresIn: '24h' }
          );

          req.session.userId = mysqlUser.id.toString();
          req.session.userRole = userWithRole.role || 'user';

          const { password: _, salt: __, ...userWithoutPassword } = mysqlUser;
          return res.json({
            user: {
              ...userWithoutPassword,
              role: userWithRole.role,
              firstName: userWithoutPassword.firstName || userWithRole.firstName,
              company: userWithoutPassword.company || userWithRole.company
            },
            token,
            message: "Login successful"
          });
        } else {
          // User exists but has no group assignment, default to 'user' role
          // Generate JWT token
          const token = jwt.sign(
            {
              userId: mysqlUser.id,
              username: mysqlUser.username,
              email: mysqlUser.email,
              role: 'user'
            },
            JWT_SECRET,
            { expiresIn: '24h' }
          );

          req.session.userId = mysqlUser.id.toString();
          req.session.userRole = 'user';

          const { password: _, salt: __, ...userWithoutPassword } = mysqlUser;
          return res.json({
            user: {
              ...userWithoutPassword,
              role: 'user'
            },
            token,
            message: "Login successful"
          });
        }
      }

      // Fallback to in-memory storage
      console.log("🔄 Trying in-memory storage authentication...");
      const user = await storage.getUserByUsername(username);
      if (!user) {
        console.log("❌ User not found in in-memory storage");
        return res.status(401).json({ error: "Invalid username or password" });
      }

      console.log("✅ User found in in-memory storage:", user.username);
      const isValidPassword = await storage.verifyPassword(password, user.password);
      if (!isValidPassword) {
        console.log("❌ Invalid password for in-memory user");
        return res.status(401).json({ error: "Invalid username or password" });
      }
      console.log("✅ In-memory authentication successful");

      // Determine role based on username for demo users
      let role = 'user';
      if (user.username === 'admin') role = 'admin';
      else if (user.username === 'corporate') role = 'corporate';
      else if (user.username === 'head_office') role = 'head_office';
      else if (user.username === 'regional') role = 'regional';
      else if (user.username === 'branch') role = 'branch';

      // Generate JWT token for in-memory user
      const token = jwt.sign(
        {
          userId: user.id,
          username: user.username,
          email: user.email,
          role: role
        },
        JWT_SECRET,
        { expiresIn: '24h' }
      );

      req.session.userId = user.id.toString();
      req.session.userRole = role;

      const { password: _, ...userWithoutPassword } = user;
      res.json({
        user: {
          ...userWithoutPassword,
          role: role
        },
        token,
        message: "Login successful"
      });
    } catch (error) {
      if (error instanceof z.ZodError) {
        return res.status(400).json({ error: error.errors[0].message });
      }
      console.error("Login error:", error);
      res.status(500).json({ error: "Internal server error" });
    }
  });

  app.post("/api/auth/logout", (req, res) => {
    req.session.destroy((err: any) => {
      if (err) {
        return res.status(500).json({ error: "Could not log out" });
      }
      res.json({ message: "Logged out successfully" });
    });
  });

  app.get("/api/auth/me", authenticateJWT, async (req: any, res) => {
    try {
      const user = req.user;

      // Try to get admin status, fallback to role-based check
      let isAdmin = false;
      try {
        isAdmin = await mysqlStorage.isAdmin(user.id);
      } catch (error) {
        // Fallback: check if user role is admin
        isAdmin = req.userRole === 'admin' || user.role === 'admin';
      }

      const { password: _, salt: __, ...userWithoutPassword } = user;

      res.json({
        user: {
          ...userWithoutPassword,
          role: req.userRole || user.role || 'user'
        },
        userId: user.id,
        userRole: req.userRole || user.role || 'user',
        isAdmin
      });
    } catch (error) {
      console.error("Error getting user info:", error);
      res.status(500).json({ error: "Internal server error" });
    }
  });

  // Admin dashboard route
  app.get("/api/admin/dashboard", authenticateJWT, requireAdmin, async (req: any, res) => {
    try {
      // Get admin dashboard data
      res.json({
        message: "Welcome to admin dashboard",
        timestamp: new Date().toISOString(),
        adminId: req.user.id
      });
    } catch (error) {
      console.error("Admin dashboard error:", error);
      res.status(500).json({ error: "Internal server error" });
    }
  });

  // User Registration Routes
  app.post("/api/users/register", async (req, res) => {
    try {
      const step1Data = registrationStep1Schema.parse(req.body.step1);
      const step2Data = registrationStep2Schema.parse(req.body.step2 || {});

      let newUser;

      try {
        // Try MySQL storage first
        const existingUserByUsername = await mysqlStorage.getUserByUsername(step1Data.username);
        if (existingUserByUsername) {
          return res.status(400).json({ error: "Username already exists" });
        }

        const existingUserByEmail = await mysqlStorage.getUserByEmail(step1Data.email);
        if (existingUserByEmail) {
          return res.status(400).json({ error: "Email already exists" });
        }

        // Create user with combined data using MySQL storage
        const userData = {
          username: step1Data.username,
          firstName: step1Data.firstName,
          lastName: step1Data.lastName,
          email: step1Data.email,
          phone: step1Data.phone,
          password: step1Data.password, // Will be hashed in mysqlStorage.createUser
          ipAddress: req.ip || '127.0.0.1',
          company: step2Data.company || null,
          // Additional fields will be added after table migration
          role: step1Data.role || 'user',
          gender: step2Data.gender || null,
          dateOfBirth: step2Data.dateOfBirth || null,
          country: step2Data.country || null,
          state: step2Data.state || null,
          district: step2Data.district || null,
          education: step2Data.education || null,
          profession: step2Data.profession || null,
        };

        newUser = await mysqlStorage.createUser(userData);
      } catch (mysqlError) {
        console.log("MySQL storage failed, falling back to in-memory storage:", mysqlError);

        // Fall back to in-memory storage
        const existingUser = await storage.getUserByUsername(step1Data.username);
        if (existingUser) {
          return res.status(400).json({ error: "Username already exists" });
        }

        const existingUserByEmail = await storage.getUserByEmail(step1Data.email);
        if (existingUserByEmail) {
          return res.status(400).json({ error: "Email already exists" });
        }

        // Create user with in-memory storage
        const userData = {
          username: step1Data.username,
          firstName: step1Data.firstName,
          lastName: step1Data.lastName,
          email: step1Data.email,
          phone: step1Data.phone,
          password: step1Data.password,
          ipAddress: req.ip || '127.0.0.1',
          role: step1Data.role || 'user',
        };

        newUser = await storage.createUser(userData);
      }

      // Generate JWT token
      const token = jwt.sign(
        {
          userId: newUser.id,
          username: newUser.username,
          email: newUser.email,
          role: step1Data.role || 'user'
        },
        JWT_SECRET,
        { expiresIn: '24h' }
      );

      // Store user session for backward compatibility
      req.session.userId = newUser.id.toString();
      req.session.userRole = step1Data.role || 'user';

      // Return user data (excluding password) with JWT token
      const { password: _, salt: __, ...userWithoutPassword } = newUser;
      res.status(201).json({
        user: {
          ...userWithoutPassword,
          role: step1Data.role || 'user'
        },
        token,
        message: "User created successfully"
      });
    } catch (error) {
      if (error instanceof z.ZodError) {
        console.error("Registration validation error:", error.errors);
        return res.status(400).json({ error: error.errors[0].message });
      }
      console.error("Registration error:", error);
      console.error("Error details:", {
        message: error instanceof Error ? error.message : 'Unknown error',
        stack: error instanceof Error ? error.stack : undefined
      });
      res.status(500).json({ error: "Internal server error" });
    }
  });

  // ===== ADMIN USER MANAGEMENT API ROUTES =====

  // Get all users for admin dashboard
  app.get("/api/admin/users", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      // Get all users from MySQL storage
      const users = await mysqlStorage.getAllUsers();
      res.json(users);
    } catch (error) {
      console.error("Error fetching users:", error);
      res.status(500).json({ error: "Failed to fetch users" });
    }
  });

  // Delete user
  app.delete("/api/admin/users/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const userId = parseInt(req.params.id);
      const success = await mysqlStorage.deleteUser(userId);

      if (success) {
        res.json({ message: "User deleted successfully" });
      } else {
        res.status(404).json({ error: "User not found" });
      }
    } catch (error) {
      console.error("Error deleting user:", error);
      res.status(500).json({ error: "Failed to delete user" });
    }
  });

  // ===== PROFILE MANAGEMENT API ROUTES =====

  // Group Management Routes (using group_create table)
  app.get("/api/admin/groups", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const groups = await mysqlStorage.getAllGroups();
      res.json(groups);
    } catch (error) {
      console.error("Error fetching groups:", error);
      res.status(500).json({ error: "Failed to fetch groups" });
    }
  });

  app.post("/api/admin/groups", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const groupData = groupCreateSchema.parse(req.body);
      const newGroup = await mysqlStorage.createGroup(groupData);
      res.status(201).json(newGroup);
    } catch (error) {
      if (error instanceof z.ZodError) {
        return res.status(400).json({ error: error.errors[0].message });
      }
      console.error("Error creating group:", error);
      res.status(500).json({ error: "Failed to create group" });
    }
  });

  app.put("/api/admin/groups/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const groupId = parseInt(req.params.id);
      const groupData = groupCreateSchema.parse(req.body);
      const updatedGroup = await mysqlStorage.updateGroup(groupId, groupData);

      if (!updatedGroup) {
        return res.status(404).json({ error: "Group not found" });
      }

      res.json(updatedGroup);
    } catch (error) {
      if (error instanceof z.ZodError) {
        return res.status(400).json({ error: error.errors[0].message });
      }
      console.error("Error updating group:", error);
      res.status(500).json({ error: "Failed to update group" });
    }
  });

  app.delete("/api/admin/groups/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const groupId = parseInt(req.params.id);
      const deleted = await mysqlStorage.deleteGroup(groupId);

      if (!deleted) {
        return res.status(404).json({ error: "Group not found" });
      }

      res.json({ message: "Group deleted successfully" });
    } catch (error) {
      console.error("Error deleting group:", error);
      res.status(500).json({ error: "Failed to delete group" });
    }
  });

  // App Create Routes (using group_create and create_details tables)
  app.get("/api/admin/app-create", async (req, res) => {
    try {
      // Check admin authentication
      if (!req.session.userId || req.session.userRole !== 'admin') {
        return res.status(401).json({ error: "Admin authentication required" });
      }

      const apps = await mysqlStorage.getAllAppsWithDetails();
      res.json(apps);
    } catch (error) {
      console.error("Error fetching apps:", error);
      res.status(500).json({ error: "Failed to fetch apps" });
    }
  });

  app.post("/api/admin/app-create", async (req, res) => {
    try {
      // Check admin authentication
      if (!req.session.userId || req.session.userRole !== 'admin') {
        return res.status(401).json({ error: "Admin authentication required" });
      }

      const { groupData, detailsData } = req.body;

      // Validate group data
      const validatedGroupData = groupCreateSchema.parse(groupData);

      // Create the group first
      const newGroup = await mysqlStorage.createGroup(validatedGroupData);

      // If details data is provided, create details
      if (detailsData) {
        const validatedDetailsData = createDetailsSchema.parse({
          ...detailsData,
          createId: newGroup.id
        });
        await mysqlStorage.createAppDetails(validatedDetailsData);
      }

      // Return the complete app with details
      const completeApp = await mysqlStorage.getAppWithDetails(newGroup.id);
      res.status(201).json(completeApp);
    } catch (error) {
      if (error instanceof z.ZodError) {
        return res.status(400).json({ error: error.errors[0].message });
      }
      console.error("Error creating app:", error);
      res.status(500).json({ error: "Failed to create app" });
    }
  });

  // App Account Routes (user management for apps)
  app.get("/api/admin/app-accounts", async (req, res) => {
    try {
      // Check admin authentication
      if (!req.session.userId || req.session.userRole !== 'admin') {
        return res.status(401).json({ error: "Admin authentication required" });
      }

      const accounts = await mysqlStorage.getAllAppAccounts();
      // console.error("Fetching app accounts:", accounts);
      res.json(accounts);
    } catch (error) {
      console.error("Error fetching app accounts:", error);
      res.status(500).json({ error: "Failed to fetch app accounts" });
    }
  });

  app.post("/api/admin/app-accounts", async (req, res) => {
    try {
      // Check admin authentication
      if (!req.session.userId || req.session.userRole !== 'admin') {
        return res.status(401).json({ error: "Admin authentication required" });
      }

      const { username, password, appId } = req.body;

      // Validate required fields
      if (!username || !password || !appId) {
        return res.status(400).json({ error: "Username, password, and app ID are required" });
      }

      // Create user account for the app
      const newAccount = await mysqlStorage.createAppAccount({
        username,
        password,
        appId: parseInt(appId),
        email: `${username}@mygroup.com`, // Generate email
        ipAddress: req.ip || '127.0.0.1'
      });

      res.status(201).json(newAccount);
    } catch (error) {
      console.error("Error creating app account:", error);
      res.status(500).json({ error: "Failed to create app account" });
    }
  });

  app.delete("/api/admin/app-accounts/:id", async (req, res) => {
    try {
      // Check admin authentication
      if (!req.session.userId || req.session.userRole !== 'admin') {
        return res.status(401).json({ error: "Admin authentication required" });
      }

      const accountId = parseInt(req.params.id);
      const deleted = await mysqlStorage.deleteAppAccount(accountId);

      if (!deleted) {
        return res.status(404).json({ error: "Account not found" });
      }

      res.json({ message: "Account deleted successfully" });
    } catch (error) {
      console.error("Error deleting app account:", error);
      res.status(500).json({ error: "Failed to delete app account" });
    }
  });

  // Change Password Route
  app.post("/api/admin/change-password", authenticateJWT, requireAdmin, async (req: any, res) => {
    try {
      const passwordData = changePasswordSchema.parse(req.body);
      const userId = req.user.id;

      // Verify old password
      const user = await mysqlStorage.getUser(userId);
      if (!user) {
        return res.status(404).json({ error: "User not found" });
      }

      const isValidOldPassword = await mysqlStorage.verifyPassword(passwordData.oldPassword, user.password);
      if (!isValidOldPassword) {
        return res.status(400).json({ error: "Current password is incorrect" });
      }

      // Update password
      const updated = await mysqlStorage.updateUserPassword(userId, passwordData.newPassword);
      if (!updated) {
        return res.status(500).json({ error: "Failed to update password" });
      }

      res.json({ message: "Password updated successfully" });
    } catch (error) {
      if (error instanceof z.ZodError) {
        return res.status(400).json({ error: error.errors[0].message });
      }
      console.error("Error changing password:", error);
      res.status(500).json({ error: "Failed to change password" });
    }
  });

  // ===== FILE UPLOAD API ROUTES =====

  // File upload route for profile assets
  app.post("/api/admin/upload", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      if (!req.files || Object.keys(req.files).length === 0) {
        return res.status(400).json({ error: "No files were uploaded" });
      }

      const uploadedFiles: any[] = [];
      const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
      const maxSize = 5 * 1024 * 1024; // 5MB

      // Process each uploaded file
      for (const [fieldName, file] of Object.entries(req.files)) {
        const uploadedFile = Array.isArray(file) ? file[0] : file as any;

        // Validate file type
        if (!allowedTypes.includes(uploadedFile.mimetype)) {
          return res.status(400).json({
            error: `Invalid file type for ${fieldName}. Allowed types: ${allowedTypes.join(', ')}`
          });
        }

        // Validate file size
        if (uploadedFile.size > maxSize) {
          return res.status(400).json({
            error: `File ${fieldName} is too large. Maximum size: ${maxSize / (1024 * 1024)}MB`
          });
        }

        // Generate unique filename
        const timestamp = Date.now();
        const extension = path.extname(uploadedFile.name);
        const filename = `${fieldName}_${timestamp}${extension}`;

        // Determine upload path based on field name
        let uploadPath: string;
        if (['icon', 'logo', 'nameImage', 'banner'].includes(fieldName)) {
          uploadPath = path.join(process.cwd(), 'uploads', 'assets', 'App', filename);
        } else {
          uploadPath = path.join(process.cwd(), 'uploads', filename);
        }

        // Move file to destination
        await uploadedFile.mv(uploadPath);

        // Generate URL for the uploaded file
        const fileUrl = `/uploads/${fieldName === 'icon' || fieldName === 'logo' || fieldName === 'nameImage' || fieldName === 'banner' ? 'assets/App/' : ''}${filename}`;

        uploadedFiles.push({
          fieldName,
          originalName: uploadedFile.name,
          filename,
          url: fileUrl,
          size: uploadedFile.size,
          mimetype: uploadedFile.mimetype
        });
      }

      res.json({
        message: "Files uploaded successfully",
        files: uploadedFiles
      });
    } catch (error) {
      console.error("Error uploading files:", error);
      res.status(500).json({ error: "Failed to upload files" });
    }
  });

  // Delete uploaded file route
  app.delete("/api/admin/upload/:filename", async (req, res) => {
    try {
      // Check admin authentication
      if (!req.session.userId || req.session.userRole !== 'admin') {
        return res.status(401).json({ error: "Admin authentication required" });
      }

      const filename = req.params.filename;
      const { folder } = req.query;

      // Construct file path
      let filePath: string;
      if (folder === 'app') {
        filePath = path.join(process.cwd(), 'uploads', 'assets', 'App', filename);
      } else {
        filePath = path.join(process.cwd(), 'uploads', filename);
      }

      // Check if file exists and delete it
      if (fs.existsSync(filePath)) {
        fs.unlinkSync(filePath);
        res.json({ message: "File deleted successfully" });
      } else {
        res.status(404).json({ error: "File not found" });
      }
    } catch (error) {
      console.error("Error deleting file:", error);
      res.status(500).json({ error: "Failed to delete file" });
    }
  });

  // Add missing columns to users table
  app.get("/api/add-registration-columns", async (req, res) => {
    try {
      // Add the new registration columns to the existing users table
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
          await mysqlStorage.executeQuery(query);
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

  // Initialize demo users
  app.get("/api/init-demo-users", async (req, res) => {
    try {
      // Check if demo users already exist
      const existingAdmin = await storage.getUserByUsername("admin");
      if (existingAdmin) {
        return res.json({ message: "Demo users already exist" });
      }

      // Create demo users with proper schema
      const demoUsers = [
        {
          username: "admin",
          firstName: "System",
          lastName: "Administrator",
          email: "admin@apphub.com",
          phone: "1234567890",
          password: "password", // Will be hashed in storage.createUser
          ipAddress: "127.0.0.1",
          role: "admin",
          gender: null,
          dateOfBirth: null,
          country: null,
          state: null,
          district: null,
          education: null,
          profession: null,
          company: null,
        },
        {
          username: "corporate",
          firstName: "Corporate",
          lastName: "Manager",
          email: "corporate@apphub.com",
          phone: "1234567891",
          password: "password",
          ipAddress: "127.0.0.1",
          role: "corporate",
          gender: null,
          dateOfBirth: null,
          country: null,
          state: null,
          district: null,
          education: null,
          profession: null,
          company: null,
        },
        {
          username: "head_office",
          firstName: "Head Office",
          lastName: "Executive",
          email: "headoffice@apphub.com",
          phone: "1234567892",
          password: "password",
          ipAddress: "127.0.0.1",
          role: "head_office",
          gender: null,
          dateOfBirth: null,
          country: null,
          state: null,
          district: null,
          education: null,
          profession: null,
          company: null,
        },
        {
          username: "regional",
          firstName: "Regional",
          lastName: "Manager",
          email: "regional@apphub.com",
          phone: "1234567893",
          password: "password",
          ipAddress: "127.0.0.1",
          role: "regional",
          gender: null,
          dateOfBirth: null,
          country: null,
          state: null,
          district: null,
          education: null,
          profession: null,
          company: null,
        },
        {
          username: "branch",
          firstName: "Branch",
          lastName: "Manager",
          email: "branch@apphub.com",
          phone: "1234567894",
          password: "password",
          ipAddress: "127.0.0.1",
          role: "branch",
          gender: null,
          dateOfBirth: null,
          country: null,
          state: null,
          district: null,
          education: null,
          profession: null,
          company: null,
        }
      ];

      for (const userData of demoUsers) {
        await storage.createUser(userData);
      }

      res.json({ message: "Demo users created successfully" });
    } catch (error) {
      console.error("Error creating demo users:", error);
      res.status(500).json({ error: "Failed to create demo users" });
    }
  });

  // ===== CONTENT MANAGEMENT API ROUTES =====

  // Continent Routes
  app.get("/api/admin/continents", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const continents = await mysqlStorage.getAllContinents();
      res.json(continents);
    } catch (error) {
      console.error("Error fetching continents:", error);
      res.status(500).json({ error: "Failed to fetch continents" });
    }
  });

  app.get("/api/admin/continents/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const continentId = parseInt(req.params.id);
      const continent = await mysqlStorage.getContinentById(continentId);

      if (!continent) {
        return res.status(404).json({ error: "Continent not found" });
      }

      res.json(continent);
    } catch (error) {
      console.error("Error fetching continent:", error);
      res.status(500).json({ error: "Failed to fetch continent" });
    }
  });

  app.post("/api/admin/continents", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const continentData = continentSchema.parse(req.body);
      const newContinent = await mysqlStorage.createContinent(continentData);
      res.status(201).json(newContinent);
    } catch (error) {
      if (error instanceof z.ZodError) {
        return res.status(400).json({ error: error.errors[0].message });
      }
      console.error("Error creating continent:", error);
      res.status(500).json({ error: "Failed to create continent" });
    }
  });

  app.put("/api/admin/continents/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const continentId = parseInt(req.params.id);
      const continentData = continentSchema.parse(req.body);
      const updatedContinent = await mysqlStorage.updateContinent(continentId, continentData);

      if (!updatedContinent) {
        return res.status(404).json({ error: "Continent not found" });
      }

      res.json(updatedContinent);
    } catch (error) {
      if (error instanceof z.ZodError) {
        return res.status(400).json({ error: error.errors[0].message });
      }
      console.error("Error updating continent:", error);
      res.status(500).json({ error: "Failed to update continent" });
    }
  });

  app.delete("/api/admin/continents/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const continentId = parseInt(req.params.id);
      const deleted = await mysqlStorage.deleteContinent(continentId);

      if (!deleted) {
        return res.status(404).json({ error: "Continent not found" });
      }

      res.json({ message: "Continent deleted successfully" });
    } catch (error) {
      console.error("Error deleting continent:", error);
      res.status(500).json({ error: "Failed to delete continent" });
    }
  });

  // Country Routes
  app.get("/api/admin/countries", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const countries = await mysqlStorage.getAllCountries();
      res.json(countries);
    } catch (error) {
      console.error("Error fetching countries:", error);
      res.status(500).json({ error: "Failed to fetch countries" });
    }
  });

  app.get("/api/admin/countries/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const countryId = parseInt(req.params.id);
      const country = await mysqlStorage.getCountryById(countryId);

      if (!country) {
        return res.status(404).json({ error: "Country not found" });
      }

      res.json(country);
    } catch (error) {
      console.error("Error fetching country:", error);
      res.status(500).json({ error: "Failed to fetch country" });
    }
  });

  app.get("/api/admin/countries/by-continent/:continentId", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const continentId = parseInt(req.params.continentId);
      const countries = await mysqlStorage.getCountriesByContinent(continentId);
      res.json(countries);
    } catch (error) {
      console.error("Error fetching countries by continent:", error);
      res.status(500).json({ error: "Failed to fetch countries" });
    }
  });

  app.post("/api/admin/countries", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const countryData = countrySchema.parse(req.body);
      const newCountry = await mysqlStorage.createCountry(countryData);
      res.status(201).json(newCountry);
    } catch (error) {
      if (error instanceof z.ZodError) {
        return res.status(400).json({ error: error.errors[0].message });
      }
      console.error("Error creating country:", error);
      res.status(500).json({ error: "Failed to create country" });
    }
  });

  app.put("/api/admin/countries/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const countryId = parseInt(req.params.id);
      const countryData = countrySchema.parse(req.body);
      const updatedCountry = await mysqlStorage.updateCountry(countryId, countryData);

      if (!updatedCountry) {
        return res.status(404).json({ error: "Country not found" });
      }

      res.json(updatedCountry);
    } catch (error) {
      if (error instanceof z.ZodError) {
        return res.status(400).json({ error: error.errors[0].message });
      }
      console.error("Error updating country:", error);
      res.status(500).json({ error: "Failed to update country" });
    }
  });

  app.delete("/api/admin/countries/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const countryId = parseInt(req.params.id);
      const deleted = await mysqlStorage.deleteCountry(countryId);

      if (!deleted) {
        return res.status(404).json({ error: "Country not found" });
      }

      res.json({ message: "Country deleted successfully" });
    } catch (error) {
      console.error("Error deleting country:", error);
      res.status(500).json({ error: "Failed to delete country" });
    }
  });

  // State Routes
  app.get("/api/admin/states", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const states = await mysqlStorage.getAllStates();
      res.json(states);
    } catch (error) {
      console.error("Error fetching states:", error);
      res.status(500).json({ error: "Failed to fetch states" });
    }
  });

  app.get("/api/admin/states/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const stateId = parseInt(req.params.id);
      const state = await mysqlStorage.getStateById(stateId);

      if (!state) {
        return res.status(404).json({ error: "State not found" });
      }

      res.json(state);
    } catch (error) {
      console.error("Error fetching state:", error);
      res.status(500).json({ error: "Failed to fetch state" });
    }
  });

  app.get("/api/admin/states/by-country/:countryId", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const countryId = parseInt(req.params.countryId);
      const states = await mysqlStorage.getStatesByCountry(countryId);
      res.json(states);
    } catch (error) {
      console.error("Error fetching states by country:", error);
      res.status(500).json({ error: "Failed to fetch states" });
    }
  });

  app.post("/api/admin/states", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const stateData = stateSchema.parse(req.body);
      const newState = await mysqlStorage.createState(stateData);
      res.status(201).json(newState);
    } catch (error) {
      if (error instanceof z.ZodError) {
        return res.status(400).json({ error: error.errors[0].message });
      }
      console.error("Error creating state:", error);
      res.status(500).json({ error: "Failed to create state" });
    }
  });

  app.put("/api/admin/states/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const stateId = parseInt(req.params.id);
      const stateData = stateSchema.parse(req.body);
      const updatedState = await mysqlStorage.updateState(stateId, stateData);

      if (!updatedState) {
        return res.status(404).json({ error: "State not found" });
      }

      res.json(updatedState);
    } catch (error) {
      if (error instanceof z.ZodError) {
        return res.status(400).json({ error: error.errors[0].message });
      }
      console.error("Error updating state:", error);
      res.status(500).json({ error: "Failed to update state" });
    }
  });

  app.delete("/api/admin/states/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const stateId = parseInt(req.params.id);
      const deleted = await mysqlStorage.deleteState(stateId);

      if (!deleted) {
        return res.status(404).json({ error: "State not found" });
      }

      res.json({ message: "State deleted successfully" });
    } catch (error) {
      console.error("Error deleting state:", error);
      res.status(500).json({ error: "Failed to delete state" });
    }
  });

  // District Routes
  app.get("/api/admin/districts", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const districts = await mysqlStorage.getAllDistricts();
      res.json(districts);
    } catch (error) {
      console.error("Error fetching districts:", error);
      res.status(500).json({ error: "Failed to fetch districts" });
    }
  });

  app.get("/api/admin/districts/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const districtId = parseInt(req.params.id);
      const district = await mysqlStorage.getDistrictById(districtId);

      if (!district) {
        return res.status(404).json({ error: "District not found" });
      }

      res.json(district);
    } catch (error) {
      console.error("Error fetching district:", error);
      res.status(500).json({ error: "Failed to fetch district" });
    }
  });

  app.get("/api/admin/districts/by-state/:stateId", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const stateId = parseInt(req.params.stateId);
      const districts = await mysqlStorage.getDistrictsByState(stateId);
      res.json(districts);
    } catch (error) {
      console.error("Error fetching districts by state:", error);
      res.status(500).json({ error: "Failed to fetch districts" });
    }
  });

  app.get("/api/admin/districts/by-country/:countryId", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const countryId = parseInt(req.params.countryId);
      const districts = await mysqlStorage.getDistrictsByCountry(countryId);
      res.json(districts);
    } catch (error) {
      console.error("Error fetching districts by country:", error);
      res.status(500).json({ error: "Failed to fetch districts" });
    }
  });

  app.post("/api/admin/districts", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const districtData = districtSchema.parse(req.body);
      const newDistrict = await mysqlStorage.createDistrict(districtData);
      res.status(201).json(newDistrict);
    } catch (error) {
      if (error instanceof z.ZodError) {
        return res.status(400).json({ error: error.errors[0].message });
      }
      console.error("Error creating district:", error);
      res.status(500).json({ error: "Failed to create district" });
    }
  });

  app.put("/api/admin/districts/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const districtId = parseInt(req.params.id);
      const districtData = districtSchema.parse(req.body);
      const updatedDistrict = await mysqlStorage.updateDistrict(districtId, districtData);

      if (!updatedDistrict) {
        return res.status(404).json({ error: "District not found" });
      }

      res.json(updatedDistrict);
    } catch (error) {
      if (error instanceof z.ZodError) {
        return res.status(400).json({ error: error.errors[0].message });
      }
      console.error("Error updating district:", error);
      res.status(500).json({ error: "Failed to update district" });
    }
  });

  app.delete("/api/admin/districts/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const districtId = parseInt(req.params.id);
      const deleted = await mysqlStorage.deleteDistrict(districtId);

      if (!deleted) {
        return res.status(404).json({ error: "District not found" });
      }

      res.json({ message: "District deleted successfully" });
    } catch (error) {
      console.error("Error deleting district:", error);
      res.status(500).json({ error: "Failed to delete district" });
    }
  });

  // ===== CATEGORIES MANAGEMENT API ROUTES =====

  // Menu Categories Route (for hierarchical dropdown menu)
  app.get("/api/admin/menu-categories", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      console.log("🔍 Menu Categories API called");

      let categories;
      try {
        categories = await mysqlStorage.getAllCategories();
        console.log("🔍 Menu Categories fetched from MySQL:", categories.length, "items");
      } catch (error) {
        console.log("🔍 MySQL getAllCategories failed, returning empty array:", error instanceof Error ? error.message : String(error));
        // Return empty array as fallback
        categories = [];
      }

      res.json(categories);
    } catch (error) {
      console.error("🔍 Error in menu categories endpoint:", error);
      res.status(500).json({ error: "Failed to fetch menu categories" });
    }
  });

  // Categories Routes (for categories management page)
  app.get("/api/admin/categories", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      console.log("📁 Categories API called");
      const categories = await mysqlStorage.getAllCategories();
      console.log("📁 Categories fetched:", categories.length, "items");
      res.json(categories);
    } catch (error) {
      console.error("📁 Error fetching categories:", error);
      res.status(500).json({ error: "Failed to fetch categories" });
    }
  });

  app.get("/api/admin/categories/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const categoryId = parseInt(req.params.id);
      const category = await mysqlStorage.getCategoryById(categoryId);

      if (!category) {
        return res.status(404).json({ error: "Category not found" });
      }

      res.json(category);
    } catch (error) {
      console.error("Error fetching category:", error);
      res.status(500).json({ error: "Failed to fetch category" });
    }
  });

  app.post("/api/admin/categories", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const categoryData = req.body;
      const newCategory = await mysqlStorage.createCategory(categoryData);
      res.status(201).json(newCategory);
    } catch (error) {
      console.error("Error creating category:", error);
      res.status(500).json({ error: "Failed to create category" });
    }
  });

  app.put("/api/admin/categories/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const categoryId = parseInt(req.params.id);
      const categoryData = req.body;
      const updatedCategory = await mysqlStorage.updateCategory(categoryId, categoryData);

      if (!updatedCategory) {
        return res.status(404).json({ error: "Category not found" });
      }

      res.json(updatedCategory);
    } catch (error) {
      console.error("Error updating category:", error);
      res.status(500).json({ error: "Failed to update category" });
    }
  });

  app.delete("/api/admin/categories/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const categoryId = parseInt(req.params.id);
      const deleted = await mysqlStorage.deleteCategory(categoryId);

      if (!deleted) {
        return res.status(404).json({ error: "Category not found" });
      }

      res.json({ message: "Category deleted successfully" });
    } catch (error) {
      console.error("Error deleting category:", error);
      res.status(500).json({ error: "Failed to delete category" });
    }
  });

  // ===== LANGUAGE, EDUCATION, PROFESSION API ROUTES =====

  // Language Management Routes
  app.get("/api/admin/languages", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      console.log("🌐 API: Fetching all languages");
      const languages = await mysqlStorage.getAllLanguages();
      console.log("🌐 API: Languages fetched:", languages.length);
      res.json(languages);
    } catch (error) {
      console.error("🌐 API: Error fetching languages:", error);
      res.status(500).json({ error: "Failed to fetch languages" });
    }
  });

  // Get language by ID
  app.get("/api/admin/languages/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const languageId = parseInt(req.params.id);
      const language = await mysqlStorage.getLanguageById(languageId);

      if (!language) {
        return res.status(404).json({ error: "Language not found" });
      }

      res.json(language);
    } catch (error) {
      console.error("Error fetching language:", error);
      res.status(500).json({ error: "Failed to fetch language" });
    }
  });

  app.post("/api/admin/languages", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      console.log("🌐 API: Creating language:", req.body);
      const languageData = req.body;

      // Validate required fields
      if (!languageData.name || !languageData.code) {
        return res.status(400).json({ error: "Name and code are required" });
      }

      const newLanguage = await mysqlStorage.createLanguage(languageData);
      console.log("🌐 API: Language created:", newLanguage);
      res.status(201).json(newLanguage);
    } catch (error) {
      console.error("🌐 API: Error creating language:", error);
      res.status(500).json({ error: "Failed to create language" });
    }
  });

  // Update language
  app.put("/api/admin/languages/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const languageId = parseInt(req.params.id);
      const languageData = req.body;

      console.log("🌐 API: Updating language:", languageId, languageData);

      const updatedLanguage = await mysqlStorage.updateLanguage(languageId, languageData);

      if (!updatedLanguage) {
        return res.status(404).json({ error: "Language not found" });
      }

      console.log("🌐 API: Language updated:", updatedLanguage);
      res.json(updatedLanguage);
    } catch (error) {
      console.error("🌐 API: Error updating language:", error);
      res.status(500).json({ error: "Failed to update language" });
    }
  });

  // Delete language
  app.delete("/api/admin/languages/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const languageId = parseInt(req.params.id);
      console.log("🌐 API: Deleting language:", languageId);

      const deleted = await mysqlStorage.deleteLanguage(languageId);

      if (!deleted) {
        return res.status(404).json({ error: "Language not found" });
      }

      console.log("🌐 API: Language deleted successfully");
      res.json({ message: "Language deleted successfully" });
    } catch (error) {
      console.error("🌐 API: Error deleting language:", error);
      res.status(500).json({ error: "Failed to delete language" });
    }
  });

  // Education Management Routes
  app.get("/api/admin/education", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      console.log("🎓 API: Fetching all education levels");
      const education = await mysqlStorage.getAllEducation();
      console.log("🎓 API: Education levels fetched:", education.length);
      res.json(education);
    } catch (error) {
      console.error("🎓 API: Error fetching education levels:", error);
      res.status(500).json({ error: "Failed to fetch education levels" });
    }
  });

  app.post("/api/admin/education", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      console.log("🎓 API: Creating education level:", req.body);
      const educationData = req.body;

      // Validate required fields
      if (!educationData.level) {
        return res.status(400).json({ error: "Level is required" });
      }

      const newEducation = await mysqlStorage.createEducation(educationData);
      console.log("🎓 API: Education level created:", newEducation);
      res.status(201).json(newEducation);
    } catch (error) {
      console.error("🎓 API: Error creating education level:", error);
      res.status(500).json({ error: "Failed to create education level" });
    }
  });

  // Update education level
  app.put("/api/admin/education/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const educationId = parseInt(req.params.id);
      const educationData = req.body;

      console.log("🎓 API: Updating education level:", educationId, educationData);

      const updatedEducation = await mysqlStorage.updateEducation(educationId, educationData);

      if (!updatedEducation) {
        return res.status(404).json({ error: "Education level not found" });
      }

      console.log("🎓 API: Education level updated:", updatedEducation);
      res.json(updatedEducation);
    } catch (error) {
      console.error("🎓 API: Error updating education level:", error);
      res.status(500).json({ error: "Failed to update education level" });
    }
  });

  // Delete education level
  app.delete("/api/admin/education/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const educationId = parseInt(req.params.id);
      console.log("🎓 API: Deleting education level:", educationId);

      const deleted = await mysqlStorage.deleteEducation(educationId);

      if (!deleted) {
        return res.status(404).json({ error: "Education level not found" });
      }

      console.log("🎓 API: Education level deleted successfully");
      res.json({ message: "Education level deleted successfully" });
    } catch (error) {
      console.error("🎓 API: Error deleting education level:", error);
      res.status(500).json({ error: "Failed to delete education level" });
    }
  });

  // Profession Management Routes
  app.get("/api/admin/professions", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      console.log("💼 API: Fetching all professions");
      const professions = await mysqlStorage.getAllProfessions();
      console.log("💼 API: Professions fetched:", professions.length);
      res.json(professions);
    } catch (error) {
      console.error("💼 API: Error fetching professions:", error);
      res.status(500).json({ error: "Failed to fetch professions" });
    }
  });

  app.post("/api/admin/professions", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      console.log("💼 API: Creating profession:", req.body);
      const professionData = req.body;

      // Validate required fields
      if (!professionData.name || !professionData.category) {
        return res.status(400).json({ error: "Name and category are required" });
      }

      const newProfession = await mysqlStorage.createProfession(professionData);
      console.log("💼 API: Profession created:", newProfession);
      res.status(201).json(newProfession);
    } catch (error) {
      console.error("💼 API: Error creating profession:", error);
      res.status(500).json({ error: "Failed to create profession" });
    }
  });

  // Update profession
  app.put("/api/admin/professions/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const professionId = parseInt(req.params.id);
      const professionData = req.body;

      console.log("💼 API: Updating profession:", professionId, professionData);

      const updatedProfession = await mysqlStorage.updateProfession(professionId, professionData);

      if (!updatedProfession) {
        return res.status(404).json({ error: "Profession not found" });
      }

      console.log("💼 API: Profession updated:", updatedProfession);
      res.json(updatedProfession);
    } catch (error) {
      console.error("💼 API: Error updating profession:", error);
      res.status(500).json({ error: "Failed to update profession" });
    }
  });

  // Delete profession
  app.delete("/api/admin/professions/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const professionId = parseInt(req.params.id);
      console.log("💼 API: Deleting profession:", professionId);

      const deleted = await mysqlStorage.deleteProfession(professionId);

      if (!deleted) {
        return res.status(404).json({ error: "Profession not found" });
      }

      console.log("💼 API: Profession deleted successfully");
      res.json({ message: "Profession deleted successfully" });
    } catch (error) {
      console.error("💼 API: Error deleting profession:", error);
      res.status(500).json({ error: "Failed to delete profession" });
    }
  });

  // ===== CORPORATE API ROUTES =====

  // Get menu categories for corporate users
  app.get("/api/corporate/menu-categories", authenticateJWT, async (req: any, res) => {
    try {
      console.log("🏢 Corporate Menu Categories API called");

      // Corporate users get a subset of categories or different categories
      // For now, return the same categories but this can be customized
      const categories = await mysqlStorage.getAllCategories();
      console.log(`🏢 Corporate Menu Categories fetched: ${categories.length} items`);
      res.json(categories);
    } catch (error) {
      console.error("Error fetching corporate menu categories:", error);
      res.status(500).json({ error: "Failed to fetch menu categories" });
    }
  });

  // Get menu categories for regional users
  app.get("/api/regional/menu-categories", authenticateJWT, async (req: any, res) => {
    try {
      console.log("🌍 Regional Menu Categories API called");
      const categories = await mysqlStorage.getAllCategories();
      console.log(`🌍 Regional Menu Categories fetched: ${categories.length} items`);
      res.json(categories);
    } catch (error) {
      console.error("Error fetching regional menu categories:", error);
      res.status(500).json({ error: "Failed to fetch menu categories" });
    }
  });

  // Get menu categories for branch users
  app.get("/api/branch/menu-categories", authenticateJWT, async (req: any, res) => {
    try {
      console.log("🏪 Branch Menu Categories API called");
      const categories = await mysqlStorage.getAllCategories();
      console.log(`🏪 Branch Menu Categories fetched: ${categories.length} items`);
      res.json(categories);
    } catch (error) {
      console.error("Error fetching branch menu categories:", error);
      res.status(500).json({ error: "Failed to fetch menu categories" });
    }
  });

  // Get menu categories for head_office users
  app.get("/api/head_office/menu-categories", authenticateJWT, async (req: any, res) => {
    try {
      console.log("🏢 Head Office Menu Categories API called");
      const categories = await mysqlStorage.getAllCategories();
      console.log(`🏢 Head Office Menu Categories fetched: ${categories.length} items`);
      res.json(categories);
    } catch (error) {
      console.error("Error fetching head office menu categories:", error);
      res.status(500).json({ error: "Failed to fetch menu categories" });
    }
  });

  // ===== CORPORATE USERS API ROUTES =====

  // Get all corporate users
  app.get("/api/admin/corporate-users", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      console.log("🏢 Fetching corporate users from database...");
      const corporateUsers = await mysqlStorage.getUsersByRole('corporate');
      console.log(`🏢 Found ${corporateUsers.length} corporate users`);
      res.json(corporateUsers);
    } catch (error) {
      console.error("Error fetching corporate users:", error);
      res.status(500).json({ error: "Failed to fetch corporate users" });
    }
  });

  // Create corporate user
  app.post("/api/admin/corporate-users", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const { name, mobile, email, username, password } = req.body;

      console.log("🏢 Creating corporate user:", { name, email, username });

      // Validate required fields
      if (!name || !email || !username || !password) {
        return res.status(400).json({ error: "Name, email, username, and password are required" });
      }

      const newUser = await mysqlStorage.createCorporateUser({
        name,
        mobile,
        email,
        username,
        password
      });

      console.log("🏢 Corporate user created successfully:", newUser.id);
      res.status(201).json(newUser);
    } catch (error) {
      console.error("Error creating corporate user:", error);
      const errorMessage = error instanceof Error ? error.message : "Failed to create corporate user";
      res.status(500).json({ error: errorMessage });
    }
  });

  // Update corporate user
  app.put("/api/admin/corporate-users/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const userId = parseInt(req.params.id);
      const { name, mobile, email, username, password } = req.body;

      console.log("🏢 Updating corporate user:", userId, { name, email, username });

      // Validate required fields
      if (!name || !email || !username) {
        return res.status(400).json({ error: "Name, email, and username are required" });
      }

      const updatedUser = await mysqlStorage.updateCorporateUser(userId, {
        name,
        mobile,
        email,
        username,
        password
      });

      console.log("🏢 Corporate user updated successfully:", userId);
      res.json(updatedUser);
    } catch (error) {
      console.error("Error updating corporate user:", error);
      const errorMessage = error instanceof Error ? error.message : "Failed to update corporate user";
      res.status(500).json({ error: errorMessage });
    }
  });

  // Reset corporate user password
  app.post("/api/admin/corporate-users/:id/reset-password", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const userId = parseInt(req.params.id);

      console.log("🔑 Resetting password for corporate user:", userId);

      const temporaryPassword = await mysqlStorage.resetCorporateUserPassword(userId);

      console.log("🔑 Password reset successfully for user:", userId);
      res.json({
        message: "Password reset successfully",
        temporaryPassword
      });
    } catch (error) {
      console.error("Error resetting password:", error);
      const errorMessage = error instanceof Error ? error.message : "Failed to reset password";
      res.status(500).json({ error: errorMessage });
    }
  });

  // ===== ADMIN SETTINGS API ROUTES =====

  // Check if app account exists
  app.get("/api/admin/app-accounts/check/:id", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const appId = parseInt(req.params.id);
      // Mock response - replace with actual database check
      // For now, randomly return true/false to simulate existing accounts
      const exists = Math.random() > 0.5; // This should be replaced with actual DB query
      res.json({ exists });
    } catch (error) {
      console.error("Error checking app account:", error);
      res.status(500).json({ error: "Failed to check app account" });
    }
  });

  // Reset app account password to 'mygroup123'
  app.post("/api/admin/app-accounts/:id/reset-password", authenticateJWT, requireAdmin, async (req, res) => {
    try {
      const appId = parseInt(req.params.id);
      // Mock response - replace with actual password reset logic
      res.json({ message: "App account password reset to 'mygroup123' successfully" });
    } catch (error) {
      console.error("Error resetting app password:", error);
      res.status(500).json({ error: "Failed to reset app password" });
    }
  });

  // ===== DUPLICATE ROUTES REMOVED =====
  // Note: The real continent routes are implemented above in the CONTENT MANAGEMENT section

  // ===== LOGOUT API ROUTE =====

  // Logout route
  app.post("/api/auth/logout", async (req, res) => {
    try {
      req.session.destroy((err) => {
        if (err) {
          console.error("Error destroying session:", err);
          return res.status(500).json({ error: "Failed to logout" });
        }
        res.clearCookie('connect.sid');
        res.json({ message: "Logged out successfully" });
      });
    } catch (error) {
      console.error("Logout error:", error);
      res.status(500).json({ error: "Internal server error" });
    }
  });

  // ===== CORPORATE FEATURE API ROUTES =====

  // Corporate File Upload Route
  app.post("/api/corporate/upload", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      if (!req.files || Object.keys(req.files).length === 0) {
        return res.status(400).json({ error: "No files were uploaded" });
      }

      const uploadedFiles: any[] = [];
      const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
      const maxSize = 10 * 1024 * 1024; // 10MB

      // Process each uploaded file
      for (const [fieldName, file] of Object.entries(req.files)) {
        const uploadedFile = Array.isArray(file) ? file[0] : file as any;

        // Validate file type
        if (!allowedTypes.includes(uploadedFile.mimetype)) {
          return res.status(400).json({
            error: `Invalid file type for ${fieldName}. Allowed types: ${allowedTypes.join(', ')}`
          });
        }

        // Validate file size
        if (uploadedFile.size > maxSize) {
          return res.status(400).json({
            error: `File ${fieldName} is too large. Maximum size: ${maxSize / (1024 * 1024)}MB`
          });
        }

        // Generate unique filename
        const timestamp = Date.now();
        const extension = path.extname(uploadedFile.name);
        const filename = `${fieldName}_${timestamp}${extension}`;

        // Determine upload path based on file type
        let uploadPath: string;
        let urlPath: string;

        if (fieldName.includes('ad') || fieldName === 'image') {
          // For ads and general images
          uploadPath = path.join(process.cwd(), 'client', 'public', 'assets', 'images', 'ads', filename);
          urlPath = `/assets/images/ads/${filename}`;
        } else if (fieldName.includes('gallery')) {
          // For gallery images
          uploadPath = path.join(process.cwd(), 'client', 'public', 'assets', 'images', 'gallery', filename);
          urlPath = `/assets/images/gallery/${filename}`;
        } else if (fieldName.includes('about')) {
          // For about us images
          uploadPath = path.join(process.cwd(), 'client', 'public', 'assets', 'images', 'about', filename);
          urlPath = `/assets/images/about/${filename}`;
        } else {
          // Default to ads folder
          uploadPath = path.join(process.cwd(), 'client', 'public', 'assets', 'images', 'ads', filename);
          urlPath = `/assets/images/ads/${filename}`;
        }

        // Ensure directory exists
        const dir = path.dirname(uploadPath);
        if (!fs.existsSync(dir)) {
          fs.mkdirSync(dir, { recursive: true });
        }

        // Move file to destination
        await uploadedFile.mv(uploadPath);

        uploadedFiles.push({
          fieldName,
          originalName: uploadedFile.name,
          filename,
          url: urlPath,
          size: uploadedFile.size,
          mimetype: uploadedFile.mimetype
        });
      }

      res.json({
        message: "Files uploaded successfully",
        files: uploadedFiles
      });
    } catch (error) {
      console.error("Error uploading corporate files:", error);
      res.status(500).json({ error: "Failed to upload files" });
    }
  });

  // Corporate User Management Routes
  app.get("/api/corporate/users", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const users = await mysqlStorage.getUsersByRole('corporate');
      res.json(users);
    } catch (error) {
      console.error("Error fetching corporate users:", error);
      res.status(500).json({ error: "Failed to fetch corporate users" });
    }
  });

  app.post("/api/corporate/users", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const { name, mobile, email, username, password } = req.body;

      // Validate required fields
      if (!name || !mobile || !email || !username) {
        return res.status(400).json({ error: "Name, mobile, email, and username are required" });
      }

      // Check if username or email already exists
      const existingUser = await mysqlStorage.getUserByUsername(username);
      if (existingUser) {
        return res.status(400).json({ error: "Username already exists" });
      }

      const existingEmail = await mysqlStorage.getUserByEmail(email);
      if (existingEmail) {
        return res.status(400).json({ error: "Email already exists" });
      }

      // Create corporate user
      const userData = {
        username,
        firstName: name,
        email,
        phone: mobile,
        password: password || '123456', // Default password
        ipAddress: req.ip || '127.0.0.1',
        role: 'corporate'
      };

      const newUser = await mysqlStorage.createCorporateUser(userData);
      res.status(201).json(newUser);
    } catch (error) {
      console.error("Error creating corporate user:", error);
      res.status(500).json({ error: "Failed to create corporate user" });
    }
  });

  app.put("/api/corporate/users/:id", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const userId = parseInt(req.params.id);
      const { name, mobile, email, username } = req.body;

      const updateData = {
        firstName: name,
        phone: mobile,
        email,
        username
      };

      const updatedUser = await mysqlStorage.updateCorporateUser(userId, updateData);
      if (!updatedUser) {
        return res.status(404).json({ error: "User not found" });
      }

      res.json(updatedUser);
    } catch (error) {
      console.error("Error updating corporate user:", error);
      res.status(500).json({ error: "Failed to update corporate user" });
    }
  });

  app.post("/api/corporate/users/:id/reset-password", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const userId = parseInt(req.params.id);
      const newPassword = await mysqlStorage.resetCorporateUserPassword(userId);

      res.json({
        message: "Password reset successfully",
        newPassword: newPassword
      });
    } catch (error) {
      console.error("Error resetting password:", error);
      res.status(500).json({ error: "Failed to reset password" });
    }
  });

  app.delete("/api/corporate/users/:id", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const userId = parseInt(req.params.id);
      const deleted = await mysqlStorage.deleteUser(userId);

      if (!deleted) {
        return res.status(404).json({ error: "User not found" });
      }

      res.json({ message: "User deleted successfully" });
    } catch (error) {
      console.error("Error deleting corporate user:", error);
      res.status(500).json({ error: "Failed to delete corporate user" });
    }
  });

  // Franchise Holder Management Routes
  app.get("/api/corporate/franchise-holders", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const { groupId } = req.query;
      const franchiseHolders = await mysqlStorage.getFranchiseHolders(groupId ? parseInt(groupId as string) : undefined);
      res.json(franchiseHolders);
    } catch (error) {
      console.error("Error fetching franchise holders:", error);
      res.status(500).json({ error: "Failed to fetch franchise holders" });
    }
  });

  app.post("/api/corporate/franchise-holders", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const { userId, country, state, district } = req.body;

      if (!userId) {
        return res.status(400).json({ error: "User ID is required" });
      }

      const franchiseHolderData = {
        userId,
        country: country || null,
        state: state || null,
        district: district || null
      };

      const newFranchiseHolder = await mysqlStorage.createFranchiseHolder(franchiseHolderData);
      res.status(201).json(newFranchiseHolder);
    } catch (error) {
      console.error("Error creating franchise holder:", error);
      res.status(500).json({ error: "Failed to create franchise holder" });
    }
  });

  app.put("/api/corporate/franchise-holders/:id", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const id = parseInt(req.params.id);
      const { country, state, district } = req.body;

      const updateData = {
        country: country || null,
        state: state || null,
        district: district || null
      };

      const updated = await mysqlStorage.updateFranchiseHolder(id, updateData);
      if (!updated) {
        return res.status(404).json({ error: "Franchise holder not found" });
      }

      res.json(updated);
    } catch (error) {
      console.error("Error updating franchise holder:", error);
      res.status(500).json({ error: "Failed to update franchise holder" });
    }
  });

  app.delete("/api/corporate/franchise-holders/:id", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const id = parseInt(req.params.id);
      const deleted = await mysqlStorage.deleteFranchiseHolder(id);

      if (!deleted) {
        return res.status(404).json({ error: "Franchise holder not found" });
      }

      res.json({ message: "Franchise holder deleted successfully" });
    } catch (error) {
      console.error("Error deleting franchise holder:", error);
      res.status(500).json({ error: "Failed to delete franchise holder" });
    }
  });

  // Corporate Ads Management Routes
  app.get("/api/corporate/ads", authenticateJWT, requireRole(['admin', 'corporate']), async (req: any, res) => {
    try {
      const { adType } = req.query;
      const userId = req.userRole === 'corporate' ? req.user.id : undefined;
      const ads = await mysqlStorage.getCorporateAds(userId, adType as string);
      res.json(ads);
    } catch (error) {
      console.error("Error fetching corporate ads:", error);
      res.status(500).json({ error: "Failed to fetch corporate ads" });
    }
  });

  app.post("/api/corporate/ads", authenticateJWT, requireRole(['admin', 'corporate']), async (req: any, res) => {
    try {
      const { adType, adPosition, adTitle, adImage, adUrl, adDescription } = req.body;

      if (!adType || !adTitle) {
        return res.status(400).json({ error: "Ad type and title are required" });
      }

      const adData = {
        userId: req.user.id,
        adType,
        adPosition: adPosition || null,
        adTitle,
        adImage: adImage || null,
        adUrl: adUrl || null,
        adDescription: adDescription || null,
        isActive: 1
      };

      const newAd = await mysqlStorage.createCorporateAd(adData);
      res.status(201).json(newAd);
    } catch (error) {
      console.error("Error creating corporate ad:", error);
      res.status(500).json({ error: "Failed to create corporate ad" });
    }
  });

  app.put("/api/corporate/ads/:id", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const id = parseInt(req.params.id);
      const { adType, adPosition, adTitle, adImage, adUrl, adDescription, isActive } = req.body;

      const updateData = {
        adType,
        adPosition,
        adTitle,
        adImage,
        adUrl,
        adDescription,
        isActive
      };

      // Remove undefined values
      Object.keys(updateData).forEach(key => {
        if (updateData[key] === undefined) {
          delete updateData[key];
        }
      });

      const updated = await mysqlStorage.updateCorporateAd(id, updateData);
      if (!updated) {
        return res.status(404).json({ error: "Ad not found" });
      }

      res.json(updated);
    } catch (error) {
      console.error("Error updating corporate ad:", error);
      res.status(500).json({ error: "Failed to update corporate ad" });
    }
  });

  app.delete("/api/corporate/ads/:id", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const id = parseInt(req.params.id);
      const deleted = await mysqlStorage.deleteCorporateAd(id);

      if (!deleted) {
        return res.status(404).json({ error: "Ad not found" });
      }

      res.json({ message: "Ad deleted successfully" });
    } catch (error) {
      console.error("Error deleting corporate ad:", error);
      res.status(500).json({ error: "Failed to delete corporate ad" });
    }
  });

  // Popup Ads Management Routes
  app.get("/api/corporate/popup-ads", authenticateJWT, requireRole(['admin', 'corporate']), async (req: any, res) => {
    try {
      const userId = req.userRole === 'corporate' ? req.user.id : undefined;
      const popupAds = await mysqlStorage.getPopupAds(userId);
      res.json(popupAds);
    } catch (error) {
      console.error("Error fetching popup ads:", error);
      res.status(500).json({ error: "Failed to fetch popup ads" });
    }
  });

  app.post("/api/corporate/popup-ads", authenticateJWT, requireRole(['admin', 'corporate']), async (req: any, res) => {
    try {
      const { sideAds, popupImage, popupTitle, popupContent } = req.body;

      const popupAdData = {
        userId: req.user.id,
        sideAds: sideAds || null,
        popupImage: popupImage || null,
        popupTitle: popupTitle || null,
        popupContent: popupContent || null,
        isActive: 1
      };

      const newPopupAd = await mysqlStorage.createPopupAd(popupAdData);
      res.status(201).json(newPopupAd);
    } catch (error) {
      console.error("Error creating popup ad:", error);
      res.status(500).json({ error: "Failed to create popup ad" });
    }
  });

  app.put("/api/corporate/popup-ads/:id", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const id = parseInt(req.params.id);
      const { sideAds, popupImage, popupTitle, popupContent, isActive } = req.body;

      const updateData = {
        sideAds,
        popupImage,
        popupTitle,
        popupContent,
        isActive
      };

      // Remove undefined values
      Object.keys(updateData).forEach(key => {
        if (updateData[key] === undefined) {
          delete updateData[key];
        }
      });

      const updated = await mysqlStorage.updatePopupAd(id, updateData);
      if (!updated) {
        return res.status(404).json({ error: "Popup ad not found" });
      }

      res.json(updated);
    } catch (error) {
      console.error("Error updating popup ad:", error);
      res.status(500).json({ error: "Failed to update popup ad" });
    }
  });

  app.delete("/api/corporate/popup-ads/:id", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const id = parseInt(req.params.id);
      const deleted = await mysqlStorage.deletePopupAd(id);

      if (!deleted) {
        return res.status(404).json({ error: "Popup ad not found" });
      }

      res.json({ message: "Popup ad deleted successfully" });
    } catch (error) {
      console.error("Error deleting popup ad:", error);
      res.status(500).json({ error: "Failed to delete popup ad" });
    }
  });

  // Terms and Conditions Management Routes
  app.get("/api/corporate/terms-conditions", authenticateJWT, requireRole(['admin', 'corporate']), async (req: any, res) => {
    try {
      const userId = req.userRole === 'corporate' ? req.user.id : undefined;
      const terms = await mysqlStorage.getTermsConditions(userId);
      res.json(terms);
    } catch (error) {
      console.error("Error fetching terms and conditions:", error);
      res.status(500).json({ error: "Failed to fetch terms and conditions" });
    }
  });

  app.post("/api/corporate/terms-conditions", authenticateJWT, requireRole(['admin', 'corporate']), async (req: any, res) => {
    try {
      const { title, content, version } = req.body;

      if (!title || !content) {
        return res.status(400).json({ error: "Title and content are required" });
      }

      const termsData = {
        userId: req.user.id,
        title,
        content,
        version: version || '1.0',
        isActive: 1
      };

      const newTerms = await mysqlStorage.createTermsConditions(termsData);
      res.status(201).json(newTerms);
    } catch (error) {
      console.error("Error creating terms and conditions:", error);
      res.status(500).json({ error: "Failed to create terms and conditions" });
    }
  });

  app.put("/api/corporate/terms-conditions/:id", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const id = parseInt(req.params.id);
      const { title, content, version, isActive } = req.body;

      const updateData = {
        title,
        content,
        version,
        isActive
      };

      // Remove undefined values
      Object.keys(updateData).forEach(key => {
        if (updateData[key] === undefined) {
          delete updateData[key];
        }
      });

      const updated = await mysqlStorage.updateTermsConditions(id, updateData);
      if (!updated) {
        return res.status(404).json({ error: "Terms and conditions not found" });
      }

      res.json(updated);
    } catch (error) {
      console.error("Error updating terms and conditions:", error);
      res.status(500).json({ error: "Failed to update terms and conditions" });
    }
  });

  app.delete("/api/corporate/terms-conditions/:id", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const id = parseInt(req.params.id);
      const deleted = await mysqlStorage.deleteTermsConditions(id);

      if (!deleted) {
        return res.status(404).json({ error: "Terms and conditions not found" });
      }

      res.json({ message: "Terms and conditions deleted successfully" });
    } catch (error) {
      console.error("Error deleting terms and conditions:", error);
      res.status(500).json({ error: "Failed to delete terms and conditions" });
    }
  });

  // About Us Management Routes
  app.get("/api/corporate/about-us", authenticateJWT, requireRole(['admin', 'corporate']), async (req: any, res) => {
    try {
      const groupId = req.user.groupId || 0;
      const aboutUs = await mysqlStorage.getAboutUs(groupId);
      res.json(aboutUs);
    } catch (error) {
      console.error("Error fetching about us:", error);
      res.status(500).json({ error: "Failed to fetch about us" });
    }
  });

  app.post("/api/corporate/about-us", authenticateJWT, requireRole(['admin', 'corporate']), async (req: any, res) => {
    try {
      const { title, content, image } = req.body;

      if (!title || !content) {
        return res.status(400).json({ error: "Title and content are required" });
      }

      const aboutUsData = {
        groupId: req.user.groupId || 0,
        title,
        content,
        image: image || null,
        isActive: 1
      };

      const newAboutUs = await mysqlStorage.createAboutUs(aboutUsData);
      res.status(201).json(newAboutUs);
    } catch (error) {
      console.error("Error creating about us:", error);
      res.status(500).json({ error: "Failed to create about us" });
    }
  });

  app.put("/api/corporate/about-us/:id", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const id = parseInt(req.params.id);
      const { title, content, image, isActive } = req.body;

      const updateData = {
        title,
        content,
        image,
        isActive
      };

      // Remove undefined values
      Object.keys(updateData).forEach(key => {
        if (updateData[key] === undefined) {
          delete updateData[key];
        }
      });

      const updated = await mysqlStorage.updateAboutUs(id, updateData);
      if (!updated) {
        return res.status(404).json({ error: "About us not found" });
      }

      res.json(updated);
    } catch (error) {
      console.error("Error updating about us:", error);
      res.status(500).json({ error: "Failed to update about us" });
    }
  });

  app.delete("/api/corporate/about-us/:id", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const id = parseInt(req.params.id);
      const deleted = await mysqlStorage.deleteAboutUs(id);

      if (!deleted) {
        return res.status(404).json({ error: "About us not found" });
      }

      res.json({ message: "About us deleted successfully" });
    } catch (error) {
      console.error("Error deleting about us:", error);
      res.status(500).json({ error: "Failed to delete about us" });
    }
  });

  // Gallery Management Routes
  app.get("/api/corporate/galleries", authenticateJWT, requireRole(['admin', 'corporate']), async (req: any, res) => {
    try {
      const groupId = req.user.groupId || 0;
      const galleries = await mysqlStorage.getGalleries(groupId);
      res.json(galleries);
    } catch (error) {
      console.error("Error fetching galleries:", error);
      res.status(500).json({ error: "Failed to fetch galleries" });
    }
  });

  app.post("/api/corporate/galleries", authenticateJWT, requireRole(['admin', 'corporate']), async (req: any, res) => {
    try {
      const { galleryName, description } = req.body;

      if (!galleryName) {
        return res.status(400).json({ error: "Gallery name is required" });
      }

      const galleryData = {
        groupId: req.user.groupId || 0,
        galleryName,
        description: description || null,
        isActive: 1
      };

      const newGallery = await mysqlStorage.createGallery(galleryData);
      res.status(201).json(newGallery);
    } catch (error) {
      console.error("Error creating gallery:", error);
      res.status(500).json({ error: "Failed to create gallery" });
    }
  });

  app.get("/api/corporate/galleries/:id/images", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const galleryId = parseInt(req.params.id);
      const images = await mysqlStorage.getGalleryImages(galleryId);
      res.json(images);
    } catch (error) {
      console.error("Error fetching gallery images:", error);
      res.status(500).json({ error: "Failed to fetch gallery images" });
    }
  });

  app.post("/api/corporate/galleries/:id/images", authenticateJWT, requireRole(['admin', 'corporate']), async (req: any, res) => {
    try {
      const galleryId = parseInt(req.params.id);
      const { images } = req.body;

      if (!images || !Array.isArray(images)) {
        return res.status(400).json({ error: "Images array is required" });
      }

      const imagesData = images.map(img => ({
        ...img,
        groupId: req.user.groupId || 0
      }));

      const addedImages = await mysqlStorage.addGalleryImages(galleryId, imagesData);
      res.status(201).json(addedImages);
    } catch (error) {
      console.error("Error adding gallery images:", error);
      res.status(500).json({ error: "Failed to add gallery images" });
    }
  });

  // Contact Us Management Routes
  app.get("/api/corporate/contact-us", authenticateJWT, requireRole(['admin', 'corporate']), async (req: any, res) => {
    try {
      const groupId = req.user.groupId || 0;
      const contactUs = await mysqlStorage.getContactUs(groupId);
      res.json(contactUs);
    } catch (error) {
      console.error("Error fetching contact us:", error);
      res.status(500).json({ error: "Failed to fetch contact us" });
    }
  });

  app.post("/api/corporate/contact-us", authenticateJWT, requireRole(['admin', 'corporate']), async (req: any, res) => {
    try {
      const { companyName, address, phone, email, website, mapLocation, workingHours } = req.body;

      if (!companyName) {
        return res.status(400).json({ error: "Company name is required" });
      }

      const contactUsData = {
        groupId: req.user.groupId || 0,
        companyName,
        address: address || null,
        phone: phone || null,
        email: email || null,
        website: website || null,
        mapLocation: mapLocation || null,
        workingHours: workingHours || null,
        isActive: 1
      };

      const newContactUs = await mysqlStorage.createContactUs(contactUsData);
      res.status(201).json(newContactUs);
    } catch (error) {
      console.error("Error creating contact us:", error);
      res.status(500).json({ error: "Failed to create contact us" });
    }
  });

  // Social Links Management Routes
  app.get("/api/corporate/social-links", authenticateJWT, requireRole(['admin', 'corporate']), async (req: any, res) => {
    try {
      const groupId = req.user.groupId || 0;
      const socialLinks = await mysqlStorage.getSocialLinks(groupId);
      res.json(socialLinks);
    } catch (error) {
      console.error("Error fetching social links:", error);
      res.status(500).json({ error: "Failed to fetch social links" });
    }
  });

  app.post("/api/corporate/social-links", authenticateJWT, requireRole(['admin', 'corporate']), async (req: any, res) => {
    try {
      const { platform, url, icon } = req.body;

      if (!platform || !url) {
        return res.status(400).json({ error: "Platform and URL are required" });
      }

      const socialLinkData = {
        groupId: req.user.groupId || 0,
        platform,
        url,
        icon: icon || null,
        isActive: 1
      };

      const newSocialLink = await mysqlStorage.createSocialLink(socialLinkData);
      res.status(201).json(newSocialLink);
    } catch (error) {
      console.error("Error creating social link:", error);
      res.status(500).json({ error: "Failed to create social link" });
    }
  });

  // Feedback and Support Routes
  app.get("/api/corporate/feedback", authenticateJWT, requireRole(['admin', 'corporate']), async (req: any, res) => {
    try {
      const userId = req.userRole === 'corporate' ? req.user.id : undefined;
      const feedback = await mysqlStorage.getFeedbacks(userId);
      res.json(feedback);
    } catch (error) {
      console.error("Error fetching feedback:", error);
      res.status(500).json({ error: "Failed to fetch feedback" });
    }
  });

  app.post("/api/corporate/feedback", authenticateJWT, requireRole(['admin', 'corporate']), async (req: any, res) => {
    try {
      const { name, email, feedbackType, subject, message, rating } = req.body;

      if (!name || !email || !feedbackType || !subject || !message) {
        return res.status(400).json({ error: "All fields are required" });
      }

      const feedbackData = {
        userId: req.user.id,
        name,
        email,
        feedbackType,
        subject,
        message,
        rating: rating || null,
        status: 'pending'
      };

      const newFeedback = await mysqlStorage.createFeedback(feedbackData);
      res.status(201).json(newFeedback);
    } catch (error) {
      console.error("Error creating feedback:", error);
      res.status(500).json({ error: "Failed to create feedback" });
    }
  });

  // Application Forms Management Routes
  app.get("/api/corporate/applications/franchise", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const applications = await mysqlStorage.getFranchiseApplications();
      res.json(applications);
    } catch (error) {
      console.error("Error fetching franchise applications:", error);
      res.status(500).json({ error: "Failed to fetch franchise applications" });
    }
  });

  app.get("/api/corporate/applications/job", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const applications = await mysqlStorage.getJobApplications();
      res.json(applications);
    } catch (error) {
      console.error("Error fetching job applications:", error);
      res.status(500).json({ error: "Failed to fetch job applications" });
    }
  });

  app.get("/api/corporate/applications/enquiry", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const enquiries = await mysqlStorage.getEnquiryForms();
      res.json(enquiries);
    } catch (error) {
      console.error("Error fetching enquiry forms:", error);
      res.status(500).json({ error: "Failed to fetch enquiry forms" });
    }
  });

  app.put("/api/corporate/applications/:table/:id/status", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const { table, id } = req.params;
      const { status } = req.body;

      if (!status) {
        return res.status(400).json({ error: "Status is required" });
      }

      const validTables = ['franchise_applications', 'job_applications', 'enquiry_forms'];
      if (!validTables.includes(table)) {
        return res.status(400).json({ error: "Invalid table name" });
      }

      const updated = await mysqlStorage.updateApplicationStatus(table, parseInt(id), status);
      if (!updated) {
        return res.status(404).json({ error: "Application not found" });
      }

      res.json({ message: "Application status updated successfully" });
    } catch (error) {
      console.error("Error updating application status:", error);
      res.status(500).json({ error: "Failed to update application status" });
    }
  });

  // Awards Management Routes (Footer Content)
  app.get("/api/corporate/awards", authenticateJWT, requireRole(['admin', 'corporate']), async (req: any, res) => {
    try {
      const groupId = req.user.groupId || 0;
      const awards = await mysqlStorage.getAwards(groupId);
      res.json(awards);
    } catch (error) {
      console.error("Error fetching awards:", error);
      res.status(500).json({ error: "Failed to fetch awards" });
    }
  });

  app.post("/api/corporate/awards", authenticateJWT, requireRole(['admin', 'corporate']), async (req: any, res) => {
    try {
      const { title, content, image, tagLine } = req.body;

      if (!title || !content) {
        return res.status(400).json({ error: "Title and content are required" });
      }

      const awardData = {
        groupId: req.user.groupId || 0,
        title,
        content,
        image: image || null,
        tagLine: tagLine || null,
        isActive: 1
      };

      const newAward = await mysqlStorage.createAward(awardData);
      res.status(201).json(newAward);
    } catch (error) {
      console.error("Error creating award:", error);
      res.status(500).json({ error: "Failed to create award" });
    }
  });

  app.put("/api/corporate/awards/:id", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const id = parseInt(req.params.id);
      const { title, content, image, tagLine, isActive } = req.body;

      const updateData = {
        title,
        content,
        image,
        tagLine,
        isActive
      };

      // Remove undefined values
      Object.keys(updateData).forEach(key => {
        if (updateData[key] === undefined) {
          delete updateData[key];
        }
      });

      const updated = await mysqlStorage.updateAward(id, updateData);
      if (!updated) {
        return res.status(404).json({ error: "Award not found" });
      }

      res.json(updated);
    } catch (error) {
      console.error("Error updating award:", error);
      res.status(500).json({ error: "Failed to update award" });
    }
  });

  app.delete("/api/corporate/awards/:id", authenticateJWT, requireRole(['admin', 'corporate']), async (req, res) => {
    try {
      const id = parseInt(req.params.id);
      const deleted = await mysqlStorage.deleteAward(id);

      if (!deleted) {
        return res.status(404).json({ error: "Award not found" });
      }

      res.json({ message: "Award deleted successfully" });
    } catch (error) {
      console.error("Error deleting award:", error);
      res.status(500).json({ error: "Failed to delete award" });
    }
  });

  // ===== APP NAVIGATION AND CATEGORIES API ROUTES =====

  // Get all app categories
  app.get("/api/app-categories", async (req, res) => {
    try {
      const categories = await mysqlStorage.getAllAppCategories();
      res.json(categories);
    } catch (error) {
      console.error("Error fetching app categories:", error);
      res.status(500).json({ error: "Failed to fetch app categories" });
    }
  });

  // ===== ADVERTISEMENT API ROUTES =====

  // Get all advertisements (main_ads)
  app.get("/api/advertisements", async (req, res) => {
    try {
      const advertisements = await mysqlStorage.getAllAdvertisements();
      res.json(advertisements);
    } catch (error) {
      console.error("Error fetching advertisements:", error);
      res.status(500).json({ error: "Failed to fetch advertisements" });
    }
  });

  // Get about us data
  app.get("/api/about-us", async (req, res) => {
    try {
      const groupId = parseInt(req.query.groupId as string) || 0;
      const aboutUs = await mysqlStorage.getAboutUsData(groupId);
      res.json(aboutUs);
    } catch (error) {
      console.error("Error fetching about us data:", error);
      res.status(500).json({ error: "Failed to fetch about us data" });
    }
  });

  // Get testimonials data
  app.get("/api/testimonials", async (req, res) => {
    try {
      const testimonials = await mysqlStorage.getTestimonialsData();
      res.json(testimonials);
    } catch (error) {
      console.error("Error fetching testimonials:", error);
      res.status(500).json({ error: "Failed to fetch testimonials" });
    }
  });

  // Get apps by category
  app.get("/api/apps/by-category/:categoryName", async (req, res) => {
    try {
      const categoryName = req.params.categoryName;
      const apps = await mysqlStorage.getAppsByCategory(categoryName);
      res.json(apps);
    } catch (error) {
      console.error("Error fetching apps by category:", error);
      res.status(500).json({ error: "Failed to fetch apps by category" });
    }
  });

  // Get all apps with their categories
  app.get("/api/apps/with-categories", async (req, res) => {
    try {
      const appsWithCategories = await mysqlStorage.getAllAppsWithCategories();
      res.json(appsWithCategories);
    } catch (error) {
      console.error("Error fetching apps with categories:", error);
      res.status(500).json({ error: "Failed to fetch apps with categories" });
    }
  });

  // Get app details by ID
  app.get("/api/apps/:id", async (req, res) => {
    try {
      const appId = parseInt(req.params.id);
      const app = await mysqlStorage.getAppById(appId);

      if (!app) {
        return res.status(404).json({ error: "App not found" });
      }

      res.json(app);
    } catch (error) {
      console.error("Error fetching app details:", error);
      res.status(500).json({ error: "Failed to fetch app details" });
    }
  });

  // Search apps
  app.get("/api/apps/search", async (req, res) => {
    try {
      const query = req.query.q as string;
      if (!query) {
        return res.status(400).json({ error: "Search query is required" });
      }

      const apps = await mysqlStorage.searchApps(query);
      res.json(apps);
    } catch (error) {
      console.error("Error searching apps:", error);
      res.status(500).json({ error: "Failed to search apps" });
    }
  });

  // ===== USER PROFILE API ROUTES =====

  // Get user profile
  app.get("/api/user/profile", authenticateJWT, async (req: any, res) => {
    try {
      const userId = req.user.id;
      const user = await mysqlStorage.getUserById(userId);

      if (!user) {
        return res.status(404).json({ error: "User not found" });
      }

      // Remove sensitive information
      const { password, salt, activationCode, forgottenPasswordCode, rememberCode, ...userProfile } = user;
      res.json(userProfile);
    } catch (error) {
      console.error("Error fetching user profile:", error);
      res.status(500).json({ error: "Internal server error" });
    }
  });

  // ===== AUTHENTICATION API ROUTES =====
  // Authentication endpoints matching PHP functionality

  // Login endpoint
  app.post('/api/auth/login', async (req, res) => {
    try {
      const { identity, password, remember = false } = req.body;

      if (!identity || !password) {
        return res.status(400).json({
          error: 'Identity and password are required',
          success: false
        });
      }

      // Authenticate user
      const user = await mysqlStorage.authenticateUser(identity, password);

      if (!user) {
        return res.status(401).json({
          error: 'Invalid credentials',
          success: false
        });
      }

      if (!user.active) {
        return res.status(401).json({
          error: 'Account is not active',
          success: false
        });
      }

      // Generate JWT token
      const token = jwt.sign(
        {
          userId: user.id,
          email: user.email,
          username: user.username,
          groupId: user.group_id
        },
        JWT_SECRET,
        { expiresIn: remember ? '30d' : '24h' }
      );

      // Update last login
      await mysqlStorage.updateLastLogin(user.id);

      // Set session data
      req.session.userId = user.id;
      req.session.userEmail = user.email;
      req.session.isLoggedIn = true;

      res.json({
        success: true,
        message: 'Login successful',
        token,
        user: {
          id: user.id,
          email: user.email,
          username: user.username,
          firstName: user.first_name,
          lastName: user.last_name,
          groupId: user.group_id,
          groups: user.groups || []
        }
      });

    } catch (error) {
      console.error('Login error:', error);
      res.status(500).json({
        error: 'Internal server error',
        success: false
      });
    }
  });

  // Client login endpoint (group-specific)
  app.post('/api/auth/client-login/:groupName', async (req, res) => {
    try {
      const { identity, password, remember = false } = req.body;
      const { groupName } = req.params;

      if (!identity || !password) {
        return res.status(400).json({
          error: 'Identity and password are required',
          success: false
        });
      }

      // Authenticate user for specific group
      const result = await mysqlStorage.authenticateClientUser(identity, password, groupName);

      if (result === 'success') {
        // Standard successful login
        const user = await mysqlStorage.getUserByIdentity(identity);
        const token = jwt.sign(
          {
            userId: user.id,
            email: user.email,
            username: user.username,
            groupId: user.group_id
          },
          JWT_SECRET,
          { expiresIn: remember ? '30d' : '24h' }
        );

        req.session.userId = user.id;
        req.session.userEmail = user.email;
        req.session.isLoggedIn = true;

        return res.json({
          success: true,
          message: 'Login successful',
          token,
          user: {
            id: user.id,
            email: user.email,
            username: user.username,
            firstName: user.first_name,
            lastName: user.last_name,
            groupId: user.group_id
          }
        });
      } else if (result && typeof result === 'number') {
        // User needs to complete registration
        const userInfo = await mysqlStorage.getUserInfoForRegistration(result);
        return res.json({
          success: false,
          requiresRegistration: true,
          redirectUrl: `/client-form/${userInfo.group_name}/${userInfo.group_id}/${userInfo.id}/${userInfo.userGroup}`,
          userInfo
        });
      } else {
        return res.status(401).json({
          error: 'Invalid credentials',
          success: false
        });
      }

    } catch (error) {
      console.error('Client login error:', error);
      res.status(500).json({
        error: 'Internal server error',
        success: false
      });
    }
  });

  // Register with OTP endpoint
  app.post('/api/auth/register-with-otp', async (req, res) => {
    try {
      const { emailId, group_id } = req.body;

      if (!emailId || !group_id) {
        return res.status(400).json({
          error: 'Email and group ID are required',
          success: false
        });
      }

      // Check if user already exists
      const existingUser = await mysqlStorage.getUserByEmailAndGroup(emailId, group_id);
      if (existingUser) {
        return res.status(409).json({
          error: 'User already exists',
          success: false
        });
      }

      // Generate OTP
      const otp = Math.floor(100000 + Math.random() * 900000); // 6-digit OTP
      const message = `This is your one-time password: ${otp}`;

      // Store or update OTP
      await mysqlStorage.storeRegistrationOTP(emailId, otp);

      // Send OTP email
      const emailSent = await mysqlStorage.sendOTPEmail(emailId, message, 'Register One Time Password');

      if (emailSent) {
        res.json({
          success: true,
          message: 'OTP sent successfully',
          email: emailId
        });
      } else {
        res.status(500).json({
          error: 'Failed to send OTP',
          success: false
        });
      }

    } catch (error) {
      console.error('Register OTP error:', error);
      res.status(500).json({
        error: 'Internal server error',
        success: false
      });
    }
  });

  // Verify registration OTP
  app.post('/api/auth/verify-registration-otp', async (req, res) => {
    try {
      const { emailId, otp } = req.body;

      if (!emailId || !otp) {
        return res.status(400).json({
          error: 'Email and OTP are required',
          success: false
        });
      }

      const otpRecord = await mysqlStorage.verifyRegistrationOTP(emailId, otp);

      if (otpRecord) {
        res.json({
          success: true,
          message: 'OTP verified successfully',
          otpId: otpRecord.id
        });
      } else {
        res.status(400).json({
          error: 'Invalid or expired OTP',
          success: false
        });
      }

    } catch (error) {
      console.error('Verify OTP error:', error);
      res.status(500).json({
        error: 'Internal server error',
        success: false
      });
    }
  });

  // Complete user registration
  app.post('/api/auth/register', async (req, res) => {
    try {
      const {
        firstName,
        lastName,
        email,
        username,
        password,
        phone,
        company,
        groupId,
        otpId
      } = req.body;

      // Validate required fields
      if (!firstName || !lastName || !email || !username || !password) {
        return res.status(400).json({
          error: 'All required fields must be provided',
          success: false
        });
      }

      // Verify OTP was validated
      if (otpId) {
        const otpValid = await mysqlStorage.checkOTPValidation(otpId);
        if (!otpValid) {
          return res.status(400).json({
            error: 'OTP verification required',
            success: false
          });
        }
      }

      // Check if user already exists
      const existingUser = await mysqlStorage.getUserByEmailOrUsername(email, username);
      if (existingUser) {
        return res.status(409).json({
          error: 'User already exists with this email or username',
          success: false
        });
      }

      // Create user
      const userData = {
        first_name: firstName,
        last_name: lastName,
        email,
        username,
        password, // Will be hashed in the storage method
        phone: phone || null,
        company: company || null,
        group_id: groupId || 1,
        active: 1,
        created_on: new Date()
      };

      const userId = await mysqlStorage.createUser(userData);

      if (userId) {
        // Add user to default group
        await mysqlStorage.addUserToGroup(userId, 2); // Default user group

        // Clean up OTP record
        if (otpId) {
          await mysqlStorage.deleteOTPRecord(otpId);
        }

        res.json({
          success: true,
          message: 'User registered successfully',
          userId
        });
      } else {
        res.status(500).json({
          error: 'Failed to create user',
          success: false
        });
      }

    } catch (error) {
      console.error('Registration error:', error);
      res.status(500).json({
        error: 'Internal server error',
        success: false
      });
    }
  });

  // Forgot password - send OTP
  app.post('/api/auth/forgot-password', async (req, res) => {
    try {
      const { identity } = req.body; // Can be email or username

      if (!identity) {
        return res.status(400).json({
          error: 'Email or username is required',
          success: false
        });
      }

      // Find user by email or username
      const user = await mysqlStorage.getUserByIdentity(identity);
      if (!user) {
        return res.status(404).json({
          error: 'User not found',
          success: false
        });
      }

      // Generate OTP
      const otp = Math.floor(1000 + Math.random() * 9000); // 4-digit OTP for password reset
      const message = `This is your one-time password: ${otp}`;

      // Store OTP for password recovery
      await mysqlStorage.storePasswordRecoveryOTP(user.username, otp);

      // Send OTP email
      const emailSent = await mysqlStorage.sendOTPEmail(user.email || user.username, message, 'Forgot Password One Time Password');

      if (emailSent) {
        res.json({
          success: true,
          message: 'OTP sent successfully',
          username: user.username
        });
      } else {
        res.status(500).json({
          error: 'Failed to send OTP',
          success: false
        });
      }

    } catch (error) {
      console.error('Forgot password error:', error);
      res.status(500).json({
        error: 'Internal server error',
        success: false
      });
    }
  });

  // Verify forgot password OTP
  app.post('/api/auth/verify-forgot-password-otp', async (req, res) => {
    try {
      const { username, otp } = req.body;

      if (!username || !otp) {
        return res.status(400).json({
          error: 'Username and OTP are required',
          success: false
        });
      }

      const otpRecord = await mysqlStorage.verifyPasswordRecoveryOTP(username, otp);

      if (otpRecord) {
        res.json({
          success: true,
          message: 'OTP verified successfully',
          recoveryId: otpRecord.id
        });
      } else {
        res.status(400).json({
          error: 'Invalid or expired OTP',
          success: false
        });
      }

    } catch (error) {
      console.error('Verify forgot password OTP error:', error);
      res.status(500).json({
        error: 'Internal server error',
        success: false
      });
    }
  });

  // Reset password
  app.post('/api/auth/reset-password', async (req, res) => {
    try {
      const { username, newPassword, otpCode } = req.body;

      if (!username || !newPassword || !otpCode) {
        return res.status(400).json({
          error: 'Username, new password, and OTP are required',
          success: false
        });
      }

      // Verify OTP one more time
      const otpValid = await mysqlStorage.verifyPasswordRecoveryOTP(username, otpCode);
      if (!otpValid) {
        return res.status(400).json({
          error: 'Invalid or expired OTP',
          success: false
        });
      }

      // Update password
      const user = await mysqlStorage.getUserByUsername(username);
      if (!user) {
        return res.status(404).json({
          error: 'User not found',
          success: false
        });
      }

      const passwordUpdated = await mysqlStorage.updateUserPassword(user.id, newPassword);

      if (passwordUpdated) {
        // Clean up OTP record
        await mysqlStorage.deletePasswordRecoveryOTP(username);

        res.json({
          success: true,
          message: 'Password reset successfully'
        });
      } else {
        res.status(500).json({
          error: 'Failed to reset password',
          success: false
        });
      }

    } catch (error) {
      console.error('Reset password error:', error);
      res.status(500).json({
        error: 'Internal server error',
        success: false
      });
    }
  });

  // Logout endpoint
  app.post('/api/auth/logout', (req, res) => {
    try {
      req.session.destroy((err) => {
        if (err) {
          console.error('Logout error:', err);
          return res.status(500).json({
            error: 'Failed to logout',
            success: false
          });
        }

        res.clearCookie('connect.sid');
        res.json({
          success: true,
          message: 'Logged out successfully'
        });
      });
    } catch (error) {
      console.error('Logout error:', error);
      res.status(500).json({
        error: 'Internal server error',
        success: false
      });
    }
  });

  // ===== USER CRUD OPERATIONS =====
  // User management endpoints

  // Get user profile
  app.get('/api/auth/profile', async (req, res) => {
    try {
      const userId = req.session.userId;

      if (!userId) {
        return res.status(401).json({
          error: 'Not authenticated',
          success: false
        });
      }

      const user = await mysqlStorage.getUserById(userId);

      if (!user) {
        return res.status(404).json({
          error: 'User not found',
          success: false
        });
      }

      // Remove password from response
      delete user.password;

      res.json({
        success: true,
        user
      });

    } catch (error) {
      console.error('Get profile error:', error);
      res.status(500).json({
        error: 'Internal server error',
        success: false
      });
    }
  });

  // Update user profile
  app.put('/api/auth/profile', async (req, res) => {
    try {
      const userId = req.session.userId;

      if (!userId) {
        return res.status(401).json({
          error: 'Not authenticated',
          success: false
        });
      }

      const { firstName, lastName, phone, company } = req.body;

      const updateData = {
        first_name: firstName,
        last_name: lastName,
        phone,
        company
      };

      const updated = await mysqlStorage.updateUser(userId, updateData);

      if (updated) {
        res.json({
          success: true,
          message: 'Profile updated successfully'
        });
      } else {
        res.status(500).json({
          error: 'Failed to update profile',
          success: false
        });
      }

    } catch (error) {
      console.error('Update profile error:', error);
      res.status(500).json({
        error: 'Internal server error',
        success: false
      });
    }
  });

  // Change password
  app.post('/api/auth/change-password', async (req, res) => {
    try {
      const userId = req.session.userId;

      if (!userId) {
        return res.status(401).json({
          error: 'Not authenticated',
          success: false
        });
      }

      const { currentPassword, newPassword } = req.body;

      if (!currentPassword || !newPassword) {
        return res.status(400).json({
          error: 'Current password and new password are required',
          success: false
        });
      }

      // Verify current password
      const user = await mysqlStorage.getUserById(userId);
      if (!user) {
        return res.status(404).json({
          error: 'User not found',
          success: false
        });
      }

      const bcrypt = require('bcrypt');
      const isValidPassword = await bcrypt.compare(currentPassword, user.password);

      if (!isValidPassword) {
        return res.status(400).json({
          error: 'Current password is incorrect',
          success: false
        });
      }

      // Update password
      const passwordUpdated = await mysqlStorage.updateUserPassword(userId, newPassword);

      if (passwordUpdated) {
        res.json({
          success: true,
          message: 'Password changed successfully'
        });
      } else {
        res.status(500).json({
          error: 'Failed to change password',
          success: false
        });
      }

    } catch (error) {
      console.error('Change password error:', error);
      res.status(500).json({
        error: 'Internal server error',
        success: false
      });
    }
  });

  // Get all users (admin only)
  app.get('/api/auth/users', async (req, res) => {
    try {
      const userId = req.session.userId;

      if (!userId) {
        return res.status(401).json({
          error: 'Not authenticated',
          success: false
        });
      }

      // Check if user is admin
      const isAdmin = await mysqlStorage.isUserInGroup(userId, 'admin');
      if (!isAdmin) {
        return res.status(403).json({
          error: 'Access denied',
          success: false
        });
      }

      const users = await mysqlStorage.getAllUsers();

      res.json({
        success: true,
        users
      });

    } catch (error) {
      console.error('Get users error:', error);
      res.status(500).json({
        error: 'Internal server error',
        success: false
      });
    }
  });

  // Create user (admin only)
  app.post('/api/auth/users', async (req, res) => {
    try {
      const userId = req.session.userId;

      if (!userId) {
        return res.status(401).json({
          error: 'Not authenticated',
          success: false
        });
      }

      // Check if user is admin
      const isAdmin = await mysqlStorage.isUserInGroup(userId, 'admin');
      if (!isAdmin) {
        return res.status(403).json({
          error: 'Access denied',
          success: false
        });
      }

      const {
        firstName,
        lastName,
        email,
        username,
        password,
        phone,
        company,
        groupId,
        active = 1
      } = req.body;

      // Validate required fields
      if (!firstName || !lastName || !email || !username || !password) {
        return res.status(400).json({
          error: 'All required fields must be provided',
          success: false
        });
      }

      // Check if user already exists
      const existingUser = await mysqlStorage.getUserByEmailOrUsername(email, username);
      if (existingUser) {
        return res.status(409).json({
          error: 'User already exists with this email or username',
          success: false
        });
      }

      // Create user
      const userData = {
        first_name: firstName,
        last_name: lastName,
        email,
        username,
        password,
        phone: phone || null,
        company: company || null,
        group_id: groupId || 1,
        active,
        created_on: new Date()
      };

      const newUserId = await mysqlStorage.createUser(userData);

      if (newUserId) {
        // Add user to default group
        await mysqlStorage.addUserToGroup(newUserId, 2); // Default user group

        res.json({
          success: true,
          message: 'User created successfully',
          userId: newUserId
        });
      } else {
        res.status(500).json({
          error: 'Failed to create user',
          success: false
        });
      }

    } catch (error) {
      console.error('Create user error:', error);
      res.status(500).json({
        error: 'Internal server error',
        success: false
      });
    }
  });

  // First step registration endpoint (matching PHP first_step_register_submit_popup)
  app.post('/api/auth/first-step-register', async (req, res) => {
    try {
      const { first_name, mobile_number, password } = req.body;

      // Validate required fields
      if (!first_name || !mobile_number || !password) {
        return res.status(400).json({
          error: 'All required fields must be provided',
          success: false
        });
      }

      // Check if user already exists
      const existingUser = await storage.getUserByUsername(mobile_number);
      if (existingUser) {
        return res.json({
          message: 'exits',
          success: false
        });
      }

      // Create user with basic information
      const newUser = {
        username: mobile_number,
        firstName: first_name,
        lastName: '',
        email: '',
        phone: mobile_number,
        password: password, // Will be hashed in storage.createUser
        company: 'Individual',
        role: 'user' as const,
        displayName: first_name,
        groupId: 2, // Default user group
      };

      // Store user in memory (in production, this would be database)
      const createdUser = await storage.createUser(newUser);

      res.json({
        success: true,
        userId: createdUser.id,
        result: createdUser.id,
        message: 'First step registration successful'
      });
    } catch (error) {
      console.error('First step registration error:', error);
      res.status(500).json({
        error: 'Registration failed',
        success: false
      });
    }
  });

  // Update registration with complete profile (matching PHP user_update_register_submit_popup)
  app.post('/api/auth/update-registration', async (req, res) => {
    try {
      const {
        register_user_id,
        register_username,
        register_password,
        display_name,
        alter_number,
        email,
        country_code,
        gender,
        marital,
        from_date,
        from_month,
        from_year,
        country,
        state,
        district,
        nationality,
        education,
        profession,
        education_others,
        work_others
      } = req.body;

      // Validate required fields
      if (!register_user_id || !register_username || !display_name) {
        return res.status(400).json({
          error: 'Required fields missing',
          success: false
        });
      }

      // Get existing user
      const existingUser = await storage.getUserById(register_user_id);
      if (!existingUser) {
        return res.status(404).json({
          error: 'User not found',
          success: false
        });
      }

      // Update user with complete profile
      const updatedUser = {
        ...existingUser,
        email: email || `${register_username}@demo.com`,
        displayName: display_name,
        alterNumber: alter_number,
        isVerified: true,
        // Additional registration details
        countryCode: country_code,
        gender: gender,
        maritalStatus: marital,
        dateOfBirth: from_year && from_month && from_date ? `${from_year}-${from_month}-${from_date}` : '',
        country: country,
        state: state,
        district: district,
        nationality: nationality,
        education: education === 'education_others' ? education_others : education,
        profession: profession === 'work_others' ? work_others : profession,
        updatedAt: new Date().toISOString()
      };

      // Update user in memory storage
      await storage.updateUser(register_user_id, updatedUser);

      // Auto-login user (matching PHP behavior)
      const token = jwt.sign(
        {
          userId: register_user_id,
          username: register_username,
          role: updatedUser.role
        },
        JWT_SECRET,
        { expiresIn: '24h' }
      );

      // Set session
      req.session.user = {
        id: register_user_id,
        username: register_username,
        role: updatedUser.role,
        token: token
      };

      res.json({
        success: true,
        result: 1,
        message: 'Registration completed successfully',
        user: {
          id: updatedUser.id,
          username: updatedUser.username,
          firstName: updatedUser.firstName,
          email: updatedUser.email,
          phone: updatedUser.phone,
          displayName: updatedUser.displayName,
          isVerified: updatedUser.isVerified
        },
        token: token
      });
    } catch (error) {
      console.error('Update registration error:', error);
      res.status(500).json({
        error: 'Registration update failed',
        success: false
      });
    }
  });

  // Simple registration endpoint
  app.post('/api/auth/register-simple', async (req, res) => {
    try {
      const { firstName, lastName, email, username, password, phone, company, role = 'user' } = req.body;

      // Validate required fields
      if (!firstName || !lastName || !email || !username || !password) {
        return res.status(400).json({
          error: 'All required fields must be provided',
          success: false
        });
      }

      // Check if user already exists in memory storage
      const existingUser = await storage.getUserByUsername(username) || await storage.getUserByEmail(email);
      if (existingUser) {
        return res.status(409).json({
          error: 'User already exists with this email or username',
          success: false
        });
      }

      // Create user in memory storage
      const newUser = await storage.createUser({
        username,
        email,
        password, // Will be hashed in the storage method
        firstName,
        lastName,
        phone: phone || '',
        company: company || '',
        role,
        isActive: true
      });

      if (newUser) {
        res.json({
          success: true,
          message: 'User registered successfully',
          userId: newUser.id
        });
      } else {
        res.status(500).json({
          error: 'Failed to create user',
          success: false
        });
      }

    } catch (error) {
      console.error('Registration error:', error);
      res.status(500).json({
        error: 'Internal server error',
        success: false
      });
    }
  });

  // ===== MOBILE HEADER API ROUTES =====

  // Get my groups apps (equivalent to PHP get_my_groups_apps)
  app.post("/api/mobile/get-my-groups-apps", async (req, res) => {
    try {
      const { apps_name = 'My Apps' } = req.body;
      const apps = await mysqlStorage.getMyGroupsApps(apps_name);
      res.json(apps);
    } catch (error) {
      console.error("Error fetching my groups apps:", error);
      res.status(500).json({ error: "Failed to fetch my groups apps" });
    }
  });

  // Get all mygroups apps (equivalent to PHP get_all_mygroups_apps)
  app.get("/api/mobile/get-all-mygroups-apps", async (req, res) => {
    try {
      const apps = await mysqlStorage.getAllMyGroupsApps();
      res.json(apps);
    } catch (error) {
      console.error("Error fetching all mygroups apps:", error);
      res.status(500).json({ error: "Failed to fetch all mygroups apps" });
    }
  });

  // Get header ads (equivalent to PHP get_header_ads)
  app.post("/api/mobile/get-header-ads", async (req, res) => {
    try {
      const { main_app, sub_app } = req.body;
      const ads = await mysqlStorage.getHeaderAds(main_app, sub_app);
      res.json(ads);
    } catch (error) {
      console.error("Error fetching header ads:", error);
      res.status(500).json({ error: "Failed to fetch header ads" });
    }
  });

  // Switch dark mode (equivalent to PHP switch_darkmode)
  app.post("/api/mobile/switch-darkmode", authenticateJWT, async (req: any, res) => {
    try {
      const { switch_mode } = req.body;
      const userId = req.user.id;

      // Store dark mode preference in session or user preferences
      req.session.darkMode = switch_mode;

      // Optionally store in database for persistence
      await mysqlStorage.updateUserPreference(userId, 'dark_mode', switch_mode);

      res.json({ success: true, dark_mode: switch_mode });
    } catch (error) {
      console.error("Error switching dark mode:", error);
      res.status(500).json({ error: "Failed to switch dark mode" });
    }
  });

  // Get location wise total users (equivalent to PHP get_location_wise_data)
  app.get("/api/mobile/get-location-wise-data", authenticateJWT, async (req: any, res) => {
    try {
      const userId = req.user.id;
      const locationData = await mysqlStorage.getLocationWiseUserData(userId);
      res.json(locationData);
    } catch (error) {
      console.error("Error fetching location wise data:", error);
      res.status(500).json({ error: "Failed to fetch location wise data" });
    }
  });

  // Edit profile mobile (equivalent to PHP edit_profile_mobile)
  app.post("/api/mobile/edit-profile", authenticateJWT, async (req: any, res) => {
    try {
      const userId = req.user.id;
      const profileData = await mysqlStorage.getUserProfileForEdit(userId);
      const countryFlags = await mysqlStorage.getCountryFlags();
      const education = await mysqlStorage.getEducationList();
      const profession = await mysqlStorage.getProfessionList();

      res.json({
        profile: profileData,
        country_flag: countryFlags,
        education: education,
        profession: profession
      });
    } catch (error) {
      console.error("Error fetching profile edit data:", error);
      res.status(500).json({ error: "Failed to fetch profile edit data" });
    }
  });

  // Update user profile
  app.put("/api/user/profile", authenticateJWT, async (req: any, res) => {
    try {
      const userId = req.user.id;
      const updateData = req.body;

      // Remove sensitive fields that shouldn't be updated via this endpoint
      const { id, password, salt, activationCode, forgottenPasswordCode, rememberCode, createdOn, lastLogin, ...allowedUpdates } = updateData;

      const updatedUser = await mysqlStorage.updateUser(userId, allowedUpdates);

      if (!updatedUser) {
        return res.status(404).json({ error: "User not found" });
      }

      // Remove sensitive information from response
      const { password: pwd, salt: s, activationCode: ac, forgottenPasswordCode: fpc, rememberCode: rc, ...userProfile } = updatedUser;
      res.json(userProfile);
    } catch (error) {
      console.error("Error updating user profile:", error);
      res.status(500).json({ error: "Internal server error" });
    }
  });

  // Upload profile image
  app.post("/api/user/profile/image", authenticateJWT, upload.single('profileImage'), async (req: any, res) => {
    try {
      const userId = req.user.id;
      const file = req.file;

      if (!file) {
        return res.status(400).json({ error: "No image file provided" });
      }

      // Generate unique filename
      const fileExtension = path.extname(file.originalname);
      const fileName = `profile_${userId}_${Date.now()}${fileExtension}`;
      const filePath = path.join(uploadsDir, fileName);

      // Move file to uploads directory
      await fs.promises.rename(file.path, filePath);

      // Update user profile with new image path
      const profileImg = `/uploads/${fileName}`;
      await mysqlStorage.updateUser(userId, { profileImg });

      res.json({ profileImg });
    } catch (error) {
      console.error("Error uploading profile image:", error);
      res.status(500).json({ error: "Internal server error" });
    }
  });

  const httpServer = createServer(app);

  return httpServer;
}
