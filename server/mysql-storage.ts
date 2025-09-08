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
  type LanguageInput
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
  deleteUser(userId: number): Promise<boolean>;
  executeQuery(query: string): Promise<any>;
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
  async getAllCountries(): Promise<any[]> {
    try {
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
}

export const mysqlStorage = new MySQLStorage();
