<?php
	defined('BASEPATH') or exit('No se permite acceso directo');
	require_once ROOT . FOLDER_PATH . '/app/models/Locations/LocationsModel.php';
	require_once LIBS_ROUTE . 'Session.php';

	class LocationsController extends Controller
	{

		private $session;
		public $model;

		public function __construct()
		{
			$this->model = new LocationsModel();
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

		public function GetLocation($request_params)
		{
	      $result = $this->model->GetLocations($request_params);
		  echo json_encode($result,JSON_UNESCAPED_UNICODE);	
		}

		public function SaveLocation($request_params)
		{
			$params =  $this->session->get('user');
			$result = $this->model->SaveLocation($request_params);	  
			echo $result;
		}

		public function UpdateLocation($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->UpdateLocation($request_params);

			echo $result;
		}

		public function GetLocations($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->GetLocations($request_params);
            $i = 0;
            while($row = $result->fetch_assoc()){
                $rowdata[$i] = $row;
                $i++;
            }
            if ($i>0){
                $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
            } else {
                $res =  '[{"lov_id":"0"}]';	
            }
            echo $res;
		}

		public function DeleteLocation($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->DeleteLocation($request_params);
			$strId= $request_params['loc_id'];	  
            echo $strId;
		}

	}