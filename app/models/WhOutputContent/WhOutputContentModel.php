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
                    pj.pjt_location, cus.cus_name, pj.pjt_id, free.free_id, 
                    wap.emp_id, wap.emp_fullname
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
        $areid = $this->db->real_escape_string($params['areid']);
        // AND prd.prd_level!='A'   SE QUITO LINEA 63
        if ($areid == 5){
            $qry = "SELECT prcn.pjtcn_id, prcn.pjtcn_prod_sku, prcn.pjtcn_prod_name, prcn.pjtcn_quantity, 
            prcn.pjtcn_prod_level, prcn.pjt_id, prcn.pjtcn_status, prcn.pjtcn_order, 
            CASE 
                 when prcn.pjtcn_section=1 then 'Base'
                 when prcn.pjtcn_section=2 then 'Extra'
                 when prcn.pjtcn_section=3 then 'Por dia'
                 else 'Subarrendo'
                 END AS section, 
                 CASE 
					  when prcn.pjtcn_prod_level='K' OR pd.prd_level = 'P' then 
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
                    WHERE pcn.pjtcn_id=prcn.pjtcn_id 
                    ORDER BY pdt.pjtdt_prod_sku) then prcn.pjtcn_quantity
                else'0'
                END 
					  ELSE (SELECT COUNT(*) FROM ctt_series AS ser 
                INNER JOIN ctt_projects_detail AS pjd ON pjd.ser_id=ser.ser_id
                INNER JOIN ctt_projects_content AS pcn ON pcn.pjtvr_id= pjd.pjtvr_id 
                LEFT JOIN ctt_products AS prd ON prd.prd_id=ser.prd_id
                WHERE ser.ser_stage IN ('TR','UP') AND pcn.pjtcn_id=prcn.pjtcn_id)
                END AS cant_ser
            FROM ctt_projects_content AS prcn
            INNER JOIN ctt_products AS pd ON pd.prd_id = prcn.prd_id
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
					  when pjc.pjtcn_prod_level='K' OR pd.prd_level = 'P' then 
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
                    WHERE pcn.pjtcn_id=pjc.pjtcn_id
                    ORDER BY pdt.pjtdt_prod_sku) then pjc.pjtcn_quantity
                else'0'
                END 
                ELSE 
                (SELECT COUNT(*) FROM ctt_series AS ser 
                INNER JOIN ctt_projects_detail AS pjd ON pjd.ser_id=ser.ser_id
                INNER JOIN ctt_projects_content AS pcn ON pcn.pjtvr_id= pjd.pjtvr_id 
                LEFT JOIN ctt_products AS prd ON prd.prd_id=ser.prd_id
                WHERE ser.ser_stage IN ('TR','UP') AND pcn.pjtcn_id=pjc.pjtcn_id)
                END AS cant_ser
            FROM ctt_projects_content AS pjc 
            INNER JOIN ctt_categories AS cat ON lpad(cat.cat_id,2,'0')=SUBSTR(pjc.pjtcn_prod_sku,1,2)
            INNER JOIN ctt_employees AS em ON em.are_id=cat.are_id
            INNER JOIN ctt_products AS pd ON pd.prd_id = pjc.prd_id
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
                WHERE pcn.pjtcn_id=$pjtcnid 
                ORDER BY pdt.pjtdt_prod_sku;";

       return $this->db->query($qry);
   }

   public function listSeriesFree($params)
   {
       $ser_id = $this->db->real_escape_string($params['serid']);
       $serorg = $this->db->real_escape_string($params['serorg']);
       $srpjvrorg = $this->db->real_escape_string($params['srpjvrorg']);

        $qry = "SELECT '$serorg' as id_orig, '$srpjvrorg' pjvr_id_org,  sr.ser_id, ser_sku, ser_serial_number, 
            ser_situation, ser_stage, pr.prd_name, pr.prd_sku, sr.ser_no_econo
            , pj.pjt_name, pdt.pjtdt_id, pdt.pjtvr_id
            FROM ctt_series AS sr
            INNER JOIN ctt_products as pr on sr.prd_id = pr.prd_id
            LEFT Join ctt_projects_detail AS pdt ON pdt.ser_id = sr.ser_id
            LEFT JOIN ctt_projects_version AS pv ON pv.pjtvr_id = pdt.pjtvr_id
            LEFT JOIN ctt_projects AS pj ON pj.pjt_id = pv.pjt_id
            WHERE (sr.ser_sku LIKE '$ser_id%')
            AND sr.ser_status=1 AND (pdt.sttd_id !=4 OR ISNULL(pdt.sttd_id) AND (sr.ser_type_asigned !='AF'));";
            
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

    $qry1 = "INSERT INTO ctt_comments (com_source_section,com_action_id, 
                    com_user,com_comment,com_status) 
            VALUES ('$comSrc',$pjtId,'$user','$comComment', 1);";

    $this->db->query($qry1);
    $comId = $this->db->insert_id;

    $qry2 = "SELECT com_id, com_date, com_user, com_comment 
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

    // public function changeSerieNew($params)
    // {
    //     $serIdNew = $this->db->real_escape_string($params['serIdNew']);
    //     $serIdOrg = $this->db->real_escape_string($params['serIdOrg']);
    //     $detailIdNew = $this->db->real_escape_string($params['detailIdNew']);
    //     $joinval= 0;

    //      // Busca los datos del detalle a modificar.
    //      $qry1 = "SELECT sr.ser_id, ser_sku, sr.pjtdt_id FROM ctt_projects_detail AS pjt
    //      INNER JOIN ctt_series AS sr ON sr.ser_id = pjt.ser_id  WHERE pjt.pjtdt_id=$serIdOrg;";
    //      $resultid =  $this->db->query($qry1);
    //      $iddetail = $resultid->fetch_object();
    //          if ($iddetail != null){
    //              $serIdOld  = $iddetail->ser_id; 
    //              $serSkuOld  = $iddetail->ser_sku; 
    //              $pjdtActiveOld = $iddetail->pjtdt_id;
    //          } 

    //     if ($detailIdNew != null) {
    //         // si la serie vieja es diferente a la nueva entonces...
    //         if ($serIdOld != $serIdNew) {
    //             // obtener el detalle de la serie 2 a modificar
    //             $qry ="SELECT sr.ser_sku, sr.pjtdt_id FROM ctt_series AS sr 
    //             INNER JOIN ctt_projects_detail AS pdt ON pdt.ser_id = sr.ser_id
    //             WHERE sr.ser_id = $serIdNew AND pdt.pjtdt_id = $detailIdNew LIMIT 1"; // datos de la serie por la que se quiere cambiar
    //             $result = $this->db->query($qry);

    //             $serie = $result->fetch_object();

    //             if ($serie != null) {
    //                 $skuNew = $serie->ser_sku;
    //                 $pjdtActiveNew = $serie->pjtdt_id;

    //                 $query ="SELECT pjpd.pjtpd_day_start, pjpd.pjtpd_day_end 
    //                     FROM ctt_projects_periods AS pjpd 
    //                     WHERE pjpd.pjtdt_id = $serIdOrg LIMIT 1";

    //                 $resultDet =  $this->db->query($query);
    //                 $detailf = $resultDet->fetch_object();
                    
    //                 if ($detailf != null){
    //                     $dtinic  = $detailf->pjtpd_day_start; 
    //                     $dtfinl    = $detailf->pjtpd_day_end; 
    //                 } 

    //                 // Buscamos si existen series a futuro que coincidan con ese rango de fechas.
    //                 if ($pjdtActiveNew == $detailIdNew) {
    //                     $qry = "SELECT ser.ser_id serId, ser.ser_sku, ser.pjtdt_id
    //                         FROM ctt_series AS ser
    //                         WHERE ser.ser_id = $serIdNew AND ser.ser_situation != 'M' AND NOT EXISTS (SELECT sr.ser_id serId
    //                         FROM ctt_series AS sr
    //                         INNER JOIN ctt_projects_detail AS pd ON pd.ser_id = sr.ser_id
    //                         INNER JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id = pd.pjtdt_id
    //                         WHERE sr.ser_id = ser.ser_id AND pd.sttd_id = 3  AND (pjp.pjtpd_day_start BETWEEN '$dtinic' AND '$dtfinl' 
    //                         OR pjp.pjtpd_day_end BETWEEN '$dtinic' AND '$dtfinl'  
    //                         OR '$dtinic' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end
    //                         OR '$dtfinl' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end)) LIMIT 1";  // solo trae un registro
    //                 }else{
    //                     $qry = "SELECT ser.ser_id serId, ser.ser_sku, ser.pjtdt_id
    //                         FROM ctt_series AS ser
    //                         WHERE ser.ser_id = $serIdNew AND ser.ser_situation != 'M' AND NOT EXISTS (SELECT sr.ser_id serId
    //                         FROM ctt_series AS sr
    //                         INNER JOIN ctt_projects_detail AS pd ON pd.ser_id = sr.ser_id
    //                         INNER JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id = pd.pjtdt_id
    //                         WHERE sr.ser_id = ser.ser_id AND pd.sttd_id != 4  AND (pjp.pjtpd_day_start BETWEEN '$dtinic' AND '$dtfinl' 
    //                         OR pjp.pjtpd_day_end BETWEEN '$dtinic' AND '$dtfinl'  
    //                         OR '$dtinic' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end
    //                         OR '$dtfinl' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end)) LIMIT 1"; 
    //                 }

    //                 $result =  $this->db->query($qry);
    //                 $serie_acept = $result->fetch_object();

    //                 // De igual manera buscamos las fechas del nuevo detalle
    //                 $query ="SELECT pjpd.pjtpd_day_start, pjpd.pjtpd_day_end 
    //                 FROM ctt_projects_periods AS pjpd 
    //                 WHERE pjpd.pjtdt_id = $detailIdNew LIMIT 1";

    //                 $resultDet =  $this->db->query($query);
    //                 $detailf2 = $resultDet->fetch_object();
                    
    //                 if ($detailf != null){
    //                     $dtinic2  = $detailf2->pjtpd_day_start; 
    //                     $dtfinl2    = $detailf2->pjtpd_day_end; 
    //                 } 
                    
    //                 if ($pjdtActiveOld == $serIdOrg) {
    //                     // No debe coincidir con series reservadas a futuro o con la activa en la serie conciderada como "OLD"
    //                     $qry = "SELECT ser.ser_id, ser.ser_sku, ser.pjtdt_id
    //                         FROM ctt_series AS ser
    //                         INNER JOIN ctt_projects_detail AS pdt ON pdt.ser_id = ser.ser_id
    //                         WHERE ser.ser_id = $serIdOld AND ser.ser_situation != 'M' AND NOT EXISTS (SELECT sr.ser_id serId
    //                         FROM ctt_series AS sr
    //                         INNER JOIN ctt_projects_detail AS pd ON pd.ser_id = sr.ser_id
    //                         INNER JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id = pd.pjtdt_id
    //                         WHERE sr.ser_id = ser.ser_id AND pd.sttd_id = 3  AND (pjp.pjtpd_day_start BETWEEN '$dtinic2' AND '$dtfinl2' 
    //                         OR pjp.pjtpd_day_end BETWEEN '$dtinic2' AND '$dtfinl2'  
    //                         OR '$dtinic2' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end
    //                         OR '$dtfinl2' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end)) LIMIT 1";  // solo trae un registro
    //                 }else{
    //                     $qry = "SELECT ser.ser_id, ser.ser_sku, ser.pjtdt_id
    //                         FROM ctt_series AS ser
    //                         INNER JOIN ctt_projects_detail AS pdt ON pdt.ser_id = ser.ser_id
    //                         WHERE ser.ser_id = $serIdOld AND ser.ser_situation != 'M' AND NOT EXISTS (SELECT sr.ser_id serId
    //                         FROM ctt_series AS sr
    //                         INNER JOIN ctt_projects_detail AS pd ON pd.ser_id = sr.ser_id
    //                         INNER JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id = pd.pjtdt_id
    //                         WHERE sr.ser_id = ser.ser_id AND pd.sttd_id != 4  AND (pjp.pjtpd_day_start BETWEEN '$dtinic2' AND '$dtfinl2' 
    //                         OR pjp.pjtpd_day_end BETWEEN '$dtinic2' AND '$dtfinl2'  
    //                         OR '$dtinic2' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end
    //                         OR '$dtfinl2' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end)) LIMIT 1"; 
    //                 }
    //                 $result =  $this->db->query($qry);
    //                 $serie_acept2 = $result->fetch_object();

    //                 // Si la primera serie no se ve afectada en las fechas con respecto a la segunda y biceversa entonces se procedera a hacer
    //                 // el intercambio de series en los detalles.
    //                 if ($serie_acept != null && $serie_acept2 != null) {

    //                     $prodSkuNew = $serie_acept->ser_sku;
    //                     $prodSkuOld = $serie_acept2->ser_sku;

    //                     // Actualiza las tablas Detalle y Series
    //                     $updt3 = "UPDATE ctt_projects_detail 
    //                             SET ser_id=$serIdNew, pjtdt_prod_sku='$prodSkuNew'
    //                             WHERE ser_id=$serIdOld AND pjtdt_id=$serIdOrg;";
    //                     $this->db->query($updt3);
    //                     $joinval=1;

    //                     // Actualiza las tablas Detalle y Series
    //                     $updt3 = "UPDATE ctt_projects_detail 
    //                             SET ser_id=$serIdOld, pjtdt_prod_sku='$prodSkuOld'
    //                             WHERE ser_id=$serIdNew AND pjtdt_id=$detailIdNew;";
    //                     $this->db->query($updt3);
    //                     $joinval=1;

    //                     if ($pjdtActiveNew == $detailIdNew) {
    //                         $updt5 = "UPDATE ctt_series SET ser_situation='EA', ser_stage='R',  pjtdt_id= $serIdOrg
    //                                 WHERE ser_id=$serIdNew AND pjtdt_id=$detailIdNew; ";
    //                         $this->db->query($updt5);
    //                     }

    //                     if ($pjdtActiveOld == $serIdOrg) {
    //                         $updt4 = "UPDATE ctt_series SET ser_situation='EA', ser_stage = 'R', pjtdt_id=$detailIdNew
    //                                 WHERE ser_id=$serIdOld AND pjtdt_id= $serIdOrg;";
    //                         $this->db->query($updt4);
    //                     }
    //                     $joinval=1;
    //                 }else{
    //                     $joinval=0; // Las series cuentan con series a futuro que no son aceptables con la nueva.
    //                 }
    //             }else{
    //                 $joinval='-2'; //NO se encontro el pjtdt_id con la serie indicada
    //             }
    //         }else{
    //             $joinval='-1'; // Enviar un indicador que la serie es la misma
    //         }
    //     }else{ 
    //         // si la nueva serie no esta en otro proyecto entonces hay que dejarla disponible.
    //         $qry2 = "SELECT ser_sku FROM ctt_series WHERE ser_id=$serIdNew;";
    //         $resultid2 =  $this->db->query($qry2);
    //         $iddetail2 = $resultid2->fetch_object();

    //         if ($iddetail2 != null){ 
    //             $prodsku  = $iddetail2->ser_sku; 
    //         } 
            
    //         $updt3 = "UPDATE ctt_projects_detail 
    //                 SET ser_id=$serIdNew, pjtdt_prod_sku='$prodsku'
    //                 WHERE ser_id=$serIdOld AND pjtdt_id=$serIdOrg;";
    //         $this->db->query($updt3);
    //         $joinval=1;

    //         $updt4 = "UPDATE ctt_series SET ser_situation='D', ser_stage='D', pjtdt_id=0
    //             WHERE ser_id=$serIdOld AND pjtdt_id=$serIdOrg;";
    //         $this->db->query($updt4);

    //         $updt5 = "UPDATE ctt_series SET ser_situation='EA', ser_stage='R',  pjtdt_id=$serIdOrg
    //                 WHERE ser_id=$serIdNew AND pjtdt_id=0; ";
    //         $this->db->query($updt5);
    //         $joinval=1;

    //     }
    //     return  $joinval;
             
    // }
    

    public function changeSerieNew($params)
    {
        $serIdNew = $this->db->real_escape_string($params['serIdNew']);
        $serIdOrg = $this->db->real_escape_string($params['serIdOrg']);
        $detailIdNew = $this->db->real_escape_string($params['detailIdNew']);

        //Obtenemos el pjtvrId
        $pjVersionNew = $this->db->real_escape_string($params['prjVersion']);
        $pjVersionOrg = $this->db->real_escape_string($params['pjVersionOrg']);

        $joinval= 0;

         // Busca los datos del detalle a modificar.
         $qry1 = "SELECT sr.ser_id, ser_sku, sr.pjtdt_id, sr.prd_id, sr.ser_type_asigned, pjt.sttd_id
         FROM ctt_projects_detail AS pjt
         INNER JOIN ctt_series AS sr ON sr.ser_id = pjt.ser_id  WHERE pjt.pjtdt_id=$serIdOrg;";
         $resultid =  $this->db->query($qry1);
         $iddetail = $resultid->fetch_object();
             if ($iddetail != null){
                 $serIdOld  = $iddetail->ser_id; 
                 $serSkuOld  = $iddetail->ser_sku; 
                 $pjdtActiveOld = $iddetail->pjtdt_id;
                 $typeAsignedOld = $iddetail->ser_type_asigned;
                 $prdIdSerOld = $iddetail->prd_id;
                 $sttdIdOld = $iddetail->sttd_id;
             } 

        if ($detailIdNew != 'null') {
            // si la serie vieja es diferente a la nueva entonces...
            if ($serIdOld != $serIdNew) {
                // obtener el detalle de la serie 2 a modificar
                $qry ="SELECT sr.ser_sku, sr.pjtdt_id, sr.ser_type_asigned, sr.prd_id, pdt.sttd_id FROM ctt_series AS sr 
                INNER JOIN ctt_projects_detail AS pdt ON pdt.ser_id = sr.ser_id
                WHERE sr.ser_id = $serIdNew AND pdt.pjtdt_id = $detailIdNew LIMIT 1"; // datos de la serie por la que se quiere cambiar
                $result = $this->db->query($qry);

                $serie = $result->fetch_object();

                if ($serie != null) {
                    $skuNew = $serie->ser_sku;
                    $pjdtActiveNew = $serie->pjtdt_id;
                    $typeAsignedNew = $serie->ser_type_asigned;
                    $prdIdSerNew = $serie->prd_id;
                    $sttdIdNew = $iddetail->sttd_id;

                    $query ="SELECT pjpd.pjtpd_day_start, pjpd.pjtpd_day_end 
                        FROM ctt_projects_periods AS pjpd 
                        WHERE pjpd.pjtdt_id = $serIdOrg LIMIT 1";

                    $resultDet =  $this->db->query($query);
                    $detailf = $resultDet->fetch_object();
                    
                    if ($detailf != null){
                        $dtinic  = $detailf->pjtpd_day_start; 
                        $dtfinl    = $detailf->pjtpd_day_end; 
                    } 

                    // Buscamos si existen series a futuro que coincidan con ese rango de fechas.
                    if ($pjdtActiveNew == $detailIdNew) {
                        $qry = "SELECT ser.ser_id serId, ser.ser_sku, ser.pjtdt_id
                            FROM ctt_series AS ser
                            WHERE ser.ser_id = $serIdNew AND ser.ser_situation != 'M' AND NOT EXISTS (SELECT sr.ser_id serId
                            FROM ctt_series AS sr
                            INNER JOIN ctt_projects_detail AS pd ON pd.ser_id = sr.ser_id
                            INNER JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id = pd.pjtdt_id
                            WHERE sr.ser_id = ser.ser_id AND pd.sttd_id = 3  AND (pjp.pjtpd_day_start BETWEEN '$dtinic' AND '$dtfinl' 
                            OR pjp.pjtpd_day_end BETWEEN '$dtinic' AND '$dtfinl'  
                            OR '$dtinic' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end
                            OR '$dtfinl' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end)) LIMIT 1";  // solo trae un registro
                    }else{
                        $qry = "SELECT ser.ser_id serId, ser.ser_sku, ser.pjtdt_id
                            FROM ctt_series AS ser
                            WHERE ser.ser_id = $serIdNew AND ser.ser_situation != 'M' AND NOT EXISTS (SELECT sr.ser_id serId
                            FROM ctt_series AS sr
                            INNER JOIN ctt_projects_detail AS pd ON pd.ser_id = sr.ser_id
                            INNER JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id = pd.pjtdt_id
                            WHERE sr.ser_id = ser.ser_id AND pd.sttd_id != 4  AND (pjp.pjtpd_day_start BETWEEN '$dtinic' AND '$dtfinl' 
                            OR pjp.pjtpd_day_end BETWEEN '$dtinic' AND '$dtfinl'  
                            OR '$dtinic' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end
                            OR '$dtfinl' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end)) LIMIT 1"; 
                    }

                    $result =  $this->db->query($qry);
                    $serie_acept = $result->fetch_object();

                    // De igual manera buscamos las fechas del nuevo detalle
                    $query ="SELECT pjpd.pjtpd_day_start, pjpd.pjtpd_day_end 
                    FROM ctt_projects_periods AS pjpd 
                    WHERE pjpd.pjtdt_id = $detailIdNew LIMIT 1";

                    $resultDet =  $this->db->query($query);
                    $detailf2 = $resultDet->fetch_object();
                    
                    if ($detailf != null){
                        $dtinic2  = $detailf2->pjtpd_day_start; 
                        $dtfinl2    = $detailf2->pjtpd_day_end; 
                    } 
                    
                    if ($pjdtActiveOld == $serIdOrg) {
                        // No debe coincidir con series reservadas a futuro o con la activa en la serie conciderada como "OLD"
                        $qry = "SELECT ser.ser_id, ser.ser_sku, ser.pjtdt_id
                            FROM ctt_series AS ser
                            INNER JOIN ctt_projects_detail AS pdt ON pdt.ser_id = ser.ser_id
                            WHERE ser.ser_id = $serIdOld AND ser.ser_situation != 'M' AND NOT EXISTS (SELECT sr.ser_id serId
                            FROM ctt_series AS sr
                            INNER JOIN ctt_projects_detail AS pd ON pd.ser_id = sr.ser_id
                            INNER JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id = pd.pjtdt_id
                            WHERE sr.ser_id = ser.ser_id AND pd.sttd_id = 3  AND (pjp.pjtpd_day_start BETWEEN '$dtinic2' AND '$dtfinl2' 
                            OR pjp.pjtpd_day_end BETWEEN '$dtinic2' AND '$dtfinl2'  
                            OR '$dtinic2' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end
                            OR '$dtfinl2' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end)) LIMIT 1";  // solo trae un registro
                    }else{
                        $qry = "SELECT ser.ser_id, ser.ser_sku, ser.pjtdt_id
                            FROM ctt_series AS ser
                            INNER JOIN ctt_projects_detail AS pdt ON pdt.ser_id = ser.ser_id
                            WHERE ser.ser_id = $serIdOld AND ser.ser_situation != 'M' AND NOT EXISTS (SELECT sr.ser_id serId
                            FROM ctt_series AS sr
                            INNER JOIN ctt_projects_detail AS pd ON pd.ser_id = sr.ser_id
                            INNER JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id = pd.pjtdt_id
                            WHERE sr.ser_id = ser.ser_id AND pd.sttd_id != 4  AND (pjp.pjtpd_day_start BETWEEN '$dtinic2' AND '$dtfinl2' 
                            OR pjp.pjtpd_day_end BETWEEN '$dtinic2' AND '$dtfinl2'  
                            OR '$dtinic2' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end
                            OR '$dtfinl2' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end)) LIMIT 1"; 
                    }
                    $result =  $this->db->query($qry);
                    $serie_acept2 = $result->fetch_object();

                    // Si la primera serie no se ve afectada en las fechas con respecto a la segunda y viceversa entonces se procedera a hacer
                    // el intercambio de series en los detalles.
                    if ($serie_acept != null && $serie_acept2 != null) {

                        $prodSkuNew = $serie_acept->ser_sku;
                        $prodSkuOld = $serie_acept2->ser_sku;

                        // Actualiza las tablas Detalle y Series
                        $updt3 = "UPDATE ctt_projects_detail 
                                SET ser_id=$serIdNew, pjtdt_prod_sku='$prodSkuNew'
                                WHERE ser_id=$serIdOld AND pjtdt_id=$serIdOrg;";
                        $this->db->query($updt3);
                        $joinval=1;

                        // Actualiza las tablas Detalle y Series
                        $updt3 = "UPDATE ctt_projects_detail 
                                SET ser_id=$serIdOld, pjtdt_prod_sku='$prodSkuOld'
                                WHERE ser_id=$serIdNew AND pjtdt_id=$detailIdNew;";
                        $this->db->query($updt3);
                        $joinval=1;

                        if ($pjdtActiveNew == $detailIdNew) {
                            $updt5 = "UPDATE ctt_series SET ser_situation='EA', ser_stage='R',  pjtdt_id= $serIdOrg
                                    WHERE ser_id=$serIdNew AND pjtdt_id=$detailIdNew; ";
                            $this->db->query($updt5);
                        }

                        if ($pjdtActiveOld == $serIdOrg) {
                            $updt4 = "UPDATE ctt_series SET ser_situation='EA', ser_stage = 'R', pjtdt_id=$detailIdNew
                                    WHERE ser_id=$serIdOld AND pjtdt_id= $serIdOrg;";
                            $this->db->query($updt4);
                        }

                        if ($typeAsignedNew == 'PF' && $typeAsignedOld == 'PV') {
                            // Seleccionamos los accesorios de la nueva serie que es fija
                            $qry3 = "SELECT * FROM ctt_projects_detail AS pjdt 
                            INNER JOIN ctt_series AS sr ON sr.ser_id = pjdt.ser_id
                            WHERE sr.prd_id_acc = $serIdNew AND pjdt.pjtvr_id = $pjVersionNew";
                            $resultSeriesNew =  $this->db->query($qry3);

                            // Datos del paquete, para conocer cantidades por cada producto en la serie.
                            $qry3 = "SELECT * FROM ctt_products_packages AS pck WHERE pck.prd_parent = $prdIdSerOld;";
                            $resultSeries =  $this->db->query($qry3);

                             //** AL NUEVO LE PONEMOS LA DEL ORIGINAL */
                            while ($row = $resultSeriesNew->fetch_assoc()) {
                                $serIdFijoNew = $row['ser_id'];
                                $detailId = $row['pjtdt_id'];
                                $serSkuFijoNew = $row['ser_sku'];
                                $prdIdFijoNew =$row['prd_id'];

                                // Los datos de las series fijas (Del detalle Original (Producto fijo)) le cambiamos de version para que
                                // reconozca a la nueva version en lugar de la original.
                                $updtDetailFijo = "UPDATE ctt_projects_detail 
                                                    SET pjtvr_id = $pjVersionOrg
                                                    WHERE ser_id=$serIdFijoNew AND pjtdt_id=$detailId;";
                                $this->db->query($updtDetailFijo);

                                $updtPackFijo = "UPDATE ctt_projects_periods
                                                    SET pjtpd_day_start = '$dtinic', pjtpd_day_end = '$dtfinl'
                                                    WHERE pjtdt_id=$detailId;";
                                $this->db->query($updtPackFijo);

                                
                            }
                             //** AL ORIGINAL LE PONEMOS LA DEL NUEVO */
                            while ($row = $resultSeries->fetch_assoc()) {
                                $qty = $row['pck_quantity'];
                                $prdIdPack = $row['prd_id'];
                                for ($i=0; $i < $qty; $i++) { 
                                    // Seleccionamos los accesorios de la nueva serie que es virtual
                                    $qrySeriesVirtuales = "SELECT sr.ser_id, pjdt.pjtdt_id, sr.ser_sku, sr.prd_id 
                                            FROM ctt_projects_detail AS pjdt 
                                            INNER JOIN ctt_series AS sr ON sr.ser_id = pjdt.ser_id
                                            INNER JOIN ctt_products_packages AS ppck ON ppck.prd_id = sr.prd_id
                                            WHERE ppck.prd_parent = '$prdIdSerNew' AND ppck.prd_id= '$prdIdPack' 
                                            AND sr.ser_type_asigned = 'AV' AND pjdt.pjtvr_id = $pjVersionOrg 
                                            LIMIT 1";
                                    $resultSeriesNew =  $this->db->query($qrySeriesVirtuales);
                                    $acceVirtual = $resultSeriesNew->fetch_object();

                                    if ($acceVirtual != null) {
                                        $serIdFijoOld = $acceVirtual->ser_id;
                                        $detailId = $acceVirtual->pjtdt_id;
                                        $serSkuFijoOld = $acceVirtual->ser_sku;
                                        $prdIdFijoOld = $acceVirtual->prd_id;
                                        // Cambiamos la version a la que pertenece los detalles para que ahora se reconozca como parte de la original
                                        $updtDetailFijo = "UPDATE ctt_projects_detail 
                                                            SET pjtvr_id = $pjVersionNew
                                                            WHERE ser_id=$serIdFijoOld AND pjtdt_id=$detailId;";
                                        $this->db->query($updtDetailFijo);

                                        $updtPackFijo = "UPDATE ctt_projects_periods
                                                            SET pjtpd_day_start = '$dtinic2', pjtpd_day_end = '$dtfinl2'
                                                            WHERE pjtdt_id=$detailId;";
                                        $this->db->query($updtPackFijo);
                                        
                                    }
                                    
                                }
                                
                            }



                        } elseif ($typeAsignedOld == 'PF' && $typeAsignedNew == 'PV') {

                            // Seleccionamos los accesorios de la serie original que es fija
                            $qry3 = "SELECT sr.ser_id, pjdt.pjtdt_id, sr.ser_sku, sr.prd_id 
                                        FROM ctt_projects_detail AS pjdt 
                                        INNER JOIN ctt_series AS sr ON sr.ser_id = pjdt.ser_id
                                        WHERE sr.prd_id_acc = $serIdOld AND pjdt.pjtvr_id = $pjVersionOrg";
                            $resultSeriesOld =  $this->db->query($qry3);

                            // Datos del paquete, para conocer cantidades por cada producto en la serie.
                            $qry3 = "SELECT * FROM ctt_products_packages AS pck WHERE pck.prd_parent = $prdIdSerNew;";
                            $resultSeries =  $this->db->query($qry3);

                            // Cambiamos los datos de la original a la nueva. //** AL ORIGINAL LE PONEMOS LA DEL NUEVO */
                            while ($row = $resultSeriesOld->fetch_assoc()) {
                                $serIdFijoOld = $row['ser_id'];
                                $detailId = $row['pjtdt_id'];
                                $serSkuFijoOld = $row['ser_sku'];
                                $prdIdFijoOld = $row['prd_id'];

                                // Los datos de las series fijas (Del detalle Original (Producto fijo)) le cambiamos de version para que
                                // reconozca a la nueva version en lugar de la original.
                                $updtDetailFijo = "UPDATE ctt_projects_detail 
                                                    SET pjtvr_id = $pjVersionNew
                                                    WHERE ser_id=$serIdFijoOld AND pjtdt_id=$detailId;";
                                $this->db->query($updtDetailFijo);

                                $updtPackFijo = "UPDATE ctt_projects_periods
                                                    SET pjtpd_day_start = '$dtinic2', pjtpd_day_end = '$dtfinl2'
                                                    WHERE pjtdt_id=$detailId;";
                                $this->db->query($updtPackFijo);

                                
                            }
                            // Hay que crear la relacion entre las nuevas series virtuales con el detalle original
                            // Seleccionamos las series correspondientes por producto. //** AL NUEVO LE PONEMOS LA DEL ORIGINAL */
                            while ($row = $resultSeries->fetch_assoc()) {
                                $qty = $row['pck_quantity'];
                                $prdIdPack = $row['prd_id'];
                                for ($i=0; $i < $qty; $i++) { 
                                    // Seleccionamos los accesorios de la nueva serie que es virtual
                                    
                                    $qrySeriesVirtuales = "SELECT sr.ser_id, pjdt.pjtdt_id, sr.ser_sku, sr.prd_id 
                                            FROM ctt_projects_detail AS pjdt 
                                            INNER JOIN ctt_series AS sr ON sr.ser_id = pjdt.ser_id
                                            INNER JOIN ctt_products_packages AS ppck ON ppck.prd_id = sr.prd_id
                                            WHERE ppck.prd_parent = '$prdIdSerNew' AND ppck.prd_id= '$prdIdPack' 
                                            AND sr.ser_type_asigned = 'AV' AND pjdt.pjtvr_id = $pjVersionNew 
                                            LIMIT 1";
                                    $resultSeriesNew =  $this->db->query($qrySeriesVirtuales);
                                    $acceVirtual = $resultSeriesNew->fetch_object();

                                    if ($acceVirtual != null) {
                                        $serIdFijoNew = $acceVirtual->ser_id;
                                        $detailId = $acceVirtual->pjtdt_id;
                                        $serSkuFijoNew = $acceVirtual->ser_sku;
                                        $prdIdFijoNew = $acceVirtual->prd_id;
                                        // Cambiamos la version a la que pertenece los detalles para que ahora se reconozca como parte de la original
                                        $updtDetailFijo = "UPDATE ctt_projects_detail 
                                                            SET pjtvr_id = $pjVersionOrg
                                                            WHERE ser_id=$serIdFijoNew AND pjtdt_id=$detailId;";
                                        $this->db->query($updtDetailFijo);

                                        $updtPackFijo = "UPDATE ctt_projects_periods
                                                            SET pjtpd_day_start = '$dtinic', pjtpd_day_end = '$dtfinl'
                                                            WHERE pjtdt_id=$detailId;";
                                        $this->db->query($updtPackFijo);
                                        
                                    }
                                    
                                }
                                
                            }


                        }
                        elseif ($typeAsignedNew == 'PF' && $typeAsignedOld == 'PF') 
                        {
                            // Seleccionamos los accesorios de la nueva serie que es fija
                            $qry3 = "SELECT sr.ser_id, pjdt.pjtdt_id, sr.ser_sku, sr.prd_id 
                                    FROM ctt_projects_detail AS pjdt 
                                    INNER JOIN ctt_series AS sr ON sr.ser_id = pjdt.ser_id
                                    WHERE sr.prd_id_acc = $serIdNew AND pjdt.pjtvr_id = $pjVersionNew";
                            $resultSeriesNew =  $this->db->query($qry3);

                            // Primero hay que crear las nuevas series de accesorios en la version original (la que intentamos cambiar)
                            while ($row = $resultSeriesNew->fetch_assoc()) {
                                $serIdFijoNew = $row['ser_id'];
                                $detailId = $row['pjtdt_id'];
                                $serSkuFijoNew = $row['ser_sku'];
                                $prdIdFijoNew = $row['prd_id'];

                                $updtDetailFijo = "UPDATE ctt_projects_detail 
                                                    SET pjtvr_id = $pjVersionOrg
                                                    WHERE ser_id=$serIdFijoNew AND pjtdt_id=$detailId;";
                                $this->db->query($updtDetailFijo);

                                $updtPackFijo = "UPDATE ctt_projects_periods
                                                    SET pjtpd_day_start = '$dtinic', pjtpd_day_end = '$dtfinl'
                                                    WHERE pjtdt_id=$detailId;";
                                $this->db->query($updtPackFijo);

                                
                            }

                            // Seleccionamos los accesorios de la serie original que es fija
                            $qry4 = "SELECT sr.ser_id, pjdt.pjtdt_id, sr.ser_sku, sr.prd_id 
                                    FROM ctt_projects_detail AS pjdt 
                                    INNER JOIN ctt_series AS sr ON sr.ser_id = pjdt.ser_id
                                    WHERE sr.prd_id_acc = $serIdOld AND pjdt.pjtvr_id = $pjVersionOrg";
                            $resultSeriesOld =  $this->db->query($qry4);
                            // Despues hay que crear las series de accesorios en el detalle que estamos modificando para pasarlos al que eliminamos de nuevos
                            while ($row = $resultSeriesOld->fetch_assoc()) {
                                $serIdFijoOld = $row['ser_id'];
                                $detailId = $row['pjtdt_id'];
                                $serSkuFijoOld = $row['ser_sku'];
                                $prdIdFijoOld = $row['prd_id'];

                                $updtDetailFijo = "UPDATE ctt_projects_detail 
                                                    SET pjtvr_id = $pjVersionNew
                                                    WHERE ser_id=$serIdFijoOld AND pjtdt_id=$detailId;";
                                $this->db->query($updtDetailFijo);

                                $updtPackFijo = "UPDATE ctt_projects_periods
                                                    SET pjtpd_day_start = '$dtinic2', pjtpd_day_end = '$dtfinl2'
                                                    WHERE pjtdt_id=$detailId;";
                                $this->db->query($updtPackFijo);
                               
                            }
                        }
                        
                        $joinval=1;
                    }else{
                        $joinval=0; // Las series cuentan con series a futuro que no son aceptables con la nueva.
                    }
                }else{
                    $joinval='-2'; //NO se encontro el pjtdt_id con la serie indicada
                }
            }else{
                $joinval='-1'; // Enviar un indicador que la serie es la misma
            }
        }else{ 
            // si la nueva serie no esta en otro proyecto entonces hay que dejarla disponible.
            $qry2 = "SELECT ser_sku, ser_type_asigned, prd_id FROM ctt_series WHERE ser_id=$serIdNew;";
            $resultid2 =  $this->db->query($qry2);
            $iddetail2 = $resultid2->fetch_object();

            if ($iddetail2 != null){ 
                $prodsku  = $iddetail2->ser_sku; 
                $typeAsignedNew  = $iddetail2->ser_type_asigned; 
                $prdIdSerNew  = $iddetail2->prd_id; 
            } 
            $updt3 = "UPDATE ctt_projects_detail 
                    SET ser_id=$serIdNew, pjtdt_prod_sku='$prodsku'
                    WHERE ser_id=$serIdOld AND pjtdt_id=$serIdOrg;";
            $this->db->query($updt3);
            $joinval=1;

            $updt4 = "UPDATE ctt_series SET ser_situation='D', ser_stage='D', pjtdt_id=0
                WHERE ser_id=$serIdOld AND pjtdt_id=$serIdOrg;";
            $this->db->query($updt4);

            $updt5 = "UPDATE ctt_series SET ser_situation='EA', ser_stage='R',  pjtdt_id=$serIdOrg
                    WHERE ser_id=$serIdNew AND pjtdt_id=0; ";
            $this->db->query($updt5);
            $joinval=1;

            

            $query ="SELECT pjpd.pjtpd_day_start, pjpd.pjtpd_day_end 
                    FROM ctt_projects_periods AS pjpd 
                    WHERE pjpd.pjtdt_id = $serIdOrg LIMIT 1";

            $resultDet =  $this->db->query($query);
            $detailf = $resultDet->fetch_object();
            
            if ($detailf != null){
                $dtinic  = $detailf->pjtpd_day_start; 
                $dtfinl    = $detailf->pjtpd_day_end; 
            } 

            if ($typeAsignedOld == 'PF' || $typeAsignedNew == 'PF') {
                // Vamos a eliminar los datos que se almacenaron respecto a los accesorios del producto original
                // Esto debido a que la nueva serie del producto podria no tener las mismas cantidades
                

                if ($typeAsignedOld == 'PF') 
                {
                    $qry3 = "SELECT ser_id,ser_sku FROM ctt_series AS sr 
                    WHERE sr.prd_id_acc = $serIdOld;";
                    $resultSeries =  $this->db->query($qry3);

                    while ($row = $resultSeries->fetch_assoc()) {
                        $serIdFijoOld = $row['ser_id'];
                        $serSkFijoOld = $row['ser_sku'];

                        $query = "SELECT * FROM ctt_projects_detail WHERE pjtvr_id = '$pjVersionOrg' and ser_id= $serIdFijoOld LIMIT 1";
                        $detailAccFijo = $this->db->query($query);
                        $acceFijo = $detailAccFijo->fetch_object();
                        if ($acceFijo != null) {
                            $detailId = $acceFijo->pjtdt_id;
                            $deleteDetailFijo = "DELETE FROM ctt_projects_detail
                                            WHERE pjtvr_id = '$pjVersionOrg' and ser_id= $serIdFijoOld and pjtdt_id = $detailId";
                            $this->db->query($deleteDetailFijo);

                            $deletePackFijo = "DELETE FROM ctt_projects_periods
                                                WHERE pjtdt_id = $detailId";
                            $this->db->query($deletePackFijo);
                            
                            $updt4 = "UPDATE ctt_series SET ser_situation='D', ser_stage='D', pjtdt_id=0
                                WHERE ser_id=$serIdFijoOld AND pjtdt_id=$detailId;";
                            $this->db->query($updt4);
                        }

                    }
                }elseif ($typeAsignedOld == 'PV') 
                {
                    $qry3 = "SELECT * FROM ctt_products_packages AS pck WHERE pck.prd_parent = $prdIdSerOld;";
                    $resultSeries =  $this->db->query($qry3);

                    while ($row = $resultSeries->fetch_assoc()) {
                        $prdIdVirtual = $row['prd_id'];
                        $quantityVirtual = $row['pck_quantity'];
                        for ($i=0; $i < $quantityVirtual; $i++) { 
                            $query = "SELECT * FROM ctt_projects_detail WHERE prd_id = $prdIdVirtual AND pjtvr_id = '$pjVersionOrg' LIMIT 1";
                            $detailAccVirtual = $this->db->query($query);
                            $acceVirtual = $detailAccVirtual->fetch_object();
                            if ($acceVirtual != null) {
                                $detail_id = $acceVirtual->pjtdt_id;
                                $deleteDetailVirtual = "DELETE FROM ctt_projects_detail
                                                    WHERE pjtdt_id = $detail_id";
                                $this->db->query($deleteDetailVirtual); 

                                $deletePackVirtual = "DELETE FROM ctt_projects_periods
                                                    WHERE pjtdt_id = $detail_id";
                                $this->db->query($deletePackVirtual); 

                                $updt4 = "UPDATE ctt_series SET ser_situation='D', ser_stage='D', pjtdt_id=0
                                    WHERE pjtdt_id=$detail_id;";
                                $this->db->query($updt4);
                            }
                        }
                        
                    }
                }

                // De la nueva serie vamos a insertar a detalles los nuevos accesorios fijos.
                if ($typeAsignedNew == 'PF') 
                {
                    $qry3 = "SELECT ser_id,ser_sku,prd_id FROM ctt_series AS sr 
                            WHERE sr.prd_id_acc = $serIdNew;";

                    $resultSeries =  $this->db->query($qry3);

                    $query ="SELECT pjpd.pjtpd_day_start, pjpd.pjtpd_day_end 
                        FROM ctt_projects_periods AS pjpd 
                        WHERE pjpd.pjtdt_id = $serIdOrg LIMIT 1";

                    $resultDet =  $this->db->query($query);
                    $detailf = $resultDet->fetch_object();
                    
                    if ($detailf != null){
                        $dtinic  = $detailf->pjtpd_day_start; 
                        $dtfinl    = $detailf->pjtpd_day_end; 
                    } 
                    while ($row = $resultSeries->fetch_assoc()) {
                        $serIdFijo = $row['ser_id'];
                        $serSkuFijo = $row['ser_sku'];
                        $prdIdFijo = $row['prd_id'];

                        $insertDetailFijo = "INSERT INTO ctt_projects_detail(pjtdt_belongs, pjtdt_prod_sku,prd_type_asigned, ser_id,prd_id,pjtvr_id,sttd_id)
                                            VALUES(0,'$serSkuFijo','AF','$serIdFijo', '$prdIdFijo','$pjVersionOrg', '$sttdIdOld')";
                        $this->db->query($insertDetailFijo);
                        $pjtdtId = $this->db->insert_id;

                        $insertPeriodsFijo = "INSERT INTO ctt_projects_periods(pjtpd_day_start, pjtpd_day_end,pjtdt_id, pjtdt_belongs,pjtpd_sequence)
                                                    VALUES('$dtinic','$dtfinl','$pjtdtId',0, '1')";
                        $this->db->query($insertPeriodsFijo);
                                
                        $updt5 = "UPDATE ctt_series SET ser_situation='EA', ser_stage='R',  pjtdt_id=$pjtdtId
                                WHERE ser_id=$serIdFijo AND pjtdt_id=0; ";
                        $this->db->query($updt5);
                        $joinval=1;

                    }
                }elseif ($typeAsignedNew == 'PV') 
                {
                    $qry3 = "SELECT prd_id,pck_quantity FROM ctt_products_packages AS pck WHERE pck.prd_parent = $prdIdSerNew;";
                    $resultSeries =  $this->db->query($qry3);
                    $joinval = $prdIdSerNew;

                    while ($row = $resultSeries->fetch_assoc()) {
                        $prdIdVirtual = $row['prd_id'];
                        $quantityVirtual = $row['pck_quantity'];
                        
                        for ($i=0; $i < $quantityVirtual; $i++) { 

                            $qrySerieNewAcc = "SELECT ser.ser_id, ser.ser_sku, ser.prd_id, pjdt.pjtdt_id FROM ctt_series AS ser
                                        LEFT JOIN ctt_projects_detail AS pjdt ON pjdt.ser_id = ser.ser_id
                                        LEFT JOIN ctt_projects_periods AS pjpr ON pjpr.pjtdt_id = pjdt.pjtdt_id
                                        WHERE ser.ser_type_asigned = 'AV' AND ser.ser_situation != 'M' 
                                        AND ser.prd_id = $prdIdVirtual AND NOT EXISTS (SELECT sr.ser_id serId
                                        FROM ctt_series AS sr
                                        INNER JOIN ctt_projects_detail AS pd ON pd.ser_id = sr.ser_id
                                        INNER JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id = pd.pjtdt_id
                                        WHERE sr.ser_id = ser.ser_id AND pd.sttd_id != 4  AND (pjp.pjtpd_day_start 
                                        BETWEEN '$dtinic' AND '$dtfinl' 
                                        OR pjp.pjtpd_day_end BETWEEN '$dtinic' AND '$dtfinl'  
                                        OR '$dtinic' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end
                                        OR '$dtfinl' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end)) LIMIT 1";

                            $serieNewAcc = $this->db->query($qrySerieNewAcc);
                            $serieNewAccFijo = $serieNewAcc->fetch_object();
                            
                            if ($serieNewAccFijo != null) {
                                $serIdAcc = $serieNewAccFijo->ser_id;
                                $serSkAcc = $serieNewAccFijo->ser_sku;
                                $prdIdAcc = $serieNewAccFijo->prd_id;
                                $sttdIdAcc = 0;
                                $pjtdtIdAcc = $serieNewAccFijo->pjtdt_id;

                                if ($pjtdtIdAcc == 0) {
                                    $sttdIdAcc = 1;
                                }else{
                                    $sttdIdAcc = 3;
                                }

                                $qryAcc = "INSERT INTO ctt_projects_detail(pjtdt_belongs, pjtdt_prod_sku,prd_type_asigned, ser_id,prd_id,pjtvr_id,sttd_id)
                                            VALUES(0,'$serSkAcc','AV','$serIdAcc', '$prdIdAcc','$pjVersionOrg','$sttdIdAcc')";
                                $this->db->query($qryAcc);
                                
                                $pjtdtId = $this->db->insert_id;

                                $insertPeriodsFijo = "INSERT INTO ctt_projects_periods(pjtpd_day_start, pjtpd_day_end,pjtdt_id, pjtdt_belongs,pjtpd_sequence)
                                                            VALUES('$dtinic','$dtfinl','$pjtdtId',0, '1')";
                                $this->db->query($insertPeriodsFijo);

                                if ($pjtdtIdAcc == 0) {
                                    $updt5 = "UPDATE ctt_series SET ser_situation='EA', ser_stage='R',  pjtdt_id=$pjtdtId
                                            WHERE ser_id=$serIdAcc AND pjtdt_id=0; ";
                                    $this->db->query($updt5);
                                }

                            }else{
                                $pjtdtIdAcc =0;
                                if ($pjtdtIdAcc == 0) {
                                    $sttdIdAcc = 1;
                                }else{
                                    $sttdIdAcc = 3;
                                }
                                $qryAcc = "INSERT INTO ctt_projects_detail(pjtdt_belongs, pjtdt_prod_sku,prd_type_asigned, ser_id,prd_id,pjtvr_id,sttd_id)
                                            VALUES(0,'pendiente','AV','0', ' $prdIdVirtual','$pjVersionOrg', '$sttdIdAcc'";
                                $this->db->query($qryAcc);
                                
                                $pjtdtId = $this->db->insert_id;

                                $insertPeriodsFijo = "INSERT INTO ctt_projects_periods(pjtpd_day_start, pjtpd_day_end,pjtdt_id, pjtdt_belongs,pjtpd_sequence)
                                                            VALUES('$dtinic','$dtfinl','$pjtdtId',0, '1')";
                                $this->db->query($insertPeriodsFijo);
                            }

                        }
                    }
                }
            }
            
        }
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

        $folio = $this->db->query($qry);
        return $folio;
    }

    public function UpdateProducts($param)
	{
        $stpid 		= $this->db->real_escape_string($param['stpid']);
        $quantity 	= $this->db->real_escape_string($param['stpqty']);
		
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
		$qry = "SELECT pjp.pjtdt_id 'id', pjt.pjt_name 'title', pjp.pjtpd_day_start 'start', DATE_ADD(pjp.pjtpd_day_end, INTERVAL 1 DAY) 'end', '#3c5777' as 'color' FROM ctt_projects_detail AS pjd 
		INNER JOIN ctt_series AS sr ON sr.ser_id = pjd.ser_id
		INNER JOIN ctt_projects_content AS pjc ON pjc.pjtvr_id = pjd.pjtvr_id
		INNER JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id = pjd.pjtdt_id
		INNER JOIN ctt_projects AS pjt ON pjt.pjt_id = pjc.pjt_id
		WHERE sr.ser_id= $ser_id AND pjd.sttd_id !=4";
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
