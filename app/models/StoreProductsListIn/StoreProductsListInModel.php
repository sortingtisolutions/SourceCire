<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class StoreProductsListInModel extends Model
{
	
    public function __construct()
    {
      parent::__construct();
    }

// Listado de Almacenes ****
    // public function listStores()
    // {
	// 	$qry = "  SELECT * FROM ctt_stores";
	// 	return $this->db->query($qry);
    // }
// Listado de Productos
	public function listProducts($store)
	{
		$store = $this->db->real_escape_string($store);
		$qry = "SELECT * FROM ctt_products AS pr
				INNER JOIN ctt_series AS sr ON sr.prd_id = pr.prd_id
				INNER JOIN ctt_stores_products AS st ON st.ser_id = sr.ser_id
				WHERE sr.ser_status = 1 AND st.stp_quantity > 0 AND st.str_id = $store;";
		return $this->db->query($qry);
	}	
// Listado de Movimientos
	public function listExchanges($guid)
	{
		$guid = $this->db->real_escape_string($guid);
		$qry = "SELECT * FROM ctt_stores_exchange WHERE exc_guid = '$guid' ORDER BY exc_date DESC;";
		return $this->db->query($qry);
	}


// Busca si existe asignado un almacen con este producto
	public function SechingProducts($param)
	{
		$prodId = $this->db->real_escape_string($param['prd']);
		$storId = $this->db->real_escape_string($param['str']);

		$qry = "SELECT count(*) as items FROM ctt_stores_products WHERE ser_id = $prodId AND str_id = $storId;";
		return $this->db->query($qry);
	}

// Agrega el registro de relación almacen producto
	public function InsertProducts($param)
	{
		$idPrd 			= $this->db->real_escape_string($param['prd']);
		$idStrSrc 		= $this->db->real_escape_string($param['str']);
		$quantity 		= $this->db->real_escape_string($param['qty']);

		$qry = "INSERT INTO ctt_stores_products (stp_quantity, str_id, ser_id) 
		VALUES ($quantity, $idStrSrc, $idPrd);";
		return $this->db->query($qry);
	}

	
}