<?php
defined('BASEPATH') or exit('No se permite acceso directo');
require( ROOT . PATH_ASSETS.  'ssp.class.php' );

class AreasModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

//Guarda proveedor  ***
	public function SaveArea($params)
	{
		$are_name 	= $this->db->real_escape_string($params['are_name']);
		$are_status 	= $this->db->real_escape_string($params['are_status']);

		$qry = "INSERT INTO ctt_areas(are_name, are_status) 
				VALUES (UPPER('$are_name'),$are_status)";
		$this->db->query($qry);	
		$are_id = $this->db->insert_id;
		return $are_id;

	}
	
// Optiene los Usuaios existentes
	public function GetAreas($params)
	{
	
		$qry = "SELECT * FROM ctt_areas where are_status=1 order by are_id ASC";
		return $this->db->query($qry);
	}

    public function UpdateArea($params)
	{

		$are_id 	= $this->db->real_escape_string($params['are_id']);
		$are_name 	= $this->db->real_escape_string($params['are_name']);
		$are_status 	= $this->db->real_escape_string($params['are_status']);

		$qry = " UPDATE ctt_areas
					SET are_name		= UPPER('$are_name'),
						are_status 	= $are_status
				WHERE are_id = '$are_id';";
		$this->db->query($qry);	
			
		return $are_id;
	}

    //borra proveedor
	public function DeleteArea($params)
	{
        $are_id 	= $this->db->real_escape_string($params['are_id']);
		$qry = "UPDATE ctt_areas
				SET are_status = 0
				WHERE are_id = $are_id";
        return $this->db->query($qry);
	}
}