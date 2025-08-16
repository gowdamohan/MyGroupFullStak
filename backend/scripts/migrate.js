#!/usr/bin/env node

const { initializeDatabase } = require('../config/database');

async function runMigrations() {
  try {
    console.log('🔄 Starting database migrations...');
    await initializeDatabase();
    console.log('✅ Database migrations completed successfully!');
    process.exit(0);
  } catch (error) {
    console.error('❌ Migration failed:', error);
    process.exit(1);
  }
}

// Run migrations if this script is executed directly
if (require.main === module) {
  runMigrations();
}

module.exports = runMigrations;