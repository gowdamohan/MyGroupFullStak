const db = require('../config/database');

class UserRegistrationData {
  static async create(registrationData) {
    try {
      const {
        user_id,
        registration_ip,
        user_agent,
        referral_source,
        utm_source,
        utm_medium,
        utm_campaign
      } = registrationData;

      const [result] = await db.execute(
        `INSERT INTO user_registration_data 
         (user_id, registration_ip, user_agent, referral_source, utm_source, utm_medium, utm_campaign, created_at) 
         VALUES (?, ?, ?, ?, ?, ?, ?, NOW())`,
        [user_id, registration_ip, user_agent, referral_source, utm_source, utm_medium, utm_campaign]
      );

      return {
        id: result.insertId,
        user_id,
        registration_ip,
        user_agent
      };
    } catch (error) {
      throw error;
    }
  }

  static async findByUserId(userId) {
    try {
      const [rows] = await db.execute(
        'SELECT * FROM user_registration_data WHERE user_id = ?',
        [userId]
      );
      return rows[0] || null;
    } catch (error) {
      throw error;
    }
  }

  static async getRegistrationStats(startDate, endDate) {
    try {
      const [rows] = await db.execute(
        `SELECT 
           DATE(created_at) as registration_date,
           COUNT(*) as total_registrations,
           COUNT(DISTINCT registration_ip) as unique_ips,
           COUNT(CASE WHEN referral_source IS NOT NULL THEN 1 END) as referred_registrations
         FROM user_registration_data 
         WHERE created_at BETWEEN ? AND ?
         GROUP BY DATE(created_at)
         ORDER BY registration_date DESC`,
        [startDate, endDate]
      );
      return rows;
    } catch (error) {
      throw error;
    }
  }

  static async getRegistrationsBySource() {
    try {
      const [rows] = await db.execute(
        `SELECT 
           COALESCE(referral_source, 'direct') as source,
           COUNT(*) as registrations
         FROM user_registration_data 
         GROUP BY referral_source
         ORDER BY registrations DESC`
      );
      return rows;
    } catch (error) {
      throw error;
    }
  }

  static async getRegistrationsByUTM() {
    try {
      const [rows] = await db.execute(
        `SELECT 
           utm_source,
           utm_medium,
           utm_campaign,
           COUNT(*) as registrations
         FROM user_registration_data 
         WHERE utm_source IS NOT NULL
         GROUP BY utm_source, utm_medium, utm_campaign
         ORDER BY registrations DESC`
      );
      return rows;
    } catch (error) {
      throw error;
    }
  }

  static async updateRegistrationData(userId, updateData) {
    try {
      const fields = [];
      const values = [];
      
      for (const [key, value] of Object.entries(updateData)) {
        if (value !== undefined && key !== 'user_id') {
          fields.push(`${key} = ?`);
          values.push(value);
        }
      }
      
      if (fields.length === 0) {
        throw new Error('No fields to update');
      }
      
      values.push(userId);
      
      const [result] = await db.execute(
        `UPDATE user_registration_data SET ${fields.join(', ')} WHERE user_id = ?`,
        values
      );
      
      return result.affectedRows > 0;
    } catch (error) {
      throw error;
    }
  }
}

module.exports = UserRegistrationData;