SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS php ;
CREATE SCHEMA IF NOT EXISTS php DEFAULT CHARACTER SET utf8 ;
USE php ;

-- -----------------------------------------------------
-- Table php.group
-- -----------------------------------------------------
DROP TABLE IF EXISTS php.group ;

CREATE  TABLE IF NOT EXISTS php.group (
  id INT NOT NULL AUTO_INCREMENT ,
  active INT NULL ,
  name VARCHAR(45) NULL ,
  creation_datetime DATETIME NULL ,
  PRIMARY KEY (id) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.client
-- -----------------------------------------------------
DROP TABLE IF EXISTS php.client ;

CREATE  TABLE IF NOT EXISTS php.client (
  id INT NOT NULL AUTO_INCREMENT ,
  name VARCHAR(45) NULL ,
  surname VARCHAR(45) NULL ,
  birthdate DATE NULL ,
  note VARCHAR(45) NULL ,
  group_id INT NULL ,
  mobile_phone VARCHAR(45) NULL ,
  phone VARCHAR(45) NULL ,
  creation_datetime DATETIME NULL ,
  update_datetime DATETIME NULL ,
  indirizzo VARCHAR(100) NULL ,
  email VARCHAR(100) NULL ,
  PRIMARY KEY (id) ,
  INDEX group_fk (group_id ASC) ,
  CONSTRAINT group_fk
    FOREIGN KEY (group_id )
    REFERENCES php.group (id )
    ON DELETE SET NULL
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.paymentstate
-- -----------------------------------------------------
DROP TABLE IF EXISTS php.paymentstate ;

CREATE  TABLE IF NOT EXISTS php.paymentstate (
  id INT NOT NULL AUTO_INCREMENT ,
  name VARCHAR(45) NULL ,
  creation_datetime DATETIME NULL ,
  PRIMARY KEY (id) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.paymentformula
-- -----------------------------------------------------
DROP TABLE IF EXISTS php.paymentformula ;

CREATE  TABLE IF NOT EXISTS php.paymentformula (
  id INT NOT NULL AUTO_INCREMENT ,
  name VARCHAR(100) NULL ,
  creation_datetime DATETIME NULL ,
  PRIMARY KEY (id) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.paymentform
-- -----------------------------------------------------
DROP TABLE IF EXISTS php.paymentform ;

CREATE  TABLE IF NOT EXISTS php.paymentform (
  id INT NOT NULL AUTO_INCREMENT ,
  name VARCHAR(45) NULL ,
  creation_datetime DATETIME NULL ,
  PRIMARY KEY (id) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.bill
-- -----------------------------------------------------
DROP TABLE IF EXISTS php.bill ;

CREATE  TABLE IF NOT EXISTS php.bill (
  id INT NOT NULL AUTO_INCREMENT ,
  date DATE NULL ,
  amount FLOAT NULL ,
  PRIMARY KEY (id) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.payment
-- -----------------------------------------------------
DROP TABLE IF EXISTS php.payment ;

CREATE  TABLE IF NOT EXISTS php.payment (
  id INT NOT NULL AUTO_INCREMENT ,
  amount FLOAT NULL ,
  name VARCHAR(100) NULL DEFAULT 'Singola prestazione' ,
  paymentstate_id INT NULL DEFAULT 2 ,
  collection_date DATE NULL DEFAULT NULL ,
  creation_datetime DATETIME NULL ,
  update_datetime DATETIME NULL ,
  paymentgroup_nominal_number_of_performance INT NULL ,
  paymentgroup_nominal_start_datetime DATETIME NULL DEFAULT NULL ,
  paymentgroup_nominal_end_datetime DATETIME NULL DEFAULT NULL ,
  paymentformula_id INT NULL DEFAULT 1 ,
  client_id VARCHAR(45) NULL ,
  paymentform_id INT NULL DEFAULT 1 ,
  bill_id INT NULL ,
  bill_description TEXT NULL ,
  PRIMARY KEY (id) ,
  INDEX payment_state_fk (paymentstate_id ASC) ,
  INDEX payment_formula_fk (paymentformula_id ASC) ,
  INDEX payment_form_fk (paymentform_id ASC) ,
  INDEX bill_id (bill_id ASC) ,
  CONSTRAINT payment_state_fk
    FOREIGN KEY (paymentstate_id )
    REFERENCES php.paymentstate (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT payment_formula_fk
    FOREIGN KEY (paymentformula_id )
    REFERENCES php.paymentformula (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT payment_form_fk
    FOREIGN KEY (paymentform_id )
    REFERENCES php.paymentform (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    /*,
  CONSTRAINT bill_id
    FOREIGN KEY (bill_id )
    REFERENCES php.bill (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    */
    )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.performance
-- -----------------------------------------------------
DROP TABLE IF EXISTS php.performance ;

CREATE  TABLE IF NOT EXISTS php.performance (
  id INT NOT NULL AUTO_INCREMENT ,
  client_id INT NULL ,
  payment_id INT NULL ,
  datetime DATETIME NULL ,
  duration INT NULL COMMENT 'minutes' ,
  pre_note TEXT NULL ,
  performancetype_id INT NULL ,
  performancetype_note VARCHAR(300) NULL ,
  post_note TEXT NULL ,
  executed INT NULL ,
  creation_datetime DATETIME NULL ,
  performancelocation_id INT NULL ,
  PRIMARY KEY (id) ,
  INDEX cliente_fk (client_id ASC) ,
  INDEX payment_fk (payment_id ASC) ,
  CONSTRAINT cliente_fk
    FOREIGN KEY (client_id )
    REFERENCES php.client (id )
    ON DELETE CASCADE
    ON UPDATE NO ACTION
    /*,
  CONSTRAINT payment_fk
    FOREIGN KEY (payment_id )
    REFERENCES php.payment (id )
    ON DELETE CASCADE
    ON UPDATE NO ACTION
    */
    )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.memo
-- -----------------------------------------------------
DROP TABLE IF EXISTS php.memo ;

CREATE  TABLE IF NOT EXISTS php.memo (
  id INT NOT NULL AUTO_INCREMENT ,
  value VARCHAR(512) NULL ,
  type INT NULL ,
  creation_datetime DATETIME NULL ,
  client_id INT NULL ,
  PRIMARY KEY (id) ,
  INDEX client_memo_id (client_id ASC) ,
  CONSTRAINT client_memo_id
    FOREIGN KEY (client_id )
    REFERENCES php.client (id )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.anamnesis
-- -----------------------------------------------------
DROP TABLE IF EXISTS php.anamnesis ;

CREATE  TABLE IF NOT EXISTS php.anamnesis (
  id INT NOT NULL AUTO_INCREMENT ,
  value TEXT NULL ,
  creation_datetime DATETIME NULL ,
  client_id INT NULL ,
  PRIMARY KEY (id) ,
  INDEX client_fk2 (client_id ASC) ,
  CONSTRAINT client_fk2
    FOREIGN KEY (client_id )
    REFERENCES php.client (id )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.performancetype
-- -----------------------------------------------------
DROP TABLE IF EXISTS php.performancetype ;

CREATE  TABLE IF NOT EXISTS php.performancetype (
  id INT NOT NULL AUTO_INCREMENT ,
  name VARCHAR(100) NULL ,
  creation_datetime DATETIME NULL ,
  PRIMARY KEY (id) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.rate
-- -----------------------------------------------------
DROP TABLE IF EXISTS php.rate ;

CREATE  TABLE IF NOT EXISTS php.rate (
  id INT NOT NULL AUTO_INCREMENT ,
  name VARCHAR(100) NULL ,
  performance_number INT NULL ,
  PRIMARY KEY (id) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.group_rate
-- -----------------------------------------------------
DROP TABLE IF EXISTS php.group_rate ;

CREATE  TABLE IF NOT EXISTS php.group_rate (
  id INT NOT NULL AUTO_INCREMENT ,
  amount INT NULL ,
  group_id INT NULL ,
  rate_id INT NULL ,
  PRIMARY KEY (id) ,
  INDEX group_fkk (group_id ASC) ,
  INDEX rate_fkk (rate_id ASC) ,
  CONSTRAINT group_fkk
    FOREIGN KEY (group_id )
    REFERENCES php.group (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT rate_fkk
    FOREIGN KEY (rate_id )
    REFERENCES php.rate (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.performance_performancetype
-- -----------------------------------------------------
DROP TABLE IF EXISTS php.performance_performancetype ;

CREATE  TABLE IF NOT EXISTS php.performance_performancetype (
  id INT NOT NULL AUTO_INCREMENT ,
  note TEXT NULL ,
  performance_id INT NULL ,
  performancetype_id INT NULL ,
  position INT NULL,
  PRIMARY KEY (id) ,
  INDEX perf_fkk (performance_id ASC) ,
  INDEX perf_type_fkk (performancetype_id ASC) ,
  CONSTRAINT perf_fk
    FOREIGN KEY (performance_id )
    REFERENCES php.performance (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT perf_type_fk
    FOREIGN KEY (performancetype_id )
    REFERENCES php.performancetype (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.performancelocation
-- -----------------------------------------------------
DROP TABLE IF EXISTS php.performancelocation ;

CREATE  TABLE IF NOT EXISTS php.performancelocation (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NULL ,
  address VARCHAR(100) NULL ,
  city VARCHAR(45) NULL ,
  PRIMARY KEY (id) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table php.billrow
-- -----------------------------------------------------
DROP TABLE IF EXISTS php.billrow ;

CREATE  TABLE IF NOT EXISTS php.billrow (
  id INT NOT NULL AUTO_INCREMENT,
  value TEXT NULL ,
  amount FLOAT NULL ,
  quantity INT NULL ,
  tax INT NULL ,
  bill_id INT NULL ,
  PRIMARY KEY (id) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table php.paymentstate
-- -----------------------------------------------------
START TRANSACTION;
USE php;
INSERT INTO php.paymentstate (id, name, creation_datetime) VALUES (1, 'Saldato', NULL);
INSERT INTO php.paymentstate (id, name, creation_datetime) VALUES (2, 'Non saldato', NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table php.paymentformula
-- -----------------------------------------------------
START TRANSACTION;
USE php;
INSERT INTO php.paymentformula (id, name, creation_datetime) VALUES (1, 'Contestuale', NULL);
INSERT INTO php.paymentformula (id, name, creation_datetime) VALUES (2, 'Anticipato', NULL);
INSERT INTO php.paymentformula (id, name, creation_datetime) VALUES (3, 'Posticipato', NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table php.paymentform
-- -----------------------------------------------------
START TRANSACTION;
USE php;
INSERT INTO php.paymentform (id, name, creation_datetime) VALUES (1, 'Contanti', NULL);
INSERT INTO php.paymentform (id, name, creation_datetime) VALUES (2, 'Assegno', NULL);
INSERT INTO php.paymentform (id, name, creation_datetime) VALUES (3, 'Bonifico', NULL);

COMMIT;
