import type { Express, Request } from "express";
import { createServer, type Server } from "http";
import { z } from "zod";
import { storage } from "./storage";
import { mysqlStorage } from "./mysql-storage";
import { loginSchema, adminLoginSchema, registrationSchema } from "@shared/schema";

// Extend Express Request type to include session
declare module 'express-session' {
  interface SessionData {
    userId?: string;
    userRole?: string;
  }
}

export async function registerRoutes(app: Express): Promise<Server> {
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
      req.session.userId = user.id.toString();
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

  // Regular user authentication (fallback to in-memory storage)
  app.post("/api/auth/login", async (req, res) => {
    try {
      const { username, password } = loginSchema.parse(req.body);

      // Try MySQL storage first
      const mysqlUser = await mysqlStorage.authenticateUser(username, password);
      if (mysqlUser) {
        req.session.userId = mysqlUser.id.toString();
        req.session.userRole = 'user';

        const { password: _, salt: __, ...userWithoutPassword } = mysqlUser;
        return res.json({
          user: userWithoutPassword,
          message: "Login successful"
        });
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
      const step1Data = registrationStep1Schema.parse(req.body.step1);
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
        role: step1Data.role,
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
          username: "regional",
          firstName: "Regional",
          lastName: "Manager",
          email: "regional@apphub.com",
          phone: "1234567892",
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
          phone: "1234567893",
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

  const httpServer = createServer(app);

  return httpServer;
}
