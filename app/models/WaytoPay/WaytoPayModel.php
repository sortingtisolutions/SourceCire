<?php
defined('BASEPATH') or exit('No se permite acceso directo');
require( ROOT . PATH_ASSETS.  'ssp.class.php' );

class WaytoPayModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
    
// Obtiene el listado de las subcategorias activas
    public function listWaytoPay($params)
    {
        $qry = "SELECT * FROM ctt_way_topay ORDER BY 1;";
        return $this->db->query($qry);
    }

// Obtiene el listado de las subcategorias activas
    public function SaveWaytoPay($params)
    {
        $wayName = $this->db->real_escape_string($params['wayName']);
        $wayCode = $this->db->real_escape_string($params['wayCode']);
        $waystat = $this->db->real_escape_string($params['waystat']);


        $qry = "INSERT INTO ctt_way_topay(wtp_clave, wtp_description, wtp_status)
                VALUES('$wayCode',UPPER('$wayName'),'$waystat')";
        $this->db->query($qry);	
        $wayId = $this->db->insert_id;
		return $wayId;
    }

// Actualiza la subcategorias seleccionada
    public function UpdateWaytoPay($params)
    {
        $wayId      = $this->db->real_escape_string($params['wayId']);
        $wayName    = $this->db->real_escape_string($params['wayName']);
        $wayCode    = $this->db->real_escape_string($params['wayCode']);
        $waystat      = $this->db->real_escape_string($params['waystat']);

        $qry = "UPDATE ctt_way_topay
                SET 
                    wtp_description  = UPPER('$wayName'),
                    wtp_clave   = '$wayCode',
                    wtp_status  = '$waystat'
                WHERE wtp_id    = '$wayId';";

        $this->db->query($qry);	
        return $wayId;
    }

// Actualiza el status de la subcategorias a eliminar
    public function DeleteWayPay($params)
    {
        $wayId      = $this->db->real_escape_string($params['wayId']);

        $qry = "UPDATE ctt_subcategories SET sbc_status = '0'
                WHERE sbc_id  = '$wayId';";

        $this->db->query($qry);	
        return $wayId;
    }

}