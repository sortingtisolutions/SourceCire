<?php
    defined('BASEPATH') or exit('No se permite acceso directo');
    require_once ROOT . FOLDER_PATH . '/app/models/WorkInputContent/WorkInputContentModel.php';
    require_once LIBS_ROUTE .'Session.php';

class WorkInputContentController extends Controller
{
    
    private $session;
    public $model;

    public function __construct()
    {
        $this->model = new WorkInputContentModel();
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

    public function listFreelances($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listFreelances($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);
        } else {
            $res =  '[{"free_id":"0"}]';
        }
        echo $res;
    }
    // Lista los proyectos
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

    // Lista los productos
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
            $res =  '[{"pjtpd_id":"0"}]';
        }
        echo $res;
    }

    public function listLocations($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listLocations($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);
        } else {
            $res =  '[{"locations":"0"}]';
        }
        echo $res;
    }
    // Lista los comentarios del proyecto // 11-10-23
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
    // ************************ //

    public function listSeries($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listSeries($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);
        } else {
            $res =  '[{"ser_id":"0"}]';
        }
        echo $res;
    }
    // Guarda el comentario // 11-10-23
    public function InsertComment($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->InsertComment($request_params, $params);

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
        // **************************** //
        
    } 
        // Lista las series
    public function listReason($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listReason($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);
        } else {
            $res =  '[{"ser_id":"0"}]';
        }
        echo $res;
    }
    public function listAnalysts($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listAnalysts($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);
        } else {
            $res =  '[{"emp_id":"0"}]';
        }
        echo $res;
    }

    public function checkSeries($request_params)
    {
        $params =  $this->session->get('user');
        $prj_id = $request_params['prjid'];
        //$result = $this->model->checkSeries($request_params);
        $result = $this->model->getSeries($request_params);
        $i = 0;
        $serie=0;
        while($row = $result->fetch_assoc()){
            $serid = $row["ser_id"];
            $ser_sku = $row["ser_sku"];
            $prdid = $row["prd_id"];
            if($row["prd_level"]=='P'){
                $serie=$serid;
                $ser_sku =$ser_sku;
            }
            $paramsdet = array(
                'serid' => $serid,
                'prjid' => $prj_id,
                'serSku' => $ser_sku,
                'prdid' => $prdid
                
            );

            $ActSeries = $this->model->ActualizaSeries($paramsdet);
            
        }
        echo $serie;
    }


    public function listSeriesFree($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listSeriesFree($request_params);
        $i = 0;
        while($row = $result->fetch_assoc()){
            $rowdata[$i] = $row;
            $i++;
        }
        if ($i>0){
            $res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);
        } else {
            $res =  '[{"ser_id":"0"}]';
        }
        echo $res;
    }

    public function createTblResp($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->createTblResp($request_params);
        echo $result;
    }

    public function regMaintenance($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->regMaintenance($request_params);
        echo $result;
    }

    public function RegisterGetIn($request_params)
        {
            $params =  $this->session->get('user');
            $result = $this->model->GetInProject($request_params);
            
            echo $result;
        }
 
}
