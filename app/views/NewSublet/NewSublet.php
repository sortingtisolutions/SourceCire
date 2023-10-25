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
            <div class="mvst_panel" style="background-color: #EDD2F5">

                <div class="form-group">
                <div>
                    <h1>PRODUCTO A REGISTRAR</h1>
                </div>

                    <!-- Almacen posición 1 -->
                    <div class="row pos1 ">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                            <select id="txtStoreSource" class="form-select form-select-sm required"><option value="0" selected>Selecciona almacen</option></select>
                            <label for="txtStoreSource" class="form-label">Almacen</label>
                        </div>
                    </div>

                    <!-- Categoria posición 4 -->
                    <div class="row pos1">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                            <select id="txtCategory" class="form-select form-select-sm"><option value="0" selected></option></select>
                            <label for="txtCategory">Catálogo</label>
                        </div>
                    </div>
                    <div class="row pos1">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                            <select id="txtSubCategory" class="form-select form-select-sm"><option value='0' selected>Selecciona la subcategoria</option></select>
                            <label for="txtSubCategory">Subcategorias</label>
                        </div>
                    </div>
                  
                    <!-- Costo,Cantidad,Serie posición 5,4 y 6 -->
                    <div class="row list-finder pos2">
                            <div class="col-md-6 col-lg-6 col-xl-6 mb-2 pos4 form-floating">
                                <input id="txtSKUproduct" type="hidden" class="form-control form-control-sm text-center number required"  data-mesage="Debes agregar la cantidad de productos" value=0>
                                <!-- <label for="txtSKUproduct">SKUproduct</label> -->
                            </div> 
                            <div class="col-md-6 col-lg-6 col-xl-6 mb-2 pos4 form-floating">
                                <input id="txtNproduct" type="hidden" class="form-control form-control-sm text-center number required"  data-mesage="Debes agregar la cantidad de productos" value=0>
                                <!-- <label for="txtNproduct">SKUproduct</label> -->
                            </div>
                            
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                    <input id="txtProducts" type="text" class="form-control form-control-sm required" data-mesage="Debes seleccionar un proveedor" style="text-transform:uppercase" autocomplete="off">
                                    <label for="txtProducts">Productos</label>
                                    <input type="hidden" id="txtIdProducts" name="txtIdProducts">
                                    <input type="hidden" id="txtNextSerie" name="txtNextSerie" value=0>
                            </div>
                            <div id="listProduct" class="list-group list-hide">
                                <div class="list-items" ></div>
                            </div>
					</div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-xl-6 mb-2 pos4 form-floating">
                            <input id="txtPrice" type="text" class="form-control form-control-sm text-center number required"  data-mesage="Debes agregar la cantidad de productos">
                            <label for="txtPrice">Precio de renta</label>
                        </div>
                        <div class="col-md-6 col-lg-6 col-xl-6 mb-2 pos4 form-floating">
                            <input id="txtOffer" type="text" class="form-control form-control-sm text-center number required"  data-mesage="Debes agregar la cantidad de productos">
                            <label for="txtOffer">Precio de subarrendo</label>
                        </div>
                        
                    </div>

                    <div style="height:10px;"></div> <!-- Agregar un espacio -->
                    <div>
                        <h1>DATOS DE LA SERIE</h1>
                    </div>
                    <div style="height:10px;"></div> <!-- Agregar un espacio -->
                    
                    <!-- Proveedores posición 2 -->
                    <div class="row list-finder pos2 ">
                        <!-- <div style="height:18px;"></div> --> <!-- Agregar un espacio -->
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <input id="txtSuppliers" type="text" class="form-control form-control-sm required" data-mesage="Debes seleccionar un proveedor" autocomplete="off">
                                <label for="txtSuppliers">Proveedores</label>
                                <input type="hidden" id="txtIdSuppliers" name="txtIdSuppliers">
                        </div>
                        <div id="listSupplier" class="list-group list-hide">
                            <div class="list-items" ></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-xl-6 mb-2 form-floating">
							<input id="txtFechaReco" name="txtFechaReco" type="date"  class="form-control form-control-sm" style="text-transform: uppercase" >
							<label for="txtFechaReco">Fecha de recoleccion</label>
						</div>
                        
                        <div class="col-md-6 col-lg-6 col-xl-6 mb-2 form-floating pos6">
                            <input id="txtCollectionTime" type="time" class="form-control form-control-sm text-center required"  data-mesage="Debes agregar la serie de productos" >
                            <label for="txtCollectionTime">Horario recoleccion</label>
                            
                        </div> 
                        <div class="col-md-6 col-lg-6 col-xl-6 mb-2 form-floating">
							<input id="txtFechaEnt" name="txtFechaEnt" type="date"  class="form-control form-control-sm" style="text-transform: uppercase" >
							<label for="txtFechaEnt">Fecha de entrega</label>
						</div>
                        <div class="col-md-6 col-lg-6 col-xl-6 mb-2 form-floating pos4 ">
                            <input id="txtDeliveryTime" type="time" class="form-control form-control-sm text-center required" data-mesage="Debes Agregar el precio">
                            <label for="txtDeliveryTime">Horario entrega</label>
                        </div>
                        
                    </div>
                    
                   <div class="row pos3 ">
                        
                        <div class="col-md-6 col-lg-6 col-xl-6 mb-2 form-floating pos6 ">
                            <input id="txtQuantity" type="text" class="form-control form-control-sm text-center number required" data-mesage="Debes Agregar el precio" value=1>
                            <label for="txtQuantity">Cantidad</label>
                        </div>
        
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating pos6 ">
                            <input id="txtLocation" type="text" class="form-control form-control-sm text-center number required" data-mesage="Debes Agregar el precio" style="text-transform:uppercase" >
                            <label for="txtLocation">Ubicacion del proveedor</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating pos6 ">
                            <input id="txtStaff" type="text" class="form-control form-control-sm text-center required"  data-mesage="Debes agregar la marca de productos" style="text-transform:uppercase" autocomplete="off">
                            <label for="txtStaff">Personal de proveedor</label>
                        </div>
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 pos6 form-floating ">
                            <input id="txtStaffCtt" type="text" class="form-control form-control-sm text-center number required"  data-mesage="Debes agregar el numero del pedimento" style="text-transform:uppercase">
                            <label for="txtStaffCtt">Personal de CTT EXP & RENTALS</label>
                        </div>
                    </div> 
                
                    <!-- Comentarios posición 4 -->
                    <div class="row  pos1 ">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                            <textarea class="form-control form-control-sm" id="txtComments" style="height:60px; text-transform:uppercase" autocomplete="off" rows="3"></textarea>
                            <label for="txtComments">Comentarios</label>
                        </div>
                    </div>


                    <!-- Boton posición 4 -->
                        <div class="row pos1 ">
                            <div class="col-md-6 mb-5">
                                <button id="btn_exchange" type="button" class="btn btn-sm btn-primary" >Agregar</button>
                            </div>
                            <div class="col-md-6 mb-5">
                                    <button type="button"  class="btn btn-danger btn-sm" id="LimpiarFormulario">Limpiar</button>
                                </div>
                        </div>
                </div>
            </div>

            <div class="mvst_table">
                <h1>Listado de subarrendos no existentes</h1>
                <table class="display compact nowrap"  id="tblExchanges">
                    <thead>
                        <tr>
                            <th style="width:  20px"></th>
                            <!-- <th style="width:  80px">Sku Producto</th> -->
                            <th style="width: 300px">Nombre <br>del Producto</th>
                            <th style="width: 80px">Precio <br> de Renta</th>
                            <th style="width:  80px">Precio <br> de subarrendo</th>

                            <th style="width: 90px">Cantidad</th>
                            <th style="width:  50px">Horario <br> de recolección</th>
                            <th style="width:  60px">Horario <br> de entrega</th>
                            <th style="width:  50px">Fecha Inicial</th>
                            <th style="width:  60px">Fecha Final</th>

                            <th style="width: 140px">Almacen</th>
                            <th style="width: 130px">Catalogo</th>
                            <th style="width: 130px">Subcategoria</th>
                            <th style="width: 130px">Proveedor</th>
                            <th style="width: 130px">Ubicacion del proveedor</th>

                            <th style="width: 130px">Personal <br> de proveedor</th>
                            <th style="width: 140px">Personal <br> de CTT EXP & RENTALS</th>
                            <th style="width: 200px">Nota</th>
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
<div class="modal fade" id="MoveResultModal" tabindex="-1" aria-labelledby="BorrarPerfilLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
            <div class="modal-header ">
            </div>
            <div class="modal-body" style="padding: 0px !important;">


            <div class="row">
                <div class="col-12 text-center">
                    <!--<span class="modal-title text-center" style="font-size: 1.2rem;" id="BorrarPerfilLabel">Folio: <h3 class="resFolio">000000000000</h3></span>-->
                    <span class="modal-title text-center" style="font-size: 1.2rem;" id="BorrarPerfilLabel">¡Subarrendo guardado exitosamente!</span>
                </div>
            </div>

            </div>
                <div class="modal-footer">
                    <!--<button type="button" class="btn btn-primary" id="btnPrintReport">Imprimir</button>-->
                    <button type="button" class="btn btn-secondary" id="btnHideModal">Cerrar</button>
                </div>
            </div>
    </div>
</div>

<script src="<?=  PATH_ASSETS . 'lib/functions.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/dataTable/datatables.min.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_VIEWS . 'NewSublet/NewSublet.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>