<?php
defined('BASEPATH') or exit('No se permite acceso directo');
require( ROOT . PATH_ASSETS.  'ssp.class.php' );

class MobilStoreModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

//Guarda proveedor  ***
	public function SaveMobilStore($params)
	{
		$str_id 		= $this->db->real_escape_string($params['str_id']);
		$ser_id 		= $this->db->real_escape_string($params['ser_id']);
		$movstr_placas 	= $this->db->real_escape_string($params['placas']);
		$prd_id 		= $this->db->real_escape_string($params['prd_id']);
		/* $cat_id 		= $this->db->real_escape_string($params['cat_id']);
		$sbc_id 		= $this->db->real_escape_string($params['sbc_id']); */

		/* $qry = "INSERT INTO ctt_mobile_store(str_id, ser_id, movstr_placas, prd_id,sbc_id,cat_id) 
				VALUES ('$str_id','$ser_id',UPPER('$movstr_placas'),$prd_id, $sbc_id, $cat_id)"; */
		$qry = "INSERT INTO ctt_mobile_store(str_id, ser_id, movstr_placas, prd_id) 
				VALUES ('$str_id','$ser_id',UPPER('$movstr_placas'),$prd_id)";
		$this->db->query($qry);	
		$movstr_id = $this->db->insert_id;
		return $movstr_id;

	}
	
// Optiene los Usuaios existentes
	public function GetMobilStores($params)
	{
	
		$qry = "SELECT mbs.movstr_id, mbs.movstr_placas, st.str_id, st.str_name, ser.ser_id, ser.ser_sku, 
		pd.prd_id, pd.prd_sku, pd.prd_name, sb.sbc_name, ct.cat_name, sb.sbc_id, ct.cat_id FROM ctt_mobile_store AS mbs
		INNER JOIN ctt_stores AS st ON st.str_id = mbs.str_id
		INNER JOIN ctt_series AS ser ON ser.ser_id = mbs.ser_id
		INNER JOIN ctt_products AS pd ON pd.prd_id = ser.prd_id 
		LEFT JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id 
		LEFT JOIN ctt_categories AS ct ON ct.cat_id = sb.cat_id
		order by movstr_id ASC";
		return $this->db->query($qry);
	}


    public function UpdateMobilStore($params)
	{

		$movstr_id 	= $this->db->real_escape_string($params['movstr_id']);
		$str_id 		= $this->db->real_escape_string($params['str_id']);
		$ser_id 		= $this->db->real_escape_string($params['ser_id']);
		$movstr_placas 	= $this->db->real_escape_string($params['placas']);
		$prd_id 		= $this->db->real_escape_string($params['prd_id']);/* 
		$cat_id 		= $this->db->real_escape_string($params['cat_id']);
		$sbc_id 		= $this->db->real_escape_string($params['sbc_id']); */

		/* $qry = " UPDATE ctt_mobile_store
					SET str_id		= $str_id,
						ser_id 	= $ser_id ,
						movstr_placas		= UPPER('$movstr_placas'),
						prd_id 	= $prd_id,
						sbc_id = $sbc_id,
						cat_id = $cat_id
				WHERE movstr_id = '$movstr_id';"; */
		$qry = " UPDATE ctt_mobile_store
					SET str_id		= $str_id,
						ser_id 	= $ser_id ,
						movstr_placas		= UPPER('$movstr_placas'),
						prd_id 	= $prd_id
				WHERE movstr_id = '$movstr_id';";
		$this->db->query($qry);	
			
		return $movstr_id;
	}

    //borra proveedor
	public function DeleteMobilStore($params)
	{
        $movstr_id 	= $this->db->real_escape_string($params['movstr_id']);
		/* $qry = "UPDATE ctt_mobile_store
				SET are_status = 0
				WHERE movstr_id = $movstr_id"; */
		$qry = "DELETE FROM ctt_mobile_store
				WHERE movstr_id = $movstr_id";
        return $this->db->query($qry);
	}

	// Listado de Almacecnes
    public function listStores()
    {
		$qry = "SELECT * FROM ctt_stores 
				WHERE str_name  NOT LIKE 'SUBARRENDO%'";
		return $this->db->query($qry);
    }
	public function listProducts($param)
	{
		$strId = $this->db->real_escape_string($param['strId']);
		$word = $this->db->real_escape_string($param['word']);
		
		/* $qry ="SELECT sr.ser_id,sr.ser_sku,sr.ser_serial_number,sr.ser_cost,st.str_id,st.stp_quantity,
				sr.prd_id, pr.prd_name,pr.prd_coin_type
				FROM ctt_products AS pr
				INNER JOIN ctt_series AS sr ON sr.prd_id = pr.prd_id
				INNER JOIN ctt_stores_products AS st ON st.ser_id = sr.ser_id
			WHERE sr.ser_status = 1 AND st.stp_quantity > 0
			AND st.str_id=$strId AND (upper(pr.prd_name) LIKE '%$word%' OR upper(pr.prd_sku) LIKE '%$word%') 
			ORDER BY pr.prd_sku;"; */
		/* $qry ="SELECT * FROM ctt_products AS pd 
			INNER JOIN ctt_series AS sr ON sr.prd_id = pd.prd_id
			WHERE (upper(pd.prd_name) LIKE '%$word%' OR upper(pd.prd_sku) LIKE '%$word%');"; */
		$qry ="SELECT * FROM ctt_series AS sr
		INNER JOIN ctt_products AS pd ON sr.prd_id = pd.prd_id
		INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
		INNER JOIN ctt_categories AS ct ON ct.cat_id = sb.cat_id
		WHERE ct.cat_id IN(11,12) AND (upper(pd.prd_name) LIKE '%$word%' OR upper(pd.prd_sku) LIKE '%$word%');";

		return $this->db->query($qry);
	}
	
	// Listado de Almacecnes
    public function listSubcategories($param)
    {
		$cat_id = $this->db->real_escape_string($param['cat_id']);
		$qry = "SELECT * FROM ctt_subcategories AS sb where sb.cat_id = $cat_id;";
		return $this->db->query($qry);
    }

	// Listado de Almacecnes
    public function listCategories()
    {
		$qry = "SELECT * FROM ctt_categories AS ct;";
		return $this->db->query($qry);
    }
}