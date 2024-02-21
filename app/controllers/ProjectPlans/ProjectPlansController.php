<?php
    defined('BASEPATH') or exit('No se permite acceso directo');
    require_once ROOT . FOLDER_PATH . '/app/models/ProjectPlans/ProjectPlansModel.php';
    require_once LIBS_ROUTE .'Session.php';

class ProjectPlansController extends Controller
{
    private $session;
    public $model;

    public function __construct()
    {
        $this->model = new ProjectPlansModel();
        $this->session = new Session();
        $this->session->init();
        if($this->session->getStatus() === 1 || empty($this->session->get('user')))
            header('location: ' . FOLDER_PATH .'/Login');
    }

    public function exec()
    {
        $params = array('user' => $this->session->get('user'));
        $this->render(__CLASS__, $params);
    }

    /** ==== Lista los proyectos  ***/
    public function listProjects($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProjects($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"pjt_id":"0"}]';	
        }
        echo $res;
    } 

/** ==== Lista los proyectos padre ===========================================================  */
    public function listProjectsParents($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProjectsParents($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"pjt_id":"0"}]';	
        }
        echo $res;
    } 
 
/** ==== Lista los versiones =================================================================  */
    public function listVersion($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listVersion($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"ver_id":"0"}]';	
        }
        echo $res;
    } 

/** ==== Lista el contendio del proyecto =====================================================  */
    public function listBudgets($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listBudgets($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"pjtvr_id":"0"}]';	
        }
        echo $res;
    } 

     // ELIMINAR LAS LOCACIONES Y ESTADOS DE UN PROYECTO ***ED
        public function DeleteLocation($request_params){
        {   
            $params =  $this->session->get('user');
            $result = $this->model->DeleteLocation($request_params);
            echo $result;
        } 
    }

/** ==== Lista de clientes ===================================================================  */
    public function listCustomers($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listCustomers($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"cus_id":"0"}]';	
        }
        echo $res;
    } 
    // LISTAR CATEGORIAS ***ED
    public function listCategories($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listCategories($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"prd_id":"0"}]';	
        }
        echo $res;
    } 

    //  ***ED
    public function listSubCategories($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listSubCategories($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"prd_id":"0"}]';	
        }
        echo $res;
    } 
    //LISTAR PRODUCTOS  ***ED
    public function listProductsCombo($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProductsCombo($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"prd_id":"0"}]';	
        }
        echo $res;
    } 
    public function listProductsInput($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProductsInput($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"prd_id":"0"}]';	
        }
        echo $res;
    } 
/** ==== Lista de productores ================================================================  */
    public function listCustomersOwn($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listCustomersOwn($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"cus_id":"0"}]';	
        }
        echo $res;
    } 

/** ==== Lista los descuentos ================================================================  */
    public function listDiscounts($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listDiscounts($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"dis_id":"0"}]';	
        }
        echo $res;
    } 

/** ==== Lista los tipos de proyectos ========================================================  */
    public function listProjectsType($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProjectsType($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"pjt_id":"0"}]';	
        }
        echo $res;

    } 
 
/** ==== Lista los tipos de llamados =========================================================  */
    public function listProjectsTypeCalled($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProjectsTypeCalled($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"pjttc_id":"0"}]';	
        }
        echo $res;
    } 

/** ==== Lista los productos =================================================================  */
    public function listProducts($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProducts($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"prd_id":"0"}]';	
        }
        echo $res;
    } 

    // Lista los productos con Subarrendo
    public function listProductsSub($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProductsSub($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"prd_id":"0"}]';	
        }
        echo $res;
    } 

/** ==== Lista los comentarios del proyecto ==================================================  */
    public function listComments($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listComments($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"com_id":"0"}]';	
        }
        echo $res;
    } 

/** ==== Lista los relacionados al producto ==================================================  */
    public function listProductsRelated($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProductsRelated($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"prd_id":"0"}]';	
        }
        echo $res;
    } 


    /** ==== Lista los relacionados al producto ==================================================  */
    public function listProductsRelatedPk($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProductsRelated($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);
        } else {
            $res =  '[{"prd_id":"0"}]';
        }
        echo $res;
    }

    /** ==== Lista los relacionados al producto ==================================================  */
    public function listChangeProd($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listChangeProd($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);
        } else {
            $res =  '[{"prd_id":"0"}]';
        }
        echo $res;
    }


/** ==== Lista los proyectos en donde se encuentra un producto ===============================  */
    public function stockProducts($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->stockProducts($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"prd_name":"0"}]';	
        }
        echo $res;
    } 

/** ==== Guarda el comentario ================================================================  */
    public function InsertComment($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->InsertComment($request_params, $params);

        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"com_id":"0"}]';	
        }
        echo $res;
        
    } 

/** ==== Valida existencia de dias de viaje ==================================  */
    public function getExistTrip($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->getExistTrip($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"cus_id":"0"}]';	
        }
        echo $res;
    } 

/** ==== Lista los relacionados al producto ==================================================  */
public function getNewProdChg($request_params)
{
    $params =  $this->session->get('user');
    $result = $this->model->updateNewProdChg($request_params);
    // echo $result;
    echo json_encode($result ,JSON_UNESCAPED_UNICODE);
} 

/** ==== Actualiza datos del proyecto ========================================================  */
    public function UpdateProject($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->UpdateProject($request_params);
        echo $result;
    } 

/** ==== Actualiza las fechas del proyecto ===================================================  */
    public function UpdatePeriodProject($request_params)
    {
        /* $params =  $this->session->get('user');
        $result = $this->model->UpdatePeriodProject($request_params);
        echo $result; */
    }    

/** ==== Guarda nueva version ================================================================  */
    public function SaveVersion($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->SaveVersion($request_params);
        echo $result;
    } 

/** ==== Promueve proyecto ===================================================================  */
    public function PromoteProject($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->PromoteProject($request_params);
        echo $result;
    } 

/** ==== Promueve el presupuesto a proyecto ==================================================  */
    public function promoteToProject($request_params)
    {   
        $params =  $this->session->get('user');
        $result = $this->model->promoteToProject($request_params);
        echo $result;
    } 

/** ==== Cuenta el numero de productos que se encuentran en pendiente ========================  */
    public function countPending($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->countPending($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"counter":"0"}]';	
        }
        echo $res;
    } 


/** ==== Actualiza la tabla concentradora del contenido del proyecto =========================  */
    public function updateMice($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->updateMice($request_params);
        echo $result;
    }

/** ==== Actualiza el ordenamiento de productos del proyecto ================================  */
    public function updateOrder($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->updateOrder($request_params);
        echo $result;
    }

/** ==== Agrega producto a la tabla concentradora ============================================  */
    public function AddProductMice($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->AddProductMice($request_params);
        echo $result;
    }

/** ==== Actualiza contenido de la version actual ============================================  */
    public function SaveBudget($request_params)
    {
        $verId     = $request_params['verId'];
        $pjtId     = $request_params['pjtId'];
        $action    = $request_params['action'];
        $discount  = $request_params['discount'];

        if ($action == 'ACT'){
            //ACT convierte una version activa a una version master
            $version            = $this->model->settingMasterVersion($pjtId, $verId, $discount);
            $projectVersion     = $this->model->settingProjectVersion($pjtId, $verId);

            $periods            = $this->model->cleanPeriods($pjtId);
            $series             = $this->model->restoreSeries($pjtId);
            $detail             = $this->model->cleanDetail($pjtId);

            $projectContent     = $this->model->settingProjectContent($pjtId, $verId);
            $result             = $this->model->getProjectVersion($pjtId);
            $dateproject        = $this->model->saveDateProject($pjtId);
            $response           = $this->setSeries($result);
            $resReorder = $this->reOrdenList($verId);
            $opt1='Opcion1';
        } else {
            //MST actualiza los datos de una version maestra 
            $projectDiscount    = $this->model->settingDiscountVersion($pjtId, $verId, $discount);
            $projectVersion     = $this->model->settingProjectVersion($pjtId, $verId);
            $projectContent     = $this->model->settingProjectContent($pjtId, $verId);
            $result             = $this->model->getVersionMice($pjtId);
            $dateproject        = $this->model->saveDateProject($pjtId);
            $response           = $this->updateSeries($result);
            $resReorder = $this->reOrdenList($verId);
            $opt1='Opcion2';
        }

        echo $verId . '|'. $pjtId . '|'. $opt1 . '|'. $response;

    }

    public function updatePeriods($periods, $result){
        while($row = $result->fetch_assoc()){
            $periodId = $row["pjtpd_id"];
            $dtini    = $periods["dtinic"];
            $dtfin    = $periods["dtfinl"];

            $params = array(
                'prdpId' => $periodId,
                'dtinic' => $dtini,
                'dtfinl' => $dtfin,
            );
            $update = $this->model-> UpdatePeriods($params);

        }
        return $update;
    }

/** ==== Actualiza las series y sus productos relacionados  ==================================  */
   
    public function setSeries($result)
    {
        while($row = $result->fetch_assoc()){
            $dtstar = $row["pjt_date_start"];
            $dybase = $row["pjtcn_days_base"];
            $dycost = $row["pjtcn_days_cost"];
            $dytrip = $row["pjtcn_days_trip"] / 2;
            $dytest = $row["pjtcn_days_test"];
            $quanty = $row["pjtcn_quantity"];
            $prodId = $row["prd_id"];
            $pjetId = $row["pjtvr_id"];
            $dyinic = $dytrip + $dytest;
            $dyfinl = $dytrip + $dybase;
            $dtinic = date('Y-m-d',strtotime($dtstar . '-'. $dyinic .' days'));
            $dtfinl = date('Y-m-d',strtotime($dtstar . '+'. ($dyfinl-1) .' days'));

            $bdgsku = $row["pjtcn_prod_sku"];
            $bdgnme = $row["pjtcn_prod_name"];
            $bdgprc = $row["pjtcn_prod_price"];
            $bdglvl = $row["pjtcn_prod_level"];
            $dsbase = $row["pjtcn_discount_base"];
            $dstrip = $row["pjtcn_discount_trip"];
            $dstest = $row["pjtcn_discount_test"];
            $bdgIns = $row["pjtcn_insured"];
            $prdexp = $row["srv_id"];
            $versId = $row["ver_id"];

            $ttlqty = $prdexp == '2'? $quanty: 1;
            $quanty = $prdexp == '2'? 1: $quanty;

            
            if ( $bdglvl == 'P' || $bdglvl == 'A'){
                for ($i = 1; $i<=$quanty; $i++){
                    
                    $params = array(
                        'pjetId' => $pjetId, 
                        'prodId' => $prodId, 
                        'dtinic' => $dtinic, 
                        'dtfinl' => $dtfinl,
                        'versId' => $versId,
                        'detlId' => 0,
                    );
                    $serie = $this->model->SettingSeries($params);
                    $typeAsigned = $this->model->getTypeAsignedSerie($serie);
                    if ($typeAsigned == 'PF') {
                        $accesorios = $this->ProcessSeriesAccesoriesFijos($params, $serie);
                    }elseif ($typeAsigned == 'PV') {
                        $accesorios = $this->ProcessSeriesAccesoriesVirtuales($params);
                    }
                }
            } else if ( $bdglvl == 'K' ){  // AÑADIR LA CANTIDAD QUE SE REQUIERE POR CADA PRODUCTO DEL PAQUETE
                for ($i = 1; $i<=$quanty; $i++){
                    $products = $this->model->GetProducts($prodId);
                    while($acc = $products->fetch_assoc()){

                        $pkpdId =  $acc["prd_id"];
                        $pkpdNm =  $acc["prd_name"];
                        $pkpdPc =  $acc["prd_price"];
                        $pkqty =  $acc["pck_quantity"];

                        if($pkqty>0){
                            for ($j=0; $j < $pkqty; $j++) { 
                                $prodparams = array(
                                    'pjetId' => $pjetId, 
                                    'prodId' => $pkpdId, 
                                    'dtinic' => $dtinic, 
                                    'dtfinl' => $dtfinl,
                                    'detlId' => 0,
                                );
                                $serie = $this->model->SettingSeries($prodparams);
                                $typeAsigned = $this->model->getTypeAsignedSerie($serie);
                                if ($typeAsigned == 'PF' && $serie != 0) {
                                    $accesorios = $this->ProcessSeriesAccesoriesFijos($prodparams, $serie);
                                }elseif ($typeAsigned == 'PV' && $serie != 0) {
                                    $accesorios = $this->ProcessSeriesAccesoriesVirtuales($prodparams);
                                }
                            }
                        } 
                    }
                }
            }
            /* if ($lvlas == 'PF'){
                for ($i = 1; $i<=$quanty; $i++){

                    $params = array(
                        'pjetId' => $pjetId,
                        'prodId' => $prodId,
                        'dtinic' => $dtinic,
                        'dtfinl' => $dtfinl,
                        'bdgsku' => $bdgsku,
                        'bdgnme' => $bdgnme,
                        'bdgprc' => $bdgprc,
                        'bdglvl' => $bdglvl,
                        'bdgqty' => $ttlqty,
                        'dybase' => $dybase,
                        'dycost' => $dycost,
                        'dsbase' => $dsbase,
                        'dytrip' => $dytrip,
                        'dstrip' => $dstrip,
                        'dytest' => $dytest,
                        'dstest' => $dstest,
                        'bdgIns' => $bdgIns,
                        'versId' => $versId,
                        'detlId' => 0,
                        'type_asigned' => 'PF',
                    );
                    $detlId = $this->model->SettingSeries($params);
                    $serId=$detlId;
                    
                    if ($serId != null) {
                        $seriesAcc = $this->ProcessSeriesAccesories($params, $serId);
                    }

                }
            }else if ($lvlas == 'PV'){
                for ($i = 1; $i<=$quanty; $i++){
                    
                    $params = array(
                        'pjetId' => $pjetId,
                        'prodId' => $prodId,
                        'dtinic' => $dtinic,
                        'dtfinl' => $dtfinl,
                        'bdgsku' => $bdgsku,
                        'bdgnme' => $bdgnme,
                        'bdgprc' => $bdgprc,
                        'bdglvl' => $bdglvl,
                        'bdgqty' => $ttlqty,
                        'dybase' => $dybase,
                        'dycost' => $dycost,
                        'dsbase' => $dsbase,
                        'dytrip' => $dytrip,
                        'dstrip' => $dstrip,
                        'dytest' => $dytest,
                        'dstest' => $dstest,
                        'bdgIns' => $bdgIns,
                        'versId' => $versId,
                        'detlId' => 0,
                        'type_asigned' => 'PV',
                    );
                    $detlId = $this->model->SettingSeries($params);

                    $products = $this->model->GetProducts($prodId);

                    while($prdPck = $products->fetch_assoc()){
                        $pkpdId =  $prdPck["prd_id"];
                        $pkpdNm =  $prdPck["prd_name"];
                        $pkpdPc =  $prdPck["prd_price"];
                        $pkqty  =  $prdPck["pck_quantity"];

                        for ($j = 1; $j <= $pkqty; $j++){
                            
                            $prdparam = array(
                                'prodId' => $pkpdId, 
                                'pjetId' => $pjetId, 
                                'dtinic' => $dtinic, 
                                'dtfinl' => $dtfinl, 
                                'detlId' => 0,
                                'type_asigned' => 'AV',
                            );
                           
                            $detlId = $this->model->SettingSeries($prdparam);
                        }
                        
                    }
                }
            }else if ($lvlas == 'KP'){
                for ($i = 1; $i<=$quanty; $i++){
                    $products = $this->model->GetProducts($prodId);
                    while($prd = $products->fetch_assoc()){

                        $pkpdId =  $prd["prd_id"];
                        $pkpdNm =  $prd["prd_name"];
                        $pkpdPc =  $prd["prd_price"];
                        $pkqty =  $prd["pck_quantity"];
                        $type =  $prd["prd_type_asigned"];

                        for ($k=1; $k<=$pkqty; $k++){
                            // Los productos pueden ser Virtuales o Fijos. 
                            if ($type == 'PV') {
                                $prdctsAcc = $this->model->GetProducts($pkpdId);

                                $params = array(
                                    'prodId' => $pkpdId, 
                                    'pjetId' => $pjetId,
                                    'dtinic' => $dtinic, 
                                    'dtfinl' => $dtfinl,
                                    'detlId' => 0,
                                    'type_asigned' => 'PV',
                                );
                                $serie      = $this->model->SettingSeries($params);

                                while($acc = $prdctsAcc->fetch_assoc()){
                
                                    $prdAccId =  $acc["prd_id"];
                                    $prdAccNm =  $acc["prd_name"];
                                    $prdAccPc =  $acc["prd_price"];
                                    $prdAccqty =  $acc["pck_quantity"];

                                    if($prdAccqty>0){
                                        for ($j=0; $j < $prdAccqty; $j++) { 
                                            $prodparams = array(
                                                'prodId' => $prdAccId, 
                                                'pjetId' => $pjetId,
                                                'dtinic' => $dtinic, 
                                                'dtfinl' => $dtfinl,
                                                'detlId' => 0,
                                                'type_asigned' => 'AV',
                                            );
                                            $detlId = $this->model->SettingSeries($prodparams);
                                        }
                                    } 
                                }
                            }elseif ($type == 'PF') {
                                
                                //for ($i=0; $i < $pkqty; $i++) { 
                                    $params = array(
                                        'prodId' => $pkpdId, 
                                        'pjetId' => $pjetId,
                                        'dtinic' => $dtinic, 
                                        'dtfinl' => $dtfinl,
                                        'detlId' => 0,
                                        'type_asigned' => 'PF',
                                    );
                                    $serie      = $this->model->SettingSeries($params);
                                    if ($serie != null) {
                                        $seriesAcc = $this->ProcessSeriesAccesories($params, $serie);
                                    }
                                    
                                //}
                                
                            }else{ // sino cumple con alguna de esas caracteriticas, significa que es un producto individual
                                
                                $prodparams = array(
                                    'prodId' => $pkpdId, 
                                    'pjetId' => $pjetId,
                                    'dtinic' => $dtinic, 
                                    'dtfinl' => $dtfinl,
                                    'detlId' => 0,
                                    'type_asigned' => 'PI',
                                );
                                $detlId = $this->model->SettingSeries($prodparams);
                                    
                            }
                            
                        }
                    }
                }
            }else{
                for ($j = 1; $j<=$quanty; $j++){
                    $params = array(
                        'pjetId' => $pjetId,
                        'prodId' => $prodId,
                        'dtinic' => $dtinic,
                        'dtfinl' => $dtfinl,
                        'bdgsku' => $bdgsku,
                        'bdgnme' => $bdgnme,
                        'bdgprc' => $bdgprc,
                        'bdglvl' => $bdglvl,
                        'bdgqty' => $ttlqty,
                        'dybase' => $dybase,
                        'dycost' => $dycost,
                        'dsbase' => $dsbase,
                        'dytrip' => $dytrip,
                        'dstrip' => $dstrip,
                        'dytest' => $dytest,
                        'dstest' => $dstest,
                        'bdgIns' => $bdgIns,
                        'versId' => $versId,
                        'detlId' => 0,
                        'type_asigned' => 'PI',
                    );
                    $detlId = $this->model->SettingSeries($params);
                    $serId=$detlId;
                }
            } */
        }
        return 1;
    } 
    public function ProcessSeriesAccesoriesFijos($params, $serie){
        $result = $this->model->getAccesorios($serie);
        while($row = $result->fetch_assoc()){
            $serId      = $row["ser_id"];
            $prdId      = $row["prd_id"];
            $pdIdAc     = $row["prd_id_acc"];
            $dtinic     = $params["dtinic"];
            $dtfinl     = $params["dtfinl"];
            $pjetId     = $params["pjetId"];
    
            $accesoryData=array(
                'pjetId' => $pjetId, 
                'prodId' => $prdId, 
                'dtinic' => $dtinic, 
                'dtfinl' => $dtfinl,
                'detlId' => 0,
                'serId'  => $serId,
                'type_asigned' => 'A',
            );
            $seriesAcc  = $this->model->SettingSeriesAcce($accesoryData);
        }
        return 1;
    }
    
    public function ProcessSeriesAccesoriesVirtuales($params){
        $prdId      = $params["prodId"];
        $dtinic     = $params["dtinic"];
        $dtfinl     = $params["dtfinl"];
        $pjetId     = $params["pjetId"];
    
       $products = $this->model->GetProducts($prdId);
    
        while($acc = $products->fetch_assoc()){
    
            $pkpdId =  $acc["prd_id"];
            $pkpdNm =  $acc["prd_name"];
            $pkpdPc =  $acc["prd_price"];
            $pkqty =  $acc["pck_quantity"];
    
            if($pkqty>0){
                for ($k=0; $k < $pkqty; $k++) { 
                    $prodparams = array(
                        'pjetId' => $pjetId, 
                        'prodId' => $pkpdId, 
                        'dtinic' => $dtinic, 
                        'dtfinl' => $dtfinl,
                        'detlId' => 0,
                        'type_asigned' => 'AV',
                    );
                    $detlId = $this->model->SettingSeries($prodparams);
                }
            } 
        }
        return 1;
    }
/** ==== Actualiza los productos de la version maestra  ======================================  */
    public function updateSeries($result)
    {
        while($row = $result->fetch_assoc()){
            $action = $row["pjtvr_action"];
            $qtyAct = $row["pjtvr_quantity"];
            $qtyAnt = $row["pjtvr_quantity_ant"];
            $daysAct = $row["pjtvr_days_base"];
            $daysAnt = $row["pjtvr_days_base_ant"];
            $qtyAct = intval($qtyAct);
            $qtyAnt = intval($qtyAnt);
            $daysAct = intval($daysAct);
            $daysAnt = intval($daysAnt);
            $updQty = $action . $daysAct . $daysAnt .$row["pjtvr_id"];
            
            $param = array(
                'prodId' => $row["prd_id"],
                'pjetId' => $row["pjtvr_id"],
                'prdlvl' => $row['prd_level'],
                'servId' => $row['srv_id'],
                'dtinic' => $row['date_start'],
                'dtfinl' => $row['date_end'],
            );
            // print_r( $param);

            switch ($action){
                case 'U' :
                    if ($qtyAct > $qtyAnt){
                        $dif = $qtyAct - $qtyAnt;
                        for ($i=1; $i <= $dif; $i++){
                            //echo 'Update ';
                            $updQty = $this-> AddQuantityDetail($param);
                        }
                    } else if ($qtyAct < $qtyAnt){
                        $dif = $qtyAnt - $qtyAct;
                        for ($i=1; $i <= $dif; $i++){
                            $updQty = $this-> KillQuantityDetail($param);
                        }
                    }
                    
                    break;
                case 'D':
                    for ($i=1; $i <= $qtyAct; $i++){
                        $updQty = $this-> KillQuantityDetail($param);
                    }
                    break;
                case 'A':
                    for ($i=1; $i <= $qtyAct; $i++){
                        $updQty = $this-> AddQuantityDetail($param);
                    }
                    break;
                default:
            }
            if ($daysAct != $daysAnt) {
                $periodparams = array(
                    'prodId' => $row["prd_id"],
                    'prdsct' => $row['pjtvr_section'],
                    'dtinic' => $row['date_start'],
                    'dtfinl' => $row['date_end'],
                );

                $result2 = $this->model-> getperiods($periodparams);
                $upd = $this->updatePeriods($periodparams, $result2);
            } 
            $res = $row['prd_id'];
        }
        return 1;
    }

/** ==== Importa proyecto ====================================================================  */
    public function importProject($request_params)
    {
        $params =  $this->session->get('user');
        $par            = $this->model->SaveVersion($request_params);

        
        $pack           = explode('|', $par);
        $verId          = $pack[0];
        $pjtId          = $pack[1];
        $pjtIdo         = $request_params['pjtIdo'];

        $mice = $this->model->importProject($pjtIdo, $pjtId, $verId);
        $periods        = $this->model->cleanPeriods($pjtId);
        $series         = $this->model->restoreSeries($pjtId);
        $detail         = $this->model->cleanDetail($pjtId);
        $projectVersion = $this->model->settingProjectVersion($pjtId, $verId);
        $projectcontent = $this->model->settingProjectContent($pjtId, $verId);
        $result         = $this->model->getProjectVersion($pjtId);
        $response       = $this->setSeries($result);

        echo $verId . '|'. $pjtId;
    } 


/** ==== Guarda contenido de la nueva version ================================================  */
    public function SaveBudgetAs($request_params)
    {

        $params         = $this->session->get('user');
        $par            = $this->model->SaveVersion($request_params);
        $pack           = explode('|', $par);
        $verId          = $pack[0];
        $pjtId          = $pack[1];
        $group = explode('|',$params);

        $user = $group[0];
        $name = $group[2];
        $otrov = $group[1];

        $periods        = $this->model->cleanPeriods($pjtId);
        $series         = $this->model->restoreSeries($pjtId);
        $detail         = $this->model->cleanDetail($pjtId);
        $projectVersion = $this->model->settinProjectVersion($pjtId, $verId);
        $projectcontent = $this->model->settingProjectContent($pjtId, $verId);
        $result         = $this->model->getProjectVersion($pjtId);
        $response       = $this->setSeries($result);
        $dateproject    = $this->model->saveDateProject($pjtId);  // comentado por jjr
        $resReorder = $this->reOrdenList($verId); // *** Edna V2
        // echo $verId . '|'. $pjtId . '|'. $user . '|'. $name . '|'. $otrov . '|-Paso '. $Locpaso;
        echo $verId . '|'. $pjtId . '|'. $dateproject . '|'. $response;

    } 


/** ==== Agrega una nueva serie al detalle del proyecto ======================================  */
    public function AddQuantityDetail($params)
    {
        $prodId =  $params['prodId'];
        $pjetId =  $params['pjetId'];
        $prdLvl =  $params['prdlvl'];
        $servId =  $params['servId'];
        $dtinic =  $params['dtinic'];
        $dtfinl =  $params['dtfinl'];

        if ($servId == '1'){
            /* if ($lvlas == 'PF'){
                
                $prdparam = array(
                    'prodId' => $prodId, 
                    'pjetId' => $pjetId, 
                    'dtinic' => $dtinic, 
                    'dtfinl' => $dtfinl, 
                    'detlId' => 0,
                    'type_asigned' => 'PF',
                );
                
                $detlId = $this->model->SettingSeries($prdparam);
                
                if ($detlId) {
                    $seriesAcc = $this->ProcessSeriesAccesories($prdparam, $detlId);
                }
                
            }else if ($lvlas == 'PV'){
                
                $prdparam = array(
                    'prodId' => $prodId, 
                    'pjetId' => $pjetId, 
                    'dtinic' => $dtinic, 
                    'dtfinl' => $dtfinl, 
                    'detlId' => 0,
                    'type_asigned' => 'PV',
                );
                
                $detlId = $this->model->SettingSeries($prdparam);

                $products = $this->model->GetProducts($prodId);
                while($pkt = $products->fetch_assoc()){
                    $kprodId = $pkt["prd_id"];
                    $kpjetId = $pjetId;
                    $type =  $pkt["prd_type_asigned"];
                    $pkqty =  $pkt["pck_quantity"];

                    for ($i=0; $i < $pkqty; $i++) { 
                        $prdparam = array(
                            'prodId' => $kprodId, 
                            'pjetId' => $kpjetId, 
                            'dtinic' => $dtinic, 
                            'dtfinl' => $dtfinl, 
                            'detlId' => 0,
                            'type_asigned' => 'AV',
                        );
                       
                        $detlId = $this->model->SettingSeries($prdparam);
                    }
                    
                }
            }else if ($lvlas == 'KP'){
                
                $products = $this->model->GetProducts($prodId);
                while($prd = $products->fetch_assoc()){

                    $pkpdId =  $prd["prd_id"];
                    $pkpdNm =  $prd["prd_name"];
                    $pkpdPc =  $prd["prd_price"];
                    $pkqty =  $prd["pck_quantity"];
                    $type =  $prd["prd_type_asigned"];

                    
                    for ($k=1; $k<=$pkqty; $k++){
                        // Los productos pueden ser Virtuales o Fijos. 
                        if ($type == 'PV') {
                            $prdctsAcc = $this->model->GetProducts($pkpdId);

                            $params = array(
                                'prodId' => $pkpdId, 
                                'pjetId' => $pjetId,
                                'dtinic' => $dtinic, 
                                'dtfinl' => $dtfinl,
                                'detlId' => 0,
                                'type_asigned' => 'PV',
                            );
                            $serie      = $this->model->SettingSeries($params);

                            while($acc = $prdctsAcc->fetch_assoc()){
            
                                $prdAccId =  $acc["prd_id"];
                                $prdAccNm =  $acc["prd_name"];
                                $prdAccPc =  $acc["prd_price"];
                                $prdAccqty =  $acc["pck_quantity"];

                                if($prdAccqty>0){
                                    for ($i=0; $i < $prdAccqty; $i++) { 
                                        $prodparams = array(
                                            'prodId' => $prdAccId, 
                                            'pjetId' => $pjetId,
                                            'dtinic' => $dtinic, 
                                            'dtfinl' => $dtfinl,
                                            'detlId' => 0,
                                            'type_asigned' => 'AV',
                                        );
                                        $detlId = $this->model->SettingSeries($prodparams);
                                    }
                                } 
                            }
                        }elseif ($type == 'PF') {
                            
                            //for ($i=0; $i < $pkqty; $i++) { 
                                $params = array(
                                    'prodId' => $pkpdId, 
                                    'pjetId' => $pjetId,
                                    'dtinic' => $dtinic, 
                                    'dtfinl' => $dtfinl,
                                    'detlId' => 0,
                                    'type_asigned' => 'PF',
                                );
                                $serie      = $this->model->SettingSeries($params);
                                if ($serie != null) {
                                    $seriesAcc = $this->ProcessSeriesAccesories($params, $serie);
                                }
                                
                            //}
                            
                        }else{ // sino cumple con alguna de esas caracteriticas, significa que es un producto individual
                            
                            $prodparams = array(
                                'prodId' => $pkpdId, 
                                'pjetId' => $pjetId,
                                'dtinic' => $dtinic, 
                                'dtfinl' => $dtfinl,
                                'detlId' => 0,
                                'type_asigned' => 'PI',
                            );
                            $detlId = $this->model->SettingSeries($prodparams);
                                
                        }
                        
                    }
                }
            }else{
                $prdparam = array(
                    'prodId' => $prodId, 
                    'pjetId' => $pjetId, 
                    'dtinic' => $dtinic, 
                    'dtfinl' => $dtfinl, 
                    'detlId' => 0,
                    'type_asigned' => 'PI',
                );
                $detlId = $this->model->SettingSeries($prdparam);
            } */

            if ($prdLvl == 'P' || $prdLvl == 'P'){
                
                $params = array(
                    'pjetId' => $pjetId, 
                    'prodId' => $prodId, 
                    'dtinic' => $dtinic, 
                    'dtfinl' => $dtfinl,
                    'detlId' => 0,
                );
                $serie = $this->model->SettingSeries($params);
                $typeAsigned = $this->model->getTypeAsignedSerie($serie);
                if ($typeAsigned == 'PF' && $serie != 0) {
                    $accesorios = $this->ProcessSeriesAccesoriesFijos($params, $serie);
                }elseif ($typeAsigned == 'PV' && $serie != 0) {
                    $accesorios = $this->ProcessSeriesAccesoriesVirtuales($params);
                }
            
            } else if ( $prdLvl == 'K' ){  // AÑADIR LA CANTIDAD QUE SE REQUIERE POR CADA PRODUCTO DEL PAQUETE
                
                    $products = $this->model->GetProducts($prodId);
                    while($acc = $products->fetch_assoc()){

                        $pkpdId =  $acc["prd_id"];
                        $pkpdNm =  $acc["prd_name"];
                        $pkpdPc =  $acc["prd_price"];
                        $pkqty =  $acc["pck_quantity"];

                        if($pkqty>0){
                            for ($j=0; $j < $pkqty; $j++) { 
                                $prodparams = array(
                                    'pjetId' => $pjetId, 
                                    'prodId' => $pkpdId, 
                                    'dtinic' => $dtinic, 
                                    'dtfinl' => $dtfinl,
                                    'detlId' => 0,
                                );
                                $serie = $this->model->SettingSeries($prodparams);
                                $typeAsigned = $this->model->getTypeAsignedSerie($serie);
                                if ($typeAsigned == 'PF' && $serie != 0) {
                                    $accesorios = $this->ProcessSeriesAccesoriesFijos($prodparams, $serie);
                                }elseif ($typeAsigned == 'PV' && $serie != 0) {
                                    $accesorios = $this->ProcessSeriesAccesoriesVirtuales($prodparams);
                                }
                            }
                        } 
                    
                }
            }

        } elseif ($servId == '2'){

        }
        return 1;
    }


/** ==== Elimina la serie correspondiente en el detalle del proyecto =========================  */
    public function KillQuantityDetail($request_params)
    {
        $lvl = $request_params['prdlvl'];
        if ($lvl == 'K') {
            
            $prodId =  $request_params['prodId'];
            $pjetId =  $request_params['pjetId'];
            $dtinic =  $request_params['dtinic'];
            $dtfinl =  $request_params['dtfinl'];

            $prdcts = $this->model->GetProducts($prodId);
            while($prd = $prdcts->fetch_assoc()){
            
                $prdId =  $prd["prd_id"];
                $prdqty =  $prd["pck_quantity"];

                if($prdqty>0){
                    for ($i=0; $i < $prdqty; $i++) { 
                        $prodparams = array(
                            'prodId' => $prdId, 
                            'pjetId' => $pjetId,
                            'dtinic' => $dtinic, 
                            'dtfinl' => $dtfinl,
                            'detlId' => 0,
                        );
                        $result = $this->model->KillQuantityDetailPackage($prodparams);
                        $res = $result;
                    }
                } 
            }
        }else{
            $params =  $this->session->get('user');
            $result = $this->model->KillQuantityDetail($request_params);
            $res = $result;
        }
        

        /* if ($request_params['type'] == 'PV') {
            $result = $this->model->KillQuantityDetailVirtual($request_params);
           
            $res = $result;
        }elseif ($request_params['type'] == 'PF') {
            $result = $this->model->KillQuantityDetailFijo($request_params);
            $res = $result;
        }elseif ($request_params['type'] == 'KP') {

            $prodId =  $request_params['prodId'];
            $pjetId =  $request_params['pjetId'];
            $dtinic =  $request_params['dtinic'];
            $dtfinl =  $request_params['dtfinl'];

            $prdcts = $this->model->GetProducts($prodId);
            while($prd = $prdcts->fetch_assoc()){
            
                $prdId =  $prd["prd_id"];
                $prdqty =  $prd["pck_quantity"];

                if($prdqty>0){
                    for ($i=0; $i < $prdqty; $i++) { 
                        $prodparams = array(
                            'prodId' => $prdId, 
                            'pjetId' => $pjetId,
                            'dtinic' => $dtinic, 
                            'dtfinl' => $dtfinl,
                            'detlId' => 0,
                        );
                        $result = $this->model->KillQuantityDetailPackage($prodparams);
                        $res = $result;
                    }
                } 
            }
            
        }else{
            $result = $this->model->KillQuantityDetail($request_params);
            $res = $result;
        } */
        
        return $res;
    }

/** ==========================================================================================  */

public function reOrdenList($request_params)
{
    $params =  $this->session->get('user');
    $result = $this->model->listReordering($request_params);

    $valnew=1;
    while($row = $result->fetch_assoc())
    {
        $prdsku = $row["pjtcn_prod_sku"];
        $docsec = $row["pjtcn_section"];
        $verid = $row["pjtvr_id"];
        $docord = $row["pjtcn_order"];
        $paramup = array(
            'valnew' => $valnew,
            'verid' => $verid,
        );

        $bandReOrder = $this->model->upReorderingProducts($paramup);
        $valnew=$valnew + 1;
    }
    // echo $bandReOrder ; 
} 
public function getLocationType($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->getLocationType();
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"loc_id":"0"}]';	
        }
        echo $res;
    } 
    // GUARDAR LOCACIONES EN EL PROYECTO. ***ED
    public function SaveLocations($request_params){
        $params =  $this->session->get('user');
        $result = $this->model->SaveLocations($request_params);
        $res = $result;
        echo $res;
    }
// LISTAR LOS ESTADOS DE LA REPUBLICA ***ED
    public function getEdosRepublic($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->getEdosRepublic($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"edos_id":"0"}]';	
        }
        echo $res;
    } 
    // LISTAR LOS DATOS DE LAS LOCACIONES Y ESTADOS GUARDADOS POR PROYECTO
    public function ListLocationsEdos($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->ListLocationsEdos($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"loc_id":"0"}]';	
        }
        echo $res;
    } 

}