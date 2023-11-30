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
            <div class="mvst_panel" style="width:280px; background-color: #e2edf3">
                <div class="form-group">
                    <div class="form_primary">
                        <h4 class="mainTitle">Responsables del Proyecto</h4>

						<div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <input id="txtProjectName" type="text" class="form-control form-control-sm" style="font-size:10px; background:#EAEDED">
                                <label for="txtProjectName">Nombre del Proyecto</label>
                                <input id="txtProjectId" type="hidden" class="form-control form-control-sm" style="font-size:10px; background:#EAEDED">
                                <input id="txtCustomerId" type="hidden" class="form-control form-control-sm" style="font-size:10px; background:#EAEDED">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <select id="txtProjectType" class="form-select form-select-sm required"><option value="0" selected>Selecciona el tipo de proyecto</option></select>
                                <label for="txtProjectType" class="form-label">Tipo de proyecto</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <input id="txtTime" type="text" class="form-control form-control-sm number required"  data-mesage="Debes agregar la cantidad de productos">
                                <label for="txtTime">Duración del proyecto</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <select id="txtLocation" class="form-select form-select-sm required"><option value="0" selected>Selecciona el tipo de locación</option></select>
                                <label for="txtLocation" class="form-label">Tipo de locación</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <input type="text" id="txtLocationEdt" name="txtLocationEdt" class="form-control form-control-sm number required" autocomplete="off">
                                <label for="txtLocationEdt" class="form-label">Locación</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <select id="txtLocationsEdt" class="form-select form-select-sm required hide"><option value="0" selected></option></select>
                                <label for="txtLocationsEdt" class="form-label">Locación</label>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <button class="bn btn-add hide" id="addLocation" ></button>
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <select id="txtCustomer" class="form-select form-select-sm required"><option value="0" selected>Selecciona Cliente</option></select>
                                <label for="txtCustomer" class="form-label">Selecciona Cliente</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <select id="txtProductor" class="form-select form-select-sm required"><option value="0" selected>Selecciona Productor</option></select>
                                <label for="txtProductor" class="form-label">Selecciona Productor</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <input id="txtHowRequired" type="text" class="form-control form-control-sm number required"  data-mesage="Debes agregar la cantidad de productos">
                                <label for="txtHowRequired">¿Quien solicitó?</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <input id="txtTripDays" type="text" class="form-control form-control-sm number required"  data-mesage="Debes agregar la cantidad de productos">
                                <label for="txtTripDays">Días de viaje</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <input id="txtReturnDays" type="text" class="form-control form-control-sm number required"  data-mesage="Debes agregar la cantidad de productos" >
                                <label for="txtReturnDays">Días de regreso</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <input id="txtLoad" type="text" class="form-control form-control-sm number required"  data-mesage="Debes agregar la cantidad de productos">
                                <label for="txtLoad">Días de carga</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <input id="txtDownload" type="text" class="form-control form-control-sm number required"  data-mesage="Debes agregar la cantidad de productos">
                                <label for="txtDownload">Días de descarga</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <input id="txtTestTec" type="text" class="form-control form-control-sm number required"  data-mesage="Debes agregar la cantidad de productos">
                                <label for="txtTestTec">Días pruebas técnicas</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <input id="txtTestLook" type="text" class="form-control form-control-sm number required"  data-mesage="Debes agregar la cantidad de productos">
                                <label for="txtTestLook">Días pruebas de look</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <select id="txttypeCall" class="form-select form-select-sm required"><option value="0" selected>Selecciona tipo de llamado</option></select>
                                <label for="txttypeCall" class="form-label">Tipo de llamado</label>
                            </div>
                        </div>
                    </div>  <!-- form_primary -->
                    <div style="height:15px;"></div> <!-- Agregar un espacio -->

                    <!-- BOTON PARA REGISTRAR LA SALIDA -->
                    <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-primary btn-sm btn-block" style="font-size: 0.8rem !important;" id="recordChgUser">Actualizar</button>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-danger btn-sm btn-block" style="font-size: 0.8rem !important;" id="cleanForm">Limpiar</button>
                            </div>
                    </div>
                </div>
            </div>

<!-- Tabla para presentar los contenidos del proyecto -->
            <div class="mvst_table">
                <h1>Selección de Proyectos</h1>
                <div class="mvst_list tblProdMaster">
                        <table class="display nowrap"  id="tblAsignedProd" style="width:100%; font-size: 0.7rem">
                            <thead>
                                <tr >
                                    <th style="width:  20px"></th>
                                    <th style="width:  200px">Nombre Proyecto</th>
                                    <th style="width:  80px">No. Proyecto</th>
									<th style="width:  80px">Tipo Proyecto</th>
                                    <th style="width:  70px">Fecha Inicio</th>
                                    <th style="width:  70px">Fecha Fin</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delProdModal" tabindex="-1" aria-labelledby="BorrarPerfilLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                <div class="modal-header ">
                </div>
                <div class="modal-body" style="padding: 0px !important;">

                <div class="row">
                    <input type="hidden" class="form-control" id="txtIdProduct" aria-describedby="basic-addon3">
                    <div class="col-12 text-center">
                        <span class="modal-title text-center" style="font-size: 1.2rem;" id="BorrarPerfilLabel">Esta agrupacion de proyectos se va a precancerlar</span>
                    </div>
                </div>

                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-danger" id="btnDelProduct">Cancelar</button>
                    </div>
                </div>
        </div>
    </div>
<!-- Boton para confirmar actualizacion de usuarios -->
<div class="modal fade" id="starClosure" tabindex="-1" aria-labelledby="BorrarPerfilLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
            <div class="modal-header ">
            </div>
            <div class="modal-body" style="padding: 0px !important;">
            
            <div class="row">
                <input type="hidden" class="form-control" id="txtIdClosure" aria-describedby="basic-addon3">
                <div class="col-12 text-center">
                    <span class="modal-title text-center" style="font-size: 1rem;" id="BorrarPerfilLabel">¿ESTAS SEGURO DE ACTUALIZAR LOS USUARIOS EN EL PROYECTO: </span>
                    <span class="modal-title text-center" style="font-size: 1rem;" id="ProjectName"> ?</span>
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


<div class="overlay_background overlay_hide" id="addLocationModal" style="width: 60%; left:25%; background-color: rgba(255, 255, 255, 0); z-index: 500;">
    <div class="overlay_modal" style="z-index: 50;">
        <div class="overlay_closer"><span class="title"></span><span class="btn_close">Cerrar</span></div>
        <div class="" style="position: absolute; top: 10px; height: 60px; padding: 10px;">
            <button type="button" class="btn btn-sm btn-primary" id="btn_save_locations">Guardar</button>
        </div>
        <div class="container-fluid" >
            <div class="contenido">
                <div class="row">
                    <div class="" style="width: 100%; height: 100vh; padding: 50px 10px 10px 10px; overflow: auto; padding: 4px; ">
                        <div class="row">
                            
                            <!-- <button class="bn btn-ok" id="addLocationEdos">Agregar</button> -->
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="background-color: #ffffff; border: 2px solid #eeeeee; border-radius: 10px;">
                                <table id="" class="table_information form-floating ">
                                    <tr>
                                        <td>Locación</td>
                                        <td>
                                            <input type="text" id="txtLocationExtra" name="txtLocationExtra" class="textbox wtf" autocomplete="off"><br>
                                            <span class="textAlert"></span>
                                        </td>
                                        <td>Estado de la República</td>
                                        <td>
                                            <select id="txtEdosRepublic_2" name="txtEdosRepublic_2" class="textbox ">
                                                <option value="0"></option>
                                            </select>
                                            <!-- <span class="textAlert"><i class="fas fa-exclamation-triangle"></i> Debes seleccionar el estado</span> -->
                                        </td>
                                        <td colspan=2>
                                            <button class="bn btn-ok" id="addLocationEdos">Agregar</button>
                                        </td>
                                    </tr>
                                    
                                </table>
                            </div>
                        </div>
                        <div class="row mt-2" >
                            <table class="display compact nowrap" style = "width: 100%" id="listLocationsTable">
                                <thead>
                                    <tr>
                                        <th style = "width: 30px"></th>
                                        <th style = "width: 100px">Locación</th>
                                        <th style = "width:  100px">Estado de la Republica</th>
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
<script src="<?=  PATH_ASSETS . 'lib/functions.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/dataTable/datatables.min.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_VIEWS . 'ParentsProjects/ParentsProjects.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>
