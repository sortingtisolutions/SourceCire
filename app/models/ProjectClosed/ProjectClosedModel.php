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
        $pjtId = $this->db->real_escape_string($params['pjtId']);
        $qry = "SELECT pj.pjt_id, pj.pjt_name, ifnull(cus.cus_id , '0') as cus_id
                FROM ctt_projects AS pj
                LEFT JOIN ctt_customers_owner AS co ON co.cuo_id=pj.cuo_id
                LEFT JOIN ctt_customers AS cus ON cus.cus_id=co.cus_id
                WHERE pjt_status IN ($pjtId);";

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
        $type = $this->db->real_escape_string($params['type']);
        $prjType = $this->db->real_escape_string($params['prjType']);
        if ($type == 1) {
            /* $qry = "SELECT  pr.prd_name AS pjtcn_prod_name, dt.pjtdt_prod_sku as prd_sku,sr.ser_situation,
                        ifnull(sr.ser_comments,'') AS ser_comments, ifnull(sr.ser_status,'1') as ser_status,
                        cn.pjtcn_quantity,
                        (pr.prd_price * cn.pjtcn_days_cost) - 
                        (pr.prd_price * cn.pjtcn_discount_base) * 
                        cn.pjtcn_days_cost + 
                        (pr.prd_price * cn.pjtcn_days_trip) - 
                        ( (pr.prd_price * cn.pjtcn_discount_trip) * cn.pjtcn_days_trip ) + 
                        (pr.prd_price * cn.pjtcn_days_test) - 
                        (pr.prd_price * cn.pjtcn_discount_test) * cn.pjtcn_days_test as costo,
                        cn.ver_id as verId,
                        ( (cn.pjtcn_insured * pr.prd_price) * cn.pjtcn_quantity) *  cn.pjtcn_days_cost  AS seguro,
                        1 AS quantity
                    FROM ctt_projects_detail AS dt
                    INNER JOIN ctt_products AS pr ON pr.prd_id=dt.prd_id
                    INNER JOIN ctt_projects_content AS cn ON cn.pjtvr_id = dt.pjtvr_id
                    LEFT JOIN ctt_series AS sr ON sr.ser_id = dt.ser_id
                    WHERE cn.pjt_id = $pjtId;"; */
                    if ($prjType == 1) {
                        $qry = "SELECT  pr.prd_name AS pjtcn_prod_name, dt.pjtdt_prod_sku as prd_sku,sr.ser_situation,
                            ifnull(sr.ser_comments,'') AS ser_comments, ifnull(sr.ser_status,'1') as ser_status,
                            cn.pjtcn_quantity,1 as quantity, case when (pr.prd_level = 'P' 
                                AND cn.pjtcn_prod_level = 'P') OR pr.prd_level='K' then 
                            (cn.pjtcn_prod_price * cn.pjtcn_days_cost) - 
                            (cn.pjtcn_prod_price * cn.pjtcn_discount_base) * 
                            cn.pjtcn_days_cost + 
                            (cn.pjtcn_prod_price * cn.pjtcn_days_trip) - 
                            ( (cn.pjtcn_prod_price * cn.pjtcn_discount_trip) * cn.pjtcn_days_trip ) + 
                            (cn.pjtcn_prod_price * cn.pjtcn_days_test) - 
                            (cn.pjtcn_prod_price * cn.pjtcn_discount_test) * cn.pjtcn_days_test ELSE 0 END  as costo,
                            cn.ver_id as verId,
                            case when (pr.prd_level = 'P' AND cn.pjtcn_prod_level = 'P') OR pr.prd_level='K' then ( (cn.pjtcn_insured * cn.pjtcn_prod_price)) *  cn.pjtcn_days_cost
                                ELSE 0 END AS seguro, dt.pjtdt_id, pj.pjt_name
                        FROM ctt_projects_detail AS dt
                        INNER JOIN ctt_products AS pr ON pr.prd_id=dt.prd_id
                        INNER JOIN ctt_projects_content AS cn ON cn.pjtvr_id = dt.pjtvr_id
                        LEFT JOIN ctt_series AS sr ON sr.ser_id = dt.ser_id
                        INNER JOIN ctt_projects AS pj ON pj.pjt_id = cn.pjt_id
                        WHERE cn.pjt_id = $pjtId AND pr.prd_level != 'A'";
                    }else{
                            if ($pjtId > 0) {
                            $qry = "SELECT  pr.prd_name AS pjtcn_prod_name, dt.pjtdt_prod_sku as prd_sku,sr.ser_situation,
                                ifnull(sr.ser_comments,'') AS ser_comments, ifnull(sr.ser_status,'1') as ser_status,
                                cn.pjtcn_quantity,1 as quantity, case when (pr.prd_level = 'P' 
                                    AND cn.pjtcn_prod_level = 'P') OR pr.prd_level='K' then 
                                (cn.pjtcn_prod_price * cn.pjtcn_days_cost) - 
                                (cn.pjtcn_prod_price * cn.pjtcn_discount_base) * 
                                cn.pjtcn_days_cost + 
                                (cn.pjtcn_prod_price * cn.pjtcn_days_trip) - 
                                ( (cn.pjtcn_prod_price * cn.pjtcn_discount_trip) * cn.pjtcn_days_trip ) + 
                                (cn.pjtcn_prod_price * cn.pjtcn_days_test) - 
                                (cn.pjtcn_prod_price * cn.pjtcn_discount_test) * cn.pjtcn_days_test ELSE 0 END  as costo,
                                cn.ver_id as verId,
                                case when (pr.prd_level = 'P' AND cn.pjtcn_prod_level = 'P') OR pr.prd_level='K' then ( (cn.pjtcn_insured * cn.pjtcn_prod_price)) *  cn.pjtcn_days_cost
                                    ELSE 0 END AS seguro, dt.pjtdt_id, pj.pjt_name
                            FROM ctt_projects_detail AS dt
                            INNER JOIN ctt_products AS pr ON pr.prd_id=dt.prd_id
                            INNER JOIN ctt_projects_content AS cn ON cn.pjtvr_id = dt.pjtvr_id
                            LEFT JOIN ctt_series AS sr ON sr.ser_id = dt.ser_id
                            INNER JOIN ctt_projects AS pj ON pj.pjt_id = cn.pjt_id
                            WHERE pj.pjt_parent = $pjtId AND pr.prd_level != 'A' and pj.pjt_status in(8,9)";
                        }
                    }

               
            }else if($type == 2){
                /* $qry = "SELECT cn.pjtcn_prod_name, pr.prd_name, dt.pjtdt_prod_sku, pr.prd_sku ,sr.ser_situation,
                        ifnull(sr.ser_comments,'') AS ser_comments, ifnull(sr.ser_status,'1') as ser_status,
                        cn.pjtcn_quantity AS quantity,
                        (cn.pjtcn_prod_price * cn.pjtcn_days_cost) - 
                        (cn.pjtcn_prod_price * cn.pjtcn_discount_base) * 
                        cn.pjtcn_days_cost + 
                        (cn.pjtcn_prod_price * cn.pjtcn_days_trip) - 
                        ( (cn.pjtcn_prod_price * cn.pjtcn_discount_trip) * cn.pjtcn_days_trip ) + 
                        (cn.pjtcn_prod_price * cn.pjtcn_days_test) - 
                        (cn.pjtcn_prod_price * cn.pjtcn_discount_test) * cn.pjtcn_days_test as costo,
                        cn.ver_id as verId,
                        ( (cn.pjtcn_insured * cn.pjtcn_prod_price) * cn.pjtcn_quantity) *  cn.pjtcn_days_cost  AS seguro
                    FROM ctt_projects_detail AS dt
                    INNER JOIN ctt_products AS pr ON pr.prd_id=dt.prd_id
                    INNER JOIN ctt_projects_content AS cn ON cn.pjtvr_id = dt.pjtvr_id
                    LEFT JOIN ctt_series AS sr ON sr.ser_id = dt.ser_id
                    WHERE cn.pjt_id = $pjtId GROUP BY cn.pjtcn_id;";  */
                    if ($prjType == 1) {
                        $qry = "SELECT cn.pjtcn_prod_name, pr.prd_name, dt.pjtdt_prod_sku as prd_sku, sr.ser_situation,
                            ifnull(sr.ser_comments,'') AS ser_comments, ifnull(sr.ser_status,'1') as ser_status,
                            1 as quantity,
                            (cn.pjtcn_prod_price * cn.pjtcn_days_cost) - 
                            (cn.pjtcn_prod_price * cn.pjtcn_discount_base) * 
                            cn.pjtcn_days_cost + 
                            (cn.pjtcn_prod_price * cn.pjtcn_days_trip) - 
                            ( (cn.pjtcn_prod_price * cn.pjtcn_discount_trip) * cn.pjtcn_days_trip ) + 
                            (cn.pjtcn_prod_price * cn.pjtcn_days_test) - 
                            (cn.pjtcn_prod_price * cn.pjtcn_discount_test) * cn.pjtcn_days_test as costo,
                            cn.ver_id as verId,
                            ( (cn.pjtcn_insured * cn.pjtcn_prod_price))*  cn.pjtcn_days_cost AS seguro,  dt.pjtdt_id, pj.pjt_name
                        FROM ctt_projects_detail AS dt
                        INNER JOIN ctt_products AS pr ON pr.prd_id=dt.prd_id
                        INNER JOIN ctt_projects_content AS cn ON cn.pjtvr_id = dt.pjtvr_id
                        LEFT JOIN ctt_series AS sr ON sr.ser_id = dt.ser_id
                        INNER JOIN ctt_projects AS pj ON pj.pjt_id = cn.pjt_id
                        WHERE cn.pjt_id = $pjtId AND cn.pjtcn_prod_level != 'K' AND pr.prd_level != 'A' UNION SELECT cn.pjtcn_prod_name, cn.pjtcn_prod_name as prd_name, cn.pjtcn_prod_sku AS prd_sku,sr.ser_situation,
                            ifnull(sr.ser_comments,'') AS ser_comments, ifnull(sr.ser_status,'1') as ser_status,
                            cn.pjtcn_quantity as quantity,
                            ((cn.pjtcn_prod_price * cn.pjtcn_days_cost) - 
                            (cn.pjtcn_prod_price * cn.pjtcn_discount_base) * 
                            cn.pjtcn_days_cost + 
                            (cn.pjtcn_prod_price * cn.pjtcn_days_trip) - 
                            ( (cn.pjtcn_prod_price * cn.pjtcn_discount_trip) * cn.pjtcn_days_trip ) + 
                            (cn.pjtcn_prod_price * cn.pjtcn_days_test) - 
                            (cn.pjtcn_prod_price * cn.pjtcn_discount_test) * cn.pjtcn_days_test) * cn.pjtcn_quantity as costo,
                            cn.ver_id as verId,
                            (( (cn.pjtcn_insured * cn.pjtcn_prod_price)) *  cn.pjtcn_days_cost)   AS seguro,  dt.pjtdt_id, pj.pjt_name
                        FROM ctt_projects_detail AS dt
                        INNER JOIN ctt_products AS pr ON pr.prd_id=dt.prd_id
                        INNER JOIN ctt_projects_content AS cn ON cn.pjtvr_id = dt.pjtvr_id
                        LEFT JOIN ctt_series AS sr ON sr.ser_id = dt.ser_id
                        INNER JOIN ctt_projects AS pj ON pj.pjt_id = cn.pjt_id
                        WHERE cn.pjt_id = $pjtId AND cn.pjtcn_prod_level = 'K' GROUP BY cn.pjtcn_id";
                    }else{
                        if ($pjtId > 0) {
                            $qry = "SELECT cn.pjtcn_prod_name, pr.prd_name, dt.pjtdt_prod_sku as prd_sku, sr.ser_situation,
                                ifnull(sr.ser_comments,'') AS ser_comments, ifnull(sr.ser_status,'1') as ser_status,
                                1 as quantity,
                                (cn.pjtcn_prod_price * cn.pjtcn_days_cost) - 
                                (cn.pjtcn_prod_price * cn.pjtcn_discount_base) * 
                                cn.pjtcn_days_cost + 
                                (cn.pjtcn_prod_price * cn.pjtcn_days_trip) - 
                                ( (cn.pjtcn_prod_price * cn.pjtcn_discount_trip) * cn.pjtcn_days_trip ) + 
                                (cn.pjtcn_prod_price * cn.pjtcn_days_test) - 
                                (cn.pjtcn_prod_price * cn.pjtcn_discount_test) * cn.pjtcn_days_test as costo,
                                cn.ver_id as verId,
                                ( (cn.pjtcn_insured * cn.pjtcn_prod_price))*  cn.pjtcn_days_cost AS seguro,  dt.pjtdt_id, pj.pjt_name
                            FROM ctt_projects_detail AS dt
                            INNER JOIN ctt_products AS pr ON pr.prd_id=dt.prd_id
                            INNER JOIN ctt_projects_content AS cn ON cn.pjtvr_id = dt.pjtvr_id
                            LEFT JOIN ctt_series AS sr ON sr.ser_id = dt.ser_id
                            INNER JOIN ctt_projects AS pj ON pj.pjt_id = cn.pjt_id
                            WHERE cn.pjt_id = $pjtId AND cn.pjtcn_prod_level != 'K' AND pr.prd_level != 'A' UNION SELECT cn.pjtcn_prod_name, cn.pjtcn_prod_name as prd_name, cn.pjtcn_prod_sku AS prd_sku,sr.ser_situation,
                                ifnull(sr.ser_comments,'') AS ser_comments, ifnull(sr.ser_status,'1') as ser_status,
                                cn.pjtcn_quantity as quantity,
                                ((cn.pjtcn_prod_price * cn.pjtcn_days_cost) - 
                                (cn.pjtcn_prod_price * cn.pjtcn_discount_base) * 
                                cn.pjtcn_days_cost + 
                                (cn.pjtcn_prod_price * cn.pjtcn_days_trip) - 
                                ( (cn.pjtcn_prod_price * cn.pjtcn_discount_trip) * cn.pjtcn_days_trip ) + 
                                (cn.pjtcn_prod_price * cn.pjtcn_days_test) - 
                                (cn.pjtcn_prod_price * cn.pjtcn_discount_test) * cn.pjtcn_days_test) * cn.pjtcn_quantity as costo,
                                cn.ver_id as verId,
                                (( (cn.pjtcn_insured * cn.pjtcn_prod_price)) *  cn.pjtcn_days_cost)   AS seguro,  dt.pjtdt_id, pj.pjt_name
                            FROM ctt_projects_detail AS dt
                            INNER JOIN ctt_products AS pr ON pr.prd_id=dt.prd_id
                            INNER JOIN ctt_projects_content AS cn ON cn.pjtvr_id = dt.pjtvr_id
                            LEFT JOIN ctt_series AS sr ON sr.ser_id = dt.ser_id
                            INNER JOIN ctt_projects AS pj ON pj.pjt_id = cn.pjt_id
                            WHERE pj.pjt_parent = $pjtId AND cn.pjtcn_prod_level = 'K' and pj.pjt_status in(8,9) GROUP BY cn.pjtcn_id";
                        }
                    }
                }
                
           /*  $qry = "SELECT * , 
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
                WHERE cn.pjt_id = $pjtId;"; */

                

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
        $type = $this->db->real_escape_string($param['type']);
        if ($type == 1) {
            $qry = "SELECT ifnull(sum(pmt_price),0) as maintenance 
                FROM ctt_products_maintenance WHERE pjt_id = $pjtId";
        }else{
            if ($pjtId > 0) {
                $qry = "SELECT ifnull(sum(pmt_price),0) as maintenance 
                FROM ctt_products_maintenance as man 
                    INNER JOIN ctt_projects AS pj ON pj.pjt_id = man.pjt_id 
                    WHERE pj.pjt_parent = $pjtId and pj.pjt_status in(8,9)";
            }
        }
        
        
        return $this->db->query($qry);
    }

    // OBTENER DATOS TOTALES PARA LOS EQUIPOS BASE, EXTRA, DIAS Y SUBARRENDOS
    public function totalEquipo($param)
    {

        $pjtId = $this->db->real_escape_string($param['pjtId']);
        $equipo = $this->db->real_escape_string($param['equipo']);
        $type = $this->db->real_escape_string($param['type']);
        if ($type == 1) {
            $qry = "SELECT IFNULL(SUM((pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost)-(pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_discount_base) +
                (pc.pjtcn_quantity* pc.pjtcn_days_trip * pc.pjtcn_prod_price)-(pc.pjtcn_quantity* pc.pjtcn_days_trip * pc.pjtcn_prod_price * pc.pjtcn_discount_trip) +
                (pc.pjtcn_quantity* pc.pjtcn_days_test * pc.pjtcn_prod_price) - (pc.pjtcn_quantity* pc.pjtcn_days_test * pc.pjtcn_prod_price * pc.pjtcn_discount_test)
                + (pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_insured)-(pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_insured * pc.pjtcn_discount_insured)),0) monto,
                $equipo as section
                FROM  ctt_projects_content AS pc
                LEFT JOIN ctt_projects AS pjt ON pjt.pjt_id = pc.pjt_id
                WHERE pjt.pjt_id = $pjtId AND pc.pjtcn_section = $equipo";
        }else{
            if ($pjtId > 0) {
                $qry = "SELECT IFNULL(SUM((pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost)-(pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_discount_base) +
                    (pc.pjtcn_quantity* pc.pjtcn_days_trip * pc.pjtcn_prod_price)-(pc.pjtcn_quantity* pc.pjtcn_days_trip * pc.pjtcn_prod_price * pc.pjtcn_discount_trip) +
                    (pc.pjtcn_quantity* pc.pjtcn_days_test * pc.pjtcn_prod_price) - (pc.pjtcn_quantity* pc.pjtcn_days_test * pc.pjtcn_prod_price * pc.pjtcn_discount_test)
                    + (pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_insured)-(pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_insured * pc.pjtcn_discount_insured)),0) monto,
                    $equipo as section
                    FROM  ctt_projects_content AS pc
                    LEFT JOIN ctt_projects AS pjt ON pjt.pjt_id = pc.pjt_id
                    WHERE pjt.pjt_parent = $pjtId AND pc.pjtcn_section = $equipo and pjt.pjt_status in(8,9)";
            }
        }

        
        
        return $this->db->query($qry);
    }

    // OBTENER PAGOS TOTALES PARA EL PROYECTO
    public function totalesProyecto($param)
    {
        $pjtId = $this->db->real_escape_string($param['pjtId']);
        $type = $this->db->real_escape_string($param['type']);
        if ($type == 1) {
            $qry = "SELECT IFNULL(SUM((pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost)-(pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_discount_base) +
                (pc.pjtcn_quantity* pc.pjtcn_days_trip * pc.pjtcn_prod_price)-(pc.pjtcn_quantity* pc.pjtcn_days_trip * pc.pjtcn_prod_price * pc.pjtcn_discount_trip) +
                (pc.pjtcn_quantity* pc.pjtcn_days_test * pc.pjtcn_prod_price) - (pc.pjtcn_quantity* pc.pjtcn_days_test * pc.pjtcn_prod_price * pc.pjtcn_discount_test)
                + (pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_insured)-(pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_insured * pc.pjtcn_discount_insured)),0) monto
                FROM  ctt_projects_content AS pc
                LEFT JOIN ctt_projects AS pjt ON pjt.pjt_id = pc.pjt_id
                WHERE pjt.pjt_id = $pjtId ";
        }else{
            if ($pjtId > 0) {
                $qry = "SELECT IFNULL(SUM((pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost)-(pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_discount_base) +
                (pc.pjtcn_quantity* pc.pjtcn_days_trip * pc.pjtcn_prod_price)-(pc.pjtcn_quantity* pc.pjtcn_days_trip * pc.pjtcn_prod_price * pc.pjtcn_discount_trip) +
                (pc.pjtcn_quantity* pc.pjtcn_days_test * pc.pjtcn_prod_price) - (pc.pjtcn_quantity* pc.pjtcn_days_test * pc.pjtcn_prod_price * pc.pjtcn_discount_test)
                + (pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_insured)-(pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_insured * pc.pjtcn_discount_insured)),0) monto
                FROM  ctt_projects_content AS pc
                LEFT JOIN ctt_projects AS pjt ON pjt.pjt_id = pc.pjt_id
                WHERE pjt.pjt_parent = $pjtId and pjt.pjt_status in(8,9)";
            }
           
        }
        
        
        return $this->db->query($qry);
    }
    // OBTENER DATOS PAGOS TOTALES DE LOS PREPAGOS REALIZADOS POR EL CLIENTE
    public function totalPrepago($param)
    {
        $pjtId = $this->db->real_escape_string($param['pjtId']);
        $type = $this->db->real_escape_string($param['type']);
        if ($type == 1) {
            $qry = "SELECT ifnull(sum(prp_amount),0) prp_amount FROM ctt_prepayments AS prp 
            WHERE prp.pjt_id = $pjtId";
        }else{
            if ($pjtId > 0) {
                $qry = "SELECT ifnull(sum(prp_amount),0) prp_amount FROM ctt_prepayments AS prp 
                INNER JOIN ctt_projects AS pj ON pj.pjt_id = prp.pjt_id
                WHERE (pj.pjt_parent =  $pjtId and pj.pjt_status in(8,9)) OR pj.pjt_id = $pjtId ";
            }
        }
        return $this->db->query($qry);
    }
    // LISTAR COMENTARIOS
    public function listComments($params)
    {
         $pjt_id = $this->db->real_escape_string($params['pjId']);
        
         $qry = "SELECT com_id, com_user, com_comment FROM ctt_comments 
                 WHERE com_action_id=$pjt_id
                 ORDER BY com_date;";
 
        return $this->db->query($qry);
    }

}