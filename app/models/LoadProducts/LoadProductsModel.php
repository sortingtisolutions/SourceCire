<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class LoadProductsModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

//Guarda proveedor    *****
public function SaveDocumento($request_params)
{
	$estatus = '';
	try {
		$fileName = $_FILES['file']['name'];
		$fileSize = $_FILES['file']['size'];
		$fileType = $_FILES['file']['type'];
		$fileNameCmps = explode(".", $fileName);
		$fileExtension = strtolower(end($fileNameCmps));

		$file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv');
		if($fileName && in_array($fileType, $file_mimes)){
			if(is_uploaded_file($_FILES['file']['tmp_name'])){
				$cont = 0;
				$aux = 0;
				$csv_file = fopen($_FILES['file']['tmp_name'], 'r');
				while(($LoadProducts = fgetcsv($csv_file)) !== FALSE){
					/* if (strlen($LoadProducts[0]) <= 10 && strlen($LoadProducts[0]) >= 7 && strlen($LoadProducts[7]) == 3 && is_numeric($LoadProducts[6]) && is_numeric($LoadProducts[8]) && is_numeric($LoadProducts[9]) && ($LoadProducts[8] == '0' || $LoadProducts[8] == '1')) {
						
						$qry1 = "SELECT COUNT(*) cant FROM ctt_categories AS ct 
							INNER JOIN ctt_subcategories AS sb ON sb.cat_id = ct.cat_id
							where ct.cat_id = SUBSTR('$LoadProducts[0]',1,2) AND sb.sbc_code = SUBSTR('$LoadProducts[0]',3,2)";
						$res = $this->db->query($qry1);
						$rs = $res->fetch_object();
						$acept = $rs->cant;

						$qry2 = "SELECT cn.cin_id FROM ctt_coins AS cn WHERE cn.cin_code = '$LoadProducts[7]' UNION SELECT 0 LIMIT 1";
						$rest = $this->db->query($qry2);
						$rst = $rest->fetch_object();
						$coin = $rst->cin_id;


						if($acept > 0 && $coin>0){
							$qry = "INSERT INTO ctt_load_products(prd_sku, prd_name, prd_english_name, prd_code_provider, prd_name_provider, prd_model, prd_price, cin_id, prd_insured, srv_id)
									VALUES ('$LoadProducts[0]', '$LoadProducts[1]','$LoadProducts[2]', '$LoadProducts[3]', '$LoadProducts[4]', '$LoadProducts[5]', '$LoadProducts[6]', '$coin', '$LoadProducts[8]', '$LoadProducts[9]')";
							$this->db->query($qry);
							$cont++;
							$estatus = strlen($LoadProducts[7]);
						}else{
							$aux++;
							$estatus = 'Hubo un problema con el sku o la moneda';
						}
					}else{
						$aux++;
						$estatus = 'Los datos del sku podrian ser mayor de lo que debe, la moneda no pertenece, no es decimal el precio';
					} */
					
					$sku = '';
					$estatus = '';
					
					if ($LoadProducts[6] != 'Nombre en Ingles') {
						if (strlen($LoadProducts[0]) == 7) {
							// REVISION DE SKU
							$skuCsv = substr($LoadProducts[0], 5, 3);
							$sku = strval($LoadProducts[0]);
							$nombre_producto = '';
							
							// Revisar que el la categoria y subcategoria exista en la base de datos
							$qry1 = "SELECT COUNT(*) cant FROM ctt_categories AS ct
							INNER JOIN ctt_subcategories AS sb ON sb.cat_id = ct.cat_id
							WHERE ct.cat_id = SUBSTR('$sku',1,2) AND sb.sbc_code = SUBSTR('$sku',3,2);";
							
							$res = $this->db->query($qry1);
							$rs = $res->fetch_object();
							$acept = $rs->cant;
	
							// Revisar que el producto No exita ya en la tabla de productos
							$qry3 = "SELECT COUNT(*) sku FROM ctt_products AS pd WHERE pd.prd_sku = '$sku'";
							$resp = $this->db->query($qry3);
							$respuesta = $resp->fetch_object();
							$skuValido = $respuesta->sku;
	
							// Obtener la subcategoria a la que pertenece el producto
							$qry4 = "SELECT sb.sbc_id FROM ctt_subcategories AS sb 
							WHERE sb.sbc_code = SUBSTR('$sku',3,2) AND sb.cat_id = SUBSTR('$sku',1,2) Union select 0 LIMIT 1;";
							$resp = $this->db->query($qry4);
							$respuesta = $resp->fetch_object();
							$sbcId = $respuesta->sbc_id;

							if (strpos($LoadProducts[1], "'") !== false) {
								$nombre_producto = str_replace("'", '"', $LoadProducts[1]);
							}else{
								$nombre_producto = $LoadProducts[1];
							}
	
	
							if ($acept == 0 ) {
								//SKU
								$estatus = $estatus. '1,';
							}
							if($skuValido != 0){ 
								// duplicidad de sku
								$estatus = $estatus. '2,';
							}
							if ($sbcId == 0) {
								// categoria o subcategoria
								$estatus = $estatus. '3,';
							}
							// Revisar que termine en numerico
							if (!is_numeric($skuCsv)) {
								//sku
								$estatus = $estatus. '1,';
								$acept =0;
							}
						}else{
							$sku = strval($LoadProducts[0]);
							// sku
							$estatus = $estatus. '1,';
							$acept =0;
						}
						if (strlen($LoadProducts[3]) == 3) {
							# Revision de Moneda
							$qry2 = "SELECT cn.cin_id FROM ctt_coins AS cn WHERE cn.cin_code = '$LoadProducts[3]' UNION SELECT 0 LIMIT 1";
							$rest = $this->db->query($qry2);
							$rst = $rest->fetch_object();
							$coin = $rst->cin_id;
							if ($coin==0) {
								//moneda
								$estatus = $estatus.'4,';
							}
						}else{
							$coin = 0;
							$estatus = $estatus.'4,';
						}
						if (!is_numeric($LoadProducts[2])) {
							# Costo
							$estatus = $estatus.'5,';
						}
						if (!is_numeric($LoadProducts[5])) {
							# Servicio
							$estatus = $estatus.'6,';
						}
						if ($LoadProducts[4] < 0 && $LoadProducts[4] > 1) {
							# Seguro
							$estatus = $estatus.'7.';
						}
						
						if($acept > 0 && $coin>0 && $skuValido == 0){
							try {
								$qry = "INSERT INTO ctt_load_products(prd_sku, prd_name, prd_price, cin_id, prd_insured, srv_id, prd_english_name, prd_code_provider, prd_name_provider, prd_model,sbc_id, result)
										VALUES ('$sku', '$nombre_producto','$LoadProducts[2]', '$coin', '$LoadProducts[4]', '$LoadProducts[5]', '$LoadProducts[6]', '$LoadProducts[7]', '$LoadProducts[8]', '$LoadProducts[9]',$sbcId, 'EXITOSO')";
								$this->db->query($qry);
								$cont++;
							} catch (\Throwable $th) {
								$estatus = 'ERROR';
							}
							
							
						}else{
							
							$qry = "INSERT INTO ctt_load_products(prd_sku, prd_name, prd_price, cin_id, prd_insured, srv_id, prd_english_name, prd_code_provider, prd_name_provider, prd_model,sbc_id, result)
									VALUES ('$sku', '$nombre_producto','$LoadProducts[2]', '$coin', '$LoadProducts[4]', '$LoadProducts[5]', '$LoadProducts[6]', '$LoadProducts[7]', '$LoadProducts[8]', '$LoadProducts[9]',$sbcId, '$estatus')";
							$this->db->query($qry);
							
							$aux++;
						}
						
					}
					
					/* else{
						$aux++;
						$estatus = 'Los datos del sku podrian ser mayor de lo que debe, la moneda no pertenece, no es decimal el precio';
					} */
					
				}
				fclose($csv_file);
				
			}
		}else{
			$estatus = "Error";
		}
		
	} catch (Exception $e) {
		/* $lastid = $e; */
		$estatus = 0;
	}
	$resultados = [
		'aceptados' => $cont,
		'rechazados' => $aux,
		'estatus'	=> $estatus
	];
	return $resultados;
}
	public function validarDatos(){
		
	}
	// Listado de Proyectos  ****
	public function listResults($store)
	{
		$qry = "SELECT SUM(case when ldp.result LIKE '%2,%' then 1 ELSE 0 END) duplicidad, 
		SUM(case when ldp.result LIKE '%1,%' then 1 ELSE 0 END) SKU,
		SUM(case when ldp.result LIKE '%4,%' then 1 ELSE 0 END) moneda,
		SUM(case when ldp.result LIKE '%5,%' then 1 ELSE 0 END) costo,
		SUM(case when ldp.result LIKE '%6,%' then 1 ELSE 0 END) servicio,
		SUM(case when ldp.result LIKE '%7%' then 1 ELSE 0 END) seguro,
		SUM(case when ldp.result LIKE '%3%' then 1 ELSE 0 END) categoria, 1 as results
		FROM ctt_load_products AS ldp";
		return $this->db->query($qry);
	}
// Optiene los Documentos existentes
	public function GetDocumentos()
	{
		$qry = "SELECT prd_id, prd_sku, prd_name, prd_english_name, ldp.prd_code_provider, ldp.prd_name_provider,
		ldp.prd_model, ldp.prd_price, ldp.prd_coin_type, ldp.prd_visibility, case when ldp.prd_insured = 1 then 'Sí' ELSE 'NO' END prd_insured,
		ldp.srv_id, srv.srv_name, cn.cin_code, result FROM ctt_load_products AS ldp
		LEFT JOIN ctt_services AS srv ON srv.srv_id = ldp.srv_id
		LEFT JOIN ctt_coins AS cn ON cn.cin_id = ldp.cin_id";
		$result = $this->db->query($qry);
		//$lista = array();
		/* while ($row = $result->fetch_row()){
			$item = array("doc_id" =>$row[0],
						"doc_code" =>$row[1],
						"doc_name" =>$row[2],
						"doc_type" =>$row[3],
						"dot_id" =>$row[4],
						"dot_name" =>$row[5],
						"doc_admission_date" =>$row[6]);
			array_push($lista, $item);
		} */
		return $result;
	}

	// Optiene los Documentos existentes
	public function GetDocumentosFicha()
	{
		$qry = "SELECT doc.doc_id, doc.doc_code, doc.doc_name, doc.doc_type, doc.dot_id, td.dot_name FROM ctt_documents as doc LEFT JOIN ctt_documents_type AS td ON td.dot_id = doc.dot_id WHERE doc.dot_id = 2";
		$result = $this->db->query($qry);
		$lista = array();
		while ($row = $result->fetch_row()){
			$item = array("doc_id" =>$row[0],
						"doc_code" =>$row[1],
						"doc_name" =>$row[2],
						"doc_type" =>$row[3],
						"dot_id" =>$row[4],
						"dot_name" =>$row[5]);
			array_push($lista, $item);
		}
		return $lista;
	}

    public function GetDocumento($params)
	{
		$qry = "SELECT doc_id, doc_code, doc_name,doc_type, dot_id, doc_admission_date 
		FROM ctt_documents WHERE doc_id = ".$params['id'].";";
		$result = $this->db->query($qry);
		if($row = $result->fetch_row()){
			$item = array("doc_id" =>$row[0],
			"doc_code" =>$row[1],
			"doc_name" =>$row[2],
			"doc_type" =>$row[3],
			"dot_id" =>$row[4],
			"doc_admission_date"=>$row[5]);
		}
		return $item;
	}

	public function GetTypeDocumento($params)
	{
		$qry = "SELECT dot_id, dot_name FROM ctt_documents_type WHERE dot_status = 1;";
		$result = $this->db->query($qry);
		$lista = array();
		while ($row = $result->fetch_row()){
			$item = array("dot_id" =>$row[0],
			"dot_name" =>$row[1]);
			array_push($lista, $item);
		}
		return $lista;
	}

    public function ActualizaDocumento($request_params)
	{
        $estatus = 0;
			try {
		       if(isset($_FILES['file']['name'])){
					$conn = new mysqli(HOST, USER, PASSWORD);
					$Code = $request_params['CodDocumento'];
					$nomebreDocumento = $request_params["NomDocumento"];
					$fechaadmision = $request_params["fechaadmision"];
			
					$fileName = $_FILES['file']['name'];
					$fileSize = $_FILES['file']['size'];
					$fileType = $_FILES['file']['type'];
					$fileNameCmps = explode(".", $fileName);
					$fileExtension = strtolower(end($fileNameCmps));
					$file = mysqli_real_escape_string($conn, file_get_contents( $_FILES['file']['tmp_name']));
			
					$newFileName = $fileName;

					$qry = "UPDATE ctt_documents SET doc_code = UPPER('".$request_params['CodDocumento']."')
					,doc_name = UPPER('".$request_params['NomDocumento']."')
					,dot_id = '".$request_params['tipoDocumento']."'
					,doc_size = $fileSize
					,doc_type = '$fileExtension'
					,doc_content_type =  '$fileType'
					,doc_document = '$file'
					,doc_admission_date = '$fechaadmision
					WHERE doc_id = ".$request_params['idDocumento'].";";
					$this->db->query($qry);

					$estatus =  $request_params['idDocumento']; 

				}else {

					$qry = "UPDATE ctt_documents
					SET doc_code = UPPER('".$request_params['CodDocumento']."')
					,dot_id = '".$request_params['tipoDocumento']."'
					,doc_name = UPPER('".$request_params['NomDocumento']."')
					,doc_admission_date = '".$request_params['fechaadmision']."'
					WHERE doc_id = ".$request_params['idDocumento'].";";

					$this->db->query($qry);
					$estatus =  $request_params['idDocumento']; 

				}			   
			} catch (Exception $e) {

				$estatus = 0;
			}
		return $estatus;
	}

    //borra proveedor
	public function DeleteData($params)
	{
        $estatus = 0;
        try {
            $qry = "TRUNCATE TABLE ctt_load_products;";
            $this->db->query($qry);
            $estatus = $this->db->query($qry);
        } catch (Exception $e) {
            $estatus = 0;
        }
		return $estatus;
	}

	public function loadProcess($params)
	{
		$qry = "INSERT INTO ctt_products(
			prd_sku, prd_name,prd_english_name, prd_code_provider, prd_model, prd_price, cin_id, prd_insured, srv_id, sbc_id)
	SELECT  prd_sku, prd_name,prd_english_name, prd_code_provider, prd_model, prd_price, cin_id, prd_insured, srv_id, sbc_id
	FROM ctt_load_products a WHERE a.result = 'EXITOSO';";
		$result = $this->db->query($qry);
		$qry1 = "TRUNCATE TABLE ctt_load_products;";
        $this->db->query($qry1);
		
		return $result;
	}

}