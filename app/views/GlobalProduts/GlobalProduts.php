<?php
	defined('BASEPATH') or exit('No se permite acceso directo');
	require ROOT . FOLDER_PATH . "/app/assets/header.php";
?>

<header>
	<?php require ROOT . FOLDER_PATH . "/app/assets/menu.php"; ?>
</header>
<!-- CUERPO DE LA PAGINA -->
<!-- Start Contenedor Listado de PRODUCTOS  -->
    <div class="container-fluid">
        <div class="contenido">
            <div class="row mvst_group">
                <div class="mvst_list tblProdMaster">
                    <div class="row rowTop">
                        <h1>Control Salida de Proyecto</h1>
                        
                     </div>
                    <table class="display compact nowrap"  id="tblProyects" style="min-width: 1400px">
                        <thead>
                            <tr>
                                <th style="width:  30px"></th>
                                <th style="width:  60px">SKU</th>
                                <th style="width: 200px">Nombre Producto</th>
                                <th style="width: 100px">Precio</th>
                                <th style="width:  40px">Tipo</th>
                                <th style="width:  40px">Servicio</th>
                                <th style="width:  40px">Seguro</th>
                                <th style="width:  60px">Moneda</th>
                                <th style="width: 150px">Categoria</th>
                                <th style="width: 150px">Subcategoria</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
            </div>
<!--            <div class="deep_loading">
                <div class="flash_loading"> Cargando datos...</div>
            </div>-->
        </div>
    </div>
<!-- End Contenedor Listado de PRODUCTOS  -->

<div class="overlay_background overlay_hide" id="ChangeModal" style="width: 60%; left:25%;">
        <div class="overlay_modal">
            <div class="row">
                <div class="overlay_closer"><span class="title"></span><span class="btn_close">Cerrar</span></div>
            </div>
            <div class="row">
                <div class="row rowTop">
                    <h1>Asignar subcategoria a los productos</h1>
                    
                    <select id="txtCategoryList" class="topList">
                        <option value="0">SELECCIONA CATÁLOGO</option>
                    </select>
                    <input type="hidden" id="txtNext" name="txtNext">
                </div>
                <table class="display compact nowrap"  id="tblSubcategories" style="width: 100%; left:15%;">
                    <thead>
                        <tr>
                            <th style="width:  30px"></th>
                            <th style="width: 100px">Código</th>
                            <th style="width: 250px">Nombre</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="row">
                <div class="overlay_closer"></div>
            </div>
            
        </div>
    </div>

<!-- End Ventana modal Registrar SALIDA -->
<div class="modal fade" id="delProdModal" tabindex="-1" aria-labelledby="BorrarPerfilLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
            <div class="modal-header ">
            </div>
            <div class="modal-body" style="padding: 0px !important;">


            <div class="row">
                <input type="hidden" class="form-control" id="txtIdProductPack" aria-describedby="basic-addon3">
                <div class="col-12 text-center">
                    <span class="modal-title text-center" style="font-size: 1.2rem;" id="BorrarPerfilLabel">¿Registrar salida de productos?</span>
                </div>
            </div>

            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger" id="btnDelProduct">Registrar</button>
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
            <p>Se estan actualizando los registros del proyecto, este proceso puede tardar varios minutos</p>
        </div>
</div>
<!-- end -->
<div class="modal fade" id="confirmarCargaModal" tabindex="-1" aria-labelledby="confirmarCargaLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-sm">
					 <div class="modal-content">
					 <div class="modal-header ">
					 </div>
					 <div class="modal-body" style="padding: 0px !important;">


					 <div class="row">
						  <input hidden type="text" class="form-control" id="IdConfirmar" aria-describedby="basic-addon3">
						  <div class="col-12 text-center">
								<span class="modal-title text-center" style="font-size: 1.2rem;" id="confirmCargaLabel">¿Seguro que desea subir a productos?</span>
								
						  </div>
						  <div class="col-12 text-center">
						  	<span class="modal-title text-center" style="font-size: 1rem;">Ten en cuenta que solo se registraran los datos que cuentan con todos sus datos confirmados</span>
						  </div>
						</div>

					 </div>
						  <div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
								<button type="button" class="btn btn-primary" id="confirmLoad">Confirmar</button>
						  </div>
					 </div>
				</div>
		</div>

<script src="<?=  PATH_ASSETS . 'lib/functions.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/dataTable/datatables.min.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_VIEWS . 'GlobalProduts/GlobalProduts.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>
