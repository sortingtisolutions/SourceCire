<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class DashboardFullModel extends Model
{

    public function __construct()
    {
      parent::__construct();
    }

    // Listado de categorias
    public function getTotalProjects()
    {
        $qry = "SELECT count(pjt_id) as Total FROM ctt_projects AS pj WHERE pj.pjt_status IN ('3','4','5','6');";
        return $this->db->query($qry);
    }


    // Listado de categorias
    public function getProjects($estatus)
    {
        
        $qry = "SELECT pj.pjt_id, pj.pjt_name, DATE(pj.pjt_date_start), 
        ifnull((
            select sum(mv.mov_quantity) from ctt_movements as mv inner join ctt_products as pd on pd.prd_id = mv.prd_id inner join ctt_subcategories as sb on sb.sbc_id = pd.sbc_id
            inner join ctt_categories as ct on ct.cat_id = sb.cat_id where mv.pjt_id = pj.pjt_id and ct.str_id = 1
        ),0) as 'Camaras',
        ifnull((
            select sum(mv.mov_quantity) from ctt_movements as mv inner join ctt_products as pd on pd.prd_id = mv.prd_id inner join ctt_subcategories as sb on sb.sbc_id = pd.sbc_id
            inner join ctt_categories as ct on ct.cat_id = sb.cat_id where mv.pjt_id = pj.pjt_id and ct.str_id = 3
        ),0) as 'Iluminación',
        ifnull((
            select sum(mv.mov_quantity) from ctt_movements as mv inner join ctt_products as pd on pd.prd_id = mv.prd_id inner join ctt_subcategories as sb on sb.sbc_id = pd.sbc_id
            inner join ctt_categories as ct on ct.cat_id = sb.cat_id where mv.pjt_id = pj.pjt_id and ct.str_id = 5
        ),0) as 'Expendables',
        ifnull((
            select sum(mv.mov_quantity) from ctt_movements as mv inner join ctt_products as pd on pd.prd_id = mv.prd_id inner join ctt_subcategories as sb on sb.sbc_id = pd.sbc_id
            inner join ctt_categories as ct on ct.cat_id = sb.cat_id where mv.pjt_id = pj.pjt_id and ct.str_id in (1, 3, 5) 
        ),0) as 'total',
        ifnull((select mov_status from ctt_movements as mp where mp.pjt_id = pj.pjt_id order by mp.mov_date desc limit 1 ), 0) as 'movement',
        (select em.emp_fullname from ctt_movements as mu inner join ctt_users as ur on ur.usr_id = mu.usr_id inner join ctt_employees as em on em.emp_id = ur.emp_id where mu.pjt_id = pj.pjt_id limit 1) as 'analista',
        (SELECT sum(pc.pjtcn_quantity) as total from ctt_projects_content as pc inner join ctt_projects as pt on pt.pjt_id = pc.pjt_id inner join ctt_products as pd on pd.prd_id = pc.prd_id where pt.pjt_status in (3,4,5,6) and pc.pjt_id  = pj.pjt_id) as 'TotalFull'
    from ctt_projects as pj where pj.pjt_status in (3,4,5,6);";
        //return $qry;

        return $this->db->query($qry);
    }

    // Listado de origen proyectos
    public function getProjectOrigen($estatus)
    {  
        $qry = "SELECT 
        case 
            when sp.str_id = 1 then 'camara'
            when sp.str_id = 3 then 'Iluminacion'
            when sp.str_id = 5 then 'Expendables'
        else '' end as store
        from ctt_projects_content as pc
        inner join ctt_stores_products as sp on sp.prd_id = pc.prd_id
        where pc.pjt_id = $estatus
        group by store;";
        //return $qry;

        return $this->db->query($qry);
    }
  
    /* public function getValueBudgets($params)
    {  
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);

        $qry = "SELECT ps.pjs_name, lc.loc_type_location ,COUNT(*) 
                FROM ctt_projects AS pj
                INNER JOIN ctt_projects_status ps ON ps.pjs_status=pj.pjt_status
                INNER JOIN ctt_location lc ON lc.loc_id=pj.loc_id
                WHERE pj.pjt_status=$pjtsta
                GROUP BY ps.pjs_name, lc.loc_type_location";
        //return $qry;

        return $this->db->query($qry);
    } */
  

    // Listado de categoria y cantidad full
    public function getKPIS()
    {  
        $qry = "SELECT
        sum(case when ct.str_id = '1' then pc.pjtcn_quantity end) as 'Camaras'
        , sum(case when ct.str_id = '3' then pc.pjtcn_quantity end) as 'Iluminación'
        , sum(case when ct.str_id = '5' then pc.pjtcn_quantity end) as 'Expendables'
        , sum(case when ct.str_id = '11' then pc.pjtcn_quantity end) as 'moviles'
        , sum(case when ct.cat_id = '9' or ct.cat_id = '10' then pc.pjtcn_quantity end) as 'especiales'
        , sum(pc.pjtcn_quantity) as total
        , case
        when pj.pjt_date_start > curdate() + 2 and pj.loc_id = 3 then 'V'
        when pj.pjt_date_start = curdate() + 2 and pj.loc_id = 3 then 'A'
        when pj.pjt_date_start = curdate() + 1 and pj.loc_id = 3 then 'A'
        when pj.pjt_date_start <= curdate() and pj.loc_id = 3 then 'R'
        else 'N' end as 'foro'
        from ctt_projects_content as pc
        inner join ctt_projects as pj on pj.pjt_id = pc.pjt_id
        inner join ctt_products as pd on pd.prd_id = pc.prd_id
        inner join ctt_subcategories as sb on sb.sbc_id = pd.sbc_id
        inner join ctt_categories as ct on ct.cat_id = sb.cat_id
        where pj.pjt_status in (3,4,5,6) and pj.pjt_date_end > curdate();";

        return $this->db->query($qry);
    }


    // Cambia el estado 
    public function changeStatus($id)
    {  
        $qry = "UPDATE ctt_movements set mov_status = 0 where pjt_id = $id;";

        return $this->db->query($qry);
    }

    public function getDatosBudget($params)
    {
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);
        $qry = "SELECT COUNT(*) AS canTotal, 
            SUM(Case when pj.pjt_status=$pjtsta then 1 ELSE 0 END) AS cantBudget,
            SUM(Case when pj.pjt_status=$pjtsta then 1 ELSE 0 END)/COUNT(*)*100 AS percent
            FROM ctt_projects AS pj;";

        return $this->db->query($qry);
    }


    public function getProgressData($params)
    {
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);

        /* $qry = "SELECT COUNT(*) AS total,
            SUM(CASE WHEN pj.loc_id=1 then 1 ELSE 0 END)/COUNT(*) * 100 AS pjLocal, 
            SUM(CASE WHEN pj.loc_id=2 then 1 ELSE 0 END)/COUNT(*) * 100 AS pjForanea,
            SUM(CASE WHEN pj.loc_id=3 then 1 ELSE 0 END)/COUNT(*) * 100 AS pjForo,
            SUM(CASE WHEN pj.loc_id=4 then 1 ELSE 0 END)/COUNT(*) * 100 AS pjMixta 
            FROM ctt_projects AS pj WHERE pj.pjt_status=$pjtsta;"; */
        /* $qry = "SELECT COUNT(*) AS total,
            SUM(CASE WHEN pj.loc_id=1 AND pj.pjt_status=$pjtsta then 1 ELSE 0 END)/COUNT(*) * 100 AS pjLocal, 
            SUM(CASE WHEN pj.loc_id=2 AND pj.pjt_status=$pjtsta then 1 ELSE 0 END)/COUNT(*) * 100 AS pjForanea,
            SUM(CASE WHEN pj.loc_id=3 AND pj.pjt_status=$pjtsta then 1 ELSE 0 END)/COUNT(*) * 100 AS pjForo,
            SUM(CASE WHEN pj.loc_id=4 AND pj.pjt_status=$pjtsta then 1 ELSE 0 END)/COUNT(*) * 100 AS pjMixta 
            FROM ctt_projects AS pj;"; */
            $qry = "SELECT pj.loc_id, COUNT(*) AS cant, (SELECT COUNT(*) FROM ctt_projects WHERE pjt_status=$pjtsta) 
            AS total, COUNT(*)/(SELECT COUNT(*) FROM ctt_projects WHERE pjt_status=$pjtsta) * 100 AS percent
            FROM ctt_projects AS pj WHERE pj.pjt_status=$pjtsta GROUP BY pj.loc_id";

        return $this->db->query($qry);
    }

    public function getSublettingData($params)
    {
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);

        /* $qry = "SELECT count(*) FROM ctt_projects AS pj
        INNER JOIN ctt_projects_content AS pc ON pc.pjt_id= pj.pjt_id
        INNER JOIN ctt_projects_detail AS pjd ON pjd.prd_id = pc.prd_id 
        INNER JOIN ctt_series AS sr ON sr.ser_id=pjd.ser_id
        WHERE SUBSTR(sr.ser_sku,8,1)='R'
        GROUP BY pj.pjt_id;"; */

        $qry = "SELECT COUNT(*) cantBudget, (SELECT COUNT(*) FROM ctt_subletting as sbl 
        INNER JOIN ctt_projects AS pjt ON pjt.pjt_id = sbl.prj_id AND pjt.pjt_status IN(2,4)) AS canTotal,
        IFNULL(COUNT(*)/(SELECT COUNT(*) FROM ctt_subletting as sbl 
        INNER JOIN ctt_projects AS pjt ON pjt.pjt_id = sbl.prj_id AND pjt.pjt_status IN(2,4))*100,0) AS percent  
        FROM ctt_subletting AS sb
        INNER JOIN ctt_projects AS pj ON pj.pjt_id = sb.prj_id WHERE sb.sub_date_start < CURRENT_TIME() 
        AND sb.sub_date_end > CURRENT_TIME() AND pj.pjt_status IN(4)";

        return $this->db->query($qry);
    }

    public function getPrjTransData($params)
    {
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);

        /* $qry = "SELECT count(*) FROM ctt_projects AS pj
        INNER JOIN ctt_projects_content AS pc ON pc.pjt_id= pj.pjt_id
        INNER JOIN ctt_products AS pd ON pd.prd_id = pc.prd_id
        INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
        WHERE sb.cat_id = 17;"; */

        /* $qry = "SELECT (SELECT COUNT(*) FROM ctt_projects AS pj
        INNER JOIN ctt_projects_content AS pc ON pc.pjt_id= pj.pjt_id
        INNER JOIN ctt_products AS pd ON pd.prd_id = pc.prd_id
        INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
        WHERE sb.cat_id IN(17,18)) AS cantBudget, 
        (SELECT COUNT(*) FROM ctt_projects) AS canTotal, 
        (SELECT COUNT(*) FROM ctt_projects AS pj
        INNER JOIN ctt_projects_content AS pc ON pc.pjt_id= pj.pjt_id
        INNER JOIN ctt_products AS pd ON pd.prd_id = pc.prd_id
        INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
        WHERE sb.cat_id IN(17,18))/(SELECT COUNT(*) FROM ctt_projects) * 100 AS percent"; */
    
        $qry = "SELECT COUNT(*) AS cantBudget, 
            (SELECT COUNT(*) FROM ctt_projects) AS canTotal, 
             COUNT(*)/(SELECT COUNT(*) FROM ctt_projects) * 100 AS percent 
             FROM (SELECT pj.pjt_id, pj.loc_id
                FROM ctt_projects AS pj
                INNER JOIN ctt_projects_content AS pc ON pc.pjt_id= pj.pjt_id
                INNER JOIN ctt_products AS pd ON pd.prd_id = pc.prd_id
                INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
                WHERE sb.cat_id IN($pjtsta) and pj.pjt_status not in(99) GROUP BY pj.pjt_id) AS proj;";

        return $this->db->query($qry);
    }
    public function getDummyData($params)
    {
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);

        $qry = "SELECT COUNT(*) cantBudget, (SELECT COUNT(*) FROM ctt_projects) 
        AS canTotal, COUNT(*)/(SELECT COUNT(*) FROM ctt_projects) * 100 AS percent
        FROM ctt_projects AS pj WHERE pj.pjt_status IN(8,9)";

        return $this->db->query($qry);
    }

    public function getTypeLocation($params)
    {
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);

        $qry = "SELECT COUNT(*) cantBudget, (SELECT COUNT(*) FROM ctt_projects) 
        AS canTotal, COUNT(*)/(SELECT COUNT(*) FROM ctt_projects) * 100 AS percent
        FROM ctt_projects AS pj WHERE pj.pjt_status IN($pjtsta)";

        return $this->db->query($qry);
    }
    public function getTypeCall($params)
    {
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);

        $qry = "SELECT COUNT(*) cantBudget, (SELECT COUNT(*) FROM ctt_projects) 
        AS canTotal, COUNT(*)/(SELECT COUNT(*) FROM ctt_projects) * 100 AS percent
        FROM ctt_projects AS pj WHERE pj.pjt_status IN(8)";

        return $this->db->query($qry);
    }

    public function getProgressDataT($params)
    {
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);
        /* $qry = "SELECT loc_id, COUNT(*) cant, (SELECT COUNT(*) FROM ctt_projects) 
                AS total, COUNT(*)/(SELECT COUNT(*) FROM ctt_projects) * 100 AS percent
                FROM ctt_projects AS pj
                INNER JOIN ctt_projects_content AS pc ON pc.pjt_id= pj.pjt_id
                INNER JOIN ctt_products AS pd ON pd.prd_id = pc.prd_id
                INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
                WHERE sb.cat_id = 17 GROUP BY pj.loc_id"; */
       /*  $qry = "SELECT loc_id, COUNT(*) AS cant, (SELECT COUNT(*) FROM ctt_projects) 
                AS total, COUNT(*)/(SELECT COUNT(*) FROM ctt_projects) * 100 AS percent FROM (SELECT pj.pjt_id, pj.loc_id
                FROM ctt_projects AS pj
                INNER JOIN ctt_projects_content AS pc ON pc.pjt_id= pj.pjt_id
                INNER JOIN ctt_products AS pd ON pd.prd_id = pc.prd_id
                INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
                WHERE sb.cat_id IN(17,18) GROUP BY pj.pjt_id) AS proj GROUP BY loc_id;"; */
        $qry = "SELECT loc_id, COUNT(*) AS cant, (SELECT COUNT(*) FROM (SELECT pj.pjt_id, pj.loc_id
                FROM ctt_projects AS pj
                INNER JOIN ctt_projects_content AS pc ON pc.pjt_id= pj.pjt_id
                INNER JOIN ctt_products AS pd ON pd.prd_id = pc.prd_id
                INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
                WHERE sb.cat_id IN($pjtsta) and pj.pjt_status not in(99) GROUP BY pj.pjt_id) AS proj) 
                                AS total, COUNT(*)/(SELECT COUNT(*) FROM (SELECT pj.pjt_id, pj.loc_id
                FROM ctt_projects AS pj
                INNER JOIN ctt_projects_content AS pc ON pc.pjt_id= pj.pjt_id
                INNER JOIN ctt_products AS pd ON pd.prd_id = pc.prd_id
                INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
                WHERE sb.cat_id IN($pjtsta) and pj.pjt_status not in(99) GROUP BY pj.pjt_id) AS proj) * 100 AS percent 
                                    FROM (SELECT pj.pjt_id, pj.loc_id
                FROM ctt_projects AS pj
                INNER JOIN ctt_projects_content AS pc ON pc.pjt_id= pj.pjt_id
                INNER JOIN ctt_products AS pd ON pd.prd_id = pc.prd_id
                INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
                WHERE sb.cat_id IN($pjtsta) and pj.pjt_status not in(99) GROUP BY pj.pjt_id) AS proj GROUP BY loc_id;";

        return $this->db->query($qry);
    }
    public function getProgressDataD($params)
    {
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);
        /* $qry = "SELECT loc_id, COUNT(*) cant, (SELECT COUNT(*) FROM ctt_projects) 
            AS total, COUNT(*)/(SELECT COUNT(*) FROM ctt_projects) * 100 AS percent
            FROM ctt_projects AS pj WHERE pj.pjttp_id =6 GROUP BY pj.loc_id";
 */     
        /* $qry ="SELECT em.emp_id, em.emp_fullname, COUNT(*) cant, (SELECT COUNT(*) FROM ctt_projects) 
                    AS total, COUNT(*)/(SELECT COUNT(*) FROM ctt_projects) * 100 AS percent, 
                    SUM(case when pj.pjt_status=1 then 1 ELSE 0 END) AS cantBudget, 
                    SUM(case when pj.pjt_status=2 then 1 ELSE 0 END) AS cantPlans, 
                    SUM(case when pj.pjt_status=4 then 1 ELSE 0 END) AS cantDetails,
                    SUM(case when pj.pjt_status=1 then 1 ELSE 0 END)/(SELECT COUNT(*) FROM ctt_projects) * 100 AS percentBudget, 
                    SUM(case when pj.pjt_status=2 then 1 ELSE 0 END)/(SELECT COUNT(*) FROM ctt_projects) * 100 AS percentPlans,
                    SUM(case when pj.pjt_status=4 then 1 ELSE 0 END)/(SELECT COUNT(*) FROM ctt_projects) * 100 AS percentDetails
                    FROM ctt_projects AS pj 
                    LEFT JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
                    LEFT JOIN ctt_employees AS em ON wap.emp_id = em.emp_id where em.emp_id IN(7,9,20,21) group BY emp_id"; */
        $qry ="SELECT em.emp_id, em.emp_fullname, COUNT(*) cant, (SELECT COUNT(*) FROM ctt_projects) 
                AS total, COUNT(*)/(SELECT COUNT(*) FROM ctt_projects) * 100 AS percent, 
                    SUM(case when pj.pjt_status=1 then 1 ELSE 0 END) AS cantBudget, 
                    SUM(case when pj.pjt_status=2 then 1 ELSE 0 END) AS cantPlans, 
                    SUM(case when pj.pjt_status=4 then 1 ELSE 0 END) AS cantDetails,
                    SUM(case when pj.pjt_status=1 then 1 ELSE 0 END)/(SELECT COUNT(*) FROM ctt_projects) * 100 AS percentBudget, 
                    SUM(case when pj.pjt_status=2 then 1 ELSE 0 END)/(SELECT COUNT(*) FROM ctt_projects) * 100 AS percentPlans,
                    SUM(case when pj.pjt_status=4 then 1 ELSE 0 END)/(SELECT COUNT(*) FROM ctt_projects) * 100 AS percentDetails
                    FROM ctt_employees AS em 
                    LEFT JOIN ctt_who_attend_projects AS wap ON wap.emp_id = em.emp_id
                    LEFT JOIN ctt_projects AS pj ON pj.pjt_id = wap.pjt_id
                    where em.emp_id IN(7,9,20,21) AND (em.are_id = 1 OR em.are_id=5) group BY emp_id;";
        return $this->db->query($qry);
    }

    public function getProgressDataS($params)
    {
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);
        $qry = "SELECT loc_id, COUNT(*) cant, (SELECT COUNT(*) FROM ctt_subletting as sbl 
        INNER JOIN ctt_projects AS pjt ON pjt.pjt_id = sbl.prj_id AND pjt.pjt_status IN(4))
                    AS total, COUNT(*)/(SELECT COUNT(*) FROM ctt_subletting as sbl 
        INNER JOIN ctt_projects AS pjt ON pjt.pjt_id = sbl.prj_id AND pjt.pjt_status IN(4)) * 100 AS percent
                    FROM ctt_projects AS pj
                    INNER JOIN ctt_subletting AS sb ON sb.prj_id= pj.pjt_id
                    WHERE sb.sub_date_start < CURRENT_TIME() AND sb.sub_date_end > CURRENT_TIME() AND pj.pjt_status IN(4)
                    GROUP BY loc_id";

        return $this->db->query($qry);
    }

    public function listProjects($params)
    {
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);
        $qry = "SELECT pj.pjt_id, pj.pjt_name, DATE(pj.pjt_date_start) as pjt_date_start, DATE(pj.pjt_date_end) as pjt_date_end, em.emp_id, em.emp_fullname, pjtc.pjttc_id, 
        pjtc.pjttc_name, loc.loc_id, loc.loc_type_location FROM ctt_projects AS pj 
        INNER JOIN ctt_location AS loc ON pj.loc_id = loc.loc_id
        INNER JOIN ctt_projects_type_called AS pjtc ON pjtc.pjttc_id = pj.pjttc_id
        INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
        INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
        WHERE pj.pjt_status = $pjtsta AND (em.are_id = 1 OR em.are_id=5);";

        return $this->db->query($qry);
    }

    public function listProjectsCierres($params)
    {
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);
        $qry = "SELECT pj.pjt_id, pj.pjt_name, DATE(pj.pjt_date_start) as pjt_date_start, DATE(pj.pjt_date_end) as pjt_date_end, em.emp_id, em.emp_fullname, pjtc.pjttc_id, 
        pjtc.pjttc_name, loc.loc_id, loc.loc_type_location, cu.cus_name
		  FROM ctt_projects AS pj 
        INNER JOIN ctt_location AS loc ON pj.loc_id = loc.loc_id
        INNER JOIN ctt_projects_type_called AS pjtc ON pjtc.pjttc_id = pj.pjttc_id
        INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
        INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
        LEFT JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
        LEFT JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
        WHERE pj.pjt_status = 9 AND (em.are_id = 1 OR em.are_id=5);";

        return $this->db->query($qry);
    }

    public function listProjectsTransport($params)
    {
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);
        $qry = "SELECT * 
        FROM (SELECT pj.pjt_id, pj.loc_id, pj.pjt_name, DATE(pj.pjt_date_start) as pjt_date_start, DATE(pj.pjt_date_end) as pjt_date_end, loc.loc_type_location, pjtc.pjttc_name, em.emp_fullname
           FROM ctt_projects AS pj
           INNER JOIN ctt_projects_content AS pc ON pc.pjt_id= pj.pjt_id
           INNER JOIN ctt_products AS pd ON pd.prd_id = pc.prd_id
           INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
                INNER JOIN ctt_location AS loc ON pj.loc_id = loc.loc_id
               INNER JOIN ctt_projects_type_called AS pjtc ON pjtc.pjttc_id = pj.pjttc_id
               INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
               INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
           WHERE sb.cat_id IN(17,18) AND (em.are_id = 1 OR em.are_id=5) and pj.pjt_status not in(99) GROUP BY pj.pjt_id) AS proj;";

        return $this->db->query($qry);
    }

    public function listSubarrendos($params)
    {
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);
        $qry = "SELECT pd.prd_id, pd.prd_sku, pd.prd_name, sb.sub_price, sb.sub_quantity, pj.pjt_id, pj.pjt_name, DATE(pj.pjt_date_start) AS pjt_date_start, DATE(pj.pjt_date_end) AS pjt_date_end, em.emp_id, em.emp_fullname, pjtc.pjttc_id, 
        pjtc.pjttc_name, loc.loc_id, loc.loc_type_location
        FROM ctt_subletting AS sb
        INNER JOIN ctt_projects AS pj ON pj.pjt_id = sb.prj_id 
        INNER JOIN ctt_products AS pd ON pd.prd_id = sb.prd_id
        INNER JOIN ctt_location AS loc ON pj.loc_id = loc.loc_id
        INNER JOIN ctt_projects_type_called AS pjtc ON pjtc.pjttc_id = pj.pjttc_id
        INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
        INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id  WHERE sb.sub_date_start < CURRENT_TIME() 
        AND sb.sub_date_end > CURRENT_TIME() AND pj.pjt_status IN(4) AND (em.are_id = 1 OR em.are_id=5)";

        return $this->db->query($qry);
    }
    /* public function getProgressDataTypeLoc($params)
    {
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);
        $qry = "SELECT loc_id, COUNT(*) cant, (SELECT COUNT(*) FROM ctt_projects WHERE pjt_status IN(8,9)) 
        AS total, COUNT(*)/(SELECT COUNT(*) FROM ctt_projects WHERE pjt_status IN(8,9)) * 100 AS percent
        FROM ctt_projects AS pj
        WHERE pj.pjt_status IN($pjtsta) GROUP BY pj.loc_id";

        return $this->db->query($qry);
    } */
    public function getProgressDataTypeCall($params)
    {
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);
        $qry = "SELECT pj.pjttc_id as loc_id, COUNT(*) cant, (SELECT COUNT(*) FROM ctt_projects WHERE pjt_status IN(8) ) 
        AS total, COUNT(*)/(SELECT COUNT(*) FROM ctt_projects WHERE pjt_status IN(8)) * 100 AS percent
        FROM ctt_projects AS pj
        WHERE pj.pjt_status IN(8) GROUP BY pj.pjttc_id;";

        return $this->db->query($qry);
    }

    // Listado de categoria y cantidad full
    public function getTotales()
    {  
        $qry = "SELECT
        sum(case when pj.pjt_status = 1 then 1 ELSE 0 end) as 'cotizacion'
        , sum(case when pj.pjt_status = 2 then 1 ELSE 0 end) as 'presupuesto'
        , sum(case when pj.pjt_status = 4 then 1 ELSE 0 end) as 'aprobados'
        , (SELECT COUNT(*) FROM ctt_subletting AS sb
INNER JOIN ctt_projects AS pjt ON pjt.pjt_id = sb.prj_id WHERE sb.sub_date_start < CURRENT_TIME() 
AND sb.sub_date_end > CURRENT_TIME() AND pjt.pjt_status IN(2,4)) AS subletting
        , sum(case when pj.pjt_status IN(8,9) then 1 ELSE 0 end) as 'analyst'
        , sum(case when pj.pjt_status IN(8) then 1 ELSE 0 end) as 'call'
        , sum(case when pj.pjt_status IN(9) then 1 ELSE 0 end) as 'close'
        , COUNT(*) as total
        , (SELECT COUNT(*) FROM (SELECT pj.pjt_id, pj.loc_id
            FROM ctt_projects AS pj
            INNER JOIN ctt_projects_content AS pc ON pc.pjt_id= pj.pjt_id
            INNER JOIN ctt_products AS pd ON pd.prd_id = pc.prd_id
            INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
            WHERE sb.cat_id IN(17,18) AND pj.pjt_status NOT IN(99) GROUP BY pj.pjt_id) AS proj) 'transport'
        , (SELECT COUNT(*) FROM (SELECT pj.pjt_id, pj.loc_id
            FROM ctt_projects AS pj
            INNER JOIN ctt_projects_content AS pc ON pc.pjt_id= pj.pjt_id
            INNER JOIN ctt_products AS pd ON pd.prd_id = pc.prd_id
            INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
            WHERE sb.cat_id IN(11,12) GROUP BY pj.pjt_id) AS proj) 'uniMoviles'
        from ctt_projects as pj;";

        return $this->db->query($qry);
    }
}