<?php
	defined('BASEPATH') or exit('No se permite acceso directo');
	require ROOT . FOLDER_PATH . "/app/assets/header.php";
?>

<header>
	<?php require ROOT . FOLDER_PATH . "/app/assets/menu.php"; ?>
    <link rel="stylesheet" href="<?= PATH_ASSETS .	'css/sx-modals.css?v=1.0.0.0' ?>" />
</header>

<!-- CUERPO DE LA PAGINA -->
<!-- Start Contenedor Listado de PRODUCTOS  -->
    <div class="container-fluid">
        <div class="contenido">
            <div class="row mvst_group">
                <div class="mvst_list tblProdMaster">
                    <div class="row rowTop">
                        <h1>Control de Cuentas x Cobrar</h1>
                        <table class="display compact nowrap"  id="tblCollets" style="min-width:1200px">
                            <thead>
                                <tr>
                                    <th style="width:  20px">Pagar</th>
                                    <th style="width:  20px">Folio de<br>Segmento</th>
                                    <th style="width:  80px">Fecha de<br>generación</th>
                                    <th style="width: auto">Nombre Cliente</th>
                                    <th style="width: auto">Nombre Proyecto</th>
                                    <th style="width:  80px">Total a pagar</th>
                                    <th style="width:  80px">Total Pagado</th>
                                    <th style="width:  80px">Saldo Pendiente</th>
                                    <th style="width:  80px">Fecha limite<br>de pago</th>
                                    <th style="width:  80px">Fecha<br>Ultimo pago</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                </div>
            </div>

        </div>
    </div>
<!-- End Contenedor Listado de PRODUCTOS  -->

<!-- End Ventana modal Iniciar Proceso de Atencion -->
<div class="modal fade" id="starToWork" tabindex="-1" aria-labelledby="BorrarPerfilLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
            <div class="modal-header ">
            </div>
            <div class="modal-body" style="padding: 0px !important;">

            <div class="row">
                <input type="hidden" class="form-control" id="txtIdProductPack" aria-describedby="basic-addon3">
                <div class="col-12 text-center">
                    <span class="modal-title text-center" style="font-size: 1.2rem;" id="BorrarPerfilLabel">¿Registar un pago para este documento?</span>
                </div>
            </div>

            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="btnToWork">Si, correcto</button>
                </div>
            </div>
    </div>
</div>

<!-- End Ventana modal Registrar SALIDA -->
<div class="modal fade" id="delProdModal" tabindex="-1" aria-labelledby="BorrarPerfilLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
            <div class="modal-header ">
            </div>
            <div class="modal-body" style="padding: 0px !important;">


            <div class="row">
                <input type="hidden" class="form-control" id="txtIdProductPack" aria-describedby="basic-addon3">
                <div class="col-12 text-center">
                    <span class="modal-title text-center" style="font-size: 1.2rem;" id="BorrarPerfilLabel">¿Registrar salida de productos?</span>
                </div>
            </div>

            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger" id="btnDelProduct">Registrar</button>
                </div>
            </div>
    </div>
</div>

<!-- Start Ventana modal AGREGA O MODIFICA PRODUCTO -->
<div class="overlay_background overlay_hide"id="registPayModal" style="height:85%; width: 70%; left:25%;" >
        <div class="overlay_modal">
            <div class="overlay_closer"><span class="title"></span><span class="btn_close">Cerrar</span></div>
            <!-- <div class="formButtons" style="position: absolute; top: 10px; height: 60px; padding: 10px;">
                <button type="button" class="btn btn-sm btn-primary" id="btn_save">Guardar</button>
            </div> -->
            <div class="project_data-box">
            <div class="project_data-table">
                <table >
                     <tr>
                        <td>Folio</td>
                        <td>
                            <input type="text" id="txtNumFol" name="txtNumFol" class="textbox wt5" autocomplete="off" disabled><br>
                            <span class="textAlert"></span>
                        </td>
                    </tr>

                    <tr>
                        <td>Nombre del proyecto</td>
                        <td class="projectName">
                            
                            <input type="text" id="txtProject" name="txtProject" class="textbox wtf" autocomplete="off" disabled>
                            <span class="textAlert"><i class="fas fa-exclamation-triangle"></i> Quieres agregar Nombre del proyecto</span>
                        </td>
                    </tr>

                    <tr>
                        <td>Referencia</td>
                        <td>
                            <input type="text" id="txtRefPayed" name="txtRefPayed" class="textbox" autocomplete="off"><br>
                            <span class="textAlert"></span>
                        </td>
                    </tr>

                    <tr>
                        <td>Monto a pagar</td>
                        <td>
                            <input type="text" id="txtMontoPayed" name="txtMontoPayed" class="textbox wt5" autocomplete="off"><br>
                            <span class="textAlert"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Periodo</td>
                        <td>
                            <input type="text" id="txtPeriodPayed"  name="txtPeriodPayed" class="textbox wtf required" autocomplete="off">
                            <i class="fas fa-calendar-alt icoTextBox" id="calendar"></i><br>
                            <span class="textAlert"><i class="fas fa-exclamation-triangle"></i> Debes agregar las fechas del projecto</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Forma de Pago</td>
                        <td>
                            <select  id="txtWayPay" name="txtWayPay" class="textbox wtf required" >
                                <option value="0"></option>
                            </select>
                            <span class="textAlert"><i class="fas fa-exclamation-triangle"></i> Debes seleccionar el tipo de proyecto</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2>
                            <button class="bn btn-ok update" id="savePayed">Guardar Pago</button>
                        </td>
                    </tr>
                </table>
            </div>
      
        </div>
        </div>
</div>


<script src="<?=  PATH_ASSETS . 'lib/functions.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/dataTable/datatables.min.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_VIEWS . 'CollectAccounts/CollectAccounts.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>
