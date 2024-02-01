<?php

ini_set('display_errors', 'On');

require_once '../../../vendor/autoload.php';

//INICIO DE PROCESOS
$pjtId = $_GET['v'];
$usrId = $_GET['u'];
$uname = $_GET['n'];
$empid = $_GET['em'];
$type = $_GET['t'];
$desgloce = $_GET['d'];

$conkey = decodificar($_GET['h']) ;

$h = explode("|",$conkey);

$conn = new mysqli($h[0],$h[1],$h[2],$h[3]);
// 11-10-23
if ($type==1) {
    if ($desgloce==1) {
        $qry = "SELECT  pr.prd_name AS pjtcn_prod_name, dt.pjtdt_prod_sku as prd_sku,sr.ser_situation,
        ifnull(sr.ser_comments,'') AS ser_comments, ifnull(sr.ser_status,'1') as ser_status,
        cn.pjtcn_quantity,1 as quantity, case when (pr.prd_type_asigned != 'KP' 
            AND cn.pjtcn_prod_level = 'P') OR pr.prd_type_asigned='KP' then 
        (cn.pjtcn_prod_price * cn.pjtcn_days_cost) - 
        (cn.pjtcn_prod_price * cn.pjtcn_discount_base) * 
        cn.pjtcn_days_cost + 
        (cn.pjtcn_prod_price * cn.pjtcn_days_trip) - 
        ( (cn.pjtcn_prod_price * cn.pjtcn_discount_trip) * cn.pjtcn_days_trip ) + 
        (cn.pjtcn_prod_price * cn.pjtcn_days_test) - 
        (cn.pjtcn_prod_price * cn.pjtcn_discount_test) * cn.pjtcn_days_test ELSE 0 END  as costo,
        cn.ver_id as verId,
        case when (pr.prd_type_asigned != 'KP' AND cn.pjtcn_prod_level = 'P') OR pr.prd_type_asigned='KP' then ( (cn.pjtcn_insured * cn.pjtcn_prod_price)) *  cn.pjtcn_days_cost
            ELSE 0 END AS seguro, dt.pjtdt_id, pj.pjt_name, pj.pjt_number, cu.cus_id, cu.cus_name, cu.cus_email, cu.cus_phone
                , cu.cus_address, cu.cus_rfc, pj.pjt_number, pj.pjt_date_project, pj.pjt_date_start, pj.pjt_date_end
                , pj.pjt_how_required, pj.pjt_location, loc.loc_type_location, pt.pjttp_name,  CONCAT(DATE(pj.pjt_date_start),' - ',DATE(pj.pjt_date_end)) period
        FROM ctt_projects_detail AS dt
        INNER JOIN ctt_products AS pr ON pr.prd_id=dt.prd_id
        INNER JOIN ctt_projects_content AS cn ON cn.pjtvr_id = dt.pjtvr_id
        LEFT JOIN ctt_series AS sr ON sr.ser_id = dt.ser_id
        INNER JOIN ctt_projects AS pj ON pj.pjt_id = cn.pjt_id
        LEFT JOIN ctt_customers_owner AS co ON co.cuo_id = pj.cuo_id
        LEFT JOIN ctt_customers AS cu ON cu.cus_id = co.cus_id
        INNER JOIN ctt_projects_type AS pt ON pt.pjttp_id = pj.pjttp_id
        INNER JOIN ctt_location AS loc ON loc.loc_id = pj.loc_id
        WHERE cn.pjt_id = $pjtId AND dt.prd_type_asigned != 'AV' AND dt.prd_type_asigned != 'AF'";
    }else{
        $qry = "SELECT cn.pjtcn_prod_name, pr.prd_name, dt.pjtdt_prod_sku as prd_sku, case when sr.ser_situation != 'M' then '' ELSE 'M' END ser_situation,
        ifnull(sr.ser_comments,'') AS ser_comments, ifnull(sr.ser_status,'1') as ser_status,
        1 as quantity,
        (cn.pjtcn_prod_price * cn.pjtcn_days_cost) - 
        (cn.pjtcn_prod_price * cn.pjtcn_discount_base) * 
        cn.pjtcn_days_cost + 
        (cn.pjtcn_prod_price * cn.pjtcn_days_trip) - 
        ( (cn.pjtcn_prod_price * cn.pjtcn_discount_trip) * cn.pjtcn_days_trip ) + 
        (cn.pjtcn_prod_price * cn.pjtcn_days_test) - 
        (cn.pjtcn_prod_price * cn.pjtcn_discount_test) * cn.pjtcn_days_test as costo,
        cn.ver_id as verId,
        ( (cn.pjtcn_insured * cn.pjtcn_prod_price))*  cn.pjtcn_days_cost AS seguro,  dt.pjtdt_id, pj.pjt_name
    FROM ctt_projects_detail AS dt
    INNER JOIN ctt_products AS pr ON pr.prd_id=dt.prd_id
    INNER JOIN ctt_projects_content AS cn ON cn.pjtvr_id = dt.pjtvr_id
    LEFT JOIN ctt_series AS sr ON sr.ser_id = dt.ser_id
    INNER JOIN ctt_projects AS pj ON pj.pjt_id = cn.pjt_id
    WHERE cn.pjt_id = $pjtId AND cn.pjtcn_prod_level != 'K' AND dt.prd_type_asigned != 'AV' AND dt.prd_type_asigned != 'AF' 
    UNION SELECT cn.pjtcn_prod_name, cn.pjtcn_prod_name as prd_name, cn.pjtcn_prod_sku AS prd_sku,case when sr.ser_situation != 'M' then '' ELSE 'M' END ser_situation,
        ifnull(sr.ser_comments,'') AS ser_comments, ifnull(sr.ser_status,'1') as ser_status,
        cn.pjtcn_quantity as quantity,
        ((cn.pjtcn_prod_price * cn.pjtcn_days_cost) - 
        (cn.pjtcn_prod_price * cn.pjtcn_discount_base) * 
        cn.pjtcn_days_cost + 
        (cn.pjtcn_prod_price * cn.pjtcn_days_trip) - 
        ( (cn.pjtcn_prod_price * cn.pjtcn_discount_trip) * cn.pjtcn_days_trip ) + 
        (cn.pjtcn_prod_price * cn.pjtcn_days_test) - 
        (cn.pjtcn_prod_price * cn.pjtcn_discount_test) * cn.pjtcn_days_test) * cn.pjtcn_quantity as costo,
        cn.ver_id as verId,
        (( (cn.pjtcn_insured * cn.pjtcn_prod_price)) *  cn.pjtcn_days_cost)   AS seguro,  dt.pjtdt_id, pj.pjt_name
    FROM ctt_projects_detail AS dt
    INNER JOIN ctt_products AS pr ON pr.prd_id=dt.prd_id
    INNER JOIN ctt_projects_content AS cn ON cn.pjtvr_id = dt.pjtvr_id
    LEFT JOIN ctt_series AS sr ON sr.ser_id = dt.ser_id
    INNER JOIN ctt_projects AS pj ON pj.pjt_id = cn.pjt_id
    WHERE cn.pjt_id = $pjtId AND cn.pjtcn_prod_level = 'K' GROUP BY cn.pjtcn_id";
    }
    
}else{
    if ($desgloce == 1) {
        $qry = "SELECT  pr.prd_name AS pjtcn_prod_name, dt.pjtdt_prod_sku as prd_sku,sr.ser_situation,
            ifnull(sr.ser_comments,'') AS ser_comments, ifnull(sr.ser_status,'1') as ser_status,
            cn.pjtcn_quantity,1 as quantity, case when (pr.prd_type_asigned != 'KP' 
                AND cn.pjtcn_prod_level = 'P') OR pr.prd_type_asigned='KP' then 
            (cn.pjtcn_prod_price * cn.pjtcn_days_cost) - 
            (cn.pjtcn_prod_price * cn.pjtcn_discount_base) * 
            cn.pjtcn_days_cost + 
            (cn.pjtcn_prod_price * cn.pjtcn_days_trip) - 
            ( (cn.pjtcn_prod_price * cn.pjtcn_discount_trip) * cn.pjtcn_days_trip ) + 
            (cn.pjtcn_prod_price * cn.pjtcn_days_test) - 
            (cn.pjtcn_prod_price * cn.pjtcn_discount_test) * cn.pjtcn_days_test ELSE 0 END  as costo,
            cn.ver_id as verId,
            case when (pr.prd_type_asigned != 'KP' AND cn.pjtcn_prod_level = 'P') OR pr.prd_type_asigned='KP' then ( (cn.pjtcn_insured * cn.pjtcn_prod_price)) *  cn.pjtcn_days_cost
                ELSE 0 END AS seguro, dt.pjtdt_id, pjt.pjt_name, pjt.pjt_number, cu.cus_id, cu.cus_name, cu.cus_email, cu.cus_phone
                , cu.cus_address, cu.cus_rfc, pj.pjt_number, pj.pjt_date_project, pj.pjt_date_start, pj.pjt_date_end
                , pj.pjt_how_required, pj.pjt_location, loc.loc_type_location, pt.pjttp_name,  CONCAT(DATE(pj.pjt_date_start),' - ',DATE(pj.pjt_date_end)) period
        FROM ctt_projects_detail AS dt
        INNER JOIN ctt_products AS pr ON pr.prd_id=dt.prd_id
        INNER JOIN ctt_projects_content AS cn ON cn.pjtvr_id = dt.pjtvr_id
        LEFT JOIN ctt_series AS sr ON sr.ser_id = dt.ser_id
        INNER JOIN ctt_projects AS pj ON pj.pjt_id = cn.pjt_id
        INNER JOIN ctt_projects AS pjt  ON pjt.pjt_id = pj.pjt_parent
        LEFT JOIN ctt_customers_owner AS co ON co.cuo_id = pj.cuo_id
        LEFT JOIN ctt_customers AS cu ON cu.cus_id = co.cus_id
        INNER JOIN ctt_projects_type AS pt ON pt.pjttp_id = pj.pjttp_id
        INNER JOIN ctt_location AS loc ON loc.loc_id = pj.loc_id
        WHERE pj.pjt_parent = $pjtId AND dt.prd_type_asigned != 'AV' AND dt.prd_type_asigned != 'AF' and pj.pjt_status in(8,9)";
    }else{
        $qry = "SELECT cn.pjtcn_prod_name, pr.prd_name, dt.pjtdt_prod_sku as prd_sku, case when sr.ser_situation != 'M' then '' ELSE 'M' END ser_situation,
        ifnull(sr.ser_comments,'') AS ser_comments, ifnull(sr.ser_status,'1') as ser_status,
        1 as quantity,
        (cn.pjtcn_prod_price * cn.pjtcn_days_cost) - 
        (cn.pjtcn_prod_price * cn.pjtcn_discount_base) * 
        cn.pjtcn_days_cost + 
        (cn.pjtcn_prod_price * cn.pjtcn_days_trip) - 
        ( (cn.pjtcn_prod_price * cn.pjtcn_discount_trip) * cn.pjtcn_days_trip ) + 
        (cn.pjtcn_prod_price * cn.pjtcn_days_test) - 
        (cn.pjtcn_prod_price * cn.pjtcn_discount_test) * cn.pjtcn_days_test as costo,
        cn.ver_id as verId,
        ( (cn.pjtcn_insured * cn.pjtcn_prod_price))*  cn.pjtcn_days_cost AS seguro,  dt.pjtdt_id, pj.pjt_name
    FROM ctt_projects_detail AS dt
    INNER JOIN ctt_products AS pr ON pr.prd_id=dt.prd_id
    INNER JOIN ctt_projects_content AS cn ON cn.pjtvr_id = dt.pjtvr_id
    LEFT JOIN ctt_series AS sr ON sr.ser_id = dt.ser_id
    INNER JOIN ctt_projects AS pj ON pj.pjt_id = cn.pjt_id
    WHERE pj.pjt_parent = $pjtId AND cn.pjtcn_prod_level != 'K'  AND dt.prd_type_asigned != 'AV' AND dt.prd_type_asigned != 'AF' 
    UNION SELECT cn.pjtcn_prod_name, cn.pjtcn_prod_name as prd_name, cn.pjtcn_prod_sku AS prd_sku,case when sr.ser_situation != 'M' then '' ELSE 'M' END ser_situation,
        ifnull(sr.ser_comments,'') AS ser_comments, ifnull(sr.ser_status,'1') as ser_status,
        cn.pjtcn_quantity as quantity,
        ((cn.pjtcn_prod_price * cn.pjtcn_days_cost) - 
        (cn.pjtcn_prod_price * cn.pjtcn_discount_base) * 
        cn.pjtcn_days_cost + 
        (cn.pjtcn_prod_price * cn.pjtcn_days_trip) - 
        ( (cn.pjtcn_prod_price * cn.pjtcn_discount_trip) * cn.pjtcn_days_trip ) + 
        (cn.pjtcn_prod_price * cn.pjtcn_days_test) - 
        (cn.pjtcn_prod_price * cn.pjtcn_discount_test) * cn.pjtcn_days_test) * cn.pjtcn_quantity as costo,
        cn.ver_id as verId,
        (( (cn.pjtcn_insured * cn.pjtcn_prod_price)) *  cn.pjtcn_days_cost)   AS seguro,  dt.pjtdt_id, pj.pjt_name
    FROM ctt_projects_detail AS dt
    INNER JOIN ctt_products AS pr ON pr.prd_id=dt.prd_id
    INNER JOIN ctt_projects_content AS cn ON cn.pjtvr_id = dt.pjtvr_id
    LEFT JOIN ctt_series AS sr ON sr.ser_id = dt.ser_id
    INNER JOIN ctt_projects AS pj ON pj.pjt_id = cn.pjt_id
    WHERE pj.pjt_parent = $pjtId AND cn.pjtcn_prod_level = 'K' and pj.pjt_status in(8,9) GROUP BY cn.pjtcn_id";
    }
}


$res = $conn->query($qry);

$conn->close();

while($row = $res->fetch_assoc()){
    $items[] = $row;
}

date_default_timezone_set('America/Mexico_City');
$hoy=new DateTime();

// Cabezal de la página
$header = '
    <header>
        <div class="cornisa">
            <table class="table-main" border="0">
                <tr>
                    <td class="box-logo side-color">
                        <img class="img-logo" src="../../../app/assets/img/Logoctt_h.png"  style="width:37mm; height:13mm; margin: 3mm 2.5mm 0 2.5mm;"/>
                    </td>
                    <td class="name-report bline" style="witdh:77mm;  font-size: 13pt; text-align: right; padding-right: 30px; padding-top: 25px">
                    <p>
                        <span class="number">Proyecto: '. $items[0]['pjt_name'] . '   #' . $items[0]['pjt_number'] .'</span>
                        <br><span style=" font-size: 8pt; color: #191970">Nombre Responsable: '. $uname .'</span>
                    </p>
                    </td>
                </tr>
            </table>
        </div>
    </header>';

$equipoBase = '1';               

$html = '
    <section>
        <div class="container">
        <table class="table-data bline-d tline">
        <tr>
            <td class="rline half">
                <!-- Start datos del cliente -->
                <table class="table-data">
                    <tr>
                        <td class="concept">Cliente:</td>
                        <td class="data">'. $items[0]['cus_name']  .'</td>
                    </tr>
                    <tr>
                        <td class="concept">Domicilio:</td>
                        <td class="data">'.  $items[0]['cus_address'] .'</td>
                    </tr>
                    <tr>
                        <td class="concept">Quien Solicita:</td>
                        <td class="data">'. $items[0]['pjt_how_required'] .'</td>
                     </tr>
                    <tr>
                        <td class="concept">Correo Electrónico:</td>
                        <td class="data">'. $items[0]['cus_email'] .'</td>
                    </tr>
                    <tr>
                        <td class="concept">Teléfono:</td>
                        <td class="data">'. $items[0]['cus_phone'] .'</td>
                    </tr>
                   
                    <tr>
                        <td class="concept">Analista CTT:</td>
                        <td class="data">'. $uname .'</td>
                    </tr>
                </table>
                <!-- End datos del cliente -->
            </td>
            <td class="half">
                <!-- Start Datos del projecto -->
                <table class="table-data">
                
                    <tr>
                        <td class="concept">Fecha Cotización:</td>
                        <td class="data">'. $hoy->format('d/m/Y') .'</td>
                    </tr>
                    <tr>
                        <td class="concept">Ciudad:</td>
                        <td class="data">'. $items[0]['pjt_location'] .'</td>
                    </tr>
                    <!-- <tr>
                        <td class="concept">Tipo de Locación:</td>
                        <td class="data">'. $items[0]['loc_type_location'] .'</td>
                    </tr> -->
                    <tr>
                        <td class="concept">Tipo de proyecto:</td>
                        <td class="data">'. $items[0]['pjttp_name'] .'</td>
                    </tr>
                    <tr>
                        <td class="concept">Fechas de Proyecto:</td>
                        <td class="data">'. $items[0]['period'] .'</td>
                    </tr>
                    ';
                    if ($items[0]['pjt_trip_go']) {
                        $html .='
                            <tr>
                                <td class="concept">Dias de Viaje:</td>
                                <td class="data">'. $items[0]['pjt_trip_go'] .'</td>
                            </tr>';
                    }
                    if ($items[0]['pjt_test_tecnic']) {
                        $html .='
                        <tr>
                            <td class="concept">Dias de Pruebas:</td>
                            <td class="data">'. $items[0]['pjt_test_tecnic'] .'</td>
                        </tr>';
                    }
                    
                    $html .='
                    <tr>
                        <td class="concept">&nbsp;</td>
                        <td class="data">&nbsp;</td>
                    </tr>
                    
                </table>
                <!-- End Datos del projecto -->
            </td>
        </tr>
    </table>
    <!-- End Datos de identificación  -->
    <table class="table-data bline tline" style="text-align: center">
        <tr>
            <td>
                <p class="tit-rep" style="font-size: 15pt; font-variant: small-caps; font-weight: bold; text-align: center">
                Impresion de contenido del Proyecto
                </p>
            </td>
     
        </tr>
        <!--<tr>
            <td class="half">
                <table class="table-data">
                    <tr>
                        <td class="concept"><strong>Nombre Responsable:</strong></td>
                        <td class="data"><strong>'. $uname .'</strong></td>
                    </tr>
                    <tr>
                        <td class="concept">&nbsp;</td>
                        <td class="data">&nbsp;</td>
                    </tr> 
                </table>
                  
            </td>
        </tr> -->
        
    </table>
    <!-- End Datos de identificación  -->
';

/* Tabla de equipo base -------------------------  */
        $html .= '
                    <!-- Start Tabla de costo base  -->
                    <h2>Lista de equipo</h2>
                    <table autosize="1" style="page-break-inside:auto" class="table-data bline">
                        <thead>
                            <tr>
                                <th class="tit-figure prod">SKU</th>
                                <th class="tit-figure amou">Producto</th>
                                <th class="tit-figure qnty">Cantidad</th>
                                <th class="tit-figure amou">Status</th>
                                <th class="tit-figure amou">Precio</th>
                            </tr>
                        </thead>
                        <tbody>';

                        for ($i = 0; $i<count($items); $i++){
                            $section     = 1;

                            if ($section == '1') {
                                $price = $items[$i]['costo'] + $items[$i]['seguro'];
        $html .= '
                            <tr>
                                <td class="dat-figure prod" style="font-size: 1.2em;">' . $items[$i]['prd_sku'] . '</td>
                                <td class="dat-figure sku">'   .$items[$i]['pjtcn_prod_name']   . '</td>
                                <td class="dat-figure sku">'   . $items[$i]['quantity']   . '</td>
                                <td class="dat-figure prod">'. $items[$i]['ser_status']             .' </td>
                                <td class="dat-figure sku">'   .  $price  . '</td>
                            </tr> '; // 11-10-23
                            }
                        }
        $html .= '
                        <tr>
                            <td class="tot-figure amou" ></td>
                            <td class="tot-figure amou"></td>
                            <td class="tot-figure amou"></td>
                            <td class="tot-figure amou"></td>
                        </tr>
                    </tbody>
                </table>
                <!-- End Tabla de costo base  -->';

    
/* Tabla de equipo base -------------------------  */

$html .= '
<!-- Start Tabla de terminos  -->
<div style="height:20px;"></div>
<section>
<div class="container name-report bline-d">
    <table class="bline-d" autosize="1"  >
        <tbody>
            <tr  >
                <td style="width:25mm; height:60px; margin: 3mm 2.5mm 0 2.5mm;"><span>&nbsp; </span> </td>
                <td style="width:25mm; height:60px; margin: 3mm 2.5mm 0 2.5mm;"><span>&nbsp; </span> </td>
                <td style="width:25mm; height:60px; margin: 3mm 2.5mm 0 2.5mm;"><span>&nbsp; </span> </td>
            </tr>
            <tr class="tline-d" style="font-size: 1.1em; text-align: center">
                <td class="prod" > Responsable Cliente </td>
                <td class="prod" >Firmas </td>
                <td class="prod" >Responsable CTT</td>
            </tr>
        </tbody>
    </table>
</div>
</section>';
/* Tabla firmas -------------------------  */


// Pie de pagina
$foot = '
    <footer>
        <table class="table-address">
            <tr>
                <td class="addData">Av Guadalupe I. Ramírez 763,<br />Tepepan Xochimilco, 16020, CDMX</td>
                <td class="addIcon addColor02"><img class="img-logo" src="../../../app/assets/img/icon-location.png" style="width:3mm; height:auto;" /></td>
                <td class="addData">ventas@cttrentals.com<br />presupuestos@cttrentals.com<br />proyectos@cttrentals.com</td>
                <td class="addIcon addColor03"><img class="img-logo" src="../../../app/assets/img/icon-email.png"  style="width:3mm; height:auto;"/></td>
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
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 25,
    'margin_bottom' => 30,
    'margin_header' => 5,
    'margin_footer' => 10, 
    'orientation' => 'P'
    ]);

$mpdf->shrink_tables_to_fit = 1;
$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter($foot);
$mpdf->WriteHTML($css,\Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);
$mpdf->Output(
    "Salida_Store_Content.pdf",
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