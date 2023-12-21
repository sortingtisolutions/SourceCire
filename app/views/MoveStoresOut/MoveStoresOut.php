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

            <div class="mvst_panel">
                <div class="form-group">

                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                            <select id="txtTypeExchange" class="form-select form-select-sm required"><option value="0" data-content="||||" selected>Selecciona tipo de movimiento</option></select>
                            <label for="txtTypeExchange">Tipo de movimiento</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                            <select id="txtStoreSource" class="form-select form-select-sm required"><option value="0" selected>Selecciona almacen</option></select>
                            <label for="txtStoreSource" class="form-label">Almacen Origen</label>
                        </div>
                    </div>
                  
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                            <select id="txtStoreTarget" class="form-select form-select-sm"><option value="0" selected>Selecciona almacen</option></select>
                            <label for="txtStoreTarget">Almacen Destino</label>
                        </div>
                    </div>

                    <div class="row list-finder hide-items">
                        <!-- <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <div id="boxProducts" type="text" class="box-items-list" >Productos
                                    <i class="fas fa-angle-down"></i>
                                </div> 
                        </div> -->
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <input id="boxProducts" type="text" class="form-control form-control-sm required" data-mesage="Debes seleccionar una factura" >
                                <label for="boxProducts">Productos</label>
                                <input type="hidden" id="boxIdProducts" name="boxIdProducts">
                            </div>
                        <div id="listProducts" class="list-group list-hide">
                            <div class="list-items" ></div>
                        </div>
                    </div>
                    <div class="row list-finder hide-items">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <input id="txtQuantity" type="text" class="form-control form-control-sm required" data-mesage="" >
                                <label for="txtQuantity">Cantidad</label> 
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                            <textarea class="form-control form-control-sm" id="txtComments" style="height: 120px" rows="3"></textarea>
                            <label for="txtComments">Comentarios al producto</label>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-5">
                            <button id="btn_exchange" type="button" class="btn btn-sm btn-primary" >Agregar</button>
                        </div>
                    </div>
                    <!-- <div class="sidebar__comments"> 
                        <span class="invoice_button toComment" id="toComment">
                            <i class="far fa-comment-alt"></i> Comentarios
                        </span> 
                    </div>  --><!--- 11-10-23 -->
                </div>
            </div>

            <div class="mvst_table">
                <h1>Salidas de almacenes</h1>
                <table class="display compact nowrap"  id="tblExchanges">
                    <thead>
                        <tr>
                            <th style="width:  80px">soppr</th>
                            <th style="width:  30px"></th>
                            <th style="width:  80px">SKU</th>
                            <th style="width: 350px">Producto</th>
                            <th style="width:  60px">Cantidad</th>
                            <th style="width: 100px">No. Serie</th>
                            <th style="width:  50px">Tipo Origen</th>
                            <th style="width: 150px">Almacen Origen</th>
                            <th style="width:  50px">Tipo Destino</th>
                            <th style="width: 150px">Almacen Destino</th>
                            <th style="width: 350px">Nota</th>
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
                    <span class="modal-title text-center" style="font-size: 1.2rem;" id="BorrarPerfilLabel">Folio: <h3 class="resFolio"></h3></span>
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

<!-- Modal Borrar -->
<div class="modal fade" id="SinExistenciasModal" tabindex="-1" aria-labelledby="SinExistenciasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
            <div class="modal-header ">
            </div>
            <div class="modal-body" style="padding: 0px !important;">

            <div class="row">
                <div class="col-12 text-center">
                    <span class="modal-title text-center" style="font-size: 1.2rem;" id="SinExistenciasLabel">No se cuenta con Existencias suficientes para procesar</span>
                </div>
            </div>

            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btnCloseModal">Cerrar</button>
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
                Identificando Productos<br>
                <i class="fas fa-spinner spin"></i> 
                </p>
            <p>Cargando informacion del almacen seleccionado ...... </p>
        </div>
</div>
<!-- end -->
<!-- Modal General  -->
<div class="invoice__modal-general invoice-border modalTable">
        <div class="modal__header invoice-border">
            <div class="modal__header-concept" style="font-weight: 700">&nbsp;Listados de productos</div>
            <i class="far fa-window-close closeModal"></i>
        </div>
        <div class="modal__body">
        </div>
    </div>

<!-- formulario de comentarios -->
<div id="commentsTemplates" class="table_hidden box_template">
        <div class="comments__box">
            <!-- Lista de comentarios -->
            <div class="comments__list"></div>
            <!-- Captura de cumentario -->
                <div class="comments__addNew">
                    <label for="txtComment">Escribe comentario</label><br>
                    <textarea name="txtComment" id="txtComment" cols="100" rows="5" class="invoiceInput"></textarea><br>
                    <span class="invoice_button" id="newComment"><i class="fas fa-plus"></i>guardar comentario</span>
                </div>
        </div>
    </div>

<script src="<?=  PATH_ASSETS . 'lib/functions.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/dataTable/datatables.min.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_VIEWS . 'MoveStoresOut/MoveStoresOut.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/jquery-ui.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>