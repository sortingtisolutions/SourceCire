<?php
    defined('BASEPATH') or exit('No se permite acceso directo');
    require_once ROOT . FOLDER_PATH . '/app/models/DashboardFull/DashboardFullModel.php';
    require_once LIBS_ROUTE .'Session.php';

class DashboardFullController extends Controller
{
    private $session;
    public $model;


    public function __construct()
    {
        $this->model = new DashboardFullModel();
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

    // Lista proyectos contemplados
    public function getDatosBudget($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->getDatosBudget($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"canTotal":"0"}]';	
        }
        echo $res;
    }
    public function getProgressData($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->getProgressData($request_params);
        if($request_params['type']==''){
            $result = $this->model->getProgressData($request_params);
        } else{
            if($request_params['type']=='T'){
                $result = $this->model->getProgressDataT($request_params);
            }else{
                if($request_params['type']=='D'){
                    $result = $this->model->getProgressDataD($request_params);
                }
                else{
                    if($request_params['type']=='S'){
                        $result = $this->model->getProgressDataS($request_params);
                    }else{
                        
                        if ($request_params['type']=='TC') {
                            $result = $this->model->getProgressDataTypeCall($request_params);
                        } 
                    }
                    
                }
            }
        } 
        
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

    public function getSublettingData($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->getSublettingData($request_params);
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
    public function getPrjTransData($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->getPrjTransData($request_params);
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
    public function getDummyData($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->getDummyData($request_params);
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
    public function getTypeLocation($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->getTypeLocation($request_params);
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
    public function getTypeCall($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->getTypeCall($request_params);
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

    // Lista KPIS
    public function getTotales($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->getTotales();
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

    // Lista los comentarios del proyecto
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

    //listProjectsCierres

    // Lista los comentarios del proyecto
    public function listProjectsCierres($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProjectsCierres($request_params);
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
    // Lista los comentarios del proyecto
    public function listProjectsTransport($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProjectsTransport($request_params);
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

    // Lista los subarrendos del proyecto
    public function listSubarrendos($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listSubarrendos($request_params);
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