<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class WorkInputModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }


// Listado de Productos
    public function listProjects($params)
    {
        $liststat = $this->db->real_escape_string($params['liststat']);

        $qry = "SELECT pt.pjttp_name, pj.pjt_name, pj.pjt_number,
                DATE_FORMAT(pj.pjt_date_start,'%d/%m/%Y') AS pjt_date_start, 
                DATE_FORMAT(pj.pjt_date_end,'%d/%m/%Y') AS pjt_date_end, 
                DATE_FORMAT(pj.pjt_date_last_motion,'%d/%m/%Y %H:%i ') AS pjt_date_project, 
                pj.pjt_location, pj.pjt_status,pj.pjt_id
                FROM ctt_projects AS pj 
                LEFT JOIN ctt_location AS lo ON lo.loc_id = pj.loc_id 
                LEFT JOIN ctt_projects_type As pt ON pt.pjttp_id = pj.pjttp_id 
                WHERE pj.pjt_status in ($liststat) ORDER BY pjt_date_start ASC;";
        return $this->db->query($qry);
    }

    public function getSelectProject($params)
    {
        /* $prdId = $this->db->real_escape_string($params['prdId']); */
        $qry = "SELECT pjtcn_id,pjtcn_prod_sku,pjtcn_prod_name,pjtcn_quantity,pjtcn_prod_level
                FROM ctt_projects_content AS pj
                WHERE pj.pjt_id = 1 limit 1;";

        return $this->db->query($qry);
    }

    public function UpdateSeriesToWork($params)
    {
        $pjtid = $this->db->real_escape_string($params['pjtid']);

        $qry2 = "UPDATE ctt_projects as pjt
                SET pjt.pjt_status='9'
                WHERE pjt.pjt_id=$pjtid AND pjt.pjt_status=8;";

        return $this->db->query($qry2);
    }

    
}
