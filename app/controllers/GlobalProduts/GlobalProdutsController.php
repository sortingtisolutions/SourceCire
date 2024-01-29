<?php
    defined('BASEPATH') or exit('No se permite acceso directo');
    require_once ROOT . FOLDER_PATH . '/app/models/GlobalProduts/GlobalProdutsModel.php';
    require_once LIBS_ROUTE .'Session.php';

class GlobalProdutsController extends Controller
{
	private $session;
	public $model;

	public function __construct()
	{
		$this->model = new GlobalProdutsModel();
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

	// Lista los proyectos
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
			$res =  '[{"cat_id":"0"}]';	
		}
		echo $res;
	}
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
	public function updateData($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->updateData($request_params);
		$res = $result ;
		echo $res;
    }	
	public function loadProcess($request_params)
	{
		$params =  $this->session->get('user');
		$result = $this->model->loadProcess($request_params);
		echo $result;
	}

	public function loadProcessAll($request_params)
	{
		$params =  $this->session->get('user');
		$result = $this->model->loadProcessAll($request_params);
		echo $result;
	}
	public function getNextSku($request_params)
    {
        //$params =  $this->session->get('user');
        $result = $this->model->getNextSku($request_params);
        
        echo $result;
    }	
	// Obtiene datos del proyecto seleccionado
	public function getSelectProject($request_params)
	{
		$params =  $this->session->get('user');
		$result = $this->model->getSelectProject($request_params);
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

	// Lista los proyectos
	public function UpdateSeriesToWork($request_params)
	{
		$params =  $this->session->get('user');
		$result = $this->model->UpdateSeriesToWork($request_params);
		$res = $result;
		echo $res;
    }
}