<?php 
    defined('BASEPATH') or exit('No se permite acceso directo'); 
    require ROOT . FOLDER_PATH . "/app/assets/header.php";
?>
<header>
    <?php require ROOT . FOLDER_PATH . "/app/assets/menu.php"; ?>
</header>
<!-- CUERPO DE LA PAGINA -->
<div class="container-fluid">
    <div class="contenido">
            
        <div class="row mvst_group">
            <div class="mvst_panel" style="background-color: #B0C4DE">
                <div class="form-group">
                <h1>Segmentacion de un pago</h1>
                    <!-- Almacen posición 1 -->
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                            <select id="txtProyects" class="form-select form-select-sm required"><option value="0" selected>Selecciona un proyecto</option></select>
                            <label for="txtProyects" class="form-label">Proyecto:</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 col-lg-8 col-xl-8 mb-2 form-floating">
                        <input id="txtCostProy" type="text" class="form-control form-control-sm text-left number" >
                            <label for="txtCostProy">Monto del Proyecto</label>
                        </div>
                       
                    </div>
                    <div style="height:10px;"></div> <!-- Agregar un espacio -->
                    <h1></h1>
                    <div class="row pos0 hide-items">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                            <select id="txtFrecuency" class="form-select form-select-sm required">
                                <option value="0" data-content="||||" selected>Selecciona</option>
                                <option value="1">Semanal</option>
                                <option value="2">Quincenal</option>
                                <option value="3">Mensual</option>
                                <option value="4">Personalizada</option>
                            </select>
                            <label for="txtFrecuency">Frecuencia</label>
                        </div>
                    </div>

                    <div class="row pos1 hide-items">
                        <div class="col-md-6 col-lg-6 col-xl-6 mb-2 form-floating">
                            <input id="txtSegments" type="text" class="form-control form-control-sm text-center number required"  data-mesage="Debes agregar la cantidad de productos" value=1>
                            <label for="txtSegments">No. Segmentaciones</label>
                        </div>
                       
                    </div>
                    <div class="row pos3 hide-items">
                        <div ><!--  class="col-md-8 col-lg-8 col-xl-8 mb-2 form-floating " -->
                            <div class="col-md-8 col-lg-8 col-xl-8 mb-2 form-floating form__modal-group">
								<input type="text" id="txtPeriodPayed"  name="txtPeriodPayed" class="form-control form-control-sm text-left" autocomplete="off" style="width:200px; height:38px"> 
								<i class="fas fa-calendar-alt icoTextBox" id="calendar"></i><br>
								<label for="txtPeriodPayed">Fecha de inicio</label>
								<span class="textAlert"><i class="fas fa-exclamation-triangle"></i> Agregar las fechas del documento</span>
							</div>
                        </div>
                    </div>
                    <!-- Costo,Cantidad,Serie posición 5,4 y 6 -->
                    <div style="height:20px;"></div> <!-- Agregar un espacio -->
                    <!-- Marca 6 -->
                    <div class="row pos2 hide-items">
                        <div class="col-md-6 col-lg-6 col-xl-6 mb-2 form-floating ">
                            <input id="txtCostInd" type="text" class="form-control form-control-sm text-center number required" data-mesage="Debes Agregar el precio" >
                            <label for="txtCostInd">Monto a cobrar</label>
                        </div>
                    </div>
                    <div style="height:10px;"></div> <!-- Agregar un espacio -->
                    <!-- Comentarios posición 4 -->
                    <div class="row pos3 hide-items">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                            <textarea class="form-control form-control-sm" id="txtComments" style="height:60px; text-transform:uppercase" autocomplete="off" rows="3"></textarea>
                            <label for="txtComments">Notas a los pagos</label>
                        </div>
                    </div>
                    <!-- Boton posición 4 -->
                    <div class="row pos3 hide-items">
                        <div class="col-md-12 mb-5">
                            <button id="btn_regist" type="button" class="btn btn-sm btn-primary" >Agregar</button>
                            <button id="btn_regist" type="button" class="btn btn-danger btn-sm btn-block" >Limpiar</button>
                        </div>
                        <!-- <div class="col-md-12 mb-5">

                        </div> -->
                    </div>
                </div>
            </div>

            <div class="mvst_table">
                <h1>Registro de datos compromisos de pagos</h1>
                <table class="display compact nowrap"  id="tblPayAgree" style = "width: 95%">
                    <thead>
                        <tr>
                            <th style="width:  20px"></th>
                            <th style="width: 100px">Numero de pago</th>
                            <th style="width: 100px">Frecuencia</th>
                            <th style="width:  80px">Monto</th>
                            <th style="width:  80px">Fecha</th>
                            <th style="width: 200px">Nota</th>
                        </tr>
                    </thead>
                    <tbody>	
                    </tbody>
                </table>
            </div>        
        </div>

    </div>
</div>

<!-- Modal Borrar -->
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
                    <button type="button" class="btn btn-primary" id="btnPrintReport">Imprimir</button>
                    <button type="button" class="btn btn-secondary" id="btnHideModal">Cerrar</button>
                </div>
            </div>
    </div>
</div>

<script src="<?=  PATH_ASSETS . 'lib/functions.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/dataTable/datatables.min.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_VIEWS . 'PaymentAgreement/PaymentAgreement.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>