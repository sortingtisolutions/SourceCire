<?php
defined('BASEPATH') or exit('No se permite acceso directo');
require( ROOT . PATH_ASSETS.  'ssp.class.php' );

class CommonsModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	
// Optiene los Usuaios existentes
	public function listAreas($params)
	{
		$qry = "SELECT * FROM ctt_areas where are_status=1 order by are_id ASC";
		return $this->db->query($qry);
	}

	public function listStores($params)
    {
        $qry = "  SELECT * FROM ctt_stores WHERE str_status = 1";
        return $this->db->query($qry);
    }
	
	public function listCategories($params)
    {
        $qry = "SELECT * FROM ctt_categories WHERE cat_status = 1;";
        return $this->db->query($qry);
    }

    public function listSubCategoriesAll($params)
    {       
        $qry = "SELECT * FROM ctt_subcategories WHERE sbc_status = 1;";
        return $this->db->query($qry);
    }

    public function listSubCategoriesOne($param)
    {
        $catId = $this->db->real_escape_string($param['catId']);
        $qry = "SELECT * FROM ctt_subcategories 
                WHERE cat_id=$catId AND sbc_status = 1;";
        return $this->db->query($qry);
    }
    
	public function listCoins($params)
    {
        $qry = "SELECT * FROM ctt_coins WHERE cin_status = 1;";
        return $this->db->query($qry);
    }   

    public function listProjects($params)
    {
        $liststat = $this->db->real_escape_string($params['liststat']);
        
        $qry = "SELECT * FROM ctt_projects WHERE pjt_status IN ($liststat);";
        return $this->db->query($qry);
    }    

    public function listProjectsType($params)
    {
        $qry = "SELECT * FROM ctt_projects_type ORDER BY pjttp_name;";
        return $this->db->query($qry);
    }    

    public function listSuppliers($params)
    {
        $qry = "  SELECT * FROM ctt_suppliers WHERE sup_status = 1 AND sut_id NOT IN (3);";
        return $this->db->query($qry);
    }
    
}