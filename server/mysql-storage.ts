import { drizzle } from "drizzle-orm/mysql2";
import mysql from "mysql2/promise";
import { eq } from "drizzle-orm";
import { users, groups, type User, type InsertUser } from "@shared/schema";
import bcrypt from "bcryptjs";
import { dbConfig } from "./mysql-connection.js";

// Create MySQL connection pool
const connection = mysql.createPool(dbConfig);
const db = drizzle(connection);

export interface IMySQLStorage {
  getUser(id: number): Promise<User | undefined>;
  getUserByUsername(username: string): Promise<User | undefined>;
  getUserByEmail(email: string): Promise<User | undefined>;
  createUser(user: Partial<InsertUser>): Promise<User>;
  verifyPassword(password: string, hashedPassword: string): Promise<boolean>;
  authenticateUser(username: string, password: string): Promise<User | null>;
  isAdmin(userId: number): Promise<boolean>;
}

export class MySQLStorage implements IMySQLStorage {
  async getUser(id: number): Promise<User | undefined> {
    try {
      const result = await db.select().from(users).where(eq(users.id, id)).limit(1);
      return result[0];
    } catch (error) {
      console.error("Error getting user by ID:", error);
      return undefined;
    }
  }

  async getUserByUsername(username: string): Promise<User | undefined> {
    try {
      const result = await db.select().from(users).where(eq(users.username, username)).limit(1);
      return result[0];
    } catch (error) {
      console.error("Error getting user by username:", error);
      return undefined;
    }
  }

  async getUserByEmail(email: string): Promise<User | undefined> {
    try {
      const result = await db.select().from(users).where(eq(users.email, email)).limit(1);
      return result[0];
    } catch (error) {
      console.error("Error getting user by email:", error);
      return undefined;
    }
  }

  async createUser(userData: Partial<InsertUser>): Promise<User> {
    try {
      // Validate required fields
      if (!userData.username || !userData.email || !userData.password) {
        throw new Error("Username, email, and password are required");
      }

      // Hash password before storing
      const saltRounds = 12;
      const hashedPassword = await bcrypt.hash(userData.password, saltRounds);

      const newUser = {
        ipAddress: '127.0.0.1',
        username: userData.username,
        password: hashedPassword,
        email: userData.email,
        firstName: userData.firstName || null,
        lastName: userData.lastName || null,
        phone: userData.phone || null,
        company: userData.company || null,
        createdOn: Math.floor(Date.now() / 1000), // Unix timestamp
        active: 1, // Active by default
        groupId: 1, // Default group
      };

      const result = await db.insert(users).values(newUser);
      const insertId = result[0].insertId;

      // Return the created user
      const createdUser = await this.getUser(Number(insertId));
      if (!createdUser) {
        throw new Error("Failed to retrieve created user");
      }

      return createdUser;
    } catch (error) {
      console.error("Error creating user:", error);
      throw error;
    }
  }

  async verifyPassword(password: string, hashedPassword: string): Promise<boolean> {
    try {
      return await bcrypt.compare(password, hashedPassword);
    } catch (error) {
      console.error("Error verifying password:", error);
      return false;
    }
  }

  async authenticateUser(username: string, password: string): Promise<User | null> {
    try {
      const user = await this.getUserByUsername(username);
      if (!user) {
        return null;
      }

      const isValidPassword = await this.verifyPassword(password, user.password);
      if (!isValidPassword) {
        return null;
      }

      // Update last login timestamp
      await db.update(users)
        .set({ lastLogin: Math.floor(Date.now() / 1000) })
        .where(eq(users.id, user.id));

      return user;
    } catch (error) {
      console.error("Error authenticating user:", error);
      return null;
    }
  }

  async isAdmin(userId: number): Promise<boolean> {
    try {
      const user = await this.getUser(userId);
      if (!user) {
        return false;
      }

      // Check if user is in admin group (assuming group ID 1 is admin)
      // You can modify this logic based on your specific admin identification
      return user.groupId === 1 || user.username === 'admin';
    } catch (error) {
      console.error("Error checking admin status:", error);
      return false;
    }
  }

  async testConnection(): Promise<boolean> {
    try {
      const result = await db.select().from(users).limit(1);
      console.log("✅ MySQL storage connection test successful");
      return true;
    } catch (error) {
      console.error("❌ MySQL storage connection test failed:", error);
      return false;
    }
  }
}

export const mysqlStorage = new MySQLStorage();
