
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
    PRIMARY KEY (`id`),
    UNIQUE INDEX `fos_user_U_1` (`username_canonical`),
    UNIQUE INDEX `fos_user_U_2` (`email_canonical`)
) ENGINE=MyISAM;

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
) ENGINE=MyISAM;

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
) ENGINE=MyISAM;

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
    `slug` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `category_slug` (`slug`(255)),
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
    `slug` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `area_slug` (`slug`(255))
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
    `slug` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `city_slug` (`slug`(255)),
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
    `slug` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `quarter_slug` (`slug`(255)),
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
-- interested_user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `interested_user`;

CREATE TABLE `interested_user`
(
    `id` INTEGER(6) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100),
    `email` VARCHAR(100),
    `phone` VARCHAR(100),
    `message` VARCHAR(500),
    `ip_adress` VARCHAR(20),
    `created_at` DATETIME,
    `ad_id` INTEGER(5),
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `interested_user_FI_1` (`ad_id`),
    CONSTRAINT `interested_user_FK_1`
        FOREIGN KEY (`ad_id`)
        REFERENCES `ad` (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8' COMMENT='interested user by ad';

-- ---------------------------------------------------------------------
-- information_user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `information_user`;

CREATE TABLE `information_user`
(
    `id` INTEGER(6) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100),
    `subject` VARCHAR(100),
    `email` VARCHAR(100),
    `message` VARCHAR(500),
    `ip_adress` VARCHAR(20),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8' COMMENT='user who need information';

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
    `statut` TINYINT(2) DEFAULT 0,
    `user_name` VARCHAR(100) NOT NULL,
    `user_email` VARCHAR(100) NOT NULL,
    `user_password` VARCHAR(255) NOT NULL,
    `user_salt` VARCHAR(100) NOT NULL,
    `user_phone` VARCHAR(50),
    `user_ip_adress` VARCHAR(40),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `ad_type_id` INTEGER(5),
    `category_id` INTEGER(6),
    `user_type_id` INTEGER(6),
    `city_id` INTEGER(6),
    `quarter_id` INTEGER(6),
    `slug` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `ad_slug` (`slug`(255)),
    INDEX `ad_FI_1` (`city_id`),
    INDEX `ad_FI_2` (`user_type_id`),
    INDEX `ad_FI_3` (`ad_type_id`),
    INDEX `ad_FI_4` (`category_id`),
    INDEX `ad_FI_5` (`quarter_id`),
    CONSTRAINT `ad_FK_1`
        FOREIGN KEY (`city_id`)
        REFERENCES `city` (`id`),
    CONSTRAINT `ad_FK_2`
        FOREIGN KEY (`user_type_id`)
        REFERENCES `user_type` (`id`),
    CONSTRAINT `ad_FK_3`
        FOREIGN KEY (`ad_type_id`)
        REFERENCES `ad_type` (`id`),
    CONSTRAINT `ad_FK_4`
        FOREIGN KEY (`category_id`)
        REFERENCES `category` (`id`),
    CONSTRAINT `ad_FK_5`
        FOREIGN KEY (`quarter_id`)
        REFERENCES `quarter` (`id`)
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
