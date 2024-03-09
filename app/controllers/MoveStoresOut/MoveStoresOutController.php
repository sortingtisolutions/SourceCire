<?php
    defined('BASEPATH') or exit('No se permite acceso directo');
    require_once ROOT . FOLDER_PATH . '/app/models/MoveStoresOut/MoveStoresOutModel.php';
    require_once LIBS_ROUTE .'Session.php';

class MoveStoresOutController extends Controller
{
	private $session;
	public $model;

	public function __construct()
	{
		$this->model = new MoveStoresOutModel();
		$this->session = new Session();
		$this->session->init();
		if($this->session->getStatus() === 1 || empty($this->session->get('user')))
			header('location: ' . FOLDER_PATH .'/Login');
	}

	public function exec()
	{
		$params = array('user' => $this->session->get('user'));
		$this->render(__CLASS__, $params);
	}

// LISTA LOS TIPOS DE MOVIMIENTOS
	public function listExchange()
	{
		$params =  $this->session->get('user');
		$result = $this->model->listExchange();
		  $i = 0;
			while($row = $result->fetch_assoc()){
				$rowdata[$i] = $row;
				$i++;
			}
			if ($i>0){
				$res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);
			} else {
				$res =  '[{"ext_id":"0"}]';	
			}
			echo $res;
	}

// Lista los almacenes 
	public function listStores($request_params)
	{
	  $params =  $this->session->get('user');
	  $result = $this->model->listStores();
		$i = 0;
		  while($row = $result->fetch_assoc()){
			  $rowdata[$i] = $row;
			  $i++;
		  }
		  if ($i>0){
			  $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
		  } else {
			  $res =  '[{"str_id":"0"}]';	
		  }
		  echo $res;
	}

	// Lista los Categorias 
    // public function listCategories($request_params)
    // {
    //     $params =  $this->session->get('user');
    //     $result = $this->model->listCategories();
    //         $i = 0;
    //         while($row = $result->fetch_assoc()){
    //             $rowdata[$i] = $row;
    //             $i++;
    //         }
    //         if ($i>0){
    //             $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
    //         } else {
    //             $res =  '[{"cat_id":"0"}]';	
    //         }
    //         echo $res;
    // }    

// Lista los productos
	public function listProducts($request_params)
	{
		$params =  $this->session->get('user');
		$result = $this->model->listProducts($request_params);
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

// Lista los movimientos 
	public function listExchanges($request_params)
	{
		$params =  $this->session->get('user');
		$result = $this->model->listExchanges($request_params['guid']);
		$i = 0;
		while($row = $result->fetch_assoc()){
			$rowdata[$i] = $row;
			$i++;
		}
		if ($i>0){
			$res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
			//$res =  json_encode($rowdata, JSON_HEX_QUOT);
		} else {
			$res =  '[{"exc_id":"0"}]';	
		}
		echo $res;
	} 

// Obtiene el folio del movimiento
	public function NextExchange($request_params)
	{
		$params =  $this->session->get('user');
		$result = $this->model->NextExchange();
		$res = $result;
        echo $res;
	} 
// Registra los movimientos entre almacenes
	public function SaveExchange($request_params)
	{
		$params =  $this->session->get('user');
		$result = $this->model->SaveExchange($request_params, $params);
		$res = $result;
		echo $res;
	} 

// Actualiza la situacion del almacen
	public function UpdateStoresSource($request_params)
	{
		$typeExch = $request_params['typeExch'];
		$strid = $request_params['strid'];
		$mov = $request_params['mov'];
		$prdlvl = $request_params['lvl'];

		$existences = $this->model->getExistences($request_params);
		$exis = $existences->fetch_object();

		$var = 0;

		if ($exis->prd_stock >= $request_params['qty']) {
			if($typeExch == 2){
				if ($mov == 'S' ){
					$params =  $this->session->get('user');
					$result = $this->model->UpdateStoresSourceT($request_params);
					$res = $result;
					// echo $res;
				}
		
				if ($mov == 'T' ){
					$params =  $this->session->get('user');
					$item = $this->model->SechingProducts($request_params);
		
					$num_items = $item->fetch_object();
		
					if ($num_items->exist > 0){
						// echo 'update';
						// actualiza la cantidad en el almacen destino
						$result = $this->model->UpdateProducts($request_params);
						
					} else {
						// echo 'insert';
						//agrega la relación almacen - producto
						$result = $this->model->InsertProducts($request_params);
					}
					$res = $result;
					// echo $num_items->exist; //$res;
				}
			}else{
				if ($typeExch == 3 &&  $strid ==30){
				
					$params =  $this->session->get('user');
					$result = $this->model->UpdateStoresSourceE($request_params);
					$res = $result;
					// echo $res;
				}else{
					if ($mov == 'S' ){
				
						$params =  $this->session->get('user');
						$result = $this->model->UpdateStoresSource($request_params);
						$res = $result;
						// echo $res;
					}
				}
				
			}
			if ($prdlvl == 'PF') {
				$response =  $this->setProducts($request_params);
			}
			$var = 1;
		}
		echo $var;
		
	} 
	public function setProducts($param)
	{
		$typeExch = $param['typeExch'];
		$strid1 = $param['strid'];
		$mov = $param['mov'];
		$qty = $param['qty'];
		$prdsku = $param['sku'];
		$prdid = $param['prdid'];
		
		$prdlvl = $param['lvl'];

		if ($prdlvl == 'PF') {
			$resultado = $this->model->getAccesories($param);

			while($row = $resultado->fetch_assoc()){
				$prd_id = $row["prd_id"];
				$ser_id = $row["ser_id"];
				$strid2 = $row["str_id"];
				$paramsacc = array(
					'prdid' => $prd_id,
					'serid' => $ser_id,
					'stridT' => $strid2,
					'strid' => $strid1,
					'qty' => $qty,
					'typeExch' => $typeExch,
					'mov' => $mov,
				);
	
				$response =  $this->setAccesories($paramsacc);
			}
		}
		/* elseif ($prdlvl == 'PV') {
			$resultado = $this->model->getProducts($prdid);
			while($pkt = $resultado->fetch_assoc()){
                $pkqty =  $pkt["pck_quantity"];
				$prd_id = $pkt["prd_id"];
				$series = $this->model->getSeriesProduct($prd_id, $pkqty);
				
				while($serie = $series->fetch_assoc()){
					$ser_id = $serie["ser_id"];
					$strid2 = $serie["str_id"];

					$paramsacc = array(
						'prdid' => $prd_id,
						'serid' => $ser_id,
						'stridT' => $strid2,
						'strid' => $strid1,
						'qty' => $qty,
						'typeExch' => $typeExch,
						'mov' => $mov,
					);
					$response =  $this->setAccesories($paramsacc);
				}
			}

		} */
		return $prdlvl;
			
	}
	public function setAccesories($paramsacc){
		$typeExch = $paramsacc['typeExch'];
		$mov = $paramsacc['mov'];
		$strid1 = $paramsacc['strid'];
		
		if($typeExch == 2){
			if ($mov == 'S' ){
				$result = $this->model->UpdateStoresSourceT($paramsacc);
				$res = $result;
				
			}
	
			if ($mov == 'T' ){
				$params =  $this->session->get('user');
				$item = $this->model->SechingProducts($paramsacc);
	
				$num_items = $item->fetch_object();
	
				if ($num_items->exist > 0){
					// echo 'update';
					// actualiza la cantidad en el almacen destino
					$result = $this->model->UpdateProducts($paramsacc);
					
				} else {
					// echo 'insert';
					//agrega la relación almacen - producto
					$result = $this->model->InsertProducts($paramsacc);
				}
				$res = $result;
				
				echo $num_items->exist; //$res;
			}
		}else{
			if ($typeExch == 3 &&  $strid1 == 30){
			
				$params =  $this->session->get('user');
				$result = $this->model->UpdateStoresSourceE($paramsacc);
				$res = $result;
				
			}else{
				if ($mov == 'S' ){
			
					$params =  $this->session->get('user');
					$result = $this->model->UpdateStoresSource($paramsacc);
					$res = $result;
				}
			}
			
		}
	}
	
}