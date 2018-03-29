-- SQL FOR INSERTING DATA. --


-- TABLE FOR USERS --

CREATE TABLE `users` (
	`user_id` BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL, 
	`phone_number` VARCHAR(64) UNIQUE KEY NOT NULL, 
	`password` VARCHAR (256) NOT NULL, 
	`fullname` VARCHAR (256) NOT NULL, 
	`location` VARCHAR (256) NOT NULL,
	`topup_balance` VARCHAR (256) NOT NULL DEFAULT '0'
);

ALTER TABLE `users` ADD `user_firebase_id` VARCHAR(512) NOT NULL;

-- TABLE FOR TICKET --

CREATE TABLE `ticket` (
	`ticket_id` BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL, 
	`user_id` BIGINT NOT NULL, 
	`start_bus_stop` VARCHAR (512) NOT NULL, 
	`destination` VARCHAR (512) NOT NULL, 
	`price` VARCHAR (256) NOT NULL, 
	`buying_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
	`usage_time` TIMESTAMP NOT NULL, 
	`ticket_admits` INT(4) NOT NULL
);

ALTER TABLE `ticket` ADD `used` ENUM ('0','1') DEFAULT '0' NOT NULL;

-- TABLE FOR CREDIT CARD STORAGE --

CREATE TABLE `creditcard` (
	`card_id` BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL, 
	`user_id` BIGINT NOT NULL, 
	`card_number` VARCHAR (512) NOT NULL, 
	`card_cv2` VARCHAR (512) NOT NULL, 
	`card_exp_date` VARCHAR (64) NOT NULL, 
	`card_pin` VARCHAR (512) NOT NULL, 
	`isgiftcard` ENUM('0', '1') DEFAULT '0' NOT NULL, 
	`expired` ENUM('0', '1') DEFAULT '0' NOT NULL
);

-- TABLE FOR GIFT CARD TOP UP CARD --
CREATE TABLE `giftcard` (
	`card_id` BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL, 
	`card_number` VARCHAR (512) NOT NULL, 
	`card_cv2` VARCHAR (512) NOT NULL, 
	`card_exp_date` VARCHAR (64) NOT NULL, 
	`card_value` VARCHAR (512) NOT NULL DEFAULT 0, 
	`card_used` ENUM('0', '1') DEFAULT '0' NOT NULL
);

-- TABLE FOR USER COMPLAINTS --
CREATE TABLE `complaints` (
	`complaint_id` BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL, 
	`user_id` BIGINT NOT NULL, 
	`complaint_title` VARCHAR (256) NOT NULL, 
	`complaint_urgency` ENUM('3','2','1','0') DEFAULT '0' NOT NULL, 
	`complaint` VARCHAR (5000) NOT NULL
);