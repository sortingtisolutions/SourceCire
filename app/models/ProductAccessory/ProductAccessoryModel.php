<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class ProductAccessoryModel extends Model
{
    public function __construct()
    {
      parent::__construct();
    }

// Listado de categorias   *******
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
public function listPackages()
{

}

// Listado de subcategorias
    public function lastIdSubcategory($params)
    {

    }

    
// Listado de productos   ******
public function listProducts()
{
    $qry = "SELECT prd_id, prd_sku, prd_name, prd_price, sbc_id 
            FROM ctt_products 
            WHERE prd_status = 1 order by prd_sku;";
    return $this->db->query($qry);
}

// Listado de productos
public function listProductsById($request_params)
{
    $sbc_id = $this->db->real_escape_string($request_params['sbc_id']);
    
    $qry = "SELECT prd_id, prd_sku, prd_name, prd_price, sbc_id 
            FROM ctt_products 
            WHERE prd_status = 1 and sbc_id = $sbc_id and 
            prd_level <> 'A' order by prd_sku;";
    
    return $this->db->query($qry);
}

// Listado de productos del paquete
public function listProductsPack($params)
{

}

public function listSeriesProd($params) // Edna
{
    $prdId = $this->db->real_escape_string($params);

    $qry = "SELECT ser.ser_id, ser.ser_sku,ser.ser_serial_number,prd.prd_name, prd.prd_id  
            FROM ctt_series AS ser
            INNER JOIN ctt_products AS prd ON prd.prd_id=ser.prd_id
            LEFT JOIN ctt_stores_products AS sp ON sp.ser_id = ser.ser_id
            WHERE prd.prd_id=$prdId AND sp.stp_quantity > 0";
    return $this->db->query($qry);
}

// Listado de accesorios por id
public function getAccesoriesById($params)
{
    $prdId = $this->db->real_escape_string($params['prdId']);

    $qry = "SELECT prd.prd_id , prd.prd_sku, prd_name, prd.prd_stock
            FROM ctt_products AS prd 
            WHERE SUBSTR(prd.prd_sku,1,10)='$prdId' AND prd.prd_level='A' AND prd.prd_status = 1 AND prd.prd_stock > 0
            GROUP BY prd.prd_id , prd.prd_sku, prd_name;";

    return $this->db->query($qry);

}

// Listado de accesorios
public function listAccesorios($param) // Edna
{
    //$prd_id       = $this->db->real_escape_string($param['prdId']);
    $qry = "SELECT prd_id, prd_name, prd_sku, prd_stock 
            FROM ctt_products 
            WHERE prd_level = 'A' AND substr(prd_sku,8,3) = 'XXX' AND prd_stock>0;";
    return $this->db->query($qry);
}


// Registra el paquete o kit en la tabla de productos
public function saveAccesorioByProducto($param)
{
    $prd_id                     = $this->db->real_escape_string($param['prdId']);
    $prd_parent_id              = $this->db->real_escape_string($param['parentId']);
    $prd_parent_Sku             = $this->db->real_escape_string($param['skuPrdPadre']);
    $sbc_id                     = $this->db->real_escape_string($param['lsbc_id']);

    $countId = 1;

    //validamos que no exista previamente
    $qry = "SELECT COUNT(*) FROM ctt_accesories WHERE prd_id = ".$prd_id ." AND prd_parent = ". $prd_parent_id."";
    $result = $this->db->query($qry);
    if ($row = $result->fetch_row()) {
        $countId = trim($row[0]);
    }

    if($countId == 0){

        //GENERAMOS EL NUMERO SUCESIVO DEL ACCESORIO
        $qry = "SELECT count(*)+1 FROM ctt_accesories WHERE prd_parent =".$prd_parent_id."";
        $result = $this->db->query($qry);
        if ($row = $result->fetch_row()) {
            $acConsecutivo = trim($row[0]);
        }

        $prd_parent_Sku =  $prd_parent_Sku."A".str_pad($acConsecutivo, 2, "0", STR_PAD_LEFT);
        
        $qry = "UPDATE ctt_products SET prd_sku = '$prd_parent_Sku' WHERE prd_id = $prd_id";
        $this->db->query($qry);

        $qry = "INSERT INTO ctt_accesories(prd_parent,acr_status,prd_id)
                VALUES ($prd_parent_id,1,$prd_id)";
        $this->db->query($qry);

        $result = $prd_parent_Sku;
        //$result = $this->db->insert_id;
    }else{
        $result = 0;
    }
    return $result ;
}


// Registra el producto al paquete
    public function SaveProduct($param)
    {
        $prd_id            = $this->db->real_escape_string($param['prdId']);
        $prd_parent        = $this->db->real_escape_string($param['prdParent']);

        $qry = "INSERT INTO ctt_products_packages ( prd_parent, prd_id ) 
                VALUES ( '$prd_parent', '$prd_id');
        ";

        $this->db->query($qry);
        $pckId = $this->db->insert_id;
                
        $qrr =  "SELECT pr.prd_id, pr.prd_sku, pr.prd_name, pr.prd_price, pk.prd_parent  
                 FROM ctt_products_packages AS pk
                 INNER JOIN ctt_products AS pr ON pr.prd_id = pk.prd_id
                 WHERE pk.pck_id = $pckId;" ;
        
        $result = $this->db->query($qrr);
        return $result;
    }


// Obtiene detalle del producto
    public function detailPack($param)
    {
        $prd_id  = $this->db->real_escape_string($param['prdId']);

        $qry =  "SELECT pr.prd_id, pr.prd_sku, pr.prd_name, pr.prd_price, 
                        pr.sbc_id, sb.cat_id
                FROM ctt_products AS pr
                INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pr.sbc_id 
                WHERE prd_id = $prd_id" ;
        
        $result = $this->db->query($qry);
        return $result;
    }    

// Borra el accesorio del producto
    public function deleteProduct($param)
    {
        $prd_id            = $this->db->real_escape_string($param['prdId']);
        $prd_parent        = $this->db->real_escape_string($param['prdParent']);

        $qry =  "DELETE FROM ctt_accesories WHERE prd_parent = $prd_parent AND prd_id = $prd_id;" ;
        $this->db->query($qry);

        $qry = "UPDATE ctt_products SET prd_sku = CONCAT(substr(prd_sku,1,7),'XXX') WHERE prd_id = ".$prd_id.""; // Modificado por edna
        $this->db->query($qry);
        
        return $prd_id;
    }    
}