<?php
defined('BASEPATH') or exit('No se permite acceso directo');
require( ROOT . PATH_ASSETS.  'ssp.class.php' );

class ScoresModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

//Guarda proveedor  ***
	public function SaveScore($params)
	{
		$scr_values 	= $this->db->real_escape_string($params['scr_values']);
		$scr_description 	= $this->db->real_escape_string($params['scr_description']);

		$qry = "INSERT INTO ctt_scores(scr_values, scr_description) 
				VALUES (UPPER('$scr_values'),'$scr_description')";
		$this->db->query($qry);	
		$scr_id = $this->db->insert_id;
		return $scr_id;
	}
	
// Optiene los Usuaios existentes
	public function GetScores($params)
	{
	
		$qry = "SELECT * FROM ctt_scores";
		return $this->db->query($qry);
	}

    public function UpdateScore($params)
	{

		$scr_id 	= $this->db->real_escape_string($params['scr_id']);
		$scr_values 	= $this->db->real_escape_string($params['scr_values']);
		$scr_description 	= $this->db->real_escape_string($params['scr_description']);

		$qry = " UPDATE ctt_scores
					SET scr_values		= UPPER('$scr_values'),
						scr_description 	= '$scr_description'
				WHERE scr_id = '$scr_id';";
		$this->db->query($qry);	
			
		return $scr_id;
	}

    //borra proveedor
	public function DeleteScore($params)
	{
        $scr_id 	= $this->db->real_escape_string($params['scr_id']);
		/* $qry = "UPDATE ctt_scores
				SET are_status = 0
				WHERE scr_id = $scr_id"; */
		$qry = "DELETE FROM ctt_scores WHERE scr_id = $scr_id";
        return $this->db->query($qry);
	}

}