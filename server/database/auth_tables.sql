-- Authentication system database tables
-- Run this script to create the necessary tables for the authentication system

-- Create OTP verification table
CREATE TABLE IF NOT EXISTS `otp_verification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `type` enum('registration','password_recovery') NOT NULL DEFAULT 'registration',
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `verified_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_email_type` (`email`, `type`),
  KEY `idx_otp_expires` (`otp`, `expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ensure users table has the required columns
-- (This assumes the users table already exists from the existing system)
ALTER TABLE `users` 
ADD COLUMN IF NOT EXISTS `username` varchar(100) UNIQUE AFTER `email`,
ADD COLUMN IF NOT EXISTS `phone` varchar(20) AFTER `last_name`,
ADD COLUMN IF NOT EXISTS `company` varchar(255) AFTER `phone`,
ADD COLUMN IF NOT EXISTS `last_login` timestamp NULL DEFAULT NULL AFTER `active`,
ADD COLUMN IF NOT EXISTS `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `last_login`,
ADD COLUMN IF NOT EXISTS `modified_on` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP AFTER `created_on`;

-- Ensure groups table exists
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default groups if they don't exist
INSERT IGNORE INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator group with full access'),
(2, 'users', 'Default user group'),
(3, 'members', 'Member group'),
(4, 'moderators', 'Moderator group');

-- Ensure users_groups table exists for many-to-many relationship
CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_group` (`user_id`, `group_id`),
  KEY `fk_users_groups_users` (`user_id`),
  KEY `fk_users_groups_groups` (`group_id`),
  CONSTRAINT `fk_users_groups_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_users_groups_groups` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ensure group_create table exists (for app groups)
CREATE TABLE IF NOT EXISTS `group_create` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `logo` varchar(255),
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default app groups if they don't exist
INSERT IGNORE INTO `group_create` (`id`, `name`, `description`) VALUES
(1, 'Myunions', 'My Unions Application'),
(2, 'Mymedia', 'My Media Application'),
(3, 'Myshop', 'My Shop Application'),
(4, 'Mybiz', 'My Business Application'),
(5, 'Mytv', 'My TV Application'),
(6, 'Myjoy', 'My Joy Application'),
(7, 'Myneedy', 'My Needy Application'),
(8, 'Mygod', 'My God Application');

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS `idx_users_email` ON `users` (`email`);
CREATE INDEX IF NOT EXISTS `idx_users_username` ON `users` (`username`);
CREATE INDEX IF NOT EXISTS `idx_users_active` ON `users` (`active`);
CREATE INDEX IF NOT EXISTS `idx_users_group_id` ON `users` (`group_id`);
CREATE INDEX IF NOT EXISTS `idx_users_last_login` ON `users` (`last_login`);

-- Create a view for user authentication with groups
CREATE OR REPLACE VIEW `user_auth_view` AS
SELECT 
    u.id,
    u.email,
    u.username,
    u.password,
    u.first_name,
    u.last_name,
    u.phone,
    u.company,
    u.active,
    u.group_id,
    u.last_login,
    u.created_on,
    u.modified_on,
    GROUP_CONCAT(CONCAT(g.id, ':', g.name, ':', COALESCE(g.description, '')) SEPARATOR '|') as groups
FROM users u
LEFT JOIN users_groups ug ON u.id = ug.user_id
LEFT JOIN groups g ON ug.group_id = g.id
GROUP BY u.id;

-- Sample data for testing (optional - remove in production)
-- INSERT IGNORE INTO `users` (`email`, `username`, `password`, `first_name`, `last_name`, `active`, `group_id`) VALUES
-- ('admin@mygroup.com', 'admin', '$2b$10$example_hash_here', 'Admin', 'User', 1, 1);

-- INSERT IGNORE INTO `users_groups` (`user_id`, `group_id`) VALUES
-- (1, 1); -- Admin user in admin group
