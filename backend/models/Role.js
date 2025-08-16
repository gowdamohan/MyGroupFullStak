const db = require('../config/database');

class Role {
  static async findById(id) {
    try {
      const [rows] = await db.execute(
        'SELECT * FROM roles WHERE id = ?',
        [id]
      );
      return rows[0] || null;
    } catch (error) {
      throw error;
    }
  }

  static async findByName(name) {
    try {
      const [rows] = await db.execute(
        'SELECT * FROM roles WHERE name = ?',
        [name]
      );
      return rows[0] || null;
    } catch (error) {
      throw error;
    }
  }

  static async getAllRoles() {
    try {
      const [rows] = await db.execute(
        'SELECT * FROM roles ORDER BY hierarchy_level ASC'
      );
      return rows;
    } catch (error) {
      throw error;
    }
  }

  static async create(roleData) {
    try {
      const { name, description, hierarchy_level, permissions } = roleData;
      
      const [result] = await db.execute(
        `INSERT INTO roles (name, description, hierarchy_level, permissions, created_at, updated_at) 
         VALUES (?, ?, ?, ?, NOW(), NOW())`,
        [name, description, hierarchy_level, JSON.stringify(permissions)]
      );

      return {
        id: result.insertId,
        name,
        description,
        hierarchy_level
      };
    } catch (error) {
      throw error;
    }
  }

  static async updateRole(roleId, updateData) {
    try {
      const fields = [];
      const values = [];
      
      for (const [key, value] of Object.entries(updateData)) {
        if (value !== undefined) {
          if (key === 'permissions') {
            fields.push(`${key} = ?`);
            values.push(JSON.stringify(value));
          } else {
            fields.push(`${key} = ?`);
            values.push(value);
          }
        }
      }
      
      if (fields.length === 0) {
        throw new Error('No fields to update');
      }
      
      fields.push('updated_at = NOW()');
      values.push(roleId);
      
      const [result] = await db.execute(
        `UPDATE roles SET ${fields.join(', ')} WHERE id = ?`,
        values
      );
      
      return result.affectedRows > 0;
    } catch (error) {
      throw error;
    }
  }

  static async deleteRole(roleId) {
    try {
      // Check if any users have this role
      const [userRows] = await db.execute(
        'SELECT COUNT(*) as count FROM users WHERE role_id = ? AND deleted_at IS NULL',
        [roleId]
      );
      
      if (userRows[0].count > 0) {
        throw new Error('Cannot delete role that is assigned to users');
      }
      
      const [result] = await db.execute(
        'DELETE FROM roles WHERE id = ?',
        [roleId]
      );
      
      return result.affectedRows > 0;
    } catch (error) {
      throw error;
    }
  }

  static async getRolePermissions(roleId) {
    try {
      const [rows] = await db.execute(
        'SELECT permissions FROM roles WHERE id = ?',
        [roleId]
      );
      
      if (rows[0] && rows[0].permissions) {
        return JSON.parse(rows[0].permissions);
      }
      
      return [];
    } catch (error) {
      throw error;
    }
  }
}

module.exports = Role;