<?php
	defined('BASEPATH') or exit('No se permite acceso directo');
	require_once ROOT . FOLDER_PATH . '/app/models/ProjectType/ProjectTypeModel.php';
	require_once LIBS_ROUTE . 'Session.php';

	class ProjectTypeController extends Controller
	{

		private $session;
		public $model;

		public function __construct()
		{
			$this->model = new ProjectTypeModel();
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

		// public function listProjectsType($request_params)
		// {
	    //   $result = $this->model->listProjectsType($request_params);
		//   echo json_encode($result,JSON_UNESCAPED_UNICODE);	
		// }

		public function SaveProjectType($request_params)
		{
			$params =  $this->session->get('user');
			$result = $this->model->SaveProjectType($request_params);	  
			echo $result;
		}

		public function UpdateProjectType($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->UpdateProjectType($request_params);

			echo $result;
		}

		// public function listProjectsType($request_params)
		// {
		// 	$params =  $this->session->get('user');
        //     $result = $this->model->listProjectsType($request_params);
        //     $i = 0;
        //     while($row = $result->fetch_assoc()){
        //         $rowdata[$i] = $row;
        //         $i++;
        //     }
        //     if ($i>0){
        //         $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        //     } else {
        //         $res =  '[{"pjttp_id":"0"}]';	
        //     }
        //     echo $res;
		// }

		public function DeleteProjectType($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->DeleteProjectType($request_params);
			$strId= $request_params['pjttp_id'];	  
            echo $strId;
		}

		
	}