<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class SeriestoProductsModel extends Model
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

    public function listCategoriesAcc()
    {
        $qry = "SELECT * FROM ctt_categories WHERE cat_status = 1 AND cat_id = 40;";
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
            WHERE prd.prd_id=$prdId AND sp.stp_quantity > 0 GROUP BY ser.ser_id";
    return $this->db->query($qry);
}

public function list_products($params) // Edna
{
    $prdId = $this->db->real_escape_string($params);

    /* $qry = "SELECT ser.ser_id, ser.ser_sku,ser.ser_serial_number,prd.prd_name, prd.prd_id  
            FROM ctt_series AS ser
            INNER JOIN ctt_products AS prd ON prd.prd_id=ser.prd_id
            LEFT JOIN ctt_stores_products AS sp ON sp.ser_id = ser.ser_id
            WHERE prd.prd_id=$prdId AND sp.stp_quantity > 0 GROUP BY ser.ser_id"; */
    $qry = "SELECT prd.prd_id ser_id, prd.prd_sku ser_sku, prd.prd_name, prd.prd_name ser_serial_number, prd.prd_id
    FROM  ctt_products AS prd
    LEFT JOIN ctt_stores_products AS sp ON sp.prd_id = prd.prd_id
    WHERE prd.prd_id = $prdId AND sp.stp_quantity > 0 GROUP BY prd.prd_id";
    return $this->db->query($qry);
}

// Listado de accesorios por id
public function getAccesoriesById($params)
{
    $prdId = $this->db->real_escape_string($params['prdId']);

   /*  $qry = "SELECT prd.prd_id , prd.prd_sku, prd_name, prd.prd_stock
            FROM ctt_products AS prd 
            WHERE SUBSTR(prd.prd_sku,1,10)='$prdId' AND prd.prd_level='A' AND prd.prd_status = 1 AND prd.prd_stock > 0
            GROUP BY prd.prd_id , prd.prd_sku, prd_name;"; */
   /*  $qry = "SELECT sr.ser_id prd_id, sr.ser_sku prd_sku, prd.prd_name, prd.prd_stock , 0 quantity
    FROM ctt_accesories AS acc 
    INNER JOIN ctt_series AS sr ON sr.ser_id = acc.ser_id
    INNER JOIN ctt_products AS prd ON prd.prd_id = sr.prd_id
    WHERE acc.prd_parent = $prdId"; */

    $qry = "SELECT sr.ser_id prd_id, sr.ser_sku prd_sku, prd.prd_name, prd.prd_stock , 0 quantity
    FROM ctt_series AS sr
    INNER JOIN ctt_products AS prd ON prd.prd_id = sr.prd_id
    WHERE sr.prd_id_acc = $prdId";

    return $this->db->query($qry);

}

// Listado de productos correspondientes a accesorios por id
public function getProdAccesoriesById($params)
{
    $prdId = $this->db->real_escape_string($params['prdId']);

   /*  $qry = "SELECT prd.prd_id , prd.prd_sku, prd_name, prd.prd_stock
            FROM ctt_products AS prd 
            WHERE SUBSTR(prd.prd_sku,1,10)='$prdId' AND prd.prd_level='A' AND prd.prd_status = 1 AND prd.prd_stock > 0
            GROUP BY prd.prd_id , prd.prd_sku, prd_name;"; */
    /* $qry = "SELECT acc.prd_parent, acc.prd_id, acc.pcr_quantity quantity, prd.prd_id, prd.prd_sku, prd.prd_name
    FROM ctt_products_accesory AS acc 
    INNER JOIN ctt_products AS prd ON prd.prd_id = acc.prd_id
    WHERE acc.prd_parent = $prdId"; */

    $qry = "SELECT acc.prd_parent, acc.prd_id, acc.pck_quantity quantity, prd.prd_id, prd.prd_sku, prd.prd_name
    FROM ctt_products_packages AS acc 
    INNER JOIN ctt_products AS prd ON prd.prd_id = acc.prd_id
    WHERE acc.prd_parent = $prdId AND acc.prd_type_asigned = 'V'";

    return $this->db->query($qry);

}

// Listado de accesorios
public function listAccesorios($param) // Edna
{
    $sbc_id       = $this->db->real_escape_string($param['prdId']);
    // $qry = "SELECT prd_id, prd_name, prd_sku, prd_stock 
    //         FROM ctt_products 
    //         WHERE prd_level = 'A' AND substr(prd_sku,1,2)='40' AND prd_stock>0;";

        /* $qry = "SELECT ser.ser_id as prd_id, prd_name, ser_sku as prd_sku, prd_stock , 0 quantity
        FROM ctt_products as prd
        inner join ctt_series as ser on ser.prd_id=prd.prd_id 
        LEFT JOIN ctt_accesories AS acc ON acc.ser_id = ser.ser_id
        WHERE prd_level = 'A' AND sbc_id=$prd_id AND prd_stock>0 AND ISNULL(prd_parent) ORDER BY ser.ser_sku ASC;"; */

    $qry = "SELECT ser.ser_id as prd_id, prd_name, ser_sku as prd_sku, prd_stock , 0 quantity
    FROM ctt_products as prd
    inner join ctt_series as ser on ser.prd_id=prd.prd_id 
    WHERE sbc_id=$sbc_id AND prd_stock>0 AND ser.prd_id_acc = 0";
    return $this->db->query($qry);
}

// Listado de accesorios
public function listPrdAccesorios($param) // Edna
{
    $prd_id       = $this->db->real_escape_string($param['prdId']);
    // $qry = "SELECT prd_id, prd_name, prd_sku, prd_stock 
    //         FROM ctt_products 
    //         WHERE prd_level = 'A' AND substr(prd_sku,1,2)='40' AND prd_stock>0;";

        $qry = "SELECT prd.prd_id, prd_name, prd_sku, 1 quantity
        FROM ctt_products as prd
		  LEFT JOIN ctt_products_packages AS acc ON acc.prd_id = prd.prd_id 
        WHERE sbc_id=$prd_id AND prd_stock>0 AND ISNULL(acc.prd_parent)";
    return $this->db->query($qry);
}
public function updateQuantityProds($param)
{
    $prd_id            = $this->db->real_escape_string($param['prdId']);
    $prd_parent        = $this->db->real_escape_string($param['prdParent']);
    $prd_qty           = $this->db->real_escape_string($param['prdQty']);

    $qry =  "UPDATE ctt_products_packages SET pck_quantity = $prd_qty 
            WHERE prd_parent = $prd_parent AND prd_id = $prd_id AND prd_type_asigned = 'V';" ;
    
    $this->db->query($qry);

    return $prd_id;
}    

// Registra el paquete o kit en la tabla de productos
public function saveAccesorioByProducto($param)
{
    $serId                      = $this->db->real_escape_string($param['serId']);
    $prdId                      = $this->db->real_escape_string($param['prdId']);
    $prd_parent_id              = $this->db->real_escape_string($param['parentId']);
    $prd_parent_Sku             = $this->db->real_escape_string($param['skuPrdPadre']);
    $sbc_id                     = $this->db->real_escape_string($param['lsbc_id']);
    $filas                      = $this->db->real_escape_string($param['filas']);

    $countId = 1;

    if ($filas == 0) {
        $qry = "UPDATE ctt_products set prd_type_asigned = 'F'
                where prd_id=$prdId";
        $this->db->query($qry);
    }
    //validamos que no exista previamente
    // $qry = "SELECT COUNT(*) FROM ctt_accesories 
    //         WHERE prd_id = ".$prd_id ." AND prd_parent = ". $prd_parent_id."";
    // $result = $this->db->query($qry);
    // if ($row = $result->fetch_row()) {
    //     $countId = trim($row[0]);
    // }


    //GENERAMOS EL NUMERO SUCESIVO DEL ACCESORIO
    // $qry = "SELECT count(*)+1 FROM ctt_accesories WHERE prd_parent =".$prd_parent_id."";
    // $result = $this->db->query($qry);
    // if ($row = $result->fetch_row()) {
    //     $acConsecutivo = trim($row[0]);
    // }

    // $prd_parent_Sku =  $prd_parent_Sku."A".str_pad($acConsecutivo, 2, "0", STR_PAD_LEFT);
    
    // $qry = "UPDATE ctt_products SET prd_sku = '$prd_parent_Sku' WHERE prd_id = $prd_id";
    // $this->db->query($qry);

    // $qry = "INSERT INTO ctt_accesories(prd_parent,acr_status,prd_id)
    //         VALUES ($prd_parent_id,1,$prd_id)";

    $qry = "UPDATE ctt_series set prd_id_acc=$prd_parent_id
            where ser_id=$serId";
    $this->db->query($qry);

   /*  $qry = "INSERT INTO ctt_accesories(prd_parent,acr_status,ser_id)
    VALUES ($prd_parent_id,1,$serId)";
    $this->db->query($qry); */
    

    $result = $prd_parent_Sku;
    //$result = $this->db->insert_id;
    
    return $result ;
}

// Registra el paquete o kit en la tabla de productos
public function saveAccesorioProducto($param)
{
    $prd_id                     = $this->db->real_escape_string($param['prdId']);
    $prd_parent_id              = $this->db->real_escape_string($param['parentId']);
    $prd_parent_Sku             = $this->db->real_escape_string($param['skuPrdPadre']);
    $quantity                   = $this->db->real_escape_string($param['quantity']);
    $sbc_id                     = $this->db->real_escape_string($param['lsbc_id']);
    $filas                      = $this->db->real_escape_string($param['filas']);


    /* $qry = "UPDATE ctt_series set prd_id_acc=$prd_parent_id
            where ser_id=$prd_id";
    $this->db->query($qry); */

   /*  $qry = "INSERT INTO ctt_products_accesory(prd_parent,pcr_quantity,prd_id)
    VALUES ($prd_parent_id,'$quantity',$prd_id)";
    $this->db->query($qry); */
    if ($filas == 0) {
        $qry = "UPDATE ctt_products set prd_type_asigned = 'V'
                where prd_id=$prd_parent_id";
        $this->db->query($qry);
    }

    $qry = "INSERT INTO ctt_products_packages ( prd_parent, pck_quantity, prd_id, prd_type_asigned)
    VALUES ($prd_parent_id,'$quantity',$prd_id, 'V')";
    $this->db->query($qry);
    

    $result = $prd_parent_Sku;
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
    public function deleteProductSer($param)
    {
        $prd_id            = $this->db->real_escape_string($param['prdId']);
        $prd_parent        = $this->db->real_escape_string($param['prdParent']);

        /* $qry =  "DELETE FROM ctt_accesories WHERE prd_parent = $prd_parent AND ser_id = $prd_id;" ;
        $this->db->query($qry); */
        $qry =  "UPDATE ctt_series set prd_id_acc= 0 WHERE ser_id = $prd_id;" ;
        $this->db->query($qry);

        /* $qry = "UPDATE ctt_products SET prd_sku = CONCAT(substr(prd_sku,1,7),'XXX') WHERE prd_id = ".$prd_id.""; // Modificado por edna
        $this->db->query($qry); */
        
        return $prd_id;
    }    

    public function deleteProduct($param)
    {
        $prd_id            = $this->db->real_escape_string($param['prdId']);
        $prd_parent        = $this->db->real_escape_string($param['prdParent']);

        $qry =  "DELETE FROM ctt_products_packages WHERE prd_parent = $prd_parent AND prd_id = $prd_id AND prd_type_asigned = 'V';" ;
        $this->db->query($qry);

        /* $qry = "UPDATE ctt_products SET prd_sku = CONCAT(substr(prd_sku,1,7),'XXX') WHERE prd_id = ".$prd_id.""; // Modificado por edna
        $this->db->query($qry); */
        
        return $prd_id;
    }
}