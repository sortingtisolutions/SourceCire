<?php 
/** v.1.2.2
*/
class LoginModel extends Model
{

	
public function __construct()
{
	parent::__construct();
}

/**** Obtiene los datos del usuarios logueado ******/
	public function signIn($employee)
	{
		$employee = $this->db->real_escape_string($employee);

		$sql = "SELECT *, '0' as mod_id 
				FROM ctt_users AS usr 
				INNER JOIN ctt_employees AS emp ON emp.emp_id = usr.emp_id
				INNER JOIN ctt_profiles AS prf ON prf.prf_id = usr.prf_id 
				WHERE emp.emp_number = '{$employee}'";
				
		return $this->db->query($sql);
	}

	public function registerAcces($employee,$UserDb)
	{
		$empl = $this->db->real_escape_string($employee);
		$userdb = $this->db->real_escape_string($UserDb);

		$qry = "INSERT INTO Register_aplication_access(reg_user_app, reg_user_db) 
		VALUES ('{$empl}','{$userdb}');";

		$this->db->query($qry);
		$reg_id = $this->db->insert_id;

		return $reg_id;
		// return $this->db->query($qry);
	}
}