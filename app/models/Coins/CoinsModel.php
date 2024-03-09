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
	// public function GetCoins($params)
	// {
	
	// 	$qry = "SELECT * FROM ctt_coins where cin_status=1 order by cin_id ASC";
	// 	return $this->db->query($qry);
	// }

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

}