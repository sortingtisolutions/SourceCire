<?php
    defined('BASEPATH') or exit('No se permite acceso directo');
    require_once ROOT . FOLDER_PATH . '/app/models/SeriestoProducts/SeriestoProductsModel.php';
    require_once LIBS_ROUTE .'Session.php';

class SeriestoProductsController extends Controller
{
    private $session;
    public $model;

    public function __construct()
    {
        $this->model = new SeriestoProductsModel();
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

// Lista las categorias
    public function listCategories()
    {
        $params =  $this->session->get('user');
        $result = $this->model->listCategories();
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

    public function listCategoriesAcc()
    {
        $params =  $this->session->get('user');
        $result = $this->model->listCategoriesAcc();
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
public function listProductsById($request_params)
{
    $params =  $this->session->get('user');
    $result = $this->model->listProductsById($request_params);
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

// Lista los productos relacionados al paquete
    public function listProductsPack($request_params)
    {
       
    }

// Lista los productos relacionados al paquete
    public function listSeriesProd($request_params)
    {
        $params =  $this->session->get('user');
        if ($request_params['opc'] == 1) {
            $result = $this->model->listSeriesProd($request_params['prdId']);
        }else{
            $result = $this->model->list_products($request_params['prdId']);
        }
        
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"ser_id":"0"}]';	
        }
        echo $res;
    }

// Lista los productos relacionados al paquete
public function listAccesorios($request_params)
{
    $params =  $this->session->get('user');
    $result = $this->model->listAccesorios($request_params);
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

// Lista los productos relacionados al paquete
public function listPrdAccesorios($request_params)
{
    $params =  $this->session->get('user');
    $result = $this->model->listPrdAccesorios($request_params);
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

// Lista de accesorios por id
public function getAccesoriesById($request_params)
{
    $params =  $this->session->get('user');
    $result = $this->model->getAccesoriesById($request_params);
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
// Lista de accesorios por id
public function getProdAccesoriesById($request_params)
{
    $params =  $this->session->get('user');
    $result = $this->model->getProdAccesoriesById($request_params);
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

    public function saveAccesorioByProducto($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->saveAccesorioByProducto($request_params);
        $res = json_encode($result,JSON_UNESCAPED_UNICODE) ;
        echo $res;
    }	

    public function saveAccesorioProducto($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->saveAccesorioProducto($request_params);
        $res = json_encode($result,JSON_UNESCAPED_UNICODE) ;
        echo $res;
    }	
    public function updateQuantityProds($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->updateQuantityProds($request_params);
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
public function deleteProduct($request_params)
{
    $params =  $this->session->get('user');
    if ($request_params['opc'] == 1) {
        $result = $this->model->deleteProductSer($request_params);
    }else{
        $result = $this->model->deleteProduct($request_params);
    }
    
    $res = $result;
    echo $res;
}

}