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
                    <h4 id="titulo">Nueva Area</h4>  
                    <form id="formProveedor" class="row g-3 needs-validation" novalidate>

                    <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <input id="nomArea" name="nomArea" type="text" class="form-control form-control-sm" style="text-transform:uppercase" required>
                                <label for="nomArea">Nombre area</label>
                            </div>
                            <input id="IdArea" name="IdArea" type="hidden">
                    </div>

                    <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <input id="emailArea" name="emailArea" type="email" pattern="[a-zA-Z0-9_]+([.-][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.-][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,6}" class="form-control form-control-sm"  >  <!-- pattern=".+@example\.com" formnovalidate -->
                                <label for="emailArea">Email Principal del area</label>
                            </div>
                    </div>

                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <select id="selectTipoAlmacen"  name="selectTipoAlmacen"  class="form-select form-select-sm" required>
                                    <option id='' value="" >Seleccione...</option>
                                    <option id='estaticos'  value='0'>Inactivo</option> 
                                    <option id='estaticos'  value='1'>Activo</option>
                                </select>
                                <label for="selectTipoAlmacen" class="form-label">Estatus</label>
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
                <h1>Lista de Areas Operativas de la Empresa</h1>
                
                <div class="row">
                    <div class="col-12 col-md-12">		
                            <table id="AreasTable" class="display compact nowrap" style="width:80%">         
                                <thead>
                                    <tr>
                                        <th style="width:  20px"></th>
                                        <th style="width:  10px">Id</th>
                                        <th style="width: 100px">Nombre</th>
                                        <th style="width: 200px">Email Principal</th>
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
<script src="<?=  PATH_VIEWS . 'Areas/Areas.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>