<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class WhOutputContentModel extends Model
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

// Listado de Productos de Proyecto asigando
    public function listDetailProds($params)
    {
        $pjt_id = $this->db->real_escape_string($params['pjt_id']);
        $empid = $this->db->real_escape_string($params['empid']);

        if ($empid==1   || $empid==2){
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
                WHERE ser.ser_stage IN ('TR','UP') AND pcn.pjtcn_id=prcn.pjtcn_id) 
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
                WHERE ser.ser_stage IN ('TR','UP') AND pcn.pjtcn_id=prcn.pjtcn_id AND prd.prd_level!='A')
                END AS cant_ser
            FROM ctt_projects_content AS prcn
            WHERE prcn.pjt_id=$pjt_id ORDER BY prcn.pjtcn_section, prcn.pjtcn_prod_sku ASC";
        }
        else{
            $qry = "SELECT pjc.pjtcn_id, pjc.pjtcn_prod_sku, pjc.pjtcn_prod_name, pjc.pjtcn_quantity, 
            pjc.pjtcn_prod_level, pjc.pjt_id, pjc.pjtcn_status, pjc.pjtcn_order, SUBSTR(pjc.pjtcn_prod_sku,1,2), 
            case 
                 when pjc.pjtcn_section=1 then 'Base'
                 when pjc.pjtcn_section=2 then 'Extra'
                 when pjc.pjtcn_section=3 then 'Por dia'
                 else 'Subarrendo'
                 END AS section,
                 case 
					  when pjc.pjtcn_prod_level='k' then 
					  CASE WHEN(SELECT COUNT(*) FROM ctt_series AS ser 
                INNER JOIN ctt_projects_detail AS pjd ON pjd.ser_id=ser.ser_id
                INNER JOIN ctt_projects_content AS pcn ON pcn.pjtvr_id= pjd.pjtvr_id 
                WHERE ser.ser_stage IN ('TR','UP') AND pcn.pjtcn_id=pjc.pjtcn_id) 
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
                WHERE ser.ser_stage IN ('TR','UP') AND pcn.pjtcn_id=pjc.pjtcn_id AND prd.prd_level!='A')
                END AS cant_ser
            FROM ctt_projects_content AS pjc 
            INNER JOIN ctt_categories AS cat ON lpad(cat.cat_id,2,'0')=SUBSTR(pjc.pjtcn_prod_sku,1,2)
            INNER JOIN ctt_employees AS em ON em.are_id=cat.are_id
            WHERE pjc.pjt_id=$pjt_id AND em.emp_id=$empid
            ORDER BY pjc.pjtcn_section, pjc.pjtcn_prod_sku ASC;";
        }
        return $this->db->query($qry);
    }
    // Listado de freelances x areas
    public function listFreelances($params)
    {
        $pjt_id = $this->db->real_escape_string($params['pjt_id']);

        $qry = "SELECT fr.free_id,fr.free_name,ar.are_name FROM ctt_freelances AS fr
                INNER JOIN ctt_areas AS ar ON ar.are_id=fr.free_area_id
                INNER JOIN ctt_assign_proyect AS ap ON ap.free_id=fr.free_id
                WHERE ap.pjt_id=$pjt_id and ap.ass_status = 1
                ORDER BY are_name";

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
   // Listado de Productos
   public function listSeries($params)
   {
        $pjtcnid = $this->db->real_escape_string($params['pjtcnid']);
       
        $qry = " SELECT pdt.pjtdt_id, pdt.pjtdt_prod_sku, prd.prd_name, prd.prd_level, prd.prd_status, 
                    pdt.ser_id, pdt.pjtvr_id, sr.ser_sku, sr.ser_serial_number, sr.ser_situation, 
                    sr.ser_stage, sr.ser_no_econo, per.pjtpd_day_start,per.pjtpd_day_end, pjv.pjtvr_section
                FROM ctt_projects_content AS pcn
                INNER JOIN ctt_projects_version as pjv ON pcn.pjtvr_id=pjv.pjtvr_id
                INNER JOIN ctt_projects_detail AS pdt ON pcn.pjtvr_id=pdt.pjtvr_id
                INNER JOIN ctt_series AS sr ON pdt.ser_id=sr.ser_id
                LEFT JOIN ctt_projects_periods AS per ON per.pjtdt_id=pdt.pjtdt_id
                LEFT JOIN ctt_products AS prd ON prd.prd_id=pdt.prd_id
                WHERE pcn.pjtcn_id=$pjtcnid AND prd.prd_level!='A' 
                ORDER BY pdt.pjtdt_prod_sku;";

       return $this->db->query($qry);
   }

   public function listSeriesFree($params)
   {
       $ser_id = $this->db->real_escape_string($params['serid']);
       $serorg = $this->db->real_escape_string($params['serorg']);

        $qry = "SELECT '$serorg' as id_orig, sr.ser_id, ser_sku, ser_serial_number, 
        ser_situation, ser_stage, pr.prd_name, pr.prd_sku, sr.ser_no_econo
        , pj.pjt_name
        FROM ctt_series AS sr
        INNER JOIN ctt_products as pr on sr.prd_id = pr.prd_id
        LEFT Join ctt_projects_detail AS pdt ON pdt.ser_id = sr.ser_id
        LEFT JOIN ctt_projects_version AS pv ON pv.pjtvr_id = pdt.pjtvr_id
        LEFT JOIN ctt_projects AS pj ON pj.pjt_id = pv.pjt_id
        WHERE pr.prd_level!='A' AND (sr.ser_sku LIKE '$ser_id%')
        AND sr.ser_status=1;";
            
       return $this->db->query($qry);
   }

   public function listComments($params)
   {
        $pjt_id = $this->db->real_escape_string($params['pjt_id']);
       
        $qry = "SELECT com_id, com_user, com_comment FROM ctt_comments 
                WHERE com_action_id=$pjt_id
                ORDER BY com_date;";

       return $this->db->query($qry);
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
    // check de Productos
    public function checkSeries($params)
    {
        $pjtcnid = $this->db->real_escape_string($params['serId']);

        $updt = "UPDATE ctt_series set ser_stage = 'TR' 
                where ser_id = '$pjtcnid' and ser_situation = 'EA'";

         $this->db->query($updt);
         return $pjtcnid;
        
    }

    public function changeSerieNew($params)
    {
        $serIdNew = $this->db->real_escape_string($params['serIdNew']);
        $serIdOrg = $this->db->real_escape_string($params['serIdOrg']);

        // Busca serie que se encuentre disponible y obtiene el id
        $qry1 = "SELECT ser_id FROM ctt_projects_detail WHERE pjtdt_id=$serIdOrg;";
        $resultid =  $this->db->query($qry1);
        $iddetail = $resultid->fetch_object();
            if ($iddetail != null){
                $serid  = $iddetail->ser_id; 
            } 

        $qry2 = "SELECT ser_sku FROM ctt_series WHERE ser_id=$serIdNew;";
        $resultid2 =  $this->db->query($qry2);
        $iddetail2 = $resultid2->fetch_object();
            if ($iddetail2 != null){ 
                $prodsku  = $iddetail2->ser_sku; 
            } 

        // Actualiza las tablas Detalle y Series
        $updt3 = "UPDATE ctt_projects_detail 
                SET ser_id=$serIdNew, pjtdt_prod_sku='$prodsku'
                WHERE ser_id=$serid AND pjtdt_id=$serIdOrg;";
        $this->db->query($updt3);
        $joinval=1;

        $updt4 = "UPDATE ctt_series SET ser_situation='D', ser_stage='D', pjtdt_id=0
                WHERE ser_id=$serid AND pjtdt_id=$serIdOrg;";
        $this->db->query($updt4);
        $joinval=$joinval+1;

        $updt5 = "UPDATE ctt_series SET ser_situation='EA', ser_stage='R',  pjtdt_id=$serIdOrg
                WHERE ser_id=$serIdNew AND pjtdt_id=0; ";
        $this->db->query($updt5);
        $joinval=$joinval+1;
        
        return  $joinval;
             
    }
    
    /** ==== Obtiene el contenido del proyecto =============================================================  */
   
    public function NextExchange()
    {
        $qry = "INSERT INTO ctt_counter_exchange (con_status) VALUES ('1');	";
        $this->db->query($qry);

        return $this->db->insert_id;
    }

    public function ActualizaSeries($params)
    {
        $serid		= $this->db->real_escape_string($params['serid']);

        $qry = "UPDATE ctt_series SET ser_stage='UP'
                WHERE ser_id=$serid ;";  
        //AND ser_stage='TR';

        $folio = $this->db->query($qry);
        return $folio;
    }

    public function UpdateProducts($param)
	{
        $stpid 		= $this->db->real_escape_string($param['stpid']);
        $quantity 	= $this->db->real_escape_string($param['stpqty']);
		/* $idStrSrc 	= $this->db->real_escape_string($param['strid']);
        $serid 		= $this->db->real_escape_string($param['serid']);
        $prodId 	= $this->db->real_escape_string($param['prodId']); */
		
		$qry = "UPDATE ctt_stores_products 
                SET stp_quantity = $quantity 
                WHERE stp_id = $stpid;";
		return $this->db->query($qry);
	}

    public function SaveExchange($param, $user)
	{
		$employee_data = explode("|",$user);
        $folio				= $this->db->real_escape_string($param['folio']);

		$exc_sku_product 	= $this->db->real_escape_string($param['prdsku']);
		$exc_product_name 	= $this->db->real_escape_string($param['prdnam']);
		$exc_quantity 		= $this->db->real_escape_string($param['cntqty']);
		$exc_serie_product	= $this->db->real_escape_string($param['serid']);
		$exc_store			= $this->db->real_escape_string($param['strid']);
		$exc_comments		= '';
		$exc_proyect		= $this->db->real_escape_string($param['prjId']);
		$exc_employee_name	= $this->db->real_escape_string($employee_data[2]);
		$ext_code			= 'SRP';
		$ext_id				= '7';

		$qry = "INSERT INTO ctt_stores_exchange (exc_sku_product, exc_product_name, exc_quantity, 
				exc_serie_product, exc_store, exc_comments, exc_proyect, exc_employee_name, 
				ext_code, ext_id, con_id)
				VALUES ('$exc_sku_product', '$exc_product_name', $exc_quantity, 
				'$exc_serie_product', '$exc_store', '$exc_comments', '$exc_proyect', 
				'$exc_employee_name', '$ext_code', $ext_id, $folio); ";
		$this->db->query($qry);
		return $folio;
	}
    public function GetEventos($params)
	{
		$ser_id 	= $this->db->real_escape_string($params['ser_id']);
		$qry = "SELECT pjp.pjtdt_id 'id', ser_sku 'title', pjp.pjtpd_day_start 'start', pjp.pjtpd_day_end 'end', '#3c5777' as 'color' FROM ctt_projects_detail AS pjd 
		INNER JOIN ctt_series AS sr ON sr.ser_id = pjd.ser_id
		INNER JOIN ctt_projects_content AS pjc ON pjc.pjtvr_id = pjd.pjtvr_id
		INNER JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id = pjd.pjtdt_id
		WHERE sr.ser_id=  $ser_id";
		return $this->db->query($qry);
	}
    public function GetProjectDetail($params)
    {
        $pjtid     = $this->db->real_escape_string($params['pjtid']);

        $qry = "SELECT stp.stp_id,(stp.stp_quantity-1) AS stp_quantity, stp.str_id, stp.ser_id, stp.prd_id,
                ct.pjtcn_prod_sku,ct.pjtcn_prod_name,ct.pjtcn_quantity,ct.pjt_id
                FROM ctt_stores_products AS stp
                INNER JOIN ctt_projects_detail AS pjd ON pjd.ser_id=stp.ser_id
                INNER JOIN ctt_projects_content AS ct ON ct.pjtvr_id=pjd.pjtvr_id
                WHERE ct.pjt_id=$pjtid and pjd.ser_id>0;";

        return $this->db->query($qry);
    }


    public function GetOutProject($params)
    {
        $pjtid = $this->db->real_escape_string($params['pjtid']);
        
        $updt = "UPDATE ctt_projects SET pjt_status = '8' 
                WHERE pjt_id = '$pjtid' ";

         /* $this->db->query($updt); */
         return $this->db->query($updt);
        
    }

    public function GetExistMovil($params)
    {
        $pjtid    = $this->db->real_escape_string($params['pjtid']);

        $qry = "SELECT mov.str_id,mov.ser_id,mov.movstr_placas,pjc.pjt_id FROM ctt_mobile_store AS mov
                INNER JOIN ctt_projects_detail AS pjd ON mov.ser_id=pjd.ser_id
                INNER JOIN ctt_projects_content AS pjc ON pjc.pjtvr_id=pjd.pjtvr_id
                WHERE pjc.pjt_id=$pjtid;";

        return $this->db->query($qry);
    }

    public function GetDetailMovil($params)
    {
        $strid        = $this->db->real_escape_string($params['strid']);

        $qry = "SELECT stp.stp_id,stp.stp_quantity,stp.str_id,stp.ser_id,stp.prd_id,prd.prd_sku,prd.prd_name,1 as quantity 
                FROM ctt_stores_products AS stp
                INNER JOIN ctt_series AS ser ON ser.ser_id=stp.ser_id
                INNER JOIN ctt_products AS prd ON prd.prd_id=ser.prd_id
                WHERE stp.str_id=$strid";

        return $this->db->query($qry);
    }


}
