<?php
defined('BASEPATH') or exit('No se permite acceso directo');
require( ROOT . PATH_ASSETS.  'ssp.class.php' );

class CoinsModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

//Guarda proveedor  ***
	public function SaveCoin($params)
	{
		$coinName 	    = $this->db->real_escape_string($params['coinName']);
		$coinsNumber	= $this->db->real_escape_string($params['coinsNumber']);
		$coinsCode 	    = $this->db->real_escape_string($params['coinsCode']);

		$qry = "INSERT INTO ctt_coins(cin_code, cin_number, cin_name, cin_status) 
				VALUES (UPPER('$coinsCode'), $coinsNumber, UPPER('$coinName'), 1)";
		$this->db->query($qry);	
		$cin_id = $this->db->insert_id;
		return $cin_id;

	}
	
// Optiene los Usuaios existentes
	public function GetCoins($params)
	{
	
		$qry = "SELECT * FROM ctt_coins where cin_status=1 order by cin_id ASC";
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

    public function UpdateCoin($params)
	{

		$cin_id 		= $this->db->real_escape_string($params['coin_id']);
		$cin_name 		= $this->db->real_escape_string($params['coin_name']);
		$cin_number 	= $this->db->real_escape_string($params['coin_number']);
		$cin_code 	= $this->db->real_escape_string($params['coin_code']);

		$qry = " UPDATE ctt_coins
					SET cin_code 		= UPPER('$cin_code'),
						cin_number		= '$cin_number',
						cin_name		= UPPER('$cin_name')
				WHERE cin_id = $cin_id;";
		$this->db->query($qry);	
			
		return $cin_id;
	}

    //borra proveedor
	public function DeleteCoin($params)
	{
        $cin_id 	= $this->db->real_escape_string($params['cin_id']);
		$qry = "UPDATE ctt_coins
				SET cin_status = 0
				WHERE cin_id = $cin_id";
        return $this->db->query($qry);
	}


	/* public function GetEncargadosAlmacen()
	{
		$qry = "SELECT emp_id, emp_fullname FROM ctt_employees WHERE emp_status = 1;";
		$result = $this->db->query($qry);
		$lista = array();
		while ($row = $result->fetch_row()){
			$item = array("emp_id" =>$row[0],
				 "emp_fullname" =>$row[1]);
			array_push($lista, $item);
		}
		return $lista;
	} */
	
	//public function listSeries_old($params)
    //{
      //  $prodId = $this->db->real_escape_string($params['strId']);
        /* $qry = "SELECT  se.ser_id, se.ser_sku, se.ser_serial_number, 
						date_format(se.ser_date_registry, '%d/%m/%Y') AS ser_date_registry,
						se.ser_cost, se.ser_situation, se.ser_stage, se.ser_status,se.ser_comments
				FROM ctt_series as se 
				LEFT JOIN ctt_stores_products AS sp ON sp.ser_id = se.ser_id
				WHERE sp.str_id IN ($prodId) AND sp.stp_quantity > 0
				ORDER BY se.ser_sku;"; */
		//	$qry = "SELECT prd.prd_sku, prd.prd_name, prd.prd_level, sum(sp.stp_quantity) as cantidad
		//	FROM ctt_products as prd
		//	INNER JOIN ctt_series as se ON prd.prd_id=se.prd_id
		//	INNER JOIN ctt_stores_products AS sp ON sp.ser_id = se.ser_id
		//	where sp.str_id IN ($prodId) and se.ser_status=1 AND sp.stp_quantity>0
		//	group by prd.prd_sku, prd.prd_name, prd.prd_level
		//	ORDER BY se.ser_sku;";
        //return $this->db->query($qry);
    //}

	/* public function countQuantity($params)
    {
        $strId = $this->db->real_escape_string($params['strId']);
        $qry = "SELECT sp.str_id, ifnull(sum(sp.stp_quantity),0) AS cantidad
				FROM  ctt_stores_products AS sp
				INNER JOIN ctt_series AS sr ON sr.ser_id = sp.ser_id
				WHERE sp.str_id = $strId AND (sr.pjtdt_id = 0 OR ISNULL(sr.pjtdt_id)) AND sp.stp_quantity>0;";
        return $this->db->query($qry);
    }

	public function listSeries($params)
    {
        // $strId = $this->db->real_escape_string($params['strId']); where sp.str_id IN (3) and se.ser_status=1 AND sp.stp_quantity>0

		// $table = "SELECT se.ser_id as serid, prd.prd_sku as produsku, prd.prd_name as serlnumb, sum(sp.stp_quantity) as dateregs
		// 			FROM ctt_products as prd
		// 			INNER JOIN ctt_series as se ON prd.prd_id=se.prd_id
		// 			INNER JOIN ctt_stores_products AS sp ON sp.ser_id = se.ser_id
		// 			WHERE sp.str_id IN (3) and se.ser_status=1 AND sp.stp_quantity>0
		// 			group by prd.prd_sku, prd.prd_name, prd.prd_level
		// 			ORDER BY se.ser_sku;";  
	
		$table = 'ctt_vw_stores_regiter';  
					
		$strId= $this->db->real_escape_string($params['strId']);
		
		$where =  "strId =" . $strId . ";";

        $primaryKey = 'serId';
        $columns = array(
            // array( 'db' => 'strId', 'dt' => 'strId' ),
			array( 'db' => 'serId', 'dt' => 'serId' ),
            array( 'db' => 'produsku', 'dt' => 'produsku' ),
            array( 'db' => 'serlnumb', 'dt' => 'serlnumb' ),
            array( 'db' => 'dateregs', 'dt' => 'dateregs' ),
        );
        $sql_details = array(
            'user' => USER,
            'pass' => PASSWORD,
            'db'   => DB_NAME,
            'host' => HOST,
            'charset' => 'utf8',
        );

        return json_encode(
			SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, null, $where )
            // SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns )
        );

		// 	$qry = "SELECT prd.prd_sku, prd.prd_name, prd.prd_level, sum(sp.stp_quantity) as cantidad
		// 	FROM ctt_products as prd
		// 	INNER JOIN ctt_series as se ON prd.prd_id=se.prd_id
		// 	INNER JOIN ctt_stores_products AS sp ON sp.ser_id = se.ser_id
		// 	where sp.str_id IN ($strId) and se.ser_status=1 AND sp.stp_quantity>0
		// 	group by prd.prd_sku, prd.prd_name, prd.prd_level
		// 	ORDER BY se.ser_sku;";
        // return $this->db->query($qry);
    } */
}