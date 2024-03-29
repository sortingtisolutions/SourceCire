<?php
    defined('BASEPATH') or exit('No se permite acceso directo');
    require_once ROOT . FOLDER_PATH . '/app/models/Packages/PackagesModel.php';
    require_once LIBS_ROUTE .'Session.php';

class PackagesController extends Controller
{
    
    private $session;
    public $model;

    public function __construct()
    {
        $this->model = new PackagesModel();
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

    // LISTAS LAS CATEGORIAS
    // public function listCategories()
    // {
    //     $params =  $this->session->get('user');
    //     $result = $this->model->listCategories();
    //     $i = 0;
    //     while($row = $result->fetch_assoc()){
    //         $rowdata[$i] = $row;
    //         $i++;
    //     }
    //     if ($i>0){
    //         $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
    //     } else {
    //         $res =  '[{"cat_id":"0"}]';	
    //     }
    //     echo $res;
    // }

// Lista las categorias
    // public function listSubCategories($request_params)
    // {
    //     $params =  $this->session->get('user');
    //     $result = $this->model->listSubCategories($request_params['catId']);
    //     $i = 0;
    //     while($row = $result->fetch_assoc()){
    //         $rowdata[$i] = $row;
    //         $i++;
    //     }
    //     if ($i>0){
    //         $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
    //     } else {
    //         $res =  '[{"sbc_id":"0"}]';	
    //     }
    //     echo $res;
    // }

// Lista de paquetes
    public function listPackages($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listPackages($request_params);
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

// Obtiene el Id correspondiente al paquete nuevo 
    public function lastIdSubcategory($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->lastIdSubcategory($request_params['sbcId']);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"nextId":""}]';	
        }
        echo $res;
    }

// Lista los productos relacionados al paquete
    public function listProducts()
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProducts();
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"prd_id":""}]';	
        }
        echo $res;
    }

// Lista los productos relacionados al paquete
    public function listProductsPack($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProductsPack($request_params['prdId']);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"prd_id":""}]';	
        }
        echo $res;
    }

// Guarda el paquete
    public function savePack($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->savePack($request_params);
        $res = $result;
        echo $res;
    }	

// Guarda producto del paquete
    public function SaveProduct($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->SaveProduct($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"prd_id":""}]';	
        }
        echo $res;
    }	

// Obtiene detalle del paquete
    public function detailPack($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->detailPack($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"prd_id":""}]';	
        }
        echo $res;
    }

    // Obtiene detalle del paquete
    public function deletePackages($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->deletePackages($request_params);
        $res = $result;
        echo $res;
    }
    
// Obtiene detalle del paquete
    public function deleteProduct($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->deleteProduct($request_params);
        $res = $result;
        echo $res;
    }
    
// Actualiza los datos del paquete
    public function updatePackage($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->updatePackage($request_params);
        $res = $result;
        echo $res;
    }

// Actualiza cantidad de productos en el paquete
    public function updateQuantityProds($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->updateQuantityProds($request_params);
        $res = $result;
        echo $res;
    }

}