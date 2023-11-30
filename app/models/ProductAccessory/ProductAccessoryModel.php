<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class ProductAccessoryModel extends Model
{
    public function __construct()
    {
      parent::__construct();
    }

// Listado de categorias   *******
    public function listCategories()
    {
        $qry = "SELECT * FROM ctt_categories WHERE cat_status = 1;";
        return $this->db->query($qry);
    }

// Listado de subcategorias
    public function listSubCategories($params)
    {
        $catId = $this->db->real_escape_string($params);
        $qry = "SELECT * FROM ctt_subcategories WHERE sbc_status = 1;";
        return $this->db->query($qry);
    }

// Listado de paquetes
public function listPackages()
{
    // $qry = "SELECT * FROM ctt_products WHERE prd_level ='K' AND prd_status =1;";
    // return $this->db->query($qry);
}

// Listado de subcategorias
    public function lastIdSubcategory($params)
    {
        // $sbcId = $this->db->real_escape_string($params);
        // $qry = "SELECT ifnull(max(convert(substring( prd_sku,5,3), signed integer)),0) + 1 as nextId  
        //         FROM ctt_products where sbc_id = $sbcId;";
        // return $this->db->query($qry);
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
    // $prdId = $this->db->real_escape_string($params);
    // $qry = "SELECT pr.prd_id, pr.prd_sku, pr.prd_name, pr.prd_price, pk.prd_parent  
    //         FROM ctt_products_packages AS pk
    //         INNER JOIN ctt_products AS pr ON pr.prd_id = pk.prd_id
    //         WHERE pk.prd_parent = $prdId AND prd_status =1;";
    // return $this->db->query($qry);
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

    /* $qry = "SELECT prd.prd_id , prd.prd_sku, prd_name, prd.prd_stock
            FROM ctt_products AS prd 
            WHERE SUBSTR(prd.prd_sku,1,10)='$prdId' AND prd.prd_level='A' AND prd.prd_status = 1 AND prd.prd_stock > 0
            GROUP BY prd.prd_id , prd.prd_sku, prd_name;";

 */
    $qry = "SELECT sr.ser_id, sr.ser_sku, prd_name, pd.prd_id
                FROM ctt_series AS sr
                INNER JOIN ctt_products AS pd ON pd.prd_id = sr.prd_id 
                WHERE SUBSTR(sr.ser_sku,1,11)=CONCAT(SUBSTR('$prdId',1,10),'A') AND pd.prd_level='A' AND pd.prd_status = 1;"; 
    /* $qry = "SELECT sr.ser_id, sr.ser_sku, pd.prd_name, pd.prd_id 
            FROM ctt_accesories AS acc
            INNER JOIN ctt_series AS sr ON sr.ser_id = acc.ser_id
            INNER JOIN ctt_products AS pd ON pd.prd_id = sr.prd_id
            WHERE acc.ser_parent = $prdId AND pd.prd_level='A' AND pd.prd_status = 1;"; */
    return $this->db->query($qry);

}

// Listado de accesorios
public function listAccesorios($param) // Edna
{
    $prd_sku       = $this->db->real_escape_string($param['prdSku']);
    $qry = "SELECT sr.ser_id, sr.ser_sku, pd.prd_name, pd.prd_id
    FROM ctt_series AS sr 
    INNER JOIN ctt_products AS pd ON sr.prd_id = pd.prd_id
    WHERE substr(sr.ser_sku,11,3) ='XXX' AND sr.ser_situation ='D' AND substr(sr.ser_sku,1,7)='$prd_sku';";
    return $this->db->query($qry);
}


// Registra el paquete o kit en la tabla de productos
public function saveAccesorioByProducto($param)
{
    $prd_id                     = $this->db->real_escape_string($param['prdId']);
    $prd_parent_id              = $this->db->real_escape_string($param['parentId']);
    $prd_parent_Sku             = $this->db->real_escape_string($param['skuPrdPadre']);
    $sbc_id                     = $this->db->real_escape_string($param['lsbc_id']);
    $ser_id                     = $this->db->real_escape_string($param['ser_id']);

    $countId = 1;

    //validamos que no exista previamente
    // $qry = "SELECT COUNT(*) FROM ctt_accesories WHERE ser_id = ".$ser_id." AND ser_parent = ". $prd_parent_id." AND prd_id = $prd_id";
    $qry ="SELECT COUNT(*) FROM ctt_series WHERE ser_id = ".$ser_id." AND prd_id_acc = ".$prd_parent_id." AND prd_id = $prd_id";
    $result = $this->db->query($qry);
    if ($row = $result->fetch_row()) {
        $countId = trim($row[0]);
    }

    if($countId == 0){

        //GENERAMOS EL NUMERO SUCESIVO DEL ACCESORIO
        /* $qry = "SELECT count(*)+1 FROM ctt_accesories WHERE ser_parent =".$prd_parent_id." AND prd_id = $prd_id"; */
        $qry = "SELECT count(*)+1 FROM ctt_series WHERE prd_id_acc =".$prd_parent_id." AND prd_id = $prd_id";
        
        $result = $this->db->query($qry);
        if ($row = $result->fetch_row()) {
            $acConsecutivo = trim($row[0]);
        }

        //$prd_parent_Sku =  $prd_parent_Sku."A".str_pad($acConsecutivo, 2, "0", STR_PAD_LEFT);
        $consecutivo = str_pad($acConsecutivo, 2, "0", STR_PAD_LEFT);
        
        /* $qry = "UPDATE ctt_products SET prd_sku = '$prd_parent_Sku' WHERE prd_id = $prd_id"; */
        /* $qry = "UPDATE ctt_series SET ser_sku = CONCAT(substr(ser_sku,1,8), SUBSTR('$prd_parent_Sku',8,3), substr('$prd_parent_Sku',12,2)), prd_id_acc = $prd_parent_id 
        WHERE ser_id = $ser_id"; */
        /* $qry = "UPDATE ctt_series SET ser_sku = '$prd_parent_Sku', prd_id_acc = $prd_parent_id 
        WHERE ser_id = $ser_id"; */
        $qry = "UPDATE ctt_series SET ser_sku = CONCAT(substr(ser_sku,1,7), 
                SUBSTR('$prd_parent_Sku',8,3), substr(ser_sku,8,3),'$consecutivo'), 
                prd_id_acc = $prd_parent_id   
                WHERE ser_id = $ser_id";
        $this->db->query($qry);

       /*  $qry = "INSERT INTO ctt_accesories(prd_id, ser_parent,acr_status,ser_id)
                VALUES ($prd_id, $prd_parent_id,1,$ser_id)";
        $this->db->query($qry); */

        $qry2 = "SELECT ser_sku FROM ctt_series WHERE ser_id = $ser_id";
        $res = $this->db->query($qry2);
        $rs = $res->fetch_object();
        
        $result = $rs->ser_sku;
        /* $result = $prd_parent_Sku; */
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
        $ser_id            = $this->db->real_escape_string($param['serId']);

        /* $qry =  "DELETE FROM ctt_accesories WHERE ser_parent = $prd_parent AND ser_id = $ser_id;" ;
        $this->db->query($qry); */

        /* $qry = "UPDATE ctt_products SET prd_sku = CONCAT(substr(prd_sku,1,7),'XXX') WHERE prd_id = ".$prd_id.""; // Modificado por edna
        $this->db->query($qry); */
        $qry = "SELECT substr(pd.prd_sku,9,2) 'sku' FROM ctt_series AS sr 
        INNER JOIN ctt_products AS pd ON pd.prd_id = sr.prd_id WHERE ser_id = $ser_id"; // Modificado por edna
        $res = $this->db->query($qry);
        $rs = $res->fetch_object();
        $sku = $rs->sku; // para evitar que al eliminar se tome como dos X donde no van.

        $qry = "UPDATE ctt_series SET ser_sku = CONCAT(substr(ser_sku,1,7),'A','$sku','XXX',substr(ser_sku,14,2)), prd_id_acc = 0  WHERE ser_id = $ser_id"; // Modificado por edna
        $this->db->query($qry);
        
        return $ser_id;
    }    
}