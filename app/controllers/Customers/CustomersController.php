<?php
    defined('BASEPATH') or exit('No se permite acceso directo');
    require_once ROOT . FOLDER_PATH . '/app/models/Customers/CustomersModel.php';
    require_once LIBS_ROUTE .'Session.php';

class CustomersController extends Controller
{
	
	private $session;
	public $model;

	public function __construct()
	{
		$this->model = new CustomersModel();
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
			$res =  '[{"cus_id":"0"}]';	
		}
		echo $res;
	}

// Lista los tipos de calificacion
	public function listScores($request_params)
	{
		$params =  $this->session->get('user');
		$result = $this->model->listScores($request_params);
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

// Lista los documentos de fichas técnicas
	public function listCustType($request_params)
	{
		$params =  $this->session->get('user');
		$result = $this->model->listCustType($request_params);
		$i = 0;
		while($row = $result->fetch_assoc()){
			$rowdata[$i] = $row;
			$i++;
		}
		if ($i>0){
			$res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
		} else {
			$res =  '[{"doc_id":"0"}]';	
		}
		echo $res;
	}

// Obtiene datos del producto seleccionado
	public function getSelectCustomer($request_params)
	{
		$params =  $this->session->get('user');
		$result = $this->model->getSelectCustomer($request_params);
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

// Guarda los cambios de un producto
	public function saveEditCustomer($request_params)
	{
		$params =  $this->session->get('user');
		$result = $this->model->saveEditCustomer($request_params);
		$res = $result ;
		echo $res;
	}

// Guarda nuevo producto
	public function saveNewCustomer($request_params)
	{
		$params =  $this->session->get('user');
		$result = $this->model->saveNewCustomer($request_params);
		$res = $result ;
		echo $res;
	}

// Borra un producto seleccionado
	public function deleteCustomers($request_params)
	{
		$params =  $this->session->get('user');
		$result = $this->model->deleteCustomers($request_params);
		$res = $result ;
		echo $res;
	}

// Borra una serie seleccionada
	public function deleteSerie($request_params)
	{
		$params =  $this->session->get('user');
		$result = $this->model->deleteSerie($request_params);
		$res = $result ;
		echo $res;
	}

}