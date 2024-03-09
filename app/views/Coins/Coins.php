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
                        <h4 id="titulo">Nueva Moneda</h4>  
                        <form id="formProveedor" class="row g-3 needs-validation" novalidate>

                        <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                    <input id="NomAlmacen" name="NomAlmacen" type="text" class="form-control form-control-sm" style="text-transform:uppercase" required>
                                    <label for="NomAlmacen">Nombre de la Moneda</label>
                                </div>
                                <input id="IdAlmacen" name="IdAlmacen" type="hidden">
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                    <input id="CoinsNumber" name="CoinsNumber" type="text" class="form-control form-control-sm" style="text-transform:uppercase" required>
                                    <label for="CoinsNumber">Numero</label>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                    <input id="CoinsCode" name="CoinsCode" type="text" class="form-control form-control-sm" style="text-transform:uppercase" required>
                                    <label for="CoinsCode">Codigo</label>
                                </div>
                                <div class="col-md-12 col-lg-12 col-xl-12 mb-2">
                                    <a href='https://www.emprendices.co/codigos-internacionales-de-las-divisas-segun-iso-4217/'>De acuerdo con el código de divisas ISO 4217</a>
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
                    <h1>Lista de Monedas</h1>
                    
                    <div class="row">
                        <div class="col-12 col-md-12">		
                                <table id="AreasTable" class="display compact nowrap" style="width:100%">         
                                        <thead>
                                            <tr>
                                                    <th style="width:  30px"></th>
                                                    <th style="width: 20px">Codigo</th>
                                                    <th style="width:  200px">Nombre</th>
                                                    <th style="width:  40px">Numero</th>
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
<script src="<?=  PATH_VIEWS . 'Coins/Coins.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>