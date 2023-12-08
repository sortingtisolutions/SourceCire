<?php
	defined('BASEPATH') or exit('No se permite acceso directo');
	require_once ROOT . FOLDER_PATH . '/app/models/LoadSeries/LoadSeriesModel.php';
	require_once LIBS_ROUTE . 'Session.php';

	class LoadSeriesController extends Controller
	{
		private $session;
		public $model;

		public function __construct()
		{
			$this->model = new LoadSeriesModel();
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
		//REALIZA LISTA DE DOCUMENTOS DISPONIBLES
		public function GetDocumento($request_params)
		{
	      $result = $this->model->GetDocumento($request_params);
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

		public function SaveDocumento($request_params)
		{
			//if($request_params['idDocumento'] == ""){
				$result = $this->model->SaveDocumento($request_params);	  
			/* }else{
				$result = $this->model->ActualizaDocumento($request_params);	  
			} */
			echo json_encode($result,JSON_UNESCAPED_UNICODE);	
		}

		public function GetDocumentos($request_params)
		{
			$result = $this->model->GetDocumentos($request_params);
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
		public function listResults($request_params)
		{
			$params =  $this->session->get('user');
			$result = $this->model->listResults($request_params);
			$i = 0;
			while($row = $result->fetch_assoc()){
				$rowdata[$i] = $row;
				$i++;
			}
			if ($i>0){
				$res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
			} else {
				$res =  '[{"results":"0"}]';	
			}
			echo $res;
		} 
		public function loadProcess($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->loadProcess($request_params);
			$res =  '[{"ser_id":"0"}]';
			echo $res;
		}

		public function DeleteData($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->DeleteData($request_params);
			$res =  '[{"ser_id":"0"}]';
			echo $res;
		}


	}