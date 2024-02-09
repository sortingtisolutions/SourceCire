<?php
    defined('BASEPATH') or exit('No se permite acceso directo');
    require_once ROOT . FOLDER_PATH . '/app/models/ProjectReports/ProjectReportsModel.php';
    require_once LIBS_ROUTE .'Session.php';

class ProjectReportsController extends Controller
{
    
    private $session;
    public $model;

    public function __construct()
    {
        $this->model = new ProjectReportsModel();
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
    public function listAnalysts($request_params)
    {

        $result = $this->model->listAnalysts($request_params);
        $i = 0;
        while ($row = $result->fetCh_assoc())
        {
            $rowdata[$i] = $row;
            $i++;
        } 
        $res = $i > 0 ? json_encode($rowdata,JSON_UNESCAPED_UNICODE) :  '[{"pjt_id":"0"}]';	
        echo $res;

    }

    public function listCustomers($request_params)
    {

        $result = $this->model->listCustomers($request_params);
        $i = 0;
        while ($row = $result->fetCh_assoc())
        {
            $rowdata[$i] = $row;
            $i++;
        } 
        $res = $i > 0 ? json_encode($rowdata,JSON_UNESCAPED_UNICODE) :  '[{"pjt_id":"0"}]';	
        echo $res;

    }    

    public function listSuppliers($request_params)
    {

        $result = $this->model->listSuppliers($request_params);
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

        /* $result = $this->model->projectContent($request_params); */
        if ($request_params['bn'] == '1') {
            $result = $this->model->projectActive($request_params);
        }elseif ($request_params['bn'] == '2') {
            $result = $this->model->patrocinios($request_params);
        }elseif ($request_params['bn'] == '3') {
            $result = $this->model->cierres($request_params);
        }elseif ($request_params['bn'] == '4') {
            $result = $this->model->equipoMasRentado($request_params);
        }elseif ($request_params['bn'] == '5') {
            $result = $this->model->ProyectosTrabajados($request_params);
        }elseif ($request_params['bn'] == '6') {
            $result = $this->model->equipoMenosRentado($request_params);
        }elseif ($request_params['bn'] == '7') {
            $result = $this->model->Subarrendos($request_params);
        }elseif ($request_params['bn'] == '8') {
            $result = $this->model->SubbletingSuppliers($request_params);
        }elseif ($request_params['bn'] == '9') {
            $result = $this->model->newCustomers($request_params);
        }elseif ($request_params['bn'] == '10') {
            $result = $this->model->Productividad($request_params);
        }elseif ($request_params['bn'] == '11') {
            $result = $this->model->ProjectsByDeveloper($request_params);
        }
        $i = 0;
        while ($row = $result->fetCh_assoc())
        {
            $rowdata[$i] = $row;
            $i++;
        } 
        $res = $i > 0 ? json_encode($rowdata,JSON_UNESCAPED_UNICODE) :  '[{"pjt_id":"0"}]';	
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



}