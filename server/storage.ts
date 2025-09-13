import { type User, type InsertUser } from "@shared/schema";
import { randomUUID } from "crypto";
import bcrypt from "bcryptjs";

// modify the interface with any CRUD methods
// you might need

export interface IStorage {
  getUser(id: string): Promise<User | undefined>;
  getUserByUsername(username: string): Promise<User | undefined>;
  getUserByEmail(email: string): Promise<User | undefined>;
  createUser(user: InsertUser): Promise<User>;
  verifyPassword(password: string, hashedPassword: string): Promise<boolean>;
  authenticateUser(username: string, password: string): Promise<User | null>;
}

export class MemStorage implements IStorage {
  private users: Map<string, User>;

  constructor() {
    this.users = new Map();
    this.initializeDemoUsers();
  }

  private async initializeDemoUsers() {
    console.log("🔄 Initializing demo users in memory storage...");

    const demoUsers = [
      {
        username: 'admin',
        firstName: 'System',
        lastName: 'Administrator',
        email: 'admin@apphub.com',
        phone: '+1-555-0001',
        password: 'password',
        company: 'AppHub System'
      },
      {
        username: 'corporate',
        firstName: 'Corporate',
        lastName: 'Manager',
        email: 'corporate@apphub.com',
        phone: '+1-555-0002',
        password: 'password',
        company: 'AppHub Corporate'
      },
      {
        username: 'head_office',
        firstName: 'Head Office',
        lastName: 'Manager',
        email: 'headoffice@apphub.com',
        phone: '+1-555-0003',
        password: 'password',
        company: 'AppHub Head Office'
      },
      {
        username: 'regional',
        firstName: 'Regional',
        lastName: 'Manager',
        email: 'regional@apphub.com',
        phone: '+1-555-0004',
        password: 'password',
        company: 'AppHub Regional'
      },
      {
        username: 'branch',
        firstName: 'Branch',
        lastName: 'Manager',
        email: 'branch@apphub.com',
        phone: '+1-555-0005',
        password: 'password',
        company: 'AppHub Branch'
      }
    ];

    for (const userData of demoUsers) {
      try {
        await this.createUser(userData);
        console.log(`✅ Created demo user in memory: ${userData.username}`);
      } catch (error) {
        console.error(`❌ Failed to create demo user ${userData.username}:`, error);
      }
    }
  }

  async getUser(id: string): Promise<User | undefined> {
    return this.users.get(id);
  }

  async getUserByUsername(username: string): Promise<User | undefined> {
    return Array.from(this.users.values()).find(
      (user) => user.username === username,
    );
  }

  async getUserByEmail(email: string): Promise<User | undefined> {
    return Array.from(this.users.values()).find(
      (user) => user.email === email,
    );
  }

  async createUser(insertUser: InsertUser): Promise<User> {
    const id = randomUUID();
    
    // Hash password before storing
    const saltRounds = 12;
    const hashedPassword = await bcrypt.hash(insertUser.password, saltRounds);
    
    const user: User = {
      id,
      username: insertUser.username,
      firstName: insertUser.firstName,
      lastName: insertUser.lastName,
      email: insertUser.email,
      phone: insertUser.phone,
      password: hashedPassword,
      // Additional fields will be added after database migration
      company: insertUser.company || null,
      isVerified: false,
      createdAt: new Date()
    };
    this.users.set(id, user);
    return user;
  }

  async verifyPassword(password: string, hashedPassword: string): Promise<boolean> {
    return await bcrypt.compare(password, hashedPassword);
  }

  async authenticateUser(username: string, password: string): Promise<User | null> {
    const user = await this.getUserByUsername(username);
    if (!user) {
      return null;
    }

    const isValidPassword = await this.verifyPassword(password, user.password);
    if (!isValidPassword) {
      return null;
    }

    return user;
  }
}

export const storage = new MemStorage();
