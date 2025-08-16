import type { Express, Request } from "express";
import { createServer, type Server } from "http";
import { z } from "zod";
import { storage } from "./storage";
import { loginSchema, registrationStep1Schema, registrationStep2Schema } from "@shared/schema";

// Extend Express Request type to include session
declare global {
  namespace Express {
    interface Request {
      session: any & {
        userId?: string;
        userRole?: string;
      };
    }
  }
}

export async function registerRoutes(app: Express): Promise<Server> {
  // Test route
  app.get("/api/test", (req, res) => {
    res.json({ message: "API is working!" });
  });

  // Authentication Routes - Now using MySQL backend
  app.post("/api/auth/login", async (req, res) => {
    try {
      const { username, password } = loginSchema.parse(req.body);
      
      // Try MySQL backend first
      try {
        const response = await fetch('http://localhost:3001/api/auth/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ username, password })
        });

        const data = await response.json();
        
        if (response.ok) {
          // Set session data from MySQL backend response
          req.session.userId = data.user.id;
          req.session.userRole = data.user.role;
          
          return res.json(data);
        } else if (response.status === 401) {
          // MySQL backend says invalid credentials, try fallback
          return res.status(401).json(data);
        }
      } catch (mysqlError) {
        console.log("MySQL backend not available, using fallback storage");
      }
      
      // Fallback to in-memory storage if MySQL backend is not available
      const user = await storage.getUserByUsername(username);
      if (!user) {
        return res.status(401).json({ error: "Invalid username or password" });
      }

      // Verify password using bcrypt
      const isValidPassword = await storage.verifyPassword(password, user.password);
      if (!isValidPassword) {
        return res.status(401).json({ error: "Invalid username or password" });
      }

      // Store user session
      req.session.userId = user.id;
      req.session.userRole = user.role;

      // Return user data (excluding password)
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

  app.get("/api/auth/me", (req, res) => {
    if (!req.session.userId) {
      return res.status(401).json({ error: "Not authenticated" });
    }
    
    // Get user info from session
    res.json({
      userId: req.session.userId,
      userRole: req.session.userRole
    });
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
