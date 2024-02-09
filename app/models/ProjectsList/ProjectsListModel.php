<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class ProjectsListModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

// Optiene los Usuaios existentes
	public function GetProjectsList()
	{
		$qry = "SELECT pjt_id,pjt_name,pjt_number, prt.pjttp_id, prt.pjttp_name, cu.cus_id, cu.cus_name,
					pjt_location, pr.loc_id, lt.loc_type_location, prs.pjs_name,
					(SELECT cus_name FROM ctt_customers 
					WHERE cus_id=(SELECT cus_id FROM ctt_customers_owner WHERE cuo_id=pr.cuo_id)) AS Casap, cuw.cus_id AS casaid,
					(SELECT cus_name FROM ctt_customers 
					WHERE cus_id=(SELECT cus_parent FROM ctt_customers_owner WHERE cuo_id=pr.cuo_id)) AS Productor, cuw.cus_parent AS ptrid
					FROM ctt_projects AS pr
					INNER JOIN ctt_projects_type AS prt ON pr.pjttp_id = prt.pjttp_id
					INNER JOIN ctt_customers_owner AS cuw ON pr.cuo_id = cuw.cuo_id
					INNER JOIN ctt_customers AS cu ON cu.cus_id = cuw.cus_id
					INNER JOIN ctt_projects_status AS prs ON prs.pjs_status=pr.pjt_status
					INNER JOIN ctt_location AS lt ON lt.loc_id=pr.loc_id
					WHERE pr.pjt_status IN (1,2) ORDER BY pjt_id;";
		return $this->db->query($qry);
	}

    public function getProject($params)
	{
		$idSup 		= $this->db->real_escape_string($params['id']);

		$qry = "SELECT pjt_id,pjt_name,pjt_location, prt.pjttp_name,cu.cus_name,prs.pjs_name	 
				FROM ctt_projects AS pr
				INNER JOIN ctt_projects_type AS prt ON pr.pjttp_id = prt.pjttp_id
				INNER JOIN ctt_customers_owner AS cuw ON pr.cuo_id = cuw.cuo_id
				INNER JOIN ctt_customers AS cu ON cu.cus_id = cuw.cus_id
				INNER JOIN ctt_projects_status AS prs ON prs.pjs_status=pr.pjt_status
				WHERE pjt_id = $idSup and pr.pjt_status = 3;";

		$result = $this->db->query($qry);

		if($row = $result->fetch_row()){
			$item = array("pjt_id" =>$row[0],
			"pjt_name" =>$row[1],
			"pjt_location"=>$row[2],
			"pjttp_name"=>$row[3],
			"cus_name"=>$row[4],
			"cus_name"=>$row[5],
			"pjs_name"=>$row[6]);
		}
		return $item;
	}


    public function ActualizaProjectsList($params)
	{
		$IdPjt 					= $this->db->real_escape_string($params['IdProjectsList']);
		$NomProjectsList 		= $this->db->real_escape_string($params['NomProjectsList']);
		$ContactoProjectsList	= $this->db->real_escape_string($params['ContactoProjectsList']);
		$tipoProjectsId 		= $this->db->real_escape_string($params['tipoProjectsId']);
		$tipoLocationId 		= $this->db->real_escape_string($params['tipoLocationId']);
		$tipoCustomerId 		= $this->db->real_escape_string($params['tipoCustomerId']);
		$tipoRelationtId 		= $this->db->real_escape_string($params['tipoRelationtId']);
		$custown				= 0;
        $estatus = 0;
		
			$qry1 = "UPDATE ctt_projects SET pjt_name = UPPER('$NomProjectsList'),
						pjt_location = UPPER('$ContactoProjectsList'),
						pjttp_id = $tipoProjectsId,
						loc_id = $tipoLocationId
					WHERE pjt_id = $IdPjt;";
			
			$this->db->query($qry1);

			$NxtId = "SELECT cuo_id FROM ctt_projects WHERE pjt_id = $IdPjt LIMIT 1;";
            $result = $this->db->query($NxtId);

			if($row = $result->fetch_row()){
				$custown = trim($row[0]);
			}

			$qry2 = "UPDATE ctt_customers_owner SET cus_id = '$tipoCustomerId',
						cus_parent = '$tipoRelationtId'
					WHERE cuo_id = '$custown';";

			$this->db->query($qry2);	

			$estatus = $IdPjt;

		return $result;
	}

	public function GetTipoProjectsList()
	{
		$qry = "SELECT pjttp_id,pjttp_name FROM ctt_projects_type;";
		$result = $this->db->query($qry);
		$lista = array();
		while ($row = $result->fetch_row()){
			$item = array("pjttp_id" =>$row[0],
						"pjttp_name" =>$row[1]);
			array_push($lista, $item);
		}
		return $lista;
	}

	public function getTipoLocation()
	{
		$qry = "SELECT loc_id,loc_type_location FROM ctt_location;";
		$result = $this->db->query($qry);
		$lista = array();
		while ($row = $result->fetch_row()){
			$item = array("loc_id" =>$row[0],
						"loc_type_location" =>$row[1]);
			array_push($lista, $item);
		}
		return $lista;
	}

	public function getCustomers()
	{
		$qry = "SELECT cus_id,cus_name FROM ctt_customers WHERE cus_status=1 and cut_id=1;";
		$result = $this->db->query($qry);
		$lista = array();
		while ($row = $result->fetch_row()){
			$item = array("cus_id" =>$row[0],
						"cus_name" =>$row[1]);
			array_push($lista, $item);
		}
		return $lista;
	}

	public function getRelation()
	{
		$qry = "SELECT cus_id,cus_name FROM ctt_customers WHERE cus_status=1 and cut_id=2;";
		$result = $this->db->query($qry);
		$lista = array();
		while ($row = $result->fetch_row()){
			$item = array("cus_id" =>$row[0],
						"cus_name" =>$row[1]);
			array_push($lista, $item);
		}
		return $lista;
	}
}