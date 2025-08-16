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

export type InsertUser = z.infer<typeof insertUserSchema>;
export type User = typeof users.$inferSelect;
export type Group = typeof groups.$inferSelect;
export type Login = z.infer<typeof loginSchema>;
export type AdminLogin = z.infer<typeof adminLoginSchema>;
export type Registration = z.infer<typeof registrationSchema>;
