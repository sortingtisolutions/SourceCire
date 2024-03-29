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
                    <h4 class="mainTitle">RELACIÓN PRODUCTO-ACCESORIO</h4>

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
                                <label for="txtSubcategoryPack" class="form-label">Subcategoria</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <select id="txtProductSubCat" class="form-select form-select-sm required">
                                    <option value="0" selected>Selecciona un producto</option>
                                </select>
                                <label for="txtProductSubCat" class="form-label">Productos</label>
                            </div>
                        </div>

                        <div id="selectAccesorios" style="visibility: hidden;">
                        <h4>Seleccion de Accesorios</h4>

                        <div class="row list-finder"  >
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <div class="box-items-list" id="boxProducts">Accesorios
                                    <i class="fas fa-angle-down"></i>
                                </div>
                            </div>
                            <div class="list-group list-hide">
                                <div class="list-items" id="listProducts"></div>
                            </div>
                            
                        </div>
                        </div>

                    </div>

                    <div class="form_secundary">
                    </div>
                </div>
            </div>

            <div class="mvst_table">
                <h1>Asignacion y selección de accesorios</h1>
                <div class="double-column">
                    <div class="left-side">
                        <h3>SERIES</h3>
                        <table class="display compact nowrap"  id="tblPackages"  style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:  70px">Sku-Serie</th>
                                    <th style="width:  auto">Descripcion</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div class="right-side">
                        <h3>Accesorios</h3>
                        <table class="display compact nowrap"  id="tblProducts" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:  20px"></th>
                                    <th style="width:  70px">SKU</th>
                                    <th >Accesorio</th>
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
                    <span class="modal-title text-center" style="font-size: 1.2rem;" id="BorrarPerfilLabel">¿Seguro que desea borrarlo?</span>
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
<script src="<?=  PATH_VIEWS . 'ProductAccessory/ProductAccessory.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>