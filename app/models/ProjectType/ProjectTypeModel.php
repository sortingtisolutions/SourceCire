<?php
defined('BASEPATH') or exit('No se permite acceso directo');
require( ROOT . PATH_ASSETS.  'ssp.class.php' );

class ProjectTypeModel extends Model
{
	
	public function __construct()
	{
		parent::__construct();
	}

//Guarda proveedor  ***
	public function SaveProjectType($params)
	{
		$pjttp_name 	= $this->db->real_escape_string($params['pjttp_name']);
		$pjttp_min_download 	= $this->db->real_escape_string($params['pjttp_min_download']);
		$pjttp_max_download 	= $this->db->real_escape_string($params['pjttp_max_download']);

		$qry = "INSERT INTO ctt_projects_type(pjttp_name, pjttp_min_download,pjttp_max_download) 
				VALUES (UPPER('$pjttp_name'),'$pjttp_min_download', '$pjttp_max_download')";
		$this->db->query($qry);	
		$are_id = $this->db->insert_id;
		return $are_id;

	}
	
// Optiene los Usuaios existentes
	public function GetProjectTypes($params)
	{
	
		$qry = "SELECT * FROM ctt_projects_type order by pjttp_id ASC";
		return $this->db->query($qry);
	}


    public function UpdateProjectType($params)
	{

		$pjttp_id 	= $this->db->real_escape_string($params['pjttp_id']);
		$pjttp_name 	= $this->db->real_escape_string($params['pjttp_name']);
		$pjttp_min_download 	= $this->db->real_escape_string($params['pjttp_min_download']);
		$pjttp_max_download 	= $this->db->real_escape_string($params['pjttp_max_download']);

		$qry = " UPDATE ctt_projects_type
					SET pjttp_name 	= '$pjttp_name',
						pjttp_min_download 	= $pjttp_min_download,
						pjttp_max_download 	= $pjttp_max_download
				WHERE pjttp_id = '$pjttp_id';";
		$this->db->query($qry);	
			
		return $pjttp_id;
	}

    //borra proveedor
	public function DeleteProjectType($params)
	{
        $pjttp_id 	= $this->db->real_escape_string($params['pjttp_id']);
		/* $qry = "UPDATE ctt_projects_type
				SET pjttp_status = 0
				WHERE pjttp_id = $pjttp_id"; */
		$qry = "DELETE FROM ctt_projects_type WHERE pjttp_id = $pjttp_id"; 
        return $this->db->query($qry);
	}
}