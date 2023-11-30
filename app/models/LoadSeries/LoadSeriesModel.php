<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class LoadSeriesModel extends Model
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
					/* $regis = $this->validarFecha($filesCsv[3]);
					$down = $this->validarFecha($filesCsv[4]);  */
					if (strlen($filesCsv[0]) <= 15 && strlen($filesCsv[0]) > 9 && (is_numeric($filesCsv[1]) || $filesCsv[1]== '') && is_numeric($filesCsv[2])) {
						
						$qry1 = "SELECT COUNT(*) cant FROM ctt_categories AS ct 
						INNER JOIN ctt_subcategories AS sb ON sb.cat_id = ct.cat_id
						INNER JOIN ctt_products AS pd ON pd.sbc_id = sb.sbc_id
						where ct.cat_id = SUBSTR('$filesCsv[0]',1,2) 
						AND sb.sbc_code = SUBSTR('$filesCsv[0]',3,2)
						AND pd.prd_sku = SUBSTR('$filesCsv[0]',1,7)";
						$res = $this->db->query($qry1);
						$rs = $res->fetch_object();
						$acept = $rs->cant;

						$qry2 = "SELECT cn.cin_id FROM ctt_coins AS cn WHERE cn.cin_code = '$filesCsv[11]' UNION SELECT 0 LIMIT 1";
						$rest = $this->db->query($qry2);
						$rst = $rest->fetch_object();
						$coin = $rst->cin_id;

						$qry2 = "SELECT st.str_id FROM ctt_stores AS st WHERE st.str_name = '$filesCsv[12]' UNION SELECT 0 LIMIT 1";
						$rest = $this->db->query($qry2);
						$rst = $rest->fetch_object();
						$store = $rst->str_id;

						$qry2 = "SELECT sp.sup_id FROM ctt_suppliers AS sp WHERE sp.sup_business_name = '$filesCsv[13]' UNION SELECT 0 LIMIT 1";
						$rest = $this->db->query($qry2);
						$rst = $rest->fetch_object();
						$supplier = $rst->sup_id;


						if($acept > 0 && $coin>0 && $store>0 && ($supplier>0 || $filesCsv[13]=='')){
							$qry = "INSERT INTO ctt_load_series(ser_sku, ser_serial_number, ser_cost, ser_date_registry, ser_date_down, ser_brand, ser_cost_import, ser_import_petition, ser_sum_ctot_cimp,ser_no_econo,ser_comments,cin_id,str_id,sup_id, ser_situation, ser_stage)
									VALUES ('$filesCsv[0]', '$filesCsv[1]','$filesCsv[2]', '$filesCsv[3]', '$filesCsv[4]', '$filesCsv[5]', '$filesCsv[6]', '$filesCsv[7]', '$filesCsv[8]', '$filesCsv[9]', '$filesCsv[10]', '$coin', '$store', '$supplier', 'D', 'D')";
							$this->db->query($qry);
							$cont++;
							$estatus = strlen($filesCsv[7]);
						}else{
							$aux++;
							$estatus = 'Hubo un problema con el sku, la moneda, el almacen o el proveedor';
						}
					}else{
						$aux++;
						$estatus = 'Revisa que los datos introducidos sean correctos';
					}
					
				}
				fclose($csv_file);
			}
		}else{
			$estatus = "Error";
		}
		
	} catch (Exception $e) {
		$estatus = 0;
	}
	$resultados = [
		'aceptados' => $cont,
		'rechazados' => $aux,
		'estatus'	=> $estatus
	];
	return $resultados;
}
	public function validarFecha($fecha){
		/* if (checkdate(date('m',$fecha), date('d',$fecha),date('y',$fecha))) {
			return true;
		}else{
			return false;
		} */
	}
// Optiene los Documentos existentes
	public function GetDocumentos()
	{
		$qry = "SELECT lds.ser_id, lds.ser_sku, lds.ser_serial_number, lds.ser_cost, lds.ser_situation, lds.ser_stage, lds.ser_date_registry,
				lds.ser_date_down, lds.ser_brand, lds.ser_cost_import, lds.ser_import_petition, lds.ser_sum_ctot_cimp,
				lds.ser_no_econo, lds.ser_comments, lds.cin_id, lds.str_id, lds.sup_id,
				sp.sup_business_name, cn.cin_code, st.str_name
				FROM ctt_load_series AS lds
				LEFT JOIN ctt_stores AS st ON st.str_id = lds.str_id
				LEFT JOIN ctt_coins AS cn ON cn.cin_id = lds.cin_id
				LEFT JOIN ctt_suppliers AS sp ON sp.sup_id = lds.sup_id";
		$result = $this->db->query($qry);
		
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