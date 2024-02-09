<?php
defined('BASEPATH') or exit('No se permite acceso directo');
require( ROOT . PATH_ASSETS.  'ssp.class.php' );

class LocationsModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

//Guarda proveedor  ***
	public function SaveLocation($params)
	{
		$loc_type_location	= $this->db->real_escape_string($params['loc_type_location']);

		$qry = "INSERT INTO ctt_location(loc_type_location) 
				VALUES (UPPER('$loc_type_location'))";
		$this->db->query($qry);	
		$loc_id = $this->db->insert_id;
		return $loc_id;

	}
	
// Optiene los Usuaios existentes
	public function GetLocations($params)
	{
	
		$qry = "SELECT * FROM ctt_location ";
		return $this->db->query($qry);
	}

    public function UpdateLocation($params)
	{

		$loc_type_location	= $this->db->real_escape_string($params['loc_type_location']);
		$loc_id	= $this->db->real_escape_string($params['loc_id']);
		$qry = " UPDATE ctt_location
					SET loc_type_location		= UPPER('$loc_type_location')
				WHERE loc_id = '$loc_id';";
		$this->db->query($qry);	
			
		return $loc_id;
	}

    //borra proveedor
	public function DeleteLocation($params)
	{
        $loc_id 	= $this->db->real_escape_string($params['loc_id']);
		$qry = "DELETE FROM ctt_location WHERE loc_id = $loc_id"; 
        return $this->db->query($qry);
	}

}