const db = require('../config/database');

class User {
  static async findByUsername(username) {
    try {
      const [rows] = await db.execute(
        'SELECT * FROM users WHERE username = ? AND deleted_at IS NULL',
        [username]
      );
      return rows[0] || null;
    } catch (error) {
      throw error;
    }
  }

  static async findByEmail(email) {
    try {
      const [rows] = await db.execute(
        'SELECT * FROM users WHERE email = ? AND deleted_at IS NULL',
        [email]
      );
      return rows[0] || null;
    } catch (error) {
      throw error;
    }
  }

  static async findById(id) {
    try {
      const [rows] = await db.execute(
        'SELECT * FROM users WHERE id = ? AND deleted_at IS NULL',
        [id]
      );
      return rows[0] || null;
    } catch (error) {
      throw error;
    }
  }

  static async create(userData) {
    try {
      const {
        username,
        password,
        first_name,
        last_name,
        email,
        phone,
        role_id
      } = userData;

      const [result] = await db.execute(
        `INSERT INTO users (username, password, first_name, last_name, email, phone, role_id, created_at, updated_at) 
         VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())`,
        [username, password, first_name, last_name, email, phone, role_id]
      );

      return {
        id: result.insertId,
        username,
        email,
        first_name,
        last_name
      };
    } catch (error) {
      throw error;
    }
  }

  static async updateLastLogin(userId) {
    try {
      await db.execute(
        'UPDATE users SET last_login = NOW(), updated_at = NOW() WHERE id = ?',
        [userId]
      );
    } catch (error) {
      throw error;
    }
  }

  static async getAllUsers(limit = 50, offset = 0) {
    try {
      const [rows] = await db.execute(
        `SELECT u.*, r.name as role_name 
         FROM users u 
         LEFT JOIN roles r ON u.role_id = r.id 
         WHERE u.deleted_at IS NULL 
         ORDER BY u.created_at DESC 
         LIMIT ? OFFSET ?`,
        [limit, offset]
      );
      return rows;
    } catch (error) {
      throw error;
    }
  }

  static async getUsersByRole(roleName) {
    try {
      const [rows] = await db.execute(
        `SELECT u.*, r.name as role_name 
         FROM users u 
         INNER JOIN roles r ON u.role_id = r.id 
         WHERE r.name = ? AND u.deleted_at IS NULL 
         ORDER BY u.created_at DESC`,
        [roleName]
      );
      return rows;
    } catch (error) {
      throw error;
    }
  }

  static async updateUser(userId, updateData) {
    try {
      const fields = [];
      const values = [];
      
      for (const [key, value] of Object.entries(updateData)) {
        if (value !== undefined) {
          fields.push(`${key} = ?`);
          values.push(value);
        }
      }
      
      if (fields.length === 0) {
        throw new Error('No fields to update');
      }
      
      fields.push('updated_at = NOW()');
      values.push(userId);
      
      const [result] = await db.execute(
        `UPDATE users SET ${fields.join(', ')} WHERE id = ?`,
        values
      );
      
      return result.affectedRows > 0;
    } catch (error) {
      throw error;
    }
  }

  static async softDelete(userId) {
    try {
      const [result] = await db.execute(
        'UPDATE users SET deleted_at = NOW(), updated_at = NOW() WHERE id = ?',
        [userId]
      );
      return result.affectedRows > 0;
    } catch (error) {
      throw error;
    }
  }
}

module.exports = User;