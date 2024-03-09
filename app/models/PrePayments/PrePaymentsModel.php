<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class PrePaymentsModel extends Model
{

    public function __construct()
    {
      parent::__construct();
    }

// Listado de Proyectos  ****
// public function listProjects($store)
// {
//     $store = $this->db->real_escape_string($store);
//     $qry = "SELECT * FROM ctt_projects 
//             WHERE pjt_status>='2' and pjt_status<='99'
//             ORDER BY pjt_id";
//     return $this->db->query($qry);
// }    

public function listTypeMov($params)
{
    
    $qry = "SELECT * FROM ctt_way_topay";
    return $this->db->query($qry);
}    

// Listado de categorias   ****
public function listCustomers()
{
    $qry = "SELECT cus_id,cus_cve_cliente,cus_name 
            FROM ctt_customers AS cus
            WHERE cus.cut_id=1 ORDER BY cus_name asc;";
    return $this->db->query($qry);
}

public function getCustomersProj($params)
{
    $pjtId = $this->db->real_escape_string($params['pjtId']);
    $qry = "SELECT cow.cuo_id,cus.cus_id,cus.cus_name, pj.pjt_id, pj.pjt_name 
            FROM ctt_projects AS pj
            LEFT JOIN ctt_customers_owner AS cow ON cow.cuo_id=pj.cuo_id
            LEFT JOIN ctt_customers AS cus ON cus.cus_id=cow.cus_id
            WHERE pj.pjt_id=$pjtId;";
    return $this->db->query($qry);
}

// Listado de Productos
    public function listRegisters($params)
    {
        $pjtId = $this->db->real_escape_string($params['pjtId']);
        $em = $this->db->real_escape_string($params['em']);
        
        if ($em == 1) {
            $qry = "SELECT cus.cus_name,pjt.pjt_name,wtp.wtp_description, 
                            if(prp.prp_status=0,'No asignado','Asigando') AS statprp, prp.*  
                    FROM ctt_prepayments AS prp
                    LEFT JOIN ctt_projects AS pjt ON pjt.pjt_id=prp.pjt_id
                    LEFT JOIN ctt_customers AS cus ON cus.cus_id=prp.cus_id
                    INNER JOIN ctt_way_topay AS wtp ON wtp.wtp_id=prp.wtp_id
                    ORDER BY prp.prp_date_register;";
        }else{
            $qry = "SELECT cus.cus_name,pjt.pjt_name,wtp.wtp_description, 
                            if(prp.prp_status=0,'No asignado','Asigando') AS statprp, prp.*  
                    FROM ctt_prepayments AS prp
                    LEFT JOIN ctt_projects AS pjt ON pjt.pjt_id=prp.pjt_id
                    LEFT JOIN ctt_customers AS cus ON cus.cus_id=prp.cus_id
                    INNER JOIN ctt_way_topay AS wtp ON wtp.wtp_id=prp.wtp_id
                    ORDER BY prp.prp_date_register;";
        }
        
        return $this->db->query($qry);
    }    

// Listado de Proveedores
    public function listSuppliers($store)
    {
        $store = $this->db->real_escape_string($store);
        $qry = "SELECT sup_id, sup_business_name FROM ctt_suppliers WHERE sup_status = 1 
        AND sut_id in (3) ORDER BY sup_business_name;";
        return $this->db->query($qry);
    }   

// Listado de monedas
    // public function listCoins()
    // {
    //     $qry = "SELECT * FROM ctt_coins WHERE cin_status = 1;";
    //     return $this->db->query($qry);
    // }   

    public function listDataProyects($params)
    {
        $pjtId = $this->db->real_escape_string($params['prpid']);

        $qry = "SELECT pjt.pjt_name, prp.prp_amount, sum(pcn.pjtcn_prod_price) as totbase, 
                        pjt.pjt_date_start, pjs.pjs_name, pjt.pjt_id
                FROM ctt_projects_content AS pcn
                INNER JOIN ctt_prepayments AS prp ON pcn.pjt_id=prp.pjt_id
                INNER JOIN ctt_projects AS pjt ON pjt.pjt_id=pcn.pjt_id
                INNER JOIN ctt_projects_status AS pjs ON pjs.pjs_status=pjt.pjt_status
                WHERE pcn.pjt_id=$pjtId AND pcn.pjtcn_section=1";

        return $this->db->query($qry);
    }   


    public function insertPayAplied($params)
    {
        $prpId = $this->db->real_escape_string($params['prpId']);
        $referen = $this->db->real_escape_string($params['referen']);
        $DateStart = $this->db->real_escape_string($params['DateStart']);
        $montopayed = $this->db->real_escape_string($params['montopayed']);
        $montoasig = $this->db->real_escape_string($params['montoasig']);
        $montodiff = $this->db->real_escape_string($params['montodiff']);
        $foldoc = $this->db->real_escape_string($params['foldoc']);
        $pjtId = $this->db->real_escape_string($params['pjtId']);
        $wayPay = $this->db->real_escape_string($params['wayPay']);
        $empId = $this->db->real_escape_string($params['empId']);

        $qry="INSERT INTO ctt_prepayments_recorded (pyr_folio, pyr_date_paid, 
                        pyr_date_done, pyr_amount, pjt_base_amount, pyr_outcome, 
                        clt_id, pjt_id, wtp_id, emp_id, prp_id) 
            VALUES ('$referen','$DateStart', Now(),'$montopayed','$montoasig','$montodiff',
                    '$foldoc','$pjtId','$wayPay','$empId','$prpId');";

        $this->db->query($qry);
        $payapl = $this->db->insert_id;

        if($montodiff == 0) {
            $qry2="UPDATE ctt_prepayments set prp_status=2
                where prp_id=$prpId";

            $this->db->query($qry2);
        } else{
            $qry3="UPDATE ctt_prepayments set prp_status=1
            where prp_id=$prpId";

            $this->db->query($qry3);
        }

        return $payapl;
    }

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

        $qry4 = " INSERT INTO ctt_stores_products 
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
            $qry1 = "UPDATE ctt_series 
                    SET 
                        ser_situation = 'D', 
                        ser_stage = 'D'
                    WHERE ser_id = '$serieId';";
            $this->db->query($qry1);
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
    public function savePrePayment($params)
    {
        $idPrj 		    = $this->db->real_escape_string($params['idPrj']);
        $idcust		    = $this->db->real_escape_string($params['idcust']);
        $folio	        = $this->db->real_escape_string($params['folio']);
        $amount 	        = $this->db->real_escape_string($params['amount']);
        $dtResIni 		= $this->db->real_escape_string($params['dtResIni']);
        $idtypemov 		= $this->db->real_escape_string($params['idtypemov']);
        $origen 		= $this->db->real_escape_string($params['origen']);
        $destiny 	= $this->db->real_escape_string($params['destiny']);
        $comments 		= $this->db->real_escape_string($params['comments']);
        $empid 	= $this->db->real_escape_string($params['empid']);

        // Agrega un nuevo prepago
        $qry1 = "INSERT INTO ctt_prepayments (prp_folio, prp_date_doc, prp_date_register, 
                            prp_amount, prp_root_account, prp_deposit_account, prp_status, prp_description, 
                            wtp_id, pjt_id, cus_id, emp_id) 
                VALUES ('$folio', '$dtResIni', CURRENT_TIME(), '$amount', '$origen','$destiny', '0', '$comments', 
                        '$idtypemov', '$idPrj','$idcust','$empid')";
                
        $this->db->query($qry1);
        $pmtId = $this->db->insert_id;
 
        return $pmtId;
    }

}


