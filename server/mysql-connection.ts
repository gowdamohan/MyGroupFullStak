import mysql from 'mysql2/promise';

// MySQL connection configuration
const dbConfig = {
  host: 'localhost',
  port: 3306,
  user: 'root',
  password: 'admin', // Using the port as password as specified
  database: 'my_group'
};

export async function testMySQLConnection() {
  try {
    console.log('🔄 Attempting to connect to MySQL database...');
    console.log(`Host: ${dbConfig.host}:${dbConfig.port}`);
    console.log(`Database: ${dbConfig.database}`);
    console.log(`User: ${dbConfig.user}`);

    const connection = await mysql.createConnection(dbConfig);
    console.log('✅ Successfully connected to MySQL database!');

    // Test basic query
    const [rows] = await connection.execute('SELECT 1 as test');
    console.log('✅ Test query successful:', rows);

    await connection.end();
    return true;
  } catch (error) {
    console.error('❌ MySQL connection failed:', error);
    return false;
  }
}

export async function analyzeDatabaseSchema() {
  try {
    console.log('🔍 Analyzing database schema...');
    const connection = await mysql.createConnection(dbConfig);
    
    // Get all tables
    const [tables] = await connection.execute(`
      SELECT TABLE_NAME 
      FROM INFORMATION_SCHEMA.TABLES 
      WHERE TABLE_SCHEMA = ?
    `, [dbConfig.database]);
    
    console.log(`📊 Found ${(tables as any[]).length} tables in database '${dbConfig.database}':`);
    
    const schemaInfo: any = {};
    
    for (const table of tables as any[]) {
      const tableName = table.TABLE_NAME;
      console.log(`\n📋 Table: ${tableName}`);
      
      // Get columns for this table
      const [columns] = await connection.execute(`
        SELECT 
          COLUMN_NAME,
          DATA_TYPE,
          IS_NULLABLE,
          COLUMN_DEFAULT,
          COLUMN_KEY,
          EXTRA,
          CHARACTER_MAXIMUM_LENGTH,
          NUMERIC_PRECISION,
          NUMERIC_SCALE
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?
        ORDER BY ORDINAL_POSITION
      `, [dbConfig.database, tableName]);
      
      schemaInfo[tableName] = columns;
      
      console.log('   Columns:');
      for (const column of columns as any[]) {
        const nullable = column.IS_NULLABLE === 'YES' ? 'NULL' : 'NOT NULL';
        const key = column.COLUMN_KEY ? ` [${column.COLUMN_KEY}]` : '';
        const extra = column.EXTRA ? ` ${column.EXTRA}` : '';
        const length = column.CHARACTER_MAXIMUM_LENGTH ? `(${column.CHARACTER_MAXIMUM_LENGTH})` : '';
        const precision = column.NUMERIC_PRECISION && column.NUMERIC_SCALE ? 
          `(${column.NUMERIC_PRECISION},${column.NUMERIC_SCALE})` : '';
        
        console.log(`     - ${column.COLUMN_NAME}: ${column.DATA_TYPE}${length}${precision} ${nullable}${key}${extra}`);
      }
    }
    
    await connection.end();
    return schemaInfo;
  } catch (error) {
    console.error('❌ Schema analysis failed:', error);
    throw error;
  }
}

export async function createMySQLConnection() {
  return await mysql.createConnection(dbConfig);
}

export { dbConfig };
