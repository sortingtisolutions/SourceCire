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
                <div class="form_primary">
                    <h4>Seleccion de proyectos únicos</h4>
<!-- 
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <input id="txtPackageName" type="text" class="form-control form-control-sm" style="text-transform:uppercase">
                                <label for="txtPackageName">Nombre del paquete</label>
                            </div>
                           
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <input id="txtPackagePrice" type="text" class="form-control form-control-sm">
                                <label for="txtPackagePrice">Precio del paquete</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <select id="txtCategoryPack" class="form-select form-select-sm required">
                                    <option value="0" data-content="||||" selected>Selecciona una categoría</option>
                                </select>
                                <label for="txtCategoryPack">Categoria</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <select id="txtSubcategoryPack" class="form-select form-select-sm required">
                                    <option value="0" selected>Selecciona una subcategoría</option>
                                </select>
                                <label for="txtSubcategoryPack" class="form-label">Subcategoia</label>
                            </div>
                          
                        </div>
 
                        <div class="row">
                            <div class="col-md-12 mb-5">
                                <button type="button" class="btn btn-sm btn-primary disabled" id="btn_packages">Crear paquete</button>
                                <button type="button" class="btn btn-sm btn-danger hide-items" id="btn_packages_cancel">Cancelar</button>
                            </div>
                        </div>
 -->
                    </div>

                    <div class="form_secundary">
                        <h4>Seleccion de proyectos únicos</h4>
                        <div class="row">
                            <input type="hidden" id="txtIdPackages" name="txtIdPackages"><br>
                            <!-- <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <select id="txtCategoryProduct" class="form-select form-select-sm required">
                                    <option value="0" data-content="||||" selected>Selecciona una categoría</option>
                                </select>
                                <label for="txtCategoryProduct">Categoria</label>
                            </div> -->
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <select id="txtSubcategoryProduct" class="form-select form-select-sm required">
                                    <option value="0" selected>Selecciona una subcategoría</option>
                                </select>
                                <label for="txtSubcategoryProduct" class="form-label">Subcategoia</label>
                            </div>
                        </div>  -->

                        <div class="row list-finder">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <div class="box-items-list" id="boxProducts">Proyectos Únicos
                                    <i class="fas fa-angle-down"></i>
                                </div>

                            </div>
                            <div class="list-group">
                                <div class="list-items" id="listProducts"></div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="mvst_table row">
            <div class="row rowTop">
                        <h1>PROYECTOS </h1>
                        
                        <select id="txtCategoryList" class="topList">
                            <option value="0">SELECCIONA CATÁLOGO</option>
                        </select>
                        
                    </div>
                <div class="double-column">
                    <div class="left-side">
                        <h3>Proyectos Padre</h3>
                       
                        <table class="display compact nowrap"  id="tblPackages"  style="width:95%">
                            <thead>
                                <tr>
                                    <th style="width:  70px">Numero</th>
                                    <th style="width:  auto">Nombre</th>
                                    <th style="width:  30px">Fecha de inicio</th>
                                    <th style="width:  30px">Fecha fin</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="right-side">
                        <h3>Proyectos Adjuntos</h3>
                        <table class="display compact nowrap"  id="tblProducts" style="width:95%">
                            <thead>
                                <tr>
                                    <th style="width:  20px"></th>
                                    <th style="width:  70px">Numero</th>
                                    <th style="width:  auto">Proyecto</th>
                                    <th style="width:  30px">Fecha de inicio</th>
                                    <th style="width:  30px">Fecha fin</th>
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


<!-- Modal Borrar -->
<div class="modal fade" id="delPackModal" tabindex="-1" aria-labelledby="BorrarPerfilLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
            <div class="modal-header ">
            </div>
            <div class="modal-body" style="padding: 0px !important;">


            <div class="row">
                <input type="hidden" class="form-control" id="txtIdPackage" aria-describedby="basic-addon3">
                <div class="col-12 text-center">
                    <span class="modal-title text-center" style="font-size: 1.2rem;" id="BorrarPerfilLabel">¿Seguro que desea borrarlo?</span>
                </div>
            </div>

            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger" id="btnDelPackage">Borrar</button>
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
                <input type="hidden" class="form-control" id="txtIdProductPack" aria-describedby="basic-addon3">
                <div class="col-12 text-center">
                    <span class="modal-title text-center" style="font-size: 1.2rem;" id="BorrarPerfilLabel">¿Seguro que desea eliminar la relacion con el proyecto padre?</span>
                </div>
            </div>

            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger" id="btnDelProduct">Borrar</button>
                </div>
            </div>
    </div>
</div>


<script src="<?=  PATH_ASSETS . 'lib/functions.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/dataTable/datatables.min.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_VIEWS . 'UnicProjectstoParent/UnicProjectstoParent.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>