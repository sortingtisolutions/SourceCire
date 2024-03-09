<?php
	defined('BASEPATH') or exit('No se permite acceso directo');
	require_once ROOT . FOLDER_PATH . '/app/models/Areas/AreasModel.php';
	require_once LIBS_ROUTE . 'Session.php';

	class AreasController extends Controller
	{

		private $session;
		public $model;

		public function __construct()
		{
			$this->model = new AreasModel();
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

		/* public function GetArea($request_params)
		{
	      $result = $this->model->GetArea($request_params);
		  echo json_encode($result,JSON_UNESCAPED_UNICODE);	
		} */

		public function SaveArea($request_params)
		{
			$params =  $this->session->get('user');
			$result = $this->model->SaveArea($request_params);	  
			echo $result;
		}

		public function UpdateArea($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->UpdateArea($request_params);

			echo $result;
		}

		public function GetAreas($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->GetAreas($request_params);
            $i = 0;
            while($row = $result->fetch_assoc()){
                $rowdata[$i] = $row;
                $i++;
            }
            if ($i>0){
                $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
            } else {
                $res =  '[{"are_id":"0"}]';	
            }
            echo $res;
		}

		public function DeleteArea($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->DeleteArea($request_params);
			$strId= $request_params['are_id'];	  
            echo $strId;
		}

	}