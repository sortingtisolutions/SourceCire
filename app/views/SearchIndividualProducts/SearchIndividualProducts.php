<?php 
	defined('BASEPATH') or exit('No se permite acceso directo'); 
	require ROOT . FOLDER_PATH . "/app/assets/header.php";
?>

<header>
	<?php require ROOT . FOLDER_PATH . "/app/assets/menu.php"; ?>
</header>
<style>
    .hiddenElement {visibility: hidden !important;}

  #calendar {
    max-width: 600px;
    margin: 0 auto;
  }
</style>
<!-- CUERPO DE LA PAGINA -->
<div class="container-fluid">
		<div class="contenido ">
			<div class="row mvst_group">
				<div class="mvst_panel" style="background-color: #EDD2F5">
					<div class="form-group">

					<div class="row list-finder pos2">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <input id="txtProducts" type="text" class="form-control form-control-sm required" autocomplete="off">
                                <label for="txtProducts">Productos</label>
                                <input type="hidden" id="txtIdProducts" name="txtIdProducts">
                        </div>
                        <div id="listProduct" class="list-group list-hide">
                            <div class="list-items" ></div>
                        </div>
					</div>
						<hr>
					</div>
				</div>

				<div class="mvst_table">
					<h1>Identificación de Productos</h1>
					<table class="display compact nowrap"  id="tblProductForSubletting">
						<thead>
							<tr>
								<th style="width:  10px"></th>
								<th style="width: 200px">Producto</th>
								<th style="width:  70px">SKU-Serie</th>
								<th style="width:  90px">Serie</th>
								<th style="width: 150px">Proyecto</th>
								<th style="width:  70px">Fecha Inicio</th>
								<th style="width:  70px">Fecha Fin</th>
								<th style="width:  30px">Estatus</th>
								<th style="width:  30px">Usado (días)</th>
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
                    <button type="button" class="btn btn-secondary" id="btnHideModal">Cerrar</button>
                </div>
            </div>
    </div>
</div>


<!-- Start Ventana modal AGREGA O MODIFICA  -->
    <div class="overlay_background overlay_hide" id="CalendarModal" style="height: 1000px; width:60%;left:220px;">
        <div class="overlay_modal" style="top:2%">
            <div class="overlay_closer"><span class="title"></span><span class="btn_close">Cerrar</span></div>
			<!--
            <div class="formButtons">
                <button type="button" class="btn btn-sm btn-primary" id="btn_save">Guardar</button>
            </div>-->
            <div class="">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
<!-- End Ventana modal AGREGA O MODIFICA  -->

<div class="overlay_background overlay_hide" id="relationModal" style="width: 50%; left:20%">
        <div class="overlay_modal">
            <div class="overlay_closer"><span class="title"></span><span class="btn_close">Cerrar</span></div>
           <!--  <button type="button" class="btn btn-sm btn-primary" id="btn_save">Aplicar Cambio</button>
            <div style="height:15px;"></div>  -->
            <table class="display compact nowrap"  id="tblrelations" style="width: 90%">
                <thead>
                    <tr>
                        <!-- <th style="width:  10px"></th> -->
                        <th style="width:  80px">SKU</th>
                        <th style="width:  auto">Descripcion Producto</th>
                        <th style="width:  40px">Cantidad</th>
                       
                        <!-- <th style="width:  40px">Cambio por:</th> -->
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>


<script src="<?=  PATH_ASSETS . 'lib/functions.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/dataTable/datatables.min.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/fullcalendar/core/index.global.min.js' ?>"></script><!-- Agregador por Edna-->
<script src="<?=  PATH_ASSETS . 'lib/fullcalendar/daygrid/index.global.min.js' ?>"></script><!-- Agregador por Edna-->
<script src="<?=  PATH_ASSETS . 'lib/fullcalendar/es.global.js' ?>"></script><!-- Agregador por Edna-->
<script src="<?=  PATH_VIEWS . 'SearchIndividualProducts/SearchIndividualProducts.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/jquery-ui.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>