<?php
    defined('BASEPATH') or exit('No se permite acceso directo');
    require_once ROOT . FOLDER_PATH . '/app/models/ClosedProyectChange/ClosedProyectChangeModel.php';
    require_once LIBS_ROUTE .'Session.php';

class ClosedProyectChangeController extends Controller
{
    private $session;
    public $model;

    public function __construct()
    {
        $this->model = new ClosedProyectChangeModel();
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

/** */
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
/** */
    public function listDataProjects($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listDataProjects($request_params);
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

    public function getMontos($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->getMontos($request_params);
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

    public function saveDocumentClosure($request_params)
    {
        $result = $this->model->saveDocumentClosure($request_params);
        echo $result;
    }
/** */
    public function insertCollectPays($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->insertCollectPays($request_params, $params);
        $res = $result;
        $pjtId  = $this->model->PromoteProject($request_params);

        echo $res;
    } 
/** */
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

}