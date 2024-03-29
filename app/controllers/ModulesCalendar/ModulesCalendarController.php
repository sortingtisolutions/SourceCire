<?php
    defined('BASEPATH') or exit('No se permite acceso directo');
    require_once ROOT . FOLDER_PATH . '/app/models/ModulesCalendar/ModulesCalendarModel.php';
    require_once LIBS_ROUTE .'Session.php';

class ModulesCalendarController extends Controller
{
    
	private $session;
    public $model;

    public function __construct()
    {
        $this->model = new ModulesCalendarModel();
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

    public function GetEventos($request_params)
		{
			$params =  $this->session->get('user');
            $result = $this->model->GetEventos($request_params);
            $i = 0;
            while($row = $result->fetch_assoc()){
                $rowdata[$i] = $row;
                $i++;
            }
            if ($i>0){
                $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
            } else {
                $res =  '[{"id":"0"}]';	
            }
            echo $res;
		}
    // LISTA LOS PROYECTOS ACTIVOS
    // public function listProjects($request_params)
    // {
    //     $params =  $this->session->get('user');
    //     $result = $this->model->listProjects($request_params['store']);
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
	public function listProducts2($request_params)
	{
		$params =  $this->session->get('user');
        $result = $this->model->listProducts2();
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
        } else {
            $res =  '[{"pro_id":"0"}]';	
        }
        echo $res;
    }

}