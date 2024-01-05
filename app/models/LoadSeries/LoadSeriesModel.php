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
					/* $regis = $this->validarFecha($LoadProducts[3]);
					$down = $this->validarFecha($LoadProducts[4]);  */
					if ( $LoadProducts[3] != 'FECHA') {
						$prdId=0;
						$number =0;
						$cost = "";
						$supIdacept = 0;
						$strId = 0;
						$estatus = 'Problemas con ';
						$acept = 0;
						// Busca el id del almacen
						$qry2 = "SELECT st.str_id FROM ctt_stores AS st WHERE st.str_name = '$LoadProducts[11]' UNION SELECT 0 LIMIT 1";
						$rest = $this->db->query($qry2);
						$rst = $rest->fetch_object();
						$store = $rst->str_id;
						
						// Busca el id del proveedor
						$qry2 = "SELECT sp.sup_id FROM ctt_suppliers AS sp WHERE sp.sup_business_name = '$LoadProducts[4]' UNION SELECT 0 LIMIT 1";
						$rest = $this->db->query($qry2);
						$rst = $rest->fetch_object();
						$supplier = $rst->sup_id;

						if (strlen($LoadProducts[0]) == 15 || strlen($LoadProducts[0]) == 10) {
							# Revisar que exista un producto con la categoria y subcat que se esta introduciendo a traves de su sku
							$qry1 = "SELECT COUNT(*) cant FROM ctt_categories AS ct 
							INNER JOIN ctt_subcategories AS sb ON sb.cat_id = ct.cat_id
							INNER JOIN ctt_products AS pd ON pd.sbc_id = sb.sbc_id
							where ct.cat_id = SUBSTR('$LoadProducts[0]',1,2) 
							AND sb.sbc_code = SUBSTR('$LoadProducts[0]',3,2)
							AND pd.prd_sku = SUBSTR('$LoadProducts[0]',1,7)";
							$res = $this->db->query($qry1);
							$rs = $res->fetch_object();
							$acept = $rs->cant;

							// Hay que verificar que los ultimos digitos son numericos
							if (strlen($LoadProducts[0]) == 15) {
								$skuCsv = substr($LoadProducts[0], 12, 4);
								$qry = "SELECT COUNT(*) cant FROM ctt_load_series WHERE ser_sku = '$LoadProducts[0]'";
								$res = $this->db->query($qry);
								$rs = $res->fetch_object();
								$ser = $rs->cant;
								
								if ($ser != 0) {
									$acept = 0;
									$estatus = $estatus . "duplicidad en sku, ";
								}

								if (!is_numeric($skuCsv)) {
									$estatus = $estatus. 'SKU, ';
									$acept =0;
								}
							}elseif (strlen($LoadProducts[0]) == 10) {
								$skuCsv = substr($LoadProducts[0], 8, 3);
								if (!is_numeric($skuCsv)) {
									$estatus = $estatus. 'SKU, ';
									$acept =0;
								}
							}

							if ($acept == 0) {
								$estatus = $estatus . "sku, ";
							}else{
								// verifica que no exista ya esta serie en la tabla de series
								$qry3 = "SELECT COUNT(*) series FROM ctt_series sr WHERE sr.ser_sku = '$LoadProducts[0]'";
								$rest = $this->db->query($qry3);
								$rst = $rest->fetch_object();
								$series = $rst->series;
		
								if ($series >= 1) {
									$acept = 0;
									$estatus = $estatus . "duplicidad en sku, ";
								}else{
									// si no existe entonces se obtiene el valor del producto con el que se relaciona
									$qry4 = "SELECT prd_id FROM ctt_products pr WHERE pr.prd_sku = SUBSTR('$LoadProducts[0]',1,7) union select 0 limit 1";
									$rest = $this->db->query($qry4);
									$rst = $rest->fetch_object();
									$prdId = $rst->prd_id;
		
									if($prdId == 0){ // si por alguna razon envia 0 (que no deberia ocurrir, por los if anteriores) enviara el error marcado en el prd_id
										$estatus = $estatus . "prd_id, ";
									}
								}
							}

							
						}else{
							$estatus = $estatus . "sku, ";
						}
						// verificar que el costo es numerico
						if (is_numeric($LoadProducts[7])) {
							# Costo
							$costo = $LoadProducts[7];
						}else{
							$costo = '';
							$estatus = $estatus . "Costo, ";
						}
						// Verificar que el tipo de moneda exista
						if (strlen($LoadProducts[6]) == 3) {
							// Moneda
							$qry2 = "SELECT cn.cin_id FROM ctt_coins AS cn WHERE cn.cin_code = '$LoadProducts[6]' UNION SELECT 0 LIMIT 1";
							$rest = $this->db->query($qry2);
							$rst = $rest->fetch_object();
							$coin = $rst->cin_id;

							if ($coin == 0) {
								$estatus = $estatus . "moneda, ";
							}
						}else{
							if ($LoadProducts[6] != '') {
								$estatus = $estatus . "moneda, ";
							}
							$coin = 0;
						}

						// Verifica que el almacen este correcto
						if ($store>0 ) {
							# code...
							$strId = $store;
						} else{
							$strId = 0;
							$estatus = $estatus . "almacen";
						}

						// verifica que el proveedor este correcto
						if ($supplier > 0 || $LoadProducts[4]=='') {
							if ($LoadProducts[4]=='') {
								$supId = 0;
							}else{
								$supId = $supplier;
							}
							$supIdacept = 1;
						} else{
							$supIdacept = 0;
							$estatus = $estatus . "proveedor, ";
						}
						
						//if ( && (is_numeric($LoadProducts[1]) || $LoadProducts[1]== '') && is_numeric($LoadProducts[2])) {
							if($acept > 0 && $strId>0 && $supIdacept == 1){
								$cont++;
								$estatus = 'EXITOSO';
							}else{
								$aux++;
							}
							$qry = "INSERT INTO ctt_load_series(ser_sku, ser_brand, ser_serial_number, ser_date_registry, sup_id, ser_import_petition,cin_id, ser_cost, ser_cost_import, ser_sum_ctot_cimp,ser_no_econo,str_id, ser_situation, ser_stage, result, prd_id, ser_status)
								VALUES ('$LoadProducts[0]', '$LoadProducts[1]', '$LoadProducts[2]', '$LoadProducts[3]', '$supId', '$LoadProducts[5]', '$coin','$costo', '$LoadProducts[8]', '$LoadProducts[9]', '$LoadProducts[10]', '$strId', 'D', 'D', '$estatus', '$prdId', 1)";
							$this->db->query($qry);
					}
					
						
						// $estatus = strlen($LoadProducts[7]);
					/* }else{
						$aux++;
						$estatus = 'Revisa que los datos introducidos sean correctos';
					} */
					
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
	
// Optiene los Documentos existentes
	public function GetDocumentos()
	{
		$qry = "SELECT lds.ser_id, lds.ser_sku, lds.ser_serial_number, lds.ser_cost, lds.ser_situation, lds.ser_stage, lds.ser_date_registry,
				lds.ser_date_down, lds.ser_brand, lds.ser_cost_import, lds.ser_import_petition, lds.ser_sum_ctot_cimp,
				lds.ser_no_econo, lds.ser_comments, lds.cin_id, lds.str_id, lds.sup_id,
				sp.sup_business_name, cn.cin_code, st.str_name, result
				FROM ctt_load_series AS lds
				LEFT JOIN ctt_stores AS st ON st.str_id = lds.str_id
				LEFT JOIN ctt_coins AS cn ON cn.cin_id = lds.cin_id
				LEFT JOIN ctt_suppliers AS sp ON sp.sup_id = lds.sup_id";
		$result = $this->db->query($qry);
		
		return $result;
	}



	// Listado de Proyectos  ****
	public function listResults($store)
	{
		$qry = "SELECT SUM(case when ldp.result LIKE '% duplicidad%' then 1 ELSE 0 END) duplicidad, 
		SUM(case when ldp.result LIKE '% SKU,%' then 1 ELSE 0 END) SKU,
		SUM(case when ldp.result LIKE '% costo,%' then 1 ELSE 0 END) costo,
		SUM(case when ldp.result LIKE '% moneda,%' then 1 ELSE 0 END) moneda,
		SUM(case when ldp.result LIKE '% almacen%' then 1 ELSE 0 END) almacen,
		SUM(case when ldp.result LIKE '% proveedor%' then 1 ELSE 0 END) proveedor, 1 as results
		FROM ctt_load_series AS ldp";
		return $this->db->query($qry);
	}
	public function DeleteData($params)
	{
        $estatus = 0;
        try {
            $qry = "TRUNCATE TABLE ctt_load_series;";
            $this->db->query($qry);
            $estatus = $this->db->query($qry);
        } catch (Exception $e) {
            $estatus = 0;
        }
		return $estatus;
	}

	public function loadProcess($params)
	{
		$qry = "INSERT INTO ctt_series_paso(
			ser_sku, ser_serial_number,ser_cost, ser_situation, ser_stage,ser_behaviour, ser_brand,ser_cost_import,ser_import_petition,ser_sum_ctot_cimp, ser_no_econo, ser_comments, cin_id, str_id, sup_id, prd_id, ser_status)
	SELECT  ser_sku, ser_serial_number,ser_cost, ser_situation, ser_stage,ser_behaviour, ser_brand,ser_cost_import,ser_import_petition,ser_sum_ctot_cimp, ser_no_econo, ser_comments, cin_id, str_id, sup_id, prd_id, ser_status
	FROM ctt_load_series a WHERE a.result = 'EXITOSO';";
		$result = $this->db->query($qry);
		$qry1 = "TRUNCATE TABLE ctt_load_series;";
        $this->db->query($qry1);
		
		return $result;
	}
}