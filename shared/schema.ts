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
  orderBy: int("order_by").default(0),
  status: tinyint("status").default(1),
  code: varchar("code", { length: 45 }),
  currency: varchar("currency", { length: 10 }),
  countryFlag: varchar("country_flag", { length: 255 }),
  phoneCode: varchar("phone_code", { length: 10 }),
  nationality: varchar("nationality", { length: 100 }),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow().onUpdateNow(),
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
  orderBy: z.number().default(0),
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

// Corporate Feature Tables

// Franchise holder table for managing head office, regional, and branch users
export const franchiseHolder = mysqlTable("franchise_holder", {
  id: int("id").primaryKey().autoincrement(),
  userId: int("user_id").notNull(),
  country: int("country"),
  state: int("state"),
  district: int("district"),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow().onUpdateNow(),
});

// Franchise staff details table
export const franchiseStaff = mysqlTable("franchise_staff", {
  id: int("id").primaryKey().autoincrement(),
  userId: int("user_id").notNull(),
  firstName: varchar("first_name", { length: 100 }),
  lastName: varchar("last_name", { length: 100 }),
  email: varchar("email", { length: 100 }),
  phone: varchar("phone", { length: 20 }),
  address: text("address"),
  position: varchar("position", { length: 100 }),
  department: varchar("department", { length: 100 }),
  joiningDate: varchar("joining_date", { length: 10 }),
  salary: varchar("salary", { length: 20 }),
  status: tinyint("status").default(1),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow().onUpdateNow(),
});

// Franchise staff documents table
export const franchiseStaffDocuments = mysqlTable("franchise_staff_documents", {
  id: int("id").primaryKey().autoincrement(),
  userId: int("user_id").notNull(),
  documentType: varchar("document_type", { length: 100 }),
  documentName: varchar("document_name", { length: 255 }),
  documentPath: varchar("document_path", { length: 500 }),
  uploadedAt: timestamp("uploaded_at").defaultNow(),
});

// Corporate ads management tables
export const corporateAds = mysqlTable("corporate_ads", {
  id: int("id").primaryKey().autoincrement(),
  userId: int("user_id").notNull(),
  adType: varchar("ad_type", { length: 50 }), // header, popup, main_page, company_header
  adPosition: varchar("ad_position", { length: 50 }), // ads1, ads2, ads3, side_ads, main_ads
  adTitle: varchar("ad_title", { length: 255 }),
  adImage: varchar("ad_image", { length: 500 }),
  adUrl: varchar("ad_url", { length: 500 }),
  adDescription: text("ad_description"),
  isActive: tinyint("is_active").default(1),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow().onUpdateNow(),
});

// Popup ads table
export const popupAds = mysqlTable("popup_ads", {
  id: int("id").primaryKey().autoincrement(),
  userId: int("user_id"),
  sideAds: varchar("side_ads", { length: 500 }),
  popupImage: varchar("popup_image", { length: 500 }),
  popupTitle: varchar("popup_title", { length: 255 }),
  popupContent: text("popup_content"),
  isActive: tinyint("is_active").default(1),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow().onUpdateNow(),
});

// Terms and conditions table
export const termsConditions = mysqlTable("terms_conditions", {
  id: int("id").primaryKey().autoincrement(),
  userId: int("user_id"),
  title: varchar("title", { length: 255 }),
  content: text("content"),
  version: varchar("version", { length: 20 }),
  isActive: tinyint("is_active").default(1),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow().onUpdateNow(),
});

// About us table
export const aboutUs = mysqlTable("about", {
  id: int("id").primaryKey().autoincrement(),
  groupId: int("group_id").default(0),
  title: varchar("title", { length: 255 }),
  content: text("content"),
  image: varchar("image", { length: 500 }),
  isActive: tinyint("is_active").default(1),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow().onUpdateNow(),
});

// Footer content tables for various sections
export const awards = mysqlTable("awards", {
  id: int("id").primaryKey().autoincrement(),
  groupId: int("group_id").default(0),
  title: varchar("title", { length: 255 }),
  content: text("content"),
  image: varchar("image", { length: 500 }),
  tagLine: varchar("tag_line", { length: 255 }),
  isActive: tinyint("is_active").default(1),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow().onUpdateNow(),
});

export const newsroom = mysqlTable("newsroom", {
  id: int("id").primaryKey().autoincrement(),
  groupId: int("group_id").default(0),
  title: varchar("title", { length: 255 }),
  content: text("content"),
  image: varchar("image", { length: 500 }),
  tagLine: varchar("tag_line", { length: 255 }),
  isActive: tinyint("is_active").default(1),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow().onUpdateNow(),
});

export const events = mysqlTable("events", {
  id: int("id").primaryKey().autoincrement(),
  groupId: int("group_id").default(0),
  title: varchar("title", { length: 255 }),
  content: text("content"),
  image: varchar("image", { length: 500 }),
  tagLine: varchar("tag_line", { length: 255 }),
  eventDate: varchar("event_date", { length: 20 }),
  eventLocation: varchar("event_location", { length: 255 }),
  isActive: tinyint("is_active").default(1),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow().onUpdateNow(),
});

export const careers = mysqlTable("careers", {
  id: int("id").primaryKey().autoincrement(),
  groupId: int("group_id").default(0),
  title: varchar("title", { length: 255 }),
  content: text("content"),
  image: varchar("image", { length: 500 }),
  tagLine: varchar("tag_line", { length: 255 }),
  isActive: tinyint("is_active").default(1),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow().onUpdateNow(),
});

export const clients = mysqlTable("clients", {
  id: int("id").primaryKey().autoincrement(),
  groupId: int("group_id").default(0),
  title: varchar("title", { length: 255 }),
  content: text("content"),
  image: varchar("image", { length: 500 }),
  tagLine: varchar("tag_line", { length: 255 }),
  isActive: tinyint("is_active").default(1),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow().onUpdateNow(),
});

export const milestones = mysqlTable("milestones", {
  id: int("id").primaryKey().autoincrement(),
  groupId: int("group_id").default(0),
  title: varchar("title", { length: 255 }),
  content: text("content"),
  image: varchar("image", { length: 500 }),
  tagLine: varchar("tag_line", { length: 255 }),
  milestoneDate: varchar("milestone_date", { length: 20 }),
  isActive: tinyint("is_active").default(1),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow().onUpdateNow(),
});

export const testimonials = mysqlTable("testimonials", {
  id: int("id").primaryKey().autoincrement(),
  groupId: int("group_id").default(0),
  title: varchar("title", { length: 255 }),
  content: text("content"),
  image: varchar("image", { length: 500 }),
  tagLine: varchar("tag_line", { length: 255 }),
  clientName: varchar("client_name", { length: 255 }),
  clientPosition: varchar("client_position", { length: 255 }),
  rating: tinyint("rating").default(5),
  isActive: tinyint("is_active").default(1),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow().onUpdateNow(),
});

// Gallery tables
export const gallery = mysqlTable("gallery", {
  id: int("id").primaryKey().autoincrement(),
  groupId: int("group_id").default(0),
  galleryName: varchar("gallery_name", { length: 255 }),
  description: text("description"),
  isActive: tinyint("is_active").default(1),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow().onUpdateNow(),
});

export const galleryImages = mysqlTable("gallery_images", {
  id: int("id").primaryKey().autoincrement(),
  galleryId: int("gallery_id").notNull(),
  groupId: int("group_id").default(0),
  imageName: varchar("image_name", { length: 500 }),
  imageDescription: text("image_description"),
  uploadedAt: timestamp("uploaded_at").defaultNow(),
});

// Contact us table
export const contactUs = mysqlTable("contact_us", {
  id: int("id").primaryKey().autoincrement(),
  groupId: int("group_id").default(0),
  companyName: varchar("company_name", { length: 255 }),
  address: text("address"),
  phone: varchar("phone", { length: 50 }),
  email: varchar("email", { length: 100 }),
  website: varchar("website", { length: 255 }),
  mapLocation: text("map_location"),
  workingHours: text("working_hours"),
  isActive: tinyint("is_active").default(1),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow().onUpdateNow(),
});

// Social media links table
export const socialLinks = mysqlTable("social_links", {
  id: int("id").primaryKey().autoincrement(),
  groupId: int("group_id").default(0),
  platform: varchar("platform", { length: 50 }), // facebook, twitter, instagram, linkedin, youtube
  url: varchar("url", { length: 500 }),
  icon: varchar("icon", { length: 255 }),
  isActive: tinyint("is_active").default(1),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow().onUpdateNow(),
});

// Privacy and policy table
export const privacyPolicy = mysqlTable("privacy_policy", {
  id: int("id").primaryKey().autoincrement(),
  groupId: int("group_id").default(0),
  title: varchar("title", { length: 255 }),
  content: text("content"),
  version: varchar("version", { length: 20 }),
  isActive: tinyint("is_active").default(1),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow().onUpdateNow(),
});

// Application forms tables
export const franchiseApplications = mysqlTable("franchise_applications", {
  id: int("id").primaryKey().autoincrement(),
  applicantName: varchar("applicant_name", { length: 255 }),
  email: varchar("email", { length: 100 }),
  phone: varchar("phone", { length: 20 }),
  address: text("address"),
  businessExperience: text("business_experience"),
  investmentCapacity: varchar("investment_capacity", { length: 100 }),
  preferredLocation: varchar("preferred_location", { length: 255 }),
  message: text("message"),
  status: varchar("status", { length: 50 }).default('pending'),
  submittedAt: timestamp("submitted_at").defaultNow(),
});

export const jobApplications = mysqlTable("job_applications", {
  id: int("id").primaryKey().autoincrement(),
  applicantName: varchar("applicant_name", { length: 255 }),
  email: varchar("email", { length: 100 }),
  phone: varchar("phone", { length: 20 }),
  position: varchar("position", { length: 255 }),
  experience: text("experience"),
  education: text("education"),
  skills: text("skills"),
  resume: varchar("resume", { length: 500 }),
  coverLetter: text("cover_letter"),
  status: varchar("status", { length: 50 }).default('pending'),
  submittedAt: timestamp("submitted_at").defaultNow(),
});

export const enquiryForms = mysqlTable("enquiry_forms", {
  id: int("id").primaryKey().autoincrement(),
  name: varchar("name", { length: 255 }),
  email: varchar("email", { length: 100 }),
  phone: varchar("phone", { length: 20 }),
  subject: varchar("subject", { length: 255 }),
  message: text("message"),
  enquiryType: varchar("enquiry_type", { length: 100 }),
  status: varchar("status", { length: 50 }).default('pending'),
  submittedAt: timestamp("submitted_at").defaultNow(),
});

// Support system tables
export const feedbackSuggestions = mysqlTable("feedback_suggestions", {
  id: int("id").primaryKey().autoincrement(),
  userId: int("user_id"),
  name: varchar("name", { length: 255 }),
  email: varchar("email", { length: 100 }),
  feedbackType: varchar("feedback_type", { length: 100 }), // feedback, suggestion, complaint
  subject: varchar("subject", { length: 255 }),
  message: text("message"),
  rating: tinyint("rating"),
  status: varchar("status", { length: 50 }).default('pending'),
  response: text("response"),
  respondedBy: int("responded_by"),
  respondedAt: timestamp("responded_at"),
  submittedAt: timestamp("submitted_at").defaultNow(),
});

export const chatMessages = mysqlTable("chat_messages", {
  id: int("id").primaryKey().autoincrement(),
  userId: int("user_id").notNull(),
  adminId: int("admin_id"),
  message: text("message"),
  messageType: varchar("message_type", { length: 50 }).default('text'), // text, image, file
  attachment: varchar("attachment", { length: 500 }),
  isFromUser: tinyint("is_from_user").default(1),
  isRead: tinyint("is_read").default(0),
  sentAt: timestamp("sent_at").defaultNow(),
});

// Copy rights table
export const copyRights = mysqlTable("copy_rights", {
  id: int("id").primaryKey().autoincrement(),
  groupId: int("group_id").default(0),
  copyRight: text("copy_right"),
  year: varchar("year", { length: 4 }),
  isActive: tinyint("is_active").default(1),
  createdAt: timestamp("created_at").defaultNow(),
  updatedAt: timestamp("updated_at").defaultNow().onUpdateNow(),
});

// Corporate feature schemas
export const corporateUserSchema = z.object({
  name: z.string().min(1, "Name is required"),
  mobile: z.string().min(10, "Mobile number must be at least 10 digits"),
  email: z.string().email("Invalid email address"),
  username: z.string().min(3, "Username must be at least 3 characters"),
  password: z.string().min(6, "Password must be at least 6 characters").optional(),
});

export const franchiseHolderSchema = z.object({
  userId: z.number().min(1, "User ID is required"),
  country: z.number().optional(),
  state: z.number().optional(),
  district: z.number().optional(),
});

export const corporateAdSchema = z.object({
  adType: z.string().min(1, "Ad type is required"),
  adPosition: z.string().optional(),
  adTitle: z.string().min(1, "Ad title is required"),
  adImage: z.string().optional(),
  adUrl: z.string().optional(),
  adDescription: z.string().optional(),
  isActive: z.number().default(1),
});

export const termsConditionsSchema = z.object({
  title: z.string().min(1, "Title is required"),
  content: z.string().min(1, "Content is required"),
  version: z.string().optional(),
  isActive: z.number().default(1),
});

export const footerContentSchema = z.object({
  title: z.string().min(1, "Title is required"),
  content: z.string().min(1, "Content is required"),
  image: z.string().optional(),
  tagLine: z.string().optional(),
  isActive: z.number().default(1),
});

export const gallerySchema = z.object({
  galleryName: z.string().min(1, "Gallery name is required"),
  description: z.string().optional(),
  isActive: z.number().default(1),
});

export const contactUsSchema = z.object({
  companyName: z.string().min(1, "Company name is required"),
  address: z.string().optional(),
  phone: z.string().optional(),
  email: z.string().email("Invalid email address").optional(),
  website: z.string().optional(),
  mapLocation: z.string().optional(),
  workingHours: z.string().optional(),
  isActive: z.number().default(1),
});

export const socialLinkSchema = z.object({
  platform: z.string().min(1, "Platform is required"),
  url: z.string().url("Invalid URL"),
  icon: z.string().optional(),
  isActive: z.number().default(1),
});

export const feedbackSchema = z.object({
  name: z.string().min(1, "Name is required"),
  email: z.string().email("Invalid email address"),
  feedbackType: z.string().min(1, "Feedback type is required"),
  subject: z.string().min(1, "Subject is required"),
  message: z.string().min(1, "Message is required"),
  rating: z.number().min(1).max(5).optional(),
});

// Type exports for corporate features
export type FranchiseHolder = typeof franchiseHolder.$inferSelect;
export type InsertFranchiseHolder = typeof franchiseHolder.$inferInsert;
export type FranchiseStaff = typeof franchiseStaff.$inferSelect;
export type InsertFranchiseStaff = typeof franchiseStaff.$inferInsert;
export type CorporateAd = typeof corporateAds.$inferSelect;
export type InsertCorporateAd = typeof corporateAds.$inferInsert;
export type PopupAd = typeof popupAds.$inferSelect;
export type InsertPopupAd = typeof popupAds.$inferInsert;
export type TermsCondition = typeof termsConditions.$inferSelect;
export type InsertTermsCondition = typeof termsConditions.$inferInsert;
export type AboutUs = typeof aboutUs.$inferSelect;
export type InsertAboutUs = typeof aboutUs.$inferInsert;
export type Award = typeof awards.$inferSelect;
export type InsertAward = typeof awards.$inferInsert;
export type Gallery = typeof gallery.$inferSelect;
export type InsertGallery = typeof gallery.$inferInsert;
export type ContactUs = typeof contactUs.$inferSelect;
export type InsertContactUs = typeof contactUs.$inferInsert;
export type SocialLink = typeof socialLinks.$inferSelect;
export type InsertSocialLink = typeof socialLinks.$inferInsert;
export type FeedbackSuggestion = typeof feedbackSuggestions.$inferSelect;
export type InsertFeedbackSuggestion = typeof feedbackSuggestions.$inferInsert;
