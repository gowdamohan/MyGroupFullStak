import { sql } from "drizzle-orm";
import { mysqlTable, text, varchar, timestamp, boolean, int, tinyint } from "drizzle-orm/mysql-core";
import { createInsertSchema } from "drizzle-zod";
import { z } from "zod";

// Main users table matching the existing MySQL schema
export const users = mysqlTable("users", {
  id: int("id").primaryKey().autoincrement(),
  ipAddress: varchar("ip_address", { length: 15 }).notNull(),
  username: varchar("username", { length: 100 }),
  password: varchar("password", { length: 255 }).notNull(),
  salt: varchar("salt", { length: 255 }),
  email: varchar("email", { length: 100 }).notNull(),
  activationCode: varchar("activation_code", { length: 40 }),
  forgottenPasswordCode: varchar("forgotten_password_code", { length: 40 }),
  forgottenPasswordTime: int("forgotten_password_time"),
  rememberCode: varchar("remember_code", { length: 40 }),
  createdOn: int("created_on").notNull(),
  lastLogin: int("last_login"),
  active: tinyint("active"),
  firstName: varchar("first_name", { length: 50 }),
  lastName: varchar("last_name", { length: 50 }),
  company: varchar("company", { length: 100 }),
  phone: varchar("phone", { length: 20 }),
  profileImg: varchar("profile_img", { length: 255 }),
  displayName: varchar("display_name", { length: 45 }),
  alterNumber: varchar("alter_number", { length: 45 }),
  groupId: int("group_id"),
  address: text("address"),
  identificationCode: varchar("identification_code", { length: 100 }),
  // Additional registration fields
  role: varchar("role", { length: 20 }),
  gender: varchar("gender", { length: 10 }),
  dateOfBirth: varchar("date_of_birth", { length: 10 }),
  country: varchar("country", { length: 50 }),
  state: varchar("state", { length: 50 }),
  district: varchar("district", { length: 50 }),
  education: varchar("education", { length: 100 }),
  profession: varchar("profession", { length: 100 }),
});

// Groups table for user roles/permissions
export const groups = mysqlTable("groups", {
  id: int("id").primaryKey().autoincrement(),
  name: varchar("name", { length: 20 }).notNull(),
  description: varchar("description", { length: 100 }).notNull(),
});

// User groups relationship table
export const usersGroups = mysqlTable("users_groups", {
  id: int("id").primaryKey().autoincrement(),
  userId: int("user_id").notNull(),
  groupId: int("group_id").notNull(),
});

// Existing group_create table (matches database structure)
export const groupCreate = mysqlTable("group_create", {
  id: int("id").primaryKey().autoincrement(),
  name: varchar("name", { length: 255 }),
  appsName: varchar("apps_name", { length: 255 }),
  orderBy: int("order_by"),
  code: varchar("code", { length: 45 }),
});

// Existing create_details table (matches database structure)
export const createDetails = mysqlTable("create_details", {
  id: int("id").primaryKey().autoincrement(),
  createId: int("create_id"),
  icon: varchar("icon", { length: 255 }),
  logo: varchar("logo", { length: 255 }),
  nameImage: varchar("name_image", { length: 255 }),
  backgroundColor: varchar("background_color", { length: 255 }),
  banner: varchar("banner", { length: 255 }),
  url: varchar("url", { length: 255 }),
});

export const insertUserSchema = createInsertSchema(users).omit({
  id: true,
  createdOn: true,
  lastLogin: true,
});

export const loginSchema = z.object({
  username: z.string().min(3, "Username must be at least 3 characters"),
  password: z.string().min(6, "Password must be at least 6 characters"),
});

export const adminLoginSchema = z.object({
  username: z.string().min(3, "Username must be at least 3 characters"),
  password: z.string().min(6, "Password must be at least 6 characters"),
});

export const registrationSchema = z.object({
  username: z.string().min(3, "Username must be at least 3 characters"),
  firstName: z.string().min(2, "First name must be at least 2 characters"),
  lastName: z.string().min(2, "Last name must be at least 2 characters"),
  email: z.string().email("Invalid email address"),
  phone: z.string().min(10, "Phone number must be at least 10 digits"),
  password: z.string().min(8, "Password must be at least 8 characters"),
  confirmPassword: z.string(),
}).refine((data) => data.password === data.confirmPassword, {
  message: "Passwords don't match",
  path: ["confirmPassword"],
});

// Registration Step 1 Schema (Basic Information)
export const registrationStep1Schema = z.object({
  username: z.string().min(3, "Username must be at least 3 characters"),
  firstName: z.string().min(2, "First name must be at least 2 characters"),
  lastName: z.string().min(2, "Last name must be at least 2 characters"),
  email: z.string().email("Invalid email address"),
  phone: z.string().min(10, "Phone number must be at least 10 digits"),
  password: z.string().min(8, "Password must be at least 8 characters"),
  confirmPassword: z.string(),
  role: z.enum(['user', 'admin', 'corporate', 'regional', 'branch']).default('user'),
}).refine((data) => data.password === data.confirmPassword, {
  message: "Passwords don't match",
  path: ["confirmPassword"],
});

// Registration Step 2 Schema (Additional Details)
export const registrationStep2Schema = z.object({
  gender: z.string().optional(),
  dateOfBirth: z.string().optional(),
  country: z.string().optional(),
  state: z.string().optional(),
  district: z.string().optional(),
  education: z.string().optional(),
  profession: z.string().optional(),
  company: z.string().optional(),
});

// Group create schema (for existing table)
export const groupCreateSchema = z.object({
  name: z.string().min(1, "Group name is required"),
  appsName: z.string().min(1, "App name is required"),
  orderBy: z.number().optional(),
  code: z.string().optional(),
});

export const insertGroupCreateSchema = createInsertSchema(groupCreate).omit({
  id: true,
});

// Create details schema (for existing table)
export const createDetailsSchema = z.object({
  createId: z.number().min(1, "Create ID is required"),
  icon: z.string().optional(),
  logo: z.string().optional(),
  nameImage: z.string().optional(),
  backgroundColor: z.string().optional(),
  banner: z.string().optional(),
  url: z.string().optional(),
});

export const insertCreateDetailsSchema = createInsertSchema(createDetails).omit({
  id: true,
});

// Change password schema
export const changePasswordSchema = z.object({
  oldPassword: z.string().min(1, "Old password is required"),
  newPassword: z.string().min(8, "New password must be at least 8 characters"),
  confirmPassword: z.string().min(1, "Confirm password is required"),
}).refine((data) => data.newPassword === data.confirmPassword, {
  message: "New passwords don't match",
  path: ["confirmPassword"],
});

// Content Management Tables
export const continentTbl = mysqlTable("continent_tbl", {
  id: int("id").primaryKey().autoincrement(),
  continent: varchar("continent", { length: 100 }),
  code: varchar("code", { length: 45 }),
  order: int("order"),
  status: tinyint("status").default(1),
});

export const countryTbl = mysqlTable("country_tbl", {
  id: int("id").primaryKey().autoincrement(),
  continentId: int("continent_id"),
  country: varchar("country", { length: 100 }),
  order: tinyint("order"),
  status: tinyint("status"),
  code: varchar("code", { length: 45 }),
  currency: varchar("currency", { length: 45 }),
  countryFlag: text("country_flag"),
  phoneCode: varchar("phone_code", { length: 100 }),
  nationality: varchar("nationality", { length: 100 }),
});

export const stateTbl = mysqlTable("state_tbl", {
  id: int("id").primaryKey().autoincrement(),
  state: varchar("state", { length: 100 }),
  countryId: int("country_id"),
  order: tinyint("order"),
  status: tinyint("status"),
  code: varchar("code", { length: 45 }),
});

export const districtTbl = mysqlTable("district_tbl", {
  id: int("id").primaryKey().autoincrement(),
  stateId: int("state_id"),
  district: varchar("district", { length: 100 }),
  order: tinyint("order"),
  status: tinyint("status"),
  code: varchar("code", { length: 45 }),
});

export const languageTbl = mysqlTable("language", {
  id: int("id").primaryKey().autoincrement(),
  name: varchar("name", { length: 100 }).notNull(),
  code: varchar("code", { length: 10 }).notNull(),
  isActive: tinyint("is_active").default(1),
  speakers: varchar("speakers", { length: 50 }),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow().onUpdateNow(),
});

// Content Management Schemas
export const continentSchema = z.object({
  continent: z.string().min(1, "Continent name is required"),
  code: z.string().min(1, "Continent code is required").max(45),
  order: z.number().default(0),
  status: z.number().default(1),
});

export const countrySchema = z.object({
  continentId: z.number().min(1, "Continent is required"),
  country: z.string().min(1, "Country name is required"),
  order: z.number().default(0),
  status: z.number().default(1),
  code: z.string().min(1, "Country code is required").max(45),
  currency: z.string().optional(),
  countryFlag: z.string().optional(),
  phoneCode: z.string().optional(),
  nationality: z.string().optional(),
});

export const stateSchema = z.object({
  countryId: z.number().min(1, "Country is required"),
  state: z.string().min(1, "State name is required"),
  order: z.number().default(0),
  status: z.number().default(1),
  code: z.string().min(1, "State code is required").max(45),
});

export const districtSchema = z.object({
  stateId: z.number().min(1, "State is required"),
  district: z.string().min(1, "District name is required"),
  order: z.number().default(0),
  status: z.number().default(1),
  code: z.string().min(1, "District code is required").max(45),
});

export const languageSchema = z.object({
  name: z.string().min(1, "Language name is required"),
  code: z.string().min(1, "Language code is required").max(10),
  isActive: z.number().default(1),
  speakers: z.string().optional(),
});

export type InsertUser = z.infer<typeof insertUserSchema>;
export type User = typeof users.$inferSelect;
export type Group = typeof groups.$inferSelect;
export type GroupCreate = typeof groupCreate.$inferSelect;
export type CreateDetails = typeof createDetails.$inferSelect;
export type Login = z.infer<typeof loginSchema>;
export type AdminLogin = z.infer<typeof adminLoginSchema>;
export type Registration = z.infer<typeof registrationSchema>;
export type RegistrationStep1 = z.infer<typeof registrationStep1Schema>;
export type RegistrationStep2 = z.infer<typeof registrationStep2Schema>;
export type GroupCreateInput = z.infer<typeof groupCreateSchema>;
export type CreateDetailsInput = z.infer<typeof createDetailsSchema>;
export type ChangePassword = z.infer<typeof changePasswordSchema>;

// Content Management Types
export type Continent = typeof continentTbl.$inferSelect;
export type InsertContinent = typeof continentTbl.$inferInsert;
export type Country = typeof countryTbl.$inferSelect;
export type InsertCountry = typeof countryTbl.$inferInsert;
export type State = typeof stateTbl.$inferSelect;
export type InsertState = typeof stateTbl.$inferInsert;
export type District = typeof districtTbl.$inferSelect;
export type InsertDistrict = typeof districtTbl.$inferInsert;
export type Language = typeof languageTbl.$inferSelect;
export type InsertLanguage = typeof languageTbl.$inferInsert;

export type ContinentInput = z.infer<typeof continentSchema>;
export type CountryInput = z.infer<typeof countrySchema>;
export type StateInput = z.infer<typeof stateSchema>;
export type DistrictInput = z.infer<typeof districtSchema>;
export type LanguageInput = z.infer<typeof languageSchema>;
