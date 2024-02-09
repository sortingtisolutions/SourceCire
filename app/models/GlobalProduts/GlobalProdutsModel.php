<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class GlobalProdutsModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

// Listado de Productos
    public function listProducts($params)
    {
        //$catId = $this->db->real_escape_string($params['catId']);

        $qry = "SELECT prd_id, prd_sku, prd_name, prd_english_name, ldp.prd_code_provider, ldp.prd_name_provider,
		ldp.prd_model, ldp.prd_price, ldp.prd_coin_type, ldp.prd_visibility, case when ldp.prd_insured = 1 then 'Sí' ELSE 'NO' END prd_insured,
		ldp.srv_id, srv.srv_name, cn.cin_code, prd_type_asigned, ct.cat_name, sb.sbc_name FROM ctt_global_products AS ldp
		LEFT JOIN ctt_services AS srv ON srv.srv_id = ldp.srv_id
		LEFT JOIN ctt_coins AS cn ON cn.cin_id = ldp.cin_id
		LEFT JOIN ctt_subcategories AS sb ON sb.sbc_id = ldp.sbc_id
		LEFT JOIN ctt_categories AS ct ON ct.cat_id = sb.cat_id where prd_status = 1";
        return $this->db->query($qry);
    }
    
    public function listCategories()
    {
        $qry = "SELECT cat_id, cat_name FROM ctt_categories 
            WHERE cat_status = 1 AND cat_id NOT IN (19,20);";
            
        return $this->db->query($qry);
    }
    public function listSubCategories($param)
    {
        $catId = $this->db->real_escape_string($param['catId']);
        $qry = "SELECT * FROM ctt_subcategories 
                WHERE sbc_status = 1 AND cat_id=$catId;";
        return $this->db->query($qry);
    }
    public function updateData($param){
        $sbcId = $this->db->real_escape_string($param['sbcId']);
        $idSelected = $this->db->real_escape_string($param['idSelected']);
        $nxtSku = $this->db->real_escape_string($param['nxtSku']);
        $cat_name  = "";
        $sbc_name  = "";
        
        $query = "SELECT CONCAT(LPAD(ct.cat_id,2,'0'),LPAD(sb.sbc_code,2, '0')) sku, ct.cat_name, sb.sbc_name
        FROM ctt_subcategories AS sb 
        INNER JOIN ctt_categories AS ct ON ct.cat_id = sb.cat_id WHERE sb.sbc_id = $sbcId LIMIT 1;";
        
        $rss = $this->db->query($query);
        $res = $rss->fetch_object();
        if ($res != null){
            $sku  = $res->sku;
            $cat_name  = $res->cat_name;
            $sbc_name  = $res->sbc_name;
        }
        $NxtId = str_pad($nxtSku, 3, "0", STR_PAD_LEFT);
        $sku .= $NxtId;
        $qry =  "UPDATE ctt_global_products SET sbc_id = '$sbcId', prd_sku = '$sku'
        WHERE prd_id = $idSelected;";
        $this->db->query($qry);

        return $idSelected .'|'. $sku .'|'. $cat_name .'|'.$sbc_name;
    }

    public function getNextSku($param){
        $sbcId = $this->db->real_escape_string($param['sbcId']);
        $Nxt = 1;
        $query = "SELECT ifnull(max(convert(substring(prd_sku,5,3), signed integer)),0) + 1 AS NEXT, SUBSTR(prd_sku,1,4) sku
                FROM ctt_products  WHERE sbc_id = $sbcId;";
        
        $rss = $this->db->query($query);
        $res = $rss->fetch_object();
        if ($res != null){
            $Nxt  = $res->NEXT; 
        }
        return $Nxt;

    }
    public function getSelectProject($params)
    {
        /* $prdId = $this->db->real_escape_string($params['prdId']); */
        $qry = "SELECT pjtcn_id,pjtcn_prod_sku,pjtcn_prod_name,pjtcn_quantity,pjtcn_prod_level
                FROM ctt_projects_content AS pj
                WHERE pj.pjt_id = 1 limit 1;";

        return $this->db->query($qry);
    }

    public function UpdateSeriesToWork($params)
    {
        $pjtid = $this->db->real_escape_string($params['pjtid']);
        $verid = $this->db->real_escape_string($params['verid']);

        $qry2 = "UPDATE ctt_projects SET pjt_status='7'
                WHERE pjt_id=$pjtid AND pjt_status='4';";

        $chprj = $this->db->query($qry2);

        $qry = "SELECT fun_RegistraAccesorios('$verid', '$pjtid') as bandsucess
                FROM DUAL;";  // solo trae un registro
        $result =  $this->db->query($qry);
        return 1;

    }

    public function loadProcess($param)
	{
        $idSelected = $this->db->real_escape_string($param['idSelected']);
		$qry = "INSERT INTO ctt_products(
			prd_sku, prd_name,prd_english_name, prd_code_provider, prd_model, prd_price, cin_id, prd_insured, prd_type_asigned, srv_id, sbc_id)
	SELECT  prd_sku, prd_name,prd_english_name, prd_code_provider, prd_model, prd_price, cin_id, prd_insured, prd_type_asigned, srv_id, sbc_id
	FROM ctt_global_products a WHERE a.prd_sku != '' AND prd_status = 1 AND sbc_id > 0 AND prd_id IN ($idSelected);";
		$result = $this->db->query($qry);

		$qry1 = "UPDATE ctt_global_products SET prd_status = 0 WHERE prd_sku != '' AND prd_id IN ($idSelected) AND sbc_id > 0;";
        $this->db->query($qry1);
		
		return $result;
	}

    public function loadProcessAll($param)
	{
		$qry = "INSERT INTO ctt_products(
			prd_sku, prd_name,prd_english_name, prd_code_provider, prd_model, prd_price, cin_id, prd_insured, prd_type_asigned, srv_id, sbc_id)
	SELECT  prd_sku, prd_name,prd_english_name, prd_code_provider, prd_model, prd_price, cin_id, prd_insured, prd_type_asigned, srv_id, sbc_id
	FROM ctt_global_products a WHERE a.prd_sku != '' AND prd_status = 1 AND sbc_id > 0;";
		$result = $this->db->query($qry);

		$qry1 = "UPDATE ctt_global_products SET prd_status = 0 WHERE prd_sku != '' AND sbc_id > 0;";
        $this->db->query($qry1);
		
		return $result;
	}

}
