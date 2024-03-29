<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class UsuariosModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}


// Optiene los Usuaios existentes *****
	public function GetUsuarios($params)
	{
		$qry = "SELECT u.usr_id, u.usr_username, e.emp_fullname, e.emp_number,emp_email, 
					p.prf_name, u.usr_dt_registry, u.usr_dt_last_access, p.prf_id
				FROM ctt_users as u
				LEFT JOIN ctt_employees as e on e.emp_id = u.emp_id
				LEFT JOIN ctt_profiles as p on p.prf_id = u.prf_id
				WHERE u.usr_status = '1' and u.emp_id > '1';";
		return $this->db->query($qry);
	}

	public function GetUsuario($params)
	{
		//Optenemos los reportes asociados al usuario
		$qry = "SELECT mod_id FROM ctt_users_modules WHERE usr_id = '".$params['id']."'";
		$result = $this->db->query($qry);
		$modulesAsing = "";

		while ($row = $result->fetch_row()){
			$modulesAsing .= $row[0].",";
		}
		$modulesAsing = substr($modulesAsing, 0, -1);

		$qry = "SELECT u.usr_id, u.usr_username, e.emp_fullname, e.emp_number, p.prf_name, 
					   u.usr_dt_registry, u.usr_dt_last_access ,p.prf_id , e.emp_area, e.emp_id , 
					   e.emp_report_to , e.pos_id, u.usr_password, e.are_id
				FROM ctt_users AS u
				INNER JOIN ctt_employees as e on e.emp_id = u.emp_id
		        LEFT JOIN ctt_profiles as p on p.prf_id = u.prf_id
				where u.usr_id =  ".$params['id'].";";
		$result = $this->db->query($qry);
		
		if($row = $result->fetch_row()){
			$item = array("usr_id" =>$row[0],
			"usr_username" =>$row[1],
			"emp_fullname"=>$row[2],
			"emp_number"=>$row[3],
			"prf_name"=>$row[4],
			"usr_dt_registry"=>$row[5],
			"usr_dt_last_access"=>$row[6],
			"prf_id"=>$row[7],
			"emp_area"=>$row[8],
			"emp_id"=>$row[9],
			"emp_report_to"=>$row[10],	
			"pos_id"=>$row[11],
			"usr_password"=>$row[12],
			"are_id"=>$row[13],
			"modulesAsing"=>$modulesAsing);
		}
		return $item;
	}

	
	public function SaveUsuario($params)
	{
        $estatus = 0;
			try {

				$pass = password_hash($this->db->real_escape_string($params['PassUsuario']), PASSWORD_DEFAULT);
				$NumEmpUsuario = $this->db->real_escape_string($params['NumEmpUsuario']);
				$NomUsuario = $this->db->real_escape_string($params['NomUsuario']);
				$AreaEmpUsuario = $this->db->real_escape_string($params['AreaEmpUsuario']);

				$IdUsuario = $this->db->real_escape_string($params['IdUsuario']);
				$idPuesto = $this->db->real_escape_string($params['idPuesto']);
				$EmpIdUsuario = $this->db->real_escape_string($params['EmpIdUsuario']);
				$empEmail = $this->db->real_escape_string($params['empEmail']);

				$NumEmpUsuario = $this->db->real_escape_string($params['NumEmpUsuario']);
				$UserNameUsuario = $this->db->real_escape_string($params['UserNameUsuario']);
				$idPerfil = $this->db->real_escape_string($params['idPerfil']);
				$idUserReport = $this->db->real_escape_string($params['idUserReport']);

				$areaNombre = $this->db->real_escape_string($params['areaNombre']);
				//Inserta Usuario-Empleado
				$qry = "INSERT into ctt_employees(emp_number, emp_fullname, emp_area, emp_email, emp_status, pos_id, emp_report_to,are_id) 
				values('$NumEmpUsuario','$NomUsuario', '$areaNombre','$empEmail', 1,'$idPuesto','$idUserReport','$AreaEmpUsuario');";
				$this->db->query($qry);

				//optiene id de Usuario insertado
				$lastid = $this->db->insert_id;
				

				//Inserta Usuario
				$qry = "INSERT into ctt_users (usr_username, usr_password, usr_dt_registry, emp_id, prf_id,usr_status) 
				      values('$UserNameUsuario','$pass',NOW(),'$lastid', '$idPerfil' ,1);";
				$this->db->query($qry);

				$lastidUser = $this->db->insert_id;
				//inserta relacion modulo perfil
				$arrayModules = explode(",", $params['modulesAsig']);
				foreach ($arrayModules as $id) {
					$qry = "INSERT into ctt_users_modules (usr_id,mod_id) values ('$lastidUser','$id');";
					$this->db->query($qry);
				}
				$estatus = $lastidUser;
			} catch (Exception $e) {
				$estatus = 0;
				//echo 'Excepción capturada: ',  $e->getMessage(), "\n";
			}
		return $estatus;
	}

	public function ActualizaUsuario($params)
	{
        $estatus = 0;
			try {
				$IdUsuario = $this->db->real_escape_string($params['IdUsuario']);
				$UserNameUsuario = $this->db->real_escape_string($params['UserNameUsuario']);
				$idPerfil = $this->db->real_escape_string($params['idPerfil']);
				$PassUsuario = $this->db->real_escape_string($params['PassUsuario']);

				$NumEmpUsuario = $this->db->real_escape_string($params['NumEmpUsuario']);
				$NomUsuario = $this->db->real_escape_string($params['NomUsuario']);
				$AreaEmpUsuario = $this->db->real_escape_string($params['AreaEmpUsuario']);
				$EmpIdUsuario = $this->db->real_escape_string($params['EmpIdUsuario']);
				$areaNombre = $this->db->real_escape_string($params['areaNombre']);
				$empEmail = $this->db->real_escape_string($params['empEmail']);

				$idPuesto = $this->db->real_escape_string($params['idPuesto']); // 11-10-23

				$qry = "SELECT usr_password FROM ctt_users WHERE usr_id = $IdUsuario;";

				$result = $this->db->query($qry);
				if ($row = $result->fetch_row()) {
					$pass = trim($row[0]);
				}

				$password = "";
				if ($PassUsuario!='') {
					if($pass == $PassUsuario){
						$password = $PassUsuario;
					}else{
						$password = password_hash($this->db->real_escape_string($params['PassUsuario']), PASSWORD_DEFAULT);
					}
	
					//Actualiza Usuario
					$qry = "UPDATE ctt_users 
							SET usr_username = '$UserNameUsuario',
								usr_password = '$password',
								usr_dt_change_pwd = now(),
								prf_id = '$idPerfil'
							WHERE usr_id =$IdUsuario;";
					$this->db->query($qry);
	
				} else {
					//Actualiza Usuario
					$qry = "UPDATE ctt_users 
							SET usr_username = '$UserNameUsuario',
								prf_id = '$idPerfil'
							WHERE usr_id =$IdUsuario;";
					$this->db->query($qry);
	
				}
				//Actualiza Empledo
				$qry = "UPDATE ctt_employees
						SET emp_number = '$NumEmpUsuario',
							emp_fullname = '$NomUsuario',
							emp_area = '$areaNombre', 
							emp_email ='$empEmail', 
							pos_id = '$idPuesto',
							are_id = '$AreaEmpUsuario'
						WHERE emp_id = $EmpIdUsuario";
				$this->db->query($qry); // 11-10-23

				//Borra los modulos asignados anteriormente al usuario 
				$qry = "DELETE FROM ctt_users_modules WHERE usr_id ='$IdUsuario'";
				$result = $this->db->query($qry);

				//inserta relacion modulo perfil
				$arrayModules = explode(",", $params['modulesAsig']);
				foreach ($arrayModules as $id) {
					$qry = "INSERT into ctt_users_modules (usr_id,mod_id) values ($IdUsuario,$id);";
					$this->db->query($qry);
				}
				$estatus = $IdUsuario;
			} catch (Exception $e) {
				$estatus = 0;
				//echo 'Excepción capturada: ',  $e->getMessage(), "\n";
			}
		return $estatus;
	}

	//borra usuario
	public function DeleteUsuario($params)
	{
		$IdUsuario = $this->db->real_escape_string($params['IdUsuario']);
		$estatus = 0;
			try {
				$qry = "DELETE FROM  ctt_users
						WHERE usr_id in (".$params['IdUsuario'].");";
				$this->db->query($qry);

				$qry2 = "DELETE FROM ctt_users_modules
						WHERE usr_id = '$IdUsuario' ;";
				$this->db->query($qry2);
				
				$estatus = 1;
			} catch (Exception $e) {
				$estatus = 0;
				//echo 'Excepción capturada: ',  $e->getMessage(), "\n";
			}
		return $estatus;
	}

	// Listado de Areas
    public function listAreas()
    {
        // $qry = "SELECT * FROM ctt_areas WHERE are_status = 1 ORDER BY are_id;";
        // return $this->db->query($qry);
    }
}