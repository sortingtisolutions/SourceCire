<?php
    defined('BASEPATH') or exit('No se permite acceso directo');
    require_once ROOT . FOLDER_PATH . '/app/models/Dashboard/DashboardModel.php';
    require_once LIBS_ROUTE .'Session.php';

class DashboardController extends Controller
{
    private $session;
    public $model;


    public function __construct()
    {
        $this->model = new DashboardModel();
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


    public function getTotalProjects()
    {
        $params =  $this->session->get('user');
        $result = $this->model->getTotalProjects();
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"Total":"0"}]';	
        }
        echo $res;
    }

    // Lista proyectos contemplados
    public function getProjects($request_params)
    {
        $params =  $this->session->get('user');
        $estatus =  $request_params['parm'];
        $result = $this->model->getProjects($estatus);
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


        // Lista proyectos contemplados
    public function getProjectOrigen($request_params)
    {
        $params =  $this->session->get('user');
        $estatus =  $request_params['pjt_id'];
        $result = $this->model->getProjectOrigen($estatus);
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


    // Lista KPIS
    public function getkpis($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->getKPIS();
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"total":"0"}]';	
        }
        echo $res;
    }

    //  cambia el estado del proyecto
    public function changeStatus($request_params)
    {
        $params =  $this->session->get('user');
        $id =  $request_params['pjt_id'];
        $result = $this->model->changeStatus($id);
        echo 1;
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
            $res =  '[{"pjt_id":"0"}]';	
        }
        echo $res;
    } 

    public function listCamaras($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listCamaras($request_params);
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

    public function listProductsCat($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProductsCat($request_params);
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

}