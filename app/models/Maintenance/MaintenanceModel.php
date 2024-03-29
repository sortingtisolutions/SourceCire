<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class MaintenanceModel extends Model
{

    public function __construct()
    {
      parent::__construct();
    }

// Listado de Proyectos  ****
// public function listProjects($params)
// {
//     $liststat = $this->db->real_escape_string($params['liststat']);
//     $qry = "SELECT * FROM ctt_projects WHERE pjt_status IN ($liststat);";
//     return $this->db->query($qry);
// }    

public function listEstatusMantenimiento($params)
{
    
    $qry = "SELECT * FROM ctt_maintenance_status";
    return $this->db->query($qry);
}    

// Listado de Productos
    public function listProducts($params)
    {
        $pjtId = $this->db->real_escape_string($params['pjtId']);
        $em = $this->db->real_escape_string($params['em']);
        if ($em == 1) {
            $qry = "SELECT pd.prd_name,pd.prd_sku, pd.prd_id, ser.ser_id, ser.ser_sku, ser.ser_serial_number, ser.ser_status, ser.ser_situation, ser.ser_stage, 
                pj.pjt_name, pj.pjt_id, pj.pjt_date_start, pj.pjt_date_end, ifnull(pdm.pmt_id,0) as pmt_id, ifnull(pdm.pmt_days,0) AS pmt_days, ifnull(pdm.pmt_hours,0) AS pmt_hours, ifnull(pdm.pmt_date_start,'') AS pmt_date_start, ifnull(pdm.pmt_date_end,'') AS pmt_date_end, ifnull(pdm.pmt_comments,'') AS pmt_comments, ifnull(pdm.pjtcr_id,0) AS pjtcr_id, ifnull(mts.mts_description,'') AS mts_description, ifnull(pdm.mts_id,0) AS mts_id,
                ifnull(pdm.pmt_price,0) AS pmt_price, ser.ser_sku, ser.ser_serial_number, ser.ser_no_econo, ifnull(pjcr.pjtcr_id,0) as pjtcr_id, ifnull(pjcr.pjtcr_definition,'') as pjtcr_definition
                from ctt_products as pd 
                INNER JOIN ctt_series AS ser ON ser.prd_id = pd.prd_id
                INNER JOIN ctt_products_maintenance AS pdm ON pdm.ser_id = ser.ser_id
                INNER JOIN ctt_maintenance_status AS mts ON mts.mts_id = pdm.mts_id
                INNER JOIN ctt_projects AS pj ON pj.pjt_id = pdm.pjt_id
                LEFT JOIN ctt_project_change_reason AS pjcr ON pjcr.pjtcr_id= pdm.pjtcr_id
                WHERE pj.pjt_id='$pjtId' AND ser.ser_situation='M'";
        }else{
            $qry = "SELECT pd.prd_name,pd.prd_sku, pd.prd_id, ser.ser_id, ser.ser_sku, ser.ser_serial_number, ser.ser_status, ser.ser_situation, ser.ser_stage, 
            pj.pjt_name, pj.pjt_id, pj.pjt_date_start, pj.pjt_date_end, ifnull(pdm.pmt_id,0) as pmt_id, ifnull(pdm.pmt_days,0) AS pmt_days, ifnull(pdm.pmt_hours,0) AS pmt_hours, ifnull(pdm.pmt_date_start,'') AS pmt_date_start, ifnull(pdm.pmt_date_end,'') AS pmt_date_end, ifnull(pdm.pmt_comments,'') AS pmt_comments, ifnull(pdm.pjtcr_id,0) AS pjtcr_id, ifnull(mts.mts_description,'') AS mts_description, ifnull(pdm.mts_id,0) AS mts_id,
            ifnull(pdm.pmt_price,0) AS pmt_price, ser.ser_sku, ser.ser_serial_number, ser.ser_no_econo, ifnull(pjcr.pjtcr_id,0) as pjtcr_id, ifnull(pjcr.pjtcr_definition,'') as pjtcr_definition
            from ctt_products as pd 
            INNER JOIN ctt_series AS ser ON ser.prd_id = pd.prd_id
            INNER JOIN ctt_products_maintenance AS pdm ON pdm.ser_id = ser.ser_id
            INNER JOIN ctt_maintenance_status AS mts ON mts.mts_id = pdm.mts_id
            INNER JOIN ctt_projects AS pj ON pj.pjt_id = pdm.pjt_id
            INNER JOIN ctt_subcategories AS sb ON sb.sbc_id= pd.sbc_id
            INNER JOIN ctt_categories AS ct ON ct.cat_id = sb.cat_id
            INNER JOIN ctt_employees AS em ON em.are_id = ct.are_id
            LEFT JOIN ctt_project_change_reason AS pjcr ON pjcr.pjtcr_id= pdm.pjtcr_id
            WHERE pj.pjt_id='$pjtId' AND ser.ser_situation='M' AND em.emp_id = $em";
        }
        
        return $this->db->query($qry);
    }    

// Listado de Proveedores
    // public function listSuppliers($store)
    // {
    //     $store = $this->db->real_escape_string($store);
    //     $qry = "SELECT sup_id, sup_business_name FROM ctt_suppliers WHERE sup_status = 1 
    //     AND sut_id in (3) ORDER BY sup_business_name;";
    //     return $this->db->query($qry);
    // }   

// Listado de monedas
    // public function listCoins()
    // {
    //     $qry = "SELECT * FROM ctt_coins WHERE cin_status = 1;";
    //     return $this->db->query($qry);
    // }   


// Listado de Almacenes
    public function listStores()
    {
        $qry = "SELECT str_id, str_name FROM ctt_stores WHERE str_type = 'ESTATICOS' AND str_status = 1
        and str_name like 'SUBARRENDO%';";
        return $this->db->query($qry);
    }

    public function listChangeReasons($params)
    {
        $qry = "SELECT * FROM ctt_project_change_reason AS pjtcr";
        return $this->db->query($qry);
    }

// Agrega el serial del producto en subarrendo
    public function addSerie($params)
    {
        $ser_sku = $params['prodsku'] . 'R001';
        $ser_serial_number = 'R001';
        $ser_cost = $params['prprice'];
        $ser_status = '1';
        $ser_situation = 'D';
        $ser_stage = 'D';
        $ser_lonely = '1';
        $ser_behaviour = 'R';
        $prd_id = $params['produid'];
        $cin_id = $params['cointyp'];

        $qry = "INSERT INTO 
                    ctt_series (ser_sku, ser_serial_number, ser_cost, ser_status, ser_situation, ser_stage, ser_lonely, ser_behaviour, prd_id, cin_id ) 
                VALUES
                    ('$ser_sku','$ser_serial_number','$ser_cost','$ser_status','$ser_situation','$ser_stage','$ser_lonely','$ser_behaviour','$prd_id','$cin_id');
                ";
            $this->db->query($qry);
            $result = $this->db->insert_id;
            return $result . '|' . $params['produid'] .'|'.$params['supplid'] .'|'.$ser_serial_number .'|'.$params['storeid'] .'|'.$params['storenm'];
    }

// Agrega los productos subarrendados
    public function addSubletting($params)
    {
        $sub_price = $params['prc'];
        $sub_coin_type = $params['cin'];
        $sub_quantity = $params['qty'];
        $sub_date_start = $params['dst'];
        $sub_date_end = $params['den'];
        $sub_comments = $params['com'];
        $ser_id = $params['ser'];
        $sup_id = $params['sup'];
        $prj_id = $params['prj'];

        $qry = "INSERT INTO 
                    ctt_subletting (sub_price, sub_quantity, sub_date_start, sub_date_end, sub_comments, ser_id, sup_id, prj_id, cin_id ) 
                VALUES
                    ('$sub_price','$sub_quantity','$sub_date_start','$sub_date_end','$sub_comments','$ser_id','$sup_id','$prj_id','$sub_coin_type');
                ";
            $this->db->query($qry);
            $result = $this->db->insert_id;
            return $result ;
    } 

// Registra los movimientos entre almacenes
    public function SaveExchange($param, $user)
    {
        $employee_data = explode("|",$user);
        $exc_sku_product    = $this->db->real_escape_string($param['sku']);
        $exc_product_name   = $this->db->real_escape_string($param['nme']);
        $exc_quantity       = $this->db->real_escape_string($param['qty']);
        $exc_serie_product  = $this->db->real_escape_string($param['srn']);
        $exc_store          = $this->db->real_escape_string($param['stn']);
        $exc_comments       = $this->db->real_escape_string($param['com']);
        $exc_proyect        = $this->db->real_escape_string($param['prj']);
        $exc_employee_name  = $this->db->real_escape_string($employee_data[2]);
        $ext_code           = $this->db->real_escape_string($param['exn']);
        $ext_id             = $this->db->real_escape_string($param['exi']);
        $exc_guid           = $this->db->real_escape_string($param['fol']);
        $cin_id             = $this->db->real_escape_string($param['cin']);

        $qry = "INSERT INTO ctt_stores_exchange
                (exc_guid, exc_sku_product, exc_product_name, exc_quantity, exc_serie_product, exc_store, exc_comments, exc_proyect, exc_employee_name, ext_code, ext_id, cin_id)
                VALUES
                ('$exc_guid', '$exc_sku_product', '$exc_product_name', $exc_quantity, '$exc_serie_product', '$exc_store', '$exc_comments', '$exc_proyect', '$exc_employee_name', '$ext_code', $ext_id, $cin_id);
                ";
        return $this->db->query($qry);
    }

// Busca si existe asignado un almacen con este producto
    public function SechingProducts($param)
    {
        $prodId = $this->db->real_escape_string($param['ser']);
        $storId = $this->db->real_escape_string($param['sti']);

        $qry = "SELECT count(*) as items FROM ctt_stores_products WHERE ser_id = $prodId AND str_id = $storId;";
        return $this->db->query($qry);
    }


// Actualizala cantidad de productos en un almacen destino
    public function UpdateProducts($param)
    {
        
    }

// Agrega el registro de relación almacen producto
    public function InsertProducts($param)
     {
    
    }

// Proceso de subarrendo
    public function checkSerie($param)
    {
        $producId 		= $this->db->real_escape_string($param['producId']);

        $qry = "SELECT  count(*) as skuCount 
                  FROM  ctt_series 
                 WHERE  prd_id = $producId 
                   AND  LEFT(RIGHT(ser_sku, 4),1) ='R' 
                   AND  pjtdt_id = 0;";
        return $this->db->query($qry);
    }    

// Agrega nuevos registros de sku en subarrendo
    public function addNewSku($params)
    {
        $pjDetail 		= $this->db->real_escape_string($params['pjDetail']);
        $producId 		= $this->db->real_escape_string($params['producId']);
        $produSku 		= $this->db->real_escape_string($params['produSku']);
        $seriCost 		= $this->db->real_escape_string($params['seriCost']);
        $dtResIni 		= $this->db->real_escape_string($params['dtResIni']);
        $dtResFin 		= $this->db->real_escape_string($params['dtResFin']);
        $comments 		= $this->db->real_escape_string($params['comments']);
        $supplier 		= $this->db->real_escape_string($params['supplier']);
        $tpCoinId 		= $this->db->real_escape_string($params['tpCoinId']);
        $projecId 		= $this->db->real_escape_string($params['projecId']);
        $storesId 		= $this->db->real_escape_string($params['storesId']);

        // Obtiene el ultimo sku registrado para el producto seleccionado
        $qry = "SELECT ifnull(max(ser_sku),0) as last_sku, ifnull(ser_serial_number,0) as last_serie FROM ctt_series WHERE prd_id = $producId AND LEFT(RIGHT(ser_sku, 4),1) ='R';";
        $result = $this->db->query($qry);

        $skus = $result->fetch_object();
        $sku = $skus->last_sku;
        $serie = $skus->last_serie;
        $skuNew = intval($sku) +1 ;
        $skuNew = 'R' . str_pad($skuNew, 3, "0", STR_PAD_LEFT);
        $serieNew = intval($serie) +1 ;
        $serieNew = 'R' . str_pad($serieNew, 3, "0", STR_PAD_LEFT);

        $newSku = $produSku . $skuNew;

        // Agrega la nueva serie
        $qry1 = "INSERT INTO ctt_series (
                    ser_sku, ser_serial_number, ser_cost, ser_status, ser_situation, ser_stage, ser_date_registry, 
                    ser_reserve_count, ser_behaviour, ser_comments, 
                    prd_id, sup_id, cin_id, pjtdt_id
                )
                SELECT 
                    '$newSku', '$serieNew', '$seriCost', ifnull(sr.ser_status,1), ifnull(sr.ser_situation,'EA'), ifnull(sr.ser_stage, 'R'), curdate(),
                    '1', ifnull(sr.ser_behaviour,'C'), '$comments', 
                    pd.prd_id, '$supplier','$tpCoinId','$pjDetail'
                FROM ctt_series AS sr  
                RIGHT JOIN ctt_products AS pd ON pd.prd_id = sr.prd_id
                WHERE pd.prd_id = $producId LIMIT 1;";
                
        $this->db->query($qry1);

        $serieId = $this->db->insert_id;

        // Actualiza el detalle del proyecto con la serie
        $qry2 = "UPDATE ctt_projects_detail AS pd
                INNER JOIN ctt_series AS sr ON sr.pjtdt_id = pd.pjtdt_id
                SET pd.pjtdt_prod_sku = sr.ser_sku, pd.ser_id = sr.ser_id
                WHERE pd.pjtdt_id = $pjDetail ;";
        $this->db->query($qry2);

        // Agrega el nuevo registro en la tabla de subarrendos
        $qry3 = "INSERT INTO ctt_subletting (
                    sub_price, sub_quantity, sub_date_start, sub_date_end, sub_comments, 
                    ser_id, sup_id, prj_id, cin_id)
                SELECT 
                    ser_cost, '1', '$dtResIni', '$dtResFin', '$comments', ser_id, 
                    '$supplier', '$projecId', '$tpCoinId' 
                FROM ctt_series WHERE pjtdt_id = $pjDetail;";
        $this->db->query($qry3);

        $qry4 = "INSERT INTO ctt_stores_products 
                    (stp_quantity, str_id, ser_id, prd_id) 
                VALUES 
                    ('1','$storesId', '$serieId','$producId');";
        $this->db->query($qry4);


        return $pjDetail;

    }

    public function changeMaintain($params)
    {
        $comments 		= $this->db->real_escape_string($params['comments']);
        $days 		    = $this->db->real_escape_string($params['days']);
        $hrs		    = $this->db->real_escape_string($params['hrs']);
        $dtResIni 		= $this->db->real_escape_string($params['dtResIni']);
        $dtResFin 		= $this->db->real_escape_string($params['dtResFin']);
        $status 		= $this->db->real_escape_string($params['status']);
        $serieId	    = $this->db->real_escape_string($params['serieId']);
        $projChange 	= $this->db->real_escape_string($params['projChange']);
        
        $idMaintain 	= $this->db->real_escape_string($params['idMaintain']);
        $cost 	= $this->db->real_escape_string($params['cost']);
        $idProject 	= $this->db->real_escape_string($params['idProject']);

        
        
        if($status == 3){
            // Obtenemos la serie actual para poner su detail en un estatus de 'usado'
            $qry3="SELECT sr.pjtdt_id, sr.prd_id FROM ctt_series AS sr
            WHERE sr.ser_id = $serieId LIMIT 1;";
            $result1 =  $this->db->query($qry3);
            $serie_actual = $result1->fetch_object();

            if ($serie_actual != null) {
                $pjtdt_id  = $serie_actual->pjtdt_id;
                $producId  = $serie_actual->prd_id;

                $query = "UPDATE ctt_projects_detail SET sttd_id = 4 where pjtdt_id='$pjtdt_id'";
                $this->db->query($query);
            }
            // vemos si existe una serie a futuro en reserva que inicie despues de que este mantenimiento termina.
            $query = "SELECT sr.ser_id serId, sr.prd_id, pd.pjtdt_id, pd.pjtvr_id, pd.sttd_id, pjp.pjtpd_day_start, pjp.pjtpd_day_end
            FROM ctt_series AS sr
            INNER JOIN ctt_projects_detail AS pd ON pd.ser_id = sr.ser_id
            INNER JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id = pd.pjtdt_id
            WHERE sr.ser_id = $serieId AND pd.sttd_id = 3 AND pjp.pjtpd_day_start > '$dtResFin' ORDER BY pjtpd_day_start ASC LIMIT 1;"; // En Orden por fecha de inicio para que la mas proxima sea la que salga
            $result = $this->db->query($query);
            $pjdt = $result->fetch_object();
            if ($pjdt != null) { // si existe alguna serie en futuro entonces la serie va a cambiar a reserva con el id del detalle de esta serie en reserva
                $pjtdt_id = $pjdt->pjtdt_id;
                $qry1 = "UPDATE ctt_series 
                        SET 
                            ser_situation = 'EA', 
                            ser_stage = 'R',
                            pjtdt_id = $pjtdt_id
                        WHERE ser_id = '$serieId';";
                $this->db->query($qry1);
                // modificamos el detalle para indicarle que es el activo.
                $qry4 ="UPDATE ctt_projects_detail SET sttd_id = 1 where pjtdt_id = $pjtdt_id";
                $this->db->query($qry4);
            }else{
                // Si no existe ninguna serie a futuro entonces sin problema lo coloca como disponible.
                $qry1 = "UPDATE ctt_series 
                        SET 
                            ser_situation = 'D', 
                            ser_stage = 'D',
                            pjtdt_id = 0
                        WHERE ser_id = '$serieId';";
                $this->db->query($qry1);
            }

        }else{
            // Vemos que si existen series a futuro que inicien antes de que el mantenimiento termine entonces cambie de serie o la coloque como pendiente.
            $query = "SELECT sr.ser_id serId, sr.prd_id, pd.pjtdt_id, pd.pjtvr_id, pd.sttd_id, pjp.pjtpd_day_start, pjp.pjtpd_day_end
            FROM ctt_series AS sr
            INNER JOIN ctt_projects_detail AS pd ON pd.ser_id = sr.ser_id
            INNER JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id = pd.pjtdt_id
            WHERE sr.ser_id = $serieId AND pd.sttd_id = 3 AND pjp.pjtpd_day_start <= '$dtResFin';";
            $result = $this->db->query($query);
            $res= $result->fetch_object();
            // Si no existen series a futuro no cambia nada
            if($res != null){
                while ($row = $result->fetch_row()){
                    $pjtd_id = $row['pjtdt_id'];
                    $prodId = $row['prd_id'];
                    // primero hay que ver si existen series disponibles en las fechas reservadas.
                    $qry = "SELECT ser.ser_id serId, ser.ser_sku serSku 
                    FROM ctt_series AS ser
                    WHERE ser.prd_id = $prodId AND NOT EXISTS (SELECT sr.ser_id serId
                    FROM ctt_series AS sr
                    INNER JOIN ctt_projects_detail AS pd ON pd.ser_id = sr.ser_id
                    INNER JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id = pd.pjtdt_id
                    WHERE sr.ser_id = ser.ser_id AND (pjp.pjtpd_day_start BETWEEN '$dtResIni' AND '$dtResFin' 
                    OR pjp.pjtpd_day_end BETWEEN '$dtResIni' AND '$dtResFin' OR 
                    '$dtResIni' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end
                    OR '$dtResFin' BETWEEN pjp.pjtpd_day_start AND pjp.pjtpd_day_end));";  // solo trae un registro
        
                    $result =  $this->db->query($qry);
                    
                    $serie_futura = $result->fetch_object();

                    if ($serie_futura !=null ) { // Si existen series disponibles en esas fechas se modifica el sku y el id de la serie en details.
                        $sersku  = $serie_futura->serSku;
                        $serie = $serie_futura->serId;

                        $qry = "UPDATE ctt_projects_detail 
                        SET pjtdt_prod_sku = $sersku, 
                            ser_id = $serie, 
                            sttd_id = 3
                        where pjtdt_id = '$pjtd_id'";
                        $this->db->query($qry);
                    }else{ // si no existen series a futuro disponibles en las fechas se quedan pendiente
                        $qry = "UPDATE ctt_projects_detail 
                        SET pjtdt_prod_sku = 'Pendiente', 
                            ser_id = null, 
                            sttd_id = 2
                        where pjtdt_id = '$pjtd_id'";
                        $this->db->query($qry);
                    }
                    
                }
            }
            
        }
        
        $qry2 = "UPDATE ctt_products_maintenance
                    SET 
                        pmt_days = '$days',
                        pmt_hours = '$hrs',
                        pmt_date_start = '$dtResIni',
                        pmt_date_end = '$dtResFin',
                        pmt_comments = '$comments',
                        pmt_price = '$cost',
                        mts_id = '$status',
                        pjt_id = '$idProject', 
                        pjtcr_id = '$projChange'
                    WHERE pmt_id = '$idMaintain'";
        $this->db->query($qry2);

        $qryPrd = "SELECT sr.prd_id FROM ctt_series AS sr WHERE sr.ser_id = $serieId";
        $result1 =  $this->db->query($qryPrd);
        $prod = $result1->fetch_object();
        
        if ($prod != null) {
            $prdId = $prod->prd_id;

            $qry = "UPDATE ctt_products SET prd_reserved = (SELECT COUNT(*) FROM ctt_stores_products AS sp
                    INNER JOIN ctt_series AS sr ON sr.ser_id = sp.ser_id
                    INNER JOIN ctt_products AS pd ON pd.prd_id = sr.prd_id
                    INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
                    WHERE pd.prd_id = $prdId AND sr.ser_situation != 'D') WHERE prd_id = $prdId";
                
            $this->db->query($qry);
        }

        return $idProject;
    }

// Lista el productomodificado
    public function getPjtDetail($pjtId)
    {
        $pjtId = $this->db->real_escape_string($pjtId);
        $qry = "SELECT 
                    prd_name, prd_sku, pjtdt_prod_sku, sub_price, sup_business_name, str_name, ser_id,
                    DATE_FORMAT(sub_date_start,'%d/%m/%Y') AS sub_date_start, DATE_FORMAT(sub_date_end,'%d/%m/%Y') AS sub_date_end, 
                    sub_comments, pjtcn_days_base, pjtcn_days_trip, pjtcn_days_test,
                    ifnull(prd_id,0) AS prd_id, ifnull(sup_id,0) AS sup_id, ifnull(str_id,0) AS str_id, 
                    ifnull(sub_id,0) AS sub_id, ifnull(sut_id,0) AS sut_id, ifnull(pjtdt_id,0) AS pjtdt_id,
                    ifnull(pjtcn_id,0) AS pjtcn_id, ifnull(cin_id,0) AS cin_id
                FROM ctt_vw_subletting WHERE pjtdt_id = $pjtId;";
        return $this->db->query($qry);
    }


    // Guardar los datos del mantenimiento
    public function saveMaintain($params)
    {
        
        $comments 		= $this->db->real_escape_string($params['comments']);
        $days 		    = $this->db->real_escape_string($params['days']);
        $hrs		    = $this->db->real_escape_string($params['hrs']);
        $dtResIni 		= $this->db->real_escape_string($params['dtResIni']);
        $dtResFin 		= $this->db->real_escape_string($params['dtResFin']);
        $status 		= $this->db->real_escape_string($params['status']);
        $serieId	    = $this->db->real_escape_string($params['serieId']);
        $projChange 	= $this->db->real_escape_string($params['projChange']);
        $cost 	= $this->db->real_escape_string($params['cost']);
        $idProject 	= $this->db->real_escape_string($params['idProject']);

               // Agrega la nueva serie
        $qry1 = "INSERT INTO ctt_products_maintenance (
            pmt_days, pmt_hours, pmt_date_start, pmt_date_end, pmt_price, pmt_comments,pmt_date_register, mts_id, ser_id, pjtcr_id,pjt_id
        )  VALUES
        ('$days', '$hrs', '$dtResIni', '$dtResFin','$cost', '$comments',CURRENT_TIME(),'$status', '$serieId','$projChange','$idProject')";
                
        $this->db->query($qry1);

        $pmtId = $this->db->insert_id;
 
        return $pmtId;

    }

}


