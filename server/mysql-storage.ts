import { drizzle } from "drizzle-orm/mysql2";
import mysql from "mysql2/promise";
import { eq } from "drizzle-orm";
import {
  users,
  groups,
  usersGroups,
  groupCreate,
  createDetails,
  continentTbl,
  countryTbl,
  stateTbl,
  districtTbl,
  languageTbl,
  franchiseHolder,
  franchiseStaff,
  franchiseStaffDocuments,
  corporateAds,
  popupAds,
  termsConditions,
  aboutUs,
  awards,
  newsroom,
  events,
  careers,
  clients,
  milestones,
  testimonials,
  gallery,
  galleryImages,
  contactUs,
  socialLinks,
  privacyPolicy,
  franchiseApplications,
  jobApplications,
  enquiryForms,
  feedbackSuggestions,
  chatMessages,
  copyRights,
  type User,
  type InsertUser,
  type GroupCreate,
  type CreateDetails,
  type GroupCreateInput,
  type CreateDetailsInput,
  type Continent,
  type InsertContinent,
  type Country,
  type InsertCountry,
  type State,
  type InsertState,
  type District,
  type InsertDistrict,
  type Language,
  type InsertLanguage,
  type ContinentInput,
  type CountryInput,
  type StateInput,
  type DistrictInput,
  type LanguageInput,
  type FranchiseHolder,
  type InsertFranchiseHolder,
  type FranchiseStaff,
  type InsertFranchiseStaff,
  type CorporateAd,
  type InsertCorporateAd,
  type PopupAd,
  type InsertPopupAd,
  type TermsCondition,
  type InsertTermsCondition,
  type AboutUs,
  type InsertAboutUs,
  type Award,
  type InsertAward,
  type Gallery,
  type InsertGallery,
  type ContactUs,
  type InsertContactUs,
  type SocialLink,
  type InsertSocialLink,
  type FeedbackSuggestion,
  type InsertFeedbackSuggestion
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
  getUserWithRole(userId: number): Promise<any>;
  isAdmin(userId: number): Promise<boolean>;
  getAllUsers(): Promise<any[]>;
  getUsersByRole(role: string): Promise<any[]>;
  createCorporateUser(userData: any): Promise<any>;
  updateCorporateUser(userId: number, userData: any): Promise<any>;
  resetCorporateUserPassword(userId: number): Promise<string>;
  deleteUser(userId: number): Promise<boolean>;
  executeQuery(query: string): Promise<any>;
  createDemoUsers(): Promise<void>;
  seedGroupsAndUsers(): Promise<void>;

  // Corporate Feature Methods
  // Franchise Holder Management
  createFranchiseHolder(data: InsertFranchiseHolder): Promise<FranchiseHolder>;
  getFranchiseHolders(groupId?: number): Promise<any[]>;
  getFranchiseHoldersByCountry(countryId: number, groupId?: number): Promise<any[]>;
  updateFranchiseHolder(id: number, data: Partial<InsertFranchiseHolder>): Promise<FranchiseHolder | null>;
  deleteFranchiseHolder(id: number): Promise<boolean>;

  // Corporate Ads Management
  createCorporateAd(data: InsertCorporateAd): Promise<CorporateAd>;
  getCorporateAds(userId?: number, adType?: string): Promise<CorporateAd[]>;
  updateCorporateAd(id: number, data: Partial<InsertCorporateAd>): Promise<CorporateAd | null>;
  deleteCorporateAd(id: number): Promise<boolean>;

  // Popup Ads Management
  createPopupAd(data: InsertPopupAd): Promise<PopupAd>;
  getPopupAds(userId?: number): Promise<PopupAd[]>;
  updatePopupAd(id: number, data: Partial<InsertPopupAd>): Promise<PopupAd | null>;
  deletePopupAd(id: number): Promise<boolean>;

  // Terms and Conditions Management
  createTermsConditions(data: InsertTermsCondition): Promise<TermsCondition>;
  getTermsConditions(userId?: number): Promise<TermsCondition[]>;
  updateTermsConditions(id: number, data: Partial<InsertTermsCondition>): Promise<TermsCondition | null>;
  deleteTermsConditions(id: number): Promise<boolean>;

  // Footer Content Management
  createAboutUs(data: InsertAboutUs): Promise<AboutUs>;
  getAboutUs(groupId?: number): Promise<AboutUs[]>;
  updateAboutUs(id: number, data: Partial<InsertAboutUs>): Promise<AboutUs | null>;
  deleteAboutUs(id: number): Promise<boolean>;

  createAward(data: InsertAward): Promise<Award>;
  getAwards(groupId?: number): Promise<Award[]>;
  updateAward(id: number, data: Partial<InsertAward>): Promise<Award | null>;
  deleteAward(id: number): Promise<boolean>;

  // Gallery Management
  createGallery(data: InsertGallery): Promise<Gallery>;
  getGalleries(groupId?: number): Promise<Gallery[]>;
  updateGallery(id: number, data: Partial<InsertGallery>): Promise<Gallery | null>;
  deleteGallery(id: number): Promise<boolean>;
  addGalleryImages(galleryId: number, images: any[]): Promise<any[]>;
  getGalleryImages(galleryId: number): Promise<any[]>;
  deleteGalleryImage(id: number): Promise<boolean>;

  // Contact Us Management
  createContactUs(data: InsertContactUs): Promise<ContactUs>;
  getContactUs(groupId?: number): Promise<ContactUs[]>;
  updateContactUs(id: number, data: Partial<InsertContactUs>): Promise<ContactUs | null>;
  deleteContactUs(id: number): Promise<boolean>;

  // Social Links Management
  createSocialLink(data: InsertSocialLink): Promise<SocialLink>;
  getSocialLinks(groupId?: number): Promise<SocialLink[]>;
  updateSocialLink(id: number, data: Partial<InsertSocialLink>): Promise<SocialLink | null>;
  deleteSocialLink(id: number): Promise<boolean>;

  // Support System
  createFeedback(data: InsertFeedbackSuggestion): Promise<FeedbackSuggestion>;
  getFeedbacks(userId?: number): Promise<FeedbackSuggestion[]>;
  updateFeedback(id: number, data: Partial<InsertFeedbackSuggestion>): Promise<FeedbackSuggestion | null>;
  deleteFeedback(id: number): Promise<boolean>;

  // Application Forms
  getFranchiseApplications(): Promise<any[]>;
  getJobApplications(): Promise<any[]>;
  getEnquiryForms(): Promise<any[]>;
  updateApplicationStatus(table: string, id: number, status: string): Promise<boolean>;
}

export class MySQLStorage implements IMySQLStorage {
  async getUser(id: number): Promise<User | undefined> {
    try {
      // Select only columns that exist in the actual database
      const result = await db.select({
        id: users.id,
        ipAddress: users.ipAddress,
        username: users.username,
        password: users.password,
        salt: users.salt,
        email: users.email,
        activationCode: users.activationCode,
        forgottenPasswordCode: users.forgottenPasswordCode,
        forgottenPasswordTime: users.forgottenPasswordTime,
        rememberCode: users.rememberCode,
        createdOn: users.createdOn,
        lastLogin: users.lastLogin,
        active: users.active,
        firstName: users.firstName,
        lastName: users.lastName,
        company: users.company,
        phone: users.phone,
        profileImg: users.profileImg,
        displayName: users.displayName,
        alterNumber: users.alterNumber,
        groupId: users.groupId,
        address: users.address,
        identificationCode: users.identificationCode,
      }).from(users).where(eq(users.id, id)).limit(1);
      const user = result[0];
      if (user) {
        // Add missing properties with default values
        return {
          ...user,
          role: 'user',
          gender: null,
          dateOfBirth: null,
          country: null,
          state: null,
          district: null,
          education: null,
          profession: null,
        } as User;
      }
      return user;
    } catch (error) {
      console.error("Error getting user by ID:", error);
      return undefined;
    }
  }

  async getUserByUsername(username: string): Promise<User | undefined> {
    try {
      // Select only columns that exist in the actual database
      const result = await db.select({
        id: users.id,
        ipAddress: users.ipAddress,
        username: users.username,
        password: users.password,
        salt: users.salt,
        email: users.email,
        activationCode: users.activationCode,
        forgottenPasswordCode: users.forgottenPasswordCode,
        forgottenPasswordTime: users.forgottenPasswordTime,
        rememberCode: users.rememberCode,
        createdOn: users.createdOn,
        lastLogin: users.lastLogin,
        active: users.active,
        firstName: users.firstName,
        lastName: users.lastName,
        company: users.company,
        phone: users.phone,
        profileImg: users.profileImg,
        displayName: users.displayName,
        alterNumber: users.alterNumber,
        groupId: users.groupId,
        address: users.address,
        identificationCode: users.identificationCode,
      }).from(users).where(eq(users.username, username)).limit(1);

      const user = result[0];
      if (user) {
        // Add missing properties with default values
        return {
          ...user,
          role: 'user',
          gender: null,
          dateOfBirth: null,
          country: null,
          state: null,
          district: null,
          education: null,
          profession: null,
        } as User;
      }
      return user;
    } catch (error) {
      console.error("Error getting user by username:", error);
      return undefined;
    }
  }

  async getUserByEmail(email: string): Promise<User | undefined> {
    try {
      // Select only columns that exist in the actual database
      const result = await db.select({
        id: users.id,
        ipAddress: users.ipAddress,
        username: users.username,
        password: users.password,
        salt: users.salt,
        email: users.email,
        activationCode: users.activationCode,
        forgottenPasswordCode: users.forgottenPasswordCode,
        forgottenPasswordTime: users.forgottenPasswordTime,
        rememberCode: users.rememberCode,
        createdOn: users.createdOn,
        lastLogin: users.lastLogin,
        active: users.active,
        firstName: users.firstName,
        lastName: users.lastName,
        company: users.company,
        phone: users.phone,
        profileImg: users.profileImg,
        displayName: users.displayName,
        alterNumber: users.alterNumber,
        groupId: users.groupId,
        address: users.address,
        identificationCode: users.identificationCode,
      }).from(users).where(eq(users.email, email)).limit(1);

      const user = result[0];
      if (user) {
        // Add missing properties with default values
        return {
          ...user,
          role: 'user',
          gender: null,
          dateOfBirth: null,
          country: null,
          state: null,
          district: null,
          education: null,
          profession: null,
        } as User;
      }
      return user;
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
        ipAddress: userData.ipAddress || '127.0.0.1',
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

  async getUserWithRole(userId: number): Promise<any> {
    try {
      // Use the SQL query provided to get user with role based on group membership
      const query = `
        SELECT u.username, u.first_name, u.company, g.name as role
        FROM users u
        JOIN users_groups ug ON u.id = ug.user_id
        JOIN \`groups\` g ON g.id = ug.group_id
        WHERE u.active = 1 AND u.id = ?
      `;

      const [rows] = await connection.execute(query, [userId]);
      return (rows as any[])[0] || null;
    } catch (error) {
      console.error("Error getting user with role:", error);
      return null;
    }
  }

  async isAdmin(userId: number): Promise<boolean> {
    try {
      const userWithRole = await this.getUserWithRole(userId);
      if (!userWithRole) {
        // Fallback to checking username for admin
        const user = await this.getUser(userId);
        return user?.username === 'admin';
      }

      // Check if user is in admin group
      return userWithRole.role === 'admin';
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

  async getAllUsers(): Promise<any[]> {
    try {
      const query = `
        SELECT
          u.id,
          u.username,
          u.email,
          u.first_name,
          u.last_name,
          u.phone,
          u.company,
          u.active as is_active,
          u.created_on,
          u.last_login,
          g.name as role_name
        FROM users u
        LEFT JOIN users_groups ug ON u.id = ug.user_id
        LEFT JOIN \`groups\` g ON ug.group_id = g.id
        WHERE u.active = 1
        ORDER BY u.created_on DESC
      `;

      const [rows] = await connection.execute(query);
      return rows as any[];
    } catch (error) {
      console.error("Error fetching all users:", error);
      throw error;
    }
  }

  async getUsersByRole(role: string): Promise<any[]> {
    try {
      const query = `
        SELECT
          u.id,
          u.username,
          u.email,
          u.first_name as name,
          u.last_name,
          u.phone as mobile,
          u.company,
          u.active as is_active,
          u.created_on,
          u.last_login,
          g.name as role_name
        FROM users u
        LEFT JOIN users_groups ug ON u.id = ug.user_id
        LEFT JOIN \`groups\` g ON ug.group_id = g.id
        WHERE u.active = 1 AND g.name = ?
        ORDER BY u.created_on DESC
      `;

      const [rows] = await connection.execute(query, [role]);

      // Transform the data to match the expected format
      const users = (rows as any[]).map(user => ({
        id: user.id,
        name: `${user.name || ''} ${user.last_name || ''}`.trim() || user.username,
        mobile: user.mobile || '',
        email: user.email,
        username: user.username,
        created_at: new Date(user.created_on * 1000).toISOString(),
        is_active: Boolean(user.is_active)
      }));

      return users;
    } catch (error) {
      console.error(`Error fetching users by role ${role}:`, error);
      throw error;
    }
  }

  async createCorporateUser(userData: any): Promise<any> {
    try {
      const { name, mobile, email, username, password } = userData;

      // Validate required fields
      if (!username || !email || !password) {
        throw new Error("Username, email, and password are required");
      }

      // Check if username or email already exists
      const [existingUser] = await connection.execute(
        'SELECT id FROM users WHERE username = ? OR email = ?',
        [username, email]
      );

      if ((existingUser as any[]).length > 0) {
        throw new Error("Username or email already exists");
      }

      // Hash password
      const hashedPassword = await bcrypt.hash(password, 12);

      // Split name into first and last name
      const nameParts = name.split(' ');
      const firstName = nameParts[0] || '';
      const lastName = nameParts.slice(1).join(' ') || '';

      // Insert user
      const [result] = await connection.execute(`
        INSERT INTO users (
          ip_address, username, password, email, first_name, last_name,
          phone, created_on, active, group_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
      `, [
        '127.0.0.1',
        username,
        hashedPassword,
        email,
        firstName,
        lastName,
        mobile || null,
        Math.floor(Date.now() / 1000),
        1,
        1 // Default group, will be updated below
      ]);

      const userId = (result as any).insertId;

      // Get corporate group ID
      const [groupRows] = await connection.execute(
        'SELECT id FROM `groups` WHERE name = ?',
        ['corporate']
      );

      if ((groupRows as any[]).length > 0) {
        const groupId = (groupRows as any[])[0].id;

        // Update user's group_id
        await connection.execute(
          'UPDATE users SET group_id = ? WHERE id = ?',
          [groupId, userId]
        );

        // Insert into users_groups table
        await connection.execute(
          'INSERT INTO users_groups (user_id, group_id) VALUES (?, ?)',
          [userId, groupId]
        );
      }

      // Return the created user
      return {
        id: userId,
        name,
        mobile: mobile || '',
        email,
        username,
        created_at: new Date().toISOString(),
        is_active: true
      };
    } catch (error) {
      console.error("Error creating corporate user:", error);
      throw error;
    }
  }

  async updateCorporateUser(userId: number, userData: any): Promise<any> {
    try {
      const { name, mobile, email, username, password } = userData;

      // Split name into first and last name
      const nameParts = name.split(' ');
      const firstName = nameParts[0] || '';
      const lastName = nameParts.slice(1).join(' ') || '';

      let updateQuery = `
        UPDATE users SET
          username = ?, email = ?, first_name = ?, last_name = ?, phone = ?
      `;
      let updateParams = [username, email, firstName, lastName, mobile || null];

      // If password is provided, hash and update it
      if (password && password.trim()) {
        const hashedPassword = await bcrypt.hash(password, 12);
        updateQuery += ', password = ?';
        updateParams.push(hashedPassword);
      }

      updateQuery += ' WHERE id = ?';
      updateParams.push(userId);

      await connection.execute(updateQuery, updateParams);

      // Return the updated user
      return {
        id: userId,
        name,
        mobile: mobile || '',
        email,
        username,
        created_at: new Date().toISOString(), // This should ideally come from DB
        is_active: true
      };
    } catch (error) {
      console.error("Error updating corporate user:", error);
      throw error;
    }
  }

  async resetCorporateUserPassword(userId: number): Promise<string> {
    try {
      // Generate a temporary password
      const tempPassword = 'temp' + Math.random().toString(36).substring(2, 8);

      // Hash the temporary password
      const hashedPassword = await bcrypt.hash(tempPassword, 12);

      // Update the user's password
      await connection.execute(
        'UPDATE users SET password = ? WHERE id = ?',
        [hashedPassword, userId]
      );

      console.log(`🔑 Password reset for user ${userId}, temp password: ${tempPassword}`);
      return tempPassword;
    } catch (error) {
      console.error("Error resetting corporate user password:", error);
      throw error;
    }
  }

  async deleteUser(userId: number): Promise<boolean> {
    try {
      // Soft delete by setting active = 0
      const [result] = await connection.execute(
        'UPDATE users SET active = 0 WHERE id = ?',
        [userId]
      );

      return (result as any).affectedRows > 0;
    } catch (error) {
      console.error("Error deleting user:", error);
      throw error;
    }
  }

  async createDemoUsers(): Promise<void> {
    try {
      console.log("🔍 Checking for demo users...");

      const demoUsersData = [
        {
          username: 'admin',
          email: 'admin@apphub.com',
          password: 'password',
          firstName: 'System',
          lastName: 'Administrator',
          company: 'AppHub System',
          phone: '+1-555-0001',
          ipAddress: '127.0.0.1',
          active: 1,
          createdOn: Math.floor(Date.now() / 1000),
          groupId: 0
        },
        {
          username: 'corporate',
          email: 'corporate@apphub.com',
          password: 'password',
          firstName: 'Corporate',
          lastName: 'Manager',
          company: 'AppHub Corporate',
          phone: '+1-555-0002',
          ipAddress: '127.0.0.1',
          active: 1,
          createdOn: Math.floor(Date.now() / 1000),
          groupId: 0
        },
        {
          username: 'head_office',
          email: 'headoffice@apphub.com',
          password: 'password',
          firstName: 'Head Office',
          lastName: 'Manager',
          company: 'AppHub Head Office',
          phone: '+1-555-0003',
          ipAddress: '127.0.0.1',
          active: 1,
          createdOn: Math.floor(Date.now() / 1000),
          groupId: 0
        },
        {
          username: 'regional',
          email: 'regional@apphub.com',
          password: 'password',
          firstName: 'Regional',
          lastName: 'Manager',
          company: 'AppHub Regional',
          phone: '+1-555-0004',
          ipAddress: '127.0.0.1',
          active: 1,
          createdOn: Math.floor(Date.now() / 1000),
          groupId: 0
        },
        {
          username: 'branch',
          email: 'branch@apphub.com',
          password: 'password',
          firstName: 'Branch',
          lastName: 'Manager',
          company: 'AppHub Branch',
          phone: '+1-555-0005',
          ipAddress: '127.0.0.1',
          active: 1,
          createdOn: Math.floor(Date.now() / 1000),
          groupId: 0
        }
      ];

      for (const userData of demoUsersData) {
        // Check if user already exists
        const [existingUser] = await connection.execute(
          'SELECT id FROM users WHERE username = ? OR email = ?',
          [userData.username, userData.email]
        );

        if ((existingUser as any[]).length === 0) {
          try {
            // Hash password
            const hashedPassword = await bcrypt.hash(userData.password, 12);

            // Insert user using raw SQL to handle different table structures
            await connection.execute(`
              INSERT INTO users (
                ip_address, username, password, email, first_name, last_name,
                company, phone, created_on, active, group_id
              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            `, [
              userData.ipAddress,
              userData.username,
              hashedPassword,
              userData.email,
              userData.firstName,
              userData.lastName,
              userData.company,
              userData.phone,
              userData.createdOn,
              userData.active,
              userData.groupId
            ]);

            console.log(`✅ Created demo user: ${userData.username}`);
          } catch (error) {
            console.error(`❌ Failed to create demo user ${userData.username}:`, error);
          }
        } else {
          console.log(`ℹ️ Demo user ${userData.username} already exists`);
        }
      }
    } catch (error) {
      console.error("❌ Error creating demo users:", error);
    }
  }

  async seedGroupsAndUsers(): Promise<void> {
    try {
      // Create language table if it doesn't exist
      await connection.execute(`
        CREATE TABLE IF NOT EXISTS \`language\` (
          id INT AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(100) NOT NULL,
          code VARCHAR(10) NOT NULL UNIQUE,
          is_active TINYINT(1) DEFAULT 1,
          speakers VARCHAR(50),
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
      `);

      // Create groups table if it doesn't exist
      await connection.execute(`
        CREATE TABLE IF NOT EXISTS \`groups\` (
          id INT AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(20) NOT NULL,
          description VARCHAR(100) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
      `);

      // Create users_groups table if it doesn't exist
      await connection.execute(`
        CREATE TABLE IF NOT EXISTS users_groups (
          id INT AUTO_INCREMENT PRIMARY KEY,
          user_id INT NOT NULL,
          group_id INT NOT NULL,
          FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
          FOREIGN KEY (group_id) REFERENCES \`groups\`(id) ON DELETE CASCADE,
          UNIQUE KEY unique_user_group (user_id, group_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
      `);

      // Check if groups exist
      const [groupRows] = await connection.execute('SELECT COUNT(*) as count FROM `groups`');

      if ((groupRows as any[])[0].count === 0) {
        // Insert default groups
        const defaultGroups = [
          ['admin', 'System Administrator'],
          ['corporate', 'Corporate Manager'],
          ['head_office', 'Head Office Manager'],
          ['regional', 'Regional Manager'],
          ['branch', 'Branch Manager'],
          ['user', 'Regular User']
        ];

        for (const [name, description] of defaultGroups) {
          await connection.execute(
            'INSERT INTO `groups` (name, description) VALUES (?, ?)',
            [name, description]
          );
        }
        console.log('✅ Default groups created successfully');
      }

      // Create demo users if they don't exist
      await this.createDemoUsers();

      // Check if demo users exist and create group associations
      const demoUsers = ['admin', 'corporate', 'head_office', 'regional', 'branch'];

      for (const username of demoUsers) {
        const [userRows] = await connection.execute('SELECT id FROM users WHERE username = ?', [username]);

        if ((userRows as any[]).length > 0) {
          const userId = (userRows as any[])[0].id;

          // Get the group ID for this user's role
          const [groupRows] = await connection.execute('SELECT id FROM `groups` WHERE name = ?', [username]);

          if ((groupRows as any[]).length > 0) {
            const groupId = (groupRows as any[])[0].id;

            // Check if association already exists
            const [existingRows] = await connection.execute(
              'SELECT COUNT(*) as count FROM users_groups WHERE user_id = ? AND group_id = ?',
              [userId, groupId]
            );

            if ((existingRows as any[])[0].count === 0) {
              // Create the association
              await connection.execute(
                'INSERT INTO users_groups (user_id, group_id) VALUES (?, ?)',
                [userId, groupId]
              );
              console.log(`✅ Associated user ${username} with group ${username}`);
            }
          }
        }
      }

      // Insert default languages if not exists
      const [languageCount] = await connection.execute('SELECT COUNT(*) as count FROM language');
      if ((languageCount as any[])[0].count === 0) {
        const defaultLanguages = [
          { name: 'English', code: 'en', is_active: 1, speakers: '1.5 Billion' },
          { name: 'Spanish', code: 'es', is_active: 1, speakers: '500 Million' },
          { name: 'Hindi', code: 'hi', is_active: 1, speakers: '600 Million' },
          { name: 'Chinese (Mandarin)', code: 'zh', is_active: 1, speakers: '1.4 Billion' },
          { name: 'French', code: 'fr', is_active: 1, speakers: '280 Million' },
          { name: 'Arabic', code: 'ar', is_active: 1, speakers: '422 Million' },
          { name: 'Bengali', code: 'bn', is_active: 1, speakers: '265 Million' },
          { name: 'Portuguese', code: 'pt', is_active: 1, speakers: '260 Million' },
          { name: 'Russian', code: 'ru', is_active: 1, speakers: '258 Million' },
          { name: 'Japanese', code: 'ja', is_active: 1, speakers: '125 Million' }
        ];

        for (const lang of defaultLanguages) {
          await connection.execute(
            'INSERT INTO language (name, code, is_active, speakers) VALUES (?, ?, ?, ?)',
            [lang.name, lang.code, lang.is_active, lang.speakers]
          );
        }

        console.log('✅ Default languages seeded');
      }

    } catch (error) {
      console.error("Error seeding groups and users:", error);
      throw error;
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

  // Content Management Methods

  // Continent Methods
  async getAllContinents(): Promise<any[]> {
    try {
      const result = await db.select().from(continentTbl).orderBy(continentTbl.continent);
      return result;
    } catch (error) {
      console.error("Error fetching continents:", error);
      throw error;
    }
  }

  async getContinentById(id: number): Promise<any | null> {
    try {
      const result = await db.select().from(continentTbl).where(eq(continentTbl.id, id)).limit(1);
      return result[0] || null;
    } catch (error) {
      console.error("Error fetching continent by ID:", error);
      throw error;
    }
  }

  async createContinent(continentData: ContinentInput): Promise<Continent> {
    try {
      const [result] = await db.insert(continentTbl).values(continentData);
      const newContinent = await db.select().from(continentTbl).where(eq(continentTbl.id, result.insertId)).limit(1);
      return newContinent[0];
    } catch (error) {
      console.error("Error creating continent:", error);
      throw error;
    }
  }

  async updateContinent(id: number, continentData: Partial<ContinentInput>): Promise<Continent | null> {
    try {
      await db.update(continentTbl).set(continentData).where(eq(continentTbl.id, id));
      const updatedContinent = await db.select().from(continentTbl).where(eq(continentTbl.id, id)).limit(1);
      return updatedContinent[0] || null;
    } catch (error) {
      console.error("Error updating continent:", error);
      throw error;
    }
  }

  async deleteContinent(id: number): Promise<boolean> {
    try {
      const [result] = await connection.execute('DELETE FROM continent_tbl WHERE id = ?', [id]);
      return (result as any).affectedRows > 0;
    } catch (error) {
      console.error("Error deleting continent:", error);
      throw error;
    }
  }

  // Country Methods
  async ensureCountryTableExists(): Promise<void> {
    try {
      // Check if table exists and has correct structure
      const [tableInfo] = await connection.execute('DESCRIBE country_tbl');
      console.log('🔍 Current country table structure:', tableInfo);

      const columns = (tableInfo as any[]).map(col => col.Field);
      console.log('🔍 Available columns:', columns);

      const requiredColumns = ['id', 'continent_id', 'country', 'order_by', 'status', 'code', 'currency', 'country_flag', 'phone_code', 'nationality'];
      const missingColumns = requiredColumns.filter(col => !columns.includes(col));

      if (missingColumns.length > 0) {
        console.log('⚠️ Country table missing required columns:', missingColumns);
        console.log('🔨 Recreating country table with correct structure...');

        // Drop existing table
        await connection.execute('DROP TABLE IF EXISTS country_tbl');

        // Create new table with correct structure
        await connection.execute(`
          CREATE TABLE country_tbl (
            id int NOT NULL AUTO_INCREMENT,
            continent_id int NOT NULL,
            country varchar(100) NOT NULL,
            order_by int DEFAULT 0,
            status tinyint(1) DEFAULT 1,
            code varchar(45) NOT NULL,
            currency varchar(10) DEFAULT NULL,
            country_flag varchar(255) DEFAULT NULL,
            phone_code varchar(10) DEFAULT NULL,
            nationality varchar(100) DEFAULT NULL,
            created_at timestamp DEFAULT CURRENT_TIMESTAMP,
            updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY code_UNIQUE (code),
            KEY fk_country_continent_idx (continent_id),
            CONSTRAINT fk_country_continent FOREIGN KEY (continent_id) REFERENCES continent_tbl (id)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
        `);

        // Insert default countries
        console.log('🌱 Seeding country table with default data...');
        await connection.execute(`
          INSERT INTO country_tbl (continent_id, country, code, currency, country_flag, phone_code, nationality, order_by) VALUES
          (1, 'India', 'IN', 'INR', '🇮🇳', '91', 'Indian', 1),
          (1, 'China', 'CN', 'CNY', '🇨🇳', '86', 'Chinese', 2),
          (1, 'Japan', 'JP', 'JPY', '🇯🇵', '81', 'Japanese', 3),
          (1, 'South Korea', 'KR', 'KRW', '🇰🇷', '82', 'Korean', 4),
          (1, 'Thailand', 'TH', 'THB', '🇹🇭', '66', 'Thai', 5)
        `);

        console.log('✅ Country table created and seeded with default countries');
      }
    } catch (error) {
      if ((error as any).code === 'ER_NO_SUCH_TABLE') {
        console.log('🔨 Country table does not exist, creating...');

        // Create new table
        await connection.execute(`
          CREATE TABLE country_tbl (
            id int NOT NULL AUTO_INCREMENT,
            continent_id int NOT NULL,
            country varchar(100) NOT NULL,
            order_by int DEFAULT 0,
            status tinyint(1) DEFAULT 1,
            code varchar(45) NOT NULL,
            currency varchar(10) DEFAULT NULL,
            country_flag varchar(255) DEFAULT NULL,
            phone_code varchar(10) DEFAULT NULL,
            nationality varchar(100) DEFAULT NULL,
            created_at timestamp DEFAULT CURRENT_TIMESTAMP,
            updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY code_UNIQUE (code),
            KEY fk_country_continent_idx (continent_id),
            CONSTRAINT fk_country_continent FOREIGN KEY (continent_id) REFERENCES continent_tbl (id)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
        `);

        // Insert default countries
        console.log('🌱 Seeding country table with default data...');
        await connection.execute(`
          INSERT INTO country_tbl (continent_id, country, code, currency, country_flag, phone_code, nationality, order_by) VALUES
          (1, 'India', 'IN', 'INR', '🇮🇳', '91', 'Indian', 1),
          (1, 'China', 'CN', 'CNY', '🇨🇳', '86', 'Chinese', 2),
          (1, 'Japan', 'JP', 'JPY', '🇯🇵', '81', 'Japanese', 3),
          (1, 'South Korea', 'KR', 'KRW', '🇰🇷', '82', 'Korean', 4),
          (1, 'Thailand', 'TH', 'THB', '🇹🇭', '66', 'Thai', 5)
        `);

        console.log('✅ Country table created and seeded with default countries');
      } else {
        console.error('Error ensuring country table exists:', error);
        throw error;
      }
    }
  }

  async getAllCountries(): Promise<any[]> {
    try {
      await this.ensureCountryTableExists();

      const query = `
        SELECT
          c.*,
          cont.continent as continent_name
        FROM country_tbl c
        LEFT JOIN continent_tbl cont ON c.continent_id = cont.id
        ORDER BY c.country
      `;
      const [rows] = await connection.execute(query);
      return rows as any[];
    } catch (error) {
      console.error("Error fetching countries:", error);
      throw error;
    }
  }

  async getCountryById(id: number): Promise<any | null> {
    try {
      const query = `
        SELECT
          c.*,
          cont.continent as continent_name
        FROM country_tbl c
        LEFT JOIN continent_tbl cont ON c.continent_id = cont.id
        WHERE c.id = ?
      `;
      const [rows] = await connection.execute(query, [id]);
      return (rows as any[])[0] || null;
    } catch (error) {
      console.error("Error fetching country by ID:", error);
      throw error;
    }
  }

  async getCountriesByContinent(continentId: number): Promise<Country[]> {
    try {
      const result = await db.select().from(countryTbl).where(eq(countryTbl.continentId, continentId)).orderBy(countryTbl.country);
      return result;
    } catch (error) {
      console.error("Error fetching countries by continent:", error);
      throw error;
    }
  }

  async createCountry(countryData: CountryInput): Promise<Country> {
    try {
      const [result] = await db.insert(countryTbl).values(countryData);
      const newCountry = await db.select().from(countryTbl).where(eq(countryTbl.id, result.insertId)).limit(1);
      return newCountry[0];
    } catch (error) {
      console.error("Error creating country:", error);
      throw error;
    }
  }

  async updateCountry(id: number, countryData: Partial<CountryInput>): Promise<Country | null> {
    try {
      await db.update(countryTbl).set(countryData).where(eq(countryTbl.id, id));
      const updatedCountry = await db.select().from(countryTbl).where(eq(countryTbl.id, id)).limit(1);
      return updatedCountry[0] || null;
    } catch (error) {
      console.error("Error updating country:", error);
      throw error;
    }
  }

  async deleteCountry(id: number): Promise<boolean> {
    try {
      const [result] = await connection.execute('DELETE FROM country_tbl WHERE id = ?', [id]);
      return (result as any).affectedRows > 0;
    } catch (error) {
      console.error("Error deleting country:", error);
      throw error;
    }
  }

  // State Methods
  async getAllStates(): Promise<any[]> {
    try {
      const query = `
        SELECT
          s.*,
          c.country as country_name,
          cont.continent as continent_name
        FROM state_tbl s
        LEFT JOIN country_tbl c ON s.country_id = c.id
        LEFT JOIN continent_tbl cont ON c.continent_id = cont.id
        ORDER BY s.state
      `;
      const [rows] = await connection.execute(query);
      return rows as any[];
    } catch (error) {
      console.error("Error fetching states:", error);
      throw error;
    }
  }

  async getStateById(id: number): Promise<any | null> {
    try {
      const query = `
        SELECT
          s.*,
          c.country as country_name,
          cont.continent as continent_name
        FROM state_tbl s
        LEFT JOIN country_tbl c ON s.country_id = c.id
        LEFT JOIN continent_tbl cont ON c.continent_id = cont.id
        WHERE s.id = ?
      `;
      const [rows] = await connection.execute(query, [id]);
      return (rows as any[])[0] || null;
    } catch (error) {
      console.error("Error fetching state by ID:", error);
      throw error;
    }
  }

  async getStatesByCountry(countryId: number): Promise<State[]> {
    try {
      const result = await db.select().from(stateTbl).where(eq(stateTbl.countryId, countryId)).orderBy(stateTbl.state);
      return result;
    } catch (error) {
      console.error("Error fetching states by country:", error);
      throw error;
    }
  }

  async createState(stateData: StateInput): Promise<State> {
    try {
      const [result] = await db.insert(stateTbl).values(stateData);
      const newState = await db.select().from(stateTbl).where(eq(stateTbl.id, result.insertId)).limit(1);
      return newState[0];
    } catch (error) {
      console.error("Error creating state:", error);
      throw error;
    }
  }

  async updateState(id: number, stateData: Partial<StateInput>): Promise<State | null> {
    try {
      await db.update(stateTbl).set(stateData).where(eq(stateTbl.id, id));
      const updatedState = await db.select().from(stateTbl).where(eq(stateTbl.id, id)).limit(1);
      return updatedState[0] || null;
    } catch (error) {
      console.error("Error updating state:", error);
      throw error;
    }
  }

  async deleteState(id: number): Promise<boolean> {
    try {
      const [result] = await connection.execute('DELETE FROM state_tbl WHERE id = ?', [id]);
      return (result as any).affectedRows > 0;
    } catch (error) {
      console.error("Error deleting state:", error);
      throw error;
    }
  }

  // District Methods
  async getAllDistricts(): Promise<any[]> {
    try {
      const query = `
        SELECT
          d.*,
          s.state as state_name,
          c.country as country_name,
          cont.continent as continent_name
        FROM district_tbl d
        LEFT JOIN state_tbl s ON d.state_id = s.id
        LEFT JOIN country_tbl c ON s.country_id = c.id
        LEFT JOIN continent_tbl cont ON c.continent_id = cont.id
        ORDER BY d.district
      `;
      const [rows] = await connection.execute(query);
      return rows as any[];
    } catch (error) {
      console.error("Error fetching districts:", error);
      throw error;
    }
  }

  async getDistrictById(id: number): Promise<any | null> {
    try {
      const query = `
        SELECT
          d.*,
          s.state as state_name,
          c.country as country_name,
          cont.continent as continent_name
        FROM district_tbl d
        LEFT JOIN state_tbl s ON d.state_id = s.id
        LEFT JOIN country_tbl c ON s.country_id = c.id
        LEFT JOIN continent_tbl cont ON c.continent_id = cont.id
        WHERE d.id = ?
      `;
      const [rows] = await connection.execute(query, [id]);
      return (rows as any[])[0] || null;
    } catch (error) {
      console.error("Error fetching district by ID:", error);
      throw error;
    }
  }

  async getDistrictsByState(stateId: number): Promise<District[]> {
    try {
      const result = await db.select().from(districtTbl).where(eq(districtTbl.stateId, stateId)).orderBy(districtTbl.district);
      return result;
    } catch (error) {
      console.error("Error fetching districts by state:", error);
      throw error;
    }
  }

  async getDistrictsByCountry(countryId: number): Promise<District[]> {
    try {
      // Get districts by country through state relationship
      const query = `
        SELECT d.*
        FROM district_tbl d
        INNER JOIN state_tbl s ON d.state_id = s.id
        WHERE s.country_id = ?
        ORDER BY d.district
      `;
      const [rows] = await connection.execute(query, [countryId]);
      return rows as District[];
    } catch (error) {
      console.error("Error fetching districts by country:", error);
      throw error;
    }
  }

  async createDistrict(districtData: DistrictInput): Promise<District> {
    try {
      const [result] = await db.insert(districtTbl).values(districtData);
      const newDistrict = await db.select().from(districtTbl).where(eq(districtTbl.id, result.insertId)).limit(1);
      return newDistrict[0];
    } catch (error) {
      console.error("Error creating district:", error);
      throw error;
    }
  }

  async updateDistrict(id: number, districtData: Partial<DistrictInput>): Promise<District | null> {
    try {
      await db.update(districtTbl).set(districtData).where(eq(districtTbl.id, id));
      const updatedDistrict = await db.select().from(districtTbl).where(eq(districtTbl.id, id)).limit(1);
      return updatedDistrict[0] || null;
    } catch (error) {
      console.error("Error updating district:", error);
      throw error;
    }
  }

  async deleteDistrict(id: number): Promise<boolean> {
    try {
      const [result] = await connection.execute('DELETE FROM district_tbl WHERE id = ?', [id]);
      return (result as any).affectedRows > 0;
    } catch (error) {
      console.error("Error deleting district:", error);
      throw error;
    }
  }

  // Categories Management Methods

  async getAllCategories(): Promise<any[]> {
    try {
      console.log("📁 MySQL: Executing getAllCategories query");
      const [rows] = await connection.execute('SELECT * FROM group_create ORDER BY apps_name, order_by');
      console.log("📁 MySQL: Query result:", rows);
      return rows as any[];
    } catch (error) {
      console.error("📁 MySQL: Error fetching categories:", error);
      throw error;
    }
  }

  async getCategoryById(id: number): Promise<any | null> {
    try {
      const [rows] = await connection.execute('SELECT * FROM group_create WHERE id = ?', [id]);
      return (rows as any[])[0] || null;
    } catch (error) {
      console.error("Error fetching category by ID:", error);
      throw error;
    }
  }

  async createCategory(categoryData: any): Promise<any> {
    try {
      const [result] = await connection.execute(
        'INSERT INTO group_create (name, apps_name, order_by, code) VALUES (?, ?, ?, ?)',
        [categoryData.name, categoryData.apps_name, categoryData.order_by || 0, categoryData.code || null]
      );

      const [newCategory] = await connection.execute('SELECT * FROM group_create WHERE id = ?', [(result as any).insertId]);
      return (newCategory as any[])[0];
    } catch (error) {
      console.error("Error creating category:", error);
      throw error;
    }
  }

  async updateCategory(id: number, categoryData: any): Promise<any | null> {
    try {
      await connection.execute(
        'UPDATE group_create SET name = ?, apps_name = ?, order_by = ?, code = ? WHERE id = ?',
        [categoryData.name, categoryData.apps_name, categoryData.order_by || 0, categoryData.code || null, id]
      );

      const [updatedCategory] = await connection.execute('SELECT * FROM group_create WHERE id = ?', [id]);
      return (updatedCategory as any[])[0] || null;
    } catch (error) {
      console.error("Error updating category:", error);
      throw error;
    }
  }

  async deleteCategory(id: number): Promise<boolean> {
    try {
      const [result] = await connection.execute('DELETE FROM group_create WHERE id = ?', [id]);
      return (result as any).affectedRows > 0;
    } catch (error) {
      console.error("Error deleting category:", error);
      throw error;
    }
  }

  // Language Management Methods
  async getAllLanguages(): Promise<any[]> {
    try {
      console.log("🌐 MySQL: Executing getAllLanguages query");

      // First check what columns exist in the language table
      try {
        const [tableInfo] = await connection.execute('DESCRIBE language');
        console.log("🔍 Current language table structure:", tableInfo);

        const columns = (tableInfo as any[]).map(col => col.Field);
        console.log("🔍 Available columns:", columns);

        // If table doesn't have the right structure, fix it
        if (!columns.includes('name')) {
          console.log("⚠️ Language table missing 'name' column, recreating...");
          await this.ensureLanguageTableExists();
        }
      } catch (error: any) {
        if (error.code === 'ER_NO_SUCH_TABLE') {
          console.log("🔍 Language table doesn't exist, creating...");
          await this.ensureLanguageTableExists();
        } else {
          console.error("Error checking language table:", error);
          throw error;
        }
      }

      const [rows] = await connection.execute('SELECT * FROM language ORDER BY name');
      console.log("🌐 MySQL: Query result:", rows);

      // Map database fields to frontend expected format
      const mappedRows = (rows as any[]).map(row => ({
        id: row.id,
        name: row.name,
        code: row.code,
        isActive: row.is_active || row.isActive,
        speakers: row.speakers,
        createdAt: row.created_at || row.createdAt,
        updatedAt: row.updated_at || row.updatedAt
      }));

      return mappedRows;
    } catch (error) {
      console.error("🌐 MySQL: Error fetching languages:", error);
      throw error;
    }
  }

  async ensureLanguageTableExists(): Promise<void> {
    try {
      console.log("🔨 Dropping existing language table if it exists...");
      await connection.execute('DROP TABLE IF EXISTS language');

      // Create language table
      console.log("🔨 Creating new language table...");
      await connection.execute(`
        CREATE TABLE \`language\` (
          id INT AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(100) NOT NULL,
          code VARCHAR(10) NOT NULL UNIQUE,
          is_active TINYINT(1) DEFAULT 1,
          speakers VARCHAR(50),
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
      `);

      // Seed with default data
      console.log("🌱 Seeding language table with default data...");
      const defaultLanguages = [
        { name: 'English', code: 'en', is_active: 1, speakers: '1.5 Billion' },
        { name: 'Spanish', code: 'es', is_active: 1, speakers: '500 Million' },
        { name: 'Hindi', code: 'hi', is_active: 1, speakers: '600 Million' },
        { name: 'Chinese (Mandarin)', code: 'zh', is_active: 1, speakers: '1.4 Billion' },
        { name: 'French', code: 'fr', is_active: 1, speakers: '280 Million' },
        { name: 'Arabic', code: 'ar', is_active: 1, speakers: '422 Million' },
        { name: 'Bengali', code: 'bn', is_active: 1, speakers: '265 Million' },
        { name: 'Portuguese', code: 'pt', is_active: 1, speakers: '260 Million' },
        { name: 'Russian', code: 'ru', is_active: 1, speakers: '258 Million' },
        { name: 'Japanese', code: 'ja', is_active: 1, speakers: '125 Million' }
      ];

      for (const lang of defaultLanguages) {
        await connection.execute(
          'INSERT INTO language (name, code, is_active, speakers) VALUES (?, ?, ?, ?)',
          [lang.name, lang.code, lang.is_active, lang.speakers]
        );
      }

      console.log('✅ Language table created and seeded with default languages');
    } catch (error) {
      console.error("❌ Error ensuring language table exists:", error);
      throw error;
    }
  }

  async getLanguageById(id: number): Promise<any | null> {
    try {
      await this.ensureLanguageTableExists();
      const [rows] = await connection.execute('SELECT * FROM language WHERE id = ?', [id]);
      return (rows as any[])[0] || null;
    } catch (error) {
      console.error("Error fetching language by ID:", error);
      throw error;
    }
  }

  async createLanguage(languageData: LanguageInput): Promise<Language> {
    try {
      await this.ensureLanguageTableExists();
      const [result] = await connection.execute(
        'INSERT INTO language (name, code, is_active, speakers) VALUES (?, ?, ?, ?)',
        [languageData.name, languageData.code, languageData.isActive || 1, languageData.speakers || null]
      );

      const [newLanguage] = await connection.execute('SELECT * FROM language WHERE id = ?', [(result as any).insertId]);
      const row = (newLanguage as any[])[0];

      // Map database fields to frontend expected format
      return {
        id: row.id,
        name: row.name,
        code: row.code,
        isActive: row.is_active,
        speakers: row.speakers,
        createdAt: row.created_at,
        updatedAt: row.updated_at
      };
    } catch (error) {
      console.error("Error creating language:", error);
      throw error;
    }
  }

  async updateLanguage(id: number, languageData: Partial<LanguageInput>): Promise<Language | null> {
    try {
      await this.ensureLanguageTableExists();
      const updateFields = [];
      const updateValues = [];

      if (languageData.name !== undefined) {
        updateFields.push('name = ?');
        updateValues.push(languageData.name);
      }
      if (languageData.code !== undefined) {
        updateFields.push('code = ?');
        updateValues.push(languageData.code);
      }
      if (languageData.isActive !== undefined) {
        updateFields.push('is_active = ?');
        updateValues.push(languageData.isActive);
      }
      if (languageData.speakers !== undefined) {
        updateFields.push('speakers = ?');
        updateValues.push(languageData.speakers);
      }

      if (updateFields.length === 0) {
        return this.getLanguageById(id);
      }

      updateValues.push(id);
      await connection.execute(
        `UPDATE language SET ${updateFields.join(', ')} WHERE id = ?`,
        updateValues
      );

      const [updatedLanguage] = await connection.execute('SELECT * FROM language WHERE id = ?', [id]);
      return (updatedLanguage as any[])[0] || null;
    } catch (error) {
      console.error("Error updating language:", error);
      throw error;
    }
  }

  async deleteLanguage(id: number): Promise<boolean> {
    try {
      await this.ensureLanguageTableExists();
      const [result] = await connection.execute('DELETE FROM language WHERE id = ?', [id]);
      return (result as any).affectedRows > 0;
    } catch (error) {
      console.error("Error deleting language:", error);
      throw error;
    }
  }

  // Education Management Methods
  async getAllEducation(): Promise<any[]> {
    try {
      console.log("🎓 MySQL: Executing getAllEducation query");
      await this.ensureEducationTableExists();

      const [rows] = await connection.execute('SELECT * FROM education ORDER BY level');
      console.log("🎓 MySQL: Query result:", rows);

      // Map database fields to frontend expected format
      const mappedRows = (rows as any[]).map(row => ({
        id: row.id,
        level: row.level,
        isActive: row.is_active || row.isActive,
        users: row.users || 0,
        createdAt: row.created_at || row.createdAt,
        updatedAt: row.updated_at || row.updatedAt
      }));

      return mappedRows;
    } catch (error) {
      console.error("🎓 MySQL: Error fetching education:", error);
      throw error;
    }
  }

  async ensureEducationTableExists(): Promise<void> {
    try {
      console.log("🔨 Checking education table structure...");

      // First check what columns exist in the education table
      try {
        const [tableInfo] = await connection.execute('DESCRIBE education');
        console.log("🔍 Current education table structure:", tableInfo);

        const columns = (tableInfo as any[]).map(col => col.Field);
        console.log("🔍 Available columns:", columns);

        // If table doesn't have the right structure, recreate it
        if (!columns.includes('level')) {
          console.log("⚠️ Education table missing 'level' column, recreating...");
          await connection.execute('DROP TABLE IF EXISTS education');

          await connection.execute(`
            CREATE TABLE \`education\` (
              id INT AUTO_INCREMENT PRIMARY KEY,
              level VARCHAR(100) NOT NULL,
              is_active TINYINT(1) DEFAULT 1,
              users INT DEFAULT 0,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
          `);
          console.log("✅ Education table recreated with correct structure");
        }
      } catch (error: any) {
        if (error.code === 'ER_NO_SUCH_TABLE') {
          console.log("🔨 Creating new education table...");
          await connection.execute(`
            CREATE TABLE \`education\` (
              id INT AUTO_INCREMENT PRIMARY KEY,
              level VARCHAR(100) NOT NULL,
              is_active TINYINT(1) DEFAULT 1,
              users INT DEFAULT 0,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
          `);
          console.log("✅ Education table created");
        } else {
          console.error("Error checking education table:", error);
          throw error;
        }
      }

      // Insert default education levels if table is empty
      const [rows] = await connection.execute('SELECT COUNT(*) as count FROM education');
      const count = (rows as any[])[0].count;

      if (count === 0) {
        console.log("🔨 Inserting default education levels...");
        const defaultEducation = [
          { level: 'High School', is_active: 1, users: 2450 },
          { level: 'Bachelor\'s Degree', is_active: 1, users: 4230 },
          { level: 'Master\'s Degree', is_active: 1, users: 2100 },
          { level: 'PhD/Doctorate', is_active: 1, users: 890 },
          { level: 'Professional Certificate', is_active: 1, users: 1560 },
        ];

        for (const edu of defaultEducation) {
          await connection.execute(
            'INSERT INTO education (level, is_active, users) VALUES (?, ?, ?)',
            [edu.level, edu.is_active, edu.users]
          );
        }
        console.log("✅ Default education levels inserted");
      }
    } catch (error) {
      console.error("❌ Error ensuring education table exists:", error);
      throw error;
    }
  }

  async createEducation(educationData: any): Promise<any> {
    try {
      await this.ensureEducationTableExists();
      const [result] = await connection.execute(
        'INSERT INTO education (level, is_active, users) VALUES (?, ?, ?)',
        [educationData.level, educationData.isActive || 1, educationData.users || 0]
      );

      const [newEducation] = await connection.execute('SELECT * FROM education WHERE id = ?', [(result as any).insertId]);
      const row = (newEducation as any[])[0];

      return {
        id: row.id,
        level: row.level,
        isActive: row.is_active,
        users: row.users,
        createdAt: row.created_at,
        updatedAt: row.updated_at
      };
    } catch (error) {
      console.error("Error creating education:", error);
      throw error;
    }
  }

  async updateEducation(id: number, educationData: any): Promise<any | null> {
    try {
      await this.ensureEducationTableExists();
      const updateFields = [];
      const updateValues = [];

      if (educationData.level !== undefined) {
        updateFields.push('level = ?');
        updateValues.push(educationData.level);
      }
      if (educationData.isActive !== undefined) {
        updateFields.push('is_active = ?');
        updateValues.push(educationData.isActive);
      }
      if (educationData.users !== undefined) {
        updateFields.push('users = ?');
        updateValues.push(educationData.users);
      }

      if (updateFields.length === 0) {
        const [education] = await connection.execute('SELECT * FROM education WHERE id = ?', [id]);
        return (education as any[])[0] || null;
      }

      updateValues.push(id);
      await connection.execute(
        `UPDATE education SET ${updateFields.join(', ')} WHERE id = ?`,
        updateValues
      );

      const [updatedEducation] = await connection.execute('SELECT * FROM education WHERE id = ?', [id]);
      const row = (updatedEducation as any[])[0];

      return row ? {
        id: row.id,
        level: row.level,
        isActive: row.is_active,
        users: row.users,
        createdAt: row.created_at,
        updatedAt: row.updated_at
      } : null;
    } catch (error) {
      console.error("Error updating education:", error);
      throw error;
    }
  }

  async deleteEducation(id: number): Promise<boolean> {
    try {
      await this.ensureEducationTableExists();
      const [result] = await connection.execute('DELETE FROM education WHERE id = ?', [id]);
      return (result as any).affectedRows > 0;
    } catch (error) {
      console.error("Error deleting education:", error);
      throw error;
    }
  }

  // Profession Management Methods
  async getAllProfessions(): Promise<any[]> {
    try {
      console.log("💼 MySQL: Executing getAllProfessions query");
      await this.ensureProfessionTableExists();

      const [rows] = await connection.execute('SELECT * FROM profession ORDER BY name');
      console.log("💼 MySQL: Query result:", rows);

      // Map database fields to frontend expected format
      const mappedRows = (rows as any[]).map(row => ({
        id: row.id,
        name: row.name,
        category: row.category,
        isActive: row.is_active || row.isActive,
        users: row.users || 0,
        createdAt: row.created_at || row.createdAt,
        updatedAt: row.updated_at || row.updatedAt
      }));

      return mappedRows;
    } catch (error) {
      console.error("💼 MySQL: Error fetching professions:", error);
      throw error;
    }
  }

  async ensureProfessionTableExists(): Promise<void> {
    try {
      console.log("🔨 Checking profession table structure...");

      // First check what columns exist in the profession table
      try {
        const [tableInfo] = await connection.execute('DESCRIBE profession');
        console.log("🔍 Current profession table structure:", tableInfo);

        const columns = (tableInfo as any[]).map(col => col.Field);
        console.log("🔍 Available columns:", columns);

        // If table doesn't have the right structure, recreate it
        if (!columns.includes('name') || !columns.includes('category')) {
          console.log("⚠️ Profession table missing required columns, recreating...");
          await connection.execute('DROP TABLE IF EXISTS profession');

          await connection.execute(`
            CREATE TABLE \`profession\` (
              id INT AUTO_INCREMENT PRIMARY KEY,
              name VARCHAR(100) NOT NULL,
              category VARCHAR(50) NOT NULL,
              is_active TINYINT(1) DEFAULT 1,
              users INT DEFAULT 0,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
          `);
          console.log("✅ Profession table recreated with correct structure");
        }
      } catch (error: any) {
        if (error.code === 'ER_NO_SUCH_TABLE') {
          console.log("🔨 Creating new profession table...");
          await connection.execute(`
            CREATE TABLE \`profession\` (
              id INT AUTO_INCREMENT PRIMARY KEY,
              name VARCHAR(100) NOT NULL,
              category VARCHAR(50) NOT NULL,
              is_active TINYINT(1) DEFAULT 1,
              users INT DEFAULT 0,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
          `);
          console.log("✅ Profession table created");
        } else {
          console.error("Error checking profession table:", error);
          throw error;
        }
      }

      // Insert default professions if table is empty
      const [rows] = await connection.execute('SELECT COUNT(*) as count FROM profession');
      const count = (rows as any[])[0].count;

      if (count === 0) {
        console.log("🔨 Inserting default professions...");
        const defaultProfessions = [
          { name: 'Software Engineer', category: 'Technology', is_active: 1, users: 1850 },
          { name: 'Marketing Manager', category: 'Marketing', is_active: 1, users: 920 },
          { name: 'Data Scientist', category: 'Technology', is_active: 1, users: 650 },
          { name: 'Product Manager', category: 'Management', is_active: 1, users: 480 },
          { name: 'Sales Representative', category: 'Sales', is_active: 1, users: 1200 },
        ];

        for (const prof of defaultProfessions) {
          await connection.execute(
            'INSERT INTO profession (name, category, is_active, users) VALUES (?, ?, ?, ?)',
            [prof.name, prof.category, prof.is_active, prof.users]
          );
        }
        console.log("✅ Default professions inserted");
      }
    } catch (error) {
      console.error("❌ Error ensuring profession table exists:", error);
      throw error;
    }
  }

  async createProfession(professionData: any): Promise<any> {
    try {
      await this.ensureProfessionTableExists();
      const [result] = await connection.execute(
        'INSERT INTO profession (name, category, is_active, users) VALUES (?, ?, ?, ?)',
        [professionData.name, professionData.category, professionData.isActive || 1, professionData.users || 0]
      );

      const [newProfession] = await connection.execute('SELECT * FROM profession WHERE id = ?', [(result as any).insertId]);
      const row = (newProfession as any[])[0];

      return {
        id: row.id,
        name: row.name,
        category: row.category,
        isActive: row.is_active,
        users: row.users,
        createdAt: row.created_at,
        updatedAt: row.updated_at
      };
    } catch (error) {
      console.error("Error creating profession:", error);
      throw error;
    }
  }

  async updateProfession(id: number, professionData: any): Promise<any | null> {
    try {
      await this.ensureProfessionTableExists();
      const updateFields = [];
      const updateValues = [];

      if (professionData.name !== undefined) {
        updateFields.push('name = ?');
        updateValues.push(professionData.name);
      }
      if (professionData.category !== undefined) {
        updateFields.push('category = ?');
        updateValues.push(professionData.category);
      }
      if (professionData.isActive !== undefined) {
        updateFields.push('is_active = ?');
        updateValues.push(professionData.isActive);
      }
      if (professionData.users !== undefined) {
        updateFields.push('users = ?');
        updateValues.push(professionData.users);
      }

      if (updateFields.length === 0) {
        const [profession] = await connection.execute('SELECT * FROM profession WHERE id = ?', [id]);
        return (profession as any[])[0] || null;
      }

      updateValues.push(id);
      await connection.execute(
        `UPDATE profession SET ${updateFields.join(', ')} WHERE id = ?`,
        updateValues
      );

      const [updatedProfession] = await connection.execute('SELECT * FROM profession WHERE id = ?', [id]);
      const row = (updatedProfession as any[])[0];

      return row ? {
        id: row.id,
        name: row.name,
        category: row.category,
        isActive: row.is_active,
        users: row.users,
        createdAt: row.created_at,
        updatedAt: row.updated_at
      } : null;
    } catch (error) {
      console.error("Error updating profession:", error);
      throw error;
    }
  }

  async deleteProfession(id: number): Promise<boolean> {
    try {
      await this.ensureProfessionTableExists();
      const [result] = await connection.execute('DELETE FROM profession WHERE id = ?', [id]);
      return (result as any).affectedRows > 0;
    } catch (error) {
      console.error("Error deleting profession:", error);
      throw error;
    }
  }

  async executeQuery(query: string): Promise<any> {
    try {
      console.log("Executing query:", query);
      const result = await connection.execute(query);
      return result;
    } catch (error) {
      console.error("Error executing query:", error);
      throw error;
    }
  }

  // Corporate Feature Methods Implementation

  // Franchise Holder Management
  async createFranchiseHolder(data: InsertFranchiseHolder): Promise<FranchiseHolder> {
    try {
      const result = await db.insert(franchiseHolder).values(data);
      const [newRecord] = await db.select().from(franchiseHolder).where(eq(franchiseHolder.id, result[0].insertId));
      return newRecord;
    } catch (error) {
      console.error("Error creating franchise holder:", error);
      throw error;
    }
  }

  async getFranchiseHolders(groupId?: number): Promise<any[]> {
    try {
      const query = `
        SELECT fh.*, u.username, u.first_name, u.last_name, u.email, u.phone,
               c.country as country_name, s.state as state_name, d.district as district_name
        FROM franchise_holder fh
        LEFT JOIN users u ON fh.user_id = u.id
        LEFT JOIN country_tbl c ON fh.country = c.id
        LEFT JOIN state_tbl s ON fh.state = s.id
        LEFT JOIN district_tbl d ON fh.district = d.id
        ${groupId ? 'WHERE u.group_id = ?' : ''}
        ORDER BY fh.created_at DESC
      `;
      const [rows] = await connection.execute(query, groupId ? [groupId] : []);
      return rows as any[];
    } catch (error) {
      console.error("Error fetching franchise holders:", error);
      throw error;
    }
  }

  async getFranchiseHoldersByCountry(countryId: number, groupId?: number): Promise<any[]> {
    try {
      const query = `
        SELECT fh.*, u.username, u.first_name, u.last_name, u.email, u.phone,
               c.country as country_name, s.state as state_name, d.district as district_name
        FROM franchise_holder fh
        LEFT JOIN users u ON fh.user_id = u.id
        LEFT JOIN country_tbl c ON fh.country = c.id
        LEFT JOIN state_tbl s ON fh.state = s.id
        LEFT JOIN district_tbl d ON fh.district = d.id
        WHERE fh.country = ? ${groupId ? 'AND u.group_id = ?' : ''}
        ORDER BY fh.created_at DESC
      `;
      const params = groupId ? [countryId, groupId] : [countryId];
      const [rows] = await connection.execute(query, params);
      return rows as any[];
    } catch (error) {
      console.error("Error fetching franchise holders by country:", error);
      throw error;
    }
  }

  async updateFranchiseHolder(id: number, data: Partial<InsertFranchiseHolder>): Promise<FranchiseHolder | null> {
    try {
      await db.update(franchiseHolder).set(data).where(eq(franchiseHolder.id, id));
      const [updated] = await db.select().from(franchiseHolder).where(eq(franchiseHolder.id, id));
      return updated || null;
    } catch (error) {
      console.error("Error updating franchise holder:", error);
      throw error;
    }
  }

  async deleteFranchiseHolder(id: number): Promise<boolean> {
    try {
      const result = await db.delete(franchiseHolder).where(eq(franchiseHolder.id, id));
      return result[0].affectedRows > 0;
    } catch (error) {
      console.error("Error deleting franchise holder:", error);
      throw error;
    }
  }

  // Corporate Ads Management
  async createCorporateAd(data: InsertCorporateAd): Promise<CorporateAd> {
    try {
      const result = await db.insert(corporateAds).values(data);
      const [newRecord] = await db.select().from(corporateAds).where(eq(corporateAds.id, result[0].insertId));
      return newRecord;
    } catch (error) {
      console.error("Error creating corporate ad:", error);
      throw error;
    }
  }

  async getCorporateAds(userId?: number, adType?: string): Promise<CorporateAd[]> {
    try {
      let query = db.select().from(corporateAds);

      if (userId && adType) {
        query = query.where(eq(corporateAds.userId, userId) && eq(corporateAds.adType, adType));
      } else if (userId) {
        query = query.where(eq(corporateAds.userId, userId));
      } else if (adType) {
        query = query.where(eq(corporateAds.adType, adType));
      }

      return await query;
    } catch (error) {
      console.error("Error fetching corporate ads:", error);
      throw error;
    }
  }

  async updateCorporateAd(id: number, data: Partial<InsertCorporateAd>): Promise<CorporateAd | null> {
    try {
      await db.update(corporateAds).set(data).where(eq(corporateAds.id, id));
      const [updated] = await db.select().from(corporateAds).where(eq(corporateAds.id, id));
      return updated || null;
    } catch (error) {
      console.error("Error updating corporate ad:", error);
      throw error;
    }
  }

  async deleteCorporateAd(id: number): Promise<boolean> {
    try {
      const result = await db.delete(corporateAds).where(eq(corporateAds.id, id));
      return result[0].affectedRows > 0;
    } catch (error) {
      console.error("Error deleting corporate ad:", error);
      throw error;
    }
  }

  // Popup Ads Management
  async createPopupAd(data: InsertPopupAd): Promise<PopupAd> {
    try {
      const result = await db.insert(popupAds).values(data);
      const [newRecord] = await db.select().from(popupAds).where(eq(popupAds.id, result[0].insertId));
      return newRecord;
    } catch (error) {
      console.error("Error creating popup ad:", error);
      throw error;
    }
  }

  async getPopupAds(userId?: number): Promise<PopupAd[]> {
    try {
      let query = db.select().from(popupAds);
      if (userId) {
        query = query.where(eq(popupAds.userId, userId));
      }
      return await query;
    } catch (error) {
      console.error("Error fetching popup ads:", error);
      throw error;
    }
  }

  async updatePopupAd(id: number, data: Partial<InsertPopupAd>): Promise<PopupAd | null> {
    try {
      await db.update(popupAds).set(data).where(eq(popupAds.id, id));
      const [updated] = await db.select().from(popupAds).where(eq(popupAds.id, id));
      return updated || null;
    } catch (error) {
      console.error("Error updating popup ad:", error);
      throw error;
    }
  }

  async deletePopupAd(id: number): Promise<boolean> {
    try {
      const result = await db.delete(popupAds).where(eq(popupAds.id, id));
      return result[0].affectedRows > 0;
    } catch (error) {
      console.error("Error deleting popup ad:", error);
      throw error;
    }
  }

  // Terms and Conditions Management
  async createTermsConditions(data: InsertTermsCondition): Promise<TermsCondition> {
    try {
      const result = await db.insert(termsConditions).values(data);
      const [newRecord] = await db.select().from(termsConditions).where(eq(termsConditions.id, result[0].insertId));
      return newRecord;
    } catch (error) {
      console.error("Error creating terms and conditions:", error);
      throw error;
    }
  }

  async getTermsConditions(userId?: number): Promise<TermsCondition[]> {
    try {
      let query = db.select().from(termsConditions);
      if (userId) {
        query = query.where(eq(termsConditions.userId, userId));
      }
      return await query;
    } catch (error) {
      console.error("Error fetching terms and conditions:", error);
      throw error;
    }
  }

  async updateTermsConditions(id: number, data: Partial<InsertTermsCondition>): Promise<TermsCondition | null> {
    try {
      await db.update(termsConditions).set(data).where(eq(termsConditions.id, id));
      const [updated] = await db.select().from(termsConditions).where(eq(termsConditions.id, id));
      return updated || null;
    } catch (error) {
      console.error("Error updating terms and conditions:", error);
      throw error;
    }
  }

  async deleteTermsConditions(id: number): Promise<boolean> {
    try {
      const result = await db.delete(termsConditions).where(eq(termsConditions.id, id));
      return result[0].affectedRows > 0;
    } catch (error) {
      console.error("Error deleting terms and conditions:", error);
      throw error;
    }
  }

  // About Us Management
  async createAboutUs(data: InsertAboutUs): Promise<AboutUs> {
    try {
      const result = await db.insert(aboutUs).values(data);
      const [newRecord] = await db.select().from(aboutUs).where(eq(aboutUs.id, result[0].insertId));
      return newRecord;
    } catch (error) {
      console.error("Error creating about us:", error);
      throw error;
    }
  }

  async getAboutUs(groupId?: number): Promise<AboutUs[]> {
    try {
      let query = db.select().from(aboutUs);
      if (groupId !== undefined) {
        query = query.where(eq(aboutUs.groupId, groupId));
      }
      return await query;
    } catch (error) {
      console.error("Error fetching about us:", error);
      throw error;
    }
  }

  async updateAboutUs(id: number, data: Partial<InsertAboutUs>): Promise<AboutUs | null> {
    try {
      await db.update(aboutUs).set(data).where(eq(aboutUs.id, id));
      const [updated] = await db.select().from(aboutUs).where(eq(aboutUs.id, id));
      return updated || null;
    } catch (error) {
      console.error("Error updating about us:", error);
      throw error;
    }
  }

  async deleteAboutUs(id: number): Promise<boolean> {
    try {
      const result = await db.delete(aboutUs).where(eq(aboutUs.id, id));
      return result[0].affectedRows > 0;
    } catch (error) {
      console.error("Error deleting about us:", error);
      throw error;
    }
  }

  // Awards Management
  async createAward(data: InsertAward): Promise<Award> {
    try {
      const result = await db.insert(awards).values(data);
      const [newRecord] = await db.select().from(awards).where(eq(awards.id, result[0].insertId));
      return newRecord;
    } catch (error) {
      console.error("Error creating award:", error);
      throw error;
    }
  }

  async getAwards(groupId?: number): Promise<Award[]> {
    try {
      let query = db.select().from(awards);
      if (groupId !== undefined) {
        query = query.where(eq(awards.groupId, groupId));
      }
      return await query;
    } catch (error) {
      console.error("Error fetching awards:", error);
      throw error;
    }
  }

  async updateAward(id: number, data: Partial<InsertAward>): Promise<Award | null> {
    try {
      await db.update(awards).set(data).where(eq(awards.id, id));
      const [updated] = await db.select().from(awards).where(eq(awards.id, id));
      return updated || null;
    } catch (error) {
      console.error("Error updating award:", error);
      throw error;
    }
  }

  async deleteAward(id: number): Promise<boolean> {
    try {
      const result = await db.delete(awards).where(eq(awards.id, id));
      return result[0].affectedRows > 0;
    } catch (error) {
      console.error("Error deleting award:", error);
      throw error;
    }
  }

  // Gallery Management
  async createGallery(data: InsertGallery): Promise<Gallery> {
    try {
      const result = await db.insert(gallery).values(data);
      const [newRecord] = await db.select().from(gallery).where(eq(gallery.id, result[0].insertId));
      return newRecord;
    } catch (error) {
      console.error("Error creating gallery:", error);
      throw error;
    }
  }

  async getGalleries(groupId?: number): Promise<Gallery[]> {
    try {
      let query = db.select().from(gallery);
      if (groupId !== undefined) {
        query = query.where(eq(gallery.groupId, groupId));
      }
      return await query;
    } catch (error) {
      console.error("Error fetching galleries:", error);
      throw error;
    }
  }

  async updateGallery(id: number, data: Partial<InsertGallery>): Promise<Gallery | null> {
    try {
      await db.update(gallery).set(data).where(eq(gallery.id, id));
      const [updated] = await db.select().from(gallery).where(eq(gallery.id, id));
      return updated || null;
    } catch (error) {
      console.error("Error updating gallery:", error);
      throw error;
    }
  }

  async deleteGallery(id: number): Promise<boolean> {
    try {
      const result = await db.delete(gallery).where(eq(gallery.id, id));
      return result[0].affectedRows > 0;
    } catch (error) {
      console.error("Error deleting gallery:", error);
      throw error;
    }
  }

  async addGalleryImages(galleryId: number, images: any[]): Promise<any[]> {
    try {
      const insertData = images.map(img => ({
        galleryId,
        groupId: img.groupId || 0,
        imageName: img.imageName,
        imageDescription: img.imageDescription || null
      }));

      const result = await db.insert(galleryImages).values(insertData);
      return result;
    } catch (error) {
      console.error("Error adding gallery images:", error);
      throw error;
    }
  }

  async getGalleryImages(galleryId: number): Promise<any[]> {
    try {
      return await db.select().from(galleryImages).where(eq(galleryImages.galleryId, galleryId));
    } catch (error) {
      console.error("Error fetching gallery images:", error);
      throw error;
    }
  }

  async deleteGalleryImage(id: number): Promise<boolean> {
    try {
      const result = await db.delete(galleryImages).where(eq(galleryImages.id, id));
      return result[0].affectedRows > 0;
    } catch (error) {
      console.error("Error deleting gallery image:", error);
      throw error;
    }
  }

  // Contact Us Management
  async createContactUs(data: InsertContactUs): Promise<ContactUs> {
    try {
      const result = await db.insert(contactUs).values(data);
      const [newRecord] = await db.select().from(contactUs).where(eq(contactUs.id, result[0].insertId));
      return newRecord;
    } catch (error) {
      console.error("Error creating contact us:", error);
      throw error;
    }
  }

  async getContactUs(groupId?: number): Promise<ContactUs[]> {
    try {
      let query = db.select().from(contactUs);
      if (groupId !== undefined) {
        query = query.where(eq(contactUs.groupId, groupId));
      }
      return await query;
    } catch (error) {
      console.error("Error fetching contact us:", error);
      throw error;
    }
  }

  async updateContactUs(id: number, data: Partial<InsertContactUs>): Promise<ContactUs | null> {
    try {
      await db.update(contactUs).set(data).where(eq(contactUs.id, id));
      const [updated] = await db.select().from(contactUs).where(eq(contactUs.id, id));
      return updated || null;
    } catch (error) {
      console.error("Error updating contact us:", error);
      throw error;
    }
  }

  async deleteContactUs(id: number): Promise<boolean> {
    try {
      const result = await db.delete(contactUs).where(eq(contactUs.id, id));
      return result[0].affectedRows > 0;
    } catch (error) {
      console.error("Error deleting contact us:", error);
      throw error;
    }
  }

  // Social Links Management
  async createSocialLink(data: InsertSocialLink): Promise<SocialLink> {
    try {
      const result = await db.insert(socialLinks).values(data);
      const [newRecord] = await db.select().from(socialLinks).where(eq(socialLinks.id, result[0].insertId));
      return newRecord;
    } catch (error) {
      console.error("Error creating social link:", error);
      throw error;
    }
  }

  async getSocialLinks(groupId?: number): Promise<SocialLink[]> {
    try {
      let query = db.select().from(socialLinks);
      if (groupId !== undefined) {
        query = query.where(eq(socialLinks.groupId, groupId));
      }
      return await query;
    } catch (error) {
      console.error("Error fetching social links:", error);
      throw error;
    }
  }

  async updateSocialLink(id: number, data: Partial<InsertSocialLink>): Promise<SocialLink | null> {
    try {
      await db.update(socialLinks).set(data).where(eq(socialLinks.id, id));
      const [updated] = await db.select().from(socialLinks).where(eq(socialLinks.id, id));
      return updated || null;
    } catch (error) {
      console.error("Error updating social link:", error);
      throw error;
    }
  }

  async deleteSocialLink(id: number): Promise<boolean> {
    try {
      const result = await db.delete(socialLinks).where(eq(socialLinks.id, id));
      return result[0].affectedRows > 0;
    } catch (error) {
      console.error("Error deleting social link:", error);
      throw error;
    }
  }

  // Support System - Feedback Management
  async createFeedback(data: InsertFeedbackSuggestion): Promise<FeedbackSuggestion> {
    try {
      const result = await db.insert(feedbackSuggestions).values(data);
      const [newRecord] = await db.select().from(feedbackSuggestions).where(eq(feedbackSuggestions.id, result[0].insertId));
      return newRecord;
    } catch (error) {
      console.error("Error creating feedback:", error);
      throw error;
    }
  }

  async getFeedbacks(userId?: number): Promise<FeedbackSuggestion[]> {
    try {
      let query = db.select().from(feedbackSuggestions);
      if (userId) {
        query = query.where(eq(feedbackSuggestions.userId, userId));
      }
      return await query;
    } catch (error) {
      console.error("Error fetching feedbacks:", error);
      throw error;
    }
  }

  async updateFeedback(id: number, data: Partial<InsertFeedbackSuggestion>): Promise<FeedbackSuggestion | null> {
    try {
      await db.update(feedbackSuggestions).set(data).where(eq(feedbackSuggestions.id, id));
      const [updated] = await db.select().from(feedbackSuggestions).where(eq(feedbackSuggestions.id, id));
      return updated || null;
    } catch (error) {
      console.error("Error updating feedback:", error);
      throw error;
    }
  }

  async deleteFeedback(id: number): Promise<boolean> {
    try {
      const result = await db.delete(feedbackSuggestions).where(eq(feedbackSuggestions.id, id));
      return result[0].affectedRows > 0;
    } catch (error) {
      console.error("Error deleting feedback:", error);
      throw error;
    }
  }

  // Application Forms Management
  async getFranchiseApplications(): Promise<any[]> {
    try {
      return await db.select().from(franchiseApplications).orderBy(franchiseApplications.submittedAt);
    } catch (error) {
      console.error("Error fetching franchise applications:", error);
      throw error;
    }
  }

  async getJobApplications(): Promise<any[]> {
    try {
      return await db.select().from(jobApplications).orderBy(jobApplications.submittedAt);
    } catch (error) {
      console.error("Error fetching job applications:", error);
      throw error;
    }
  }

  async getEnquiryForms(): Promise<any[]> {
    try {
      return await db.select().from(enquiryForms).orderBy(enquiryForms.submittedAt);
    } catch (error) {
      console.error("Error fetching enquiry forms:", error);
      throw error;
    }
  }

  async updateApplicationStatus(table: string, id: number, status: string): Promise<boolean> {
    try {
      let query;
      switch (table) {
        case 'franchise_applications':
          query = db.update(franchiseApplications).set({ status }).where(eq(franchiseApplications.id, id));
          break;
        case 'job_applications':
          query = db.update(jobApplications).set({ status }).where(eq(jobApplications.id, id));
          break;
        case 'enquiry_forms':
          query = db.update(enquiryForms).set({ status }).where(eq(enquiryForms.id, id));
          break;
        default:
          throw new Error(`Unknown table: ${table}`);
      }

      const result = await query;
      return result[0].affectedRows > 0;
    } catch (error) {
      console.error("Error updating application status:", error);
      throw error;
    }
  }
}

export const mysqlStorage = new MySQLStorage();
