<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class OutputReprintModel extends Model
{
    public function __construct()
    {
      parent::__construct();
    }
   
// Listado de Productos de Proyecto asigando
    public function listDetailProds($params)
    {
        $pjt_id = $this->db->real_escape_string($params['pjt_id']);

        $qry = "SELECT pj.pjt_id, pj.pjt_name, pj.pjt_number, pt.pjttp_name,
                DATE_FORMAT(pj.pjt_date_start,'%d/%m/%Y') AS pjt_date_start, 
                DATE_FORMAT(pj.pjt_date_end,'%d/%m/%Y') AS pjt_date_end, 
                DATE_FORMAT(pj.pjt_date_last_motion,'%d/%m/%Y %H:%i ') AS pjt_date_project, 
                pj.pjt_location, pj.pjt_status,pj.pjt_id, pj.pjt_whomake, pj.pjt_whoattend
                FROM ctt_projects AS pj
                INNER JOIN ctt_projects_type As pt ON pt.pjttp_id = pj.pjttp_id 
                WHERE pj.pjt_status in ('8')
                ORDER BY pjt_date_start ASC;";
        return $this->db->query($qry);
    }
    
}
