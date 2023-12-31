<?php
defined('BASEPATH') or exit('No se permite acceso directo');
require( ROOT . PATH_ASSETS.  'ssp.class.php' );

class SearchIndividualProductsModel extends Model
{
    public function __construct()
    {
      parent::__construct();
    }

// Listado de Proyectos  ****
public function listProyects($store)
{
    $store = $this->db->real_escape_string($store);
    $qry = "SELECT * FROM ctt_projects WHERE pjt_status in (1,2,4) ;";
    return $this->db->query($qry);
}    

// Listado de Productos
    public function listProducts($params)
    {
        $pjtId = $this->db->real_escape_string($params['pjtId']);
        $qry = "SELECT pd.prd_id, pd.prd_sku, pd.prd_name, pd.prd_price, 
                pd.prd_visibility, ser.ser_id, ser.ser_sku, ser.ser_serial_number, 
                ser.ser_situation, ser.ser_date_registry, ser.ser_date_down, IFNULL(pjp.pjtpd_day_start,'') AS 
                    pjtpd_day_start, 
                    IFNULL(pjp.pjtpd_day_end,'')
                pjtpd_day_end, pj.pjt_name FROM ctt_products AS pd 
                INNER JOIN ctt_series AS ser ON ser.prd_id = pd.prd_id
                Left JOIN ctt_projects_detail AS pjd ON pjd.ser_id = ser.ser_id
                Left JOIN ctt_projects_content AS pjc ON pjc.pjtvr_id = pjd.pjtvr_id
                Left JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id = pjd.pjtdt_id
                Left  JOIN ctt_projects AS pj ON pj.pjt_id = pjc.pjt_id 
                WHERE pd.prd_id = $pjtId ORDER BY ser.ser_serial_number;";
        return $this->db->query($qry);
    }    

// Listar los productos2
public function listProducts2()
{
    $qry = "SELECT * FROM ctt_products A 
            WHERE A.prd_visibility=1 AND A.prd_level='P'
            ORDER BY prd_name;";
    return $this->db->query($qry);
}

// Busca si existe asignado un almacen con este producto
    public function SechingProducts($param)
    {
        $prodId = $this->db->real_escape_string($param['ser']);
        $storId = $this->db->real_escape_string($param['sti']);

        $qry = "SELECT count(*) as items FROM ctt_stores_products 
                WHERE ser_id = $prodId AND str_id = $storId;";
        return $this->db->query($qry);
    }


}



