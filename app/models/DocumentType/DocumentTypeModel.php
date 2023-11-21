<?php
defined('BASEPATH') or exit('No se permite acceso directo');
require( ROOT . PATH_ASSETS.  'ssp.class.php' );

class DocumentTypeModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

//Guarda proveedor  ***
	public function SaveDocumentType($params)
	{
		$dot_name 	= $this->db->real_escape_string($params['dot_name']);
		$dot_status 	= $this->db->real_escape_string($params['dot_status']);
		$doc_code	= $this->db->real_escape_string($params['doc_code']);

		$qry = "INSERT INTO ctt_documents_type(dot_code, dot_name, dot_status) 
				VALUES (UPPER('$doc_code'), UPPER('$dot_name'),$dot_status)";
		$this->db->query($qry);	
		$dot_id = $this->db->insert_id;
		return $dot_id;

	}
	
// Optiene los Usuaios existentes
	public function GetDocumentTypes($params)
	{
	
		$qry = "SELECT * FROM ctt_documents_type where dot_status = 1";
		return $this->db->query($qry);
	}

    /* public function GetArea($params)
	{
		$qry = "SELECT str_id, str_name, str_type, emp_id, emp_fullname  FROM ctt_stores WHERE str_id = ".$params['id'].";";
		$result = $this->db->query($qry);
		if($row = $result->fetch_row()){
			$item = array("str_id" =>$row[0],
			"str_name" =>$row[1],
			"str_type"=>$row[2],
			"emp_id"=>$row[3],
			"emp_fullname"=>$row[4]);
		}
		return $item;
	} */

    public function UpdateDocumentType($params)
	{

		$dot_id 	= $this->db->real_escape_string($params['dot_id']);
		$dot_name 	= $this->db->real_escape_string($params['dot_name']);
		$dot_status 	= $this->db->real_escape_string($params['dot_status']);
		$doc_code 	= $this->db->real_escape_string($params['doc_code']);

		$qry = " UPDATE ctt_documents_type
					SET dot_code		= UPPER('$doc_code'),
						dot_name 	= UPPER('$dot_name'),
						dot_status 	= $dot_status
				WHERE dot_id = '$dot_id';";
		$this->db->query($qry);	
			
		return $dot_id;
	}

    //borra proveedor
	public function DeleteDocumentType($params)
	{
        $dot_id 	= $this->db->real_escape_string($params['dot_id']);
		$qry = "UPDATE ctt_documents_type
				SET dot_status = 0
				WHERE dot_id = $dot_id";
        return $this->db->query($qry);
	}


}