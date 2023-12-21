<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class WorkInputContentModel extends Model
{
    public function __construct()
    {
      parent::__construct();
    }

    // Listado de proyectos    ****** // 11-10-23
    public function listProjects($params)
    {
        $pjt_id = $this->db->real_escape_string($params['pjt_id']);

        $qry = "SELECT pt.pjttp_name, pj.pjt_name, pj.pjt_number,
        DATE_FORMAT(pj.pjt_date_start,'%d/%m/%Y') AS pjt_date_start,
        DATE_FORMAT(pj.pjt_date_end,'%d/%m/%Y') AS pjt_date_end,
        DATE_FORMAT(pj.pjt_date_project,'%d/%m/%Y %H:%i ') AS pjt_date_project,
        pj.pjt_location, cus.cus_name, pj.pjt_id, free.free_id, wap.emp_id, wap.emp_fullname
        FROM ctt_projects AS pj 
        LEFT JOIN ctt_customers_owner AS cuw ON cuw.cuo_id=pj.cuo_id
        LEFT JOIN ctt_customers AS cus ON cus.cus_id=cuw.cus_id
        LEFT JOIN ctt_location AS lo ON lo.loc_id = pj.loc_id
        LEFT JOIN ctt_projects_type As pt ON pt.pjttp_id = pj.pjttp_id
        LEFT JOIN ctt_assign_proyect AS asp ON asp.pjt_id= pj.pjt_id
        LEFT JOIN ctt_freelances AS free ON free.free_id = asp.free_id
        LEFT JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
        WHERE pj.pjt_id=$pjt_id AND wap.are_id=1 ORDER BY pjt_date_start ASC;";

        return $this->db->query($qry);
    }
    // Listado de freelances x areas
    public function listAnalysts($params)
    {
        $pjt_id = $this->db->real_escape_string($params['pjt_id']);

        $qry = "SELECT wap.whoatd_id, wap.pjt_id, wap.usr_id, wap.emp_id, wap.emp_fullname, wap.are_id, are.are_name
            FROM ctt_projects AS pj 
            LEFT JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
            INNER JOIN ctt_areas AS are ON are.are_id=wap.are_id
            WHERE pj.pjt_id=$pjt_id ORDER BY pjt_date_start ASC;";

        return $this->db->query($qry);
    }
// Listado de Productos de Proyecto asigando // 11-10-23
    public function listDetailProds($params)
    {
        $pjt_id = $this->db->real_escape_string($params['pjt_id']);
        $empid = $this->db->real_escape_string($params['empid']);
        $areid = $this->db->real_escape_string($params['areid']);

        if ($areid == 5){
            $qry = "SELECT prcn.pjtcn_id, prcn.pjtcn_prod_sku, prcn.pjtcn_prod_name, prcn.pjtcn_quantity, 
            prcn.pjtcn_prod_level, prcn.pjt_id, prcn.pjtcn_status, prcn.pjtcn_order, 
             case 
                 when prcn.pjtcn_section=1 then 'Base'
                 when prcn.pjtcn_section=2 then 'Extra'
                 when prcn.pjtcn_section=3 then 'Por dia'
                 else 'Subarrendo'
                 END AS section, 
                 case 
					  when prcn.pjtcn_prod_level='k' then 
					  CASE WHEN(SELECT COUNT(*) FROM ctt_series AS ser 
                INNER JOIN ctt_projects_detail AS pjd ON pjd.ser_id=ser.ser_id
                INNER JOIN ctt_projects_content AS pcn ON pcn.pjtvr_id= pjd.pjtvr_id 
                WHERE (ser.ser_stage = 'D' OR ser.ser_situation='M') AND pcn.pjtcn_id=prcn.pjtcn_id) 
                = 
                (SELECT COUNT(*)
                FROM ctt_projects_content AS pcn
                    INNER JOIN ctt_projects_version as pjv ON pcn.pjtvr_id=pjv.pjtvr_id
                    INNER JOIN ctt_projects_detail AS pdt ON pcn.pjtvr_id=pdt.pjtvr_id
                    INNER JOIN ctt_series AS sr ON pdt.ser_id=sr.ser_id
                    LEFT JOIN ctt_products AS prd ON prd.prd_id=pdt.prd_id
                    WHERE pcn.pjtcn_id=prcn.pjtcn_id AND prd.prd_level!='A'
                    ORDER BY pdt.pjtdt_prod_sku) then prcn.pjtcn_quantity
                else'0'
                END 
					  ELSE (SELECT COUNT(*) FROM ctt_series AS ser 
                INNER JOIN ctt_projects_detail AS pjd ON pjd.ser_id=ser.ser_id
                INNER JOIN ctt_projects_content AS pcn ON pcn.pjtvr_id= pjd.pjtvr_id 
                LEFT JOIN ctt_products AS prd ON prd.prd_id=ser.prd_id
                WHERE (ser.ser_stage = 'D' OR ser.ser_situation='M') AND pcn.pjtcn_id=prcn.pjtcn_id AND prd.prd_level!='A')
                END AS cant_ser
            FROM ctt_projects_content AS prcn
            WHERE prcn.pjt_id=$pjt_id ORDER BY prcn.pjtcn_section, prcn.pjtcn_prod_sku ASC;";
        }
        else{
            $qry = "SELECT pjc.pjtcn_id, pjc.pjtcn_prod_sku, pjc.pjtcn_prod_name, pjc.pjtcn_quantity, 
            pjc.pjtcn_prod_level, pjc.pjt_id, pjc.pjtcn_status, pjc.pjtcn_order, SUBSTR(pjc.pjtcn_prod_sku,1,2), 
            case 
                 when pjc.pjtcn_section=1 then 'Base'
                 when pjc.pjtcn_section=2 then 'Extra'
                 when pjc.pjtcn_section=3 then 'Por dia'
                 else 'Subarrendo'
                 END AS section, case 
					  when pjc.pjtcn_prod_level='k' then 
					  CASE WHEN(SELECT COUNT(*) FROM ctt_series AS ser 
                INNER JOIN ctt_projects_detail AS pjd ON pjd.ser_id=ser.ser_id
                INNER JOIN ctt_projects_content AS pcn ON pcn.pjtvr_id= pjd.pjtvr_id 
                WHERE (ser.ser_stage = 'D' OR ser.ser_situation='M') AND pcn.pjtcn_id=pjc.pjtcn_id) 
                = 
                (SELECT COUNT(*)
                FROM ctt_projects_content AS pcn
                    INNER JOIN ctt_projects_version as pjv ON pcn.pjtvr_id=pjv.pjtvr_id
                    INNER JOIN ctt_projects_detail AS pdt ON pcn.pjtvr_id=pdt.pjtvr_id
                    INNER JOIN ctt_series AS sr ON pdt.ser_id=sr.ser_id
                    LEFT JOIN ctt_products AS prd ON prd.prd_id=pdt.prd_id
                    WHERE pcn.pjtcn_id=pjc.pjtcn_id AND prd.prd_level!='A'
                    ORDER BY pdt.pjtdt_prod_sku) then pjc.pjtcn_quantity
                else'0'
                END 
                ELSE 
                (SELECT COUNT(*) FROM ctt_series AS ser 
                INNER JOIN ctt_projects_detail AS pjd ON pjd.ser_id=ser.ser_id
                INNER JOIN ctt_projects_content AS pcn ON pcn.pjtvr_id= pjd.pjtvr_id 
                LEFT JOIN ctt_products AS prd ON prd.prd_id=ser.prd_id
                WHERE (ser.ser_stage = 'D' OR ser.ser_situation='M') AND pcn.pjtcn_id=pjc.pjtcn_id AND prd.prd_level!='A')
                END AS cant_ser
            FROM ctt_projects_content AS pjc 
            INNER JOIN ctt_categories AS cat ON lpad(cat.cat_id,2,'0')=SUBSTR(pjc.pjtcn_prod_sku,1,2)
            INNER JOIN ctt_employees AS em ON em.are_id=cat.are_id
            WHERE pjc.pjt_id=$pjt_id AND em.emp_id=$empid
            ORDER BY pjc.pjtcn_section, pjc.pjtcn_prod_sku ASC;";
        }
        return $this->db->query($qry);
    }

    public function listFreelances($params)
    {
        $pjt_id = $this->db->real_escape_string($params['pjt_id']);

        $qry = "SELECT fr.free_id,fr.free_name,ar.are_name FROM ctt_freelances AS fr
                INNER JOIN ctt_areas AS ar ON ar.are_id=fr.free_area_id
                INNER JOIN ctt_assign_proyect AS ap ON ap.free_id=fr.free_id
                WHERE ap.pjt_id=$pjt_id 
                ORDER BY are_name";

        return $this->db->query($qry);
    }

    public function listSeries($params)
   {
        $pjtcnid = $this->db->real_escape_string($params['pjtcnid']);
       
        $qry = "SELECT pdt.pjtdt_id, pdt.pjtdt_prod_sku, prd.prd_name, prd.prd_level, prd.prd_status, pdt.ser_id, pdt.pjtvr_id, 
                sr.ser_sku, sr.ser_serial_number, sr.ser_situation, sr.ser_stage
                FROM ctt_projects_content AS pcn
                INNER JOIN ctt_projects_version as pjv ON pcn.pjtvr_id=pjv.pjtvr_id
                INNER JOIN ctt_projects_detail AS pdt ON pcn.pjtvr_id=pdt.pjtvr_id
                INNER JOIN ctt_series AS sr ON pdt.ser_id=sr.ser_id
                LEFT JOIN ctt_products AS prd ON prd.prd_id=pdt.prd_id
                WHERE pcn.pjtcn_id=$pjtcnid AND prd.prd_level!='A' 
                ORDER BY pdt.pjtdt_prod_sku;";

       return $this->db->query($qry);
   }

   public function listSeriesFree($params)
   {
       $ser_id = $this->db->real_escape_string($params['serid']);
       $serorg = $this->db->real_escape_string($params['serorg']);

        $qry = "SELECT '$serorg' as id_orig, ser_id, ser_sku, ser_serial_number, 
                ser_situation, ser_stage, pr.prd_name, pr.prd_sku
                FROM ctt_series AS sr
                INNER JOIN ctt_products as pr on sr.prd_id = pr.prd_id
                WHERE sr.ser_sku LIKE '$ser_id%' and sr.ser_situation='D' and sr.ser_status=1;";
            
       return $this->db->query($qry);
   }

   // Listado de Motivos para mantenimiento
   public function listReason($params)
   {
        $pjtcnid = $this->db->real_escape_string($params['pjtcnid']);
       
        $qry = "SELECT * FROM ctt_project_change_reason;";
        return $this->db->query($qry);
   }

    // check de Productos
    public function checkSeries($params)
    {
        $serId = $this->db->real_escape_string($params['serId']);

        $updt = "UPDATE ctt_series 
                SET ser_situation='D', ser_stage = 'D', pjtdt_id=0
                WHERE ser_id = $serId;";

         $this->db->query($updt);
         return $serId;
    }
    // listar los datos de las series que coinciden
    public function getSeries($params){
        $serSku = $this->db->real_escape_string($params['serSku']);
        $prjid = $this->db->real_escape_string($params['prjid']);
        $qry = "SELECT * FROM ctt_series AS sr 
        INNER JOIN ctt_products AS pd ON pd.prd_id = sr.prd_id
        INNER JOIN ctt_projects_detail AS pjd ON pjd.ser_id = sr.ser_id
        INNER JOIN ctt_projects_content AS ct ON ct.pjtvr_id=pjd.pjtvr_id
        WHERE ct.pjt_id=$prjid and pjd.ser_id>0 AND sr.ser_sku like '$serSku%';";

       return $this->db->query($qry);
    }
    public function ActualizaSeries($params){
        $serid		= $this->db->real_escape_string($params['serid']);

        $qry = "UPDATE ctt_series SET ser_situation='D', ser_stage = 'D', pjtdt_id=0
                WHERE ser_id=$serid;";  
        $folio = $this->db->query($qry);
        return $folio;
    }
    public function regMaintenance($params)
    {
        $serId = $this->db->real_escape_string($params['serId']);
        $codstag = $this->db->real_escape_string($params['codstag']);
        $codmot = $this->db->real_escape_string($params['codmot']);
        $prjid = $this->db->real_escape_string($params['prjid']);

        $qryins = "INSERT INTO ctt_products_maintenance 
                                (pmt_date_register,ser_id, pjt_id, pjtcr_id, mts_id) 
                    VALUES (CURRENT_TIMESTAMP, $serId, $prjid, $codmot, 1);";
        $this->db->query($qryins);
        $mainId = $this->db->insert_id;

        $updt = "UPDATE ctt_series 
                    SET ser_situation='M', ser_stage = '$codstag'
                WHERE ser_id = $serId;"; 
        
         $this->db->query($updt);

         return $serId;
        
    }

    // check de Productos
    public function createTblResp($params)
    {
        $prjid = $this->db->real_escape_string($params['prjid']);
        $prjnum = $this->db->real_escape_string($params['prjnum']);

        $updt = "CREATE TABLE cttapp_back_projects.CLOSE_PROYECT_".$prjnum." SELECT 
                    pj.pjt_id, pj.pjt_number, pj.pjt_name, pj.pjt_date_project, pj.pjt_status, 
                    pj.pjt_date_start ,pj.pjt_date_end, pj.pjt_date_last_motion, pj.cuo_id,
                    pjtcn_id, pjtcn_prod_sku, pjtcn_quantity, pjtcn_days_base, pjtcn_days_cost, 
                    pjtcn_discount_base, pjtcn_discount_insured, pjtcn_days_trip, pjtcn_discount_trip, 
                    pjtcn_days_test, pjtcn_discount_test ,pjtcn_insured, pjtcn_prod_level, pjtcn_section, 
                    pjtcn_order, pjc.ver_id, 
                    pjd.pjtdt_id, pjd.pjtdt_prod_sku, pjd.pjtvr_id, 
                    pjp.pjtpd_day_start,pjp.pjtpd_day_end,
                    sr.ser_id, ser_sku, ser_serial_number,ser_situation, ser_stage, 
                    prd.prd_id, prd_name, prd_price, sbc_id 
                FROM ctt_projects_detail AS pjd
                INNER JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id=pjd.pjtdt_id
                INNER JOIN ctt_projects_content AS pjc ON pjc.pjtvr_id=pjd.pjtvr_id
                INNER JOIN ctt_projects AS pj ON pj.pjt_id=pjc.pjt_id
                INNER JOIN ctt_series AS sr ON sr.ser_id=pjd.ser_id
                INNER JOIN ctt_products AS prd ON prd.prd_id=sr.prd_id
                WHERE pjc.pjt_id = $prjid;";

         $this->db->query($updt);
         return $prjid;
        
    }

    public function GetInProject($params)
    {
        $pjtid = $this->db->real_escape_string($params['pjtid']);
        
        $updt = "UPDATE ctt_projects SET pjt_status = '9' 
                WHERE pjt_id = '$pjtid' ";
         return $this->db->query($updt);
        
    }
    // Agrega Comentario // 11-10-23
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
    // Listado los comentarios del proyecto
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
    public function listLocations($params)
    {
        $pjt_id = $this->db->real_escape_string($params['pjt_id']);

        $qry = "SELECT (case when lce.pjt_id
                    then lce.lce_location
                    ELSE pj.pjt_location END) AS locations
                FROM ctt_projects AS pj
                LEFT JOIN ctt_locacion_estado AS lce ON lce.pjt_id = pj.pjt_id
                WHERE pj.pjt_id=$pjt_id ORDER BY pjt_date_start ASC;";

        return $this->db->query($qry);
    }

}
