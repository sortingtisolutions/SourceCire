<?php
	defined('BASEPATH') or exit('No se permite acceso directo');
	require_once ROOT . FOLDER_PATH . '/app/models/ModMenu/ModMenuModel.php';
	require_once LIBS_ROUTE . 'Session.php';

	class ModMenuController extends Controller
	{

		private $session;
		public $model;

		public function __construct()
		{
			$this->model = new ModMenuModel();
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

		public function GetMenu($request_params)
		{
	      $result = $this->model->GetMenu($request_params);
		  echo json_encode($result,JSON_UNESCAPED_UNICODE);	
		}

		public function SaveMenu($request_params)
		{
			$params =  $this->session->get('user');
			$result = $this->model->SaveMenu($request_params);	  
			echo $result;
		}

		public function UpdateMenu($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->UpdateMenu($request_params);

			echo $result;
		}

		public function GetMenus($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->GetMenus($request_params);
            $i = 0;
            while($row = $result->fetch_assoc()){
                $rowdata[$i] = $row;
                $i++;
            }
            if ($i>0){
                $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
            } else {
                $res =  '[{"mnu_id":"0"}]';	
            }
            echo $res;
		}

		public function DeleteMenu($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->DeleteMenu($request_params);
			$strId= $request_params['mnu_id'];	  
            echo $strId;
		}

		public function listMenuParents($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listMenuParents();
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"mnu_id":"0"}]';	
        }
        echo $res;
    } 

	public function listModules($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listModules();
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"mod_id":"0"}]';	
        }
        echo $res;
    } 

	}