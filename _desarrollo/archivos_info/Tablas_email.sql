/* v_2.1.5 */
CREATE TABLE `tbl_flags_email` (
	`fem_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'id de la bandera del email',
	`fem_descripcion` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Descripcion de la bandera' COLLATE 'utf8mb4_general_ci',
	`fem_code` INT(2) NULL DEFAULT '0' COMMENT 'Codigo de la bandera',
	`fem_status` INT(1) NULL DEFAULT '0' COMMENT 'Encendido=1 apagado=0',
	PRIMARY KEY (`fem_id`) USING BTREE
)
COMMENT='Banderas para controlar el envio de los correos'
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

ALTER TABLE `ctt_employees`
	CHANGE COLUMN `emp_number` `emp_number` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Numero del empleado' COLLATE 'utf8mb4_general_ci' AFTER `emp_id`,
	CHANGE COLUMN `emp_fullname` `emp_fullname` VARCHAR(100) NOT NULL DEFAULT '' COMMENT 'Nombre del empleado' COLLATE 'utf8mb4_general_ci' AFTER `emp_number`,
	CHANGE COLUMN `emp_area` `emp_area` VARCHAR(50) NULL DEFAULT '' COMMENT 'Area a la que pertenece el empleado' COLLATE 'utf8mb4_general_ci' AFTER `emp_fullname`,
	ADD COLUMN `emp_email` VARCHAR(50) NULL DEFAULT '' COMMENT 'Email del empleado' AFTER `emp_area`,
	CHANGE COLUMN `emp_report_to` `emp_report_to` INT(11) NULL DEFAULT 0 COMMENT 'ID del empleado jefe inmediato relacion asi mismo' AFTER `emp_email`,
	CHANGE COLUMN `emp_status` `emp_status` VARCHAR(1) NULL DEFAULT 1 COMMENT 'Estatus del empleado 1-Activo, 0-Inactivo' COLLATE 'utf8mb4_general_ci' AFTER `emp_report_to`;
