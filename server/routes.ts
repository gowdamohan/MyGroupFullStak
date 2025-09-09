import type { Express, Request } from "express";
import { createServer, type Server } from "http";
import { z } from "zod";
import jwt from "jsonwebtoken";
import path from "path";
import fs from "fs";
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
      const user = await mysqlStorage.getUser(decoded.userId);
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

      // Try MySQL storage first with group-based authentication
      const mysqlUser = await mysqlStorage.authenticateUser(username, password);
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
      const user = await storage.getUserByUsername(username);
      if (!user) {
        return res.status(401).json({ error: "Invalid username or password" });
      }

      const isValidPassword = await storage.verifyPassword(password, user.password);
      if (!isValidPassword) {
        return res.status(401).json({ error: "Invalid username or password" });
      }

      req.session.userId = user.id.toString();
      req.session.userRole = 'user'; // Default role for fallback storage

      const { password: _, ...userWithoutPassword } = user;
      res.json({
        user: userWithoutPassword,
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
      const isAdmin = await mysqlStorage.isAdmin(user.id);
      const { password: _, salt: __, ...userWithoutPassword } = user;

      res.json({
        user: userWithoutPassword,
        userId: user.id,
        userRole: req.userRole,
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

        // Create user with combined data using MySQL storage - only using existing columns
        const userData = {
          username: step1Data.username,
          firstName: step1Data.firstName,
          lastName: step1Data.lastName,
          email: step1Data.email,
          phone: step1Data.phone,
          password: step1Data.password, // Will be hashed in mysqlStorage.createUser
          ipAddress: req.ip || '127.0.0.1',
          company: step1Data.company || null,
          displayName: step2Data.displayName || null,
          alterNumber: step2Data.alterNumber || null,
          address: step2Data.address || null,
          identificationCode: step2Data.identificationCode || null,
          active: 1, // Set user as active
          createdOn: Math.floor(Date.now() / 1000), // Unix timestamp
          groupId: 0, // Default group
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

        };

        newUser = await storage.createUser(userData);
      }

      // Generate JWT token
      const token = jwt.sign(
        {
          userId: newUser.id,
          username: newUser.username,
          email: newUser.email,
          role: 'user' // Default role for new registrations
        },
        JWT_SECRET,
        { expiresIn: '24h' }
      );

      // Store user session for backward compatibility
      req.session.userId = newUser.id.toString();
      req.session.userRole = 'user';

      // Return user data (excluding password) with JWT token
      const { password: _, salt: __, ...userWithoutPassword } = newUser;
      res.status(201).json({
        user: {
          ...userWithoutPassword,
          role: 'user' // Default role for new registrations
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
      // Check if demo users already exist in MySQL
      const existingAdmin = await mysqlStorage.getUserByUsername("admin");
      if (existingAdmin) {
        return res.json({ message: "Demo users already exist" });
      }

      // Create demo users with proper schema - only using existing columns
      const demoUsers = [
        {
          username: "admin",
          firstName: "System",
          lastName: "Administrator",
          email: "admin@apphub.com",
          phone: "1234567890",
          password: "password", // Will be hashed in mysqlStorage.createUser
          ipAddress: "127.0.0.1",
          company: "AppHub System",
          active: 1,
          createdOn: Math.floor(Date.now() / 1000),
          groupId: 1, // Admin group
        },
        {
          username: "corporate",
          firstName: "Corporate",
          lastName: "Manager",
          email: "corporate@apphub.com",
          phone: "1234567891",
          password: "password",
          ipAddress: "127.0.0.1",
          company: "Corporate Division",
          active: 1,
          createdOn: Math.floor(Date.now() / 1000),
          groupId: 2, // Corporate group
        },
        {
          username: "head_office",
          firstName: "Head Office",
          lastName: "Executive",
          email: "headoffice@apphub.com",
          phone: "1234567892",
          password: "password",
          ipAddress: "127.0.0.1",
          company: "Head Office",
          active: 1,
          createdOn: Math.floor(Date.now() / 1000),
          groupId: 3, // Head Office group
        },
        {
          username: "regional",
          firstName: "Regional",
          lastName: "Manager",
          email: "regional@apphub.com",
          phone: "1234567893",
          password: "password",
          ipAddress: "127.0.0.1",
          company: "Regional Office",
          active: 1,
          createdOn: Math.floor(Date.now() / 1000),
          groupId: 4, // Regional group
        },
        {
          username: "branch",
          firstName: "Branch",
          lastName: "Manager",
          email: "branch@apphub.com",
          phone: "1234567894",
          password: "password",
          ipAddress: "127.0.0.1",
          company: "Branch Office",
          active: 1,
          createdOn: Math.floor(Date.now() / 1000),
          groupId: 5, // Branch group
        }
      ];

      for (const userData of demoUsers) {
        await mysqlStorage.createUser(userData);
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
  app.get("/api/admin/countries", requireAdmin, async (req, res) => {
    try {
      const countries = await mysqlStorage.getAllCountries();
      res.json(countries);
    } catch (error) {
      console.error("Error fetching countries:", error);
      res.status(500).json({ error: "Failed to fetch countries" });
    }
  });

  app.get("/api/admin/countries/:id", requireAdmin, async (req, res) => {
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

  app.get("/api/admin/countries/by-continent/:continentId", requireAdmin, async (req, res) => {
    try {
      const continentId = parseInt(req.params.continentId);
      const countries = await mysqlStorage.getCountriesByContinent(continentId);
      res.json(countries);
    } catch (error) {
      console.error("Error fetching countries by continent:", error);
      res.status(500).json({ error: "Failed to fetch countries" });
    }
  });

  app.post("/api/admin/countries", requireAdmin, async (req, res) => {
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

  app.put("/api/admin/countries/:id", requireAdmin, async (req, res) => {
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

  app.delete("/api/admin/countries/:id", requireAdmin, async (req, res) => {
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
  app.get("/api/admin/states", requireAdmin, async (req, res) => {
    try {
      const states = await mysqlStorage.getAllStates();
      res.json(states);
    } catch (error) {
      console.error("Error fetching states:", error);
      res.status(500).json({ error: "Failed to fetch states" });
    }
  });

  app.get("/api/admin/states/:id", requireAdmin, async (req, res) => {
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

  app.get("/api/admin/states/by-country/:countryId", requireAdmin, async (req, res) => {
    try {
      const countryId = parseInt(req.params.countryId);
      const states = await mysqlStorage.getStatesByCountry(countryId);
      res.json(states);
    } catch (error) {
      console.error("Error fetching states by country:", error);
      res.status(500).json({ error: "Failed to fetch states" });
    }
  });

  app.post("/api/admin/states", requireAdmin, async (req, res) => {
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

  app.put("/api/admin/states/:id", requireAdmin, async (req, res) => {
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

  app.delete("/api/admin/states/:id", requireAdmin, async (req, res) => {
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
  app.get("/api/admin/districts", requireAdmin, async (req, res) => {
    try {
      const districts = await mysqlStorage.getAllDistricts();
      res.json(districts);
    } catch (error) {
      console.error("Error fetching districts:", error);
      res.status(500).json({ error: "Failed to fetch districts" });
    }
  });

  app.get("/api/admin/districts/:id", requireAdmin, async (req, res) => {
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

  app.get("/api/admin/districts/by-state/:stateId", requireAdmin, async (req, res) => {
    try {
      const stateId = parseInt(req.params.stateId);
      const districts = await mysqlStorage.getDistrictsByState(stateId);
      res.json(districts);
    } catch (error) {
      console.error("Error fetching districts by state:", error);
      res.status(500).json({ error: "Failed to fetch districts" });
    }
  });

  app.get("/api/admin/districts/by-country/:countryId", requireAdmin, async (req, res) => {
    try {
      const countryId = parseInt(req.params.countryId);
      const districts = await mysqlStorage.getDistrictsByCountry(countryId);
      res.json(districts);
    } catch (error) {
      console.error("Error fetching districts by country:", error);
      res.status(500).json({ error: "Failed to fetch districts" });
    }
  });

  app.post("/api/admin/districts", requireAdmin, async (req, res) => {
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

  app.put("/api/admin/districts/:id", requireAdmin, async (req, res) => {
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

  app.delete("/api/admin/districts/:id", requireAdmin, async (req, res) => {
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
  app.get("/api/admin/menu-categories", requireAdmin, async (req, res) => {
    try {
      console.log("🔍 Menu Categories API called");
      const categories = await mysqlStorage.getAllCategories();
      console.log("🔍 Menu Categories fetched:", categories.length, "items");
      res.json(categories);
    } catch (error) {
      console.error("🔍 Error fetching menu categories:", error);
      res.status(500).json({ error: "Failed to fetch menu categories" });
    }
  });

  // Categories Routes (for categories management page)
  app.get("/api/admin/categories", requireAdmin, async (req, res) => {
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

  app.get("/api/admin/categories/:id", requireAdmin, async (req, res) => {
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

  app.post("/api/admin/categories", requireAdmin, async (req, res) => {
    try {
      const categoryData = req.body;
      const newCategory = await mysqlStorage.createCategory(categoryData);
      res.status(201).json(newCategory);
    } catch (error) {
      console.error("Error creating category:", error);
      res.status(500).json({ error: "Failed to create category" });
    }
  });

  app.put("/api/admin/categories/:id", requireAdmin, async (req, res) => {
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

  app.delete("/api/admin/categories/:id", requireAdmin, async (req, res) => {
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
  app.get("/api/admin/languages", requireAdmin, async (req, res) => {
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
  app.get("/api/admin/languages/:id", requireAdmin, async (req, res) => {
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

  app.post("/api/admin/languages", requireAdmin, async (req, res) => {
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
  app.put("/api/admin/languages/:id", requireAdmin, async (req, res) => {
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
  app.delete("/api/admin/languages/:id", requireAdmin, async (req, res) => {
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
  app.get("/api/admin/education", requireAdmin, async (req, res) => {
    try {
      // Mock data for now - replace with actual database queries
      const education = [
        { id: 1, level: 'High School', isActive: true, users: 2450 },
        { id: 2, level: 'Bachelor\'s Degree', isActive: true, users: 4230 },
        { id: 3, level: 'Master\'s Degree', isActive: true, users: 2100 },
        { id: 4, level: 'PhD/Doctorate', isActive: true, users: 890 },
        { id: 5, level: 'Professional Certificate', isActive: true, users: 1560 },
      ];
      res.json(education);
    } catch (error) {
      console.error("Error fetching education levels:", error);
      res.status(500).json({ error: "Failed to fetch education levels" });
    }
  });

  app.post("/api/admin/education", requireAdmin, async (req, res) => {
    try {
      const { level, isActive } = req.body;
      // Mock response - replace with actual database insert
      const newEducation = {
        id: Date.now(),
        level,
        isActive: isActive || true,
        users: 0
      };
      res.status(201).json(newEducation);
    } catch (error) {
      console.error("Error creating education level:", error);
      res.status(500).json({ error: "Failed to create education level" });
    }
  });

  // Profession Management Routes
  app.get("/api/admin/professions", requireAdmin, async (req, res) => {
    try {
      // Mock data for now - replace with actual database queries
      const professions = [
        { id: 1, name: 'Software Engineer', category: 'Technology', isActive: true, users: 1850 },
        { id: 2, name: 'Marketing Manager', category: 'Marketing', isActive: true, users: 920 },
        { id: 3, name: 'Data Scientist', category: 'Technology', isActive: true, users: 650 },
        { id: 4, name: 'Product Manager', category: 'Management', isActive: true, users: 480 },
        { id: 5, name: 'Sales Representative', category: 'Sales', isActive: true, users: 1200 },
      ];
      res.json(professions);
    } catch (error) {
      console.error("Error fetching professions:", error);
      res.status(500).json({ error: "Failed to fetch professions" });
    }
  });

  app.post("/api/admin/professions", requireAdmin, async (req, res) => {
    try {
      const { name, category, isActive } = req.body;
      // Mock response - replace with actual database insert
      const newProfession = {
        id: Date.now(),
        name,
        category,
        isActive: isActive || true,
        users: 0
      };
      res.status(201).json(newProfession);
    } catch (error) {
      console.error("Error creating profession:", error);
      res.status(500).json({ error: "Failed to create profession" });
    }
  });

  // ===== CORPORATE USERS API ROUTES =====

  // Get all corporate users
  app.get("/api/admin/corporate-users", requireAdmin, async (req, res) => {
    try {
      // Mock data for now - replace with actual database queries
      const corporateUsers = [
        {
          id: 1,
          name: 'John Corporate',
          mobile: '+1234567890',
          email: 'john@corporate.com',
          username: 'johncorp',
          created_at: '2024-01-15T10:00:00Z',
          is_active: true
        },
        {
          id: 2,
          name: 'Jane Business',
          mobile: '+1234567891',
          email: 'jane@business.com',
          username: 'janebiz',
          created_at: '2024-02-20T11:30:00Z',
          is_active: true
        }
      ];
      res.json(corporateUsers);
    } catch (error) {
      console.error("Error fetching corporate users:", error);
      res.status(500).json({ error: "Failed to fetch corporate users" });
    }
  });

  // Create corporate user
  app.post("/api/admin/corporate-users", requireAdmin, async (req, res) => {
    try {
      const { name, mobile, email, username, password } = req.body;
      // Mock response - replace with actual database insert
      const newUser = {
        id: Date.now(),
        name,
        mobile,
        email,
        username,
        created_at: new Date().toISOString(),
        is_active: true
      };
      res.status(201).json(newUser);
    } catch (error) {
      console.error("Error creating corporate user:", error);
      res.status(500).json({ error: "Failed to create corporate user" });
    }
  });

  // Update corporate user
  app.put("/api/admin/corporate-users/:id", requireAdmin, async (req, res) => {
    try {
      const userId = parseInt(req.params.id);
      const { name, mobile, email, username, password } = req.body;
      // Mock response - replace with actual database update
      const updatedUser = {
        id: userId,
        name,
        mobile,
        email,
        username,
        created_at: '2024-01-15T10:00:00Z',
        is_active: true
      };
      res.json(updatedUser);
    } catch (error) {
      console.error("Error updating corporate user:", error);
      res.status(500).json({ error: "Failed to update corporate user" });
    }
  });

  // Reset corporate user password
  app.post("/api/admin/corporate-users/:id/reset-password", requireAdmin, async (req, res) => {
    try {
      const userId = parseInt(req.params.id);
      // Mock response - replace with actual password reset logic
      res.json({ message: "Password reset successfully", temporaryPassword: "temp123" });
    } catch (error) {
      console.error("Error resetting password:", error);
      res.status(500).json({ error: "Failed to reset password" });
    }
  });

  // ===== ADMIN SETTINGS API ROUTES =====

  // Check if app account exists
  app.get("/api/admin/app-accounts/check/:id", requireAdmin, async (req, res) => {
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
  app.post("/api/admin/app-accounts/:id/reset-password", requireAdmin, async (req, res) => {
    try {
      const appId = parseInt(req.params.id);
      // Mock response - replace with actual password reset logic
      res.json({ message: "App account password reset to 'mygroup123' successfully" });
    } catch (error) {
      console.error("Error resetting app password:", error);
      res.status(500).json({ error: "Failed to reset app password" });
    }
  });

  // ===== CONTINENTS API ROUTES =====

  // Get all continents
  app.get("/api/admin/continents", requireAdmin, async (req, res) => {
    try {
      // Mock data - replace with actual database queries
      const continents = [
        { id: 1, continent: 'Asia', code: 'AS', created_at: '2024-01-15T10:00:00Z' },
        { id: 2, continent: 'Europe', code: 'EU', created_at: '2024-01-16T10:00:00Z' },
        { id: 3, continent: 'Africa', code: 'AF', created_at: '2024-01-17T10:00:00Z' },
        { id: 4, continent: 'North America', code: 'NA', created_at: '2024-01-18T10:00:00Z' },
        { id: 5, continent: 'South America', code: 'SA', created_at: '2024-01-19T10:00:00Z' },
        { id: 6, continent: 'Australia', code: 'AU', created_at: '2024-01-20T10:00:00Z' },
        { id: 7, continent: 'Antarctica', code: 'AN', created_at: '2024-01-21T10:00:00Z' }
      ];
      res.json(continents);
    } catch (error) {
      console.error("Error fetching continents:", error);
      res.status(500).json({ error: "Failed to fetch continents" });
    }
  });

  // Create continent
  app.post("/api/admin/continents", requireAdmin, async (req, res) => {
    try {
      const { continent, code } = req.body;
      const newContinent = {
        id: Date.now(),
        continent,
        code,
        created_at: new Date().toISOString()
      };
      res.status(201).json(newContinent);
    } catch (error) {
      console.error("Error creating continent:", error);
      res.status(500).json({ error: "Failed to create continent" });
    }
  });

  // Update continent
  app.put("/api/admin/continents/:id", requireAdmin, async (req, res) => {
    try {
      const continentId = parseInt(req.params.id);
      const { continent, code } = req.body;
      const updatedContinent = {
        id: continentId,
        continent,
        code,
        created_at: '2024-01-15T10:00:00Z'
      };
      res.json(updatedContinent);
    } catch (error) {
      console.error("Error updating continent:", error);
      res.status(500).json({ error: "Failed to update continent" });
    }
  });

  // Delete continent
  app.delete("/api/admin/continents/:id", requireAdmin, async (req, res) => {
    try {
      const continentId = parseInt(req.params.id);
      res.json({ message: "Continent deleted successfully" });
    } catch (error) {
      console.error("Error deleting continent:", error);
      res.status(500).json({ error: "Failed to delete continent" });
    }
  });

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

  const httpServer = createServer(app);

  return httpServer;
}
