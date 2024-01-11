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
			$result = $this->model->getLoadSeries($request_params);
			$load 	= $this->load_process($result);
			$borrarSeries = $this->model->vaciarLoadSeries();
            // $result = $this->model->loadProcess($request_params);
			$res =  '[{"ser_id":"0"}]';
			echo $res;
		}
		public function load_process($res){
			while($row = $res->fetch_assoc()){
				$ser_sku 			= $row["ser_sku"];
				$ser_serial_number 	= $row["ser_serial_number"];
				$ser_cost 			= $row["ser_cost"];
				$ser_status 		= $row["ser_status"];
				$ser_situation 		= $row["ser_situation"];
				$ser_stage 			= $row["ser_stage"];
				$ser_date_registry 	= $row["ser_date_registry"];
				$ser_date_down	 	= $row["ser_date_down"];

				$ser_brand 			= $row["ser_brand"];
				$ser_cost_import 	= $row["ser_cost_import"];
				$ser_import_petition= $row["ser_import_petition"];
				$ser_sum_ctot_cimp 	= $row["ser_sum_ctot_cimp"];
				$ser_no_econo 		= $row["ser_no_econo"];
				$ser_comments 		= $row["ser_comments"];
				$prd_id 			= $row["prd_id"];
				$sup_id 			= $row["sup_id"];
				$cin_id 			= $row["cin_id"];
				$str_id 			= $row["str_id"];

				$params = array(
					'sersku' 		=> $ser_sku,
					'sernum' 		=> $ser_serial_number,
					'sercost' 		=> $ser_cost,
					'status' 		=> $ser_status ,
					'situation' 	=> $ser_situation,
					'stage' 		=> $ser_stage,
					'datereg' 		=> $ser_date_registry,
					'datedown' 		=> $ser_date_down,

					'serbrand' 		=> $ser_brand,
					'costimport' 	=> $ser_cost_import,
					'imppetition' 	=> $ser_import_petition,
					'sumctotcimp' 	=> $ser_sum_ctot_cimp,
					'noecono' 		=> $ser_no_econo,
					'comments' 		=> $ser_comments,
					'prdid' 		=> $prd_id,
					'supid' 		=> $sup_id,

					'cinid' 		=> $cin_id,
					'strid' 		=> $str_id,
				);
				$this->model->saveSerie($params);
			}
		}
		public function DeleteData($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->DeleteData($request_params);
			$res =  '[{"ser_id":"0"}]';
			echo $res;
		}
		public function listErrores($request_params)
		{
			$result = $this->model->listErrores($request_params);
			$i = 0;
			  while($row = $result->fetch_assoc()){
				  $rowdata[$i] = $row;
				  $i++;
			  }
			  if ($i>0){
				  $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
			  } else {
				  $res =  '[{"erm_id":"0"}]';	
			  }
			  echo $res;
		}

	}