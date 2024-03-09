<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class ClosedProyectChangeModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

// Listado de almacenes  ****
        public function listProjects($params)
        {
            $liststat = $this->db->real_escape_string($params['liststat']);

            $qry = "SELECT DISTINCT pj.pjt_id, pj.pjt_number, pj.pjt_name
                    FROM ctt_projects AS pj
                    INNER JOIN ctt_documents_closure AS dcl ON dcl.pjt_id=pj.pjt_id
                    WHERE pjt_status IN ($liststat) ORDER BY pj.pjt_number;";
            return $this->db->query($qry);
        }    

        public function listDataProjects($params)
        {
            $pjtId = $this->db->real_escape_string($params['pjtId']);

            $qry = "SELECT pj.pjt_id, pj.pjt_number, pj.pjt_name, pj.pjt_date_start, pj.pjt_date_end,
                            cu.cus_name, cu.cus_legal_representative, cu.cus_address, emp_fullname
                    FROM ctt_projects AS pj
                    LEFT JOIN ctt_customers_owner AS co ON co.cuo_id = pj.cuo_id
                    LEFT JOIN ctt_customers AS cu On cu.cus_id = co.cus_id
                    LEFT JOIN ctt_who_attend_projects AS wt ON wt.pjt_id=pj.pjt_id
                    WHERE pj.pjt_id=$pjtId AND wt.are_id=1 ORDER BY pj.pjt_number;";
            return $this->db->query($qry);
        }    

        public function getMontos($params)
        {
            $pjtId = $this->db->real_escape_string($params['pjtId']);

            $qry = "SELECT clo_id,clo_ver_closed,clo_total_proyects,clo_total_maintenance,
                            clo_total_expendables, clo_total_diesel,clo_total_discounts,clo_total_document	 
                    FROM ctt_documents_closure WHERE pjt_id=$pjtId;";
            return $this->db->query($qry);
        }    

        public function getValuesDoc($params)
        {
            $cloid = $this->db->real_escape_string($params['pjtId']);

            $qry = "SELECT clo_id,clo_ver_closed,clo_total_proyects,clo_total_maintenance,
                            clo_total_expendables, clo_total_diesel,clo_total_discounts,clo_total_document	 
                    FROM ctt_documents_closure WHERE clo_id=$cloid;";
            return $this->db->query($qry);
        }    

// Listado de productos
        public function listProducts($params)
        {
            $strId = $this->db->real_escape_string($params['strId']);

            $qry = "SELECT sr.ser_id, sr.ser_sku, pd.prd_sku, pd.prd_name, 
                            pd.prd_price, SUM(sp.stp_quantity) as stock
                    FROM ctt_series AS sr
                    INNER JOIN ctt_stores_products AS sp ON sp.ser_id = sr.ser_id
                    INNER JOIN ctt_products AS pd ON pd.prd_id = sr.prd_id
                    WHERE sp.str_id = $strId AND pd.prd_status = 1
                    GROUP BY sr.ser_id, sr.ser_sku, pd.prd_sku, pd.prd_name, pd.prd_price
                    ORDER BY pd.prd_name ASC;";
            return $this->db->query($qry);
        }    
/** */
    public function saveDocumentClosure($params)
    {
        $cloTotProy     =  $this->db->real_escape_string($params['cloTotProy']);
        $cloTotMaint    = $this->db->real_escape_string($params['cloTotMaint']);
        $cloTotExpen    = $this->db->real_escape_string($params['cloTotExpen']);
        $cloTotCombu    =  $this->db->real_escape_string($params['cloTotCombu']);
        $cloTotDisco    =  $this->db->real_escape_string($params['cloTotDisco']);
        $cloTotDocum    =  $this->db->real_escape_string($params['cloTotDocum']);
        $cloId          = $this->db->real_escape_string($params['cloId']);
        $usrid          = $this->db->real_escape_string($params['usrid']);
      
            $qry="INSERT INTO ctt_documents_closure(clo_total_proyects, clo_total_maintenance, 
                    clo_total_expendables, clo_total_diesel, clo_total_discounts,clo_total_document,
                    clo_fecha_cierre,clo_flag_send,  cus_id, pjt_id, usr_id, ver_id)
                SELECT '$cloTotProy','$cloTotMaint','$cloTotExpen','$cloTotCombu','$cloTotDisco',
                    '$cloTotDocum',Now(),'0', cus_id, pjt_id, '$usrid', ver_id 
                FROM ctt_documents_closure
                WHERE clo_id=$cloId;";

        $this->db->query($qry);
        $ducloId = $this->db->insert_id;

        return $ducloId;
    }
/** */
    public function insertCollectPays($params)
    {
        $folid     =  $this->db->real_escape_string($params['folid']);
        $cusid    = $this->db->real_escape_string($params['cusid']);
        $amoupay     =  $this->db->real_escape_string($params['amoupay']);
        $pjtId    = $this->db->real_escape_string($params['pjtId']);
        $cloid    =  $this->db->real_escape_string($params['cloid']);
        $empid    =  $this->db->real_escape_string($params['empid']);
        $deadpay    =  $this->db->real_escape_string($params['deadpay']);

        $qry="INSERT INTO ctt_collect_accounts(clt_folio,clt_date_generated, clt_deadline, 
                ctl_amount_payable, cus_id, pjt_id, clo_id, emp_id) 
            VALUES ('$folid', Now(),'$deadpay','$amoupay','$cusid','$pjtId', '$cloid','$empid');";

        $this->db->query($qry);
        $colacc = $this->db->insert_id;

        return $colacc;
    }

    public function PromoteProject($params)
    {
        /* Actualiza el estado en 2 convirtiendolo en presupuesto  */
        $pjtId                  = $this->db->real_escape_string($params['pjtId']);
        $qry = "UPDATE ctt_projects SET pjt_status = '99' WHERE pjt_id = $pjtId;";
        $this->db->query($qry);

        return $pjtId;

    }

}


    