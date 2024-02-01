<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class ProjectPlansModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

/** ====== Listado de proyectos ===========================================  */
    public function listProjects($params)
    {
        // Debe leer todos los proyectos que se encuentren en estaus 2 - Presupuesto
        $pjId = $this->db->real_escape_string($params['pjId']);

        $qry = "SELECT 
                    pj.pjt_id  
                    , pj.pjt_number 
                    , pj.pjt_name  
                    , DATE_FORMAT(pj.pjt_date_project,'%d/%m/%Y') AS pjt_date_project 
                    , DATE_FORMAT(pj.pjt_date_start,'%d/%m/%Y') AS pjt_date_start 
                    , DATE_FORMAT(pj.pjt_date_end,'%d/%m/%Y') AS pjt_date_end
                    , pj.pjt_time
                    , pj.pjt_location 
                    , pj.pjt_status 
                    , pj.cuo_id 
                    , pj.loc_id 
                    , co.cus_id 
                    , co.cus_parent 
                    , pj.pjt_parent 
                    , lo.loc_type_location 
                    , pt.pjttp_name 
                    , pt.pjttp_id
                    , pj.pjttc_id
                    , '$pjId' as pjId
                    , pj.pjt_how_required
                    , pjt_trip_go
                    , pjt_trip_back
                    , pjt_to_carry_on
                    , pjt_to_carry_out
                    , pjt_test_tecnic
                    , pjt_test_look
                    , pj.pjt_status
                FROM ctt_projects AS pj
                LEFT JOIN ctt_customers_owner AS co ON co.cuo_id = pj.cuo_id
                LEFT JOIN ctt_location AS lo ON lo.loc_id = pj.loc_id
                LEFT JOIN ctt_projects_type As pt ON pt.pjttp_id = pj.pjttp_id
                WHERE pj.pjt_status in ('2','40') ORDER BY pj.pjt_id DESC;
                ";
        return $this->db->query($qry);
    }   
    
/** ====== Listado de proyectos padre ========================================================  */        
    public function listProjectsParents($params)
    {
        // Debe leer todos los proyectos que se encuentren en estaus 40 - Cotización
        $qry = "SELECT pjt_id, pjt_name, pjt_number 
                FROM ctt_projects 
                WHERE pjt_status = '40' ORDER BY pjt_name ASC;
                ";
        return $this->db->query($qry);
    }    

/** ====== Listado de versiones ==============================================================  */
    public function listVersion($params)
    {
        $pjtId = $this->db->real_escape_string($params['pjtId']);

        $qry3 = "SELECT * FROM ctt_version WHERE pjt_id = $pjtId AND ver_status = 'R' ORDER BY ver_date DESC;";
        return $this->db->query($qry3);
    }    

/** ====== Listado de contenido del proyecto =================================================  */
    public function listBudgets($params)
    {
        $pjtId = $this->db->real_escape_string($params['pjtId']);
        $verId = $this->db->real_escape_string($params['verId']);

        $qry3 = "DELETE FROM ctt_projects_mice WHERE pjt_id = $pjtId;";
        $this->db->query($qry3);

        $qry4 = "INSERT INTO ctt_projects_mice (
                    pjtvr_id, pjtvr_action, pjtvr_prod_sku, pjtvr_prod_name, pjtvr_prod_price, pjtvr_quantity, 
                    pjtvr_quantity_ant, pjtvr_days_base, pjtvr_days_base_ant,  pjtvr_days_cost, pjtvr_discount_base, pjtvr_discount_insured, 
                    pjtvr_days_trip, pjtvr_discount_trip, pjtvr_days_test, pjtvr_discount_test, pjtvr_insured, 
                    pjtvr_prod_level, pjtvr_section, pjtvr_status, pjtvr_order, ver_id, prd_id, pjt_id
                )
                SELECT pjtvr_id, 'N' AS pjtvr_action, pjtvr_prod_sku, pjtvr_prod_name, pjtvr_prod_price, pjtvr_quantity, 
                    pjtvr_quantity AS pjtvr_quantity_ant, pjtvr_days_base, pjtvr_days_base as pjtvr_days_base_ant, pjtvr_days_cost, pjtvr_discount_base, pjtvr_discount_insured, 
                    pjtvr_days_trip, pjtvr_discount_trip, pjtvr_days_test, pjtvr_discount_test, pjtvr_insured, pjtvr_prod_level, 
                    pjtvr_section, pjtvr_status, pjtvr_order, ver_id, prd_id, pjt_id 
                FROM ctt_projects_version 
                WHERE ver_id = $verId;  ";
        $this->db->query($qry4);

        $qry5 = "SELECT pc.*, pj.pjt_id, sb.sbc_name,
                    date_format(pj.pjt_date_start, '%Y%m%d') AS pjt_date_start, 
                    date_format(pj.pjt_date_end, '%Y%m%d') AS pjt_date_end, pd.srv_id,
                    CASE 
                        WHEN pjtvr_prod_level ='K' THEN 
                            (SELECT count(*) FROM ctt_products_packages WHERE prd_parent = pc.prd_id)
                        WHEN pjtvr_prod_level ='P' THEN 
                            (SELECT prd_stock FROM ctt_products WHERE prd_id = pc.prd_id)
                        ELSE 
                            (SELECT prd_stock FROM ctt_products WHERE prd_id = pc.prd_id)
                        END AS bdg_stock,
                    CASE 
                        WHEN pjtvr_section != 1 then (
                            SELECT ifnull(sum(datediff(pg.pjtpd_day_end, pg.pjtpd_day_start) +1), pc.pjtvr_days_base) AS dias 
                            FROM ctt_projects_detail AS pf
                            INNER JOIN ctt_projects_periods AS pg ON pg.pjtdt_id = pf.pjtdt_id
                            WHERE pf.pjtvr_id =  pc.pjtvr_id and pf.prd_id = pd.prd_id
                        )
                    else pc.pjtvr_days_base end  as daybasereal,
                    (Select count(ser_comments)  from ctt_series AS ser
							INNER JOIN ctt_projects_detail AS pjd ON pjd.ser_id = ser.ser_id
							INNER JOIN ctt_projects_version AS pjv ON pjv.pjtvr_id = pjd.pjtvr_id
							where ser.prd_id = pc.prd_id AND pjv.pjt_id=pj.pjt_id AND pjv.ver_id=pc.ver_id AND ser.ser_comments!='' AND pjv.pjtvr_section = pc.pjtvr_section
							ORDER BY ser.prd_id) as comments, pc.pjtvr_id 
                FROM ctt_projects_version AS pc
                INNER JOIN ctt_projects AS pj ON pj.pjt_id = pc.pjt_id
                INNER JOIN ctt_products AS pd ON pd.prd_id = pc.prd_id
                LEFT JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
                WHERE pc.ver_id = $verId ORDER BY pc.pjtvr_order asc;"; // *** Edna V1

        return $this->db->query($qry5); //**modificado por ed */
    } 

/** ====== Listado clientes ==================================================================  */
    public function listCustomers($params)
    {
        $prd = $this->db->real_escape_string($params['prm']);

        $qry = "SELECT cs.*, ct.cut_name FROM ctt_customers AS cs
                INNER JOIN ctt_customers_type AS ct ON ct.cut_id = cs.cut_id
                WHERE cs.cus_status = 1 ORDER BY cs.cus_name;";
        return $this->db->query($qry);
    }    

/** ====== Listado de relaciones de clientes =================================================  */
    public function listCustomersOwn($params)
    {
        $qry = "SELECT * FROM ctt_customers_owner";
        return $this->db->query($qry);
    }    

/** ====== Listado de descuentos =============================================================  */
    public function listDiscounts($params)
    {
        $level = $this->db->real_escape_string($params['level']);
        $qry = "SELECT *, dis_discount*100 AS dis_porcentaje FROM ctt_discounts WHERE dis_level = $level ORDER BY dis_discount;";
        return $this->db->query($qry);
    }    
     
/** ====== Listado de los tipos de proyecto ==================================================  */
    public function listProjectsType($params)
    {

        $qry = "SELECT * FROM ctt_projects_type ORDER BY pjttp_name;";
        return $this->db->query($qry);
    }    
   
/** ====== Listado de tipos de llamados ======================================================  */
    public function listProjectsTypeCalled($params)
    {

        $qry = "SELECT * FROM ctt_projects_type_called ORDER BY pjttc_id";
        return $this->db->query($qry);
    }    

/** ====== Listado de productos ==============================================================  */
    public function listProducts($params)
    {

        $word = $this->db->real_escape_string($params['word']);
        $dstr = $this->db->real_escape_string($params['dstr']);
        $dend = $this->db->real_escape_string($params['dend']);

        $qry = "SELECT pd.prd_id, pd.prd_sku, pd.prd_name, pd.prd_price, pd.prd_level, pd.prd_insured, pd.prd_type_asigned,
                        sb.sbc_name,
                CASE 
                    WHEN prd_type_asigned ='KP' THEN 
                        (SELECT count(*) FROM ctt_products_packages WHERE prd_parent = pd.prd_id)
                    WHEN prd_type_asigned !='KP' THEN 
                        (SELECT prd_stock-fun_buscarentas(pd.prd_sku) FROM ctt_products WHERE prd_id = pd.prd_id)
                    ELSE 
                        (SELECT prd_stock-fun_buscarentas(pd.prd_sku) FROM ctt_products WHERE prd_id = pd.prd_id)
                    END AS stock
                FROM ctt_products AS pd
                INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
                WHERE (upper(pd.prd_name) LIKE '%$word%' OR upper(pd.prd_sku) LIKE '%$word%') 
                        AND pd.prd_status = 1 AND pd.prd_visibility = 1 AND sb.cat_id NOT IN (16)
                ORDER BY pd.prd_name ;";
        return $this->db->query($qry);
    } 

    // Listado de productos con subarrendo
    public function listProductsSub($params)
    {
        $word = $this->db->real_escape_string($params['word']);
        $dstr = $this->db->real_escape_string($params['dstr']);
        $dend = $this->db->real_escape_string($params['dend']);

        $qry = "SELECT pd.prd_id, pd.prd_sku, pd.prd_name, pd.prd_price, pd.prd_level, pd.prd_insured, pd.prd_type_asigned,
                        sb.sbc_name,
                CASE 
                    WHEN prd_type_asigned ='KP' THEN 
                        (SELECT count(*) FROM ctt_products_packages WHERE prd_parent = pd.prd_id)
                    WHEN prd_type_asigned !='KP' THEN 
                        (SELECT prd_stock-fun_buscarentas(pd.prd_sku) FROM ctt_products WHERE prd_id = pd.prd_id)
                    ELSE 
                        (SELECT prd_stock-fun_buscarentas(pd.prd_sku) FROM ctt_products WHERE prd_id = pd.prd_id)
                    END AS stock
            FROM ctt_products AS pd
            INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
            INNER JOIN ctt_subletting AS sbl ON sbl.prd_id = pd.prd_id
            WHERE (upper(pd.prd_name) LIKE '%$word%' OR upper(pd.prd_sku) LIKE '%$word%') 
                        AND pd.prd_status = 1 AND pd.prd_visibility = 1 AND sb.cat_id NOT IN (16)
            ORDER BY pd.prd_name ;";
        return $this->db->query($qry);
    } 


/** ====== Lista los comentarios registrados al proyecto =====================================  */
    public function listComments($params)
    {
        $pjtId = $this->db->real_escape_string($params['pjId']);


        $qry = "SELECT com_id, com_date,com_user, com_comment 
                FROM ctt_comments 
                WHERE com_source_section = 'projects' 
                AND com_action_id = $pjtId
                ORDER BY com_date ASC;";
        return $this->db->query($qry);
    }    

/** ====== Promueve el presupuesto a proyecto ================================================  */
    public function promoteToProject($params)
    {
        $pjtId         = $this->db->real_escape_string($params['pjtId']);
        $verId         = $this->db->real_escape_string($params['verId']);

        $qry1 = "UPDATE ctt_projects SET pjt_status = '4' WHERE pjt_id = $pjtId;";
        $this->db->query($qry1);

        $qry2 = "UPDATE ctt_version SET ver_status = 'P', ver_active = '1', ver_master = '1', ver_code = 'P0001' WHERE ver_id = $verId;";
        $this->db->query($qry2);
       
        return $pjtId.'|'. $verId;
    }  
    
/** ====== Listado de productos relacionados como accesosrios ================================  */
    public function listProductsRelated($params)
    {
        $type = $this->db->real_escape_string($params['type']);
        $prdId = $this->db->real_escape_string($params['prdId']);
        $verId = $this->db->real_escape_string($params['verId']);
        $section = $this->db->real_escape_string($params['section']);  // *** Modificado por Ed

        $qry = "SELECT pr.prd_id, sr.ser_id, pr.prd_sku, pj.pjtdt_prod_sku, pr.prd_name,
                    pr.prd_level, ct.cat_name, ifnull(sr.ser_comments,'') as ser_comments, pr.prd_type_asigned,
                    ROW_NUMBER() OVER (ORDER BY sr.ser_sku DESC) AS reng
                FROM ctt_projects_detail AS pj
                INNER JOIN ctt_products AS pr ON pr.prd_id = pj.prd_id
                INNER JOIN ctt_subcategories AS sc ON sc.sbc_id = pr.sbc_id
                INNER JOIN ctt_categories AS ct ON ct.cat_id = sc.cat_id
                LEFT JOIN ctt_series as sr ON sr.prd_id = pj.prd_id AND sr.pjtdt_id = pj.pjtdt_id
                INNER JOIN ctt_projects_version AS cn ON cn.pjtvr_id = pj.pjtvr_id and pj.pjtdt_belongs = 0
                WHERE  cn.prd_id  = $prdId  and cn.ver_id = $verId AND cn.pjtvr_section = $section
                ORDER BY reng, pr.prd_sku, pr.prd_type_asigned DESC;";   // ***Modificado por Ed
        /* $qry = "SELECT pr.prd_id, sr.ser_id, pr.prd_sku, pj.pjtdt_prod_sku, pr.prd_name,
                pr.prd_level, ct.cat_name, ifnull(sr.ser_comments,'') as ser_comments,
                ROW_NUMBER() OVER (ORDER BY sr.ser_sku DESC) AS reng
            FROM ctt_projects_detail AS pj
            INNER JOIN ctt_products AS pr ON pr.prd_id = pj.prd_id
            INNER JOIN ctt_subcategories AS sc ON sc.sbc_id = pr.sbc_id
            INNER JOIN ctt_categories AS ct ON ct.cat_id = sc.cat_id
            LEFT JOIN ctt_series as sr ON sr.prd_id = pj.prd_id AND sr.pjtdt_id = pj.pjtdt_id
            INNER JOIN ctt_projects_version AS cn ON cn.pjtvr_id = pj.pjtvr_id and pj.pjtdt_belongs = 0
            WHERE  cn.prd_id  = $prdId  and cn.ver_id = $verId AND cn.pjtvr_section = $section
            ORDER BY pj.pjtdt_prod_sku, reng ASC;";   // ***Modificado por Ed */

        return $this->db->query($qry);

    }

/** ====== Muestra el inventario de productos en almacen =====================================  */
    public function stockProducts($params)
    {
        $prdId = $this->db->real_escape_string($params['prdId']);

        $qry = "SELECT  ifnull(pdt.pjtdt_prod_sku,'') as pjtdt_prod_sku, 
                        ifnull(ser.ser_sku,'') as ser_sku, 
                        ifnull(ser.ser_serial_number,'') as ser_serial_number, 
                        ifnull(ser.ser_situation,'') as ser_situation, 
                        ifnull(pjt.pjt_name,'') as pjt_name,
                        ifnull(ped.pjtpd_day_start,'') AS pjtpd_day_start, 
                        ifnull(ped.pjtpd_day_end,'') AS pjtpd_day_end
                FROM  ctt_series AS ser     
                LEFT JOIN ctt_projects_detail AS pdt ON pdt.ser_id = ser.ser_id 
                LEFT JOIN ctt_projects_content AS pcn ON pcn.pjtvr_id = pdt.pjtvr_id
                LEFT JOIN ctt_projects AS pjt ON pjt.pjt_id = pcn.pjt_id
                LEFT JOIN ctt_projects_periods AS ped ON ped.pjtdt_id = pdt.pjtdt_id
                WHERE ser.prd_id = $prdId AND pdt.sttd_id != 4 Order by pdt.pjtdt_prod_sku, ped.pjtpd_day_start ASC;";
        return $this->db->query($qry);
    } 


/** ====== cuenta los productos que no se tiene en existencia y estan solicitados  ===========  */
    public function countPending($params)
    {
        $pjtvrId    = $this->db->real_escape_string($params['pjtvrId']);
        $prdId      = $this->db->real_escape_string($params['prdId']);

        $qry = "SELECT '$prdId' AS prd_id, '$pjtvrId' AS pjtvr_id, count(*) as counter
                  FROM  ctt_projects_detail 
                 WHERE  pjtvr_id = $pjtvrId
                   AND  (pjtdt_prod_sku = 'Pendiente')
                   AND  pjtdt_belongs = 0; ";

        return $this->db->query($qry);
    } 

/** ==== Valida existencia de dias de viaje ==================================  */
    public function getExistTrip($params)
    {
        $pjtvrId    = $this->db->real_escape_string($params['pjtvrId']);
        $prdId      = $this->db->real_escape_string($params['prdId']);

        $qry = "SELECT COUNT(*) AS existrip 
                FROM ctt_projects_content
                WHERE pjtcn_days_trip<>0 AND pjt_id=$prdId";

        return $this->db->query($qry);
    }    


/** ====== Actualiza cifras e la tabla temporal ==============================================  */
    public function updateMice($params)
    {
        $pjtId      = $this->db->real_escape_string($params['pjtId']);
        $prdId      = $this->db->real_escape_string($params['prdId']);
        $field      = $this->db->real_escape_string($params['field']);
        $value      = $this->db->real_escape_string($params['value']);
        $section    = $this->db->real_escape_string($params['section']);
        $action     = $this->db->real_escape_string($params['action']);

        $qry1 = "UPDATE ctt_projects_mice 
                SET $field = $value,  pjtvr_action = '$action'
                WHERE pjt_id = $pjtId AND prd_id = $prdId AND pjtvr_section = $section 
                AND (pjtvr_action = 'N' OR pjtvr_action = 'U');";
        $this->db->query($qry1);

        $qry2 = "UPDATE ctt_projects_mice 
                SET $field = $value
                WHERE pjt_id = $pjtId AND prd_id = $prdId AND pjtvr_section = $section AND pjtvr_action = 'A';";
        return $this->db->query($qry2);

    }


/** ====== Actualiza el orden delos productos en la cotizacion ===============================  */
    public function updateOrder($params)
    {
        $pjtId      = $this->db->real_escape_string($params['pjtId']);
        $prdId      = $this->db->real_escape_string($params['prdId']);
        $order      = $this->db->real_escape_string($params['order']);
        $section    = $this->db->real_escape_string($params['section']);
        
        $qry1 = "UPDATE ctt_projects_mice 
                SET pjtvr_order = $order
                WHERE pjt_id = $pjtId AND prd_id = $prdId AND pjtvr_section = $section;";
       return $this->db->query($qry1);
    }    


/** ====== Agrega un nuevo comentario ========================================================  */
    public function InsertComment($params, $userParam)
    {

        $group = explode('|',$userParam);

        $user   = $group[2];
        $pjtId  = $this->db->real_escape_string($params['pjtId']);
        $comSrc = $this->db->real_escape_string($params['comSrc']);
        $comComment  = $this->db->real_escape_string($params['comComment']);

        $qry1 = "INSERT INTO ctt_comments (
                        com_source_section, 
                        com_action_id, 
                        com_user, 
                        com_comment, 
                        com_status
                ) VALUES (
                        '$comSrc', 
                        $pjtId, 
                        '$user',
                        '$comComment',
                        1
                );";
        $this->db->query($qry1);
        $comId = $this->db->insert_id;

        $qry2 = "   SELECT com_id, com_date, com_user, com_comment 
                    FROM ctt_comments 
                    WHERE com_id = $comId;";
        return $this->db->query($qry2);
    }

/** ====== Promueve el presupuesto proyecto ==================================================  */
    public function UpdateProject($params)
    {
        $cuo_id         = $this->db->real_escape_string($params['cuoId']);
        $cus_id         = $this->db->real_escape_string($params['cusId']); 
        $cus_Parent     = $this->db->real_escape_string($params['cusParent']);

        $qry01 = "  UPDATE      ctt_customers_owner 
                        SET     cus_id      = '$cus_id', 
                                cus_parent  = '$cus_Parent'
                        WHERE   cuo_id      = '$cuo_id';";

        $this->db->query($qry01);

        $pjt_id                 = $this->db->real_escape_string($params['projId']); 
        $pjt_name               = $this->db->real_escape_string($params['pjtName']); 
        $pjt_date_start         = $this->db->real_escape_string($params['pjtDateStart']);
        $pjt_date_end           = $this->db->real_escape_string($params['pjtDateEnd']); 
        $pjt_time               = $this->db->real_escape_string($params['pjtTime']); 
        $pjt_location           = $this->db->real_escape_string($params['pjtLocation']);
        $pjt_type               = $this->db->real_escape_string($params['pjtType']);
        $pjttc_id               = $this->db->real_escape_string($params['pjttcId']);
        $loc_id                 = $this->db->real_escape_string($params['locId']);
        $pjt_how_required       = $this->db->real_escape_string($params['pjtHowRequired']);
        $pjt_trip_go            = $this->db->real_escape_string($params['pjtTripGo']);
        $pjt_trip_back          = $this->db->real_escape_string($params['pjtTripBack']);
        $pjt_to_carry_on        = $this->db->real_escape_string($params['pjtToCarryOn']);
        $pjt_to_carry_out       = $this->db->real_escape_string($params['pjtToCarryOut']);
        $pjt_test_tecnic        = $this->db->real_escape_string($params['pjtTestTecnic']);
        $pjt_test_look          = $this->db->real_escape_string($params['pjtTestLook']);

        $qry02 = "UPDATE    ctt_projects
                    SET     pjt_name            = '$pjt_name', 
                            pjt_date_start      = '$pjt_date_start', 
                            pjt_date_end        = '$pjt_date_end',
                            pjt_time            = '$pjt_time',
                            pjt_location        = '$pjt_location', 
                            pjt_how_required    = '$pjt_how_required',
                            pjt_trip_go         = '$pjt_trip_go',
                            pjt_trip_back       = '$pjt_trip_back',
                            pjt_to_carry_on     = '$pjt_to_carry_on',
                            pjt_to_carry_out    = '$pjt_to_carry_out',
                            pjt_test_tecnic     = '$pjt_test_tecnic',
                            pjt_test_look       = '$pjt_test_look',
                            pjttp_id            = '$pjt_type',  
                            cuo_id              = '$cuo_id',
                            loc_id              = '$loc_id',
                            pjttc_id            = '$pjttc_id'
                    WHERE   pjt_id              =  $pjt_id;
                    ";
        $this->db->query($qry02);

        return $pjt_id;
    }

/** ====== Actualiza las fechas en el periodo =================================================  */
    public function UpdatePeriods($params)
    {
        /* $pjtId                  = $this->db->real_escape_string($params['pjtId']);
        $pjtDateStart           = $this->db->real_escape_string($params['pjtDateStart']);
        $pjtDateEnd             = $this->db->real_escape_string($params['pjtDateEnd']); */
        $prodId                 = $this->db->real_escape_string($params['prdpId']);
        $dtinic                 = $this->db->real_escape_string($params['dtinic']);
        $dtfinl                 = $this->db->real_escape_string($params['dtfinl']);

        $qry = "UPDATE ctt_projects_periods SET 
                pjtpd_day_start = '$dtinic',
                pjtpd_day_end   = '$dtfinl' where pjtpd_id = '$prodId'";
        $this->db->query($qry);

        return $prodId;

    }

    public function getperiods($params){
        $prodId                 = $this->db->real_escape_string($params['prodId']);
        $prdsct                 = $this->db->real_escape_string($params['prdsct']);

        $qry = "SELECT ppr.pjtpd_id FROM ctt_projects_periods AS ppr 
        INNER JOIN ctt_projects_detail AS dt ON dt.pjtdt_id = ppr.pjtdt_id
        INNER JOIN ctt_projects_version AS pjv ON pjv.pjtvr_id = dt.pjtvr_id
        WHERE pjv.prd_id = '$prodId'  AND pjv.pjtvr_section = '$prdsct'";

        return $this->db->query($qry);
    }

/** ====== Promueve proyecto ==================================================================  */
    public function PromoteProject($params)
    {
        /* Actualiza el estado en 2 convirtiendolo en presupuesto  */
        $pjtId      = $this->db->real_escape_string($params['pjtId']);
        
        $qry = "UPDATE ctt_projects SET pjt_status = '3' WHERE pjt_id = $pjtId;";
        $this->db->query($qry);

        return $pjtId;
    }

    
/** ====== Guarda una nueva version ==========================================================  */
    public function saveDateProject($pjtId)
    {
        $pjtId = $this->db->real_escape_string($pjtId);

        $qry1 = "UPDATE ctt_projects 
                    SET pjt_date_last_motion = CURRENT_TIMESTAMP() 
                    WHERE pjt_id = '$pjtId' ";
        
        $this->db->query($qry1);

        $qry2 = "SELECT fun_getTotalProject($pjtId) as getTotalProject FROM DUAL;";  // solo trae un registro
        $result =  $this->db->query($qry2);
        $respuesta = $result->fetch_object();

        return $pjtId;
    }


/** ====== Guarda una nueva version ==========================================================  */
    public function SaveVersion($params)
    {
        $pjtId      = $this->db->real_escape_string($params['pjtId']);
        $verCode    = $this->db->real_escape_string($params['verCode']);
        $discount   = $this->db->real_escape_string($params['discount']);
        $verActive  = '1';
        $verMaster  = '1';
        $verStatus  = 'R';

        $qry1 = "UPDATE ctt_version SET ver_active = 0, ver_master = 0 WHERE pjt_id = $pjtId;";
        $this->db->query($qry1);

        $qry2 = "INSERT INTO ctt_version 
                (ver_code,   pjt_id, ver_active, ver_master,  ver_status, ver_discount_insured) 
                 VALUES ('$verCode', $pjtId, $verActive, $verMaster, '$verStatus', $discount);";
        $this->db->query($qry2);
        $result = $this->db->insert_id;

        return $result . '|' . $pjtId;
    }


/** ====== Agrega producto a la tabla temporal ===============================================  */
    public function AddProductMice($params)
    {
        $pjtvr_prod_sku         = $params['pjtvr_prod_sku'];
        $pjtvr_prod_name        = $params['pjtvr_prod_name'];
        $pjtvr_prod_price       = $params['pjtvr_prod_price'];
        $pjtvr_quantity         = $params['pjtvr_quantity'];
        $pjtvr_quantity_ant     = $params['pjtvr_quantity'];
        $pjtvr_days_base        = $params['pjtvr_days_base'];
        $pjtvr_days_base_ant    = $params['pjtvr_days_base'];
        $pjtvr_days_cost        = $params['pjtvr_days_cost'];
        $pjtvr_discount_base    = $params['pjtvr_discount_base'];
        $pjtvr_discount_insured = $params['pjtvr_discount_insured'];
        $pjtvr_days_trip        = $params['pjtvr_days_trip'];
        $pjtvr_discount_trip    = $params['pjtvr_discount_trip'];
        $pjtvr_days_test        = $params['pjtvr_days_test'];
        $pjtvr_discount_test    = $params['pjtvr_discount_test'];
        $pjtvr_insured          = $params['pjtvr_insured'];
        $pjtvr_prod_level       = $params['pjtvr_prod_level'];
        $pjtvr_section          = $params['pjtvr_section'];
        $pjtvr_status           = '1';
        $pjtvr_order            = $params['pjtvr_order'];
        $ver_id                 = $params['ver_id'];
        $prd_id                 = $params['prd_id'];
        $pjt_id                 = $params['pjt_id'];
        $pjtvr_action           = 'A';

        $qry1 = "SELECT MAX(pjtvr_order + 1) AS nextorder FROM ctt_projects_mice
                WHERE pjtvr_section= $pjtvr_section AND ver_id=$ver_id AND pjt_id=$pjt_id";
        $result =  $this->db->query($qry1);

        while($row = $result->fetch_assoc())
        {
            $nextorder = $row["nextorder"];
        
            $qry = "INSERT INTO ctt_projects_mice (
                pjtvr_prod_sku, pjtvr_action, pjtvr_prod_name, pjtvr_prod_price, 
                pjtvr_quantity, pjtvr_quantity_ant, pjtvr_days_base, pjtvr_days_base_ant, pjtvr_days_cost, 
                pjtvr_discount_base, pjtvr_discount_insured, pjtvr_days_trip, 
                pjtvr_discount_trip, pjtvr_days_test, pjtvr_discount_test, 
                pjtvr_insured, pjtvr_prod_level, pjtvr_section, pjtvr_status, 
                pjtvr_order, ver_id, prd_id, pjt_id ) 
                VALUES (
                '$pjtvr_prod_sku',
                '$pjtvr_action', 
                REPLACE('$pjtvr_prod_name','\¿','\''),
                '$pjtvr_prod_price',
                '$pjtvr_quantity',
                '$pjtvr_quantity_ant',
                '$pjtvr_days_base',
                '$pjtvr_days_base_ant',
                '$pjtvr_days_cost',
                '$pjtvr_discount_base',
                '$pjtvr_discount_insured',
                '$pjtvr_days_trip',
                '$pjtvr_discount_trip',
                '$pjtvr_days_test',
                '$pjtvr_discount_test',
                '$pjtvr_insured',
                '$pjtvr_prod_level',
                '$pjtvr_section',
                '$pjtvr_status',
                '$nextorder',
                '$ver_id',
                '$prd_id',
                '$pjt_id'
            ); ";
        }
        $this->db->query($qry);
        $result = $this->db->insert_id;
        
    }

/** ====== Actualiza contenido de la version =================================================  */
    public function settingMasterVersion($pjtId, $verId, $discount)
    {
        $qry1 = "UPDATE ctt_version 
                SET ver_master = 0, ver_active = 0, ver_discount_insured = $discount 
                WHERE pjt_id = $pjtId;";
        $this->db->query($qry1);
        
        $qry2 = "UPDATE ctt_version 
                SET ver_master = 1, ver_active = 1, ver_discount_insured = $discount 
                WHERE ver_id = $verId;";
        return $this->db->query($qry2);
    }

    public function settingDiscountVersion($pjtId, $verId, $discount)
    {
        $qry1 = "UPDATE ctt_version SET ver_discount_insured = $discount WHERE ver_id = $verId;";
        $this->db->query($qry1);
    }

    public function settingProjectVersion($pjtId, $verId)
    {
        //  Borra el contenido de la version anterior
        $qry1 = "DELETE FROM ctt_projects_version WHERE ver_id = $verId;";
        $this->db->query($qry1);
        
        //  Agrega el contenido en la version desde la tabla mice
        $qry2 = "INSERT INTO ctt_projects_version (
                    pjtvr_id, pjtvr_prod_sku, pjtvr_prod_name, pjtvr_prod_price, pjtvr_quantity, pjtvr_days_base, pjtvr_days_cost, 
                    pjtvr_discount_base, pjtvr_discount_insured, pjtvr_days_trip, pjtvr_discount_trip, pjtvr_days_test, pjtvr_discount_test, pjtvr_insured, 
                    pjtvr_prod_level, pjtvr_section, pjtvr_status, pjtvr_order, ver_id, prd_id, pjt_id)  
                SELECT 
                    pjtvr_id, pjtvr_prod_sku, pjtvr_prod_name, pjtvr_prod_price, pjtvr_quantity, pjtvr_days_base, pjtvr_days_cost, 
                    pjtvr_discount_base, pjtvr_discount_insured, pjtvr_days_trip, pjtvr_discount_trip, pjtvr_days_test, pjtvr_discount_test, pjtvr_insured, 
                    pjtvr_prod_level, pjtvr_section, pjtvr_status, pjtvr_order, ver_id, prd_id, pjt_id 
                FROM ctt_projects_mice WHERE pjtvr_action != 'D' AND pjt_id = $pjtId;";
        return $this->db->query($qry2);
            
    }
    
    public function settingProjectContent($pjtId, $verId)
    {
        $qry1 = "DELETE FROM ctt_projects_content WHERE pjt_id = $pjtId;";
        $this->db->query($qry1);
        
        $qry2 = "INSERT INTO ctt_projects_content (
                    pjtcn_prod_sku, pjtcn_prod_name, pjtcn_prod_price, pjtcn_quantity, pjtcn_days_base, pjtcn_days_cost, pjtcn_discount_base, pjtcn_discount_insured, 
                    pjtcn_days_trip, pjtcn_discount_trip, pjtcn_days_test, pjtcn_discount_test, pjtcn_insured, pjtcn_prod_level, pjtcn_section, 
                    pjtcn_status, pjtcn_order, ver_id, prd_id, pjt_id, pjtvr_id)
                SELECT 
                    pjtvr_prod_sku, pjtvr_prod_name, pjtvr_prod_price, pjtvr_quantity, pjtvr_days_base, pjtvr_days_cost, pjtvr_discount_base, pjtvr_discount_insured, 
                    pjtvr_days_trip, pjtvr_discount_trip, pjtvr_days_test, pjtvr_discount_test, pjtvr_insured, pjtvr_prod_level, pjtvr_section, 
                    pjtvr_status, pjtvr_order, ver_id, prd_id, pjt_id, pjtvr_id 
                FROM ctt_projects_version WHERE ver_id = $verId;";
        return $this->db->query($qry2);

     }
  
     public function getProjectVersion($pjtId)
     {
        $pjtId   = $this->db->real_escape_string($pjtId);
        $qry1 = "SELECT * FROM ctt_projects_content AS pc 
                 INNER JOIN ctt_projects AS pj ON pj.pjt_id = pc.pjt_id
                 INNER JOIN ctt_products AS pd ON pd.prd_id = pc.prd_id
                 WHERE pj.pjt_id = $pjtId AND pd.srv_id IN (1,4);";
        return $this->db->query($qry1);
       
     }
  
    public function getVersionMice($pjtId)
    {
            $qry1 = "SELECT * , 
            DATE(DATE_SUB(pj.pjt_date_start, INTERVAL(pc.pjtvr_days_trip + pc.pjtvr_days_test) DAY)) date_start,
            DATE(DATE_ADD(pj.pjt_date_start, INTERVAL (pc.pjtvr_days_base + pc.pjtvr_days_trip)-1 DAY)) AS date_end
                     FROM ctt_projects_mice AS pc
                     INNER JOIN ctt_version AS vr ON vr.ver_id = pc.ver_id
                     INNER JOIN ctt_projects AS pj ON pj.pjt_id = vr.pjt_id
                     INNER JOIN ctt_products AS pd ON pd.prd_id = pc.prd_id
                     WHERE pc.pjt_id = $pjtId;";
            return $this->db->query($qry1);
     }


/** ====== Agrega contenido de la nueva version ==============================================  */ // JJR agrego pjtvr_action != 'D' AND
    public function settinProjectVersion($pjtId, $verId )
    {
        $qry = "INSERT INTO ctt_projects_version (
                    pjtvr_prod_sku, pjtvr_prod_name, pjtvr_prod_price, pjtvr_quantity, pjtvr_days_base, pjtvr_days_cost,
                    pjtvr_discount_base, pjtvr_discount_insured, pjtvr_days_trip, pjtvr_discount_trip, pjtvr_days_test, pjtvr_discount_test, pjtvr_insured,
                    pjtvr_prod_level, pjtvr_section, pjtvr_status, pjtvr_order, ver_id, prd_id, pjt_id
                )
                SELECT 
                    pjtvr_prod_sku, pjtvr_prod_name, pjtvr_prod_price, pjtvr_quantity, pjtvr_days_base, pjtvr_days_cost,
                    pjtvr_discount_base, pjtvr_discount_insured, pjtvr_days_trip, pjtvr_discount_trip, pjtvr_days_test, pjtvr_discount_test, pjtvr_insured,
                    pjtvr_prod_level, pjtvr_section, pjtvr_status, pjtvr_order, '$verId' as ver_id, prd_id, pjt_id
                FROM ctt_projects_mice WHERE pjtvr_action != 'D' AND pjt_id = $pjtId;";

        return $this->db->query($qry);
            
    }

/** ====== Elimina los periodos de las series correspondientes al periodo ====================  */
    public function cleanPeriods($params)
    {
        $pjtId = $this->db->real_escape_string($params);
        $qry = "DELETE FROM ctt_projects_periods WHERE pjtdt_id IN (
                    SELECT DISTINCT pjtdt_id FROM ctt_projects_detail AS pdt 
                    INNER JOIN ctt_projects_content AS pcn ON pcn.pjtvr_id = pdt.pjtvr_id
                    WHERE pcn.pjt_id = $pjtId
                );";
        return $this->db->query($qry);
    }

/** ====== Restaura las series del proyecto a productos disponibles ==========================  */
    public function restoreSeries($params)
    {
        $pjtId = $this->db->real_escape_string($params);
        $qry = "UPDATE ctt_series 
                SET ser_situation = 'D', ser_stage ='D', pjtdt_id = 0 
                WHERE pjtdt_id IN (
                    SELECT DISTINCT pjtdt_id FROM ctt_projects_detail AS pdt 
                    INNER JOIN ctt_projects_content AS pcn ON pcn.pjtvr_id = pdt.pjtvr_id
                    WHERE pcn.pjt_id = $pjtId
                );";
        /* $qry2 = "UPDATE ctt_products 
        SET 
            prd_reserved = prd_reserved - 1
        WHERE prd_id = $prodId;";
    $this->db->query($qry2); */
        return $this->db->query($qry);
    }

/** ====== Elimina los registros del detalle del proyecto  ===================================  */
    public function cleanDetail($params)
    {
        $pjtId = $this->db->real_escape_string($params);
        $qry = "DELETE FROM ctt_projects_detail WHERE pjtvr_id IN  (
                    SELECT pjtvr_id FROM ctt_projects_content WHERE pjt_id = $pjtId
                );";
        return $this->db->query($qry);
    }

/** ====== Elimina los registros del contenido del proyecto  =================================  */
    public function cleanContent($params)
    {
        $pjtId = $this->db->real_escape_string($params);
        $qry = "DELETE FROM ctt_projects_content WHERE pjt_id = $pjtId;";
        return $this->db->query($qry);
    }

/** ====== Agrega los registros del contenido del proyecto  ==================================  */
    public function restoreContent($params)
    {
        $verId = $this->db->real_escape_string($params);
        $qry1 = "INSERT INTO ctt_projects_content (
                    pjtcn_prod_sku, pjtcn_prod_name, pjtcn_prod_price, pjtcn_quantity, pjtcn_days_base, pjtcn_days_cost, pjtcn_discount_base, pjtcn_discount_insured, 
                    pjtcn_days_trip, pjtcn_discount_trip, pjtcn_days_test, pjtcn_discount_test, pjtcn_insured, pjtcn_prod_level, pjtcn_section, 
                    pjtcn_status, pjtcn_order, ver_id, prd_id, pjt_id, pjtvr_id)
                SELECT 
                    pjtvr_prod_sku, pjtvr_prod_name, pjtvr_prod_price, pjtvr_quantity, pjtvr_days_base, pjtvr_days_cost, pjtvr_discount_base, pjtvr_discount_insured, 
                    pjtvr_days_trip, pjtvr_discount_trip, pjtvr_days_test, pjtvr_discount_test, pjtvr_insured, pjtvr_prod_level, pjtvr_section, 
                    pjtvr_status, pjtvr_order, ver_id, prd_id, pjt_id, pjtvr_id 
                FROM ctt_projects_version WHERE ver_id = $verId;";
        $this->db->query($qry1);

        $qry2 = "SELECT * 
                FROM ctt_projects_content AS pc
                INNER JOIN ctt_version AS vr ON vr.ver_id = pc.ver_id
                INNER JOIN ctt_projects AS pj ON pj.pjt_id = vr.pjt_id
                INNER JOIN ctt_products AS pd ON pd.prd_id = pc.prd_id
                WHERE pc.ver_id = $verId;";
        return $this->db->query($qry2);
    }

/** ====== Elimina los registros de detalle y series  ========================================  */
    public function KillQuantityDetail($params)
    {
        $pjtvrId = $this->db->real_escape_string($params['pjetId']);

        $qry1 = "WITH elements AS (
                        SELECT dt.*,
                            ROW_NUMBER() OVER (partition by dt.prd_id ORDER BY dt.pjtdt_prod_sku DESC) AS reng
                            FROM ctt_projects_detail AS dt
		                    LEFT join ctt_series as sr on sr.ser_id = dt.ser_id 
                            WHERE pjtvr_id = $pjtvrId AND (sr.prd_id_acc = 0 OR ISNULL(sr.prd_id_acc)) ORDER BY dt.pjtdt_prod_sku)
                SELECT pjtdt_id FROM elements WHERE reng =1;";
        $result =  $this->db->query($qry1);
 
        while($row = $result->fetch_assoc()){
            $pjtdtId = $row["pjtdt_id"];
            /* $qry2 = "UPDATE ctt_series 
                        SET ser_situation = 'D', 
                            ser_stage = 'D', 
                            pjtdt_id = 0 
                      WHERE pjtdt_id = $pjtdtId;";
            $this->db->query($qry2);

            $qry3 = "DELETE FROM ctt_projects_detail WHERE pjtdt_id = $pjtdtId;";
            $this->db->query($qry3);

            $qry4 = "DELETE FROM ctt_projects_periods WHERE pjtdt_id = $pjtdtId;";
            $this->db->query($qry4); */
            $qry = "SELECT pjd.pjtdt_id, pjd.ser_id FROM ctt_projects_detail AS pjd
            INNER JOIN ctt_projects_periods AS ppd ON ppd.pjtdt_id = pjd.pjtdt_id
            WHERE pjd.sttd_id = 3 AND pjd.ser_id = (SELECT pdt.ser_id FROM ctt_projects_detail AS pdt
            INNER JOIN ctt_series AS sr ON sr.ser_id = pdt.ser_id
            WHERE pdt.pjtdt_id = $pjtdtId AND prd_id_acc = 0 LIMIT 1) ORDER BY ppd.pjtpd_day_start ASC LIMIT 1";
        
            $result =  $this->db->query($qry);
            $pjtdt = $result->fetch_object();
            
            if ($pjtdt != null ) {
                $pjtdt_ft_id = $pjtdt->pjtdt_id; 
                $ser_id = $pjtdt->ser_id; 
                $qry = "SELECT sr.pjtdt_id FROM ctt_series AS sr WHERE sr.ser_id = $ser_id";
        
                $result =  $this->db->query($qry);
                $series = $result->fetch_object();
                if ($series != null) {
                    $pjtdt_id = $series->pjtdt_id;
                    if ($pjtdt_id == $pjtdtId) {
                    
                        $qry2 = "UPDATE ctt_series 
                                    SET ser_situation = 'EA', 
                                        ser_stage = 'R',
                                        pjtdt_id = $pjtdt_ft_id
                                WHERE ser_id = $ser_id;";
                        $this->db->query($qry2);
                       /*  $qry2 = "UPDATE ctt_products 
                                SET 
                                    prd_reserved = prd_reserved + 1
                                WHERE prd_id = $prodId;";
                            $this->db->query($qry2); */
            
                        $updtQry = "UPDATE ctt_projects_detail SET sttd_id = 1 where pjtdt_id=$pjtdt_ft_id";
                        $this->db->query($updtQry);
                    }
                }else{
                    $qry2 = "UPDATE ctt_series 
                        SET ser_situation = 'D', 
                                ser_stage = 'D', 
                                pjtdt_id = 0 
                        WHERE pjtdt_id = $pjtdtId;";
                    $this->db->query($qry2);
                   /*  $qry2 = "UPDATE ctt_products 
                                SET 
                                    prd_reserved = prd_reserved - 1
                                WHERE prd_id = $prodId;";
                            $this->db->query($qry2); */
                }
                
            }else{
                $qry2 = "UPDATE ctt_series 
                    SET ser_situation = 'D', 
                            ser_stage = 'D', 
                            pjtdt_id = 0 
                    WHERE pjtdt_id = $pjtdtId;";
                $this->db->query($qry2);

                /* $qry2 = "UPDATE ctt_products 
                                SET 
                                    prd_reserved = prd_reserved - 1
                                WHERE prd_id = $prodId;";
                            $this->db->query($qry2); */
            }
            $qry3 = "DELETE FROM ctt_projects_detail WHERE pjtdt_id = $pjtdtId;";
            $this->db->query($qry3);
        
            $qry4 = "DELETE FROM ctt_projects_periods WHERE pjtdt_id = $pjtdtId;";
            $this->db->query($qry4);
        }
        return '1';
    }

    public function KillQuantityDetailFijo($params)
    {
        $pjtvrId = $this->db->real_escape_string($params['pjetId']);

        $qry1 = "WITH elements AS (
                        SELECT dt.*,
                            ROW_NUMBER() OVER (partition by dt.prd_id ORDER BY dt.pjtdt_prod_sku DESC) AS reng
                            FROM ctt_projects_detail AS dt
		                    LEFT join ctt_series as sr on sr.ser_id = dt.ser_id 
                            WHERE pjtvr_id = $pjtvrId AND (sr.prd_id_acc = 0 OR ISNULL(sr.prd_id_acc)) ORDER BY dt.pjtdt_prod_sku)
                SELECT pjtdt_id,  ser_id FROM elements WHERE reng =1;";
        $result =  $this->db->query($qry1);
 
        while($row = $result->fetch_assoc()){
            $pjtdtId = $row["pjtdt_id"];
            $serId = $row["ser_id"];
            
            $qry = "SELECT pjd.pjtdt_id, pjd.ser_id FROM ctt_projects_detail AS pjd
            INNER JOIN ctt_projects_periods AS ppd ON ppd.pjtdt_id = pjd.pjtdt_id
            WHERE pjd.sttd_id = 3 AND pjd.ser_id = (SELECT pdt.ser_id FROM ctt_projects_detail AS pdt
            INNER JOIN ctt_series AS sr ON sr.ser_id = pdt.ser_id
            WHERE pdt.pjtdt_id = $pjtdtId AND prd_id_acc = 0 LIMIT 1) ORDER BY ppd.pjtpd_day_start ASC LIMIT 1";
            
        
            $result =  $this->db->query($qry);
            $pjtdt = $result->fetch_object();

            if ($pjtdt != null ) {
                $pjtdt_ft_id = $pjtdt->pjtdt_id; 
                $ser_id = $pjtdt->ser_id; 
                $qry = "SELECT sr.pjtdt_id FROM ctt_series AS sr WHERE sr.ser_id = $ser_id";
        
                $result =  $this->db->query($qry);
                $series = $result->fetch_object();

                

                if ($series != null) {
                    $pjtdt_id = $series->pjtdt_id;
                    if ($pjtdt_id == $pjtdtId) {
                    
                        $qry2 = "UPDATE ctt_series 
                                    SET ser_situation = 'EA', 
                                        ser_stage = 'R',
                                        pjtdt_id = $pjtdt_ft_id
                                WHERE ser_id = $ser_id;";
                        $this->db->query($qry2);

                        /* $qry2 = "UPDATE ctt_products 
                                SET 
                                    prd_reserved = prd_reserved + 1
                                WHERE prd_id = $prodId;";
                            $this->db->query($qry2);
             */
                        $updtQry = "UPDATE ctt_projects_detail SET sttd_id = 1 where pjtdt_id=$pjtdt_ft_id";
                        $this->db->query($updtQry);

                        
                    }
                }
                
                
            }else{
                
                $qry2 = "UPDATE ctt_series 
                    SET ser_situation = 'D', 
                            ser_stage = 'D', 
                            pjtdt_id = 0 
                    WHERE pjtdt_id = $pjtdtId;";
                $this->db->query($qry2);
                /* $qry2 = "UPDATE ctt_products 
                    SET 
                        prd_reserved = prd_reserved - 1
                    WHERE prd_id = $prodId;";
                $this->db->query($qry2); */
            }
            $qry3 = "DELETE FROM ctt_projects_detail WHERE pjtdt_id = $pjtdtId;";
            $this->db->query($qry3);
        
            $qry4 = "DELETE FROM ctt_projects_periods WHERE pjtdt_id = $pjtdtId;";
            $this->db->query($qry4);

            if ($serId != 0) {
                $qry = "SELECT pd.* FROM ctt_projects_detail AS pd
                INNER JOIN ctt_series AS sr ON sr.ser_id = pd.ser_id
                WHERE sr.prd_id_acc = $serId";
        
                $seriesAcc =  $this->db->query($qry);
                while ($row = $seriesAcc->fetch_assoc()) {
                    $ptdtId = $row['pjtdt_id'];
                    $ser_id = $row['ser_id'];
    
                    $qry = "SELECT pjd.pjtdt_id, pjd.ser_id FROM ctt_projects_detail AS pjd
                    INNER JOIN ctt_projects_periods AS ppd ON ppd.pjtdt_id = pjd.pjtdt_id
                    WHERE pjd.sttd_id = 3 AND pjd.ser_id = (SELECT pdt.ser_id FROM ctt_projects_detail AS pdt
                    INNER JOIN ctt_series AS sr ON sr.ser_id = pdt.ser_id
                    WHERE pdt.pjtdt_id = $ptdtId AND prd_id_acc > 0 LIMIT 1) ORDER BY ppd.pjtpd_day_start ASC LIMIT 1";
                    
                
                    $result =  $this->db->query($qry);
                    $pjtdtR = $result->fetch_object();
                    if ($pjtdtR != null ) {
                        $pjtdtftid = $pjtdtR->pjtdt_id; 
                        $serId = $pjtdtR->ser_id; 
                        $qry = "SELECT sr.pjtdt_id FROM ctt_series AS sr WHERE sr.ser_id = $serId";
                        $result =  $this->db->query($qry);
                        $srs = $result->fetch_object();
                        if ($srs != null) {
                            $pdt_id = $srs->pjtdt_id;
                            if ($pdt_id == $ptdtId) {
                                $qry2 = "UPDATE ctt_series 
                                            SET ser_situation = 'EA', 
                                                ser_stage = 'R',
                                                pjtdt_id = $pjtdtftid
                                        WHERE ser_id = $serId;";
                                $this->db->query($qry2);

                                /* $qry2 = "UPDATE ctt_products 
                                    SET 
                                        prd_reserved = prd_reserved + 1
                                    WHERE prd_id = $prodId;";
                                $this->db->query($qry2); */
                    
                                $updtQry = "UPDATE ctt_projects_detail SET sttd_id = 1 where pjtdt_id=$pjtdtftid";
                                $this->db->query($updtQry);
                            }
                        }
                    }else{
                        $qry2 = "UPDATE ctt_series 
                            SET ser_situation = 'D', 
                                    ser_stage = 'D', 
                                    pjtdt_id = 0 
                            WHERE pjtdt_id = $ptdtId;";
                        $this->db->query($qry2);
                        /* $qry2 = "UPDATE ctt_products 
                                SET 
                                    prd_reserved = prd_reserved - 1
                                WHERE prd_id = $prodId;";
                            $this->db->query($qry2); */
                    }
                    $qry3 = "DELETE FROM ctt_projects_detail WHERE pjtdt_id = $ptdtId;";
                    $this->db->query($qry3);
                
                    $qry4 = "DELETE FROM ctt_projects_periods WHERE pjtdt_id = $ptdtId;";
                    $this->db->query($qry4);
                } 
            }
              
           
        }
        return '1';
    }
    public function KillQuantityDetailVirtual($params)
    {
        $pjtvrId = $this->db->real_escape_string($params['pjetId']);

        $qry1 = "WITH elements AS (
            SELECT dt.pjtdt_id, dt.ser_id, dt.pjtdt_prod_sku, dt.prd_id, dt.pjtvr_id, dt.sttd_id,
                ROW_NUMBER() OVER (partition by dt.prd_id ORDER BY dt.pjtdt_prod_sku DESC) AS reng
                FROM ctt_projects_detail AS dt
              LEFT join ctt_series as sr on sr.ser_id = dt.ser_id 
                LEFT JOIN ctt_products AS pd ON pd.prd_id = dt.prd_id
                WHERE pjtvr_id = $pjtvrId AND (sr.prd_id_acc = 0 OR ISNULL(sr.prd_id_acc)) AND pd.prd_type_asigned = 'PV' ORDER BY dt.pjtdt_prod_sku)
      SELECT pjtdt_id,  ser_id, reng, pjtdt_prod_sku, prd_id FROM elements WHERE reng = 1;";
        $result =  $this->db->query($qry1);
 
        while($row = $result->fetch_assoc()){
            $pjtdtId = $row["pjtdt_id"];
            $prdId = $row["prd_id"];
            
            $qry = "SELECT pjd.pjtdt_id, pjd.ser_id FROM ctt_projects_detail AS pjd
            INNER JOIN ctt_projects_periods AS ppd ON ppd.pjtdt_id = pjd.pjtdt_id
            WHERE pjd.sttd_id = 3 AND pjd.ser_id = (SELECT pdt.ser_id FROM ctt_projects_detail AS pdt
            INNER JOIN ctt_series AS sr ON sr.ser_id = pdt.ser_id
            WHERE pdt.pjtdt_id = $pjtdtId AND prd_id_acc = 0 LIMIT 1) ORDER BY ppd.pjtpd_day_start ASC LIMIT 1";
            
        
            $result =  $this->db->query($qry);
            $pjtdt = $result->fetch_object();

            $qryProds = "SELECT * FROM ctt_products_packages WHERE prd_parent = $prdId";
            $prods = $this->db->query($qryProds);

            if ($pjtdt != null ) {
                $pjtdt_ft_id = $pjtdt->pjtdt_id; 
                $ser_id = $pjtdt->ser_id; 
                $qry = "SELECT sr.pjtdt_id FROM ctt_series AS sr WHERE sr.ser_id = $ser_id";
        
                $result =  $this->db->query($qry);
                $series = $result->fetch_object();

                

                if ($series != null) {
                    $pjtdt_id = $series->pjtdt_id;
                    if ($pjtdt_id == $pjtdtId) {
                    
                        $qry2 = "UPDATE ctt_series 
                                    SET ser_situation = 'EA', 
                                        ser_stage = 'R',
                                        pjtdt_id = $pjtdt_ft_id
                                WHERE ser_id = $ser_id;";
                        $this->db->query($qry2);
                       /*  $qry2 = "UPDATE ctt_products 
                                SET 
                                    prd_reserved = prd_reserved + 1
                                WHERE prd_id = $prodId;";
                            $this->db->query($qry2); */
            
                        $updtQry = "UPDATE ctt_projects_detail SET sttd_id = 1 where pjtdt_id=$pjtdt_ft_id";
                        $this->db->query($updtQry);

                        
                    }
                }
                
                
            }else{
                
                $qry2 = "UPDATE ctt_series 
                    SET ser_situation = 'D', 
                            ser_stage = 'D', 
                            pjtdt_id = 0 
                    WHERE pjtdt_id = $pjtdtId;";
                $this->db->query($qry2);

                /* $qry2 = "UPDATE ctt_products 
                    SET 
                        prd_reserved = prd_reserved - 1
                    WHERE prd_id = $prodId;";
                $this->db->query($qry2); */
            }
            $qry3 = "DELETE FROM ctt_projects_detail WHERE pjtdt_id = $pjtdtId;";
            $this->db->query($qry3);
        
            $qry4 = "DELETE FROM ctt_projects_periods WHERE pjtdt_id = $pjtdtId;";
            $this->db->query($qry4);

            while($row = $prods->fetch_assoc()){
                $prdId = $row["prd_id"];
                $pck_quantity = $row["pck_quantity"];
                
                for ($i=0; $i < $pck_quantity; $i++) { 
                    $query ="SELECT * FROM ctt_projects_detail AS pdt WHERE pdt.prd_id = $prdId AND pjtvr_id = $pjtvrId ORDER BY pdt.pjtdt_prod_sku LIMIT 1;";
                    $detailPakc= $this->db->query($query);
                    $series = $detailPakc->fetch_object();

                    if ($series != null) {
                        $pjtdtId = $series->pjtdt_id; 

                        $qry = "SELECT pjd.pjtdt_id, pjd.ser_id FROM ctt_projects_detail AS pjd
                        INNER JOIN ctt_projects_periods AS ppd ON ppd.pjtdt_id = pjd.pjtdt_id
                        WHERE pjd.sttd_id = 3 AND pjd.ser_id = (SELECT pdt.ser_id FROM ctt_projects_detail AS pdt
                        INNER JOIN ctt_series AS sr ON sr.ser_id = pdt.ser_id
                        WHERE pdt.pjtdt_id = $pjtdtId AND prd_id_acc = 0 LIMIT 1) ORDER BY ppd.pjtpd_day_start ASC LIMIT 1";
                        
                    
                        $result =  $this->db->query($qry);
                        $pjtdt = $result->fetch_object();
            
                        if ($pjtdt != null ) {
                            $pjtdt_ft_id = $pjtdt->pjtdt_id; 
                            $ser_id = $pjtdt->ser_id; 
                            $qry = "SELECT sr.pjtdt_id FROM ctt_series AS sr WHERE sr.ser_id = $ser_id";
                    
                            $result =  $this->db->query($qry);
                            $series = $result->fetch_object();
            
                            
            
                            if ($series != null) {
                                $pjtdt_id = $series->pjtdt_id;
                                if ($pjtdt_id == $pjtdtId) {
                                
                                    $qry2 = "UPDATE ctt_series 
                                                SET ser_situation = 'EA', 
                                                    ser_stage = 'R',
                                                    pjtdt_id = $pjtdt_ft_id
                                            WHERE ser_id = $ser_id;";
                                    $this->db->query($qry2);

                                    /* $qry2 = "UPDATE ctt_products 
                                        SET 
                                            prd_reserved = prd_reserved + 1
                                        WHERE prd_id = $prodId;";
                                    $this->db->query($qry2); */
                        
                                    $updtQry = "UPDATE ctt_projects_detail SET sttd_id = 1 where pjtdt_id=$pjtdt_ft_id";
                                    $this->db->query($updtQry);
            
                                    
                                }
                            }
                            
                        }
                        $qry3 = "DELETE FROM ctt_projects_detail WHERE pjtdt_id = $pjtdtId;";
                        $this->db->query($qry3);
                    
                        $qry4 = "DELETE FROM ctt_projects_periods WHERE pjtdt_id = $pjtdtId;";
                        $this->db->query($qry4);
                    }else{
                    
                        $qry2 = "UPDATE ctt_series 
                            SET ser_situation = 'D', 
                                    ser_stage = 'D', 
                                    pjtdt_id = 0 
                            WHERE pjtdt_id = $pjtdtId;";
                        $this->db->query($qry2);

                        /* $qry2 = "UPDATE ctt_products 
                                SET 
                                    prd_reserved = prd_reserved - 1
                                WHERE prd_id = $prodId;";
                            $this->db->query($qry2); */
                    }
                }

            }


        }
        return '1';
    }
    public function KillQuantityDetailPackage($params)
    {
        $pjtvrId = $this->db->real_escape_string($params['pjetId']);
        $prodId = $this->db->real_escape_string($params['prodId']);

        $qry1 = "WITH elements AS (
            SELECT dt.pjtdt_id, dt.ser_id, dt.pjtdt_prod_sku, dt.prd_id, dt.pjtvr_id, dt.sttd_id,pd.prd_type_asigned,
                ROW_NUMBER() OVER (partition by dt.prd_id ORDER BY dt.pjtdt_prod_sku DESC) AS reng
                FROM ctt_projects_detail AS dt
              LEFT join ctt_series as sr on sr.ser_id = dt.ser_id 
                LEFT JOIN ctt_products AS pd ON pd.prd_id = dt.prd_id
                WHERE pjtvr_id = $pjtvrId AND dt.prd_id =  $prodId AND (sr.prd_id_acc = 0 OR ISNULL(sr.prd_id_acc)) AND (pd.prd_type_asigned = 'PV' OR pd.prd_type_asigned = 'PF' OR pd.prd_type_asigned = 'PI') ORDER BY dt.pjtdt_prod_sku)
      SELECT pjtdt_id,  ser_id, reng, pjtdt_prod_sku, prd_id,prd_type_asigned FROM elements WHERE reng = 1;";
        $result =  $this->db->query($qry1);
 
        while($row = $result->fetch_assoc()){
            $pjtdtId = $row["pjtdt_id"];
            $prdId = $row["prd_id"];
            $typeAsigned = $row["prd_type_asigned"];
            $serId = $row["ser_id"];

            // PRIMERO MODIFICAR EL PRODUCTO PRINCIPAL YA SEA FIJO O VIRTUAL
            $qry = "SELECT pjd.pjtdt_id, pjd.ser_id FROM ctt_projects_detail AS pjd
            INNER JOIN ctt_projects_periods AS ppd ON ppd.pjtdt_id = pjd.pjtdt_id
            WHERE pjd.sttd_id = 3 AND pjd.ser_id = (SELECT pdt.ser_id FROM ctt_projects_detail AS pdt
            INNER JOIN ctt_series AS sr ON sr.ser_id = pdt.ser_id
            INNER JOIN ctt_products AS pd ON pd.prd_id = sr.prd_id
            WHERE pdt.pjtdt_id = $pjtdtId AND prd_id_acc = 0 AND (pd.prd_type_asigned = 'PV' OR pd.prd_type_asigned = 'PF') LIMIT 1) ORDER BY ppd.pjtpd_day_start ASC LIMIT 1";
        
            $result =  $this->db->query($qry);
            $pjtdt = $result->fetch_object();

            if ($pjtdt != null ) {
                $pjtdt_ft_id = $pjtdt->pjtdt_id; 
                $ser_id = $pjtdt->ser_id; 
                $qry = "SELECT sr.pjtdt_id FROM ctt_series AS sr WHERE sr.ser_id = $ser_id";
        
                $result =  $this->db->query($qry);
                $series = $result->fetch_object();

                

                if ($series != null) {
                    $pjtdt_id = $series->pjtdt_id;
                    if ($pjtdt_id == $pjtdtId) {
                    
                        $qry2 = "UPDATE ctt_series 
                                    SET ser_situation = 'EA', 
                                        ser_stage = 'R',
                                        pjtdt_id = $pjtdt_ft_id
                                WHERE ser_id = $ser_id;";
                        $this->db->query($qry2);

                        /* $qry2 = "UPDATE ctt_products 
                                SET 
                                    prd_reserved = prd_reserved + 1
                                WHERE prd_id = $prodId;";
                            $this->db->query($qry2); */
            
                        $updtQry = "UPDATE ctt_projects_detail SET sttd_id = 1 where pjtdt_id=$pjtdt_ft_id";
                        $this->db->query($updtQry);

                        
                    }
                }
                
            }else{
                
                $qry2 = "UPDATE ctt_series 
                    SET ser_situation = 'D', 
                            ser_stage = 'D', 
                            pjtdt_id = 0 
                    WHERE pjtdt_id = $pjtdtId;";
                $this->db->query($qry2);

                /* $qry2 = "UPDATE ctt_products 
                    SET 
                        prd_reserved = prd_reserved - 1
                    WHERE prd_id = $prodId;";
                $this->db->query($qry2); */
            }
            $qry3 = "DELETE FROM ctt_projects_detail WHERE pjtdt_id = $pjtdtId;";
            $this->db->query($qry3);
        
            $qry4 = "DELETE FROM ctt_projects_periods WHERE pjtdt_id = $pjtdtId;";
            $this->db->query($qry4);


            // VERIFICAMOS DE QUE TIPO ES EL PRODUCTO, PARA VER QUE TIPO DE ACCESORIOS VA A BUSCAR

            if ($typeAsigned == 'PF') {
                if ($serId != 0) {
                    $qry = "SELECT pd.* FROM ctt_projects_detail AS pd
                    INNER JOIN ctt_series AS sr ON sr.ser_id = pd.ser_id
                    WHERE sr.prd_id_acc = $serId";
            
                    $seriesAcc =  $this->db->query($qry);
                    while ($row = $seriesAcc->fetch_assoc()) {
                        $ptdtId = $row['pjtdt_id'];
                        $ser_id = $row['ser_id'];
        
                        $qry = "SELECT pjd.pjtdt_id, pjd.ser_id FROM ctt_projects_detail AS pjd
                        INNER JOIN ctt_projects_periods AS ppd ON ppd.pjtdt_id = pjd.pjtdt_id
                        WHERE pjd.sttd_id = 3 AND pjd.ser_id = (SELECT pdt.ser_id FROM ctt_projects_detail AS pdt
                        INNER JOIN ctt_series AS sr ON sr.ser_id = pdt.ser_id
                        WHERE pdt.pjtdt_id = $ptdtId AND prd_id_acc > 0 LIMIT 1) ORDER BY ppd.pjtpd_day_start ASC LIMIT 1";
                        
                    
                        $result =  $this->db->query($qry);
                        $pjtdtR = $result->fetch_object();
                        if ($pjtdtR != null ) {
                            $pjtdtftid = $pjtdtR->pjtdt_id; 
                            $serId = $pjtdtR->ser_id; 
                            $qry = "SELECT sr.pjtdt_id FROM ctt_series AS sr WHERE sr.ser_id = $serId";
                            $result =  $this->db->query($qry);
                            $srs = $result->fetch_object();
                            if ($srs != null) {
                                $pdt_id = $srs->pjtdt_id;
                                if ($pdt_id == $ptdtId) {
                                    $qry2 = "UPDATE ctt_series 
                                                SET ser_situation = 'EA', 
                                                    ser_stage = 'R',
                                                    pjtdt_id = $pjtdtftid
                                            WHERE ser_id = $serId;";
                                    $this->db->query($qry2);

                                    /* $qry2 = "UPDATE ctt_products 
                                        SET 
                                            prd_reserved = prd_reserved + 1
                                        WHERE prd_id = $prodId;";
                                    $this->db->query($qry2); */
                        
                                    $updtQry = "UPDATE ctt_projects_detail SET sttd_id = 1 where pjtdt_id=$pjtdtftid";
                                    $this->db->query($updtQry);
                                }
                            }
                        }else{
                            $qry2 = "UPDATE ctt_series 
                                SET ser_situation = 'D', 
                                        ser_stage = 'D', 
                                        pjtdt_id = 0 
                                WHERE pjtdt_id = $ptdtId;";
                            $this->db->query($qry2);

                            /* $qry2 = "UPDATE ctt_products 
                                SET 
                                    prd_reserved = prd_reserved - 1
                                WHERE prd_id = $prodId;";
                            $this->db->query($qry2); */
                        }
                        $qry3 = "DELETE FROM ctt_projects_detail WHERE pjtdt_id = $ptdtId;";
                        $this->db->query($qry3);
                    
                        $qry4 = "DELETE FROM ctt_projects_periods WHERE pjtdt_id = $ptdtId;";
                        $this->db->query($qry4);
                    } 
                }
            }elseif ($typeAsigned == 'PV') {
                $qryProds = "SELECT * FROM ctt_products_packages WHERE prd_parent = $prdId";
                $prods = $this->db->query($qryProds);

                while($row = $prods->fetch_assoc()){
                    $prdId = $row["prd_id"];
                    $pck_quantity = $row["pck_quantity"];
                    
                    for ($i=0; $i < $pck_quantity; $i++) { 
                        $query ="SELECT * FROM ctt_projects_detail AS pdt WHERE pdt.prd_id = $prdId AND pjtvr_id = $pjtvrId ORDER BY pdt.pjtdt_prod_sku LIMIT 1;";
                        $detailPakc= $this->db->query($query);
                        $series = $detailPakc->fetch_object();
    
                        if ($series != null) {
                            $pjtdtId = $series->pjtdt_id; 
    
                            $qry = "SELECT pjd.pjtdt_id, pjd.ser_id FROM ctt_projects_detail AS pjd
                            INNER JOIN ctt_projects_periods AS ppd ON ppd.pjtdt_id = pjd.pjtdt_id
                            WHERE pjd.sttd_id = 3 AND pjd.ser_id = (SELECT pdt.ser_id FROM ctt_projects_detail AS pdt
                            INNER JOIN ctt_series AS sr ON sr.ser_id = pdt.ser_id
                            WHERE pdt.pjtdt_id = $pjtdtId AND prd_id_acc = 0 LIMIT 1) ORDER BY ppd.pjtpd_day_start ASC LIMIT 1";
                            
                        
                            $result =  $this->db->query($qry);
                            $pjtdt = $result->fetch_object();
                
                            if ($pjtdt != null ) {
                                $pjtdt_ft_id = $pjtdt->pjtdt_id; 
                                $ser_id = $pjtdt->ser_id; 
                                $qry = "SELECT sr.pjtdt_id FROM ctt_series AS sr WHERE sr.ser_id = $ser_id";
                        
                                $result =  $this->db->query($qry);
                                $series = $result->fetch_object();
                
                                
                
                                if ($series != null) {
                                    $pjtdt_id = $series->pjtdt_id;
                                    if ($pjtdt_id == $pjtdtId) {
                                    
                                        $qry2 = "UPDATE ctt_series 
                                                    SET ser_situation = 'EA', 
                                                        ser_stage = 'R',
                                                        pjtdt_id = $pjtdt_ft_id
                                                WHERE ser_id = $ser_id;";
                                        $this->db->query($qry2);
                            
                                        $updtQry = "UPDATE ctt_projects_detail SET sttd_id = 1 where pjtdt_id=$pjtdt_ft_id";
                                        $this->db->query($updtQry);
                                        /* $qry2 = "UPDATE ctt_products 
                                            SET 
                                                prd_reserved = prd_reserved + 1
                                            WHERE prd_id = $prodId;";
                                        $this->db->query($qry2); */
                
                                        
                                    }
                                }
                                
                            }
                            $qry3 = "DELETE FROM ctt_projects_detail WHERE pjtdt_id = $pjtdtId;";
                            $this->db->query($qry3);
                        
                            $qry4 = "DELETE FROM ctt_projects_periods WHERE pjtdt_id = $pjtdtId;";
                            $this->db->query($qry4);
                        }else{
                        
                            $qry2 = "UPDATE ctt_series 
                                SET ser_situation = 'D', 
                                        ser_stage = 'D', 
                                        pjtdt_id = 0 
                                WHERE pjtdt_id = $pjtdtId;";
                            $this->db->query($qry2);

                            /* $qry2 = "UPDATE ctt_products 
                                SET 
                                    prd_reserved = prd_reserved - 1
                                WHERE prd_id = $prodId;";
                            $this->db->query($qry2); */
                        }
                    }
                }
            }
            
        }
        return $prodId;
    }
    /* public function KillQuantityDetailPackage($params)
    {
        $pjtvrId = $this->db->real_escape_string($params['pjetId']);

        $qry1 = "WITH elements AS (
                        SELECT dt.*,
                            ROW_NUMBER() OVER (partition by dt.prd_id ORDER BY dt.pjtdt_prod_sku DESC) AS reng
                            FROM ctt_projects_detail AS dt
		                    LEFT join ctt_series as sr on sr.ser_id = dt.ser_id 
                            WHERE pjtvr_id = $pjtvrId ORDER BY dt.pjtdt_prod_sku)
                SELECT pjtdt_id FROM elements WHERE reng =1;";
        $result =  $this->db->query($qry1);
 
        while($row = $result->fetch_assoc()){
            $pjtdtId = $row["pjtdt_id"];
            
            $qry = "SELECT pjd.pjtdt_id, pjd.ser_id FROM ctt_projects_detail AS pjd
                INNER JOIN ctt_projects_periods AS ppd ON ppd.pjtdt_id = pjd.pjtdt_id
                WHERE pjd.sttd_id = 3 AND pjd.ser_id = (SELECT ser_id FROM ctt_projects_detail AS pdt
                WHERE pdt.pjtdt_id = $pjtdtId LIMIT 1) ORDER BY ppd.pjtpd_day_start ASC LIMIT 1";
        
            $result =  $this->db->query($qry);
            $pjtdt = $result->fetch_object();
            
            if ($pjtdt != null ) {
                $pjtdt_ft_id = $pjtdt->pjtdt_id; 
                $ser_id = $pjtdt->ser_id; 
                $qry = "SELECT sr.pjtdt_id FROM ctt_series AS sr WHERE sr.ser_id = $ser_id";
        
                $result =  $this->db->query($qry);
                $series = $result->fetch_object();
                if ($series != null) {
                    $pjtdt_id = $series->pjtdt_id;
                    if ($pjtdt_id == $pjtdtId) {
                    
                        $qry2 = "UPDATE ctt_series 
                                    SET ser_situation = 'EA', 
                                        ser_stage = 'R',
                                        pjtdt_id = $pjtdt_ft_id
                                WHERE ser_id = $ser_id;";
                        $this->db->query($qry2);
            
                        $updtQry = "UPDATE ctt_projects_detail SET sttd_id = 1 where pjtdt_id=$pjtdt_ft_id";
                        $this->db->query($updtQry);
                    }
                }
                
            }else{
                $qry2 = "UPDATE ctt_series 
                    SET ser_situation = 'D', 
                            ser_stage = 'D', 
                            pjtdt_id = 0 
                    WHERE pjtdt_id = $pjtdtId;";
                $this->db->query($qry2);
            }
            $qry3 = "DELETE FROM ctt_projects_detail WHERE pjtdt_id = $pjtdtId;";
            $this->db->query($qry3);
        
            $qry4 = "DELETE FROM ctt_projects_periods WHERE pjtdt_id = $pjtdtId;";
            $this->db->query($qry4);
        }
        return '1';
    } */

/** ====== Asigna las series y el detalle del producto al detalle del proyecto  ==============  */
    public function SettingSeries($params)
    {
        $prodId   = $this->db->real_escape_string($params['prodId']);
        $dtinic   = $this->db->real_escape_string($params['dtinic']);
        $dtfinl   = $this->db->real_escape_string($params['dtfinl']);
        $pjetId   = $this->db->real_escape_string($params['pjetId']);
        $detlId   = $this->db->real_escape_string($params['detlId']);
        $type_asigned   = $this->db->real_escape_string($params['type_asigned']);
        // Busca serie que se encuentre disponible y obtiene el id
        $qry1 = "SELECT ser_id, ser_sku, (ser_reserve_count + 1) as ser_reserve_count 
                 FROM ctt_series WHERE prd_id = $prodId 
                 AND (pjtdt_id = 0 OR ISNULL(pjtdt_id)) AND prd_id_acc = 0
                 ORDER BY ser_reserve_count asc LIMIT 1;";
        $result =  $this->db->query($qry1);
        
        $series = $result->fetch_object();
        if ($series != null){
            $serie  = $series->ser_id; 
            $sersku  = $series->ser_sku; 
            $ser_reserve_count  = $series->ser_reserve_count;

            // Agrega el registro en el detalle con los datos de la serie
            $qry3 = "INSERT INTO ctt_projects_detail (
                pjtdt_belongs, pjtdt_prod_sku, ser_id, prd_id, pjtvr_id, sttd_id, prd_type_asigned) 
                VALUES ('$detlId', '$sersku', '$serie',  '$prodId',  '$pjetId', 1, '$type_asigned'
                ); ";
            $this->db->query($qry3);
            $pjtdtId = $this->db->insert_id;

            // Si la encuentra coloca la etapa y el estatus a la serie
            $qry2 = "UPDATE ctt_series 
                        SET 
                            ser_situation = 'EA', ser_stage = 'R',
                            ser_reserve_count = $ser_reserve_count,
                            pjtdt_id = '$pjtdtId'
                        WHERE ser_id = $serie;";
            $this->db->query($qry2);

            /* $qry2 = "UPDATE ctt_products 
                SET 
                    prd_reserved = prd_reserved + 1
                WHERE prd_id = $prodId;";
            $this->db->query($qry2); */

        } else {
            
            $qry = "SELECT ser.ser_id serId, ser.ser_sku serSku 
            FROM ctt_series AS ser
            WHERE ser.prd_id = '$prodId' AND prd_id_acc = 0 AND NOT EXISTS (SELECT sr.ser_id serId
            FROM ctt_series AS sr
            INNER JOIN ctt_projects_detail AS pd ON pd.ser_id = sr.ser_id
            INNER JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id = pd.pjtdt_id
            WHERE sr.ser_id = ser.ser_id AND pd.sttd_id != 4 AND (pjp.pjtpd_day_start BETWEEN '$dtinic' AND '$dtfinl' 
            OR pjp.pjtpd_day_end BETWEEN '$dtinic' AND '$dtfinl'  
            OR '$dtinic' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end
            OR '$dtfinl' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end));";

            $result =  $this->db->query($qry);
            
            $serie_futura = $result->fetch_object();
            
            if ($serie_futura != null){
                $sersku  = $serie_futura->serSku;
                $serie = $serie_futura->serId;

                // Si la fecha de reserva de una serie asignada es mayor a la de la serie que se planea colocar a futuro
                // que esta tome su lugar y la serie anterior que se coloque como serie a futuro
                // Esto en caso de que se reserve antes una serie pero despues en otro proyecto las fechas de reserva sean menores a la anterior
                $qry3="SELECT sr.ser_id serId, pjp.pjtpd_day_start, pjp.pjtpd_day_end, sr.pjtdt_id
                FROM ctt_series AS sr
                INNER JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id = sr.pjtdt_id
                WHERE sr.ser_id = $serie AND pjp.pjtpd_day_start > '$dtfinl' AND prd_id_acc = 0 LIMIT 1;";
                $result1 =  $this->db->query($qry3);
                            
                $serie_actual = $result1->fetch_object();
                if ($serie_actual != null ) {
                    $pjtdt_id  = $serie_actual->pjtdt_id;

                    $qry4 ="UPDATE ctt_projects_detail SET sttd_id = 3 where pjtdt_id = $pjtdt_id";
                    $this->db->query($qry4);


                    $qry2 = "INSERT INTO ctt_projects_detail (
                        pjtdt_belongs, pjtdt_prod_sku, ser_id, prd_id, pjtvr_id, sttd_id, prd_type_asigned) 
                        VALUES ('$detlId', '$sersku', '$serie',  '$prodId',  '$pjetId', 1, '$type_asigned'
                        ); ";
                    $this->db->query($qry2);
                    $pjtdtId = $this->db->insert_id;

                    $qry4 ="UPDATE ctt_series 
                        SET 
                            pjtdt_id = $pjtdtId
                        WHERE ser_id = '$serie'";
                    $this->db->query($qry4);

                    /* $qry2 = "UPDATE ctt_products 
                        SET 
                            prd_reserved = prd_reserved + 1
                        WHERE prd_id = $prodId;";
                    $this->db->query($qry2); */


                }else{
                    $qry2 = "INSERT INTO ctt_projects_detail (
                        pjtdt_belongs, pjtdt_prod_sku, ser_id, prd_id, pjtvr_id, sttd_id, prd_type_asigned) 
                        VALUES ('$detlId', '$sersku', '$serie',  '$prodId',  '$pjetId', 3, '$type_asigned'
                        ); ";
                    $this->db->query($qry2);
                    $pjtdtId = $this->db->insert_id;
                }

            }else{
                $serie  = null; 
                $sersku  = 'Pendiente' ;
                $qry2 = "INSERT INTO ctt_projects_detail (
                    pjtdt_belongs, pjtdt_prod_sku, ser_id, prd_id, pjtvr_id, sttd_id, prd_type_asigned) 
                    VALUES ('$detlId', '$sersku', '$serie',  '$prodId',  '$pjetId', 2, '$type_asigned'
                    ); ";
                $this->db->query($qry2);
                $pjtdtId = $this->db->insert_id;
            }

           
        }
        // Agrega los periodos desiganados a la serie 
        $qry5 = "INSERT INTO ctt_projects_periods 
                    (pjtpd_day_start, pjtpd_day_end, pjtdt_id, pjtdt_belongs) 
                VALUES ('$dtinic', '$dtfinl', '$pjtdtId', '$detlId')";
        $this->db->query($qry5);

        return $serie;
    }
    
    public function getDetails($params){
        $pjtvr = $this->db->real_escape_string($params['prodId']);

    }

    public function getAccesorios($serie){
        $qry = "SELECT *, (ser_reserve_count + 1) as ser_reserve_count  
                FROM ctt_series WHERE prd_id_acc = $serie";  // solo trae un registro

        return $this->db->query($qry);
    }
    public function SettingSeriesAcce($params)
    {
        $pjetId   = $this->db->real_escape_string($params['pjetId']);  // este es el valor pjtvr_id
        $prodId   = $this->db->real_escape_string($params['prodId']);
        $dtinic   = $this->db->real_escape_string($params['dtinic']);
        $dtfinl   = $this->db->real_escape_string($params['dtfinl']);
        $detlId   = $this->db->real_escape_string($params['detlId']);
        $serId   = $this->db->real_escape_string($params['serId']);

        $serieAcc = "SELECT ser_id, ser_sku, (ser_reserve_count + 1) as ser_reserve_count  
        FROM ctt_series WHERE pjtdt_id = 0 AND ser_id = $serId LIMIT 1";
        
        $result =  $this->db->query($serieAcc);
            
        $series = $result->fetch_object();

        if ($series != null) {
            $serId  = $series->ser_id; 
            $sersku  = $series->ser_sku;
            $ser_reserve_count  = $series->ser_reserve_count; 
            $qry2 = "INSERT INTO ctt_projects_detail (
                pjtdt_belongs, pjtdt_prod_sku, ser_id, prd_id, pjtvr_id, sttd_id, prd_type_asigned) 
                VALUES ('$detlId', '$sersku', '$serId',  '$prodId',  '$pjetId', 1, 'AF'
                ); ";
            $this->db->query($qry2);
            $pjtdtId = $this->db->insert_id;
            
            $qry1 = "UPDATE ctt_series SET ser_situation = 'EA', ser_stage = 'R',
                        ser_reserve_count = $ser_reserve_count,
                        pjtdt_id = '$pjtdtId'
                    WHERE ser_id = $serId;"; 
            $this->db->query($qry1);
            /* $qry2 = "UPDATE ctt_products 
                        SET 
                            prd_reserved = prd_reserved + 1
                        WHERE prd_id = $prodId;";
                    $this->db->query($qry2); */

        }else{
            $qry = "SELECT ser.ser_id serId, ser.ser_sku serSku 
                    FROM ctt_series AS ser
                    WHERE ser.ser_id = $serId AND NOT EXISTS (SELECT sr.ser_id serId
                    FROM ctt_series AS sr
                    INNER JOIN ctt_projects_detail AS pd ON pd.ser_id = sr.ser_id
                    INNER JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id = pd.pjtdt_id
                    WHERE sr.ser_id = ser.ser_id AND pd.sttd_id != 4 AND (pjp.pjtpd_day_start BETWEEN '$dtinic' AND '$dtfinl' 
                    OR pjp.pjtpd_day_end BETWEEN '$dtinic' AND '$dtfinl'  
                    OR '$dtinic' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end
                    OR '$dtfinl' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end))";  // solo trae un registro

            $result =  $this->db->query($qry);
            $serie_futura = $result->fetch_object();
            if ($serie_futura != null){
                $sersku  = $serie_futura->serSku;
                $serId = $serie_futura->serId;

                /* $qry2 = "INSERT INTO ctt_projects_detail (
                    pjtdt_belongs, pjtdt_prod_sku, ser_id, prd_id, pjtvr_id, sttd_id) 
                    VALUES ('$detlId', '$sersku', '$serie',  '$prodId',  '$pjetId', 3
                    ); "; */
                    $qry3="SELECT sr.ser_id serId, pjp.pjtpd_day_start, pjp.pjtpd_day_end, sr.pjtdt_id
                    FROM ctt_series AS sr
                    INNER JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id = sr.pjtdt_id
                    WHERE sr.ser_id = $serId AND pjp.pjtpd_day_start > '$dtfinl' LIMIT 1;";
                    $result1 =  $this->db->query($qry3);
                                
                    $serie_actual = $result1->fetch_object();
                    if ($serie_actual != null ) {
                        $pjtdt_id  = $serie_actual->pjtdt_id;

                        $qry4 ="UPDATE ctt_projects_detail SET sttd_id = 3 where pjtdt_id = $pjtdt_id";
                        $this->db->query($qry4);


                        $qry2 = "INSERT INTO ctt_projects_detail (
                            pjtdt_belongs, pjtdt_prod_sku, ser_id, prd_id, pjtvr_id, sttd_id, prd_type_asigned) 
                            VALUES ('$detlId', '$sersku', '$serId',  '$prodId',  '$pjetId', 1, 'AF'
                            ); ";
                        $this->db->query($qry2);
                        $pjtdtId = $this->db->insert_id;

                        $qry4 ="UPDATE ctt_series 
                            SET 
                                pjtdt_id = $pjtdtId
                            WHERE ser_id = '$serId'";
                        $this->db->query($qry4);
                        /* $qry2 = "UPDATE ctt_products 
                            SET 
                                prd_reserved = prd_reserved + 1
                            WHERE prd_id = $prodId;";
                        $this->db->query($qry2); */
                    }else{
                        $qry2 = "INSERT INTO ctt_projects_detail (
                            pjtdt_belongs, pjtdt_prod_sku, ser_id, prd_id, pjtvr_id, sttd_id, prd_type_asigned) 
                            VALUES ('$detlId', '$sersku', '$serId',  '$prodId',  '$pjetId', 3, 'AF'
                            ); ";
                        $this->db->query($qry2);
                        $pjtdtId = $this->db->insert_id;
                    }
            }else{
                $serId  = null; 
                $sersku  = 'Pendiente' ;
                $qry2 = "INSERT INTO ctt_projects_detail (
                    pjtdt_belongs, pjtdt_prod_sku, ser_id, prd_id, pjtvr_id, sttd_id, prd_type_asigned) 
                    VALUES ('$detlId', '$sersku', '$serId',  '$prodId',  '$pjetId', 2, 'AF'
                    ); ";
                $this->db->query($qry2);
                $pjtdtId = $this->db->insert_id;
            }
        

        }

        $qry4 = "INSERT INTO ctt_projects_periods 
                    (pjtpd_day_start, pjtpd_day_end, pjtdt_id, pjtdt_belongs ) 
                VALUES ('$dtinic', '$dtfinl', '$pjtdtId', '$detlId');";
        $this->db->query($qry4);

    return  $serId;
    }
    public function GetAccesories($params)
    {
        $prodId   = $this->db->real_escape_string($params['prodId']);
        $serId   = $this->db->real_escape_string($params['serId']);

        $qry = "SELECT ser_id FROM ctt_projects_detail 
                WHERE pjtdt_id = $serId LIMIT 1;";
        $result =  $this->db->query($qry);

        $locserid = $result->fetch_object();
        if ($locserid != null){
            $locser  = $locserid->ser_id; 
            
            $qry1 = "SELECT pr.* 
            FROM ctt_products AS pr
            INNER JOIN ctt_accesories AS ac ON ac.ser_parent = pr.prd_id 
            WHERE ac.prd_parent = $prodId AND ac.prd_id=$locser;";
        }
        return $this->db->query($qry1);
    }

    public function GetProducts($params)
    {
        $prodId        = $this->db->real_escape_string($params);
        $qry = "SELECT pd.*, pk.pck_quantity
                FROM ctt_products_packages AS pk 
                INNER JOIN ctt_products AS pd ON pd.prd_id = pk.prd_id
                WHERE  pk.prd_parent = $prodId;";
        return $this->db->query($qry);

    }

/** ====== Importa proyecto ==================================================================  */
    public function importProject($pjtIdo, $pjtId, $verId)
    {

        $qry3 = "DELETE FROM ctt_projects_mice WHERE pjt_id = $pjtId;";
        $this->db->query($qry3);

        $qry4 = "INSERT INTO ctt_projects_mice (
                    pjtvr_action, pjtvr_prod_sku, pjtvr_prod_name, pjtvr_prod_price, pjtvr_quantity, pjtvr_quantity_ant, pjtvr_days_base, pjtvr_days_base_ant, pjtvr_days_cost, pjtvr_discount_base, pjtvr_discount_insured, 
                    pjtvr_days_trip, pjtvr_discount_trip, pjtvr_days_test, pjtvr_discount_test, pjtvr_insured, pjtvr_prod_level, pjtvr_section, pjtvr_status, pjtvr_order, ver_id, prd_id, pjt_id
                )
                SELECT 'N' AS pjtcn_action, pjtcn_prod_sku, pjtcn_prod_name, pjtcn_prod_price, pjtcn_quantity, pjtcn_quantity AS pjtcn_quantity_ant, pjtcn_days_base, pjtcn_days_base as  pjtcn_days_base_ant, pjtcn_days_cost, pjtcn_discount_base, pjtcn_discount_insured, 
                    pjtcn_days_trip, pjtcn_discount_trip, pjtcn_days_test, pjtcn_discount_test, pjtcn_insured, pjtcn_prod_level, pjtcn_section, pjtcn_status, pjtcn_order, '$verId' as ver_id, prd_id, '$pjtId' as pjt_id 
                FROM ctt_projects_content 
                WHERE pjt_id = $pjtIdo;
                ";
        return $this->db->query($qry4);
    } 

    // Listado los comentarios del proyecto
    public function listChangeProd($params)
        {
            $catsub = $this->db->real_escape_string($params['catsub']);

            $qry = "SELECT prd_id, prd_sku, prd_name, prd_stock-fun_buscarentas(prd_sku) AS stock
                    FROM ctt_products 
                    WHERE substr(prd_sku,1,4)='$catsub' AND prd_type_asigned !='KP'
                    ORDER BY prd_id;";

            return $this->db->query($qry);
        }  
        // Listado de estados de la republica ***Ed
        public function getEdosRepublic($params)
    {
        $prd = $this->db->real_escape_string($params['prm']);
        
        $qry = "SELECT edos_id,edos_name,edos_abrev
                FROM ctt_estados_mex ORDER BY edos_id;";
        return $this->db->query($qry);
    }    
    // Listado de locaciones de estados
    public function ListLocationsEdos($params){
        $pjtId = $this->db->real_escape_string($params['prj_id']);
        $qry = "SELECT * FROM ctt_locacion_estado AS ldo 
                INNER JOIN ctt_estados_mex AS edo ON ldo.edos_id=edo.edos_id WHERE ldo.pjt_id='$pjtId';";
        return $this->db->query($qry);
    } 
    // Guardar locaciones 
    public function SaveLocations($params){
        $loc        = $this->db->real_escape_string($params['loc']);
        $edo        = $this->db->real_escape_string($params['edo']);
        $prjId        = $this->db->real_escape_string($params['prjId']);

        $qry1 = "INSERT INTO ctt_locacion_estado(
                    lce_location,  edos_id, pjt_id)
                VALUES('$loc','$edo','$prjId');";
        $this->db->query($qry1);

        return $loc;
    }  

    // Listado los comentarios del proyecto
    public function updateNewProdChg($params)
    {
        $skuold   = $this->db->real_escape_string($params['skuold']);
        $skunew   = $this->db->real_escape_string($params['skunew']);

        // Busca serie que se encuentre disponible y obtiene el id
        $qry1 = "SELECT pjtdt_id FROM ctt_projects_detail
                WHERE pjtdt_prod_sku LIKE '$skuold%'; ";

        $resultid =  $this->db->query($qry1);
        $iddetail = $resultid->fetch_object();
            if ($iddetail != null){
                $detid  = $iddetail->pjtdt_id; 
            } 

        $qry2 = "SELECT ser_id, ser_sku, prd_id FROM ctt_series 
                WHERE SER_SKU LIKE '$skunew%' AND ser_situation='D'
                AND length(ser_sku)=10 ORDER BY RAND() LIMIT 1; ";

        $result =  $this->db->query($qry2);
        $detseries = $result->fetch_object();
            if ($detseries != null){
                $serie  = $detseries->ser_id; 
                $sersku  = $detseries->ser_sku;
                $serprdid  = $detseries->prd_id; 
            } 
        
        // Agrega los periodos designados a la serie 
        $qry3 = "UPDATE ctt_projects_detail 
                    SET pjtdt_prod_sku = '$sersku', ser_id = '$serie', prd_id = '$serprdid'
                WHERE pjtdt_id = '$detid'; ";
        $this->db->query($qry3);
        
        $joinval = $skuold . ' | ' . $skunew . ' | ' . $detid . ' | ' . $serie . ' | ' . $sersku . ' | ' . $serprdid ;
        return  $joinval;
    }    

    public function listReordering($params)
    {
        // $pjtId      = $this->db->real_escape_string($params['pjtId']);
        $verId = $this->db->real_escape_string($params);

        $qry = "SELECT pcn.pjtvr_id, pcn.pjtcn_id, pcn.pjtcn_prod_sku,pcn.pjtcn_section,pcn.pjtcn_order, sb.sbc_order_print
                FROM ctt_projects_content AS pcn 
                INNER JOIN ctt_subcategories AS sb 
                    ON sb.cat_id=substr(pcn.pjtcn_prod_sku,1,2) 
                    AND sb.sbc_code=substr(pcn.pjtcn_prod_sku,3,2)
                LEFT JOIN ctt_category_subcategories AS cs ON cs.sbc_id = sb.sbc_id 
        		LEFT JOIN ctt_category_report AS cr ON cr.crp_id = cs.crp_id 
                WHERE ver_id=$verId 
                GROUP BY pcn.ver_id, pcn.pjtcn_id, pcn.pjtcn_prod_sku,pcn.pjtcn_section,pcn.pjtcn_order
                ORDER BY pcn.pjtcn_section, sb.sbc_order_print, cr.crp_id, cs.cts_id, SUBSTR(pcn.pjtcn_prod_sku,1,4),pjtcn_order;";

        $result =  $this->db->query($qry);
                
        return $this->db->query($qry);
    } 

    public function upReorderingProducts($params)
    {
        $valnew      = $this->db->real_escape_string($params['valnew']);
        $verid      = $this->db->real_escape_string($params['verid']);

        $qry = "UPDATE ctt_projects_version 
                    SET pjtvr_order=$valnew
                WHERE pjtvr_id=$verid;";
    
        return $this->db->query($qry);
    } 
    public function getLocationType(){
        $qry = "SELECT loc_id, loc_type_location
        FROM ctt_location;
        ";
        return $this->db->query($qry);
    } 
    // eliminar locacion
    function DeleteLocation($params){
        $loc_id = $this->db->real_escape_string($params['loc_id']);
        $qry1 = "DELETE FROM ctt_locacion_estado 
                WHERE lce_id = '$loc_id';
                ";
        $this->db->query($qry1);
        return $loc_id;
    }
    public function listProducts3($params)
    {
        $word = $this->db->real_escape_string($params['word']);
        $sbc_id = $this->db->real_escape_string($params['dstr']);
        if ($word == '') {
            $qry = "SELECT * from ctt_vw_list_products2
            WHERE sbc_id = '$sbc_id';";
        }else{
            $qry = "SELECT * from ctt_vw_list_products2
            WHERE (upper(prd_name) LIKE '%$word%' OR upper(prd_sku) LIKE '%$word%') AND sbc_id = '$sbc_id';";
        }
        return $this->db->query($qry);
    } 
    // Listado de categorias
    public function listCategories($params)
    {
        $opc = $this->db->real_escape_string($params['op']);
        
        if ($opc == 1) {
            $qry = "SELECT * FROM ctt_categories 
                WHERE cat_status  = 1 and cat_id <> 30";
        }else{
            $qry = "SELECT * FROM ctt_categories 
            WHERE cat_status  = 1 ";
        }
        return $this->db->query($qry);
    }
    // Listado de subcategoria
    public function listSubCategories($param)
    {
        $catId = $this->db->real_escape_string($param['catId']);
        $qry = "SELECT * FROM ctt_subcategories 
                WHERE sbc_status = 1 AND cat_id=$catId;";
        return $this->db->query($qry);
    }
}

