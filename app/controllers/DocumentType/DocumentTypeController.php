<?php
	defined('BASEPATH') or exit('No se permite acceso directo');
	require_once ROOT . FOLDER_PATH . '/app/models/DocumentType/DocumentTypeModel.php';
	require_once LIBS_ROUTE . 'Session.php';

	class DocumentTypeController extends Controller
	{

		private $session;
		public $model;

		public function __construct()
		{
			$this->model = new DocumentTypeModel();
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

		public function GetDocumentType($request_params)
		{
	      $result = $this->model->GetDocumentTypes($request_params);
		  echo json_encode($result,JSON_UNESCAPED_UNICODE);	
		}

		public function SaveDocumentType($request_params)
		{
			$params =  $this->session->get('user');
			$result = $this->model->SaveDocumentType($request_params);	  
			echo $result;
		}

		public function UpdateDocumentType($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->UpdateDocumentType($request_params);

			echo $result;
		}

		public function GetDocumentTypes($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->GetDocumentTypes($request_params);
            $i = 0;
            while($row = $result->fetch_assoc()){
                $rowdata[$i] = $row;
                $i++;
            }
            if ($i>0){
                $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
            } else {
                $res =  '[{"dot_id":"0"}]';	
            }
            echo $res;
		}

		public function DeleteDocumentType($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->DeleteDocumentType($request_params);
			$strId= $request_params['dot_id'];	  
            echo $strId;
		}

		
	}