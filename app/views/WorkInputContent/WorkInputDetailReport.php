<?php

ini_set('display_errors', 'On');

require_once '../../../vendor/autoload.php';

//INICIO DE PROCESOS
$prdId = $_GET['v'];
$usrId = $_GET['u'];
$uname = $_GET['n'];
$empid = $_GET['em'];
$numProject =  $_GET['nump'];
$nameProject =$_GET['np'];

$conkey = decodificar($_GET['h']) ;
date_default_timezone_set('America/Mexico_City');

$h = explode("|",$conkey);
// 11-10-23
$conn = new mysqli($h[0],$h[1],$h[2],$h[3]);
if ($empid == '1' || $empid == '2'){
$qry = "SELECT pjtcn_prod_name, prd.prd_name, pdt.pjtdt_prod_sku, sr.ser_serial_number, pj.pjt_number, 
        pj.pjt_name, pj.pjt_date_start, '1' AS dt_cantidad, sr.ser_no_econo, sr.ser_comments, cu.cus_id, cu.cus_name, cu.cus_email, cu.cus_phone
                , cu.cus_address, cu.cus_rfc, pj.pjt_number, pj.pjt_date_project, pj.pjt_date_start, pj.pjt_date_end
                , pj.pjt_how_required, pj.pjt_location, loc.loc_type_location, pt.pjttp_name, 
                CONCAT(DATE(pj.pjt_date_start),' - ',DATE(pj.pjt_date_end)) period
        FROM ctt_projects_content AS pcn
        INNER JOIN ctt_projects_detail AS pdt ON pcn.pjtvr_id=pdt.pjtvr_id
        INNER JOIN ctt_series AS sr ON sr.ser_id=pdt.ser_id
        INNER JOIN ctt_projects AS pj ON pj.pjt_id=pcn.pjt_id
        INNER JOIN ctt_products AS prd ON prd.prd_id=pdt.prd_id
        LEFT JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
        LEFT JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
        INNER JOIN ctt_projects_type AS pt ON pt.pjttp_id = pj.pjttp_id
        INNER JOIN ctt_location AS loc ON loc.loc_id = pj.loc_id
        WHERE pcn.pjt_id=$prdId AND substr(pdt.pjtdt_prod_sku,11,1)!='A' 
        ORDER BY pdt.pjtdt_prod_sku;";
} else{
$qry = "SELECT pjtcn_prod_name,prd.prd_name, pjtdt_prod_sku, pjtcn_quantity, 
        pjc.pjt_id, '1' AS dt_cantidad, pjtcn_order, pjc.pjtcn_section,
        sr.ser_serial_number,sr.ser_no_econo,
            pj.pjt_number, pj.pjt_name, pj.pjt_date_start, sr.ser_comments, cu.cus_id, cu.cus_name, cu.cus_email, cu.cus_phone
                , cu.cus_address, cu.cus_rfc, pj.pjt_number, pj.pjt_date_project, pj.pjt_date_start, pj.pjt_date_end
                , pj.pjt_how_required, pj.pjt_location, loc.loc_type_location, pt.pjttp_name, 
                CONCAT(DATE(pj.pjt_date_start),' - ',DATE(pj.pjt_date_end)) period
        FROM ctt_projects_content AS pjc
        INNER JOIN ctt_projects_detail AS pdt ON pdt.pjtvr_id=pjc.pjtvr_id
        INNER JOIN ctt_series AS sr ON sr.ser_id=pdt.ser_id
        INNER JOIN ctt_projects AS pj ON pj.pjt_id=pjc.pjt_id
        INNER JOIN ctt_categories AS cat ON lpad(cat.cat_id,2,'0')=SUBSTR(pjc.pjtcn_prod_sku,1,2)
        INNER JOIN ctt_employees AS em ON em.are_id=cat.are_id
        INNER JOIN ctt_products AS prd ON prd.prd_id=pdt.prd_id
        LEFT JOIN ctt_customers_owner AS cuo ON cuo.cuo_id = pj.cuo_id
        LEFT JOIN ctt_customers AS cu ON cu.cus_id = cuo.cus_id
        INNER JOIN ctt_projects_type AS pt ON pt.pjttp_id = pj.pjttp_id
        INNER JOIN ctt_location AS loc ON loc.loc_id = pj.loc_id
        WHERE pjc.pjt_id=$prdId AND em.emp_id=$empid AND substr(pdt.pjtdt_prod_sku,11,1)!='A'
        ORDER BY pjc.pjtcn_section, pjc.pjtcn_prod_sku ASC;";
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
                        <span class="number">Proyecto: '. $nameProject. '   #' . $numProject .'</span>
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
            <div style="height:80px;"></div>
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
                        Impresion de detalles de entrada de proyecto

                        </p>
                    </td>
            
                </tr>
            </table>
            <!-- End Datos de identificación  -->
';

/* Tabla de equipo base -------------------------  */
if ($equipoBase == '1'){
        $html .= '
                    <!-- Start Tabla de costo base  -->
                    <h2>Lista de equipo</h2>
                    <table autosize="1" style="page-break-inside:void" class="table-data bline-d">
                        <thead>
                            <tr>
                                <th class="tit-figure prod">Producto</th>
                                <th class="tit-figure amou">Sku</th>
                                <th class="tit-figure qnty">Cant.</th>
                                <th class="tit-figure amou">No. <br>Economico</th>
                                <th class="tit-figure prod">Notas</th>
                            </tr>
                        </thead>
                        <tbody>';

                        for ($i = 0; $i<count($items); $i++){
                            $section     = 1;

                            if ($section == '1') {
                                $prodname     = $items[$i]['prd_name'] ;  //  ------------
                                $prodsku      = $items[$i]['pjtdt_prod_sku'] ; //  ------------
                                $quantity     = $items[$i]['dt_cantidad'] ;  //  ------------
                                $sernum       = $items[$i]['ser_no_econo'] ; //  -------- 
                                $comment       = $items[$i]['ser_comments'] ; // 11-10-23
        $html .= '
                            <tr>
                                <td class="dat-figure supply">' . $prodname . '</td>
                                <td class="dat-figure sku">' . $prodsku  . '</td>
                                <td class="dat-figure qnty">' . $quantity  . '</td>
                                <td class="dat-figure days">' . $sernum . '</td>
                                <td class="dat-figure prod"> '. $comment               .'</td>
                            </tr>
                            '; // 11-10-23
                            }
                        }
        $html .= '
                        <tr>
                            <td class="tot-figure amou" ></td>
                            <td class="tot-figure amou"></td>
                            <td class="tot-figure amou"></td>
                            <td class="tot-figure amou"></td>
                            <td class="tot-figure amou"></td>
                        </tr>
                    </tbody>
                </table>
                <!-- End Tabla de costo base  -->';
}
    
/* Tabla de equipo base -------------------------  */

// Pie de pagina
// Pie de pagina
$foot = '
    <footer>
        <table class="table-footer">
            <tr>
                <td class="side-color"></td>
                <td>
                    <table width="100%">
                        <tr>
                            <td class="td-foot foot-date" width="25%">{DATE F j, Y, g:i a}</td>
                            <td class="td-foot foot-page" width="25%" align="center">{PAGENO}/{nbpg}</td>
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
                <td class="addData">reservaciones@cttrentals.com<br />presupuestos@cttrentals.com<br />cotizaciones@cttrentals.com</td>
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
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 10,
    'margin_bottom' => 30,
    'margin_header' => 10,
    'margin_footer' => 5, 
    'orientation' => 'L'
    ]);

$mpdf->shrink_tables_to_fit = 1;
$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter($foot);
$mpdf->WriteHTML($css,\Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);
$mpdf->Output(
    "Salida_Store_detail.pdf",
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