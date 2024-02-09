<?php
    defined('BASEPATH') or exit('No se permite acceso directo');
    require_once ROOT . FOLDER_PATH . '/app/models/ProjectClosed/ProjectClosedModel.php';
    require_once LIBS_ROUTE .'Session.php';

class ProjectClosedController extends Controller
{
    
    private $session;
    public $model;

    public function __construct()
    {
        $this->model = new ProjectClosedModel();
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

/* -- Listado de proyectos  ------------------------------------------------------------------ */
    public function listProjects($request_params)
    {

        $result = $this->model->listProjects($request_params);
        $i = 0;
        while ($row = $result->fetCh_assoc())
        {
            $rowdata[$i] = $row;
            $i++;
        } 
        $res = $i > 0 ? json_encode($rowdata,JSON_UNESCAPED_UNICODE) :  '[{"pjt_id":"0"}]';	
        echo $res;
    }

    /* -- Listado de proyectos  ------------------------------------------------------------------ */
    public function listChgStatus($request_params)
    {

        $result = $this->model->listChgStatus($request_params);
        $i = 0;
        while ($row = $result->fetCh_assoc())
        {
            $rowdata[$i] = $row;
            $i++;
        } 
        $res = $i > 0 ? json_encode($rowdata,JSON_UNESCAPED_UNICODE) :  '[{"pjt_id":"0"}]';	
        echo $res;

    }

/* -- Listado de contenido de proyecto seleccionado  ----------------------------------------- */
    public function projectContent($request_params)
    {

        $result = $this->model->projectContent($request_params);
        $i = 0;
        while ($row = $result->fetCh_assoc())
        {
            $rowdata[$i] = $row;
            $i++;
        } 
        $res = $i > 0 ? json_encode($rowdata,JSON_UNESCAPED_UNICODE) :  '[{"ser_id":"0"}]';	
        echo $res;

    }
    /* -- Listado ventas de expendables    ---------------------- */
    public function saleExpendab($request_params)
    {

        $result = $this->model->saleExpendab($request_params);
        $i = 0;
        while ($row = $result->fetCh_assoc())
        {
            $rowdata[$i] = $row;
            $i++;
        } 
        $res = $i > 0 ? json_encode($rowdata,JSON_UNESCAPED_UNICODE) :  '[{"ser_id":"0"}]';	
        echo $res;

    }

    public function saveDocumentClosure($request_params)
    {
        $result = $this->model->saveDocumentClosure($request_params);
        echo $result;
    }
    // Añadido por Edna v3
    public function totalMantenimiento($request_params)
    {
        $result = $this->model->totalMantenimiento($request_params);	  
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

    // OBTENER DATOS TOTALES PARA LOS EQUIPOS BASE, EXTRA, DIAS Y SUBARRENDOS
    public function totalEquipo($request_params)
    {
        $result = $this->model->totalEquipo($request_params);	  
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
    // OBTENER DATOS PAGOS TOTALES DEL PROYECTO
    public function totalesProyecto($request_params)
    {
        $result = $this->model->totalesProyecto($request_params);	  
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

    // OBTENER DATOS PAGOS TOTALES DE LOS PREPAGOS REALIZADOS POR EL CLIENTE
    public function totalPrepago($request_params)
    {
        $result = $this->model->totalPrepago($request_params);	  
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

    // LISTAR COMENTARIOS
    public function listComments($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listComments($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);
        } else {
            $res =  '[{"com_id":"0"}]';
        }
        echo $res;
    }

}