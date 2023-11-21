<?php
	defined('BASEPATH') or exit('No se permite acceso directo');
	require_once ROOT . FOLDER_PATH . '/app/models/Scores/ScoresModel.php';
	require_once LIBS_ROUTE . 'Session.php';

	class ScoresController extends Controller
	{

		private $session;
		public $model;

		public function __construct()
		{
			$this->model = new ScoresModel();
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

		public function GetScore($request_params)
		{
	      $result = $this->model->GetScore($request_params);
		  echo json_encode($result,JSON_UNESCAPED_UNICODE);	
		}

		public function SaveScore($request_params)
		{
			$params =  $this->session->get('user');
			$result = $this->model->SaveScore($request_params);	  
			echo $result;
		}

		public function UpdateScore($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->UpdateScore($request_params);

			echo $result;
		}

		public function GetScores($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->GetScores($request_params);
            $i = 0;
            while($row = $result->fetch_assoc()){
                $rowdata[$i] = $row;
                $i++;
            }
            if ($i>0){
                $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
            } else {
                $res =  '[{"scr_id":"0"}]';	
            }
            echo $res;
		}

		public function DeleteScore($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->DeleteScore($request_params);
			$strId= $request_params['scr_id'];	  
            echo $strId;
		}

	}