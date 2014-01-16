SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


CREATE DATABASE IF NOT EXISTS php CHARACTER SET = utf8;

-- ---------------------------------------------------
-- Table php.client_group
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS php.client_group (
  id INT NOT NULL ,
  rate_1_amount INT NULL ,
  rate_1_label VARCHAR(100) NULL ,
  rate_2_amount INT NULL ,
  rate_2_label VARCHAR(100) NULL ,
  active INT NULL ,
  name VARCHAR(45) NULL ,
  creation_datetime DATETIME NULL ,
  PRIMARY KEY (id) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.client
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS php.client (
  id INT NOT NULL AUTO_INCREMENT ,
  name VARCHAR(45) NULL ,
  surname VARCHAR(45) NULL ,
  birthdate DATE NULL ,
  note VARCHAR(45) NULL ,
  id_anamnesis INT NULL ,
  id_group INT NULL ,
  mobile_phone VARCHAR(45) NULL ,
  phone VARCHAR(45) NULL ,
  PRIMARY KEY (id) ,
  INDEX group_fk (id_group ASC) ,
  CONSTRAINT group_fk
    FOREIGN KEY (id_group )
    REFERENCES php.client_group (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.treatment_type
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS php.treatment_type (
  id INT NOT NULL ,
  name VARCHAR(100) NULL ,
  PRIMARY KEY (id) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.payment_state
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS php.payment_state (
  id INT NOT NULL ,
  name VARCHAR(45) NULL ,
  PRIMARY KEY (id) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.payment_type
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS php.payment_type (
  id INT NOT NULL ,
  name VARCHAR(45) NULL ,
  PRIMARY KEY (id) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.payment_packet_type
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS php.payment_packet_type (
  id INT NOT NULL ,
  name VARCHAR(100) NULL ,
  PRIMARY KEY (id) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.payment
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS php.payment (
  id INT NOT NULL ,
  amount INT NULL ,
  nominal_number_of_performance INT NULL ,
  name VARCHAR(100) NULL ,
  id_payment_state INT NULL ,
  id_payment_type INT NULL ,
  collection_date DATE NULL ,
  billing_date DATE NULL ,
  creation_datetime DATETIME NULL ,
  modify_datetime DATETIME NULL ,
  registered INT NULL ,
  nominal_start_datetime DATETIME NULL ,
  nominal_end_datetime DATETIME NULL ,
  id_client VARCHAR(45) NULL ,
  id_payment_packet_type INT NULL ,
  PRIMARY KEY (id) ,
  INDEX payment_state_fk (id_payment_state ASC) ,
  INDEX payment_type_fk (id_payment_type ASC) ,
  INDEX payment_packet_type_fk (id_payment_packet_type ASC) ,
  CONSTRAINT payment_state_fk
    FOREIGN KEY (id_payment_state )
    REFERENCES php.payment_state (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT payment_type_fk
    FOREIGN KEY (id_payment_type )
    REFERENCES php.payment_type (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT payment_packet_type_fk
    FOREIGN KEY (id_payment_packet_type )
    REFERENCES php.payment_packet_type (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.performance
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS php.performance (
  id INT NOT NULL ,
  id_client INT NOT NULL ,
  id_payment INT NULL ,
  datetime DATETIME NULL ,
  duration INT NULL COMMENT 'minutes' ,
  reason VARCHAR(300) NULL ,
  id_treatment_type INT NULL ,
  treatment_type_note VARCHAR(300) NULL ,
  note VARCHAR(300) NULL ,
  executed INT NULL ,
  PRIMARY KEY (id) ,
  INDEX cliente_fk (id_client ASC) ,
  INDEX treatment_type_fk (id_payment ASC) ,
  INDEX payment_fk (id_payment ASC) ,
  CONSTRAINT cliente_fk
    FOREIGN KEY (id_client )
    REFERENCES php.client (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT treatment_type_fk
    FOREIGN KEY (id_payment )
    REFERENCES php.treatment_type (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT payment_fk
    FOREIGN KEY (id_payment )
    REFERENCES php.payment (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.client_memo
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS php.client_memo (
  id INT NOT NULL ,
  value VARCHAR(45) NULL ,
  type INT NULL ,
  creation_datetime DATETIME NULL ,
  id_client_memo INT NULL ,
  PRIMARY KEY (id) ,
  INDEX client_memo_id (id_client_memo ASC) ,
  CONSTRAINT client_memo_id
    FOREIGN KEY (id_client_memo )
    REFERENCES php.client (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.anamnesis
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS php.anamnesis (
  id INT NOT NULL ,
  value TEXT NULL ,
  creation_datetime DATETIME NULL ,
  id_client INT NULL ,
  PRIMARY KEY (id) ,
  INDEX client_fk2 (id_client ASC) ,
  CONSTRAINT client_fk2
    FOREIGN KEY (id_client )
    REFERENCES php.client (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
