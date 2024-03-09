<?php
defined('BASEPATH') or exit('No se permite acceso directo');
require( ROOT . PATH_ASSETS.  'ssp.class.php' );

class PaymentsAppliedModel extends Model
{
    
	public function __construct()
	{
		parent::__construct();
	}

// Obtiene el listado de las subcategorias activas
    public function listProjects($params)
    {
        $liststat = $this->db->real_escape_string($params['liststat']);
        $qry = "SELECT * FROM ctt_projects WHERE pjt_status IN ($liststat);";

        return $this->db->query($qry);
    }

// Obtiene el listado de las subcategorias activas
    public function listPaymentsApplied($params)
    {
        $pjtId = $this->db->real_escape_string($params['pjtId']);

        $qry = "SELECT pym.pym_id, pym.pym_folio, date_format(pym.pym_date_paid,'%d-%m-%Y') as pym_date_paid, 
                    date_format(pym.pym_date_paid,'%d-%m-%Y') as pym_date_paid, 
                    date_format(pym.pym_date_done,'%d-%m-%Y') as pym_date_done, 
                    pym.pym_amount, pym.emp_id AS emp_reg,
                    wtp.wtp_description, pjt.pjt_name , emp.emp_fullname AS emp_reg 
                FROM ctt_payments_applied AS pym
                LEFT JOIN ctt_collect_accounts AS clt ON clt.clt_id=pym.clt_id
                LEFT JOIN ctt_projects AS pjt ON pjt.pjt_id=pym.pjt_id
                LEFT JOIN ctt_way_topay AS wtp ON wtp.wtp_id=pym.wtp_id
                LEFT JOIN ctt_employees AS emp ON pym.emp_id=emp.emp_id
                WHERE pjt.pjt_id=$pjtId
                ORDER BY pym.pym_id DESC;";

        return $this->db->query($qry);
    }


}