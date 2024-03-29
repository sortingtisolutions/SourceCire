<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class PuestosModel extends Model
{
	
	public function __construct()
	{
		parent::__construct();
	}

// Optiene los Usuaios existentes
	public function GetPuestos()
	{
		$qry = "SELECT pos_id, pos_name, pos_description 
				FROM ctt_position
				WHERE pos_id>1 AND pos_status <> '0'; ";
		$result = $this->db->query($qry);
		$lista = array();
		while ($row = $result->fetch_row()){
			$item = array("pos_id" =>$row[0],
						"pos_name" =>$row[1],
						"pos_description" =>$row[2]);
			array_push($lista, $item);
		}
		return $lista;
	}

    public function GetPuesto($params)
	{
		$posId 	= $this->db->real_escape_string($params['pos_id']);
		$qry = "SELECT pos_id, pos_name, pos_description 
				FROM ctt_position
				WHERE pos_id = $posId;";
		$result = $this->db->query($qry);
		
		return $result;
	}


    public function ActualizaPuesto($params)
	{
        $estatus = 0;
			try {
				$qry = "UPDATE ctt_position 
						SET pos_name = '".$params['NomPuesto']."'
						,pos_description = '".$params['DesPuesto']."'
						WHERE pos_id = ".$params['IdPuesto'].";";

				$this->db->query($qry);	
				$estatus = $params['IdPuesto'];
			} catch (Exception $e) {
				$estatus = 0;
			}
		return $estatus;
	}

	//Guarda proveedor
	public function SavePuesto($params)
	{
        $estatus = 0;
			try {
				$qry = "INSERT INTO ctt_position (pos_name,pos_description,pos_status)
						     VALUES('".$params['NomPuesto']."','".$params['DesPuesto']."',1)";

                $this->db->query($qry);	
				$qry = "SELECT MAX(pos_id) AS id FROM ctt_position;";
				$result = $this->db->query($qry);
				if ($row = $result->fetch_row()) {
				    $lastid = trim($row[0]);
				}

				$estatus = $lastid;
			} catch (Exception $e) {
				$estatus = 0;
			}
		return $estatus;
	}

	//Ejecuta columna
	public function SavePuesto_old($params)
	{
		$varcolumna ='Columna_';
		$indvar = 3;
		$contavar = 2;

		$tmpcolumna = $varcolumna.$contavar;

		$qry = "SELECT $tmpcolumna from tmp_carga_proyectos where columna_5='CLAVE' limit 1;";
        $result = $this->db->query($qry);
		$lista = array();
		while ($row = $result->fetch_row()){
			$item = array("campo" =>$row[0]);
			array_push($lista, $item);
		}
		$qry2 = "INSERT INTO tmp_carga_cat14(nueva,anterior) VALUES ('$tmpcolumna', '$item');";

		$this->db->query($qry2);
        $result2 = $this->db->insert_id;
        
		return $lista;
	}
	
    //borra proveedor
	public function DeletePuesto($params)
	{
        $estatus = 0;
        try {
            $qry = "UPDATE ctt_position
                    SET pos_status = 0
                    WHERE pos_id in (".$params['IdPuesto'].");";
            $this->db->query($qry);
            $estatus = 1;
        } catch (Exception $e) {
            $estatus = 0;
        }
		return $estatus;
	}


}