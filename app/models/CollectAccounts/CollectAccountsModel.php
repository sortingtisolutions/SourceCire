<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class CollectAccountsModel extends Model
{
    
    public function __construct()
    {
        parent::__construct();
    }

// Obtiene el siguiente SKU   ******

    public function getWayToPay($sbcId)
    {
        $qry = "SELECT * FROM ctt_way_topay;";
        return $this->db->query($qry);
    }

// Listado de Productos
    public function listProjects($params)
    {
         $qry = "SELECT clt.clt_id,clt_folio,clt.ctl_amount_payable, 
                    date_format(clt.clt_deadline,'%d-%m-%Y') AS clt_deadline,
                    date_format(clt.clt_date_generated,'%d-%m-%Y') AS clt_date_generated,
                    cus.cus_id,cus.cus_name,
                    pjt.pjt_id, pjt.pjt_name,
                    pa.pym_amount,date_format(pa.pym_date_paid,'%d-%m-%Y') AS pym_date_paid,
                    SUM(clt.ctl_amount_payable-pa.pym_amount) AS pendiente, 
                            case when pa.pym_pending then pa.pym_pending END  AS pending, 
                            case when pa.pym_total then pa.pym_total ELSE clt.ctl_amount_payable END amount_payable
                FROM ctt_collect_accounts AS clt
                LEFT JOIN ctt_payments_applied AS pa ON clt.clt_id = pa.clt_id
                LEFT JOIN ctt_customers AS cus ON cus.cus_id=clt.cus_id
                LEFT JOIN ctt_projects AS pjt ON pjt.pjt_id=clt.pjt_id
                GROUP BY clt.clt_id, clt.clt_date_generated,clt.ctl_amount_payable, 
                        clt.clt_deadline,cus.cus_id,cus.cus_name,
                        pjt.pjt_id, pjt.pjt_name,pa.pym_amount,pa.pym_date_paid
                ORDER BY clt.clt_folio,clt_deadline ASC;";
        return $this->db->query($qry);
    }

    public function getSelectProject($params)
    {
        /* $prdId = $this->db->real_escape_string($params['prdId']); */
        $qry = "SELECT pjtcn_id,pjtcn_prod_sku,pjtcn_prod_name,pjtcn_quantity,pjtcn_prod_level
                FROM ctt_projects_content AS pj
                WHERE pj.pjt_id IN ('9') limit 1;";

        return $this->db->query($qry);
    }

    public function UpdateSeriesToWork($params)
    {
        $pjtid = $this->db->real_escape_string($params['pjtid']);
        $qry = "UPDATE ctt_series AS ser
                INNER JOIN ctt_projects_detail AS pjd ON pjd.ser_id=ser.ser_id
                INNER JOIN ctt_projects_version AS pjv ON pjv.pjtvr_id=pjd.pjtvr_id
                INNER JOIN ctt_version AS ver ON ver.ver_id=pjv.ver_id
                INNER JOIN ctt_projects AS pjt ON pjt.pjt_id=pjv.pjt_id
                SET ser.ser_stage='TA'
                WHERE (ver.ver_active=1 AND pjt.pjt_id=$pjtid AND pjt.pjt_status=4);";

        return $this->db->query($qry);
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

        $qry1 = "SELECT case when pya.pym_amount > 0 then cla.ctl_amount_payable - SUM(pya.pym_amount) ELSE cla.ctl_amount_payable end as payment
        FROM ctt_collect_accounts AS cla
        LEFT JOIN ctt_payments_applied AS pya ON cla.clt_id = pya.clt_id
        WHERE cla.clt_id = $foldoc LIMIT 1;";
        
        $rest = $this->db->query($qry1);
        $rst = $rest->fetch_object();
        $payment = $rst->payment;

        $pending = floatval($payment) - floatval($montopayed);
        $qry="INSERT INTO ctt_payments_applied (pym_folio,pym_date_paid,pym_date_done,
                pym_amount,clt_id,pjt_id,wtp_id,emp_id, pym_total,pym_pending)
            VALUES ('$referen','$DateStart', Now(),'$montopayed','$foldoc','$pjtId','$wayPay','$empId', '$payment', '$pending');";

        $this->db->query($qry);
        $payapl = $this->db->insert_id;

        return $payapl;
    }

}
