CREATE TABLE `merchants` (
	`merchant_id` BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	`merchant_username` VARCHAR(256) UNIQUE KEY NOT NULL, 
	`mechant_password` VARCHAR(256) NOT NULL, 
	`merchant_company_name` VARCHAR(256) NOT NULL, 
	`merchant_location` VARCHAR(256) NOT NULL, 
	`merchant_verified` ENUM('0','1') DEFAULT '0' NOT NULL,
);

ALTER TABLE `merchants` ADD `merchant_firebase_id` VARCHAR(512) NOT NULL;

/*CREATE TABLE `merchant_vehicles` (
	
);*/