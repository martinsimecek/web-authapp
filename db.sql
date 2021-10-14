-- auth_role
CREATE TABLE `auth_role` (
	`role` VARCHAR(255) NOT NULL,
	`read` TINYINT(1) NOT NULL,
	`edit` TINYINT(1) NOT NULL,
	`manage` TINYINT(1) NOT NULL,
	PRIMARY KEY (`role`)
);
INSERT INTO `auth_role` (`role`, `read`, `edit`, `manage`)
VALUES
	('Neaktivní', 0, 0, 0),
	('Uživatel', 1, 0, 0),
	('Editor', 1, 1, 0),
	('Správce', 1, 1, 1);

-- auth_user
CREATE TABLE `auth_user` (
	`user` CHAR(36) NOT NULL,
	`created_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
	`created_by` CHAR(36) NOT NULL,
	`email` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`role` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`user`),
	CONSTRAINT `FK_role`
		FOREIGN KEY (`role`) REFERENCES auth_role(`role`)
		ON UPDATE CASCADE
		ON DELETE RESTRICT,
	CONSTRAINT `FK_created_by_1`
		FOREIGN KEY (`created_by`) REFERENCES auth_user(`user`)
		ON UPDATE RESTRICT
		ON DELETE NO ACTION
);
INSERT INTO `auth_user` (`user`, `created_by`, `email`, `password`, `role`)
VALUES
	('00000000-0000-0000-0000-000000000000', '00000000-0000-0000-0000-000000000000', '<email>', '$2y$10$Af/8C.q1KFGpY3SUA7Q5UebX7SSVp7GEAlTD9scCQb6bhjUNYIRxO', 'Správce'); -- Default password: 123456789

-- auth_login
CREATE TABLE `auth_login` (
	`id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	`created_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
	`created_by` CHAR(36) NOT NULL,
	`ip_address` VARCHAR(45) NOT NULL,
	PRIMARY KEY (`id`),
	CONSTRAINT `FK_created_by_2`
		FOREIGN KEY (`created_by`) REFERENCES auth_user(`user`)
		ON UPDATE RESTRICT
		ON DELETE CASCADE
);
