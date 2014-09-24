SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema adminns
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `adminns` ;
CREATE SCHEMA IF NOT EXISTS `adminns` DEFAULT CHARACTER SET utf8 ;
USE `adminns` ;

-- -----------------------------------------------------
-- Table `adminns`.`module`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`module` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_bin' NOT NULL,
  `permission` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_bin' NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id` (`id` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;


-- -----------------------------------------------------
-- Table `adminns`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_bin' NOT NULL,
  `password` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_bin' NOT NULL,
  `token` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_bin' NOT NULL,
  `name` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_bin' NOT NULL,
  `active` TINYINT(1) NOT NULL,
  `lastlogin` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` TINYINT(1) NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id` (`id` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;


-- -----------------------------------------------------
-- Table `adminns`.`user_module`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`user_module` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `level` INT(11) NOT NULL,
  `module_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`, `module_id`, `user_id`),
  UNIQUE INDEX `id` (`id` ASC),
  INDEX `fk_user_module_module_idx` (`module_id` ASC),
  INDEX `fk_user_module_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_user_module_module`
    FOREIGN KEY (`module_id`)
    REFERENCES `adminns`.`module` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_module_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `adminns`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;


-- -----------------------------------------------------
-- Table `adminns`.`cliente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`cliente` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NULL,
  `documento` VARCHAR(45) NULL,
  `site` VARCHAR(255) NULL,
  `user_module_id` INT UNSIGNED NOT NULL,
  `module_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_cliente_user_module1_idx` (`user_module_id` ASC, `module_id` ASC, `user_id` ASC),
  CONSTRAINT `fk_cliente_user_module1`
    FOREIGN KEY (`user_module_id` , `module_id` , `user_id`)
    REFERENCES `adminns`.`user_module` (`id` , `module_id` , `user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`estado`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`estado` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NULL,
  `uf` VARCHAR(2) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`cidade`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`cidade` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NULL,
  `estado_id` INT NOT NULL,
  PRIMARY KEY (`id`, `estado_id`),
  INDEX `fk_cidade_estado1_idx` (`estado_id` ASC),
  CONSTRAINT `fk_cidade_estado1`
    FOREIGN KEY (`estado_id`)
    REFERENCES `adminns`.`estado` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`endereco`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`endereco` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '	',
  `logradouro` VARCHAR(255) NULL,
  `bairro` VARCHAR(255) NULL,
  `cep` VARCHAR(15) NULL,
  `cidade_id` INT NOT NULL,
  PRIMARY KEY (`id`, `cidade_id`),
  INDEX `fk_endereco_cidade1_idx` (`cidade_id` ASC),
  CONSTRAINT `fk_endereco_cidade1`
    FOREIGN KEY (`cidade_id`)
    REFERENCES `adminns`.`cidade` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`cliente_endereco_tipo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`cliente_endereco_tipo` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`cliente_endereco`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`cliente_endereco` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `cliente_id` INT NOT NULL,
  `endereco_id` INT NOT NULL,
  `endereco_cidade_id` INT NOT NULL,
  `numero` VARCHAR(15) NULL,
  `complemento` VARCHAR(255) NULL,
  `referencia` TEXT NULL,
  `cliente_endereco_tipo_id` INT NOT NULL,
  PRIMARY KEY (`id`, `cliente_id`, `endereco_id`, `endereco_cidade_id`, `cliente_endereco_tipo_id`),
  INDEX `fk_endereco_has_cliente_cliente1_idx` (`cliente_id` ASC),
  INDEX `fk_endereco_has_cliente_endereco1_idx` (`endereco_id` ASC, `endereco_cidade_id` ASC),
  INDEX `fk_cliente_endereco_cliente_endereco_tipo1_idx` (`cliente_endereco_tipo_id` ASC),
  CONSTRAINT `fk_endereco_has_cliente_endereco1`
    FOREIGN KEY (`endereco_id` , `endereco_cidade_id`)
    REFERENCES `adminns`.`endereco` (`id` , `cidade_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_endereco_has_cliente_cliente1`
    FOREIGN KEY (`cliente_id`)
    REFERENCES `adminns`.`cliente` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cliente_endereco_cliente_endereco_tipo1`
    FOREIGN KEY (`cliente_endereco_tipo_id`)
    REFERENCES `adminns`.`cliente_endereco_tipo` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`pedido`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`pedido` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `data` DATETIME NULL,
  `total` DECIMAL NULL,
  `aamortizar` DECIMAL NULL,
  `pedido_formapagamento_id` INT NOT NULL,
  `pedido_status_id` INT NOT NULL,
  `user_module_id` INT UNSIGNED NOT NULL,
  `user_module_module_id` INT UNSIGNED NOT NULL,
  `user_module_user_id` INT UNSIGNED NOT NULL,
  `cliente_id` INT NOT NULL,
  PRIMARY KEY (`id`, `pedido_formapagamento_id`, `pedido_status_id`, `cliente_id`),
  INDEX `fk_pedido_pedido_formapagamento1_idx` (`pedido_formapagamento_id` ASC),
  INDEX `fk_pedido_pedido_status1_idx` (`pedido_status_id` ASC),
  INDEX `fk_pedido_user_module1_idx` (`user_module_id` ASC, `user_module_module_id` ASC, `user_module_user_id` ASC),
  INDEX `fk_pedido_cliente1_idx` (`cliente_id` ASC),
  CONSTRAINT `fk_pedido_pedido_formapagamento1`
    FOREIGN KEY (`pedido_formapagamento_id`)
    REFERENCES `adminns`.`pedido_formapagamento` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedido_pedido_status1`
    FOREIGN KEY (`pedido_status_id`)
    REFERENCES `adminns`.`pedido_status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedido_user_module1`
    FOREIGN KEY (`user_module_id` , `user_module_module_id` , `user_module_user_id`)
    REFERENCES `adminns`.`user_module` (`id` , `module_id` , `user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedido_cliente1`
    FOREIGN KEY (`cliente_id`)
    REFERENCES `adminns`.`cliente` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`contato_tipo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`contato_tipo` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`contato`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`contato` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '			',
  `nome` VARCHAR(255) NULL,
  `cargo` VARCHAR(50) NULL,
  `aniversario` DATE NULL,
  `padrao` TINYINT(1) NULL,
  `cliente_id` INT NOT NULL,
  `contato_tipo_id` INT NOT NULL,
  PRIMARY KEY (`id`, `cliente_id`, `contato_tipo_id`),
  INDEX `fk_contato_cliente1_idx` (`cliente_id` ASC),
  INDEX `fk_contato_contato_tipo1_idx` (`contato_tipo_id` ASC),
  CONSTRAINT `fk_contato_cliente1`
    FOREIGN KEY (`cliente_id`)
    REFERENCES `adminns`.`cliente` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contato_contato_tipo1`
    FOREIGN KEY (`contato_tipo_id`)
    REFERENCES `adminns`.`contato_tipo` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`contato_email`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`contato_email` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NULL,
  `pessoal` TINYINT(1) NULL,
  `contato_id` INT NOT NULL,
  `contato_cliente_id` INT NOT NULL,
  `contato_contato_tipo_id` INT NOT NULL,
  PRIMARY KEY (`id`, `contato_id`, `contato_cliente_id`, `contato_contato_tipo_id`),
  INDEX `fk_contato_email_contato1_idx` (`contato_id` ASC, `contato_cliente_id` ASC, `contato_contato_tipo_id` ASC),
  CONSTRAINT `fk_contato_email_contato1`
    FOREIGN KEY (`contato_id` , `contato_cliente_id` , `contato_contato_tipo_id`)
    REFERENCES `adminns`.`contato` (`id` , `cliente_id` , `contato_tipo_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`contato_telefone`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`contato_telefone` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `telefone` VARCHAR(45) NULL,
  `celular` TINYINT(1) NULL,
  `contato_id` INT NOT NULL,
  `contato_cliente_id` INT NOT NULL,
  `contato_contato_tipo_id` INT NOT NULL,
  PRIMARY KEY (`id`, `contato_id`, `contato_cliente_id`, `contato_contato_tipo_id`),
  INDEX `fk_contato_telefone_contato1_idx` (`contato_id` ASC, `contato_cliente_id` ASC, `contato_contato_tipo_id` ASC),
  CONSTRAINT `fk_contato_telefone_contato1`
    FOREIGN KEY (`contato_id` , `contato_cliente_id` , `contato_contato_tipo_id`)
    REFERENCES `adminns`.`contato` (`id` , `cliente_id` , `contato_tipo_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`pedido_formapagamento`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`pedido_formapagamento` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`pedido_status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`pedido_status` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`pedido`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`pedido` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `data` DATETIME NULL,
  `total` DECIMAL NULL,
  `aamortizar` DECIMAL NULL,
  `pedido_formapagamento_id` INT NOT NULL,
  `pedido_status_id` INT NOT NULL,
  `user_module_id` INT UNSIGNED NOT NULL,
  `user_module_module_id` INT UNSIGNED NOT NULL,
  `user_module_user_id` INT UNSIGNED NOT NULL,
  `cliente_id` INT NOT NULL,
  PRIMARY KEY (`id`, `pedido_formapagamento_id`, `pedido_status_id`, `cliente_id`),
  INDEX `fk_pedido_pedido_formapagamento1_idx` (`pedido_formapagamento_id` ASC),
  INDEX `fk_pedido_pedido_status1_idx` (`pedido_status_id` ASC),
  INDEX `fk_pedido_user_module1_idx` (`user_module_id` ASC, `user_module_module_id` ASC, `user_module_user_id` ASC),
  INDEX `fk_pedido_cliente1_idx` (`cliente_id` ASC),
  CONSTRAINT `fk_pedido_pedido_formapagamento1`
    FOREIGN KEY (`pedido_formapagamento_id`)
    REFERENCES `adminns`.`pedido_formapagamento` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedido_pedido_status1`
    FOREIGN KEY (`pedido_status_id`)
    REFERENCES `adminns`.`pedido_status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedido_user_module1`
    FOREIGN KEY (`user_module_id` , `user_module_module_id` , `user_module_user_id`)
    REFERENCES `adminns`.`user_module` (`id` , `module_id` , `user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedido_cliente1`
    FOREIGN KEY (`cliente_id`)
    REFERENCES `adminns`.`cliente` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`pedido_notafiscal`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`pedido_notafiscal` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `data` DATETIME NULL,
  `total` DECIMAL NULL,
  `pedido_id` INT NOT NULL,
  `pedido_pedido_formapagamento_id` INT NOT NULL,
  `pedido_pedido_status_id` INT NOT NULL,
  PRIMARY KEY (`id`, `pedido_id`, `pedido_pedido_formapagamento_id`, `pedido_pedido_status_id`),
  INDEX `fk_pedido_notafiscal_pedido1_idx` (`pedido_id` ASC, `pedido_pedido_formapagamento_id` ASC, `pedido_pedido_status_id` ASC),
  CONSTRAINT `fk_pedido_notafiscal_pedido1`
    FOREIGN KEY (`pedido_id` , `pedido_pedido_formapagamento_id` , `pedido_pedido_status_id`)
    REFERENCES `adminns`.`pedido` (`id` , `pedido_formapagamento_id` , `pedido_status_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`cliente_pedido`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`cliente_pedido` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `pedido_id` INT NOT NULL,
  `pedido_pedido_formapagamento_id` INT NOT NULL,
  `pedido_pedido_status_id` INT NOT NULL,
  `cliente_id` INT NOT NULL,
  PRIMARY KEY (`id`, `pedido_id`, `pedido_pedido_formapagamento_id`, `pedido_pedido_status_id`, `cliente_id`),
  INDEX `fk_pedido_has_cliente_cliente1_idx` (`cliente_id` ASC),
  INDEX `fk_pedido_has_cliente_pedido1_idx` (`pedido_id` ASC, `pedido_pedido_formapagamento_id` ASC, `pedido_pedido_status_id` ASC),
  CONSTRAINT `fk_pedido_has_cliente_pedido1`
    FOREIGN KEY (`pedido_id` , `pedido_pedido_formapagamento_id` , `pedido_pedido_status_id`)
    REFERENCES `adminns`.`pedido` (`id` , `pedido_formapagamento_id` , `pedido_status_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedido_has_cliente_cliente1`
    FOREIGN KEY (`cliente_id`)
    REFERENCES `adminns`.`cliente` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `adminns`.`produto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`produto` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NULL,
  `descricao` TEXT NULL,
  `garantia` VARCHAR(255) NULL,
  `kit` TINYINT(1) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`pedido_produto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`pedido_produto` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `produto_id` INT NOT NULL,
  `pedido_id` INT NOT NULL,
  `pedido_formapagamento_id` INT NOT NULL,
  `pedido_status_id` INT NOT NULL,
  `cliente_id` INT NOT NULL,
  `quantidade` INT NULL,
  INDEX `fk_produto_has_pedido_pedido1_idx` (`pedido_id` ASC, `pedido_formapagamento_id` ASC, `pedido_status_id` ASC, `cliente_id` ASC),
  INDEX `fk_produto_has_pedido_produto1_idx` (`produto_id` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_produto_has_pedido_produto1`
    FOREIGN KEY (`produto_id`)
    REFERENCES `adminns`.`produto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_produto_has_pedido_pedido1`
    FOREIGN KEY (`pedido_id` , `pedido_formapagamento_id` , `pedido_status_id` , `cliente_id`)
    REFERENCES `adminns`.`pedido` (`id` , `pedido_formapagamento_id` , `pedido_status_id` , `cliente_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`produto_rastreavel`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`produto_rastreavel` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `numerodeserie` VARCHAR(45) NULL,
  `produto_id` INT NOT NULL,
  PRIMARY KEY (`id`, `produto_id`),
  INDEX `fk_produto_rastreavel_produto1_idx` (`produto_id` ASC),
  CONSTRAINT `fk_produto_rastreavel_produto1`
    FOREIGN KEY (`produto_id`)
    REFERENCES `adminns`.`produto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`produto_categoria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`produto_categoria` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(45) NULL,
  `produto_categoria_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_produto_categoria_produto_categoria1_idx` (`produto_categoria_id` ASC),
  CONSTRAINT `fk_produto_categoria_produto_categoria1`
    FOREIGN KEY (`produto_categoria_id`)
    REFERENCES `adminns`.`produto_categoria` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`produto_produto_categoria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`produto_produto_categoria` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `categoria_id` INT NOT NULL,
  `produto_id` INT NOT NULL,
  PRIMARY KEY (`id`, `categoria_id`, `produto_id`),
  INDEX `fk_categoria_has_produto_produto1_idx` (`produto_id` ASC),
  INDEX `fk_categoria_has_produto_categoria1_idx` (`categoria_id` ASC),
  CONSTRAINT `fk_categoria_has_produto_categoria1`
    FOREIGN KEY (`categoria_id`)
    REFERENCES `adminns`.`produto_categoria` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_categoria_has_produto_produto1`
    FOREIGN KEY (`produto_id`)
    REFERENCES `adminns`.`produto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`suporte_status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`suporte_status` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(150) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`suporte_ranking`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`suporte_ranking` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ranking` INT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`suporte_categoria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`suporte_categoria` (
  `id` INT NOT NULL,
  `descricao` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`suporte`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`suporte` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `produto_id` INT NOT NULL,
  `pedido_id` INT NOT NULL,
  `contato_id` INT NOT NULL,
  `contato_cliente_id` INT NOT NULL,
  `contato_tipo_id` INT NOT NULL,
  `suporte_status_id` INT NOT NULL,
  `suporte_ranking_id` INT NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `suporte_categoria_id` INT NOT NULL,
  `datahora` DATETIME NULL,
  `custo` DECIMAL NULL,
  `valor` DECIMAL NULL,
  PRIMARY KEY (`id`, `suporte_categoria_id`),
  INDEX `fk_suporte_contato1_idx` (`contato_id` ASC),
  INDEX `fk_suporte_suporte_status1_idx` (`suporte_status_id` ASC),
  INDEX `fk_suporte_suporte_ranking1_idx` (`suporte_ranking_id` ASC),
  INDEX `fk_suporte_user1_idx` (`user_id` ASC),
  INDEX `fk_suporte_suporte_categoria1_idx` (`suporte_categoria_id` ASC),
  CONSTRAINT `fk_suporte_pedido_produto1`
    FOREIGN KEY (`id`)
    REFERENCES `adminns`.`pedido_produto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_suporte_contato1`
    FOREIGN KEY (`contato_id`)
    REFERENCES `adminns`.`contato` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_suporte_suporte_status1`
    FOREIGN KEY (`suporte_status_id`)
    REFERENCES `adminns`.`suporte_status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_suporte_suporte_ranking1`
    FOREIGN KEY (`suporte_ranking_id`)
    REFERENCES `adminns`.`suporte_ranking` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_suporte_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `adminns`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_suporte_suporte_categoria1`
    FOREIGN KEY (`suporte_categoria_id`)
    REFERENCES `adminns`.`suporte_categoria` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`suporte_observacao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`suporte_observacao` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(45) NULL,
  `observacao` VARCHAR(45) NULL,
  `datetime` VARCHAR(45) NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `suporte_id` INT NOT NULL,
  PRIMARY KEY (`id`, `suporte_id`),
  INDEX `fk_suporte_observacao_user1_idx` (`user_id` ASC),
  INDEX `fk_suporte_observacao_suporte2_idx` (`suporte_id` ASC),
  CONSTRAINT `fk_suporte_observacao_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `adminns`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_suporte_observacao_suporte2`
    FOREIGN KEY (`suporte_id`)
    REFERENCES `adminns`.`suporte` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`produto_acessorio`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`produto_acessorio` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(45) NULL,
  `produto_id` INT NOT NULL,
  PRIMARY KEY (`id`, `produto_id`),
  INDEX `fk_produto_acessorio_produto1_idx` (`produto_id` ASC),
  CONSTRAINT `fk_produto_acessorio_produto1`
    FOREIGN KEY (`produto_id`)
    REFERENCES `adminns`.`produto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`produto_erro`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`produto_erro` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(255) NULL,
  `descricao` TEXT NULL,
  `solucao` TEXT NULL,
  `data` DATETIME NULL,
  `produto_erroscol` VARCHAR(45) NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `produto_id` INT NOT NULL,
  `suporte_observacao_id` INT NOT NULL,
  `suporte_observacao_suporte_id` INT NOT NULL,
  `produto_acessorio_id` INT NULL,
  `mensagemdeerro` VARCHAR(45) NULL,
  PRIMARY KEY (`id`, `user_id`, `produto_id`),
  INDEX `fk_produto_erro_user1_idx` (`user_id` ASC),
  INDEX `fk_produto_erro_produto1_idx` (`produto_id` ASC),
  INDEX `fk_produto_erro_suporte_observacao1_idx` (`suporte_observacao_id` ASC, `suporte_observacao_suporte_id` ASC),
  INDEX `fk_produto_erro_produto_acessorio1_idx` (`produto_acessorio_id` ASC),
  CONSTRAINT `fk_produto_erro_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `adminns`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_produto_erro_produto1`
    FOREIGN KEY (`produto_id`)
    REFERENCES `adminns`.`produto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_produto_erro_suporte_observacao1`
    FOREIGN KEY (`suporte_observacao_id` , `suporte_observacao_suporte_id`)
    REFERENCES `adminns`.`suporte_observacao` (`id` , `suporte_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_produto_erro_produto_acessorio1`
    FOREIGN KEY (`produto_acessorio_id`)
    REFERENCES `adminns`.`produto_acessorio` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `adminns`.`suporte_observacao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `adminns`.`suporte_observacao` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(45) NULL,
  `observacao` VARCHAR(45) NULL,
  `datetime` VARCHAR(45) NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `suporte_id` INT NOT NULL,
  PRIMARY KEY (`id`, `suporte_id`),
  INDEX `fk_suporte_observacao_user1_idx` (`user_id` ASC),
  INDEX `fk_suporte_observacao_suporte2_idx` (`suporte_id` ASC),
  CONSTRAINT `fk_suporte_observacao_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `adminns`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_suporte_observacao_suporte2`
    FOREIGN KEY (`suporte_id`)
    REFERENCES `adminns`.`suporte` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
