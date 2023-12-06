<?php
defined('BASEPATH') or exit('No se permite acceso directo');
require( ROOT . PATH_ASSETS.  'ssp.class.php' );

class ModulesModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

//Guarda proveedor  ***
	public function SaveModule($params)
	{
		$mod_name 			= $this->db->real_escape_string($params['mod_name']);
		$mod_code			= $this->db->real_escape_string($params['mod_code']);
		$mod_item 			= $this->db->real_escape_string($params['mod_item']);
		$mod_description 	= $this->db->real_escape_string($params['mod_description']);

		$qry = "INSERT INTO ctt_modules(mod_name, mod_code, mod_item, mod_description) 
				VALUES ('$mod_name','$mod_code','$mod_item','$mod_description')";
		$this->db->query($qry);	
		$mod_id = $this->db->insert_id;

		$qry = "INSERT INTO ctt_users_modules(usr_id, mod_id) 
				VALUES ('1','$mod_id')";
				
		$this->db->query($qry);	
		$mod_id = $this->db->insert_id;
		return $mod_id;

	}
	
// Optiene los Usuaios existentes
	public function GetModules($params)
	{
	
		$qry = "SELECT * FROM ctt_modules";
		return $this->db->query($qry);
	}

    public function UpdateModule($params)
	{

		$mod_id 	= $this->db->real_escape_string($params['mod_id']);
		$mod_name 	= $this->db->real_escape_string($params['mod_name']);
		$mod_code 	= $this->db->real_escape_string($params['mod_code']);
		$mod_item 	= $this->db->real_escape_string($params['mod_item']);
		$mod_description 	= $this->db->real_escape_string($params['mod_description']);

		$qry = " UPDATE ctt_modules
					SET mod_name		= '$mod_name',
						mod_code		= '$mod_code', 
						mod_item		= '$mod_item',
						mod_description	= '$mod_description'
				WHERE mod_id = '$mod_id';";
		$this->db->query($qry);	
			
		return $mod_id;
	}

    //borra proveedor
	public function DeleteModule($params)
	{
        $mod_id 	= $this->db->real_escape_string($params['mod_id']);
		/* $qry = "UPDATE ctt_modules
				SET are_status = 0
				WHERE mod_id = $mod_id"; */
		$qry = "DELETE FROM ctt_modules WHERE mod_id = $mod_id"; 
        return $this->db->query($qry);
	}

}