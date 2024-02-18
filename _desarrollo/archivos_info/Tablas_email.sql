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
