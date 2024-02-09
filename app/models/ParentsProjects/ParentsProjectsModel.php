<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class ParentsProjectsModel extends Model
{
    public function __construct()
    {
      parent::__construct();
    }

// Listado de usuarios de programacion   ******
    public function listUsersP($params)
    {
        $pjt_id = $this->db->real_escape_string($params['pjt_id']);

        $qry = "SELECT usr.usr_id,emp.emp_id, emp.emp_fullname,emp.emp_number 
                FROM ctt_users AS usr
                RIGHT JOIN ctt_employees AS emp ON emp.emp_id=usr.emp_id
                WHERE (emp.emp_id != 1 OR emp.emp_fullname != 'Super Usuario')
                AND are_id in (1,5);";

        return $this->db->query($qry);
    }
//  Lista de usuarios de camara  ***********
    public function listUsersC($params)
    {
        $pjt_id = $this->db->real_escape_string($params['pjt_id']);

        $qry = "SELECT usr.usr_id,emp.emp_id, emp.emp_fullname,emp.emp_number FROM ctt_users AS usr
                RIGHT JOIN ctt_employees AS emp ON emp.emp_id=usr.emp_id
                WHERE (emp.emp_id != 1 OR emp.emp_fullname != 'Super Usuario')
                AND are_id in (2);";

        return $this->db->query($qry);
    }

    //  Lista de usuarios de almacen Iluminacion  ***********
    public function listUsersA($params)
    {
        $pjt_id = $this->db->real_escape_string($params['pjt_id']);

        $qry = "SELECT usr.usr_id,emp.emp_id, emp.emp_fullname,emp.emp_number FROM ctt_users AS usr
                RIGHT JOIN ctt_employees AS emp ON emp.emp_id=usr.emp_id
                WHERE (emp.emp_id != 1 OR emp.emp_fullname != 'Super Usuario')
                AND are_id in (3);";

        return $this->db->query($qry);
    }

   
// Listado de Productos de Proyecto asigando
    public function listDetailProds($params)
    {
        $pjt_id = $this->db->real_escape_string($params['pjt_id']);

        $qry = "SELECT pj.pjt_id, pj.pjt_name, pj.pjt_number, pt.pjttp_name,
                DATE_FORMAT(pj.pjt_date_start,'%d/%m/%Y') AS pjt_date_start, 
                DATE_FORMAT(pj.pjt_date_end,'%d/%m/%Y') AS pjt_date_end, 
                DATE_FORMAT(pj.pjt_date_last_motion,'%d/%m/%Y %H:%i ') AS pjt_date_project, 
                pj.pjt_location, pj.pjt_status,pj.pjt_id, pj.pjt_whomake, pj.pjt_whoattend
                FROM ctt_projects AS pj
                INNER JOIN ctt_projects_type As pt ON pt.pjttp_id = pj.pjttp_id 
                WHERE pj.pjt_status in (40) 
                ORDER BY pjt_date_start ASC;";
        return $this->db->query($qry);
    }
    
    // Listado de Productos de Proyecto asigando
    public function listUsersOnProj($params)
    {
        $pjtid = $this->db->real_escape_string($params['pjt_id']);

        $qry = "SELECT whoatd_id,pjt_id,usr_id,emp_id,emp_fullname,are_id 
                FROM ctt_who_attend_projects
                WHERE pjt_id=$pjtid";
        return $this->db->query($qry);
    }

    /** ==== Obtiene el contenido del proyecto =============================================================  */
    public function updateUsers($params)
    {
        $pjtid		= $this->db->real_escape_string($params['pjtid']);
        $areid		= $this->db->real_escape_string($params['areid']);
        $empid		= $this->db->real_escape_string($params['empid']);
        $empname	= $this->db->real_escape_string($params['empname']);
        $usrid	    = $this->db->real_escape_string($params['usrid']);

        $qry = "SELECT fun_updateuser($pjtid,$areid,$empid,'$empname',$usrid) 
                AS valresult
                FROM DUAL;";  

        return $this->db->query($qry);
    }

    public function getProject($params)
    {
        $pjt_id = $this->db->real_escape_string($params['pjt_id']);

        $qry = "SELECT pj.pjt_id, pj.pjt_name, pj.pjt_parent, pj.pjt_number, DATE_FORMAT(pj.pjt_date_project,'%d/%m/%Y') AS pjt_date_project, 
        DATE_FORMAT(pj.pjt_date_start,'%d/%m/%Y') AS pjt_date_start, 
        DATE_FORMAT(pj.pjt_date_end,'%d/%m/%Y') AS pjt_date_end,
        pj.pjt_date_last_motion, pj.pjt_time, pj.pjt_location, pj.pjt_status,
        pj.pjt_how_required, pj.pjt_trip_go, pj.pjt_trip_back,pj.pjt_to_carry_on, 
        pj.pjt_to_carry_out, pj.pjt_test_tecnic, pj.pjt_test_look, pj.cuo_id,
        pj.loc_id, pj.pjttp_id, pj.pjttc_id, pj.edos_id, pj.pjt_whomake, pj.pjt_whoattend,
        cuo.cus_id, cuo.cus_parent
         FROM ctt_projects AS pj 
         LEFT JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
         WHERE pj.pjt_id = $pjt_id ";
        return $this->db->query($qry);
    }

    public function listCustomers($params)
    {
        $prd = $this->db->real_escape_string($params['prm']);
        
        $qry = "SELECT cs.cus_id,cs.cus_name,cs.cus_address,cs.cus_email,cs.cus_phone,cs.cus_qualification, ct.cut_name, cs.cut_id
                FROM ctt_customers AS cs
                INNER JOIN ctt_customers_type AS ct ON ct.cut_id = cs.cut_id
                WHERE cs.cus_status = 1 ORDER BY cs.cus_name;";
        return $this->db->query($qry);
    }    
public function listProjectsType($params)
    {
        $qry = "SELECT * FROM ctt_projects_type ORDER BY pjttp_name;";
        return $this->db->query($qry);
    }    
    
public function listProjectsTypeCalled($params)
    {
        $qry = "SELECT * FROM ctt_projects_type_called ORDER BY pjttc_id";
        return $this->db->query($qry);
    }    
    public function getLocationType(){
        $qry = "SELECT loc_id, loc_type_location FROM ctt_location; ";
        return $this->db->query($qry);
    } 
    public function getLocations($params){
        $pjtid = $this->db->real_escape_string($params['pjtid']);
        $qry = "SELECT lce_id, lce_location FROM ctt_locacion_estado AS lce
        INNER JOIN ctt_projects AS pj ON pj.pjt_id = lce.pjt_id
        WHERE pj.pjt_parent = $pjtid; ";
        return $this->db->query($qry);
    }
    // ELIMINAR UN REGISTRO DE UN CLIENTE
    public function CancelParentsProjects($params, $userParam)
    {
        $pjtid = $this->db->real_escape_string($params['pjtid']);
        $group = explode('|',$userParam);
    
        $user = $group[0];
        $name = $group[2];
        
        $qry3 = "UPDATE ctt_projects SET pjt_status = 5 where pjt_parent = $pjtid AND pjt_status > 3";
        $this->db->query($qry3);

        $qry  = "INSERT ctt_activity_log (log_date, log_event, emp_number, emp_fullname, acc_id) 
        VALUES(current_time(), 'PRE-CANCELADO','$user','$name', 1)"; // AQUI REQUIERE EL ID DE ACC_ID CORRESPONDIENTE
        $this->db->query($qry);

        return $pjtid;
    }
    public function UpdateProject($params)
    {
        $cuo_id         = $this->db->real_escape_string($params['cuoId']);
        $cus_id         = $this->db->real_escape_string($params['cusId']); 
        $cus_Parent     = $this->db->real_escape_string($params['cusParent']);
    
        $qry01 = "  UPDATE      ctt_customers_owner 
                        SET     cus_id      = '$cus_id', 
                                cus_parent  = '$cus_Parent'
                        WHERE   cuo_id      = '$cuo_id';";
    
        $this->db->query($qry01);
    
        $pjt_id                 = $this->db->real_escape_string($params['projId']); 
        $pjt_name               = $this->db->real_escape_string($params['pjtName']); 
        $pjt_time               = $this->db->real_escape_string($params['pjtTime']); 
        $pjt_location           = $this->db->real_escape_string($params['pjtLocation']);
        $pjt_type               = $this->db->real_escape_string($params['pjtType']);
        $pjttc_id               = $this->db->real_escape_string($params['pjttcId']);
        $loc_id                 = $this->db->real_escape_string($params['locId']);
        $pjt_how_required       = $this->db->real_escape_string($params['pjtHowRequired']);
        $pjt_trip_go            = $this->db->real_escape_string($params['pjtTripGo']);
        $pjt_trip_back          = $this->db->real_escape_string($params['pjtTripBack']);
        $pjt_to_carry_on        = $this->db->real_escape_string($params['pjtToCarryOn']);
        $pjt_to_carry_out       = $this->db->real_escape_string($params['pjtToCarryOut']);
        $pjt_test_tecnic        = $this->db->real_escape_string($params['pjtTestTecnic']);
        $pjt_test_look          = $this->db->real_escape_string($params['pjtTestLook']);
        
        $qry02 = "UPDATE    ctt_projects
                SET    pjt_name            = UPPER('$pjt_name'), 
                        pjt_time            = '$pjt_time',
                        pjt_how_required    = UPPER('$pjt_how_required'),
                        pjt_location        = UPPER('$pjt_location'),
                        pjt_trip_go         = '$pjt_trip_go',
                        pjt_trip_back       = '$pjt_trip_back',
                        pjt_to_carry_on     = '$pjt_to_carry_on',
                        pjt_to_carry_out    = '$pjt_to_carry_out',
                        pjt_test_tecnic     = '$pjt_test_tecnic',
                        pjt_test_look       = '$pjt_test_look',
                        pjttp_id            = '$pjt_type',  
                        cuo_id              = '$cuo_id',
                        loc_id              = '$loc_id',
                        pjttc_id            = '$pjttc_id'
                WHERE   pjt_id              =  $pjt_id;
                ";								
        $this->db->query($qry02);

        $qry = "UPDATE    ctt_projects
                SET    pjt_time            = '$pjt_time',
                        pjt_how_required    = UPPER('$pjt_how_required'),
                        pjt_location        = UPPER('$pjt_location'),
                        pjttp_id            = '$pjt_type',  
                        cuo_id              = '$cuo_id',
                        loc_id              = '$loc_id',
                        pjttc_id            = '$pjttc_id'
                WHERE   pjt_parent              =  $pjt_id;
                ";								
        $this->db->query($qry);
    
        return $pjt_id;
    }
    
}
