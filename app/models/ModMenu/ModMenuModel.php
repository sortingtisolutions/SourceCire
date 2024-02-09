<?php
defined('BASEPATH') or exit('No se permite acceso directo');
require( ROOT . PATH_ASSETS.  'ssp.class.php' );

class ModMenuModel extends Model
{
	
	public function __construct()
	{
		parent::__construct();
	}

//Guarda proveedor  ***
	public function SaveMenu($params)
	{
		$mnuParent 			= $this->db->real_escape_string($params['mnuParent']);
		$mnuItems 			= $this->db->real_escape_string($params['mnuItems']);
		$mnuDescription 	= $this->db->real_escape_string($params['mnuDescription']);
		$mnuOrder 			= $this->db->real_escape_string($params['mnuOrder']);
		$mnuModule 			= $this->db->real_escape_string($params['mnuModule']);

		$qry = "INSERT INTO ctt_menu(mnu_parent, mnu_item, mnu_description, mnu_order, mod_id) 
				VALUES ('$mnuParent','$mnuItems','$mnuDescription','$mnuOrder','$mnuModule')";
		$this->db->query($qry);	
		$mnu_id = $this->db->insert_id;
		return $mnu_id;

	}
	
// Optiene los menus existentes
	public function GetMenus($params)
	{
	
		$qry = "SELECT mnu.mnu_id, mnu.mnu_parent, mnu.mnu_item, mnu.mnu_description, mnu.mnu_order, mnu.mod_id,
			mdl.mod_name 
			FROM ctt_menu AS mnu
			INNER JOIN ctt_modules AS mdl ON mdl.mod_id = mnu.mod_id;";
		return $this->db->query($qry);
	}


    public function UpdateMenu($params)
	{

		$mnuId 				= $this->db->real_escape_string($params['mnuId']);
		$mnuParent 			= $this->db->real_escape_string($params['mnuParent']);
		$mnuItems 			= $this->db->real_escape_string($params['mnuItems']);
		$mnuDescription 	= $this->db->real_escape_string($params['mnuDescription']);
		$mnuOrder 			= $this->db->real_escape_string($params['mnuOrder']);
		$mnuModule 			= $this->db->real_escape_string($params['mnuModule']);

		$qry = " UPDATE ctt_menu
					SET mnu_parent		= '$mnuParent',
						mnu_item		= '$mnuItems',
						mnu_description		= '$mnuDescription',
						mnu_order		= '$mnuOrder',
						mod_id		= '$mnuModule'
				WHERE mnu_id = '$mnuId';";
		$this->db->query($qry);	
			
		return $mnuId;
	}

    //borra proveedor
	public function DeleteMenu($params)
	{
        $mnuId 	= $this->db->real_escape_string($params['mnuId']);
		$qry = "DELETE FROM ctt_menu WHERE mnu_id = $mnuId";
        return $this->db->query($qry);
	}

	public function listMenuParents()
    {
        $qry = "SELECT * FROM ctt_menu as mnu;";
        return $this->db->query($qry);
    }

	public function listModules()
    {
        $qry = "SELECT * FROM ctt_modules;";
        return $this->db->query($qry);
    }
}