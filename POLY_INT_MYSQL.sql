SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`POLY_INT_schools`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`POLY_INT_schools` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`POLY_INT_departments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`POLY_INT_departments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`POLY_INT_users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`POLY_INT_users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `role` VARCHAR(10) NOT NULL,
  `email` VARCHAR(45) NULL,
  `password` VARCHAR(45) NULL,
  `firstname` VARCHAR(45) NULL,
  `lastname` VARCHAR(45) NULL,
  `facebook_url` VARCHAR(45) NULL,
  `twitter_url` VARCHAR(45) NULL,
  `linkedin_url` VARCHAR(45) NULL,
  `viadeo_url` VARCHAR(45) NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  `school_id` INT NOT NULL,
  `department_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_POLY_INT_users_POLY_INT_schools1_idx` (`school_id` ASC),
  INDEX `fk_POLY_INT_users_POLY_INT_departments1_idx` (`department_id` ASC),
  CONSTRAINT `fk_POLY_INT_users_POLY_INT_schools1`
    FOREIGN KEY (`school_id`)
    REFERENCES `mydb`.`POLY_INT_schools` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_POLY_INT_users_POLY_INT_departments1`
    FOREIGN KEY (`department_id`)
    REFERENCES `mydb`.`POLY_INT_departments` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`POLY_INT_countries`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`POLY_INT_countries` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `code` VARCHAR(3) NULL,
  `experienceNumber` INT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`POLY_INT_cities`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`POLY_INT_cities` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `lat` VARCHAR(45) NULL,
  `lng` VARCHAR(45) NULL,
  `experienceNumber` INT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  `country_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_cities_countries1_idx` (`country_id` ASC),
  CONSTRAINT `fk_cities_countries1`
    FOREIGN KEY (`country_id`)
    REFERENCES `mydb`.`POLY_INT_countries` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`POLY_INT_motives`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`POLY_INT_motives` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`POLY_INT_experiences`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`POLY_INT_experiences` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date_start` DATETIME NULL,
  `date_end` DATETIME NULL,
  `description` VARCHAR(140) NULL,
  `note` INT NULL,
  `comment` TINYTEXT NULL,
  `notify` TINYINT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  `city_id` INT NOT NULL,
  `motive_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_experiences_cities1_idx` (`city_id` ASC),
  INDEX `fk_experiences_motives1_idx` (`motive_id` ASC),
  INDEX `fk_experiences_users1_idx` (`user_id` ASC),
  CONSTRAINT `fk_experiences_cities1`
    FOREIGN KEY (`city_id`)
    REFERENCES `mydb`.`POLY_INT_cities` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_experiences_motives1`
    FOREIGN KEY (`motive_id`)
    REFERENCES `mydb`.`POLY_INT_motives` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_experiences_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `mydb`.`POLY_INT_users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
