<?php 
      defined('BASEPATH') or exit('No se permite acceso directo'); 
      require ROOT . FOLDER_PATH . "/app/assets/header.php";	  
?>
<header>
    <?php require ROOT . FOLDER_PATH . "/app/assets/menu.php"; ?>

</header>
<style>
    .hiddenElement { visibility: hidden !important; }
  	#calendar { max-width: 600px; margin: 0 auto; }
</style>
<div class="container-fluid">
		<div class="contenido ">
			<div class="row mvst_group">
				<div class="mvst_panel" style="background-color: #e2edf3">
					<div class="form-group">
						<div class="" style="display: grid; ">
							<label for="txtProject" style="font-size: 1.5em;font-weight: bold;">Proyecto</label>
							<div style="height:15px;"></div> 
							<div style="max-height: 480px; background-color:#ffffff;border-radius: 5px; overflow-y: scroll;">
								<div class="" id="txtProject" style=" font-size: 1.1em; margin-bottom: 1rem; padding: 10px 15px 15px 10px; ">
										
								</div>
							</div>
						</div>
						<hr>
						<h6	class="nameProduct objet objHidden" style="font-size: 1rem; font-weight: bold; text-align: center">Producto</h6>
						<input type="hidden" id="txtIdProject" class="form-control">
						<input type="hidden" id="txtIdSerie" class="form-control">
						<input type="hidden" id="txtIdStatus" class="form-control">
						<input type="hidden" id="txtIdMaintain" class="form-control">

					</div>
				</div>

				<div class="mvst_table">
					<h1>Calendario</h1>
					<div id="calendarPrueba"></div>
					<!-- <table class="display compact nowrap"  id="tblProductForSubletting">
						<thead>
							<tr>
								<th style="width:  30px"></th>
								<th style="width: 120px">Sku</th>
								<th style="width: 120px">Producto</th>
								<th style="width:  30px">Costo</th>
								<th style="width:  30px">Dias <br>Reparacion</th>
								<th style="width:  30px">Horas <br>Reparacion</th>
								<th style="width:  60px">Fecha Inicio</th>
								<th style="width:  60px">Fecha Fin</th>
								<th style="width:  20px">No. Eco</th>
								<th style="width:  150px">Comentarios</th>
								<th style="width:  50px">Motivo</th>
								<th style="width: 40px">Estatus</th>
								<th style="width: 50px">No. Serie</th>
							</tr>
						</thead>
						<tbody>						
						</tbody>
					</table>-->
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
<div class="overlay_background overlay_hide" id="mantenimientoModal" style="height: 600px; width:600px;">
        <div class="overlay_modal">
            <div class="overlay_closer"><span class="title"></span><span class="btn_close">Cerrar</span></div>
			<!--
            <div class="formButtons">
                <button type="button" class="btn btn-sm btn-primary" id="btn_save">Guardar</button>
            </div>-->
            <div class="">
                <table id="tblMotivoMantenimiento">
						<thead>
							<tr>
								<th style="width:  30px"></th>
								<th style="width: 100px">Código corto</th>
								<th style="width:  250px"> Motivo de Mantenimiento</th>
							</tr>
						</thead>
						<tbody>						
						</tbody>
                </table>
            </div>
        </div>
    </div>
<!-- End Ventana modal AGREGA O MODIFICA  -->

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
                    <span class="modal-title text-center" style="font-size: 1.2rem;" id="BorrarPerfilLabel">¡Producto de matenimiento guardado exitosamente!</span>
                </div>
            </div>

            </div>
                <div class="modal-footer">
                    <!--<button type="button" class="btn btn-primary" id="btnPrintReport">Imprimir</button>-->
                    <button type="button" class="btn btn-secondary" id="btnHideModalM">Cerrar</button>
                </div>
            </div>
    </div>
</div>
	<div class="overlay_background overlay_hide" id="ReportModal" style="max-width:45%;max-height:45%; margin-left: 35%;">
		<div class="overlay_modal">
			<div class="overlay_closer"><span class="title"></span><span class="btn_close">Cerrar</span></div>
			<div class="row">
				<div class="col-md-6 col-lg-6 col-xl-6 mb-2 form-floating">
					<input id="fechaIncial" name="fechaIncial" type="date"  class="form-control form-control-sm required2" style="text-transform: uppercase" >
					<label for="fechaadmision">Fecha Inicial</label>
				</div>
				<div class="col-md-6 col-lg-6 col-xl-6 mb-2 form-floating">
					<input id="fechaFinal" name="fechaFinal" type="date"  class="form-control form-control-sm required2" style="text-transform: uppercase" >
					<label for="fechaadmision">Fecha Final</label>
				</div>
			</div>
			<div class="row mt-4">
				<div class="col-md-8 col-lg-8 col-xl-10 ">
				</div>
				<div class="col-md-2 col-lg-2 col-xl-2 mt-4">
					<button type="button"  class="btn btn-primary btn-sm btn-block" style="font-size: 0.8rem !important;" id="GenerarReporte">Generar</button>
				</div>
			</div>
        </div>
	</div>
	
<script src="<?=  PATH_ASSETS . 'lib/functions.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/dataTable/datatables.min.js?v=1.0.0.0' ?>"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src="<?=  PATH_ASSETS . 'lib/fullcalendar/core/index.global.min.js' ?>"></script><!-- Agregador por Edna-->
<script src="<?=  PATH_ASSETS . 'lib/fullcalendar/daygrid/index.global.min.js' ?>"></script><!-- Agregador por Edna-->
<script src="<?=  PATH_ASSETS . 'lib/fullcalendar/es.global.js' ?>"></script><!-- Agregador por Edna-->
<script src="<?=  PATH_VIEWS . 'CalendarProyects/CalendarProyects.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>