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
		$prd_id 		= $this->db->real_escape_string($params['prd_id']);

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
	
		$qry ="SELECT * FROM ctt_series AS sr
				INNER JOIN ctt_products AS pd ON sr.prd_id = pd.prd_id
				INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
				INNER JOIN ctt_categories AS ct ON ct.cat_id = sb.cat_id
				WHERE ct.cat_id IN(11,12) AND (upper(pd.prd_name) 
				LIKE '%$word%' OR upper(pd.prd_sku) LIKE '%$word%');";

		return $this->db->query($qry);
	}
	
	// Listado de Almacecnes
    // public function listSubcategories($param)
    // {
	// 	$cat_id = $this->db->real_escape_string($param['cat_id']);
	// 	$qry = "SELECT * FROM ctt_subcategories AS sb 
	// 			WHERE sb.cat_id = $cat_id;";
	// 	return $this->db->query($qry);
    // }

	// Listado de Almacecnes
    // public function listCategories()
    // {
	// 	$qry = "SELECT * FROM ctt_categories AS ct;";
	// 	return $this->db->query($qry);
    // }
}