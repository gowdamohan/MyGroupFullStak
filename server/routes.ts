import type { Express, Request } from "express";
import { createServer, type Server } from "http";
import { z } from "zod";
import path from "path";
import fs from "fs";
import { storage } from "./storage";
import { mysqlStorage } from "./mysql-storage";
import {
  loginSchema,
  adminLoginSchema,
  registrationSchema,
  groupCreateSchema,
  createDetailsSchema,
  changePasswordSchema,
  continentSchema,
  countrySchema,
  stateSchema,
  districtSchema
} from "@shared/schema";

// Extend Express Request type to include session
declare module 'express-session' {
  interface SessionData {
    userId?: string;
    userRole?: string;
  }
}

// Extend Express Request type to include files from express-fileupload
declare module 'express' {
  interface Request {
    files?: any;
  }
}

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

      // Store user session
      req.session.userId = user.id;
      req.session.userRole = 'admin';

      // Return user data (excluding password)
      const { password: _, salt: __, ...userWithoutPassword } = user;
      res.json({
        user: userWithoutPassword,
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
          req.session.userId = mysqlUser.id;
          req.session.userRole = userWithRole.role || 'user';

          const { password: _, salt: __, ...userWithoutPassword } = mysqlUser;
          return res.json({
            user: {
              ...userWithoutPassword,
              role: userWithRole.role,
              firstName: userWithoutPassword.firstName || userWithRole.firstName,
              company: userWithoutPassword.company || userWithRole.company
            },
            message: "Login successful"
          });
        } else {
          // User exists but has no group assignment, default to 'user' role
          req.session.userId = mysqlUser.id;
          req.session.userRole = 'user';

          const { password: _, salt: __, ...userWithoutPassword } = mysqlUser;
          return res.json({
            user: {
              ...userWithoutPassword,
              role: 'user'
            },
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

      req.session.userId = user.id;
      req.session.userRole = user.role;

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

  // Admin middleware
  const requireAdmin = async (req: any, res: any, next: any) => {
    if (!req.session.userId) {
      return res.status(401).json({ error: "Not authenticated" });
    }

    if (req.session.userRole !== 'admin') {
      return res.status(403).json({ error: "Admin access required" });
    }

    next();
  };

  app.get("/api/auth/me", async (req, res) => {
    if (!req.session.userId) {
      return res.status(401).json({ error: "Not authenticated" });
    }

    try {
      // Try to get user from MySQL storage
      const userId = parseInt(req.session.userId);
      if (!isNaN(userId)) {
        const user = await mysqlStorage.getUser(userId);
        if (user) {
          const isAdmin = await mysqlStorage.isAdmin(userId);
          const { password: _, salt: __, ...userWithoutPassword } = user;
          return res.json({
            user: userWithoutPassword,
            userId: req.session.userId,
            userRole: req.session.userRole,
            isAdmin
          });
        }
      }

      // Fallback to session data
      res.json({
        userId: req.session.userId,
        userRole: req.session.userRole,
        isAdmin: req.session.userRole === 'admin'
      });
    } catch (error) {
      console.error("Error getting user info:", error);
      res.json({
        userId: req.session.userId,
        userRole: req.session.userRole,
        isAdmin: req.session.userRole === 'admin'
      });
    }
  });

  // Admin dashboard route
  app.get("/api/admin/dashboard", requireAdmin, async (req, res) => {
    try {
      // Get admin dashboard data
      res.json({
        message: "Welcome to admin dashboard",
        timestamp: new Date().toISOString(),
        adminId: req.session.userId
      });
    } catch (error) {
      console.error("Admin dashboard error:", error);
      res.status(500).json({ error: "Internal server error" });
    }
  });

  // User Registration Routes
  app.post("/api/users/register", async (req, res) => {
    try {
      const step1Data = registrationSchema.parse(req.body.step1);
      const step2Data = req.body.step2 || {};

      // Check if username or email already exists
      const existingUserByUsername = await storage.getUserByUsername(step1Data.username);
      if (existingUserByUsername) {
        return res.status(400).json({ error: "Username already exists" });
      }

      const existingUserByEmail = await storage.getUserByEmail(step1Data.email);
      if (existingUserByEmail) {
        return res.status(400).json({ error: "Email already exists" });
      }

      // Create user with combined data
      const userData = {
        username: step1Data.username,
        firstName: step1Data.firstName,
        lastName: step1Data.lastName,
        email: step1Data.email,
        phone: step1Data.phone,
        password: step1Data.password, // Will be hashed in storage.createUser
        ipAddress: req.ip || '127.0.0.1',
        gender: step2Data.gender || null,
        dateOfBirth: step2Data.dateOfBirth || null,
        country: step2Data.country || null,
        state: step2Data.state || null,
        district: step2Data.district || null,
        education: step2Data.education || null,
        profession: step2Data.profession || null,
        company: step2Data.company || null,
      };

      const newUser = await storage.createUser(userData);
      
      // Return user data (excluding password)
      const { password: _, ...userWithoutPassword } = newUser;
      res.status(201).json({
        user: userWithoutPassword,
        message: "User created successfully"
      });
    } catch (error) {
      if (error instanceof z.ZodError) {
        return res.status(400).json({ error: error.errors[0].message });
      }
      console.error("Registration error:", error);
      res.status(500).json({ error: "Internal server error" });
    }
  });

  // ===== ADMIN USER MANAGEMENT API ROUTES =====

  // Get all users for admin dashboard
  app.get("/api/admin/users", async (req, res) => {
    try {
      // Check admin authentication
      if (!req.session.userId || req.session.userRole !== 'admin') {
        return res.status(401).json({ error: "Admin authentication required" });
      }

      // Get all users from MySQL storage
      const users = await mysqlStorage.getAllUsers();
      res.json(users);
    } catch (error) {
      console.error("Error fetching users:", error);
      res.status(500).json({ error: "Failed to fetch users" });
    }
  });

  // Delete user
  app.delete("/api/admin/users/:id", async (req, res) => {
    try {
      // Check admin authentication
      if (!req.session.userId || req.session.userRole !== 'admin') {
        return res.status(401).json({ error: "Admin authentication required" });
      }

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
  app.get("/api/admin/groups", async (req, res) => {
    try {
      // Check admin authentication
      if (!req.session.userId || req.session.userRole !== 'admin') {
        return res.status(401).json({ error: "Admin authentication required" });
      }

      const groups = await mysqlStorage.getAllGroups();
      res.json(groups);
    } catch (error) {
      console.error("Error fetching groups:", error);
      res.status(500).json({ error: "Failed to fetch groups" });
    }
  });

  app.post("/api/admin/groups", async (req, res) => {
    try {
      // Check admin authentication
      if (!req.session.userId || req.session.userRole !== 'admin') {
        return res.status(401).json({ error: "Admin authentication required" });
      }

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

  app.put("/api/admin/groups/:id", async (req, res) => {
    try {
      // Check admin authentication
      if (!req.session.userId || req.session.userRole !== 'admin') {
        return res.status(401).json({ error: "Admin authentication required" });
      }

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

  app.delete("/api/admin/groups/:id", async (req, res) => {
    try {
      // Check admin authentication
      if (!req.session.userId || req.session.userRole !== 'admin') {
        return res.status(401).json({ error: "Admin authentication required" });
      }

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
  app.post("/api/admin/change-password", async (req, res) => {
    try {
      // Check admin authentication
      if (!req.session.userId || req.session.userRole !== 'admin') {
        return res.status(401).json({ error: "Admin authentication required" });
      }

      const passwordData = changePasswordSchema.parse(req.body);
      const userId = parseInt(req.session.userId);

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
  app.post("/api/admin/upload", async (req, res) => {
    try {
      // Check admin authentication
      if (!req.session.userId || req.session.userRole !== 'admin') {
        return res.status(401).json({ error: "Admin authentication required" });
      }

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
  app.get("/api/admin/continents", requireAdmin, async (req, res) => {
    try {
      const continents = await mysqlStorage.getAllContinents();
      res.json(continents);
    } catch (error) {
      console.error("Error fetching continents:", error);
      res.status(500).json({ error: "Failed to fetch continents" });
    }
  });

  app.get("/api/admin/continents/:id", requireAdmin, async (req, res) => {
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

  app.post("/api/admin/continents", requireAdmin, async (req, res) => {
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

  app.put("/api/admin/continents/:id", requireAdmin, async (req, res) => {
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

  app.delete("/api/admin/continents/:id", requireAdmin, async (req, res) => {
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
      // Mock data for now - replace with actual database queries
      const languages = [
        { id: 1, name: 'English', code: 'en', isActive: true, speakers: '1.5B' },
        { id: 2, name: 'Spanish', code: 'es', isActive: true, speakers: '500M' },
        { id: 3, name: 'Hindi', code: 'hi', isActive: true, speakers: '600M' },
        { id: 4, name: 'Chinese', code: 'zh', isActive: false, speakers: '1.4B' },
        { id: 5, name: 'French', code: 'fr', isActive: true, speakers: '280M' },
      ];
      res.json(languages);
    } catch (error) {
      console.error("Error fetching languages:", error);
      res.status(500).json({ error: "Failed to fetch languages" });
    }
  });

  app.post("/api/admin/languages", requireAdmin, async (req, res) => {
    try {
      const { name, code, isActive, speakers } = req.body;
      // Mock response - replace with actual database insert
      const newLanguage = {
        id: Date.now(),
        name,
        code,
        isActive: isActive || true,
        speakers: speakers || '0'
      };
      res.status(201).json(newLanguage);
    } catch (error) {
      console.error("Error creating language:", error);
      res.status(500).json({ error: "Failed to create language" });
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
