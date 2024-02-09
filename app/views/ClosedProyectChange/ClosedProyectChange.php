<?php 
	defined('BASEPATH') or exit('No se permite acceso directo'); 
	require ROOT . FOLDER_PATH . "/app/assets/header.php";
?>

<header>
	<?php require ROOT . FOLDER_PATH . "/app/assets/menu.php"; ?>
</header>

<!-- CUERPO DE LA PAGINA -->
<div class="container-fluid">
    <div class="contenido ">
            <div class="block_01" style="width: 100%">
                <div class="row" >
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 blocks" style="width: 100%">
                        <span class="titleSection">Datos Generales del Proyecto</span>
                        <table class="table_information form-floating">
                            <tr>
                                <td class="formSales">
                                </td>
                            </tr>
                            <tr>
                                <td class="formSales" >
                                    <form id="formSales" >
                                        <div class="form_group">
                                            <label for="lstProject">Nombre de proyecto:</label> 
                                            <select id="lstProject" name="lstProject" >
                                                <option value="">Selecciona...</option>
                                            </select>
                                        </div>

                                        <div class="form_group">
                                            <label for="txtCustomer">Nombre Cliente:</label> 
                                            <input id="txtCustomer" name="txtCustomer" type="text" class="textbox " disabled>
                                        </div>

                                        <div class="form_group">
                                            <label for="txtDateStar">Fecha Inicio Proyecto:</label> 
                                            <input id="txtDateStar" name="txtDateStar" type="text" class="textbox " disabled>
                                        </div>

                                        <div class="form_group">
                                            <label for="txtDateEnd">Fecha final Proyecto:</label> 
                                            <input id="txtDateEnd" name="txtDateEnd" type="text" class="textbox " disabled>
                                        </div>

                                        <div class="form_group">
                                            <label for="txtRepresen">Representante Legal:</label> 
                                            <input id="txtRepresen" name="txtRepresen" type="text" class="textbox " disabled>
                                        </div>

                                        <div class="form_group">
                                            <label for="txtAdress">Domicilio:</label> 
                                            <input id="txtAdress" name="txtAdress" type="text" class="textbox " disabled>
                                        </div>

                                        <div class="form_group">
                                            <label for="txtRespProg">Analista Programacion:</label> 
                                            <input id="txtRespProg" name="txtRespProg" type="text" class="textbox " disabled>
                                        </div>
                                        
                                        <div class="form_group hide">
                                            <label for="txtInvoice">Num Factura:</label> 
                                            <input id="txtInvoice" name="txtInvoice" type="text" class="textbox">
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 blocks">
                        <div class="block_01-02">
                            <div class="menu_control"></div>
                            <div id="tbl_dynamic" class="tbl_dynamic"></div>
                        </div>   
                    </div>
                </div>
            </div>
       
    </div>
</div>


<!-- Start Ventana modal AGREGA O MODIFICA PRODUCTO -->
<div class="overlay_background overlay_hide"id="newValuesModal" style="width: 65%; left:25%;" >
        <div class="overlay_modal">
            <div class="overlay_closer"><span class="title"></span><span class="btn_close">Cerrar</span></div>
            <div class="formButtons">
                <button type="button" class="btn btn-sm btn-primary" id="btn_save">Guardar</button>
            </div>
            <div class="formContent">
                <table id="tblNewValues">
                    <tr >
                        <td class="concept"> Nombre del Proyecto:</td>
                        <!-- <td class=""> -->
                            <!--         <div class="row list-finder pos2 "> -->
                            <td class="data">
                                <input id="txtProject" type="text" class="textbox" style="width:250px;" autocomplete="off" disabled>
                                <span class="fail_note hide"><i class="fas fa-arrow-left"></i> Campo requerido</span>
                                <span class="intructions">Nombre del proyecto</span>
                            </td>
                    </tr>

                    <tr>
                        <td class="concept"><span class="reqsign"></span> Total del Proyecto:</td>
                        <td class="data">
                            <input type="text" id="txtMontoProy" name="txtMontoProy" class="textbox" style="width:150px;" autocomplete="off" disabled>
                            <span class="fail_note hide"><i class="fas fa-arrow-left"></i> Campo requerido</span>
                            <span class="intructions">monto total del proyecto</span>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="concept"><span class="reqsign"></span> Total Mantenimiento:</td>
                        <td class="data">
                            <input type="text" id="txtMontoMant" name="txtMontoMant" class="textbox" style="width:150px" autocomplete="off">
                            <span class="fail_note hide"><i class="fas fa-arrow-left"></i> Campo requerido</span>
                            <span class="intructions">monto total del mantenimiento</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="concept"><span class="reqsign"></span>Total Expendables:</td>
                        <td class="data">
                            <input type="text" id="txtMontoexpe" name="txtMontoexpe" class="textbox" style="width:150px" autocomplete="off">
                            <span class="fail_note hide"></span>
                            <span class="intructions">monto total de los expendables</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="concept"><span class="reqsign"></span>Total Diesel:</td>
                        <td class="data">
                            <input type="text" id="txtMontoDies" name="txtMontoDies" class="textbox" style="width:150px" autocomplete="off">
                            <span class="fail_note hide"></span>
                            <span class="intructions">monto total del diesel</span>
                        </td>
                    </tr>

                    <tr>
                        <td class="concept"><span class="reqsign"></span>Monto Descuento:</td>
                        <td class="data">
                            <input type="text" id="txtMontoDesc" name="txtMontoDesc" class="textbox" style="width:150px;" autocomplete="off">
                            <span class="fail_note hide"></span>
                            <span class="intructions">monto total del descuento aplicado previo</span>
                        </td>
                    </tr>

                    <tr>
                        <td class="concept"><span class="reqsign">**</span>Total Documento:</td>
                        <td class="data">
                            <input type="text" id="txtMontoTotal" name="txtMontoTotal" class="textbox" style="width:150px; font-weight: bold; font-size: medium" disabled>
                            <span class="fail_note hide"></span>
                            <span class="intructions">Montol total del documento</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
</div>

<div class="overlay_background overlay_hide" id="addSegmentModal" style="width: 85%; left:15%; background-color: rgba(255, 255, 255, 0); z-index: 500;">
    <div class="overlay_modal" style="z-index: 50;">
        
        <div class="overlay_closer"><span class="title"></span><span class="btn_close">Cerrar</span></div>
        <div class="" style="position: absolute; top: 10px; height: 60px; padding: 10px;">
            <button type="button" class="btn btn-sm btn-primary" id="btn_saveSegment">Guardar</button>
        </div>


        <div class="container-fluid" >
            <div class="contenido">
                <div class="row">
                    <div class="" style="width: 100%; height: 120vh; padding: 1% 10%;"> <!-- overflow: auto; -->
                        <div class="row">
                            <!-- <button class="bn btn-ok" id="addRowTbl">Agregar</button> -->
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="background-color: #ffffff; border: 2px solid #eeeeee; border-radius: 10px;">
                                <table id="" class="table_information form-floating ">
                                    <tr>
                                        <td class="concept"><span class="reqsign"></span>Monto total del documento: </td>
                                        <td>
                                            <input type="text" id="txtMontoTotSeg" name="txtMontoTotSeg" class="form-control form-control-sm" style="width:150px" autocomplete="off"><br>
                                            <span class="textAlert"></span>
                                        </td>
                                        <td class="concept"><span class="reqsign"></span>Fecha: </td>
                                        <td>
                                            <input type="text" id="txtPeriodPayed"  name="txtPeriodPayed" class="form-control" style="width:150px" autocomplete="off">
                                            <td><i class="fas fa-calendar-alt icoTextBox" id="calendar"></i></td>
                                        </td>
                                    </tr>
                                    <div style="height:10px;"></div> <!-- Agregar un espacio -->
                                    <tr>   
                             
                                        <td class="concept"><span class="reqsign"></span>Frecuencia de pagos</td>
                                        <td>
                                            <select id="txtFrecuency" name="txtFrecuency" class="form-control form-control-sm" style="width:100px">
                                                <option value="1">Semanal</option>
                                                <option value="2">Mensual</option>
                                                <option value="3">Trimestral</option>
                                                <option value="4">Anual</option>
                                            </select>
                                            <!-- <span class="textAlert"><i class="fas fa-exclamation-triangle"></i> Debes seleccionar el estado</span> -->
                                        </td>
                                        <td class="concept"><span class="reqsign"></span>Segmentacion de Pagos</td>
                                        <td>
                                            <select id="txtSegment" name="txtSegment" class="form-control form-control-sm" style="width:100px">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                            </select>
                                            <!-- <span class="textAlert"><i class="fas fa-exclamation-triangle"></i> Debes seleccionar el estado</span> -->
                                        </td>
                        
                                    </tr>
                                    <tr style="text-align:center;">
                                        <td colspan=4>
                                            <button class="bn btn-ok" id="addButtonSegm">Agregar</button>
                                        </td>
                                    </tr>
                                
                                </table>
                            </div>
                        </div>
                        <div style="height:10px;"></div>
                        <div class="row mt-2" >
                            <table class="display compact nowrap" id="listTable"style = "width: 95%" >
                                <thead>
                                    <tr>
                                        <th style = "width: 20px"></th>
                                        <th style = "width: 50px">Numero de Pago</th>
                                        <th style = "width: 50px">Frecuencia</th>
                                        <th style = "width: 50px">Monto</th>
                                        <th style = "width: 50px">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Start Lista de productos -->
    <div class="box_list_products" id="Products" >
        <div class="sel_product" contenteditable="true"></div>
        <div class="list_products">
            <ul></ul>
        </div>
    </div>
    <!-- End Lista de productos -->

    <!-- Boton para confirmar salida de productos -->
    <div class="modal fade" id="starClosure" tabindex="-1" aria-labelledby="BorrarPerfilLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                <div class="modal-header ">
                </div>
                <div class="modal-body" style="padding: 0px !important;">
                <div class="row">
                    <input type="hidden" class="form-control" id="txtIdClosure" aria-describedby="basic-addon3">
                    <div class="col-12 text-center">
                        <span class="modal-title text-center" style="font-size: 1.2rem;" id="BorrarPerfilLabel">¿Seguro de agregar estos nuevos datos?</span>
                    </div>
                </div>
                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-danger" id="btnClosure">Si</button>
                    </div>
                </div>
        </div>
    </div>

    <!-- Modal para imprimir folio de salida -->
    <div class="modal fade" id="MoveFolioModal" tabindex="-1" aria-labelledby="BorrarPerfilLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                <div class="modal-header ">
                </div>
                <div class="modal-body" style="padding: 0px !important;">

                <div class="row">
                    <div class="col-12 text-center">
                        <span class="modal-title text-center" style="font-size: 1.2rem;" id="BorrarPerfilLabel">Folio: <h3 class="resFolio">000000000000</h3></span>
                    </div>
                </div>

                </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-primary" id="btnPrintReport">Imprimir</button> -->
                        <button type="button" class="btn btn-secondary" id="btnHideModal">Cerrar</button>
                    </div>
                </div>
        </div>
    </div>

    <!-- Fondo obscuro -->
    <div class="invoice__modalBackgound"></div>

    <!-- loading -->
    <div class="invoice__loading modalLoading">
            <div class="box_loading">
                <p class="text_loading">
                    Registrando Salida de Proyecto<br>
                    <i class="fas fa-spinner spin"></i> 
                    </p>
                <p>Se estan actualizando los registros del proyecto, este proceso puede tardar varios minutos</p>
            </div>
        </div>
    <!-- end -->

<script src="<?=  PATH_ASSETS . 'lib/functions.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/dataTable/datatables.min.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_VIEWS . 'ClosedProyectChange/ClosedProyectChange.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/jquery-ui.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>