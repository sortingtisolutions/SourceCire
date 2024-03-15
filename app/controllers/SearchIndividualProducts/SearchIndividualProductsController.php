<?php
    defined('BASEPATH') or exit('No se permite acceso directo');
    require_once ROOT . FOLDER_PATH . '/app/models/SearchIndividualProducts/SearchIndividualProductsModel.php';
    require_once LIBS_ROUTE .'Session.php';

class SearchIndividualProductsController extends Controller
{
    
	private $session;
    public $model;

    public function __construct()
    {
        $this->model = new SearchIndividualProductsModel();
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
    
// Lista los productos
    public function listSeriesProd($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listSeriesProd($request_params);
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
	public function listProductsWord($request_params)
	{
		$params =  $this->session->get('user');
        $result = $this->model->listProductsWord($request_params);
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
// LISTA LOS PROYECTOS ACTIVOS
    public function listRelation($request_params)
    {
        $params =  $this->session->get('user');
        $result = $this->model->listRelation($request_params);
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