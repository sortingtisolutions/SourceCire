<?php
defined('BASEPATH') or exit('No se permite acceso directo');

//////////////////////////////////////
// Valores de uri
/////////////////////////////////////

define('URI', $_SERVER['REQUEST_URI']);

define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);

//////////////////////////////////////
// Valores de rutas
/////////////////////////////////////

define('FOLDER_PATH', '/TesteoCire');               /* DESARROLLO LOCAL */
//define('FOLDER_PATH', '/TesteoCire');               /* DESARROLLO REMOTO */
//define('FOLDER_PATH', '/SourceCire');               /* DESARROLLO REMOTO */


define('ROOT', $_SERVER['DOCUMENT_ROOT']);

define('PATH_VIEWS', FOLDER_PATH . '/app/views/');

define('PATH_ASSETS', FOLDER_PATH . '/app/assets/');

define('PATH_CONTROLLERS', 'app/controllers/');

define('HELPER_PATH', 'system/helpers/');

define('LIBS_ROUTE', ROOT . FOLDER_PATH . '/system/libs/');

define('FULL_PATH', ROOT . FOLDER_PATH);


//////////////////////////////////////
// Valores de core
/////////////////////////////////////

define('CORE', 'system/core/');
define('DEFAULT_CONTROLLER', 'Login');

//////////////////////////////////////
// Valores de base de datos
/////////////////////////////////////

//////////////////////////////////////
// Parametros para conexión
//////////////////////////////////////

define('HOST', 'localhost');
define('USER', 'root');
define('PASSWORD', '');
define('DB_NAME', 'cttapp_cire');

define('HOSTB', 'localhost');
define('USERB', 'root');
define('PASSWORDB', '');
define('DB_NAMEB', 'cttapp_back_projects');

define('HOST1', 'localhost');
define('USER1', 'cttapp_user_qry1');
define('PASSWORD1', '');
define('DB_NAME1', 'cttapp_cire');

define('HOST2', 'localhost');
define('USER2', 'cttapp_user_qry2');
define('PASSWORD2', '');
define('DB_NAME2', 'cttapp_cire');

define('HOST3', 'localhost');
define('USER3', 'cttapp_user_qry3');
define('PASSWORD3', '');
define('DB_NAME3', 'cttapp_cire');


//////////////////////////////////////
// Valores configuracion
/////////////////////////////////////

define('ERROR_REPORTING_LEVEL', -1);
