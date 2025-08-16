import { drizzle } from "drizzle-orm/mysql2";
import mysql from "mysql2/promise";
import { eq } from "drizzle-orm";
import {
  users,
  groups,
  usersGroups,
  groupCreate,
  createDetails,
  type User,
  type InsertUser,
  type GroupCreate,
  type CreateDetails,
  type GroupCreateInput,
  type CreateDetailsInput
} from "@shared/schema";
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

  // Group Management Methods
  async getAllGroups(): Promise<GroupCreate[]> {
    try {
      const result = await db.select().from(groupCreate);
      return result;
    } catch (error) {
      console.error("Error fetching groups:", error);
      throw error;
    }
  }

  async createGroup(groupData: GroupCreateInput): Promise<GroupCreate> {
    try {
      const [result] = await db.insert(groupCreate).values(groupData);
      const newGroup = await db.select().from(groupCreate).where(eq(groupCreate.id, result.insertId)).limit(1);
      return newGroup[0];
    } catch (error) {
      console.error("Error creating group:", error);
      throw error;
    }
  }

  async updateGroup(id: number, groupData: Partial<GroupCreateInput>): Promise<GroupCreate | null> {
    try {
      await db.update(groupCreate).set(groupData).where(eq(groupCreate.id, id));
      const updatedGroup = await db.select().from(groupCreate).where(eq(groupCreate.id, id)).limit(1);
      return updatedGroup[0] || null;
    } catch (error) {
      console.error("Error updating group:", error);
      throw error;
    }
  }

  async deleteGroup(id: number): Promise<boolean> {
    try {
      const [result] = await connection.execute('DELETE FROM group_create WHERE id = ?', [id]);
      return (result as any).affectedRows > 0;
    } catch (error) {
      console.error("Error deleting group:", error);
      throw error;
    }
  }

  // App Create Methods
  async getAllAppsWithDetails(): Promise<any[]> {
    try {
      // Get all groups with their details
      const query = `
        SELECT
          gc.id,
          gc.name,
          gc.apps_name,
          gc.order_by,
          gc.code,
          cd.icon,
          cd.logo,
          cd.name_image,
          cd.background_color,
          cd.banner,
          cd.url
        FROM group_create gc
        LEFT JOIN create_details cd ON gc.id = cd.create_id
      `;

      const [rows] = await connection.execute(query);
      return rows as any[];
    } catch (error) {
      console.error("Error fetching apps with details:", error);
      throw error;
    }
  }

  async getAppWithDetails(id: number): Promise<any> {
    try {
      const query = `
        SELECT
          gc.id,
          gc.name,
          gc.apps_name,
          gc.order_by,
          gc.code,
          cd.icon,
          cd.logo,
          cd.name_image,
          cd.background_color,
          cd.banner,
          cd.url
        FROM group_create gc
        LEFT JOIN create_details cd ON gc.id = cd.create_id
        WHERE gc.id = ?
      `;

      const [rows] = await connection.execute(query, [id]);
      return (rows as any[])[0] || null;
    } catch (error) {
      console.error("Error fetching app with details:", error);
      throw error;
    }
  }

  async createAppDetails(detailsData: CreateDetailsInput): Promise<CreateDetails> {
    try {
      const [result] = await db.insert(createDetails).values(detailsData);
      const newDetails = await db.select().from(createDetails).where(eq(createDetails.id, result.insertId)).limit(1);
      return newDetails[0];
    } catch (error) {
      console.error("Error creating app details:", error);
      throw error;
    }
  }

  // App Account Methods
  async getAllAppAccounts(): Promise<any[]> {
    try {
      const query = `
        SELECT
          u.id,
          u.username,
          u.email,
          u.first_name,
          u.last_name,
          u.created_on,
          u.active,
          gc.name as app_name,
          gc.apps_name,
          g.name as group_name
        FROM users u
        LEFT JOIN users_groups ug ON u.id = ug.user_id
        LEFT JOIN \`groups\` g ON ug.group_id = g.id
        LEFT JOIN group_create gc ON u.group_id = gc.id
        WHERE g.name = 'client'
        ORDER BY u.created_on DESC
      `;

      const [rows] = await connection.execute(query);
      return rows as any[];
    } catch (error) {
      console.error("Error fetching app accounts:", error);
      throw error;
    }
  }

  async createAppAccount(accountData: {
    username: string;
    password: string;
    appId: number;
    email: string;
    ipAddress: string;
  }): Promise<any> {
    try {
      // Hash password
      const saltRounds = 12;
      const hashedPassword = await bcrypt.hash(accountData.password, saltRounds);

      const userData = {
        ipAddress: accountData.ipAddress,
        username: accountData.username,
        password: hashedPassword,
        email: accountData.email,
        createdOn: Math.floor(Date.now() / 1000),
        active: 1,
        groupId: accountData.appId,
      };

      const [result] = await db.insert(users).values(userData);
      const userId = result.insertId;

      // Get or create 'client' group
      let clientGroupId;
      const [clientGroups] = await connection.execute('SELECT id FROM `groups` WHERE name = ?', ['client']);

      if ((clientGroups as any[]).length === 0) {
        // Create client group if it doesn't exist
        const [groupResult] = await connection.execute('INSERT INTO `groups` (name) VALUES (?)', ['client']);
        clientGroupId = (groupResult as any).insertId;
      } else {
        clientGroupId = (clientGroups as any[])[0].id;
      }
      console.log('Client group ID:', clientGroupId);

      // Associate user with client group
      await connection.execute(
        'INSERT INTO users_groups (user_id, group_id) VALUES (?, ?)',
        [userId, clientGroupId]
      );

      // Get the created user with app info
      const query = `
        SELECT
          u.id,
          u.username,
          u.email,
          u.created_on,
          u.active,
          gc.name as app_name,
          gc.apps_name,
          g.name as group_name
        FROM users u
        LEFT JOIN group_create gc ON u.group_id = gc.id
        LEFT JOIN users_groups ug ON u.id = ug.user_id
        LEFT JOIN \`groups\` g ON ug.group_id = g.id
        WHERE u.id = ? AND g.name = 'client'
      `;

      const [rows] = await connection.execute(query, [userId]);
      return (rows as any[])[0];
    } catch (error) {
      console.error("Error creating app account:", error);
      throw error;
    }
  }

  async deleteAppAccount(id: number): Promise<boolean> {
    try {
      const [result] = await connection.execute('DELETE FROM users WHERE id = ?', [id]);
      return (result as any).affectedRows > 0;
    } catch (error) {
      console.error("Error deleting app account:", error);
      throw error;
    }
  }

  // Password Management Methods

  async updateUserPassword(userId: number, newPassword: string): Promise<boolean> {
    try {
      // Hash the new password
      const saltRounds = 12;
      const hashedPassword = await bcrypt.hash(newPassword, saltRounds);

      const [result] = await connection.execute(
        'UPDATE users SET password = ? WHERE id = ?',
        [hashedPassword, userId]
      );

      return (result as any).affectedRows > 0;
    } catch (error) {
      console.error("Error updating user password:", error);
      throw error;
    }
  }
}

export const mysqlStorage = new MySQLStorage();
