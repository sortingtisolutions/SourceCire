<?php
	defined('BASEPATH') or exit('No se permite acceso directo');
	require_once ROOT . FOLDER_PATH . '/app/models/MobilStore/MobilStoreModel.php';
	require_once LIBS_ROUTE . 'Session.php';

	class MobilStoreController extends Controller
	{
		private $session;
		public $model;

		public function __construct()
		{
			$this->model = new MobilStoreModel();
			$this->session= new Session();
			$this->session->init();
			if($this->session->getStatus()===1 || empty($this->session->get('user')))
				header('location: ' . FOLDER_PATH . '/Login');
		}
		
		public function exec()
		{
		  $params = array('user' => $this->session->get('user'));
		  $this->render(__CLASS__, $params);
		}

		public function GetMobilStore($request_params)
		{
	      $result = $this->model->GetMobilStores($request_params);
		  echo json_encode($result,JSON_UNESCAPED_UNICODE);	
		}

		public function SaveMobilStore($request_params)
		{
			$params =  $this->session->get('user');
			$result = $this->model->SaveMobilStore($request_params);	  
			echo $result;
		}

		public function UpdateMobilStore($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->UpdateMobilStore($request_params);

			echo $result;
		}

		public function GetMobilStores($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->GetMobilStores($request_params);
            $i = 0;
            while($row = $result->fetch_assoc()){
                $rowdata[$i] = $row;
                $i++;
            }
            if ($i>0){
                $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
            } else {
                $res =  '[{"movstr_id":"0"}]';	
            }
            echo $res;
		}

		public function DeleteMobilStore($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->DeleteMobilStore($request_params);
			$strId= $request_params['movstr_id'];	  
            echo $strId;
		}
		
// Lista los almacenes 
	public function listStores($request_params)
	{
	  $params =  $this->session->get('user');
	  $result = $this->model->listStores();
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

	public function listSubcategories($request_params)
	{
	  $params =  $this->session->get('user');
	  $result = $this->model->listSubcategories($request_params);
		$i = 0;
		  while($row = $result->fetch_assoc()){
			  $rowdata[$i] = $row;
			  $i++;
		  }
		  if ($i>0){
			  $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
		  } else {
			  $res =  '[{"sbc_id":"0"}]';	
		  }
		  echo $res;
	}

	public function listCategories($request_params)
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
	}