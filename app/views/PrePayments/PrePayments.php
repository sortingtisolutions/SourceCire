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
				<div class="mvst_panel" style="background-color: #B0C4DE">
					<div class="form-group">
					<h1>Registro de nuevos pagos </h1>
						<div class="row">
							<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
								<select id="txtProject" class="form-select form-select-sm " aria-label="Floating label select" data-mesage="Debes seleccionar un proyecto">
									<option value="0" selected>Selecciona proyecto</option>
								</select>
								<label for="txtProject">Proyecto</label>
							</div>
						</div>
						<div style="height:10px;"></div> <!-- Agregar un espacio -->
						<div class="row">
							<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
								<select id="txtCustomer" class="form-select form-select-sm " aria-label="Floating label select" data-mesage="Debes seleccionar un proyecto">
									<option value="0" selected>Selecciona Cliente</option>
								</select>
								<label for="txtCustomer">Cliente</label>
							</div>
						</div>

						<hr>
						<h6	class="nameProduct objet objHidden" style="font-size: 1rem; font-weight: bold; text-align: center">Producto</h6>
						<input type="hidden" id="txtIdProject" class="form-control">

						<!-- <input type="hidden" id="txtIdSerie" class="form-control">
						<input type="hidden" id="txtIdStatus" class="form-control">
						<input type="hidden" id="txtIdMaintain" class="form-control"> -->

						
						<div class="row ">    <!-- <div class="row objet objHidden"> -->
							<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
								<input id="txtFolio" type="text" class="form-control" data-mesage="Debes Agregar el monto" >
								<label for="txtFolio">Folio de documento</label>
							</div>

							<div class="col-md-8 col-lg-8 col-xl-8 mb-2 form-floating">
								<!-- <input id="txtCost" type="text" class="form-control form-control-sm  required" data-mesage="Debes Agregar el monto" > -->
								<input id="txtCost" type="number" class="form-control required" data-mesage="Debes Agregar el monto" >
								<label for="txtCost">Monto de adelanto</label>
							</div>

							<div class="col-md-12 col-lg-8 col-xl-8 mb-2 form-floating form__modal-group">
								<input type="text" id="txtPeriod"  name="txtPeriod" class="form-control form-control-sm text-left required" autocomplete="off" style="width:200px; height:38px">
								<i class="fas fa-calendar-alt icoTextBox" id="calendar"></i><br>
								<label for="txtPeriod">Fecha del documento</label>
								<span class="textAlert"><i class="fas fa-exclamation-triangle"></i> Agregar las fechas del documento</span>
							</div>

							<div style="height:20px;"></div> <!-- Agregar un espacio -->
	
							<div class="col-md-8 col-lg-8 col-xl-8 mb-2 form-floating">
								<select id="txtTypeMov" class="form-select form-select-sm  required" aria-label="Floating label select" data-mesage="Debes seleccionar el tipo de moneda">
									<option value="0" selected></option>
								</select>
								<label for="txtTypeMov">Tipo Movimiento</label>
							</div>

							<div class="row objet objHidden">
								<div class="col-md-6 col-lg-6 col-xl-6 mb-2 form-floating">
									<input id="txtRoot" type="text" class="form-control form-control-sm number" data-mesage="Debes Agregar los dias para la reparacion" >
									<label for="txtRoot">Cuenta Origen</label>
								</div>

								<div class="col-md-6 col-lg-6 col-xl-6 mb-2 form-floating">
									<input id="txtDest" type="text" class="form-control form-control-sm number" data-mesage="Debes Agregar las horas de reparacion" >
									<label for="txtDest">Cuenta Destino</label>
								</div>
							</div>

							<div class="row ">
								<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
									<textarea class="form-control form-control-sm" id="txtComments" style="height: 80px" rows="3"></textarea>
									<label for="txtComments">Comentarios</label>
								</div>
							</div>
							
						</div>
						<!-- <div class="row ">
							<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
								<select id="txtStatus" class="form-select form-select-sm  required" aria-label="Floating label select" data-mesage="Debes seleccionar el tipo de moneda">
									<option value="0" selected>Selecciona el estatus</option>
								</select>
								<label for="txtStatus">Estatus</label>
							</div>
						</div>
						<div class="row ">
							<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
								<select id="txtMotivo" class="form-select form-select-sm  required" aria-label="Floating label select" data-mesage="Debes seleccionar el tipo de moneda">
									<option value="0" selected>Selecciona el motivo de mantenimiento</option>
								</select>
								<label for="txtMotivo">Motivo de Mantenimiento</label>
							</div>
						</div> -->
						<div style="height:10px;"></div>
						<hr>
						<div class="row ">

								<div class="col-6">
									<button type="button" class="btn btn-sm btn-primary disabled" data_accion="add" id="btn_subletting">Registrar</button>
								</div>
								<div class="col-6">
									<button type="button" class="btn btn-sm btn-danger" data_accion="clean" id="btn_clean">Limpiar</button>
								</div>

						</div>
					</div>
				</div>

				<div class="mvst_table">
					<h1>Registros de Pagos adelantados para proyectos</h1>
					<table class="display compact nowrap"  id="tblPrePayment">
						<thead>
							<tr>
								<th style="width:  30px"></th>
								<th style="width: 150px">Cliente</th>
								<th style="width: 150px">Proyecto</th>
								<th style="width:  60px">Folio de <br>Documento</th>
								<th style="width:  40px">Monto</th>
								<th style="width:  60px">Fecha de <br>Documento</th>
								<th style="width:  60px">Status</th>
								<th style="width:  60px">Fecha <br>Registrado</th>
								<th style="width:  20px">Tipo <br>Movimiento</th>
								<th style="width:  50px">Cta Origen</th>
								<th style="width:  50px">Cta Destino</th>
								<th style="width: 250px">Comentarios</th>
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
<script src="<?=  PATH_VIEWS . 'PrePayments/PrePayments.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>