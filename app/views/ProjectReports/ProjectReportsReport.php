<?php

ini_set('display_errors', 'On');

require_once '../../../vendor/autoload.php';
//INICIO DE PROCESOS
$verId = $_GET['v'];
$usrId = $_GET['u'];
$uname = $_GET['n'];

$findAna = $_GET['e'];
$findCli = $_GET['c'];
$proj = $_GET['p'];
$fechaIni = $_GET['fs'];
$fechaFin = $_GET['fe'];
$bandera = $_GET['ba'];

$conkey = decodificar($_GET['h']) ;

$h = explode("|",$conkey);

$conn = new mysqli($h[0],$h[1],$h[2],$h[3]);

if($proj == 1){
    $titulo = "Proyectos Activos";
    if ($bandera == '1') {
        $qry = "SELECT  pjt.pjt_id, pjt.pjt_name, pjttp.pjttp_name,SUM(pjtcn_prod_price) AS allsum, cust.cus_name, cust.cus_id,
            loc.loc_id, loc.loc_type_location, em.emp_id, em.emp_fullname, pjtt.pjttp_id, pjtt.pjttp_name,
            pjttc.pjttc_id, pjttc.pjttc_name, pjt.pjt_location, pjttc.pjttc_name, 
            (SELECT (SUM(pc.pjtcn_discount_base + pc.pjtcn_discount_trip + pc.pjtcn_discount_test)*100)/(COUNT(pc.pjtcn_id)) 
            FROM ctt_projects_content pc WHERE pc.pjt_id = pjc.pjt_id) AS discount, pjt.pjt_to_carry_on, pjt.pjt_to_carry_out,
            IFNULL((SELECT COUNT(*) FROM ctt_infocfdi AS cfdi WHERE cfdi.pjt_id = pjt.pjt_id), 0) AS cfdi, pjt.pjt_test_look,
            IFNULL((SELECT COUNT(*) FROM ctt_employees AS em 
            INNER join ctt_who_attend_projects AS wat ON em.emp_id = wat.emp_id 
            INNER JOIN ctt_projects AS pj ON pj.pjt_id = wat.pjt_id 
            WHERE pj.pjt_id = pjt.pjt_id AND em.are_id IN (1,2,3,4,5)),0) AS empleados, 
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
                IFNULL((SELECT COUNT(*) FROM ctt_employees AS em 
                INNER join ctt_who_attend_projects AS wat ON em.emp_id = wat.emp_id 
                INNER JOIN ctt_projects AS pj ON pj.pjt_id = wat.pjt_id 
                WHERE pj.pjt_id = pjt.pjt_id AND em.are_id IN (1,2,3,4,5)),0) AS empleados, 
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
                IFNULL((SELECT COUNT(*) FROM ctt_employees AS em 
                INNER join ctt_who_attend_projects AS wat ON em.emp_id = wat.emp_id 
                INNER JOIN ctt_projects AS pj ON pj.pjt_id = wat.pjt_id 
                WHERE pj.pjt_id = pjt.pjt_id AND em.are_id IN (1,2,3,4,5)),0) AS empleados, 
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
                    IFNULL((SELECT COUNT(*) FROM ctt_employees AS em 
                    INNER join ctt_who_attend_projects AS wat ON em.emp_id = wat.emp_id 
                    INNER JOIN ctt_projects AS pj ON pj.pjt_id = wat.pjt_id 
                    WHERE pj.pjt_id = pjt.pjt_id AND em.are_id IN (1,2,3,4,5)),0) AS empleados, 
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
}
elseif($proj == 2){
    $titulo = "Patrocinios";
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
                WHERE em.are_id IN(1) AND pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin'";
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
                        WHERE em.are_id IN(1) AND pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND  em.emp_id ='$findAna'";
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
                            WHERE em.are_id IN(1) AND pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND cu.cus_id ='$findCli'";
            }else{
                if ($bandera == 4) {
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
                            WHERE em.are_id IN(1) AND pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND cu.cus_id ='$findCli' AND em.emp_id ='$findAna'";
                }
            }
        }
    }
}
elseif($proj == 3){
    $titulo = "Cierres";
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
                WHERE  em.are_id IN(1) AND pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND pj.pjt_status = 9;";
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
                WHERE em.are_id IN(1) AND pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND em.emp_id ='$findAna' AND pj.pjt_status = 9;";
        
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
                WHERE em.are_id IN(1) AND pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND cu.cus_id = '$findCli' AND pj.pjt_status = 9;";
        
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
                WHERE em.are_id IN(1) AND pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND cu.cus_id = '$findCli' AND em.emp_id ='$findAna' AND pj.pjt_status = 9;";
    }
}

elseif($proj == 4){
    $titulo = "Equipos Más Rentados";
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
}
elseif($proj == 5){
    $titulo = "Proyectos Trabajados";
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
                        WHERE em.are_id IN(1) AND pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND pj.pjt_status = 99";
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
                        WHERE em.are_id IN(1) AND pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND em.emp_id ='$findAna' AND pj.pjt_status = 99";
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
                        WHERE em.are_id IN(1) AND pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND cu.cus_id = '$findCli' AND pj.pjt_status = 99";
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
                        WHERE em.are_id IN(1) AND pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND cu.cus_id = '$findCli' AND em.emp_id ='$findAna' AND pj.pjt_status = 99";
    }
}
elseif($proj == 6){
    $titulo = "Equipos Menos Rentados";
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
}
elseif($proj == 7){
    $titulo = "Subarrendos";
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
}

elseif($proj == 8){
    $titulo = "Proveedores de Subarrendos";
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
}

elseif($proj == 9){
    $titulo = "Clientes Nuevos";
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
}

elseif($proj == 10){
    $titulo = "Productividad";
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
}

elseif($proj == 11){
    $titulo = "Proyectos por Programador";
    if($bandera == '1'){
        $qry = "SELECT em.emp_id, em.emp_fullname, pj.pjt_name, 
        CONCAT(DATE(pj.pjt_date_start), ' - ' ,DATE(pj.pjt_date_end)) AS dates, pt.pjttp_name
                FROM ctt_projects AS pj 
                INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
                INNER JOIN ctt_projects_type AS pt ON pt.pjttp_id = pj.pjttp_id
                INNER JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
                INNER JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
                WHERE pj.pjt_date_start >= '$fechaIni' AND pj.pjt_date_end <= '$fechaFin' AND em.are_id IN(1) AND em.are_id = 1";
            
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
}

$res = $conn->query($qry);
$conn->close();

while($row = $res->fetch_assoc()){
    $items[] = $row;
}


// Cabezal de la página
$header = '
    <header>
        <div class="cornisa">
            <table class="table-main" border="0">
                <tr>
                    <td class="box-logo side-color">
                        <img class="img-logo" src="../../../app/assets/img/logo-blanco.jpg"  style="width:20mm; height:auto; margin: 3mm 2.5mm 0 2.5mm;"/>
                    </td>

                </tr>
            </table>
        </div>
    </header>';
                
$html = '
    <section>
        <div class="container">
            <div class="name-report">
                <p>
                    <span class="number">'. $titulo.'</span>
                <br>
                    <span class="date"> </span>
                </p>
            </div>
';


/* Tabla para los Proyectos Activos -------------------------  */
    if ($proj == '1'){
        $html .= '


                    <!-- Start Tabla de costo base  -->
                    <table autosize="1" style="page-break-inside:void" class="table-data bline-d">
                        <thead>
                            <tr>
                                <th class="tit-figure prod">Proyecto</th>
                                <th class="tit-figure pric">Clientes</th>
                                <th class="tit-figure qnty">Programador encargado</th>
                                <th class="tit-figure days">Tipo de LLamado</th>
                                <th class="tit-figure disc">Ubicación</th>
                                <th class="tit-figure amou">Locación</th>
                                <th class="tit-figure days">CFDI de Traslado con carta porte</th>
                                <th class="tit-figure amou">Descuento aplicado</th>
                                <th class="tit-figure amou">Prueba de cámara y look</th>
                                <th class="tit-figure amou">Cargas</th>
                                <th class="tit-figure amou">Descargas</th>
                                <th class="tit-figure amou">Con encargado</th>
                                <th class="tit-figure amou">Proyectos que llevan transportes</th>
                            </tr>
                        </thead>
                        <tbody>';
                        for ($i = 0; $i<count($items); $i++){
                            if($items[$i]['transport'] >1){
                                $transport = 'Sí';
                            }else{
                                $transport ='No';
                            }
                            if($items[$i]['cfdi'] >1){
                                $cfdi = 'Sí';
                            }else{
                                $cfdi ='No';
                            }
                            if($items[$i]['empleados'] >1){
                                $empleados = 'Sí';
                            }else{
                                $empleados ='No';
                            }
                            $html .= '
                                <tr>
                                    <td class="dat-figure prod">' . $items[$i]['pjt_name']          . '</td>
                                    <td class="dat-figure pric">' . $items[$i]['cus_name']          . '</td>
                                    <td class="dat-figure qnty">' . $items[$i]['emp_fullname']      . '</td>
                                    <td class="dat-figure days">' . $items[$i]['pjttc_name']        . '</td>
                                    <td class="dat-figure disc">' . $items[$i]['pjt_location']      . '</td>
                                    <td class="dat-figure amou">' . $items[$i]['pjttp_name']        . '</td>
                                    <td class="dat-figure days">' . $cfdi                           . '</td>
                                    <td class="dat-figure amou">' . $items[$i]['discount']   . '</td>
                                    <td class="dat-figure amou">' . $items[$i]['pjt_test_look']     . '</td>
                                    <td class="dat-figure amou">' . $items[$i]['pjt_to_carry_on']   . '</td>
                                    <td class="dat-figure amou">' . $items[$i]['pjt_to_carry_out']  . '</td>
                                    <td class="dat-figure amou">' . $empleados   . '</td>
                                    <td class="dat-figure amou">' . $transport   . '</td>
                                </tr>
                                ';
                            

                        }
        $html .= '
                       
                    </tbody>
                </table>
                <!-- End Tabla de costo base  -->';

    }
/* Tabla de equipo base -------------------------  */

/* Tabla para los Patrocinios -------------------------  */
if ($proj == '2'){
    $html .= '


                <!-- Start Tabla de costo base  -->
                <table autosize="1" style="page-break-inside:void" class="table-data bline-d">
                    <thead>
                        <tr>
                            <th class="tit-figure prod">Cliente</th>
                            <th class="tit-figure pric">Proyecto</th>
                            <th class="tit-figure qnty">Tipo</th>
                            <th class="tit-figure days">Fechas</th>
                            <th class="tit-figure disc">Descuento</th>
                            <th class="tit-figure amou">Programador</th>
                        </tr>
                    </thead>
                    <tbody>';
                    for ($i = 0; $i<count($items); $i++){
                        $html .= '
                            <tr>
                                <td class="dat-figure prod">' . $items[$i]['cus_name']          . '</td>
                                <td class="dat-figure pric">' . $items[$i]['pjt_name']          . '</td>
                                <td class="dat-figure qnty">' . $items[$i]['pjttp_name']      . '</td>
                                <td class="dat-figure days">' . $items[$i]['dates']        . '</td>
                                <td class="dat-figure disc">' . $items[$i]['discount']      . '</td>
                                <td class="dat-figure amou">' . $items[$i]['emp_fullname']        . '</td>
                            </tr>
                            ';
                        

                    }
    $html .= '
                   
                </tbody>
            </table>
            <!-- End Tabla de costo base  -->';

}
/* Tabla de equipo base -------------------------  */


/* Tabla para los Cierres -------------------------  */
if ($proj == '3'){
    $html .= '


                <!-- Start Tabla de costo base  -->
                <table autosize="1" style="page-break-inside:void" class="table-data bline-d">
                    <thead>
                        <tr>
                            <th class="tit-figure prod">Cliente</th>
                            <th class="tit-figure pric">Proyecto</th>
                            <th class="tit-figure qnty">Tipo</th>
                            <th class="tit-figure days">Fechas</th>
                        </tr>
                    </thead>
                    <tbody>';
                    for ($i = 0; $i<count($items); $i++){
                        $html .= '
                            <tr>
                                <td class="dat-figure prod">' . $items[$i]['cus_name']          . '</td>
                                <td class="dat-figure pric">' . $items[$i]['pjt_name']          . '</td>
                                <td class="dat-figure qnty">' . $items[$i]['pjttp_name']      . '</td>
                                <td class="dat-figure days">' . $items[$i]['dates']        . '</td>
                            </tr>
                            ';
                        

                    }
    $html .= '
                   
                </tbody>
            </table>
            <!-- End Tabla de costo base  -->';

}
/* Tabla de equipo base -------------------------  */

/* Tabla para los Equipos Más Rentados -------------------------  */
if ($proj == '4'){
    $html .= '


                <!-- Start Tabla de costo base  -->
                <table autosize="1" style="page-break-inside:void" class="table-data bline-d">
                    <thead>
                        <tr>
                            <th class="tit-figure prod">Producto</th>
                            <th class="tit-figure pric">Numero de serie</th>
                            <th class="tit-figure qnty">Proyecto asignado</th>
                            <th class="tit-figure days">Tiempo de uso</th>
                            <th class="tit-figure disc">Locación</th>
                            <th class="tit-figure amou">Cantidad de rentas</th>
                        </tr>
                    </thead>
                    <tbody>';
                    for ($i = 0; $i<count($items); $i++){
                       
                        $html .= '
                            <tr>
                                <td class="dat-figure prod">' . $items[$i]['prd_name']          . '</td>
                                <td class="dat-figure pric">' . $items[$i]['ser_sku']          . '</td>
                                <td class="dat-figure qnty">' . $items[$i]['pjt_name']      . '</td>
                                <td class="dat-figure days">' . $items[$i]['tiempo']        . '</td>
                                <td class="dat-figure disc">' . $items[$i]['loc_type_location']      . '</td>
                                <td class="dat-figure amou">' . $items[$i]['ser_reserve_count']        . '</td>
                            </tr>
                            ';
                        

                    }
    $html .= '
                   
                </tbody>
            </table>
            <!-- End Tabla de costo base  -->';

}
/* Tabla de equipo base -------------------------  */

/* Tabla para los Proyectos Trabajados -------------------------  */
if ($proj == '5'){
    $html .= '


                <!-- Start Tabla de costo base  -->
                <table autosize="1" style="page-break-inside:void" class="table-data bline-d">
                    <thead>
                        <tr>
                            <th class="tit-figure prod">Cliente</th>
                            <th class="tit-figure pric">Proyecto</th>
                            <th class="tit-figure qnty">Tipo</th>
                            <th class="tit-figure days">Descuento</th>
                            <th class="tit-figure disc">Programador</th>
                        </tr>
                    </thead>
                    <tbody>';
                    for ($i = 0; $i<count($items); $i++){
                        
                        $html .= '
                            <tr>
                                <td class="dat-figure prod">' . $items[$i]['cus_name']          . '</td>
                                <td class="dat-figure pric">' . $items[$i]['pjt_name']          . '</td>
                                <td class="dat-figure qnty">' . $items[$i]['pjttp_name']      . '</td>
                                <td class="dat-figure days">' . $items[$i]['discount']        . '</td>
                                <td class="dat-figure disc">' . $items[$i]['emp_fullname']      . '</td>
                            </tr>
                            ';
                        

                    }
    $html .= '
                   
                </tbody>
            </table>
            <!-- End Tabla de costo base  -->';

}
/* Tabla de equipo base -------------------------  */

/* Tabla para los Equipos Menos Rentados -------------------------  */
if ($proj == '6'){
    $html .= '


                <!-- Start Tabla de costo base  -->
                <table autosize="1" style="page-break-inside:void" class="table-data bline-d">
                    <thead>
                        <tr>
                            <th class="tit-figure prod">Producto</th>
                            <th class="tit-figure pric">SKU</th>
                            <th class="tit-figure qnty">Tiempo sin uso</th>
                            <th class="tit-figure days">Último proyecto asignado</th>
                        </tr>
                    </thead>
                    <tbody>';
                    for ($i = 0; $i<count($items); $i++){
                        
                        $html .= '
                            <tr>
                                <td class="dat-figure prod">' . $items[$i]['prd_name']          . '</td>
                                <td class="dat-figure pric">' . $items[$i]['prd_sku']          . '</td>
                                <td class="dat-figure qnty">' . $items[$i]['timedif']      . '</td>
                                <td class="dat-figure days">' . $items[$i]['pjt_name']        . '</td>
                            </tr>
                            ';
                        

                    }
    $html .= '
                   
                </tbody>
            </table>
            <!-- End Tabla de costo base  -->';

}
/* Tabla de equipo base -------------------------  */

/* Tabla para los Subarrendos -------------------------  */
if ($proj == '7'){
    $html .= '


                <!-- Start Tabla de costo base  -->
                <table autosize="1" style="page-break-inside:void" class="table-data bline-d">
                    <thead>
                        <tr>
                            <th class="tit-figure prod">Producto</th>
                            <th class="tit-figure pric">SKU</th>
                            <th class="tit-figure qnty">Num de serie</th>
                            <th class="tit-figure days">Proyecto asignado</th>
                            <th class="tit-figure disc">Tiempo de uso</th>
                            <th class="tit-figure amou">Proveedor</th>
                            <th class="tit-figure days">Locación</th>
                        </tr>
                    </thead>
                    <tbody>';
                    for ($i = 0; $i<count($items); $i++){
                       
                        $html .= '
                            <tr>
                                <td class="dat-figure prod">' . $items[$i]['prd_name']          . '</td>
                                <td class="dat-figure pric">' . $items[$i]['prd_sku']          . '</td>
                                <td class="dat-figure qnty">' . $items[$i]['ser_sku']      . '</td>
                                <td class="dat-figure days">' . $items[$i]['pjt_name']        . '</td>
                                <td class="dat-figure disc">' . $items[$i]['tiempo']      . '</td>
                                <td class="dat-figure amou">' . $items[$i]['sup_business_name']        . '</td>
                                <td class="dat-figure amou">' . $items[$i]['loc_type_location']   . '</td>
                            </tr>
                            ';
                        

                    }
    $html .= '
                   
                </tbody>
            </table>
            <!-- End Tabla de costo base  -->';

}
/* Tabla de equipo base -------------------------  */

/* Tabla para los Proveedores de subarrendo -------------------------  */
if ($proj == '8'){
    $html .= '


                <!-- Start Tabla de costo base  -->
                <table autosize="1" style="page-break-inside:void" class="table-data bline-d">
                    <thead>
                        <tr>
                            <th class="tit-figure prod">Proveedor</th>
                            <th class="tit-figure pric">Cantidad de subarrendo</th>
                            <th class="tit-figure qnty">Equipos subarrendados</th>
                            <th class="tit-figure days">Periodo de subarrendo</th>
                        </tr>
                    </thead>
                    <tbody>';
                    for ($i = 0; $i<count($items); $i++){
                        
                        $html .= '
                            <tr>
                                <td class="dat-figure prod">' . $items[$i]['sup_business_name']          . '</td>
                                <td class="dat-figure pric">' . $items[$i]['qty']          . '</td>
                                <td class="dat-figure qnty">' . $items[$i]['prd_name']      . '</td>
                                <td class="dat-figure days">' . $items[$i]['dates']        . '</td>
                            </tr>
                            ';
                        

                    }
    $html .= '
                   
                </tbody>
            </table>
            <!-- End Tabla de costo base  -->';

}
/* Tabla de equipo base -------------------------  */

/* Tabla para los Clientes Nuevos -------------------------  */
if ($proj == '9'){
    $html .= '


                <!-- Start Tabla de costo base  -->
                <table autosize="1" style="page-break-inside:void" class="table-data bline-d">
                    <thead>
                        <tr>
                            <th class="tit-figure prod">Cliente</th>
                            <th class="tit-figure pric">Proyecto</th>
                            <th class="tit-figure qnty">Monto</th>
                            <th class="tit-figure days">Rango del proyecto</th>
                            <th class="tit-figure disc">Contacto</th>
                            <th class="tit-figure amou">Programador</th>
                        </tr>
                    </thead>
                    <tbody>';
                    for ($i = 0; $i<count($items); $i++){
                        
                        $html .= '
                            <tr>
                                <td class="dat-figure prod">' . $items[$i]['cus_name']          . '</td>
                                <td class="dat-figure pric">' . $items[$i]['pjt_name']          . '</td>
                                <td class="dat-figure qnty">' . $items[$i]['monto']      . '</td>
                                <td class="dat-figure days">' . $items[$i]['dates']        . '</td>
                                <td class="dat-figure disc">' . $items[$i]['cus_contact_name']      . '</td>
                                <td class="dat-figure amou">' . $items[$i]['emp_fullname']        . '</td>
                            </tr>
                            ';
                        

                    }
    $html .= '
                   
                </tbody>
            </table>
            <!-- End Tabla de costo base  -->';

}
/* Tabla de equipo base -------------------------  */

/* Tabla para la Productividad -------------------------  */
if ($proj == '10'){
    $html .= '


                <!-- Start Tabla de costo base  -->
                <table autosize="1" style="page-break-inside:void" class="table-data bline-d">
                    <thead>
                        <tr>
                            <th class="tit-figure prod">Programador</th>
                            <th class="tit-figure pric">Cantidad de proyectos</th>
                            <th class="tit-figure qnty">Cotización</th>
                            <th class="tit-figure days">Presupuesto</th>
                            <th class="tit-figure disc">Proyecto</th>
                        </tr>
                    </thead>
                    <tbody>';
                    for ($i = 0; $i<count($items); $i++){
                       
                        $html .= '
                            <tr>
                                <td class="dat-figure prod">' . $items[$i]['emp_fullname']          . '</td>
                                <td class="dat-figure pric">' . $items[$i]['cantidad']          . '</td>
                                <td class="dat-figure qnty">' . $items[$i]['budget']      . '</td>
                                <td class="dat-figure days">' . $items[$i]['plans']        . '</td>
                                <td class="dat-figure disc">' . $items[$i]['projects']      . '</td>
                            </tr>
                            ';
                        

                    }
    $html .= '
                   
                </tbody>
            </table>
            <!-- End Tabla de costo base  -->';

}
/* Tabla de equipo base -------------------------  */

/* Tabla para los Proyectos por Programador -------------------------  */
if ($proj == '11'){
    $html .= '
                <!-- Start Tabla de costo base  -->
                <table autosize="1" style="page-break-inside:void" class="table-data bline-d">
                    <thead>
                        <tr>
                            <th class="tit-figure prod">Programador</th>
                            <th class="tit-figure pric">Proyecto</th>
                            <th class="tit-figure qnty">Tipo</th>
                            <th class="tit-figure days">Fechas</th>
                        </tr>
                    </thead>
                    <tbody>';
                    for ($i = 0; $i<count($items); $i++){
                        
                        $html .= '
                            <tr>
                                <td class="dat-figure prod">' . $items[$i]['emp_fullname']          . '</td>
                                <td class="dat-figure pric">' . $items[$i]['pjt_name']          . '</td>
                                <td class="dat-figure qnty">' . $items[$i]['pjttp_name']      . '</td>
                                <td class="dat-figure days">' . $items[$i]['dates']        . '</td>
                            </tr>
                            ';
                        

                    }
    $html .= '
                   
                </tbody>
            </table>
            <!-- End Tabla de costo base  -->';

}
/* Tabla de equipo base -------------------------  */

// Pie de pagina
$foot = '
    <footer>
        <table class="table-footer">
            <tr>
                <td class="side-color"></td>
                <td>
                    <table width="100%">
                        <tr>
                            <td class="td-foot foot-date" width="25%">{DATE F j, Y}</td>
                            <td class="td-foot foot-page" width="25%" align="center">{PAGENO}/{nbpg}</td>
                            <td class="td-foot foot-rept" width="25%" style="text-align: right">Elaboró: '. $uname . '</td>
                            <td class="td-foot foot-rept" width="25%" style="text-align: right">Versión </td>
                        </tr>
                    </table>

                </td>
            </tr>
            
        </table>
        <table class="table-address">
            <tr>
                <td class="addData">55 5676-1113<br />55 5676-1483</td>
                <td class="addIcon addColor01"><img class="img-logo" src="../../../app/assets/img/icon-phone.png" style="width:4mm; height:auto;" /></td>

                <td class="addData">Av Guadalupe I. Ramírez 763,<br />Tepepan Xochimilco, 16020, CDMX</td>
                <td class="addIcon addColor02"><img class="img-logo" src="../../../app/assets/img/icon-location.png" style="width:4mm; height:auto;" /></td>
                <td class="addData">ventas@cttrentals.com<br />contacto@cttretnals.com<br />cotizaciones@cttrentals.com</td>
                <td class="addIcon addColor03"><img class="img-logo" src="../../../app/assets/img/icon-email.png"  style="width:4mm; height:auto;"/></td>
            </tr>
        </table>
    </footer>
';



$css = file_get_contents('../../assets/css/reports_p.css');

ob_clean();
ob_get_contents();
$mpdf= new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'Letter',
    'margin_left' => 0,
    'margin_right' => 0,
    'margin_top' => 5,
    'margin_bottom' => 30,
    'margin_header' => 0,
    'margin_footer' => 0, 
    'orientation' => 'L'
    ]);

$mpdf->shrink_tables_to_fit = 1;
$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter($foot);
$mpdf->WriteHTML($css,\Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);
$mpdf->Output(
    "Proyecto.pdf",
    \Mpdf\Output\Destination::INLINE
);


// "Cotizacion-". $items[0]['ver_code'].".pdf",

function decodificar($dato) {
    $resultado = base64_decode($dato);
    list($resultado, $letra) = explode('+', $resultado);
    $arrayLetras = array('M', 'A', 'R', 'C', 'O', 'S');
    for ($i = 0; $i < count($arrayLetras); $i++) {
        if ($arrayLetras[$i] == $letra) {
            for ($j = 1; $j <= $i; $j++) {
                $resultado = base64_decode($resultado);
            }
            break;
        }
    }
    return $resultado;
}