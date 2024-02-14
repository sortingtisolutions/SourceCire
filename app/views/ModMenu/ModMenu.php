<?php 
      defined('BASEPATH') or exit('No se permite acceso directo'); 
      require ROOT . FOLDER_PATH . "/app/assets/header.php";	  
?>
<header>
    <?php require ROOT . FOLDER_PATH . "/app/assets/menu.php"; ?>
</header>
<style>
    .hiddenElement {visibility: hidden !important;}
</style>
<!-- Comentario prueba 2 -->
<!-- CUERPO DE LA PAGINA -->
<div class="container-fluid">
    <div class="contenido">
        <div class="row mvst_group">
                <!-- Start área de formularios -->
                <div class="mvst_panel">
                    <div class="form-group">
                        <h4 id="titulo">Registrar Nuevo Menu</h4>  
                        <form id="formProveedor" class="row g-3 needs-validation" novalidate>

                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                    <select id="mnuParent" class="form-select form-select-sm">
                                        <option value="0" selected>Selecciona el menu padre</option>
                                    </select>
                                    <label for="mnuParent">Menu padre</label>
                                </div>
                                <input id="txtIdMenu" name="txtIdMenu" type="hidden">
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                    <input id="txtItems" name="txtItems" type="text" class="form-control form-control-sm" required>
                                    <label for="txtItems">Titulo del menú</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                    <textarea class="form-control form-control-sm" id="txtDescription" style="height:100px; background-color: #FFFAFF" autocomplete="off" rows="5"></textarea>
                                    <label for="txtDescription">Descripción</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                    
                                    <input id="txtOrder" name="txtOrder" type="text" class="form-control form-control-sm" required>
                                    <label for="txtOrder">Orden</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                    <select id="txtModule"  name="txtModule"  class="form-select form-select-sm">
                                        <option id='' value="0" >Seleccione...</option>
                                    </select>
                                    <label for="txtModule" class="form-label">Módulo</label>
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
                    <h1>Lista de menús registrados</h1>
                    
                    <div class="row">
                        <div class="col-12 col-md-12">		
                                <table id="MenuTable" class="display compact nowrap" style="width:70%">         
                                        <thead>
                                            <tr>
                                                    <th style="width:  20px"></th>
                                                    <th style="width:  10px">Menu Padre</th>
                                                    <th style="width: 60px">Elemento</th>
                                                    <th style="width:  200px">Descripción</th>
                                                    <th style="width: 10px">Orden</th>
                                                    <th style="width:  100px">Modulo</th>
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
<script src="<?=  PATH_VIEWS . 'ModMenu/ModMenu.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>