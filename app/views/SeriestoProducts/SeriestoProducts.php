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
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating" style="display: flex;gap: 1rem;font-size: 13px; background: #fff;margin-top: 20px; border-radius: 4px; ">
                                
                                <div class="" style="margin-left: 0px; margin-top: 5px;margin-bottom: 5px;">
                                    <input class="form-check-input checkTipe" type="radio" name="RadioConceptos" id="RadioConceptos1" val="1" checked>
                                    <label class="form-check-label" for="RadioConceptos1">
                                    Accesorios Fijos
                                    </label>
                                </div>
                                <div class="" style="margin-left: 18px;margin-top: 5px;">
                                    <input class="form-check-input checkTipe" type="radio" name="RadioConceptos" id="RadioConceptos2" val="2" >
                                    <label class="form-check-label" for="RadioConceptos2">
                                    Accesorios Virtuales
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px;">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <select id="txtCategoryProd" class="form-select form-select-sm required">
                                    <option value="0" data-content="||||" selected>Selecciona una categoría</option>
                                </select>
                                <label for="txtCategoryProd">Categoria</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <select id="txtSubcategoryProd" class="form-select form-select-sm required">
                                    <option value="0" selected>Selecciona una subcategoría</option>
                                </select>
                                <label for="txtSubcategoryProd" class="form-label">Subcategoria</label>
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
                            <div style="height:10px;"></div>
                            <h4>Seleccion de Accesorios</h4>

                            <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <select id="txtCategoryAcce" class="form-select form-select-sm required">
                                    <option value="0" data-content="||||" selected>Selecciona</option>
                                </select>
                                <label for="txtCategoryAcce">Categoria de los accesorios</label>
                            </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                    <select id="txtSubcategoryAcce" class="form-select form-select-sm required">
                                        <option value="0" selected>Selecciona</option>
                                    </select>
                                    <label for="txtSubcategoryAcce" class="form-label">Subcategoria de accesorios</label>
                                </div>
                            </div>
                            <div style="height:10px;"></div>
                            <!-- <div class="row list-finder"  >
                                <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                    <div class="box-items-list" id="boxProducts">Accesorios
                                        <i class="fas fa-angle-down"></i>
                                    </div>
                                </div>
                                <div class="list-group list-hide">
                                    <div class="list-items" id="listProducts" style="max-height: 150px !important;"></div>
                                </div>
                                
                            </div> -->

                            <div class="row list-finder pos2">
                                <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                        <input id="txtProducts" type="text" class="form-control form-control-sm required" data-mesage="Debes seleccionar un proveedor" autocomplete="off">
                                        <label for="txtProducts">Productos</label>
                                        <input type="hidden" id="txtIdProducts" name="txtIdProducts">
                                </div>
                                <div id="listProduct" class="list-group list-hide">
                                    <div class="list-items" style="max-height: 160px !important;"></div>
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
                        <h3>SERIES DE PRODUCTO</h3>
                        <table class="display compact nowrap"  id="tblPackages"  style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:  70px">Sku Producto</th>
                                    <th style="width:  auto">Descripcion</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div class="right-side">
                        <h3>SERIES DE ACCESORIOS</h3>
                        <table class="display compact nowrap"  id="tblProducts" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:  20px"></th>
                                    <th style="width:  40px">Sku Accesorio</th>
                                    <th >Nombre Accesorio</th>
                                    <th style="width:  20px">Cantidad</th>
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
<script src="<?=  PATH_VIEWS . 'SeriestoProducts/SeriestoProducts.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>