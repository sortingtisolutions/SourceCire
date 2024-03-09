<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class ProjectCancelModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    //LISTA DE PROYECTOS A CANCELAR ****
    public function listProjects($params)
    {
        $liststat = $this->db->real_escape_string($params['liststat']);

        $qry = "SELECT pj.pjt_id, pj.pjt_number, pj.pjt_name, 
		DATE_FORMAT(pj.pjt_date_project,'%d/%m/%Y') AS date_regs,
		DATE_FORMAT(pj.pjt_date_start,'%d/%m/%Y') AS date_ini,
		DATE_FORMAT(pj.pjt_date_end,'%d/%m/%Y') AS date_end,
		cl.cus_name, ps.pjs_name
		FROM ctt_projects AS pj
		LEFT JOIN ctt_customers_owner AS co ON co.cuo_id = pj.cuo_id
		LEFT JOIN ctt_customers AS cl ON cl.cus_id = co.cus_id
		INNER JOIN ctt_projects_status AS ps ON ps.pjs_status=pj.pjt_status
		WHERE pj.pjt_status IN ($liststat);";
        return $this->db->query($qry);

    }
    
/** Habilita el projecto                                                           ====  */    
    public function EnableProject($params)
    {
        $pjtId = $this->db->real_escape_string($params['pjtId']);
        $qr1 = "UPDATE ctt_projects
                SET pjt_status = '4'
                WHERE pjt_id = $pjtId;";
        
        $this->db->query($qr1);

        return $pjtId;
    }

/** Elimina los periodos de las series correspondientes al periodo                 ====  */
    public function cleanPeriods($params)
    {
        $pjtId = $this->db->real_escape_string($params);
        $qry = "DELETE FROM ctt_projects_periods WHERE pjtdt_id IN (
                    SELECT DISTINCT pjtdt_id FROM ctt_projects_detail AS pdt 
                    INNER JOIN ctt_projects_content AS pcn ON pcn.pjtvr_id = pdt.pjtvr_id
                    WHERE pcn.pjt_id = $pjtId );";
        return $this->db->query($qry);
    }

/** Restaura las series del proyecto a productos disponibles                       ====  */
    public function restoreSeries($params)
    {
        $pjtId = $this->db->real_escape_string($params);
        /* $qry = "UPDATE ctt_series 
                SET ser_situation = 'D', ser_stage ='D', pjtdt_id = 0 
                WHERE pjtdt_id IN (
                    SELECT DISTINCT pjtdt_id FROM ctt_projects_detail AS pdt 
                    INNER JOIN ctt_projects_content AS pcn ON pcn.pjtvr_id = pdt.pjtvr_id
                    WHERE pcn.pjt_id = $pjtId AND pdt.sttd_id = 1);";
        $this->db->query($qry); */

        // Hay que editar
        /* $qry1 = "SELECT pd.prd_id, sr.pjtdt_id ser_pjtdt_id, pdt.pjtdt_id, pdt.sttd_id FROM ctt_series AS sr
        INNER JOIN ctt_products AS pd ON pd.prd_id = sr.prd_id
        INNER JOIN ctt_projects_detail AS pdt ON pdt.ser_id = sr.ser_id
        INNER JOIN ctt_projects_content AS pcn ON pcn.pjtvr_id = pdt.pjtvr_id
        WHERE pcn.pjt_id = $pjtId AND pdt.sttd_id = 1 GROUP BY pd.prd_id;";

        $result = $this->db->query($qry1);

        while ($row = $result->fetch_assoc()) {
            $prdId = $row["prd_id"];
            $serPjtdtId = $row["ser_pjtdt_id"];
            $pjtdtId = $row["pjtdt_id"];
            if ($serPjtdtId == $pjtdtId) {

                $qry = "UPDATE ctt_series 
                        SET ser_situation = 'D', ser_stage ='D', pjtdt_id = 0 
                        WHERE pjtdt_id = $pjtdtId;";
                $this->db->query($qry);
            }

            $updQry = "UPDATE ctt_products SET prd_reserved = (SELECT COUNT(*) FROM ctt_stores_products AS sp
            INNER JOIN ctt_series AS sr ON sr.ser_id = sp.ser_id
            INNER JOIN ctt_products AS pd ON pd.prd_id = sr.prd_id
            INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
            WHERE pd.prd_id = $prdId AND sr.ser_situation != 'D') WHERE prd_id =$prdId";
            
            $updt = $this->db->query($updQry);
            
        } */
        $qry = "UPDATE ctt_series 
                SET ser_situation = 'D', ser_stage ='D', pjtdt_id = 0 
                WHERE pjtdt_id IN (
                    SELECT DISTINCT pjtdt_id FROM ctt_projects_detail AS pdt 
                    INNER JOIN ctt_projects_content AS pcn ON pcn.pjtvr_id = pdt.pjtvr_id
                    WHERE pcn.pjt_id = $pjtId
                );";
        $this->db->query($qry);

        // MODIFICAMOS LA CANTIDAD EN RESERVA, QUITANDO LO QUE SE RESERVO.
        $qry1 = "SELECT pd.prd_id, COUNT(*) cant FROM ctt_series AS sr
        INNER JOIN ctt_products AS pd ON pd.prd_id = sr.prd_id
        INNER JOIN ctt_projects_detail AS pdt ON pdt.ser_id = sr.ser_id
        INNER JOIN ctt_projects_content AS pcn ON pcn.pjtvr_id = pdt.pjtvr_id
        WHERE pcn.pjt_id = $pjtId AND pdt.sttd_id = 1 GROUP BY pd.prd_id;";

        $result = $this->db->query($qry1);

        while ($row = $result->fetch_assoc()) {
            $reservas = $row["cant"];
            $prdId = $row["prd_id"];
            $updQry = "UPDATE ctt_products SET prd_reserved = (SELECT COUNT(*) FROM ctt_stores_products AS sp
            INNER JOIN ctt_series AS sr ON sr.ser_id = sp.ser_id
            INNER JOIN ctt_products AS pd ON pd.prd_id = sr.prd_id
            INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
            WHERE pd.prd_id = $prdId AND sr.ser_situation != 'D') WHERE prd_id =$prdId";

            $updt = $this->db->query($updQry);
        }
        return 1;
    }

/** Elimina los registros del detalle del proyecto                                 ====  */
    public function cleanDetail($params)
    {
        $pjtId = $this->db->real_escape_string($params);
        $qry = "DELETE FROM ctt_projects_detail WHERE pjtvr_id IN  (
                    SELECT pjtvr_id FROM ctt_projects_content 
                    WHERE pjt_id = $pjtId );";
        return $this->db->query($qry);
    }

/** Cancela el proyecto definitivamente                                            ====  */
    public function CancelProject($params, $userParam)
    {
        /* Actualiza el estado en 6, status de cancelado definitivamente   */
        $pjtId = $this->db->real_escape_string($params['pjtId']);
        $group = explode('|',$userParam);
    
        $user = $group[0];
        $name = $group[2];
        $qr1 = "UPDATE ctt_projects
                SET pjt_status = '6'
                WHERE pjt_id = $pjtId;";
        
        $this->db->query($qr1);

        $qr3 = "UPDATE ctt_subletting SET sub_date_end = now() WHERE prj_id = $pjtId;";
        $this->db->query($qr3);

        $qry  = "INSERT ctt_activity_log (log_date, log_event, emp_number, emp_fullname, acc_id) 
        VALUES(current_time(), 'CANCELADO','$user','$name', 2)"; // AQUI REQUIERE EL ID DE ACC_ID CORRESPONDIENTE
        $this->db->query($qry);

        return $pjtId;
    }

}