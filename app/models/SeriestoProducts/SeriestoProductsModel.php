<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class SeriestoProductsModel extends Model
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

    public function listCategoriesAcc()
    {
        $qry = "SELECT * FROM ctt_categories WHERE cat_status = 1 AND cat_id = 40;";
        return $this->db->query($qry);
    }

// Listado de subcategorias
    // public function listSubCategories($params)
    // {
    //     $catId = $this->db->real_escape_string($params);
    //     $qry = "SELECT * FROM ctt_subcategories WHERE sbc_status = 1;";
    //     return $this->db->query($qry);
    // }

    
// Listado de productos   ******
public function listProducts()
{
    $qry = "SELECT prd_id, prd_sku, prd_name, prd_price, sbc_id 
            FROM ctt_products 
            WHERE prd_status = 1 AND prd_level != 'K' order by prd_sku;";
    return $this->db->query($qry);
}

// Listado de productos
public function listProductsById($request_params)
{
    $sbc_id = $this->db->real_escape_string($request_params['sbc_id']);
    
    $qry = "SELECT prd_id, prd_sku, prd_name, prd_price, sbc_id 
            FROM ctt_products 
            WHERE prd_status = 1 and sbc_id = $sbc_id and 
            prd_level <> 'A' AND prd_level != 'K' order by prd_sku;";
    
    return $this->db->query($qry);
}

public function listSeriesProd($params) // Edna
{
    $prdId = $this->db->real_escape_string($params);

    $qry = "SELECT ser.ser_id, ser.ser_sku,ser.ser_serial_number,prd.prd_name, prd.prd_id  
            FROM ctt_series AS ser
            INNER JOIN ctt_products AS prd ON prd.prd_id=ser.prd_id
            LEFT JOIN ctt_stores_products AS sp ON sp.ser_id = ser.ser_id
            WHERE prd.prd_id=$prdId GROUP BY ser.ser_id";
    return $this->db->query($qry);
}

public function list_products($params) // Edna
{
    $prdId = $this->db->real_escape_string($params);

    $qry = "SELECT prd.prd_id ser_id, prd.prd_sku ser_sku, prd.prd_name, prd.prd_name ser_serial_number, prd.prd_id
    FROM  ctt_products AS prd
    LEFT JOIN ctt_stores_products AS sp ON sp.prd_id = prd.prd_id
    WHERE prd.prd_id = $prdId GROUP BY prd.prd_id, prd.prd_sku";
    return $this->db->query($qry);
}

// Listado de accesorios por id
public function getAccesoriesById($params)
{
    $prdId = $this->db->real_escape_string($params['prdId']);

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

    $qry = "SELECT acc.prd_parent, acc.prd_id, acc.pck_quantity quantity, prd.prd_id, prd.prd_sku, prd.prd_name
    FROM ctt_products_packages AS acc 
    INNER JOIN ctt_products AS prd ON prd.prd_id = acc.prd_id
    WHERE acc.prd_parent = $prdId";

    return $this->db->query($qry);

}

// Listado de accesorios
public function listAccesorios($param) // Edna
{
    $sbc_id       = $this->db->real_escape_string($param['prdId']);
    
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

    $qry = "SELECT prd.prd_id, prd_name, prd_sku, 1 quantity
    FROM ctt_products as prd
        LEFT JOIN ctt_products_packages AS acc ON acc.prd_id = prd.prd_id 
    WHERE sbc_id=$prd_id AND prd_stock>0 GROUP BY prd.prd_id";
    return $this->db->query($qry);
}
public function updateQuantityProds($param)
{
    $prd_id            = $this->db->real_escape_string($param['prdId']);
    $prd_parent        = $this->db->real_escape_string($param['prdParent']);
    $prd_qty           = $this->db->real_escape_string($param['prdQty']);

    $qry =  "UPDATE ctt_products_packages SET pck_quantity = $prd_qty 
            WHERE prd_parent = $prd_parent AND prd_id = $prd_id;" ;
    
    $this->db->query($qry);

    return $prd_qty;
}    

// Registra el paquete o kit en la tabla de productos
public function saveAccesorioByProducto($param)
{
    $serId                      = $this->db->real_escape_string($param['serId']);
    $prdId                      = $this->db->real_escape_string($param['prdId']);
    $prd_parent_id              = $this->db->real_escape_string($param['parentId']);
    $prd_parent_Sku             = $this->db->real_escape_string($param['skuPrdPadre']);
    $sbc_id                     = $this->db->real_escape_string($param['lsbc_id']);
    

    $countId = 1;

    $qry1 = "UPDATE ctt_series set prd_id_acc=$prd_parent_id, ser_type_asigned = 'AF'
            where ser_id=$serId";
    $this->db->query($qry1);

    $qry2 = "UPDATE ctt_series set ser_type_asigned = 'PF'
            where ser_id=$prd_parent_id";
    $this->db->query($qry2);

    $qry3 = "UPDATE ctt_products SET prd_stock = prd_stock - 1 
    WHERE pd.prd_id = (SELECT prd_id FROM ctt_series AS sr WHERE ser_id = $prd_parent_id Limit 1)";
    $this->db->query($qry3);

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
        // $quantity                   = $this->db->real_escape_string($param['quantity']);
        $sbc_id                     = $this->db->real_escape_string($param['lsbc_id']);
        // $filas                      = $this->db->real_escape_string($param['filas']);

        $qry1 = "UPDATE ctt_series set ser_type_asigned = 'AV'
                WHERE prd_id = $prd_id AND prd_id_acc=0";
        $this->db->query($qry1);

        $qry2 = "UPDATE ctt_series SET ser_type_asigned = 'PV'
                WHERE prd_id = $prd_parent_id 
                AND ser_type_asigned != 'PF'";
        $this->db->query($qry2);
        
        $qry3 = "INSERT INTO ctt_products_packages ( prd_parent, pck_quantity, prd_id, prd_type_asigned)
                VALUES ($prd_parent_id,'1',$prd_id, 'AV')";
        $this->db->query($qry3);
        
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

        $qry1 =  "UPDATE ctt_series SET prd_id_acc= 0, ser_type_asigned = case 
                        when prd_id IN 
                            (SELECT pck.prd_id FROM ctt_products_packages AS pck 
                            WHERE pck.prd_type_asigned = 'AV') then 'AV'
                        ELSE 'PI'
                        
                    END
                    WHERE ser_id = $prd_id;" ;

        $this->db->query($qry1);

        $qry2 = "UPDATE ctt_series SET ser_type_asigned = case 
                        WHEN (SELECT sr.ser_id FROM ctt_series AS sr WHERE sr.prd_id_acc = $prd_parent) then 'PF'
                        when prd_id IN 
                            (SELECT pck.prd_id FROM ctt_products_packages AS pck 
                            WHERE pck.prd_type_asigned = 'AV') then 'PV'
                        
                        ELSE 'PI' 
                    END
                WHERE ser_id = $prd_parent;";
        $this->db->query($qry2);

        

        return $prd_id;
    }    


    public function deleteProduct($param)
    {
        $prd_id            = $this->db->real_escape_string($param['prdId']);
        $prd_parent        = $this->db->real_escape_string($param['prdParent']);

        $qry1 =  "DELETE FROM ctt_products_packages 
                WHERE prd_parent = $prd_parent AND prd_id = $prd_id;" ;
        $this->db->query($qry1);

        $qry2 = "UPDATE ctt_series SET ser_type_asigned = 'PI' 
                WHERE prd_id = $prd_id AND ser_type_asigned = 'AV' AND NOT EXISTS (
                    SELECT * FROM ctt_products_packages pck WHERE pck.prd_id = $prd_id);";
        $this->db->query($qry2);

        $qry3 = "UPDATE ctt_series SET ser_type_asigned = 'PI' 
                WHERE prd_id = $prd_parent AND ser_type_asigned = 'PV' 
                AND NOT EXISTS(SELECT pck.prd_id FROM ctt_products_packages AS pck 
                        WHERE pck.prd_parent = $prd_parent);";
        $this->db->query($qry3);

        return $prd_id;

    }
}