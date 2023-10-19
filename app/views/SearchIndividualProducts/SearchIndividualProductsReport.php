<?php

ini_set('display_errors', 'On');

require_once '../../../vendor/autoload.php';

//INICIO DE PROCESOS
$prdId = $_GET['p'];
$usrId = $_GET['u'];
$uname = $_GET['n'];
/*
$totalBase = 0;
$totalTrip = 0;
$totalTest = 0;
$totalInsr = 0;         //      Total del seguro
$totalMain = 0;
$totalInsrGral = 0;

$equipoBase = 0;
$equipoExtra = 0;
$equipoDias = 0;
$equipoSubarrendo = 0;*/

$conkey = decodificar($_GET['h']) ;

$h = explode("|",$conkey);


$conn = new mysqli($h[0],$h[1],$h[2],$h[3]);
//echo $h[2];


$qry = "SELECT pd.prd_id, pd.prd_sku, pd.prd_name, pd.prd_price, 
pd.prd_visibility, ser.ser_id, ser.ser_sku, ser.ser_serial_number, 
ser.ser_situation, ser.ser_date_registry, ser.ser_date_down, IFNULL(pjp.pjtpd_day_start,'') AS 
                         pjtpd_day_start, 
                          IFNULL(pjp.pjtpd_day_end,'')
                        pjtpd_day_end, pj.pjt_name, pj.pjt_number FROM ctt_products AS pd 
INNER JOIN ctt_series AS ser ON ser.prd_id = pd.prd_id
Left JOIN ctt_projects_detail AS pjd ON pjd.ser_id = ser.ser_id
Left JOIN ctt_projects_content AS pjc ON pjc.pjtvr_id = pjd.pjtvr_id
Left JOIN ctt_projects_periods AS pjp ON pjp.pjtdt_id = pjd.pjtdt_id
Left  JOIN ctt_projects AS pj ON pj.pjt_id = pjc.pjt_id WHERE pd.prd_id = $prdId ORDER BY ser.ser_serial_number;";

$res = $conn->query($qry);
//$conn->close();


while($row = $res->fetch_assoc()){
    $items[] = $row;
}
$conn->close();


// Cabezal de la página
$header = '
    <header>
        <div class="cornisa">
            <table class="table-main" border="0">
                <tr>
                    <td class="box-logo side-color">
                        <img class="img-logo" src="../../../app/assets/img/Logoctt_h.png"  style="width:42mm; height:16mm; margin: 3mm 2.5mm 0 2.5mm;"/>
                    </td>
                    <td class="name-report bline" style="witdh:77mm;  font-size: 13pt; text-align: right; padding-right: 30px; padding-top: 25px">
                    <p>
                        <span class="number">Producto: '. $items[0]['prd_name'] . '   #' . $items[0]['prd_sku'] .'</span>
                        <br><span class="date">'.'</span>
                    </p>
                    </td>
                </tr>
            </table>
           
        </div>
       
    </header>';

/* Tabla de equipo base -------------------------  */
    
        $html = '
                    
                    <table autosize="1" style="page-break-inside:void" class="table-data bline">
                            <thead>
                                <tr>
                                    
                                    <th style="width:  70px">SKU-Serie</th>
                                    <th style="width:  100px">Serie</th>
                                    <th style="width: 200px">Proyecto</th>
                                    <th style="width:  70px">Fecha Inicio</th>
                                    <th style="width:  70px">Fecha Fin</th>
                                    <th style="width: 40px">Estatus</th>
                                </tr>
                            </thead>
                        <tbody>';
                        /*
                        $discountBaseTotal  = 0;
                        $amountBaseTotal    = 0;
                        $discountTripTotal   = 0;
                        $amountTripTotal    = 0;
                        $amountGralTotal    = 0;*/

                        for ($i = 0; $i<count($items); $i++){
                           

        $html .= '
                            <tr>
                                
                                <td class="dat-figure pric">' . $items[$i]['ser_sku']            . '</td>
                                <td class="dat-figure qnty">' . $items[$i]['ser_serial_number']                                    . '</td>
                                <td class="dat-figure days">' . $items[$i]['pjt_name']                                    . '</td>
                                <td class="dat-figure disc">' . $items[$i]['pjtpd_day_start']   . '</td>
                                <td class="dat-figure amou">' . $items[$i]['pjtpd_day_end']       . '</td>
                                <td class="dat-figure amou">' . $items[$i]['ser_situation']       . '</td>
                            </tr>
                            ';
                            
                            

                        }
                    
        $html .= '
                           
                        </tr>
                    </tbody>
                </table>
                <div style="height:30px;"></div>
                <!-- End Tabla de costo base  -->';

// Pie de pagina
// <td class="td-foot foot-rept" width="25%" style="text-align: right">Elaboró: '. $uname . '</td>

$foot = '
    <footer>
        <table class="table-footer">
            <tr>
                <td class="side-color"></td>
                <td>
                    <table width="100%">
                        <tr>
                            <td class="td-foot foot-date" width="25%"></td>
                            <td class="td-foot foot-page" width="25%" align="center">{PAGENO}/{nbpg}</td>
                            <td class="td-foot foot-rept" width="25%" style="text-align: right"></td>
                        </tr>
                    </table>
                </td>
            </tr> 
        </table>
        
        <table class="table-address">
            <tr>
                <td class="addData">
                    reservaciones@cttrentals.com, 
                
                </td>
                <td class="addData">
                    presupuestos@cttrentals.com,
                </td>
                <td class="addData">
                
                    proyectos@cttrentals.com,
                </td>
                <td class="addData">
                    cotizaciones@cttrentals.com.
                </td>
            </tr>

            
        </table>
        <table class="table-address">
            
            <tr>
                <td class="addData">Av Guadalupe I. Ramírez 763, Tepepan Xochimilco, 16020, CDMX</td>
                
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
    'margin_bottom' => 35,
    'margin_header' => 5,
    'margin_footer' => 4, 
    'orientation' => 'P'
    ]);

$mpdf->shrink_tables_to_fit = 1;
$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter($foot);
$mpdf->AddPage();
$mpdf->WriteHTML($css,\Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);
$mpdf->Output(
    "Cotizacion-". $items[0]['ver_code'].".pdf",
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