-- Tabla de pagos adelantados

CREATE TABLE `ctt_prepayments` (
	`prp_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del Registro',
	`prp_folio` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Folio del documento que ampara el adelanto' COLLATE 'utf8mb4_general_ci',
	`prp_date_doc` DATE NULL DEFAULT NULL COMMENT 'Fecha del documento que ampara adelanto',
	`prp_date_register` DATETIME NOT NULL COMMENT 'Fecha de registro del movimiento',
	`prp_amount` DOUBLE NOT NULL DEFAULT '0' COMMENT 'Monto adelantado',
	`prp_root_account` VARCHAR(30) NULL DEFAULT NULL COMMENT 'Cuenta de Origen' COLLATE 'utf8mb4_general_ci',
	`prp_deposit_account` VARCHAR(30) NULL DEFAULT NULL COMMENT 'Cuenta de deposito' COLLATE 'utf8mb4_general_ci',
	`prp_status` INT(1) NULL DEFAULT '0' COMMENT '0=No asignado 1=Asignado',
	`prp_description` VARCHAR(200) NULL DEFAULT NULL COMMENT 'Descripcion especial del movimiento' COLLATE 'utf8mb4_general_ci',
	`wtp_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Id de la Forma de pago o deposito',
	`pjt_id` INT(11) NULL DEFAULT NULL COMMENT 'Id del Proyecto',
	`cus_id` INT(11) NULL DEFAULT NULL COMMENT 'Id del Cliente',
	`emp_id` INT(11) NOT NULL COMMENT 'Id del Empleado',
	PRIMARY KEY (`prp_id`) USING BTREE
)
COMMENT='Registro de Pagos adelantados a proyectos'
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=2
;

UPDATE `cttapp_cire`.`ctt_menu` SET `mnu_parent`=41, `mnu_order`=3 WHERE  `mnu_id`=39;

INSERT INTO ctt_modules (mod_code, mod_name, mod_description, mod_item) 
VALUES ('s-prepayment', 'Prepagos', 'Modulo de Registro de Pre-pagos', 'PrePayments');

INSERT INTO ctt_menu (mnu_parent, mnu_item, mnu_description, mnu_order, mod_id) 
VALUES (44, 'Registro Pre-Pagos', 'seccion para registrar pre-pagos', 2, 76);

Insert into ctt_users_modules (usr_id, mod_id) values (1,76);

-- Relacion de prepagos con proyectos seccion BASE
CREATE TABLE `ctt_prepayments_recorded` (
	`pyr_id` INT(11) NOT NULL AUTO_INCREMENT,
	`pyr_folio` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Folio o referencia del documento pagado' COLLATE 'utf8mb4_general_ci',
	`pyr_date_paid` DATETIME NULL DEFAULT NULL COMMENT 'Fecha del documento pagado',
	`pyr_date_done` DATETIME NULL DEFAULT current_timestamp() COMMENT 'Fecha del registro de pago',
	`pyr_amount` DOUBLE NULL DEFAULT '0' COMMENT 'Monto aplicado del adelanto',
	`pjt_base_amount` DOUBLE NULL DEFAULT '0' COMMENT 'Monto Base del proyecto considerado',
	`pyr_outcome` DOUBLE NULL DEFAULT '0' COMMENT 'Diferencia entre Base y aplicado',
	`clt_id` INT(11) NULL DEFAULT NULL COMMENT 'Relacion con Id de la Cuenta x Pagar',
	`pjt_id` INT(11) NULL DEFAULT NULL COMMENT 'Relacion con Id del Proyecto',
	`wtp_id` INT(11) NULL DEFAULT NULL COMMENT 'Relacion con la forma del pago',
	`emp_id` INT(11) NULL DEFAULT '0' COMMENT 'Id de empleado que registro pago',
	PRIMARY KEY (`pyr_id`) USING BTREE
)
COMMENT='Tabla que registra la aplicacion de prepagos a proyectos'
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DYNAMIC
;

ALTER TABLE `ctt_prepayments_recorded`
	ADD COLUMN `prp_id` INT(11) NULL DEFAULT '0' COMMENT 'Id del documento de prepago' AFTER `emp_id`;