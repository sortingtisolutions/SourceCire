<?php
	defined('BASEPATH') or exit('No se permite acceso directo');
	require_once ROOT . FOLDER_PATH . '/app/models/FilesCsv/FilesCsvModel.php';
	require_once LIBS_ROUTE . 'Session.php';

	class FilesCsvController extends Controller
	{
		private $session;
		public $model;

		public function __construct()
		{
			$this->model = new FilesCsvModel();
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
                $res =  '[{"prd_id":"0"}]';	
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
				  $res =  '[{"prd_id":"0"}]';	
			  }
			  echo $res;
		}
		public function GetDocumentosFicha($request_params)
		{
	      $result = $this->model->GetDocumentosFicha($request_params);
		  echo json_encode($result,JSON_UNESCAPED_UNICODE);	
		}

		public function DeleteDocumentos($request_params)
		{
		  $result = $this->model->DeleteDocumentos($request_params);	  
		  echo json_encode($result ,JSON_UNESCAPED_UNICODE);	
		}

		public function verDocumento($request_params)
		{
		  $result = $this->model->verDocumento($request_params);	
		  echo json_encode($result ,JSON_UNESCAPED_UNICODE);	
		}

		public function GetTypeDocumento($request_params)
		{
		  $result = $this->model->GetTypeDocumento($request_params);	
		  echo json_encode($result ,JSON_UNESCAPED_UNICODE);	
		}

	}