<?php
	defined('BASEPATH') or exit('No se permite acceso directo');
	require_once ROOT . FOLDER_PATH . '/app/models/Commons/CommonsModel.php';
	require_once LIBS_ROUTE . 'Session.php';

class CommonsController extends Controller
{

	private $session;
	public $model;

	public function __construct()
	{
		$this->model = new CommonsModel();
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

	public function listAreas($request_params)
	{
		$params =  $this->session->get('user');
		$result = $this->model->listAreas($request_params);
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

	public function listStores($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listStores($request_params);
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
	
	public function listCategories($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listCategories($request_params);
            $i = 0;
            while($row = $result->fetch_assoc()){
                $rowdata[$i] = $row;
                $i++;
            }
            if ($i>0){
                $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
            } else {
                $res =  '[{"cat_id":"0"}]';	
            }
            echo $res;
    }    

    public function listSubcategoriesAll($request_params)
    {
        $result = $this->model->listSubcategoriesAll($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
        $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"sbc_id":"0"}]';	
        }
        echo $res;	
    }

    public function listSubcategoriesOne($request_params)
    {
        $result = $this->model->listSubcategoriesOne($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
        $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"sbc_id":"0"}]';	
        }
        echo $res;	
    }
	public function listCoins($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listCoins($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"cin_id":"0"}]';	
        }
        echo $res;
    } 	 

    public function listProjects($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProjects($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"pjt_id":"0"}]';	
        }
        echo $res;
    } 

    public function listProjectsType($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProjectsType($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"pjt_id":"0"}]';	
        }
        echo $res;
    } 
    
    public function listSuppliers($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listSuppliers($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"sup_id":"0"}]';	
        }
        echo $res;
    } 
}