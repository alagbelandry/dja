
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- fos_user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `fos_user`;

CREATE TABLE `fos_user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255),
    `username_canonical` VARCHAR(255),
    `email` VARCHAR(255),
    `email_canonical` VARCHAR(255),
    `enabled` TINYINT(1) DEFAULT 0,
    `salt` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `last_login` DATETIME,
    `locked` TINYINT(1) DEFAULT 0,
    `expired` TINYINT(1) DEFAULT 0,
    `expires_at` DATETIME,
    `confirmation_token` VARCHAR(255),
    `password_requested_at` DATETIME,
    `credentials_expired` TINYINT(1) DEFAULT 0,
    `credentials_expire_at` DATETIME,
    `roles` TEXT,
    `phone` INTEGER(10),
    `ip_adress` VARCHAR(30),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `fos_user_U_1` (`username_canonical`),
    UNIQUE INDEX `fos_user_U_2` (`email_canonical`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- fos_group
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `fos_group`;

CREATE TABLE `fos_group`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `roles` TEXT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- fos_user_group
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `fos_user_group`;

CREATE TABLE `fos_user_group`
(
    `fos_user_id` INTEGER NOT NULL,
    `fos_group_id` INTEGER NOT NULL,
    PRIMARY KEY (`fos_user_id`,`fos_group_id`),
    INDEX `fos_user_group_FI_2` (`fos_group_id`),
    CONSTRAINT `fos_user_group_FK_1`
        FOREIGN KEY (`fos_user_id`)
        REFERENCES `fos_user` (`id`),
    CONSTRAINT `fos_user_group_FK_2`
        FOREIGN KEY (`fos_group_id`)
        REFERENCES `fos_group` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- category_type
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `category_type`;

CREATE TABLE `category_type`
(
    `id` INTEGER(5) NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(100) NOT NULL,
    `code` VARCHAR(20),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8' COMMENT='type of category';

-- ---------------------------------------------------------------------
-- category
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category`
(
    `id` INTEGER(6) NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(100) NOT NULL,
    `code` VARCHAR(20),
    `category_type_id` INTEGER(5) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `category_FI_1` (`category_type_id`),
    CONSTRAINT `category_FK_1`
        FOREIGN KEY (`category_type_id`)
        REFERENCES `category_type` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8' COMMENT='category of ad';

-- ---------------------------------------------------------------------
-- area
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `area`;

CREATE TABLE `area`
(
    `id` INTEGER(5) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `code` VARCHAR(20),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8' COMMENT='area of country';

-- ---------------------------------------------------------------------
-- city
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `city`;

CREATE TABLE `city`
(
    `id` INTEGER(6) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `code` VARCHAR(20),
    `area_id` INTEGER(5) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `city_FI_1` (`area_id`),
    CONSTRAINT `city_FK_1`
        FOREIGN KEY (`area_id`)
        REFERENCES `area` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8' COMMENT='city of area';

-- ---------------------------------------------------------------------
-- quarter
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `quarter`;

CREATE TABLE `quarter`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `city_id` INTEGER(6),
    PRIMARY KEY (`id`),
    INDEX `quarter_FI_1` (`city_id`),
    CONSTRAINT `quarter_FK_1`
        FOREIGN KEY (`city_id`)
        REFERENCES `city` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8' COMMENT='quarter of city';

-- ---------------------------------------------------------------------
-- user_type
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user_type`;

CREATE TABLE `user_type`
(
    `id` INTEGER(5) NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(100) NOT NULL,
    `code` VARCHAR(20),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8' COMMENT='type of user';

-- ---------------------------------------------------------------------
-- ad_type
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `ad_type`;

CREATE TABLE `ad_type`
(
    `id` INTEGER(5) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `code` VARCHAR(20),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8' COMMENT='type of ad';

-- ---------------------------------------------------------------------
-- ad
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `ad`;

CREATE TABLE `ad`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(100) NOT NULL,
    `description` VARCHAR(500),
    `price` VARCHAR(30),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `ad_type_id` INTEGER(5),
    `category_id` INTEGER(6),
    `user_type_id` INTEGER(6),
    `user_id` INTEGER(11),
    `city_id` INTEGER(6),
    PRIMARY KEY (`id`),
    INDEX `ad_FI_1` (`city_id`),
    INDEX `ad_FI_2` (`user_id`),
    INDEX `ad_FI_3` (`user_type_id`),
    INDEX `ad_FI_4` (`ad_type_id`),
    INDEX `ad_FI_5` (`category_id`),
    CONSTRAINT `ad_FK_1`
        FOREIGN KEY (`city_id`)
        REFERENCES `city` (`id`),
    CONSTRAINT `ad_FK_2`
        FOREIGN KEY (`user_id`)
        REFERENCES `fos_user` (`id`),
    CONSTRAINT `ad_FK_3`
        FOREIGN KEY (`user_type_id`)
        REFERENCES `user_type` (`id`),
    CONSTRAINT `ad_FK_4`
        FOREIGN KEY (`ad_type_id`)
        REFERENCES `ad_type` (`id`),
    CONSTRAINT `ad_FK_5`
        FOREIGN KEY (`category_id`)
        REFERENCES `category` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8' COMMENT='ad';

-- ---------------------------------------------------------------------
-- picture_ad
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `picture_ad`;

CREATE TABLE `picture_ad`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `path` VARCHAR(500) NOT NULL,
    `ad_id` INTEGER(5),
    PRIMARY KEY (`id`),
    INDEX `picture_ad_FI_1` (`ad_id`),
    CONSTRAINT `picture_ad_FK_1`
        FOREIGN KEY (`ad_id`)
        REFERENCES `ad` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8' COMMENT='picture of ad';

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
