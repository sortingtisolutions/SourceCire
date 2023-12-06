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
        $qry = "SELECT * FROM ctt_projects WHERE pjt_status>='9'
                ORDER BY 1 DESC;";

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
    
// Obtiene el listado de las subcategorias activas
    public function SaveSubcategory($params)
    {
        // $sbcName = $this->db->real_escape_string($params['sbcName']);
        // $sbcCode = $this->db->real_escape_string($params['sbcCode']);
        // $catId = $this->db->real_escape_string($params['catId']);


        // $qry = "INSERT INTO ctt_subcategories(sbc_code, sbc_name, sbc_status, cat_id)
        //         VALUES('$sbcCode',UPPER('$sbcName'),1,'$catId')";
        // $this->db->query($qry);	
        // $sbcId = $this->db->insert_id;
		// return $sbcId;
    }

// Actualiza la subcategorias seleccionada
    public function UpdateSubcategory($params)
    {
        // $sbcId      = $this->db->real_escape_string($params['sbcId']);
        // $sbcName    = $this->db->real_escape_string($params['sbcName']);
        // $sbcCode    = $this->db->real_escape_string($params['sbcCode']);
        // $catId      = $this->db->real_escape_string($params['catId']);

        // $qry = "UPDATE ctt_subcategories
        //         SET 
        //               sbc_name  = UPPER('$sbcName'),
        //               sbc_code  = '$sbcCode',
        //               cat_id    = '$catId'
        //         WHERE sbc_id    = '$sbcId';";

        // $this->db->query($qry);	
        // return $sbcId;
    }

// Actualiza el status de la subcategorias a eliminar
    public function DeleteSubcategory($params)
    {
        // $sbcid      = $this->db->real_escape_string($params['sbcId']);

        // $qry = "UPDATE ctt_subcategories SET sbc_status = '0'
        //         WHERE sbc_id  = '$sbcid';";

        // $this->db->query($qry);	
        // return $sbcid;
    }

    public function insertPayAplied($params)
    {
        $referen = $this->db->real_escape_string($params['referen']);
        $DateStart = $this->db->real_escape_string($params['DateStart']);
        $montopayed = $this->db->real_escape_string($params['montopayed']);
        $foldoc = $this->db->real_escape_string($params['foldoc']);
        $pjtId = $this->db->real_escape_string($params['pjtId']);
        $wayPay = $this->db->real_escape_string($params['wayPay']);
        $empId = $this->db->real_escape_string($params['empId']);

        $qry="INSERT INTO ctt_payments_applied (pym_folio,pym_date_paid,pym_date_done,
                pym_amount,clt_id,pjt_id,wtp_id,emp_id)
            VALUES ('$referen','$DateStart', Now(),'$montopayed','$foldoc','$pjtId','$wayPay','$empId');";

        $this->db->query($qry);
        $payapl = $this->db->insert_id;

        return $payapl;
    }

}