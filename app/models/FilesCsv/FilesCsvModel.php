<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class FilesCsvModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

//Guarda proveedor    *****
public function SaveDocumento($request_params)
{
	$estatus = 0;
	try {
		$fileName = $_FILES['file']['name'];
		$fileSize = $_FILES['file']['size'];
		$fileType = $_FILES['file']['type'];
		$fileNameCmps = explode(".", $fileName);
		$fileExtension = strtolower(end($fileNameCmps));

		$file_mimes = array('text/x-comma-separated-values', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv');
		if($fileName && in_array($fileType, $file_mimes)){
			if(is_uploaded_file($_FILES['file']['tmp_name'])){
				$cont = 0;
				$aux = 0;
				$csv_file = fopen($_FILES['file']['tmp_name'], 'r');
				while(($filesCsv = fgetcsv($csv_file)) !== FALSE){
					if (strlen($filesCsv[0]) <= 10 && strlen($filesCsv[0]) > 6 && strlen($filesCsv[7]) == 3 && is_numeric($filesCsv[6]) && is_numeric($filesCsv[8]) && is_numeric($filesCsv[9]) && ($filesCsv[8] == '0' || $filesCsv[8] == '1')) {
						
						$qry1 = "SELECT COUNT(*) cant FROM ctt_categories AS ct 
							INNER JOIN ctt_subcategories AS sb ON sb.cat_id = ct.cat_id
							where ct.cat_id = SUBSTR('$filesCsv[0]',1,2) AND sb.sbc_code = SUBSTR('$filesCsv[0]',3,2)";
						$res = $this->db->query($qry1);
						$rs = $res->fetch_object();
						$acept = $rs->cant;

						$qry2 = "SELECT cn.cin_id FROM ctt_coins AS cn WHERE cn.cin_code = '$filesCsv[7]' UNION SELECT 0 LIMIT 1";
						$rest = $this->db->query($qry2);
						$rst = $rest->fetch_object();
						$coin = $rst->cin_id;


						if($acept > 0 && $coin>0){
							$qry = "INSERT INTO ctt_load_products(prd_sku, prd_name, prd_english_name, prd_code_provider, prd_name_provider, prd_model, prd_price, cin_id, prd_insured, srv_id)
									VALUES ('$filesCsv[0]', '$filesCsv[1]','$filesCsv[2]', '$filesCsv[3]', '$filesCsv[4]', '$filesCsv[5]', '$filesCsv[6]', '$coin', '$filesCsv[8]', '$filesCsv[9]')";
							$this->db->query($qry);
							$cont++;
							$estatus = strlen($filesCsv[7]);
						}else{
							$aux++;
							$estatus = 'Hubo un problema con el sku o la moneda';
						}
					}else{
						$aux++;
						$estatus = 'Los datos del sku podrian ser mayor de lo que debe, la moneda no pertenece, no es decimal el precio ';
					}
					
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
// Optiene los Documentos existentes
	public function GetDocumentos()
	{
		$qry = "SELECT prd_id, prd_sku, prd_name, prd_english_name, ldp.prd_code_provider, ldp.prd_name_provider,
		ldp.prd_model, ldp.prd_price, ldp.prd_coin_type, ldp.prd_visibility, case when ldp.prd_insured = 1 then 'Sí' ELSE 'NO' END prd_insured,
		ldp.srv_id, srv.srv_name FROM ctt_load_products AS ldp
		INNER JOIN ctt_services AS srv ON srv.srv_id = ldp.srv_id
		INNER JOIN ctt_coins AS cn ON cn.cin_id = ldp.cin_id";
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
	public function DeleteDocumentos($params)
	{
        $estatus = 0;
        try {
            $qry = "DELETE FROM ctt_documents
                    WHERE doc_id in (".$params['IdDocumento'].")";
            $this->db->query($qry);
            $estatus = $qry;
        } catch (Exception $e) {
            $estatus = 0;
        }
		return $estatus;
	}

	public function verDocumento($params)
	{
		$qry = "SELECT doc_name, doc_type, doc_size, doc_content_type, doc_document FROM ctt_documents WHERE doc_id =  ".$params['id'].";";
		$result = $this->db->query($qry);
		if($row = $result->fetch_row()){
			$item = array("doc_name" =>$row[0],
			"doc_type" =>$row[1],
			"doc_size" =>$row[2],
			"doc_content_type" =>$row[3],
			"doc_document" =>base64_encode($row[4]));
		}
		return $item;
	}

}