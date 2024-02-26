<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class ProjectReportsModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }


/* -- Listado de proyectos  ------------------------------------- */    
    public function listAnalysts($params)
    {
        $qry = "SELECT emp_id, emp_fullname FROM ctt_employees WHERE pos_id=8;";
        return $this->db->query($qry);

    }

    public function listCustomers($params)
    {
        $qry = "SELECT * FROM ctt_customers AS cu";
        return $this->db->query($qry);

    }

    // public function listSuppliers($params)
    // {
    //     $qry = "SELECT * FROM ctt_suppliers AS cu";
    //     return $this->db->query($qry);

    // }

/* -- Listado de contenido de proyecto seleccionado  -------------- */
    public function projectContent($params)
    {
        $fechaIni = $this->db->real_escape_string($params['fechaIni']);
        $fechaFin = $this->db->real_escape_string($params['fechaFin']);
        $findAna = $this->db->real_escape_string($params['findAna']);
        $findCli = $this->db->real_escape_string($params['findCli']);
        $bandera = $this->db->real_escape_string($params['bandera']);

            $qry = "SELECT  pjt.pjt_id, pjt.pjt_name, pjttp.pjttp_name,SUM(pjtcn_prod_price) AS allsum, cust.cus_name, cust.cus_id,
			loc.loc_id, loc.loc_type_location, em.emp_id, em.emp_fullname, pjtt.pjttp_id, pjtt.pjttp_name,
			pjttc.pjttc_id, pjttc.pjttc_name, pjt.pjt_location, pjttc.pjttc_name, 
			(SELECT (SUM(pc.pjtcn_discount_base + pc.pjtcn_discount_trip + pc.pjtcn_discount_test)*100)/(COUNT(pc.pjtcn_id)) 
			FROM ctt_projects_content pc WHERE pc.pjt_id = pjc.pjt_id) AS discount, pjt.pjt_to_carry_on, pjt.pjt_to_carry_out,
			IFNULL((SELECT COUNT(*) FROM ctt_infocfdi AS cfdi WHERE cfdi.pjt_id = pjt.pjt_id), 0) AS cfdi,
			IFNULL((SELECT COUNT(*) FROM ctt_projects AS pj 
            INNER join ctt_who_attend_projects AS wat ON wat.pjt_id = pj.pjt_id
            INNER JOIN ctt_employees AS em ON em.emp_id = wat.emp_id
            WHERE pj.pjt_id = pjt.pjt_id),0) AS empleados, 
            IFNULL((SELECT count(*) FROM ctt_projects_content AS pc 
            inner join ctt_products as pd ON pc.prd_id = pd.prd_id
            INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
            INNER JOIN ctt_projects AS pj ON pj.pjt_id = pc.pjt_id
            WHERE sb.cat_id IN(17,18) AND pj.pjt_id = pjt.pjt_id),0) AS transport
                FROM ctt_projects_content AS pjc
                LEFT JOIN ctt_projects as pjt ON  pjc.pjt_id=pjt.pjt_id
                LEFT JOIN ctt_projects_type AS pjttp ON pjttp.pjttp_id=pjt.pjttp_id
                LEFT JOIN ctt_customers_owner AS cusow ON cusow.cuo_id=pjt.cuo_id
                LEFT JOIN ctt_customers AS cust ON cust.cus_id=cusow.cus_id
                INNER join ctt_who_attend_projects AS wat ON wat.pjt_id = pjt.pjt_id
                INNER JOIN ctt_employees AS em ON em.emp_id = wat.emp_id
                INNER JOIN ctt_location AS loc ON loc.loc_id = pjt.loc_id
                INNER JOIN ctt_projects_type AS pjtt ON pjtt.pjttp_id = pjt.pjttp_id
                INNER JOIN ctt_projects_type_called AS pjttc ON pjttc.pjttc_id = pjt.pjttc_id
                WHERE pjt.pjt_date_start >= '$fechaIni' 
                AND pjt.pjt_date_end <= '$fechaFin' 
                GROUP BY pjt.pjt_name,pjttp.pjttp_name,cust.cus_name;";
        return $this->db->query($qry);
    }
    


/* -- Listado ventas de expendables  --------------------------------------------------------- */
    public function saleExpendab($params)
    {
        $pjtId = $this->db->real_escape_string($params['pjtId']);

        $qry = "SELECT sum(sd.sld_quantity * sd.sld_price) AS expendables
                FROM ctt_sales_details AS sd
                INNER JOIN ctt_sales as sl on sl.sal_id = sd.sal_id
                WHERE pjt_id =  $pjtId;";
        return $this->db->query($qry);

    }

/* -- Listado de contenido de proyecto seleccionado  -------------- */
public function projectActive($params)
{
    $fechaIni = $this->db->real_escape_string($params['fechaIni']);
    $fechaFin = $this->db->real_escape_string($params['fechaFin']);
    $findAna = $this->db->real_escape_string($params['findAna']);
    $findCli = $this->db->real_escape_string($params['findCli']);
    $bandera = $this->db->real_escape_string($params['bandera']);

    if ($bandera == '1') {
        $qry = "SELECT  pjt.pjt_id, pjt.pjt_name, pjttp.pjttp_name,SUM(pjtcn_prod_price) AS allsum, cust.cus_name, cust.cus_id,
			loc.loc_id, loc.loc_type_location, em.emp_id, em.emp_fullname, pjtt.pjttp_id, pjtt.pjttp_name,
			pjttc.pjttc_id, pjttc.pjttc_name, pjt.pjt_location, pjttc.pjttc_name, 
			(SELECT (SUM(pc.pjtcn_discount_base + pc.pjtcn_discount_trip + pc.pjtcn_discount_test)*100)/(COUNT(pc.pjtcn_id)) 
			FROM ctt_projects_content pc WHERE pc.pjt_id = pjc.pjt_id) AS discount, pjt.pjt_to_carry_on, pjt.pjt_to_carry_out,
			IFNULL((SELECT COUNT(*) FROM ctt_infocfdi AS cfdi WHERE cfdi.pjt_id = pjt.pjt_id), 0) AS cfdi, pjt.pjt_test_look,
			IFNULL((SELECT COUNT(*) FROM ctt_projects AS pj 
            INNER join ctt_who_attend_projects AS wat ON wat.pjt_id = pj.pjt_id
            INNER JOIN ctt_employees AS em ON em.emp_id = wat.emp_id
            WHERE pj.pjt_id = pjt.pjt_id),0) AS empleados, 
            IFNULL((SELECT count(*) FROM ctt_projects_content AS pc 
            inner join ctt_products as pd ON pc.prd_id = pd.prd_id
            INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
            INNER JOIN ctt_projects AS pj ON pj.pjt_id = pc.pjt_id
            WHERE sb.cat_id IN(17,18) AND pj.pjt_id = pjt.pjt_id),0) AS transport
                FROM ctt_projects_content AS pjc
                LEFT JOIN ctt_projects as pjt ON  pjc.pjt_id=pjt.pjt_id
                LEFT JOIN ctt_projects_type AS pjttp ON pjttp.pjttp_id=pjt.pjttp_id
                LEFT JOIN ctt_customers_owner AS cusow ON cusow.cuo_id=pjt.cuo_id
                LEFT JOIN ctt_customers AS cust ON cust.cus_id=cusow.cus_id
                INNER join ctt_who_attend_projects AS wat ON wat.pjt_id = pjt.pjt_id
                INNER JOIN ctt_employees AS em ON em.emp_id = wat.emp_id
                INNER JOIN ctt_location AS loc ON loc.loc_id = pjt.loc_id
                INNER JOIN ctt_projects_type AS pjtt ON pjtt.pjttp_id = pjt.pjttp_id
                INNER JOIN ctt_projects_type_called AS pjttc ON pjttc.pjttc_id = pjt.pjttc_id
                WHERE pjt.pjt_date_start >= '$fechaIni' 
                AND pjt.pjt_date_end <= '$fechaFin' 
                AND em.are_id IN(1,5) AND pjt.pjt_status IN(1,2,4)
                GROUP BY pjt.pjt_name,pjttp.pjttp_name,cust.cus_name;";
    } else {
        if ($bandera == '2') {
            $qry = "SELECT  pjt.pjt_id, pjt.pjt_name, pjttp.pjttp_name,SUM(pjtcn_prod_price) AS allsum, cust.cus_name, cust.cus_id,
                loc.loc_id, loc.loc_type_location, em.emp_id, em.emp_fullname, pjtt.pjttp_id, pjtt.pjttp_name,
                pjttc.pjttc_id, pjttc.pjttc_name, pjt.pjt_location, pjttc.pjttc_name, 
                (SELECT (SUM(pc.pjtcn_discount_base + pc.pjtcn_discount_trip + pc.pjtcn_discount_test)*100)/(COUNT(pc.pjtcn_id)) 
                FROM ctt_projects_content pc WHERE pc.pjt_id = pjc.pjt_id) AS discount, pjt.pjt_to_carry_on, pjt.pjt_to_carry_out,
                IFNULL((SELECT COUNT(*) FROM ctt_infocfdi AS cfdi WHERE cfdi.pjt_id = pjt.pjt_id), 0) AS cfdi, pjt.pjt_test_look,
                IFNULL((SELECT COUNT(*) FROM ctt_projects AS pj 
                INNER join ctt_who_attend_projects AS wat ON wat.pjt_id = pj.pjt_id
                INNER JOIN ctt_employees AS em ON em.emp_id = wat.emp_id
                WHERE pj.pjt_id = pjt.pjt_id),0) AS empleados, 
                IFNULL((SELECT count(*) FROM ctt_projects_content AS pc 
                inner join ctt_products as pd ON pc.prd_id = pd.prd_id
                INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
                INNER JOIN ctt_projects AS pj ON pj.pjt_id = pc.pjt_id
                WHERE sb.cat_id IN(17,18) AND pj.pjt_id = pjt.pjt_id),0) AS transport
                    FROM ctt_projects_content AS pjc
                    LEFT JOIN ctt_projects as pjt ON  pjc.pjt_id=pjt.pjt_id
                    LEFT JOIN ctt_projects_type AS pjttp ON pjttp.pjttp_id=pjt.pjttp_id
                    LEFT JOIN ctt_customers_owner AS cusow ON cusow.cuo_id=pjt.cuo_id
                    LEFT JOIN ctt_customers AS cust ON cust.cus_id=cusow.cus_id
                    INNER join ctt_who_attend_projects AS wat ON wat.pjt_id = pjt.pjt_id
                    INNER JOIN ctt_employees AS em ON em.emp_id = wat.emp_id
                    INNER JOIN ctt_location AS loc ON loc.loc_id = pjt.loc_id
                    INNER JOIN ctt_projects_type AS pjtt ON pjtt.pjttp_id = pjt.pjttp_id
                    INNER JOIN ctt_projects_type_called AS pjttc ON pjttc.pjttc_id = pjt.pjttc_id
                    WHERE pjt.pjt_date_start >= '$fechaIni' 
                    AND pjt.pjt_date_end <= '$fechaFin'
                    AND em.emp_id = $findAna 
                    AND em.are_id IN(1) AND pjt.pjt_status IN(1,2,4)
                    GROUP BY pjt.pjt_name,pjttp.pjttp_name,cust.cus_name;";
        }else{
            if ($bandera == '3') {
                $qry = "SELECT  pjt.pjt_id, pjt.pjt_name, pjttp.pjttp_name,SUM(pjtcn_prod_price) AS allsum, cust.cus_name, cust.cus_id,
                loc.loc_id, loc.loc_type_location, em.emp_id, em.emp_fullname, pjtt.pjttp_id, pjtt.pjttp_name,
                pjttc.pjttc_id, pjttc.pjttc_name, pjt.pjt_location, pjttc.pjttc_name, 
                (SELECT (SUM(pc.pjtcn_discount_base + pc.pjtcn_discount_trip + pc.pjtcn_discount_test)*100)/(COUNT(pc.pjtcn_id)) 
                FROM ctt_projects_content pc WHERE pc.pjt_id = pjc.pjt_id) AS discount, pjt.pjt_to_carry_on, pjt.pjt_to_carry_out,
                IFNULL((SELECT COUNT(*) FROM ctt_infocfdi AS cfdi WHERE cfdi.pjt_id = pjt.pjt_id), 0) AS cfdi, pjt.pjt_test_look,
                IFNULL((SELECT COUNT(*) FROM ctt_projects AS pj 
                INNER join ctt_who_attend_projects AS wat ON wat.pjt_id = pj.pjt_id
                INNER JOIN ctt_employees AS em ON em.emp_id = wat.emp_id
                WHERE pj.pjt_id = pjt.pjt_id),0) AS empleados, 
                IFNULL((SELECT count(*) FROM ctt_projects_content AS pc 
                inner join ctt_products as pd ON pc.prd_id = pd.prd_id
                INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
                INNER JOIN ctt_projects AS pj ON pj.pjt_id = pc.pjt_id
                WHERE sb.cat_id IN(17,18) AND pj.pjt_id = pjt.pjt_id),0) AS transport
                    FROM ctt_projects_content AS pjc
                    LEFT JOIN ctt_projects as pjt ON  pjc.pjt_id=pjt.pjt_id
                    LEFT JOIN ctt_projects_type AS pjttp ON pjttp.pjttp_id=pjt.pjttp_id
                    LEFT JOIN ctt_customers_owner AS cusow ON cusow.cuo_id=pjt.cuo_id
                    LEFT JOIN ctt_customers AS cust ON cust.cus_id=cusow.cus_id
                    INNER join ctt_who_attend_projects AS wat ON wat.pjt_id = pjt.pjt_id
                    INNER JOIN ctt_employees AS em ON em.emp_id = wat.emp_id
                    INNER JOIN ctt_location AS loc ON loc.loc_id = pjt.loc_id
                    INNER JOIN ctt_projects_type AS pjtt ON pjtt.pjttp_id = pjt.pjttp_id
                    INNER JOIN ctt_projects_type_called AS pjttc ON pjttc.pjttc_id = pjt.pjttc_id
                    WHERE pjt.pjt_date_start >= '$fechaIni' 
                    AND pjt.pjt_date_end <= '$fechaFin'
                    AND cusow.cus_id = $findCli
                    AND em.are_id IN(1) AND pjt.pjt_status IN(1,2,4)
                    GROUP BY pjt.pjt_name,pjttp.pjttp_name,cust.cus_name;";
            } else {
                if ($bandera == '4') {
                    $qry = "SELECT  pjt.pjt_id, pjt.pjt_name, pjttp.pjttp_name,SUM(pjtcn_prod_price) AS allsum, cust.cus_name, cust.cus_id,
                    loc.loc_id, loc.loc_type_location, em.emp_id, em.emp_fullname, pjtt.pjttp_id, pjtt.pjttp_name,
                    pjttc.pjttc_id, pjttc.pjttc_name, pjt.pjt_location, pjttc.pjttc_name, 
                    (SELECT (SUM(pc.pjtcn_discount_base + pc.pjtcn_discount_trip + pc.pjtcn_discount_test)*100)/(COUNT(pc.pjtcn_id)) 
                    FROM ctt_projects_content pc WHERE pc.pjt_id = pjc.pjt_id) AS discount, pjt.pjt_to_carry_on, pjt.pjt_to_carry_out,
                    IFNULL((SELECT COUNT(*) FROM ctt_infocfdi AS cfdi WHERE cfdi.pjt_id = pjt.pjt_id), 0) AS cfdi, pjt.pjt_test_look,
                    IFNULL((SELECT COUNT(*) FROM ctt_projects AS pj 
                    INNER join ctt_who_attend_projects AS wat ON wat.pjt_id = pj.pjt_id
                    INNER JOIN ctt_employees AS em ON em.emp_id = wat.emp_id
                    WHERE pj.pjt_id = pjt.pjt_id),0) AS empleados, 
                    IFNULL((SELECT count(*) FROM ctt_projects_content AS pc 
                    inner join ctt_products as pd ON pc.prd_id = pd.prd_id
                    INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
                    INNER JOIN ctt_projects AS pj ON pj.pjt_id = pc.pjt_id
                    WHERE sb.cat_id IN(17,18) AND pj.pjt_id = pjt.pjt_id),0) AS transport
                        FROM ctt_projects_content AS pjc
                        LEFT JOIN ctt_projects as pjt ON  pjc.pjt_id=pjt.pjt_id
                        LEFT JOIN ctt_projects_type AS pjttp ON pjttp.pjttp_id=pjt.pjttp_id
                        LEFT JOIN ctt_customers_owner AS cusow ON cusow.cuo_id=pjt.cuo_id
                        LEFT JOIN ctt_customers AS cust ON cust.cus_id=cusow.cus_id
                        INNER join ctt_who_attend_projects AS wat ON wat.pjt_id = pjt.pjt_id
                        INNER JOIN ctt_employees AS em ON em.emp_id = wat.emp_id
                        INNER JOIN ctt_location AS loc ON loc.loc_id = pjt.loc_id
                        INNER JOIN ctt_projects_type AS pjtt ON pjtt.pjttp_id = pjt.pjttp_id
                        INNER JOIN ctt_projects_type_called AS pjttc ON pjttc.pjttc_id = pjt.pjttc_id
                        WHERE pjt.pjt_date_start >= '$fechaIni' 
                        AND pjt.pjt_date_end <= '$fechaFin'
                        AND em.emp_id IN($findAna)
                        AND cusow.cus_id IN($findCli) 
                        AND em.are_id IN(1) AND pjt.pjt_status IN(1,2,4)
                        GROUP BY pjt.pjt_name,pjttp.pjttp_name,cust.cus_name;";
                }
            }
        }
    }   
    return $this->db->query($qry);
}

public function patrocinios($params)
{
    $fechaIni = $this->db->real_escape_string($params['fechaIni']);
    $fechaFin = $this->db->real_escape_string($params['fechaFin']);
    $findAna = $this->db->real_escape_string($params['findAna']);
    $findCli = $this->db->real_escape_string($params['findCli']);
    $bandera = $this->db->real_escape_string($params['bandera']);

    if ($bandera == 1) {
        $qry = "SELECT cu.cus_id, cu.cus_name, pj.pjt_id, pj.pjt_name, pjt.pjttp_id, pjt.pjttp_name, 
        CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, 
        em.emp_fullname, (SELECT (SUM(pc.pjtcn_discount_base + pc.pjtcn_discount_trip + pc.pjtcn_discount_test)*100)/(COUNT(pc.pjtcn_id)) 
            FROM ctt_projects_content pc WHERE pc.pjt_id = pj.pjt_id) AS discount
                FROM ctt_projects AS pj 
                INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                INNER JOIN ctt_projects_type AS pjt ON pjt.pjttp_id = pj.pjttp_id
                INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' 
                GROUP BY pj.pjt_id";
    }else{
        if ($bandera == 2) {
            $qry = "SELECT cu.cus_id, cu.cus_name, pj.pjt_id, pj.pjt_name, pjt.pjttp_id, pjt.pjttp_name, 
                CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, 
                em.emp_fullname, (SELECT (SUM(pc.pjtcn_discount_base + pc.pjtcn_discount_trip + pc.pjtcn_discount_test)*100)/(COUNT(pc.pjtcn_id)) 
                    FROM ctt_projects_content pc WHERE pc.pjt_id = pj.pjt_id) AS discount
                        FROM ctt_projects AS pj  
                        INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                        INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                        INNER JOIN ctt_projects_type AS pjt ON pjt.pjttp_id = pj.pjttp_id
                        INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                        INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                        WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND  em.emp_id ='$findAna'
                        GROUP BY pj.pjt_id";
        }else{
            if ($bandera == 3) {
                $qry = "SELECT cu.cus_id, cu.cus_name, pj.pjt_id, pj.pjt_name, pjt.pjttp_id, pjt.pjttp_name, 
                    CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, 
                    em.emp_fullname, (SELECT (SUM(pc.pjtcn_discount_base + pc.pjtcn_discount_trip + pc.pjtcn_discount_test)*100)/(COUNT(pc.pjtcn_id)) 
                        FROM ctt_projects_content pc WHERE pc.pjt_id = pj.pjt_id) AS discount
                            FROM ctt_projects AS pj 
                            INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                            INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                            INNER JOIN ctt_projects_type AS pjt ON pjt.pjttp_id = pj.pjttp_id
                            INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                            INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                            WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND cu.cus_id ='$findCli'
                            GROUP BY pj.pjt_id";
            }else{
                $qry = "SELECT cu.cus_id, cu.cus_name, pj.pjt_id, pj.pjt_name, pjt.pjttp_id, pjt.pjttp_name, 
                    CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, 
                    em.emp_fullname, (SELECT (SUM(pc.pjtcn_discount_base + pc.pjtcn_discount_trip + pc.pjtcn_discount_test)*100)/(COUNT(pc.pjtcn_id)) 
                        FROM ctt_projects_content pc WHERE pc.pjt_id = pj.pjt_id) AS discount
                            FROM ctt_projects AS pj 
                            INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                            INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                            INNER JOIN ctt_projects_type AS pjt ON pjt.pjttp_id = pj.pjttp_id
                            INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                            INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                            WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND cu.cus_id ='$findCli' AND em.emp_id ='$findAna'
                            GROUP BY pj.pjt_id";
            }
        }
    }
        
    return $this->db->query($qry);
}
public function cierres($params)
{
    $fechaIni = $this->db->real_escape_string($params['fechaIni']);
    $fechaFin = $this->db->real_escape_string($params['fechaFin']);
    $findAna = $this->db->real_escape_string($params['findAna']);
    $findCli = $this->db->real_escape_string($params['findCli']);
    $bandera = $this->db->real_escape_string($params['bandera']);
    if ($bandera == '1' ){
        $qry = "SELECT cu.cus_id, cu.cus_name, pj.pjt_id, pj.pjt_name, pjt.pjttp_id, 
            pjt.pjttp_name, 
                CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, 
                em.emp_fullname, (SELECT (SUM(pc.pjtcn_discount_base + pc.pjtcn_discount_trip + pc.pjtcn_discount_test)*100)/(COUNT(pc.pjtcn_id)) 
            FROM ctt_projects_content pc WHERE pc.pjt_id = pj.pjt_id) AS discount
                FROM ctt_projects AS pj 
                INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                INNER JOIN ctt_projects_type AS pjt ON pjt.pjttp_id = pj.pjttp_id
                INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND pj.pjt_status = 9;";
    }elseif ($bandera == '2') {
        $qry = "SELECT cu.cus_id, cu.cus_name, pj.pjt_id, pj.pjt_name, pjt.pjttp_id, 
        pjt.pjttp_name, 
            CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, 
            em.emp_fullname, (SELECT (SUM(pc.pjtcn_discount_base + pc.pjtcn_discount_trip + pc.pjtcn_discount_test)*100)/(COUNT(pc.pjtcn_id)) 
            FROM ctt_projects_content pc WHERE pc.pjt_id = pj.pjt_id) AS discount
                FROM ctt_projects AS pj 
                INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                INNER JOIN ctt_projects_type AS pjt ON pjt.pjttp_id = pj.pjttp_id
                INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND em.emp_id ='$findAna' AND pj.pjt_status = 9;";
        
    }elseif ($bandera == '3') {
        $qry = "SELECT cu.cus_id, cu.cus_name, pj.pjt_id, pj.pjt_name, pjt.pjttp_id, 
        pjt.pjttp_name, 
            CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, 
            em.emp_fullname, (SELECT (SUM(pc.pjtcn_discount_base + pc.pjtcn_discount_trip + pc.pjtcn_discount_test)*100)/(COUNT(pc.pjtcn_id)) 
            FROM ctt_projects_content pc WHERE pc.pjt_id = pj.pjt_id) AS discount
                FROM ctt_projects AS pj 
                INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                INNER JOIN ctt_projects_type AS pjt ON pjt.pjttp_id = pj.pjttp_id
                INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND cu.cus_id = '$findCli' AND pj.pjt_status = 9;";
        
    }elseif ($bandera == '4') {
        $qry = "SELECT cu.cus_id, cu.cus_name, pj.pjt_id, pj.pjt_name, pjt.pjttp_id, 
        pjt.pjttp_name, 
            CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, 
            em.emp_fullname, (SELECT (SUM(pc.pjtcn_discount_base + pc.pjtcn_discount_trip + pc.pjtcn_discount_test)*100)/(COUNT(pc.pjtcn_id)) 
            FROM ctt_projects_content pc WHERE pc.pjt_id = pj.pjt_id) AS discount
                FROM ctt_projects AS pj 
                INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                INNER JOIN ctt_projects_type AS pjt ON pjt.pjttp_id = pj.pjttp_id
                INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND cu.cus_id = '$findCli' AND em.emp_id ='$findAna' AND pj.pjt_status = 9;";
    }
    return $this->db->query($qry);
}
public function equipoMasRentado($params)
{
    $fechaIni = $this->db->real_escape_string($params['fechaIni']);
    $fechaFin = $this->db->real_escape_string($params['fechaFin']);
    $findAna = $this->db->real_escape_string($params['findAna']);
    $findCli = $this->db->real_escape_string($params['findCli']);
    $bandera = $this->db->real_escape_string($params['bandera']);
    if($bandera == '1'){
        $qry = "SELECT ser_sku, prd.prd_name, COUNT(*) ser_reserve_count, prd.prd_id, sr.ser_sku, sr.ser_id, pjt.pjt_id
        , pjt.pjt_name, IFNULL((DATEDIFF(pd.pjtpd_day_end, pd.pjtpd_day_start)+1),0) tiempo, lc.loc_type_location
        FROM ctt_series AS sr
        INNER JOIN ctt_projects_detail AS dt ON sr.ser_id=dt.ser_id
        INNER JOIN ctt_projects_periods AS pd ON dt.pjtdt_id=pd.pjtdt_id
        INNER JOIN ctt_products AS prd ON prd.prd_id = sr.prd_id
        INNER JOIN ctt_projects_content AS pcn ON pcn.prd_id = prd.prd_id 
        INNER JOIN ctt_projects AS pjt ON pjt.pjt_id = pcn.pjt_id
        INNER JOIN ctt_location AS lc ON lc.loc_id = pjt.loc_id
        LEFT JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pjt.cuo_id
        LEFT JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
        INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pjt.pjt_id
        INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
        WHERE pd.pjtpd_day_start>='$fechaIni' AND pd.pjtpd_day_end<='$fechaFin'
        GROUP BY ser_sku, prd.prd_name ORDER BY COUNT(*) DESC LIMIT 20;";

    }elseif ($bandera == '2') {
        $qry = "SELECT ser_sku, prd.prd_name, COUNT(*) ser_reserve_count, prd.prd_id, sr.ser_sku, sr.ser_id, pjt.pjt_id
        , pjt.pjt_name, IFNULL((DATEDIFF(pd.pjtpd_day_end, pd.pjtpd_day_start)+1),0) tiempo, lc.loc_type_location
        FROM ctt_series AS sr
        INNER JOIN ctt_projects_detail AS dt ON sr.ser_id=dt.ser_id
        INNER JOIN ctt_projects_periods AS pd ON dt.pjtdt_id=pd.pjtdt_id
        INNER JOIN ctt_products AS prd ON prd.prd_id = sr.prd_id
        INNER JOIN ctt_projects_content AS pcn ON pcn.prd_id = prd.prd_id 
        INNER JOIN ctt_projects AS pjt ON pjt.pjt_id = pcn.pjt_id
        INNER JOIN ctt_location AS lc ON lc.loc_id = pjt.loc_id
        LEFT JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pjt.cuo_id
        LEFT JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
        INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pjt.pjt_id
        INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
        WHERE pd.pjtpd_day_start>='$fechaIni' AND pd.pjtpd_day_end<='$fechaFin' AND em.emp_id ='$findAna' 
        GROUP BY ser_sku, prd.prd_name ORDER BY COUNT(*) DESC LIMIT 20;";
    }elseif ($bandera == '3') {
        $qry = "SELECT ser_sku, prd.prd_name, COUNT(*) ser_reserve_count, prd.prd_id, sr.ser_sku, sr.ser_id, pjt.pjt_id
        , pjt.pjt_name, IFNULL((DATEDIFF(pd.pjtpd_day_end, pd.pjtpd_day_start)+1),0) tiempo, lc.loc_type_location
        FROM ctt_series AS sr
        INNER JOIN ctt_projects_detail AS dt ON sr.ser_id=dt.ser_id
        INNER JOIN ctt_projects_periods AS pd ON dt.pjtdt_id=pd.pjtdt_id
        INNER JOIN ctt_products AS prd ON prd.prd_id = sr.prd_id
        INNER JOIN ctt_projects_content AS pcn ON pcn.prd_id = prd.prd_id 
        INNER JOIN ctt_projects AS pjt ON pjt.pjt_id = pcn.pjt_id
        INNER JOIN ctt_location AS lc ON lc.loc_id = pjt.loc_id
        LEFT JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pjt.cuo_id
        LEFT JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
        INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pjt.pjt_id
        INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
        WHERE pd.pjtpd_day_start>='$fechaIni' AND pd.pjtpd_day_end<='$fechaFin' AND cu.cus_id = '$findCli'
        GROUP BY ser_sku, prd.prd_name ORDER BY COUNT(*) DESC LIMIT 20;";
    }elseif ($bandera == '4') {
        $qry = "SELECT ser_sku, prd.prd_name, COUNT(*) ser_reserve_count, prd.prd_id, sr.ser_sku, sr.ser_id, pjt.pjt_id
        , pjt.pjt_name, IFNULL((DATEDIFF(pd.pjtpd_day_end, pd.pjtpd_day_start)+1),0) tiempo, lc.loc_type_location
        FROM ctt_series AS sr
        INNER JOIN ctt_projects_detail AS dt ON sr.ser_id=dt.ser_id
        INNER JOIN ctt_projects_periods AS pd ON dt.pjtdt_id=pd.pjtdt_id
        INNER JOIN ctt_products AS prd ON prd.prd_id = sr.prd_id
        INNER JOIN ctt_projects_content AS pcn ON pcn.prd_id = prd.prd_id 
        INNER JOIN ctt_projects AS pjt ON pjt.pjt_id = pcn.pjt_id
        INNER JOIN ctt_location AS lc ON lc.loc_id = pjt.loc_id
        LEFT JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pjt.cuo_id
        LEFT JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
        INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pjt.pjt_id
        INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
        WHERE pd.pjtpd_day_start>='$fechaIni' AND pd.pjtpd_day_end<='$fechaFin' AND em.emp_id ='$findAna' AND cu.cus_id = '$findCli'
        GROUP BY ser_sku, prd.prd_name ORDER BY COUNT(*) DESC LIMIT 20;";
        //AND cu.cus_id = '$findCli' AND em.emp_id ='$findAna'
    }
        
    return $this->db->query($qry);
}
public function equipoMenosRentado($params)
{
    $fechaIni = $this->db->real_escape_string($params['fechaIni']);
    $fechaFin = $this->db->real_escape_string($params['fechaFin']);
    $findAna = $this->db->real_escape_string($params['findAna']);
    $findCli = $this->db->real_escape_string($params['findCli']);
    $bandera = $this->db->real_escape_string($params['bandera']);
    if($bandera == '1'){
        $qry = "SELECT pd.prd_id, pd.prd_sku, pd.prd_name, DATEDIFF(CURDATE(), 
        prp.pjtpd_day_end) timedif, pjt_name
                FROM ctt_products AS pd
                INNER JOIN ctt_projects_content AS pc ON pc.prd_id = pd.prd_id
                INNER JOIN ctt_projects_detail AS pdt ON pdt.prd_id = pd.prd_id
                INNER JOIN ctt_projects AS pj ON pj.pjt_id = pc.pjt_id
                INNER JOIN ctt_projects_periods AS prp ON prp.pjtdt_id = pdt.pjtdt_id 
                LEFT JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                LEFT JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                 INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                 INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                where em.are_id IN(1,5) AND prp.pjtpd_day_start >= '$fechaIni' AND  prp.pjtpd_day_end <= '$fechaFin' 
                GROUP BY pd.prd_id ORDER by COUNT(*),prp.pjtpd_day_end DESC limit 20";
    }elseif ($bandera == '2') {
        $qry = "SELECT pd.prd_id, pd.prd_sku, pd.prd_name, DATEDIFF(CURDATE(), 
        prp.pjtpd_day_end) timedif, pjt_name
                FROM ctt_products AS pd
                INNER JOIN ctt_projects_content AS pc ON pc.prd_id = pd.prd_id
                INNER JOIN ctt_projects_detail AS pdt ON pdt.prd_id = pd.prd_id
                INNER JOIN ctt_projects AS pj ON pj.pjt_id = pc.pjt_id
                INNER JOIN ctt_projects_periods AS prp ON prp.pjtdt_id = pdt.pjtdt_id 
                LEFT JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                LEFT JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                 INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                 INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                where em.are_id IN(1,5) AND prp.pjtpd_day_start >= '$fechaIni' AND  prp.pjtpd_day_end <= '$fechaFin' AND em.emp_id ='$findAna' 
                GROUP BY pd.prd_id ORDER by COUNT(*),prp.pjtpd_day_end DESC limit 20";
    }elseif ($bandera == '3') {
       $qry = "SELECT pd.prd_id, pd.prd_sku, pd.prd_name, DATEDIFF(CURDATE(), 
       prp.pjtpd_day_end) timedif, pjt_name
               FROM ctt_products AS pd
               INNER JOIN ctt_projects_content AS pc ON pc.prd_id = pd.prd_id
               INNER JOIN ctt_projects_detail AS pdt ON pdt.prd_id = pd.prd_id
               INNER JOIN ctt_projects AS pj ON pj.pjt_id = pc.pjt_id
               INNER JOIN ctt_projects_periods AS prp ON prp.pjtdt_id = pdt.pjtdt_id 
               LEFT JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
               LEFT JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
               where em.are_id IN(1,5) AND prp.pjtpd_day_start >= '$fechaIni' AND  prp.pjtpd_day_end <= '$fechaFin' AND cu.cus_id = '$findCli' 
               GROUP BY pd.prd_id ORDER by COUNT(*),prp.pjtpd_day_end DESC limit 20";
    }elseif ($bandera == '4') {
        $qry = "SELECT pd.prd_id, pd.prd_sku, pd.prd_name, DATEDIFF(CURDATE(), 
        prp.pjtpd_day_end) timedif, pjt_name
                FROM ctt_products AS pd
                INNER JOIN ctt_projects_content AS pc ON pc.prd_id = pd.prd_id
                INNER JOIN ctt_projects_detail AS pdt ON pdt.prd_id = pd.prd_id
                INNER JOIN ctt_projects AS pj ON pj.pjt_id = pc.pjt_id
                INNER JOIN ctt_projects_periods AS prp ON prp.pjtdt_id = pdt.pjtdt_id 
                LEFT JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                LEFT JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                 INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                 INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                where em.are_id IN(1,5) AND prp.pjtpd_day_start >= '$fechaIni' AND  prp.pjtpd_day_end <= '$fechaFin' AND cu.cus_id = '$findCli' AND em.emp_id ='$findAna' 
                GROUP BY pd.prd_id ORDER by COUNT(*),prp.pjtpd_day_end DESC limit 20";
    }
        
    return $this->db->query($qry);
}
public function ProyectosTrabajados($params)
{
    $fechaIni = $this->db->real_escape_string($params['fechaIni']);
    $fechaFin = $this->db->real_escape_string($params['fechaFin']);
    $findAna = $this->db->real_escape_string($params['findAna']);
    $findCli = $this->db->real_escape_string($params['findCli']);
    $bandera = $this->db->real_escape_string($params['bandera']);
    if($bandera == '1'){
        $qry = "SELECT cu.cus_id, cu.cus_name, pj.pjt_id, pj.pjt_name, pjt.pjttp_id, pjt.pjttp_name, 
                CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, 
                em.emp_fullname, (SELECT (SUM(pc.pjtcn_discount_base + pc.pjtcn_discount_trip + pc.pjtcn_discount_test)*100)/(COUNT(pc.pjtcn_id)) 
                    FROM ctt_projects_content pc WHERE pc.pjt_id = pj.pjt_id) AS discount
                        FROM ctt_projects AS pj 
                        INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                        INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                        INNER JOIN ctt_projects_type AS pjt ON pjt.pjttp_id = pj.pjttp_id
                        INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                        INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                        WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND pj.pjt_status = 99 
                        GROUP BY pj.pjt_id";
    }elseif ($bandera == '2') {
        $qry = "SELECT cu.cus_id, cu.cus_name, pj.pjt_id, pj.pjt_name, pjt.pjttp_id, pjt.pjttp_name, 
                CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, 
                em.emp_fullname, (SELECT (SUM(pc.pjtcn_discount_base + pc.pjtcn_discount_trip + pc.pjtcn_discount_test)*100)/(COUNT(pc.pjtcn_id)) 
                    FROM ctt_projects_content pc WHERE pc.pjt_id = pj.pjt_id) AS discount
                        FROM ctt_projects AS pj 
                        INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                        INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                        INNER JOIN ctt_projects_type AS pjt ON pjt.pjttp_id = pj.pjttp_id
                        INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                        INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                        WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND em.emp_id ='$findAna' AND pj.pjt_status = 99 
                        GROUP BY pj.pjt_id";
    }elseif ($bandera == '3') {
        $qry = "SELECT cu.cus_id, cu.cus_name, pj.pjt_id, pj.pjt_name, pjt.pjttp_id, pjt.pjttp_name, 
                CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, 
                em.emp_fullname, (SELECT (SUM(pc.pjtcn_discount_base + pc.pjtcn_discount_trip + pc.pjtcn_discount_test)*100)/(COUNT(pc.pjtcn_id)) 
                    FROM ctt_projects_content pc WHERE pc.pjt_id = pj.pjt_id) AS discount
                        FROM ctt_projects AS pj 
                        INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                        INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                        INNER JOIN ctt_projects_type AS pjt ON pjt.pjttp_id = pj.pjttp_id
                        INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                        INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                        WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND cu.cus_id = '$findCli' AND pj.pjt_status = 99 
                        GROUP BY pj.pjt_id";
    }elseif ($bandera == '4') {
        $qry = "SELECT cu.cus_id, cu.cus_name, pj.pjt_id, pj.pjt_name, pjt.pjttp_id, pjt.pjttp_name, 
                CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, 
                em.emp_fullname, (SELECT (SUM(pc.pjtcn_discount_base + pc.pjtcn_discount_trip + pc.pjtcn_discount_test)*100)/(COUNT(pc.pjtcn_id)) 
                    FROM ctt_projects_content pc WHERE pc.pjt_id = pj.pjt_id) AS discount
                        FROM ctt_projects AS pj 
                        INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                        INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                        INNER JOIN ctt_projects_type AS pjt ON pjt.pjttp_id = pj.pjttp_id
                        INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                        INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                        WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND cu.cus_id = '$findCli' AND em.emp_id ='$findAna' AND pj.pjt_status = 99 
                        GROUP BY pj.pjt_id";
    }
        
    return $this->db->query($qry);
}
public function Subarrendos($params)
{
    $fechaIni = $this->db->real_escape_string($params['fechaIni']);
    $fechaFin = $this->db->real_escape_string($params['fechaFin']);
    $findAna = $this->db->real_escape_string($params['findAna']);
    $findCli = $this->db->real_escape_string($params['findCli']);
    $bandera = $this->db->real_escape_string($params['bandera']);
    if($bandera == '1'){
        $qry = "SELECT pd.prd_id, pd.prd_sku, pd.prd_name, sb.sub_price, sb.sub_quantity, sr.ser_sku, pj.pjt_name, sr.ser_id,
        loc.loc_id, loc.loc_type_location, sup.sup_id, sup.sup_business_name, (SELECT SUM(DATEDIFF(per.pjtpd_day_end, per.pjtpd_day_start))
                FROM ctt_projects_periods AS per
                INNER JOIN ctt_projects_detail AS pjdt ON pjdt.pjtdt_id = per.pjtpd_id 
                WHERE pjdt.pjtdt_id = pdt.pjtdt_id) AS tiempo
            FROM ctt_subletting AS sb
            INNER JOIN ctt_projects AS pj ON pj.pjt_id = sb.prj_id 
            INNER JOIN ctt_products AS pd ON pd.prd_id = sb.prd_id
            INNER JOIN ctt_location AS loc ON pj.loc_id = loc.loc_id
            INNER JOIN ctt_projects_detail AS pdt ON pdt.prd_id = pd.prd_id
             INNER JOIN ctt_series AS sr ON sr.pjtdt_id = pdt.pjtdt_id
             INNER JOIN ctt_suppliers AS sup ON sup.sup_id = sr.sup_id
            WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin';";
    }elseif ($bandera == '2') {
        $qry = "SELECT pd.prd_id, pd.prd_sku, pd.prd_name, sb.sub_price, sb.sub_quantity, sr.ser_sku, pj.pjt_name, sr.ser_id,
        loc.loc_id, loc.loc_type_location, sup.sup_id, sup.sup_business_name, (SELECT SUM(DATEDIFF(per.pjtpd_day_end, per.pjtpd_day_start))
                FROM ctt_projects_periods AS per
                INNER JOIN ctt_projects_detail AS pjdt ON pjdt.pjtdt_id = per.pjtpd_id 
                WHERE pjdt.pjtdt_id = pdt.pjtdt_id) AS tiempo
            FROM ctt_subletting AS sb
            INNER JOIN ctt_projects AS pj ON pj.pjt_id = sb.prj_id 
            INNER JOIN ctt_products AS pd ON pd.prd_id = sb.prd_id
            INNER JOIN ctt_location AS loc ON pj.loc_id = loc.loc_id
            INNER JOIN ctt_projects_detail AS pdt ON pdt.prd_id = pd.prd_id
             INNER JOIN ctt_series AS sr ON sr.pjtdt_id = pdt.pjtdt_id
             INNER JOIN ctt_suppliers AS sup ON sup.sup_id = sr.sup_id
             LEFT JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
            LEFT JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
            INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
            INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
        WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND em.emp_id ='$findAna' AND em.are_id IN(1);";
    }elseif ($bandera == '3') {
       $qry = "SELECT pd.prd_id, pd.prd_sku, pd.prd_name, sb.sub_price, sb.sub_quantity, sr.ser_sku, pj.pjt_name, sr.ser_id,
        loc.loc_id, loc.loc_type_location, sup.sup_id, sup.sup_business_name, (SELECT SUM(DATEDIFF(per.pjtpd_day_end, per.pjtpd_day_start))
                FROM ctt_projects_periods AS per
                INNER JOIN ctt_projects_detail AS pjdt ON pjdt.pjtdt_id = per.pjtpd_id 
                WHERE pjdt.pjtdt_id = pdt.pjtdt_id) AS tiempo
            FROM ctt_subletting AS sb
            INNER JOIN ctt_projects AS pj ON pj.pjt_id = sb.prj_id 
            INNER JOIN ctt_products AS pd ON pd.prd_id = sb.prd_id
            INNER JOIN ctt_location AS loc ON pj.loc_id = loc.loc_id
            INNER JOIN ctt_projects_detail AS pdt ON pdt.prd_id = pd.prd_id
             INNER JOIN ctt_series AS sr ON sr.pjtdt_id = pdt.pjtdt_id
             INNER JOIN ctt_suppliers AS sup ON sup.sup_id = sr.sup_id
             LEFT JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
        LEFT JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
        WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND cu.cus_id = '$findCli';";
    }elseif ($bandera == '4') {
        $qry = "SELECT pd.prd_id, pd.prd_sku, pd.prd_name, sb.sub_price, sb.sub_quantity, sr.ser_sku, pj.pjt_name, sr.ser_id,
        loc.loc_id, loc.loc_type_location, sup.sup_id, sup.sup_business_name, (SELECT SUM(DATEDIFF(per.pjtpd_day_end, per.pjtpd_day_start))
                FROM ctt_projects_periods AS per
                INNER JOIN ctt_projects_detail AS pjdt ON pjdt.pjtdt_id = per.pjtpd_id 
                WHERE pjdt.pjtdt_id = pdt.pjtdt_id) AS tiempo
            FROM ctt_subletting AS sb
            INNER JOIN ctt_projects AS pj ON pj.pjt_id = sb.prj_id 
            INNER JOIN ctt_products AS pd ON pd.prd_id = sb.prd_id
            INNER JOIN ctt_location AS loc ON pj.loc_id = loc.loc_id
            INNER JOIN ctt_projects_detail AS pdt ON pdt.prd_id = pd.prd_id
            INNER JOIN ctt_series AS sr ON sr.pjtdt_id = pdt.pjtdt_id
            INNER JOIN ctt_suppliers AS sup ON sup.sup_id = sr.sup_id
            LEFT JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
            LEFT JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
            INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
            INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
        WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND cu.cus_id = '$findCli' AND em.emp_id ='$findAna' AND em.are_id IN(1);";
    }
        
    return $this->db->query($qry);
}
public function SubbletingSuppliers($params)
{
    $fechaIni = $this->db->real_escape_string($params['fechaIni']);
    $fechaFin = $this->db->real_escape_string($params['fechaFin']);
    $findAna = $this->db->real_escape_string($params['findAna']);
    $findCli = $this->db->real_escape_string($params['findCli']);
    $bandera = $this->db->real_escape_string($params['bandera']);
    if($bandera == '1'){
        $qry = "SELECT sb.sub_id,sp.sup_business_name, SUM(sb.sub_quantity) AS qty,
            pd.prd_name, CONCAT(DATE(sb.sub_date_start), ' - ' ,DATE(sb.sub_date_end)) AS dates
            FROM ctt_subletting AS sb
            INNER JOIN ctt_suppliers AS sp ON sp.sup_id = sb.sub_id
            INNER JOIN ctt_series AS sr ON sr.ser_id = sb.ser_id
            INNER JOIN ctt_products AS pd ON pd.prd_id = sr.prd_id
            WHERE sb.sub_date_start >= '$fechaIni' AND sb.sub_date_end <= '$fechaFin'
            GROUP BY pd.prd_id";
    }elseif($bandera == '2'){
        $qry = "SELECT sb.sub_id,sp.sup_business_name, SUM(sb.sub_quantity) AS qty,
            pd.prd_name, CONCAT(DATE(sb.sub_date_start), ' - ' ,DATE(sb.sub_date_end)) AS dates
            FROM ctt_subletting AS sb
            INNER JOIN ctt_suppliers AS sp ON sp.sup_id = sb.sub_id
            INNER JOIN ctt_series AS sr ON sr.ser_id = sb.ser_id
            INNER JOIN ctt_products AS pd ON pd.prd_id = sr.prd_id
            WHERE sb.sub_date_start >= '$fechaIni' AND sb.sub_date_end <= '$fechaFin' AND sb.sub_id ='$findAna'
            GROUP BY pd.prd_id";
    }
        
    return $this->db->query($qry);
}
public function newCustomers($params)
{
    $fechaIni = $this->db->real_escape_string($params['fechaIni']);
    $fechaFin = $this->db->real_escape_string($params['fechaFin']);
    $findAna = $this->db->real_escape_string($params['findAna']);
    $findCli = $this->db->real_escape_string($params['findCli']);
    $bandera = $this->db->real_escape_string($params['bandera']);
    if($bandera == '1'){
        $qry = "SELECT cu.cus_id, cu.cus_name, pj.pjt_id, pj.pjt_name, pjt.pjttp_id, 
        pjt.pjttp_name, 
            CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, 
            em.emp_fullname, (SELECT SUM((pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost)-(pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_discount_base) +
                    (pc.pjtcn_quantity* pc.pjtcn_days_trip * pc.pjtcn_prod_price)-(pc.pjtcn_quantity* pc.pjtcn_days_trip * pc.pjtcn_prod_price * pc.pjtcn_discount_trip) +
                    (pc.pjtcn_quantity* pc.pjtcn_days_test * pc.pjtcn_prod_price) - (pc.pjtcn_quantity* pc.pjtcn_days_test * pc.pjtcn_prod_price * pc.pjtcn_discount_test)
                    + (pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_insured)-(pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_insured * pc.pjtcn_discount_insured)) monto
                    FROM  ctt_projects_content AS pc
                    LEFT JOIN ctt_projects AS pjt ON pjt.pjt_id = pc.pjt_id
                    WHERE pjt.pjt_id = pj.pjt_id) monto, 
                    cu.cus_contact_name
                    FROM ctt_projects AS pj 
                    INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                    INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                    INNER JOIN ctt_projects_type AS pjt ON pjt.pjttp_id = pj.pjttp_id
                    INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                    INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                    WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND em.are_id IN(1,5);";
    }elseif ($bandera == '2') {
        $qry = "SELECT cu.cus_id, cu.cus_name, pj.pjt_id, pj.pjt_name, pjt.pjttp_id, 
        pjt.pjttp_name, 
            CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, 
            em.emp_fullname, (SELECT SUM((pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost)-(pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_discount_base) +
                    (pc.pjtcn_quantity* pc.pjtcn_days_trip * pc.pjtcn_prod_price)-(pc.pjtcn_quantity* pc.pjtcn_days_trip * pc.pjtcn_prod_price * pc.pjtcn_discount_trip) +
                    (pc.pjtcn_quantity* pc.pjtcn_days_test * pc.pjtcn_prod_price) - (pc.pjtcn_quantity* pc.pjtcn_days_test * pc.pjtcn_prod_price * pc.pjtcn_discount_test)
                    + (pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_insured)-(pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_insured * pc.pjtcn_discount_insured)) monto
                    FROM  ctt_projects_content AS pc
                    LEFT JOIN ctt_projects AS pjt ON pjt.pjt_id = pc.pjt_id
                    WHERE pjt.pjt_id = pj.pjt_id) monto, cu.cus_contact_name
                    FROM ctt_projects AS pj 
                    INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                    INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                    INNER JOIN ctt_projects_type AS pjt ON pjt.pjttp_id = pj.pjttp_id
                    INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                    INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                    WHERE em.are_id IN(1,5) AND pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND em.emp_id ='$findAna';";
    }elseif ($bandera == '3') {
        $qry = "SELECT cu.cus_id, cu.cus_name, pj.pjt_id, pj.pjt_name, pjt.pjttp_id, 
        pjt.pjttp_name, 
            CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, 
            em.emp_fullname, (SELECT SUM((pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost)-(pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_discount_base) +
                    (pc.pjtcn_quantity* pc.pjtcn_days_trip * pc.pjtcn_prod_price)-(pc.pjtcn_quantity* pc.pjtcn_days_trip * pc.pjtcn_prod_price * pc.pjtcn_discount_trip) +
                    (pc.pjtcn_quantity* pc.pjtcn_days_test * pc.pjtcn_prod_price) - (pc.pjtcn_quantity* pc.pjtcn_days_test * pc.pjtcn_prod_price * pc.pjtcn_discount_test)
                    + (pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_insured)-(pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_insured * pc.pjtcn_discount_insured)) monto
                    FROM  ctt_projects_content AS pc
                    LEFT JOIN ctt_projects AS pjt ON pjt.pjt_id = pc.pjt_id
                    WHERE pjt.pjt_id = pj.pjt_id) monto, cu.cus_contact_name
                    FROM ctt_projects AS pj 
                    INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                    INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                    INNER JOIN ctt_projects_type AS pjt ON pjt.pjttp_id = pj.pjttp_id
                    INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                    INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                    WHERE em.are_id IN(1,5) AND pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND cu.cus_id = '$findCli';";
    }elseif ($bandera == '4') {
        $qry = "SELECT cu.cus_id, cu.cus_name, pj.pjt_id, pj.pjt_name, pjt.pjttp_id, 
        pjt.pjttp_name, 
            CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, 
            em.emp_fullname, (SELECT SUM((pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost)-(pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_discount_base) +
                    (pc.pjtcn_quantity* pc.pjtcn_days_trip * pc.pjtcn_prod_price)-(pc.pjtcn_quantity* pc.pjtcn_days_trip * pc.pjtcn_prod_price * pc.pjtcn_discount_trip) +
                    (pc.pjtcn_quantity* pc.pjtcn_days_test * pc.pjtcn_prod_price) - (pc.pjtcn_quantity* pc.pjtcn_days_test * pc.pjtcn_prod_price * pc.pjtcn_discount_test)
                    + (pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_insured)-(pc.pjtcn_quantity * pc.pjtcn_prod_price * pc.pjtcn_days_cost * pc.pjtcn_insured * pc.pjtcn_discount_insured)) monto
                    FROM  ctt_projects_content AS pc
                    LEFT JOIN ctt_projects AS pjt ON pjt.pjt_id = pc.pjt_id
                    WHERE pjt.pjt_id = pj.pjt_id) monto, cu.cus_contact_name
                    FROM ctt_projects AS pj 
                    INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                    INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                    INNER JOIN ctt_projects_type AS pjt ON pjt.pjttp_id = pj.pjttp_id
                    INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                    INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                    WHERE em.are_id IN(1,5) AND pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND cu.cus_id = '$findCli' AND em.emp_id ='$findAna';";
    }
        
    return $this->db->query($qry);
}
public function Productividad($params)
{
    $fechaIni = $this->db->real_escape_string($params['fechaIni']);
    $fechaFin = $this->db->real_escape_string($params['fechaFin']);
    $findAna = $this->db->real_escape_string($params['findAna']);
    $findCli = $this->db->real_escape_string($params['findCli']);
    $bandera = $this->db->real_escape_string($params['bandera']);
    if($bandera == '1'){
        $qry = "SELECT em.emp_id, em.emp_fullname, COUNT(*) AS cantidad, 
        SUM(case when pj.pjt_status=1 then 1 ELSE 0 END) AS budget,
        SUM(case when pj.pjt_status=2 then 1 ELSE 0 END) AS plans, 
        SUM(case when pj.pjt_status=4 then 1 ELSE 0 END) AS projects
        FROM ctt_projects AS pj 
        INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
        INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
        WHERE em.are_id = 1 AND pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND em.are_id IN(1)
        GROUP BY em.emp_id;";
    }elseif ($bandera == '2') {
        $qry = "SELECT em.emp_id, em.emp_fullname, COUNT(*) AS cantidad, 
        SUM(case when pj.pjt_status=1 then 1 ELSE 0 END) AS budget,
        SUM(case when pj.pjt_status=2 then 1 ELSE 0 END) AS plans, 
        SUM(case when pj.pjt_status=4 then 1 ELSE 0 END) AS projects
        FROM ctt_projects AS pj 
        INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
        INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
        WHERE em.are_id = 1  AND pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND em.emp_id ='$findAna' AND em.are_id IN(1)
        GROUP BY em.emp_id;";
    }else {
        $qry = "SELECT em.emp_id, em.emp_fullname, COUNT(*) AS cantidad, 
        SUM(case when pj.pjt_status=1 then 1 ELSE 0 END) AS budget,
        SUM(case when pj.pjt_status=2 then 1 ELSE 0 END) AS plans, 
        SUM(case when pj.pjt_status=4 then 1 ELSE 0 END) AS projects
        FROM ctt_projects AS pj 
        INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
        INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
        WHERE em.are_id = 1
        GROUP BY em.emp_id;";
    }
        
    return $this->db->query($qry);
}
public function ProjectsByDeveloper($params)
{
    $fechaIni = $this->db->real_escape_string($params['fechaIni']);
    $fechaFin = $this->db->real_escape_string($params['fechaFin']);
    $findAna = $this->db->real_escape_string($params['findAna']);
    $findCli = $this->db->real_escape_string($params['findCli']);
    $bandera = $this->db->real_escape_string($params['bandera']);
    if($bandera == '1'){
        $qry = "SELECT em.emp_id, em.emp_fullname, pj.pjt_name, 
        CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, pt.pjttp_name
                FROM ctt_projects AS pj 
                INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                INNER JOIN ctt_projects_type AS pt ON pt.pjttp_id = pj.pjttp_id
                INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND em.are_id IN(1)
 AND em.are_id = 1";
            
    }elseif ($bandera == '2') {
        $qry = "SELECT em.emp_id, em.emp_fullname, pj.pjt_name, 
        CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, pt.pjttp_name
                FROM ctt_projects AS pj 
                INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                INNER JOIN ctt_projects_type AS pt ON pt.pjttp_id = pj.pjttp_id
                INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
            WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND em.emp_id ='$findAna' AND em.are_id = 1";
    }elseif ($bandera == '3') {
        $qry = "SELECT em.emp_id, em.emp_fullname, pj.pjt_name, 
        CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, pt.pjttp_name
                FROM ctt_projects AS pj 
                INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                INNER JOIN ctt_projects_type AS pt ON pt.pjttp_id = pj.pjttp_id
                INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
            WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND cu.cus_id = '$findCli' AND em.are_id = 1";
    }elseif ($bandera == '4') {
       $qry = "SELECT em.emp_id, em.emp_fullname, pj.pjt_name, 
       CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, pt.pjttp_name
               FROM ctt_projects AS pj 
               INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
               INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
               INNER JOIN ctt_projects_type AS pt ON pt.pjttp_id = pj.pjttp_id
               INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
               INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
            WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND cu.cus_id = '$findCli' AND em.emp_id ='$findAna' AND em.are_id = 1";
    }
   
    return $this->db->query($qry);
}

}