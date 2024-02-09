<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class DashboardModel extends Model
{

    public function __construct()
    {
      parent::__construct();
    }

    // Listado de categorias
    public function getTotalProjects()
    {
        $qry = "SELECT count(pjt_id) as Total FROM ctt_projects AS pj 
                WHERE pj.pjt_status IN ('4','7','8');";
        return $this->db->query($qry);
    }


    // Listado de categorias
    public function getProjects($estatus)
    {
        
        $qry = "SELECT pj.pjt_id, pj.pjt_name, DATE(pj.pjt_date_start) AS pjt_date_start, pj.pjt_status,
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
        ifnull((SELECT sum(pc.pjtcn_quantity) as total from ctt_projects_content as pc inner join ctt_projects as pt on pt.pjt_id = pc.pjt_id inner join ctt_products as pd on pd.prd_id = pc.prd_id where pt.pjt_status in (3,4,5,6,8) and pc.pjt_id  = pj.pjt_id),0) as 'TotalFull'
    from ctt_projects as pj where pj.pjt_status in (4,7,8);"; 

        return $this->db->query($qry);
    }

    // Listado de origen proyectos
    public function getProjectOrigen($estatus)
    {  
        $qry = "SELECT 
        CASE 
            WHEN sp.str_id = 1 THEN 'camara'
            WHEN sp.str_id = 3 THEN 'Iluminacion'
            WHEN sp.str_id = 5 THEN 'Expendables'
        ELSE '' END AS store
        FROM ctt_projects_content AS pc
        INNER JOIN ctt_stores_products AS sp on sp.prd_id = pc.prd_id
        WHERE pc.pjt_id = $estatus
        GROUP BY store;";
        //return $qry;

        return $this->db->query($qry);
    }
  
    // Listado de categoria y cantidad full
    public function getKPIS()
    {  
        $qry = "SELECT
        sum(case when ct.str_id = '1' then pc.pjtcn_quantity end) as 'Camaras'
        , sum(case when ct.str_id = '3' then pc.pjtcn_quantity end) as 'Iluminación'
        , sum(case when ct.str_id = '31' then pc.pjtcn_quantity end) as 'Expendables'
        , sum(case when ct.cat_id='11' then pc.pjtcn_quantity end) as 'moviles'
        , sum(case when ct.cat_id = '9' or ct.cat_id = '10' then pc.pjtcn_quantity end) as 'especiales'
        , sum(case when ct.cat_id='12' then pc.pjtcn_quantity END) AS 'plantas'
        , sum(pc.pjtcn_quantity) as total
        , sum(case when Date(pj.pjt_date_start) <= curdate() and pj.loc_id = 3 then pc.pjtcn_quantity END) AS 'foro'
        from ctt_projects_content as pc
        inner join ctt_projects as pj on pj.pjt_id = pc.pjt_id
        inner join ctt_products as pd on pd.prd_id = pc.prd_id
        inner join ctt_subcategories as sb on sb.sbc_id = pd.sbc_id
        inner join ctt_categories as ct on ct.cat_id = sb.cat_id
        where pj.pjt_status in (4,7,8) and pj.pjt_date_end > curdate()";

        return $this->db->query($qry);
    }

    // Cambia el estado 
    public function changeStatus($id)
    {  
        $qry = "UPDATE ctt_movements set mov_status = 0 where pjt_id = $id;";

        return $this->db->query($qry);
    }

    public function listProjects($params)
    {
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);
        $qry = "SELECT pj.pjt_id, pj.pjt_name, DATE(pj.pjt_date_start) AS pjt_date_start, DATE(pj.pjt_date_end) AS pjt_date_end, em.emp_id, em.emp_fullname, pjtc.pjttc_id, 
        pjtc.pjttc_name, loc.loc_id, loc.loc_type_location FROM ctt_projects AS pj 
        INNER JOIN ctt_location AS loc ON pj.loc_id = loc.loc_id
        INNER JOIN ctt_projects_type_called AS pjtc ON pjtc.pjttc_id = pj.pjttc_id
        INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
        INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
        where em.are_id = 1 OR em.are_id=5;";

        return $this->db->query($qry);
    }

    public function listProducts($params)
    {
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);
        $qry = "SELECT
        pd.prd_id, pd.prd_name, pd.prd_english_name, pd.prd_code_provider, pd.prd_name_provider, pd.prd_price,
        pd.prd_status, pd.prd_level, pd.prd_stock,pjc.pjtcn_quantity, em.emp_id, em.emp_fullname, pj.pjt_id, pj.pjt_name, pjtc.pjttc_name
        from ctt_projects_content as pjc
        inner join ctt_projects as pj on pj.pjt_id = pjc.pjt_id
        inner join ctt_products as pd on pd.prd_id = pjc.prd_id
        inner join ctt_subcategories as sb on sb.sbc_id = pd.sbc_id
        inner join ctt_categories as ct on ct.cat_id = sb.cat_id
        INNER JOIN ctt_projects_type_called AS pjtc ON pjtc.pjttc_id = pj.pjttc_id
        INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
        INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
        WHERE (em.are_id = 1 OR em.are_id=5) AND pj.pjt_status in (4,7,8) and pj.pjt_date_end > CURDATE()
        ORDER BY pj.pjt_id;";

        return $this->db->query($qry);
    }

    public function listCamaras($params)
    {
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);
        $qry = "SELECT
        pd.prd_id, pd.prd_name, pd.prd_english_name, pd.prd_code_provider, pd.prd_name_provider, pd.prd_price,
        pd.prd_status, pd.prd_level, pd.prd_stock,pjc.pjtcn_quantity, em.emp_id, em.emp_fullname, pj.pjt_id, pj.pjt_name, pjtc.pjttc_name
        from ctt_projects_content as pjc
        inner join ctt_projects as pj on pj.pjt_id = pjc.pjt_id
        inner join ctt_products as pd on pd.prd_id = pjc.prd_id
        inner join ctt_subcategories as sb on sb.sbc_id = pd.sbc_id
        inner join ctt_categories as ct on ct.cat_id = sb.cat_id
        INNER JOIN ctt_projects_type_called AS pjtc ON pjtc.pjttc_id = pj.pjttc_id
        INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
        INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
        WHERE (em.are_id = 1 OR em.are_id=5) AND pj.pjt_status in (4,7,8) and pj.pjt_date_end > CURDATE() AND ct.str_id =$pjtsta";

        return $this->db->query($qry);
    }
    public function listProductsCat($params)
    {
        $pjtsta = $this->db->real_escape_string($params['pjtsta']);
        $qry = "SELECT pd.prd_id, pd.prd_name, pd.prd_english_name, pd.prd_code_provider, pd.prd_name_provider, pd.prd_price,
        pd.prd_status, pd.prd_level, pd.prd_stock,pjc.pjtcn_quantity, pj.pjt_id, pj.pjt_name, pjtc.pjttc_name 
        from ctt_projects_content as pjc
        inner join ctt_projects as pj on pj.pjt_id = pjc.pjt_id
        inner join ctt_products as pd on pd.prd_id = pjc.prd_id
        inner join ctt_subcategories as sb on sb.sbc_id = pd.sbc_id
        inner join ctt_categories as ct on ct.cat_id = sb.cat_id
        INNER JOIN ctt_projects_type_called AS pjtc ON pjtc.pjttc_id = pj.pjttc_id
        INNER JOIN ctt_who_attend_projects AS wap ON wap.pjt_id = pj.pjt_id
        INNER JOIN ctt_employees AS em ON em.emp_id = wap.emp_id
        WHERE pj.pjt_status in (4,7,8) and pj.pjt_date_end > CURDATE() AND ct.cat_id IN ($pjtsta)";

        return $this->db->query($qry);
    }
}