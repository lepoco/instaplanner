
-- Options table
CREATE TABLE IF NOT EXISTS rdev_options (
	option_name VARCHAR(64) NOT NULL PRIMARY KEY,
	option_value LONGTEXT
) CHARACTER SET utf8 COLLATE utf8_general_ci;

-- Accounts table
CREATE TABLE IF NOT EXISTS rdev_accounts (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	token_id VARCHAR(128),
	name VARCHAR(128) NOT NULL,
	full_name VARCHAR(128) NOT NULL,
	avatar VARCHAR(256) NOT NULL,
	website VARCHAR(512),
	posts INT(32),
	followers INT(32),
	following INT(32),
	description LONGTEXT,
	post_order LONGTEXT,
	active BOOLEAN DEFAULT true,
	last_update DATETIME DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8 COLLATE utf8_general_ci;

-- Posts table
CREATE TABLE IF NOT EXISTS rdev_posts (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	CONSTRAINT fk_account_id FOREIGN KEY (account_id) REFERENCES rdev_accounts(id),
	account_id INT(6) UNSIGNED NOT NULL,
	image VARCHAR(256) NOT NULL,
	description LONGTEXT
) CHARACTER SET utf8 COLLATE utf8_general_ci;

-- Users table
CREATE TABLE IF NOT EXISTS rdev_users (
	user_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user_name VARCHAR(128) NOT NULL,
	user_display_name VARCHAR(128),
	user_email VARCHAR(256),
	user_password VARCHAR(1024) NOT NULL,
	user_token VARCHAR(256),
	user_role VARCHAR(256),
	user_status INT(2) NOT NULL DEFAULT 0,
	user_registered DATETIME DEFAULT CURRENT_TIMESTAMP,
	user_last_login DATETIME,
	user_selected_account INT(6)
) CHARACTER SET utf8 COLLATE utf8_general_ci;