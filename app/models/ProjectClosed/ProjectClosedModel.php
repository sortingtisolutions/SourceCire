<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class ProjectClosedModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }


/* -- Listado de proyectos  ------------------------------------- */    
    public function listProjects($params)
    {
        // $qry = "SELECT pjt_id, pjt_name FROM ctt_projects 
        //         WHERE pjt_status IN (8,9);"; /* AND pjt_date_start < curdate();"; */

        $qry = "SELECT pj.pjt_id, pj.pjt_name, ifnull(cus.cus_id , '0') as cus_id
                FROM ctt_projects AS pj
                LEFT JOIN ctt_customers_owner AS co ON co.cuo_id=pj.cuo_id
                LEFT JOIN ctt_customers AS cus ON cus.cus_id=co.cus_id
                WHERE pjt_status IN (8,9);";

        return $this->db->query($qry);
    }

    /* -- Listado de proyectos  ------------------------------------- */    
    public function listChgStatus($params)
    {
        $qry = "SELECT * FROM ctt_project_change_reason";
        return $this->db->query($qry);

    }

/* -- Listado de contenido de proyecto seleccionado  -------------- */
    public function projectContent($params)
    {
        $pjtId = $this->db->real_escape_string($params['pjtId']);

            $qry = "SELECT * , 
                    ifnull(sr.ser_comments,'') AS ser_comments, ifnull(sr.ser_status,'1') as ser_status, 
                    cn.pjtcn_quantity,
                    (cn.pjtcn_prod_price * cn.pjtcn_days_cost) - 
                    (cn.pjtcn_prod_price * cn.pjtcn_discount_base) * 
                    cn.pjtcn_days_cost + (cn.pjtcn_prod_price * cn.pjtcn_days_trip) - 
                    ((cn.pjtcn_prod_price * cn.pjtcn_discount_trip) * cn.pjtcn_days_trip) + 
                    (cn.pjtcn_prod_price * cn.pjtcn_days_test) - 
                    (cn.pjtcn_prod_price * cn.pjtcn_discount_test) * cn.pjtcn_days_test as costo,
                    cn.ver_id as verId
                FROM ctt_projects_detail AS dt
                INNER JOIN ctt_projects_content AS cn ON cn.pjtvr_id = dt.pjtvr_id AND cn.prd_id = dt.prd_id
                LEFT JOIN ctt_series AS sr ON sr.ser_id = dt.ser_id
                WHERE cn.pjt_id = $pjtId;";

        return $this->db->query($qry);

    }
    

/* -- Listado ventas de expendables  --------------------------------------------------------- */
    public function saleExpendab($params)
    {
        $pjtId = $this->db->real_escape_string($params['pjtId']);

        $qry = "SELECT ifnull(sum(sd.sld_quantity * sd.sld_price),0) AS expendables
                FROM ctt_sales_details AS sd
                INNER JOIN ctt_sales as sl on sl.sal_id = sd.sal_id
                WHERE pjt_id =  $pjtId;";
        return $this->db->query($qry);
    }

    public function saveDocumentClosure($params)
    {
        $cloTotProy     =  $this->db->real_escape_string($params['cloTotProy']);
        $cloTotMaint    = $this->db->real_escape_string($params['cloTotMaint']);
        $cloTotExpen    = $this->db->real_escape_string($params['cloTotExpen']);
        $cloTotCombu    =  $this->db->real_escape_string($params['cloTotCombu']);
        $cloTotDisco    =  $this->db->real_escape_string($params['cloTotDisco']);
        $cloTotDocum    =  $this->db->real_escape_string($params['cloTotDocum']);
        $cloCommen      = $this->db->real_escape_string($params['cloCommen']);
        $pjtid          = $this->db->real_escape_string($params['pjtid']);
        $usrid          = $this->db->real_escape_string($params['usrid']);
        $verid          = $this->db->real_escape_string($params['verid']);
        $cusId          = $this->db->real_escape_string($params['cusId']);
      
            $qry="INSERT INTO ctt_documents_closure(clo_total_proyects, clo_total_maintenance, 
                clo_total_expendables, clo_total_diesel, clo_total_discounts,clo_total_document,
                clo_fecha_cierre,clo_flag_send,clo_comentarios, clo_ver_closed, 
                cus_id, pjt_id, usr_id, ver_id)
            VALUES ('$cloTotProy','$cloTotMaint','$cloTotExpen','$cloTotCombu','$cloTotDisco',
            ' $cloTotDocum', Now(), '0', '$cloCommen','1',
            '$cusId','$pjtid','$usrid','$verid');";

        $this->db->query($qry);
        $ducloId = $this->db->insert_id;

        return $ducloId;
    }

    // Añadido por Edna V3
    public function totalMantenimiento($param)
    {
        $pjtId = $this->db->real_escape_string($param['pjtId']);
        $qry = "SELECT ifnull(sum(pmt_price),0) as maintenance 
                FROM ctt_products_maintenance WHERE pjt_id = $pjtId";
        
        return $this->db->query($qry);
    }

}