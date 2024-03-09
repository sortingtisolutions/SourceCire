<?php
    defined('BASEPATH') or exit('No se permite acceso directo');
    require_once ROOT . FOLDER_PATH . '/app/models/ParentsProjects/ParentsProjectsModel.php';
    require_once LIBS_ROUTE .'Session.php';

class ParentsProjectsController extends Controller
{
    
    private $session;
    public $model;

    public function __construct()
    {
        $this->model = new ParentsProjectsModel();
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

    // Lista los proyectos
    public function listUsersP($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listUsersP($request_params);
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

    public function listUsersA($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listUsersA($request_params);
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

    public function listUsersC($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listUsersC($request_params);
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
    // Lista los proyectos
    public function listDetailProds($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listDetailProds($request_params);
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

    // Lista los proyectos
    public function getProject($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->getProject($request_params);
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
    public function UpdateProject($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->UpdateProject($request_params);
        echo $result;
    } 
    public function listCustomers($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listCustomers($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"cus_id":"0"}]';	
        }
        echo $res;
    } 
    // Borra un producto seleccionado
	public function CancelParentsProjects($request_params)
	{
		$params =  $this->session->get('user');
		$result = $this->model->CancelParentsProjects($request_params, $params);
		$res = $result ;
		echo $res;
	}

    public function getLocationType($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->getLocationType();
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"loc_id":"0"}]';	
        }
        echo $res;
    } 

    public function getLocations($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->getLocations($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"loc_id":"0"}]';	
        }
        echo $res;
    } 

    // public function listProjectsType($request_params)
    // {
    //     $params =  $this->session->get('user');
    //     $result = $this->model->listProjectsType($request_params);
    //     $i = 0;
    //     while($row = $result->fetch_assoc()){
    //         $rowdata[$i] = $row;
    //         $i++;
    //     }
    //     if ($i>0){
    //         $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
    //     } else {
    //         $res =  '[{"pjt_id":"0"}]';	
    //     }
    //     echo $res;

    // } 
    public function listProjectsTypeCalled($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProjectsTypeCalled($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"pjttc_id":"0"}]';	
        }
        echo $res;

    } 
    // Lista los usuarion en los proyectos
    public function listUsersOnProj($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listUsersOnProj($request_params);
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
    public function updateUsers($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->updateUsers($request_params);
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

}
