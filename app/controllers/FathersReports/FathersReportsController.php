<?php
    defined('BASEPATH') or exit('No se permite acceso directo');
    require_once ROOT . FOLDER_PATH . '/app/models/FathersReports/FathersReportsModel.php';
    require_once LIBS_ROUTE .'Session.php';

class FathersReportsController extends Controller
{
    private $session;
    public $model;


    public function __construct()
    {
        $this->model = new FathersReportsModel();
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

    
    // LISTA LOS PROYECTOS ACTIVOS
    public function listProyects($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProyects($request_params['store']);
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
// Lista los productos
    public function listProjectsForProject($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listProjectsForProject($request_params);
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

// Proceso de series de subarrendos    
    public function changeMaintain($request_params)
    {
        $params =  $this->session->get('user');
        $pjtId = $this->model->changeMaintain($request_params);

        $result = $this->model->getPjtDetail($pjtId);

        $i = 0;
            while($row = $result->fetch_assoc()){
                $rowdata[$i] = $row;
                $i++;
            }
            if ($i>0){
                $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
            } else {
                $res =  '[{"pjdt_id_id":"0"}]';	
            }
            
        echo $res;
    }

    function saveMaintain($request_params){
        $params =  $this->session->get('user');
        $result = $this->model->saveMaintain($request_params);

        echo $result;
    }
}