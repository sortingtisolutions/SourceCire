<?php 
      defined('BASEPATH') or exit('No se permite acceso directo'); 
      require ROOT . FOLDER_PATH . "/app/assets/header.php";	  
?>
<header>
    <?php require ROOT . FOLDER_PATH . "/app/assets/menu.php"; ?>
</header>
<style>
    .hiddenElement {
        visibility: hidden !important;
    }
</style>
<!-- Comentario prueba 2 -->
<!-- CUERPO DE LA PAGINA -->
<div class="container-fluid">
    <div class="contenido">
        <div class="row mvst_group">
                <!-- Start área de formularios -->
                <div class="mvst_panel" style="background-color: #E8DC9F">
                    <div class="form-group">
                        <h4 id="titulo">NUEVA RELACIÓN DE UNIDAD Y ALMACEN MOVIL.</h4>  
                        <form id="formProveedor" class="row g-3 needs-validation" novalidate>

                        <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                    <input id="NomAlmacen" name="NomAlmacen" type="text" class="form-control form-control-sm" style="text-transform:uppercase" required>
                                    <label for="NomAlmacen">Placa</label>
                                </div>
                                <input id="IdAlmacen" name="IdAlmacen" type="hidden">
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                    <select id="txtStoreSource" class="form-select form-select-sm required"><option value="0" selected>Selecciona almacen</option></select>
                                    <label for="txtStoreSource" class="form-label">Almacen</label>
                                </div>
                            </div>

                            <div class="row list-finder">
                                <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                        <input id="boxProducts" type="text" class="form-control form-control-sm required" data-mesage="Debes seleccionar una factura" >
                                        <label for="boxProducts">Productos</label>
                                        <input type="hidden" id="boxIdProducts" name="boxIdProducts">
                                    </div>
                                <div id="listProducts" class="list-group list-hide">
                                    <div class="list-items" ></div>
                                </div>
                            </div>                            

                            <div class="row">
                                <div class="col-6">
                                    <button type="button"  class="btn btn-primary btn-sm btn-block" style="font-size: 1rem !important;" id="GuardarAlmacen">Guardar</button>
                                </div>
                                <div class="col-6">
                                    <button type="button"  class="btn btn-danger btn-sm btn-block" style="font-size: 1rem !important;" id="LimpiarFormulario">Limpiar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End área de formularios -->

                <!-- Start área de listado -->
                <div class="mvst_table">
                    <h1>Lista de la relación entre unidad móvil y almacen</h1>
                    
                    <div class="row">
                        <div class="col-12 col-md-12">		
                                <table id="AreasTable" class="display compact nowrap" style="width:100%">         
                                        <thead>
                                            <tr>
                                                    <th style="width:  30px"></th>
                                                    <th style="width: 20px">Numero de Placa</th>
                                                    <th style="width:  200px">Almacen</th>
                                                    <th style="width:  40px">Producto</th>
                                                    <th style="width:  40px">Categorias</th>
                                                    <th style="width:  40px">Subcategorias</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablaAlmacenesRow">
                                        </tbody>
                                    </table>
                            </div>
                    </div>
                </div>
                <!-- End área de listado -->
            </div>
           
    </div>
    
</div>

<script src="<?=  PATH_ASSETS . 'lib/functions.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/dataTable/datatables.min.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_VIEWS . 'MobilStore/MobilStore.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>