<?php 
	defined('BASEPATH') or exit('No se permite acceso directo'); 
	require ROOT . FOLDER_PATH . "/app/assets/header.php";
?>

<header>
	<?php require ROOT . FOLDER_PATH . "/app/assets/menu.php"; ?>
</header>
<!-- CUERPO DE LA PAGINA -->
<div class="container-fluid">
		<div class="contenido ">
			<div class="row mvst_group">
				<div class="mvst_panel" style="background-color: #EDD2F5">
					<div class="form-group">
						<div class="row">
							<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
								<select id="txtProject" class="form-select form-select-sm " aria-label="Floating label select" data-mesage="Debes seleccionar un proyecto">
									<option value="0" selected>Selecciona proyecto</option>
								</select>
								<label for="txtProject">Proyecto</label>
							</div>
						</div>
						<hr>
						
					</div>
				</div>

				<div class="mvst_table">
					<h1>Proyectos</h1>
					<table class="display compact nowrap"  id="tblProductForSubletting">
						<thead>
							<tr>
								<th style="width:  30px"></th>
								<th style="width:  50px">Numero</th>
								<th style="width:  200px">Proyecto</th>
								<th style="width:  150px">Locacion</th>
								<th style="width:  100px">Tipo de locacion</th>
								<th style="width:  80px">Fecha Inicio</th>
								<th style="width:  80px">Fecha Fin</th>
								<th style="width:  40px">Tiempo</th>
							</tr>
						</thead>
						<tbody id="tblProductForSublettingRow">						
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
<script src="<?=  PATH_VIEWS . 'FathersReports/FathersReports.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>