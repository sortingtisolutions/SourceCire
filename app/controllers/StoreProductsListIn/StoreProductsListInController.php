<?php
    defined('BASEPATH') or exit('No se permite acceso directo');
    require_once ROOT . FOLDER_PATH . '/app/models/StoreProductsListIn/StoreProductsListInModel.php';
    require_once LIBS_ROUTE .'Session.php';

class StoreProductsListInController extends Controller
{
    
    private $session;
    public $model;

    public function __construct()
    {
        $this->model = new StoreProductsListInModel();
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

// Lista los almacenes 
    // public function listStores($request_params)
    // {
    //   $params =  $this->session->get('user');
    //   $result = $this->model->listStores();
    //     $i = 0;
    //       while($row = $result->fetch_assoc()){
    //           $rowdata[$i] = $row;
    //           $i++;
    //       }
    //       if ($i>0){
    //           $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
    //       } else {
    //           $res =  '[{"str_id":"0"}]';	
    //       }
    //       echo $res;
    // }
 // Lista los almacenes
    public function listProducts($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProducts($request_params['store']);
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

// Lista los almacenes 
    public function listExchanges($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listExchanges($request_params['guid']);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
            //$res =  json_encode($rowdata, JSON_HEX_QUOT);
        } else {
            $res =  '[{"exc_id":"0"}]';	
        }
        echo $res;
    } 


// Guarda lista de productos en json 
    public function saveList($request_params)
    {
        $params = $this->session->get('user');
        $par = $request_params['par'];

        $dir = ROOT . FOLDER_PATH . '/app/views/StoreProductsList/StoreProductsFile.txt';

        $fileJson = fopen( $dir ,"w") or die("problema al escribir el archivo ");
        fwrite($fileJson, $par);
        fclose($fileJson);

    } 


}