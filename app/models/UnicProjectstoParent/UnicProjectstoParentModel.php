<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class UnicProjectstoParentModel extends Model
{
    public function __construct()
    {
      parent::__construct();
    }

// Listado de categorias  *****
    // public function listCategories()
    // {
        
    //     $qry = "SELECT * FROM ctt_categories WHERE cat_status = 1;";
    //     return $this->db->query($qry);
    // }

// Listado de subcategorias
    // public function listSubCategories($params)
    // {
    //     $catId = $this->db->real_escape_string($params);
        
    //     $qry = "SELECT * FROM ctt_subcategories WHERE sbc_status = 1;";
    //     return $this->db->query($qry);
    // }

// Listado de paquetes
public function listPackages($params)
{
    $qry = "SELECT pj.pjt_id, pj.pjt_number, pj.pjt_name, pj.pjt_date_start, pj.pjt_date_end 
            FROM ctt_projects as pj WHERE pj.pjt_status = 40 ;";

    return $this->db->query($qry);
}

// Listado de subcategorias
    public function lastIdSubcategory($params)
    {
        $sbcId = $this->db->real_escape_string($params);
        $qry = "SELECT ifnull(max(convert(substring( prd_sku,5,4), signed integer)),0) + 1 as nextId  FROM ctt_products where sbc_id = $sbcId;";
        return $this->db->query($qry);
    }

    
// Listado de productos
public function listProducts()
{
    $qry = "SELECT pj.pjt_id, pj.pjt_number, pj.pjt_name, pj.pjt_date_start, pj.pjt_date_end, pj.pjt_parent
    FROM ctt_projects as pj WHERE pj.pjt_parent = 0 AND pj.pjt_status NOT IN(40);";
    return $this->db->query($qry);
}

// Listado de productos del paquete
public function listProductsPack($params)
{
    $prjId = $this->db->real_escape_string($params['prjId']);
    $qry = "SELECT pj.pjt_id, pj.pjt_number, pj.pjt_name, pj.pjt_date_start, pj.pjt_date_end, pj.pjt_parent
    FROM ctt_projects as pj WHERE pj.pjt_parent = $prjId ;";
    return $this->db->query($qry);
}

// Registra el paquete o kit en la tabla de productos
    public function savePack($param)
    {
        $prd_sku            = $this->db->real_escape_string($param['prdsku']);
        $prd_name           = $this->db->real_escape_string($param['prdName']);
        $prd_model          = $this->db->real_escape_string($param['prdModel']);
        $prd_price	        = $this->db->real_escape_string($param['prdPrice']);
        $prd_coin_type      = $this->db->real_escape_string($param['prdCoinType']);
        $prd_visibility     = $this->db->real_escape_string($param['prdVisibility']);
        $prd_comments       = $this->db->real_escape_string($param['prdComments']);
        $prd_status	        = $this->db->real_escape_string($param['prdStatus']);
        $prd_level          = $this->db->real_escape_string($param['prdLevel']);
        $sbc_id             = $this->db->real_escape_string($param['sbcId']);
        $srv_id             = $this->db->real_escape_string($param['srvId']);
        $cin_id             = $this->db->real_escape_string($param['exmId']);
        $prd_insured        =  1;
        $prd_stock          =  1; // *** Edna V2

        $qry = "INSERT INTO ctt_products (prd_sku, prd_name, prd_model, prd_price, prd_visibility, 
        prd_comments, prd_status, prd_level, sbc_id, srv_id, cin_id, prd_insured, prd_stock) 
        VALUES ('$prd_sku', UPPER('$prd_name'), '$prd_model', '$prd_price', '$prd_visibility', '$prd_comments',
         '$prd_status', '$prd_level', '$sbc_id', '$srv_id', '$cin_id','$prd_insured','$prd_stock');
        ";
         $this->db->query($qry);
        $result = $this->db->insert_id;
        return $result . '|' . $prd_sku . '|' . $prd_name . '|' . $prd_price;
    }

// Registra el producto al paquete
    public function SaveProject($param)
    {
        $prj_id            = $this->db->real_escape_string($param['prjId']);
        $prj_parent        = $this->db->real_escape_string($param['prjParent']);

        $qry =  "UPDATE ctt_projects SET pjt_parent = '$prj_parent' where pjt_id = '$prj_id'" ;
        $this->db->query($qry);
        $qrr =  "SELECT pj.pjt_id, pj.pjt_number, pj.pjt_name, pj.pjt_date_start, pj.pjt_date_end, pj.pjt_parent
                    FROM ctt_projects as pj
                    WHERE pjt_id = '$prj_id';" ;
        $result = $this->db->query($qrr);
        return $result;
    }


// Obtiene detalle de paquete
    public function detailPack($param)
    {
        $prd_id            = $this->db->real_escape_string($param['prjId']);

        $qry =  "SELECT pj.pjt_id, pj.pjt_number, pj.pjt_name, pj.pjt_date_start, pj.pjt_date_end, pj.pjt_parent
        FROM ctt_projects as pj
        WHERE pjt_id = $prd_id" ;
        
        $result = $this->db->query($qry);
        return $result;
    }    

// Borra el producto y paquete
    public function deletePackages($param)
    {
        $prd_id            = $this->db->real_escape_string($param['prdId']);

        $qry =  "DELETE FROM ctt_products WHERE prd_id = $prd_id" ;
        $this->db->query($qry);

        $qrr =  "DELETE FROM ctt_products_packages WHERE prd_parent = $prd_id" ;
        $this->db->query($qrr);

        return $prd_id;
    }    


// Borra el producto del paquete
    public function deleteProduct($param)
    {
        $prj_id            = $this->db->real_escape_string($param['prdId']);

        $qry =  "UPDATE ctt_projects SET pjt_parent = 0 where pjt_id = '$prj_id'" ;
        $this->db->query($qry);

        return $prj_id;
    }    

// Borra el producto del paquete
    public function updatePackage($param)
    {
        $prd_id             = $this->db->real_escape_string($param['prdId']);
        $prd_name           = $this->db->real_escape_string($param['prdName']);
        $prd_price          = $this->db->real_escape_string($param['prdPrice']);

        $qry =  "UPDATE ctt_products SET prd_name = '$prd_name', prd_price = '$prd_price' 
                WHERE prd_id = '$prd_id';" ;
        
        return $this->db->query($qry);

    }      
    
// Actualiza la cantidad de productos asociados al paquete
    public function updateQuantityProds($param)
    {
        $prd_id            = $this->db->real_escape_string($param['prdId']);
        $prd_parent        = $this->db->real_escape_string($param['prdParent']);
        $prd_qty           = $this->db->real_escape_string($param['prdQty']);

        $qry =  "UPDATE ctt_products_packages SET pck_quantity = $prd_qty 
                WHERE prd_parent = $prd_parent AND prd_id = $prd_id;" ;
        
        $this->db->query($qry);

        return $prd_id;
    }    

}