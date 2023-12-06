<?php
    defined('BASEPATH') or exit('No se permite acceso directo');
    require_once ROOT . FOLDER_PATH . '/app/models/PrePayments/PrePaymentsModel.php';
    require_once LIBS_ROUTE .'Session.php';

class PrePaymentsController extends Controller
{
    private $session;
    public $model;


    public function __construct()
    {
        $this->model = new PrePaymentsModel();
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

    
    // LISTA LOS PROYECTOS ACTIVOS
    public function listProyects($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProyects($request_params['store']);
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
// Lista los productos
    public function listRegisters($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listRegisters($request_params);
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
    //
    public function listTypeMov($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listTypeMov($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"mst_id":"0"}]';	
        }
        echo $res;
    } 

    // Lista las clientes
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
			$res =  '[{"cat_id":"0"}]';	
		}
		echo $res;
	}

    
    public function listChangeReasons($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listChangeReasons($request_params);
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

// Lista los proveedores de subarrendo
    public function listSuppliers($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listSuppliers($request_params['store']);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"sup_id":"0"}]';	
        }
        echo $res;
    } 	 

// Lista los monedas
    public function listCoins($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listCoins($request_params['store']);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"cin_id":"0"}]';	
        }
        echo $res;
    } 	 
// Lista los monedas
public function listDataProyects($request_params)
{
    $params =  $this->session->get('user');
    $result = $this->model->listDataProyects($request_params);
    $i = 0;
    while($row = $result->fetch_assoc()){
        $rowdata[$i] = $row;
        $i++;
    }
    if ($i>0){
        $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
    } else {
        $res =  '[{"prp_id":"0"}]';	
    }
    echo $res;
} 	 

// Lista los monedas
    public function listStores($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listStores($request_params['store']);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"str_id":"0"}]';	
        }
        echo $res;
    } 	 

// Agrega los seriales de los productos para subarrendo
    public function addSerie($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->addSerie($request_params);
        echo $result;
    } 	


// Agrega los productos subarrendados
    public function addSubletting($request_params)
    {
        $params =  $this->session->get('user');
        $result1 = $this->model->addSubletting($request_params);

        $params =  $this->session->get('user');
        $result2 = $this->model->SaveExchange($request_params, $params);

        $params =  $this->session->get('user');
        $item = $this->model->SechingProducts($request_params);
        $num_items = $item->fetch_object();

        if ($num_items->items > 0){
            echo 'update';
            // actualiza la cantidad en el almacen destino
            $result3 = $this->model->UpdateProducts($request_params);
        } else {
            echo 'insert';
            //agrega la relación almacen - producto
            $result3 = $this->model->InsertProducts($request_params);
        }
        $res = $result3;
        echo $res;
    } 	
    

// Proceso de series de subarrendos    
    public function saveSubletting($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->checkSerie($request_params);
        $skus = $result->fetch_object();
        $sku  = $skus->skuCount; 

        if ($sku == 0){
            $pjtId = $this->model->addNewSku($request_params);
            $setData = $this->model->getPjtDetail($pjtId);
            $i = 0;
            while($row = $setData->fetch_assoc()){
                $rowdata[$i] = $row;
                $i++;
            }
            if ($i>0){
                $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
            } else {
                $res =  '[{"pjdt_id_id":"0"}]';	
            }
        } else {
            $res =  '[{"pjdt_id_id":"0"}]';	
        }
        echo $res;
    }

// Proceso de series de subarrendos    
    public function changeMaintain($request_params)
    {
        $params =  $this->session->get('user');
        $pjtId = $this->model->changeMaintain($request_params);

        $result = $this->model->getPjtDetail($pjtId);

        echo $pjtId;
    }

    function savePrePayment($request_params){
        $params =  $this->session->get('user');
        $result = $this->model->savePrePayment($request_params);
            
        echo $result;
    }

    public function insertPayAplied($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->insertPayAplied($request_params, $params);
        $res = $result;
        // $pjtId  = $this->model->PromoteProject($request_params);

        echo $res;
    } 
}