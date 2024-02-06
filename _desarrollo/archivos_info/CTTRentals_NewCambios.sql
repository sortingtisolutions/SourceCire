/* v_1.2.2 */
ALTER TABLE `ctt_subletting`
	ADD COLUMN `prd_id` INT(11) NULL DEFAULT NULL COMMENT 'ID del producto relacion con ctt_products' AFTER `cin_id`;

CREATE TABLE `ctt_attends_projects` (
	`atp_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la relacion de la atencion',
	`pjt_id` INT(11) NULL DEFAULT NULL COMMENT 'Id relacion con ctt_projects',
	`usr_id` INT(11) NULL DEFAULT NULL COMMENT 'Id relacion con ctt_users',
	`wta_id` INT(11) NULL DEFAULT NULL COMMENT 'Id relacion con ctt_way_to_attends',
	`status` INT NULL DEFAULT '1' COMMENT '1=activo, 0=Inactivo',
	PRIMARY KEY (`atp_id`) USING BTREE
)
COMMENT='Relacion de usuarios para atender las distintas facetas del proyecto'
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=3
;

CREATE TABLE `ctt_way_to_attends` (
	`wta_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la forma para atender un proyecto',
	`wta_descripcion` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Descripcion de la forma de atencion' COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`wta_id`) USING BTREE
)
COMMENT='Tabla con las distintas formas de atender un proyecto'
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

ALTER TABLE `ctt_projects`
	ADD COLUMN `pjt_whomake` INT(11) NOT NULL COMMENT 'Id de usuario que realiza proyecto' AFTER `pjttc_id`,
	ADD COLUMN `pjt_whoattend` INT(11) NOT NULL COMMENT 'Id de Usuario que atiende en almacen' AFTER `pjt_whomake`;

ALTER TABLE `ctt_projects`
	CHANGE COLUMN `pjt_whomake` `pjt_whomake` VARCHAR(100) NOT NULL DEFAULT '' COMMENT 'Id de usuario que realiza proyecto' COLLATE 'utf8mb4_general_ci' AFTER `pjttc_id`,
	CHANGE COLUMN `pjt_whoattend` `pjt_whoattend` VARCHAR(100) NOT NULL DEFAULT '' COMMENT 'Id de Usuario que atiende en almacen' COLLATE 'utf8mb4_general_ci' AFTER `pjt_whomake`;


ALTER TABLE `ctt_projects_status`
	CHANGE COLUMN `pjs_status` `pjs_status` INT NOT NULL COMMENT 'Status de proyecto' COLLATE 'utf8mb4_general_ci' FIRST;

ALTER TABLE `ctt_series`
	ADD COLUMN `str_id` INT(11) NULL DEFAULT '0' COMMENT 'ID relacion con ctt_stores almacenes' AFTER `prd_id_acc`;

CREATE TABLE `ctt_infocfdi` (
	`cfdi_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Id Datos complementarios CFDI',
	`cfdi_distancia` INT NULL COMMENT 'Distancia del Proyecto en KM',
	`cfid_transporte_ctt` VARCHAR(2) NULL DEFAULT NULL COMMENT 'Lleva Transporte de CTT' COLLATE 'utf8mb4_general_ci',
	`cfdi_operador_movil` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`cfdi_unidad_movil` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`cfdi_placas` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`cfdi_permiso_fed` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`cfdi_cantidad_eq` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`pjt_id` INT NULL DEFAULT NULL COMMENT 'Id relacion proyecto ctt_proyects',
	`cus_id` INT NULL DEFAULT NULL COMMENT 'id relacion clientes ctt_customers',
	PRIMARY KEY (`cfdi_id`)
)
COMMENT='Tabla complementaria de datos para el CFDI'
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `ctt_suppliers` (
	`sup_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID del proveedor',
	`sup_business_name` VARCHAR(100) NULL DEFAULT '' COMMENT 'Nombre de la empresa' COLLATE 'utf8mb4_general_ci',
	`sup_trade_name` VARCHAR(100) NOT NULL DEFAULT '' COMMENT 'Nombre Comercial' COLLATE 'utf8mb4_general_ci',
	`sup_contact` VARCHAR(100) NULL DEFAULT '' COMMENT 'Nombre del responsable' COLLATE 'utf8mb4_general_ci',
	`sup_rfc` VARCHAR(15) NULL DEFAULT '' COMMENT 'Registro Federal de Contribuyentes' COLLATE 'utf8mb4_general_ci',
	`sup_email` VARCHAR(100) NULL DEFAULT '' COMMENT 'Correo electrónico' COLLATE 'utf8mb4_general_ci',
	`sup_phone` VARCHAR(100) NULL DEFAULT '' COMMENT 'Número telefónico' COLLATE 'utf8mb4_general_ci',
	`sup_phone_extension` VARCHAR(10) NOT NULL DEFAULT '' COMMENT 'Extension del telefono' COLLATE 'utf8mb4_general_ci',
	`sup_status` VARCHAR(1) NULL DEFAULT '1' COMMENT 'Estatus del proveedor 1-Activo, 0-Inactivo' COLLATE 'utf8mb4_general_ci',
	`sup_credit` VARCHAR(5) NOT NULL DEFAULT '0' COMMENT 'Ofrece credito Si / No' COLLATE 'utf8mb4_general_ci',
	`sup_credit_days` INT(11) NOT NULL DEFAULT 0 COMMENT 'Dias de Credito',
	`sup_balance` INT(11) NULL DEFAULT 0 COMMENT 'Saldo',
	`sup_money_advance` VARCHAR(5) NOT NULL DEFAULT '0' COMMENT 'Anticipo Si / No' COLLATE 'utf8mb4_general_ci',
	`sup_advance_amount` INT(11) NULL DEFAULT 0 COMMENT 'Monto de anticipo',
	`sup_comments` VARCHAR(100) NOT NULL DEFAULT '' COMMENT 'Comentarios sobre el cliente' COLLATE 'utf8mb4_general_ci',
	`sut_id` INT(11) NULL DEFAULT NULL COMMENT 'Tipo de Proveedor',
	`sup_proof_tax_situation` VARCHAR(50) NULL DEFAULT '' COMMENT 'Constancia de situacion fiscal Si / No' COLLATE 'utf8mb4_general_ci',
	`sup_id_international_supplier` VARCHAR(50) NULL DEFAULT '' COMMENT 'Id Proveedor internacional' COLLATE 'utf8mb4_general_ci',
	`sup_description_id_is` VARCHAR(100) NULL DEFAULT '' COMMENT 'Descripcion del Proveedor Internacional' COLLATE 'utf8mb4_general_ci',
	`sup_way_pay` VARCHAR(100) NULL DEFAULT '' COMMENT 'Forma de pago' COLLATE 'utf8mb4_general_ci',
	`sup_bank` VARCHAR(100) NULL DEFAULT '' COMMENT 'Banco' COLLATE 'utf8mb4_general_ci',
	`sup_clabe` VARCHAR(25) NULL DEFAULT '' COMMENT 'Numero de cuenta' COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`sup_id`) USING BTREE
)
COMMENT='Proveedores de la empresa.'
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=87
;

-- *************  JUNIO 5 2023 ************
CREATE TABLE `ctt_who_attend_projects` (
	`whoatd_id` INT(11) NOT NULL AUTO_INCREMENT,
	`pjt_id` INT(11) NOT NULL COMMENT 'Id del Proyecto',
	`usr_id` INT(11) NOT NULL COMMENT 'Id del Usuario del sistema',
	`emp_id` INT(11) NOT NULL COMMENT 'id del empleado',
	`emp_fullname` VARCHAR(100) NULL DEFAULT '' COMMENT 'Nombre del empleado' COLLATE 'utf8mb4_general_ci',
	`are_id` INT(11) NOT NULL COMMENT 'Id de la area asignada al empleado',
	PRIMARY KEY (`whoatd_id`) USING BTREE
)
COMMENT='Empleados que atienden el proyecto en las diferentes etapas'
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

--*********** 16 de junio ********************
ALTER TABLE `ctt_projects`
	ADD COLUMN `edos_id` INT(11) NOT NULL COMMENT 'Id del estado en caso de foraneo' AFTER `pjttc_id`;

--*********** 26 de junio ********************
ALTER TABLE `ctt_subcategories`
	ADD COLUMN `sbc_order_print` INT(11) NOT NULL AFTER `cat_id`;

CREATE TABLE `ctt_estados_mex` (
	`edos_id` INT(11) NOT NULL AUTO_INCREMENT,
	`edos_name` VARCHAR(50) NULL DEFAULT NULL COMMENT 'nombre del estado' COLLATE 'utf8mb4_general_ci',
	`edos_abrev` VARCHAR(5) NULL DEFAULT NULL COMMENT 'abreviatura del estado' COLLATE 'utf8mb4_general_ci',
	`edos_capital` VARCHAR(50) NULL DEFAULT NULL COMMENT 'capital del estado' COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`edos_id`) USING BTREE
)
COMMENT='Catalogo de los estados de la republica mexicana'
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `ctt_freelances` (
	`free_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del Freelance',
	`free_cve` INT(11) NULL DEFAULT NULL COMMENT 'clave para control del Freelance',
	`free_name` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Nombre del Freelance' COLLATE 'utf8mb4_general_ci',
	`free_area_id` INT(11) NULL DEFAULT NULL COMMENT 'Id con relacion a ctt_area',
	`free_rfc` VARCHAR(15) NULL DEFAULT NULL COMMENT 'RFC del Freelance' COLLATE 'utf8mb4_general_ci',
	`free_adress` VARCHAR(200) NULL DEFAULT NULL COMMENT 'Direccion del Freelance' COLLATE 'utf8mb4_general_ci',
	`free_email` VARCHAR(100) NULL DEFAULT NULL COMMENT 'correo electronico' COLLATE 'utf8mb4_general_ci',
	`free_phone` VARCHAR(13) NULL DEFAULT NULL COMMENT 'telefono del freelance' COLLATE 'utf8mb4_general_ci',
	`free_unit` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Unidad movil que opera' COLLATE 'utf8mb4_general_ci',
	`free_plates` VARCHAR(10) NULL DEFAULT NULL COMMENT 'Placas de la unidad' COLLATE 'utf8mb4_general_ci',
	`free_license` VARCHAR(15) NULL DEFAULT NULL COMMENT 'Numero de licencia del Freelance' COLLATE 'utf8mb4_general_ci',
	`free_fed_perm` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Permiso federal del Freelance' COLLATE 'utf8mb4_general_ci',
	`free_clase` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Clase de la unidad movil' COLLATE 'utf8mb4_general_ci',
	`free_año` INT(4) NULL DEFAULT NULL COMMENT 'Año de la unidad movil',
	PRIMARY KEY (`free_id`) USING BTREE
)
COMMENT='Tabla de datos de los Freelance '
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

ALTER TABLE `ctt_series`
	CHANGE COLUMN `ser_import_petition` `ser_import_petition` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Numero de Pedimento de importación' COLLATE 'utf8mb4_general_ci' AFTER `ser_cost_import`;

ALTER TABLE `ctt_products`
	CHANGE COLUMN `prd_stock` `prd_stock` INT(11) NULL DEFAULT 0 COMMENT 'Cantidad existente en almacenes' AFTER `cin_id`;

ALTER TABLE `ctt_projects_content`
	ADD COLUMN `pjtvr_action` VARCHAR(2) NULL DEFAULT NULL COMMENT 'accion sobre el registro' COLLATE 'utf8mb4_general_ci' AFTER `pjtvr_id`;

ALTER TABLE `ctt_projects_version`
	ADD COLUMN `pjtvr_action` VARCHAR(2) NOT NULL DEFAULT '' COMMENT 'accion a seguir' COLLATE 'utf8mb4_general_ci' AFTER `pjt_id`;

ALTER TABLE `ctt_categories`
	ADD COLUMN `are_id` INT(11) NULL DEFAULT NULL COMMENT 'Id relacion con el area' AFTER `str_id`;

ALTER TABLE `ctt_categories`
	CHANGE COLUMN `cat_id` `cat_id` INT(11) NOT NULL COMMENT 'ID del catálogo' FIRST;

ALTER TABLE `ctt_categories`
	CHANGE COLUMN `str_id` `str_id` INT(11) NULL DEFAULT 0 COMMENT 'Id Relacion ctt_stores' AFTER `cat_status`,
	CHANGE COLUMN `are_id` `are_id` INT(11) NULL DEFAULT 0 COMMENT 'Id relacion con el area' AFTER `str_id`;

ALTER TABLE `ctt_project_change_reason`
	ADD COLUMN `pjtcr_code_stage` VARCHAR(3) NULL DEFAULT '' COMMENT 'Codigo del motivo del cambio de estatus' COLLATE 'utf8mb4_general_ci' AFTER `pjtcr_description`;

CREATE TABLE `ctt_assign_proyect` (
	`ass_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID de la asignacion',
	`pjt_id` INT(11) NULL DEFAULT NULL COMMENT 'Id relacion con ctt_projects',
	`free_id` INT(11) NULL DEFAULT NULL COMMENT 'Id relacion con ctt_freelances',
	`ass_date_start` DATETIME NULL DEFAULT NULL COMMENT 'Fecha de asignacion del freelance',
	`ass_date_end` DATETIME NULL DEFAULT NULL COMMENT 'Fecha en la que se completa la asignacion',
	`ass_coments` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Comentarios sobre la asignacion' COLLATE 'utf8mb4_general_ci',
	`ass_status` INT(11) NULL DEFAULT NULL COMMENT 'Estado actual de la asignacion. ',
	PRIMARY KEY (`ass_id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `ctt_maintenance_status` (
	`mts_id` INT(11) NOT NULL AUTO_INCREMENT,
	`mts_description` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`mts_id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `ctt_products_maintenance` (
	`pmt_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la tabla',
	`pmt_days` INT(11) NULL DEFAULT NULL COMMENT 'Dias de duracion de reparacion si existe',
	`pmt_hours` INT(11) NULL DEFAULT NULL COMMENT 'Horas de duracion de reparacion, si existe',
	`pmt_date_start` DATE NULL DEFAULT NULL COMMENT 'Fecha de comienzo de la reparación ',
	`pmt_date_end` DATE NULL DEFAULT NULL COMMENT 'Fecha final de la reparacion',
	`pmt_comments` VARCHAR(50) NULL DEFAULT '' COMMENT 'Comentarios sobre la reparacion' COLLATE 'utf8mb4_general_ci',
	`mts_id` INT(11) NULL DEFAULT '0' COMMENT '1. revisado 2. atendiendose 3. concluido',
	`ser_id` INT(11) NULL DEFAULT '0' COMMENT 'Id en relacion con la tabla ctt_series',
	`pjtcr_id` INT(11) NULL DEFAULT '0' COMMENT 'Id en relacion con la tabla ctt_project_change_reason',
	PRIMARY KEY (`pmt_id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

--*******************************
DROP TABLE IF EXISTS
CREATE TABLE `ctt_subletting` (
	`sub_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID del subarrendo',
	`sub_price` DECIMAL(10,2) NULL DEFAULT '0.00' COMMENT 'precio de renta del producto por unidad',
	`sub_quantity` INT(11) NULL DEFAULT NULL COMMENT 'Cantidad de piezas subarrendadas',
	`sub_collection_time` TIME NULL DEFAULT NULL,
	`sub_delivery_time` TIME NULL DEFAULT NULL,
	`sub_location` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`sub_name_provider_staff` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`sub_name_provider_ctt` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`sub_date_start` DATETIME NULL DEFAULT NULL COMMENT 'Fecha de inicio de periodo de subarrendo',
	`sub_date_end` DATETIME NULL DEFAULT NULL COMMENT 'Fecha de término de periodo de subarrendo',
	`sub_comments` VARCHAR(300) NOT NULL COMMENT 'Comentarios referentes al subarrendo' COLLATE 'utf8mb4_general_ci',
	`ser_id` INT(11) NULL DEFAULT NULL COMMENT 'Id del serial del producto relacion ctt_serial',
	`sup_id` INT(11) NULL DEFAULT NULL COMMENT 'Id del proveedor relacion ctt_suppliers',
	`prj_id` INT(11) NULL DEFAULT NULL COMMENT 'Id del proyecto ',
	`cin_id` INT(11) NULL DEFAULT NULL COMMENT 'ID del tipo de moneda relacion ctt_coin',
	`prd_id` INT(11) NULL DEFAULT NULL COMMENT 'ID del producto relacion con ctt_products',
	PRIMARY KEY (`sub_id`) USING BTREE
)
COMMENT='Tabla de situación de subarrendos'
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

--******* 02-ago-23 ********************
CREATE TABLE `ctt_products_maintenance` (
	`pmt_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de la tabla',
	`pmt_days` INT(11) NULL DEFAULT '0' COMMENT 'Dias de duracion de reparacion si existe',
	`pmt_hours` INT(11) NULL DEFAULT '0' COMMENT 'Horas de duracion de reparacion, si existe',
	`pmt_date_start` DATE NULL DEFAULT NULL COMMENT 'Fecha de comienzo de la reparación ',
	`pmt_date_end` DATE NULL DEFAULT NULL COMMENT 'Fecha final de la reparacion',
	`pmt_price` DECIMAL(20,2) NULL DEFAULT 0 COMMENT 'Costo del mantenimiento',
	`pmt_comments` VARCHAR(50) NULL DEFAULT '' COMMENT 'Comentarios sobre la reparacion' COLLATE 'utf8mb4_general_ci',
	`pmt_date_register` DATETIME NULL DEFAULT NULL COMMENT 'Fecha de registro de mantenimiento',
	`ser_id` INT(11) NULL DEFAULT '0' COMMENT 'Id en relacion con la tabla ctt_series',
	`pjt_id` INT(11) NULL DEFAULT '0' COMMENT 'Id en relacion a la tabla ctt_projects',
	`pjtcr_id` INT(11) NULL DEFAULT '0' COMMENT 'Id en relacion con la tabla ctt_project_change_reason',
	`mts_id` INT(11) NULL DEFAULT '0' COMMENT '1. Sin Atender 2. atendiendose 3. concluido',
	PRIMARY KEY (`pmt_id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;


--******* 15-ago-23 ********************
CREATE VIEW ctt_vw_list_products AS
SELECT pd.prd_id, pd.prd_sku, pd.prd_name, pd.prd_price, pd.prd_level, 
            pd.prd_insured, sb.sbc_name,cat_name,
    CASE 
        WHEN prd_level ='K' THEN 
            (SELECT prd_stock
                    FROM ctt_products WHERE prd_id = pd.prd_id)
        WHEN prd_level ='P' THEN 
            (SELECT prd_stock-fun_buscarentas(pd.prd_sku) 
                    FROM ctt_products WHERE prd_id = pd.prd_id)
        ELSE 
            (SELECT prd_stock-fun_buscarentas(pd.prd_sku) 
                    FROM ctt_products WHERE prd_id = pd.prd_id)
        END AS stock
FROM ctt_products AS pd
INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
INNER JOIN ctt_categories AS ct ON ct.cat_id=sb.cat_id
WHERE pd.prd_status = 1 AND pd.prd_visibility = 1 AND sb.cat_id NOT IN (16)
ORDER BY pd.prd_name;



CREATE TABLE `ctt_locacion_estado` (
	`lce_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID de la tabla',
	`lce_location` VARCHAR(50) NULL DEFAULT '' COMMENT 'Lugar de la locacion' COLLATE 'utf8mb4_general_ci',
	`edos_id` INT(11) NULL DEFAULT '0' COMMENT 'Relacion con la tabla estados_mex',
	`pjt_id` INT(11) NULL DEFAULT '0' COMMENT 'Relacion con la tabla project',
	PRIMARY KEY (`lce_id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=19
;

--************** 06 sep 23 *****************
CREATE TABLE `prc_into_crontab` (
	`prc_id` INT NULL COMMENT 'Id del proceso a ejecutar',
	`pjt_id` INT NULL COMMENT 'Id del Proyecto',
	`ver_id` INT NULL COMMENT 'Id del  version del documento',
	`pjtvr_id` INT NULL COMMENT 'Id de la version del proyecto',
	`date_register` DATETIME NULL DEFAULT CURRENT_TIMESTAMP() COMMENT 'Fecha y Hora actualizada',
	`prc_status` INT NULL DEFAULT 0 COMMENT 'Status del Proceso'
)
COMMENT='Tabla que registra los procesos a ejecutar en crontab para accesorios'
COLLATE='utf8mb4_general_ci'
;

--************** 26 sep 23 *****************
ALTER TABLE `ctt_documents_closure`
	CHANGE COLUMN `clo_total_proyects` `clo_total_proyects` DOUBLE UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Suma total de la renta del proyecto' AFTER `clo_id`,
	CHANGE COLUMN `clo_total_maintenance` `clo_total_maintenance` DOUBLE UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Captura del costo de mantenimiento' AFTER `clo_total_proyects`,
	CHANGE COLUMN `clo_total_expendables` `clo_total_expendables` DOUBLE UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Captura del Costo de la venta de expendables' AFTER `clo_total_maintenance`,
	CHANGE COLUMN `clo_total_diesel` `clo_total_diesel` DOUBLE UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Captura del costo del Combustible' AFTER `clo_total_expendables`,
	CHANGE COLUMN `clo_total_discounts` `clo_total_discounts` DOUBLE UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Monto total del descuento a aplicar' AFTER `clo_total_diesel`,
	CHANGE COLUMN `clo_comentarios` `clo_comentarios` VARCHAR(300) NULL DEFAULT '' COMMENT 'Comentarios al cierre' COLLATE 'utf8mb4_general_ci' AFTER `clo_fecha_cierre`;

ALTER TABLE `ctt_documents_closure`
	ADD COLUMN `clo_ver_closed` INT(11) NULL DEFAULT 0 COMMENT 'Version del documento de cierre' AFTER `clo_comentarios`;


--*************** 20 Oct 23 *****************
CREATE TABLE `Register_aplication_access` (
	`regacc_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Id_del registro de acceso',
	`reg_user_app` INT NOT NULL DEFAULT 0 COMMENT 'Id del empleado',
	`reg_user_db` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Usuario de la base de datos',
	`reg_date_in` DATETIME NULL DEFAULT NOW() COMMENT 'Fecha y hora del acceso',
	PRIMARY KEY (`regacc_id`)
)
COMMENT='Registro de los acceso a la aplicacion'
COLLATE='utf8mb4_general_ci'
;


/** INSERTAR NUEVO MODULO **/

INSERT INTO `ctt_menu` (`mnu_id`, `mnu_parent`, `mnu_item`, `mnu_description`, `mnu_order`, `mod_id`) VALUES
	(77, 2, 'Proyectos Padre', 'Seccion para editar y pre cancelar los proyectos padres', 13, 76);

INSERT INTO `ctt_modules` (`mod_id`, `mod_code`, `mod_name`, `mod_description`, `mod_item`) VALUES
	(76, 's-parents-proj', 'Proyectos padres', 'Modulo para editar y precancelar los datos de un proyecto padre', 'ParentsProjects');

INSERT INTO `ctt_users_modules` (`urm_id`, `usr_id`, `mod_id`) VALUES
	(509, 1, 76);


/** INSERTA EL NUEVO MENU PARA EL CRUD DE LOS MODULOS Y MENUS **/

INSERT INTO `ctt_modules` (`mod_code`, `mod_name`, `mod_description`, `mod_item`) VALUES
	('p-system', 'Sistema', 'Modulo para crear y editar modulos/menu', '#');

INSERT INTO `ctt_menu` (`mnu_parent`, `mnu_item`, `mnu_description`, `mnu_order`, `mod_id`) VALUES
	(0, 'Sistemas', 'Seccion para crear y editar modulos/menu', 14, ??);

INSERT INTO `ctt_users_modules` (`usr_id`, `mod_id`) VALUES
	(1, ??);
	
INSERT INTO `ctt_modules` (`mod_code`, `mod_name`, `mod_description`, `mod_item`) VALUES
	('s-modules', 'Modulo', 'Modulo para crear y editar modulos', 'Modules'),
	('s-menu', 'Menu', 'Modulo para añadir y editar el menu', 'Menu');

INSERT INTO `ctt_menu` (`mnu_parent`, `mnu_item`, `mnu_description`, `mnu_order`, `mod_id`) VALUES
	(??, 'Modulos', 'Seccion para crear y editar modulos', 1, ??),
	(??, 'Menú', 'Seccion para añadir y editar el menu', 2, ??);

INSERT INTO `ctt_users_modules` (`usr_id`, `mod_id`) VALUES
	(1, 78),
	(1, 79);
		
-- ************ 27 NOV 2023 ***********
CREATE TABLE `ctt_tracking_proyects` (
	`trck_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Id del Tracking',
	`trck_date_mov` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP() COMMENT 'Fecha Automatica del movimiento',
	`pjt_id` INT NULL COMMENT 'Id del Proyecto',
	`pjs_status` INT NULL COMMENT 'Id de status de referencia al movimiento',
	PRIMARY KEY (`trck_id`)
)
COMMENT='Tabla para registrar los movimientos de un proyecto desde la creacion del proyecto, hasta la salida a llamado'
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `ctt_load_products` (
	`prd_id` INT(10) NOT NULL AUTO_INCREMENT COMMENT 'ID del producto',
	`prd_sku` VARCHAR(15) NULL DEFAULT '' COMMENT 'SKU identificador del producto' COLLATE 'utf8mb4_general_ci',
	`prd_name` VARCHAR(200) NULL DEFAULT '' COMMENT 'Nombre del producto' COLLATE 'utf8mb4_general_ci',
	`prd_english_name` VARCHAR(100) NULL DEFAULT '' COMMENT 'Nombre del producto en ingles' COLLATE 'utf8mb4_general_ci',
	`prd_code_provider` VARCHAR(30) NULL DEFAULT '' COMMENT 'Código del producto segun proveedor' COLLATE 'utf8mb4_general_ci',
	`prd_name_provider` VARCHAR(100) NULL DEFAULT '' COMMENT 'Nombre del producto segun proveedor' COLLATE 'utf8mb4_general_ci',
	`prd_model` VARCHAR(50) NULL DEFAULT '' COMMENT 'Modelo del producto' COLLATE 'utf8mb4_general_ci',
	`prd_price` DECIMAL(10,2) NULL DEFAULT '0.00' COMMENT 'Precio unitario del producto',
	`prd_coin_type` VARCHAR(30) NULL DEFAULT '' COMMENT 'Tipo de moneda' COLLATE 'utf8mb4_general_ci',
	`prd_visibility` VARCHAR(1) NULL DEFAULT '1' COMMENT 'Visibilidad del producto en cotización 1-visible, 0-no visible' COLLATE 'utf8mb4_general_ci',
	`prd_comments` VARCHAR(300) NULL DEFAULT '' COMMENT 'Observaciones' COLLATE 'utf8mb4_general_ci',
	`prd_status` VARCHAR(1) NULL DEFAULT '1' COMMENT 'Estatus del producto 1-Activo, 0-Inactivo' COLLATE 'utf8mb4_general_ci',
	`prd_level` VARCHAR(1) NULL DEFAULT 'P' COMMENT 'Tipo de registro P-Producto, A-Accesorio, K-Paquete' COLLATE 'utf8mb4_general_ci',
	`prd_lonely` VARCHAR(2) NULL DEFAULT NULL COMMENT 'Renta solo (sin accesorio)' COLLATE 'utf8mb4_general_ci',
	`prd_insured` VARCHAR(1) NULL DEFAULT '1' COMMENT 'Cotiza seguro 1-si, 0-no' COLLATE 'utf8mb4_general_ci',
	`sbc_id` INT(10) NULL DEFAULT '0' COMMENT 'ID de la subcategoría relacion ctt_subcategories',
	`srv_id` INT(10) NULL DEFAULT '0' COMMENT 'ID del tipo de servicio relacion ctt_services',
	`cin_id` INT(10) NULL DEFAULT '0' COMMENT 'ID del tipo de moneda relacion ctt_coin',
	`prd_stock` INT(10) NULL DEFAULT '0' COMMENT 'Cantidad existente en almacenes',
	`prd_reserved` INT(10) NULL DEFAULT '0' COMMENT 'Cantidad reservado',
	`doc_id` INT(10) NULL DEFAULT '0' COMMENT 'Id del documento para relacionar la ficha técnica ctt_products_documents',
	`result` VARCHAR(100) NULL DEFAULT '' COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`prd_id`) USING BTREE,
	INDEX `ndx_prdsku` (`prd_sku`) USING BTREE
)
COMMENT='Productos de la empresa.'
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DYNAMIC
;

CREATE TABLE `ctt_load_series` (
	`ser_id` INT(10) NOT NULL AUTO_INCREMENT COMMENT 'ID de la serie',
	`ser_sku` VARCHAR(15) NULL DEFAULT NULL COMMENT 'SKU identificador del producto' COLLATE 'utf8mb4_general_ci',
	`ser_serial_number` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Numero de serie del producto' COLLATE 'utf8mb4_general_ci',
	`ser_cost` DECIMAL(10,2) NULL DEFAULT '0.00' COMMENT 'Costo unitario del producto',
	`ser_status` VARCHAR(1) NULL DEFAULT NULL COMMENT 'Estatus del producto 1-Activo, 0-Inactivo' COLLATE 'utf8mb4_general_ci',
	`ser_situation` VARCHAR(5) NULL DEFAULT NULL COMMENT 'Situación de estatus dentro del proceso ' COLLATE 'utf8mb4_general_ci',
	`ser_stage` VARCHAR(5) NULL DEFAULT NULL COMMENT 'Etapa dentro del proceso' COLLATE 'utf8mb4_general_ci',
	`ser_date_registry` DATETIME NULL DEFAULT 'CURRENT_TIMESTAMP' COMMENT 'Fecha de registro del producto',
	`ser_date_down` DATETIME NULL DEFAULT 'CURRENT_TIMESTAMP' COMMENT 'Fecha de baja del producto',
	`ser_reserve_count` INT(10) NULL DEFAULT '0' COMMENT 'Contador de rentas',
	`ser_behaviour` VARCHAR(1) NOT NULL COMMENT 'Comportamiento del producto C-Compra, R-Renta' COLLATE 'utf8mb4_general_ci',
	`ser_brand` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Marca del producto' COLLATE 'utf8mb4_general_ci',
	`ser_cost_import` INT(10) NOT NULL DEFAULT '0' COMMENT 'Costo individual de importacion',
	`ser_import_petition` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Numero de Pedimento de importación' COLLATE 'utf8mb4_general_ci',
	`ser_sum_ctot_cimp` DOUBLE(10,2) NULL DEFAULT '0.00' COMMENT 'Suma del costo + costo importacion',
	`ser_convert_cnac` DOUBLE(10,2) NULL DEFAULT '0.00' COMMENT 'Conversion del costo por tipo moneda',
	`ser_no_econo` VARCHAR(20) NULL DEFAULT NULL COMMENT 'Numero economico asignado por almacen' COLLATE 'utf8mb4_general_ci',
	`ser_comments` VARCHAR(200) NULL DEFAULT NULL COMMENT 'Comentarios sobre la serie del producto' COLLATE 'utf8mb4_general_ci',
	`prd_id` INT(10) NULL DEFAULT '0' COMMENT 'ID del producto relacion ctt_productos',
	`sup_id` INT(10) NULL DEFAULT '0' COMMENT 'ID de la proveedor relacion ctt_suppliers',
	`cin_id` INT(10) NULL DEFAULT '0' COMMENT 'ID del tipo de moneda relacion ctt_coin',
	`pjtdt_id` INT(10) NULL DEFAULT '0' COMMENT 'Id del detalle de proyecto relacion ctt_projects_detail',
	`prd_id_acc` INT(10) NULL DEFAULT '0' COMMENT 'id relacion accesorio en productos',
	`str_id` INT(10) NULL DEFAULT '0' COMMENT 'ID relacion con ctt_stores almacenes',
	`result` VARCHAR(100) NULL DEFAULT '' COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`ser_id`) USING BTREE,
	INDEX `indx_prd_id` (`prd_id`) USING BTREE,
	INDEX `prd_id_acc` (`prd_id_acc`) USING BTREE
)
COMMENT='Numero serie de productos correspondientes a un modelo.'
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DYNAMIC
;


-- *************** 21 Diciembre *************
ALTER TABLE `ctt_payments_applied`
	ADD COLUMN `pym_pending` DOUBLE NULL DEFAULT '0' COMMENT 'Monto pendiente' COLLATE 'utf8mb4_general_ci' AFTER `pym_amount`
	ADD COLUMN `pym_total` DOUBLE NULL DEFAULT '0' COMMENT 'Monto Total pagado' COLLATE 'utf8mb4_general_ci' AFTER `pym_pending`;


-- *************** 22 Diciembre *************
ALTER ALGORITHM = UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ctt_vw_stores_regiter` AS SELECT sp.str_id AS strId, se.ser_id as serId, prd.prd_sku as produsku, prd.prd_name as serlnumb, sum(sp.stp_quantity) as dateregs
	FROM ctt_products as prd
	INNER JOIN ctt_series as se ON prd.prd_id=se.prd_id
	INNER JOIN ctt_stores_products AS sp ON sp.ser_id = se.ser_id
	WHERE se.ser_status=1 AND sp.stp_quantity>0
	group by prd.prd_sku, prd.prd_name, prd.prd_level, sp.str_id
	ORDER BY se.ser_sku  ;

--******************** 30 Diciembre ************************
ALTER TABLE `ctt_projects_mice`
	ADD COLUMN `pjtvr_days_base_ant` INT(11) NULL DEFAULT NULL AFTER `pjtvr_days_base`;



--**********************************************
ALTER TABLE `ctt_projects_detail`
	ADD COLUMN `sttd_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'FK id de la relacion con el estatus del detalle' AFTER `pjtvr_id`;

CREATE TABLE `ctt_project_series_periods` (
	`pjspd_id` INT(11) NOT NULL AUTO_INCREMENT,
	`pjspd_days` INT(11) NULL DEFAULT NULL,
	`pjt_id` INT(11) NULL DEFAULT NULL,
	`ser_id` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`pjspd_id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1
;

CREATE TABLE `ctt_status_details` (
	`sttd_id` INT(11) NOT NULL AUTO_INCREMENT,
	`sttd_name` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`sttd_id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1
;

--*************************************************
ALTER TABLE `ctt_project_series_periods`
	ADD COLUMN `pjtdt_id` INT NULL AFTER `ser_id`;

--***************************************************
CREATE TABLE `ctt_error_message` (
	`erm_id` INT(11) NOT NULL AUTO_INCREMENT,
	`erm_title` VARCHAR(150) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	PRIMARY KEY (`erm_id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1
;

--**************************************************

ALTER TABLE ctt_products
	ADD COLUMN prd_type_asigned INT(10) NULL DEFAULT NULL COMMENT 'Tipo de asignacion al Producto en su relacion' AFTER prd_name_provider;

ALTER TABLE `ctt_products`
	CHANGE COLUMN `prd_type_asigned` `prd_type_asigned` VARCHAR(5) NULL DEFAULT NULL COMMENT 'Tipo de asignacion al Producto en su relacion' AFTER `prd_name_provider`;
ALTER TABLE `ctt_products_packages`
	ADD COLUMN `prd_type_asigned` VARCHAR(5) NULL DEFAULT NULL COMMENT 'Tipo de asignacion al Producto en su relacion' AFTER `pck_quantity`;


CREATE TABLE ctt_total_project_amount (
	tpa_id INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del registro',
	pjt_id INT(11) NULL DEFAULT NULL COMMENT 'Relacion con Id del Proyecto',
	tpa_amount DOUBLE NULL DEFAULT NULL COMMENT 'Suma total del proyecto',
	tpa_date_registed DATETIME NULL DEFAULT current_timestamp() COMMENT 'Fecha de actualizacion',
	PRIMARY KEY (tpa_id) USING BTREE,
	INDEX pjt_id (pjt_id) USING BTREE
)
COMMENT='Suma total del proyecto sin IVA'
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

ALTER TABLE `ctt_projects_detail`
	ADD COLUMN `prd_type_asigned` VARCHAR(5) NULL DEFAULT NULL AFTER `pjtdt_prod_sku`;

--************************

ALTER ALGORITHM = UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ctt_vw_list_products2` AS SELECT pd.prd_id, pd.prd_sku, pd.prd_name, pd.prd_price, pd.prd_level, pd.prd_type_asigned,
            pd.prd_insured, sb.sbc_name,cat_name,
    CASE 
        WHEN pd.prd_type_asigned ='KP' THEN 
            (SELECT prd_stock
                    FROM ctt_products WHERE prd_id = pd.prd_id)
        WHEN (pd.prd_type_asigned ='PI' OR pd.prd_type_asigned ='PV' OR pd.prd_type_asigned ='PF') THEN 
            (SELECT prd_stock-fun_buscarentas(pd.prd_sku) 
                    FROM ctt_products WHERE prd_id = pd.prd_id)
        ELSE 
            (SELECT prd_stock-fun_buscarentas(pd.prd_sku) 
                    FROM ctt_products WHERE prd_id = pd.prd_id)
        END AS stock, pd.sbc_id
FROM ctt_products AS pd
INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
INNER JOIN ctt_categories AS ct ON ct.cat_id=sb.cat_id
WHERE pd.prd_status = 1 AND pd.prd_visibility = 1 AND sb.cat_id NOT IN (16)
ORDER BY pd.prd_name  ;


ALTER TABLE `ctt_load_products`
	ADD COLUMN `prd_type_asigned` VARCHAR(5) NULL DEFAULT 'PI' AFTER `prd_insured`;


--***********05febrero24******************

CREATE TABLE `ctt_global_products` (
	`prd_id` INT(10) NOT NULL AUTO_INCREMENT COMMENT 'ID del producto',
	`prd_sku` VARCHAR(15) NULL DEFAULT '' COMMENT 'SKU identificador del producto' COLLATE 'utf8mb4_general_ci',
	`prd_name` VARCHAR(200) NULL DEFAULT '' COMMENT 'Nombre del producto' COLLATE 'utf8mb4_general_ci',
	`prd_price` DECIMAL(10,2) NULL DEFAULT '0.00' COMMENT 'Precio unitario del producto',
	`prd_visibility` VARCHAR(1) NULL DEFAULT '1' COMMENT 'Visibilidad del producto en cotización 1-visible, 0-no visible' COLLATE 'utf8mb4_general_ci',
	`prd_status` VARCHAR(1) NULL DEFAULT '1' COMMENT 'Estatus del producto 1-Activo, 0-Inactivo' COLLATE 'utf8mb4_general_ci',
	`prd_level` VARCHAR(1) NULL DEFAULT 'P' COMMENT 'Tipo de registro P-Producto, A-Accesorio, K-Paquete' COLLATE 'utf8mb4_general_ci',
	`prd_lonely` VARCHAR(2) NULL DEFAULT NULL COMMENT 'Renta solo (sin accesorio)' COLLATE 'utf8mb4_general_ci',
	`prd_insured` VARCHAR(1) NULL DEFAULT '1' COMMENT 'Cotiza seguro 1-si, 0-no' COLLATE 'utf8mb4_general_ci',
	`prd_stock` INT(10) NULL DEFAULT '0' COMMENT 'Cantidad existente en almacenes',
	`prd_reserved` INT(10) NULL DEFAULT '0' COMMENT 'Cantidad reservado',
	`prd_comments` VARCHAR(300) NULL DEFAULT '' COMMENT 'Observaciones' COLLATE 'utf8mb4_general_ci',
	`prd_model` VARCHAR(50) NULL DEFAULT '' COMMENT 'Modelo del producto' COLLATE 'utf8mb4_general_ci',
	`prd_coin_type` VARCHAR(30) NULL DEFAULT '' COMMENT 'Tipo de moneda' COLLATE 'utf8mb4_general_ci',
	`prd_code_provider` VARCHAR(30) NULL DEFAULT '' COMMENT 'Código del producto segun proveedor' COLLATE 'utf8mb4_general_ci',
	`prd_english_name` VARCHAR(100) NULL DEFAULT '' COMMENT 'Nombre del producto en ingles' COLLATE 'utf8mb4_general_ci',
	`prd_name_provider` VARCHAR(100) NULL DEFAULT '' COMMENT 'Nombre del producto segun proveedor' COLLATE 'utf8mb4_general_ci',
	`prd_type_asigned` VARCHAR(5) NULL DEFAULT NULL COMMENT 'Tipo de asignacion al Producto en su relacion' COLLATE 'utf8mb4_general_ci',
	`sbc_id` INT(10) NULL DEFAULT '0' COMMENT 'ID de la subcategoría relacion ctt_subcategories',
	`srv_id` INT(10) NULL DEFAULT '0' COMMENT 'ID del tipo de servicio relacion ctt_services',
	`cin_id` INT(10) NULL DEFAULT '0' COMMENT 'ID del tipo de moneda relacion ctt_coin',
	`doc_id` INT(10) NULL DEFAULT '0' COMMENT 'Id del documento para relacionar la ficha técnica ctt_products_documents',
	PRIMARY KEY (`prd_id`) USING BTREE,
	INDEX `ndx_prdsku` (`prd_sku`) USING BTREE
)
COMMENT='Productos de la empresa.'
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DYNAMIC
;

