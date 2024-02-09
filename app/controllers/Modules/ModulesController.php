<?php
	defined('BASEPATH') or exit('No se permite acceso directo');
	require_once ROOT . FOLDER_PATH . '/app/models/Modules/ModulesModel.php';
	require_once LIBS_ROUTE . 'Session.php';

	class ModulesController extends Controller
	{

		private $session;
		public $model;

		public function __construct()
		{
			$this->model = new ModulesModel();
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

		public function GetModule($request_params)
		{
	      $result = $this->model->GetModules($request_params);
		  echo json_encode($result,JSON_UNESCAPED_UNICODE);	
		}

		public function SaveModule($request_params)
		{
			$params =  $this->session->get('user');
			$result = $this->model->SaveModule($request_params);	  
			echo $result;
		}

		public function UpdateModule($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->UpdateModule($request_params);

			echo $result;
		}

		public function GetModules($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->GetModules($request_params);
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

		public function DeleteModule($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->DeleteModule($request_params);
			$strId= $request_params['mod_id'];	  
            echo $strId;
		}

		/* // Obtiene encargados de almacen
		public function GetEncargadosAlmacen($request_params)
		{
	      $result = $this->model->GetEncargadosAlmacen($request_params);
		  echo json_encode($result,JSON_UNESCAPED_UNICODE);	
		}

		// Lista las series
		public function listSeries_old($request_params)
		{
			// $params =  $this->session->get('user');
			// $result = $this->model->listSeries($request_params);
			// $i = 0;
			// while($row = $result->fetch_assoc()){
			// 	$rowdata[$i] = $row;
			// 	$i++;
			// }
			// if ($i>0){
			// 	$res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
			// } else {
			// 	$res =  '[{"str_id":"0"}]';	
			// }
			// echo $res;
		}

		public function listSeries($request_params)
		{
			$params =  $this->session->get('user');
			$result = $this->model->listSeries($request_params);
			// print_r($result);
			echo $result;

		}

		//CONTABILIZA REGISTROS
        public function countQuantity($request_params)
        {
            $params =  $this->session->get('user');
            $result = $this->model->countQuantity($request_params);
			$i = 0;
            while($row = $result->fetch_assoc()){
                $rowdata[$i] = $row;
                $i++;
            }
            if ($i>0){
                $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
            } else {
                $res =  '[{"count":"0"}]';	
            }
            echo $res;
        }
 */
	}