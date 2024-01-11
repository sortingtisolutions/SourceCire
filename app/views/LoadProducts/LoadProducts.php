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
				<!-- Start área de formularios -->
				<div class="mvst_panel">
					<div class="form-group">
						<h4 id="titulo">Carga de Archivos CSV</h4>  
						<form id="formDocumento" class="row g-3 needs-validation" novalidate>

							<div class="row" hidden>
								<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                           			<input id="IdDocumento" name="IdDocumento" type="text" class="form-control form-control-sm" >
                           			<input id="IdDocumentNew" name="IdDocumentNew" type="text" class="form-control form-control-sm" >
									<input id="ExtDocumento" name="ExtDocumento" type="text" class="form-control form-control-sm" >
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                           	<div class="custom-file">
                           		<input type="file"  class="custom-file-input form-control form-control-sm" id="cargaFiles" >
                           </div>
                           </div>
							</div>

							<div class="row">
								<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
									<input id="NomDocumento" name="NomDocumento" type="text" class="form-control form-control-sm" style="text-transform:uppercase" required >
									<label for="NomDocumento">Nombre Documento</label>
								</div>
							</div>
							<div style="height:3px;"></div> <!-- Agregar un espacio -->
							<!-- <div class="row mt-4">
								<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
									<button type="button"  class="btn btn-secondary btn-sm btn-block" style="font-size: 0.8rem !important;" id="DescargarEjemplo">Descargar Ejemplo</button>
								</div>
							</div> -->

							<!-- <div class="row">
								<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
									<select id="selectRowTipoDocumento"  name="selectRowTipoDocumento"  class="form-select form-select-sm" autocomplete="off" required >
									</select>
									<label for="selectRowTipoDocumento" class="form-label">Tipo Documento</label>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
									<input id="fechaadmision" name="fechaadmision" type="date"  class="form-control form-control-sm" style="text-transform: uppercase" >
									<label for="fechaadmision">Fecha de Admision</label>
								</div>
							</div>

                    		<div class="row">
								<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
									<input id="CodDocumento" name="CodDocumento" type="text" class="form-control form-control-sm" style="text-transform: uppercase" >
									<label for="CodDocumento">Codigo Documento</label>
								</div>
							</div>
 -->
							<div class="row">
								<div class="col-6">
									<button type="button"  class="btn btn-primary btn-sm btn-block" style="font-size: 0.8rem !important;" id="GuardarDocumento">Cargar</button>
								</div>
								<div class="col-6">
									<button type="button"  class="btn btn-danger btn-sm btn-block" style="font-size: 0.8rem !important;" id="LimpiarFormulario">Limpiar</button>
								</div>
								<div class="col-6 objHidden">
									<button type="button"  class="btn btn-primary btn-sm btn-block" style="font-size: 0.8rem !important;" id="GuardarProcess">Procesar</button>
								</div>
								<div class="col-6 objHidden">
									<button type="button"  class="btn btn-danger btn-sm btn-block" style="font-size: 0.8rem !important;" id="LimpiarTabla">Limpiar Tabla</button>
								</div>
							</div>
							<div style="height:3px;"></div> <!-- Agregar un espacio -->
							<!-- <div class="row mt-4 ">
								<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
									<button type="button"  class="btn btn-secondary btn-sm btn-block objHidden" style="font-size: 0.8rem !important;" id="verMotivo">Motivo</button>
									<input id="IdErrores" name="IdErrores" type="hidden" class="form-control form-control-sm" >
								</div>
							</div> -->
						</form>
					</div>
				</div>
				<!-- End área de formularios -->

				<!-- Start área de listado -->
				<div class="mvst_table">
					<h1>Productos cargados</h1>

					<div class="row">
						<div class="col-12 col-md-12 ">		
								<table id="DocumentosTable" class="display  compact nowrap" style="width:95%">         
										<thead>
											<tr>
													<th style="width: 100px"></th>
													<th style="width: 100px">Resultado</th>
													<th style="width: 200px">SKU</th>
													<th style="width: 100px">Producto</th>
													<th style="width: 100px">Nombre en Ingles</th>
													<th style="width: 100px">Codigo de Proveedor</th>
													<th style="width: 100px">Nombre de Proveedor</th>
													<th style="width:  60px">Modelo</th>
													<th style="width: 100px">Precio</th>
													<th style="width:  60px">Moneda</th>
													<th style="width: 100px">Seguro</th>
													<th style="width: 100px">Servicio</th>
											</tr>
										</thead>
										<tbody id="tablaDocumentosRow">
										</tbody>
									</table>
							</div>
					</div>
				</div>
				<!-- End área de listado -->
			</div>
	</div>
</div>

<!-- Modal Borrar -->
<div class="modal fade" id="BorrarDocumentosModal" tabindex="-1" aria-labelledby="BorrarPerfilLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-sm">
					 <div class="modal-content">
					 <div class="modal-header ">
					 </div>
					 <div class="modal-body" style="padding: 0px !important;">


					 <div class="row">
						  <input hidden type="text" class="form-control" id="IdDocumentoBorrar" aria-describedby="basic-addon3">
						  <div class="col-12 text-center">
								<span class="modal-title text-center" style="font-size: 1.2rem;" id="BorrarPerfilLabel">¿Seguro que desea eliminar los datos?</span>
						  </div>
					 </div>

					 </div>
						  <div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
								<button type="button" class="btn btn-danger" id="BorrarProveedor">Borrar</button>
						  </div>
					 </div>
				</div>
		</div>

		<!-- Modal Borrar -->
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
						  	<span class="modal-title text-center" style="font-size: 1rem;">Ten en cuenta que solo se registraran los datos que tienen un estatus exitoso</span>
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

<!-- Modal Borrar -->
<div class="modal fade" id="filtroDocumentoModal" tabindex="-1" aria-labelledby="BorrarPerfilLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-sm">
					 <div class="modal-content">
					 <div class="modal-header ">
					 </div>
					 <div class="modal-body" style="padding: 0px !important;">


					 <div class="row">
						  <input hidden type="text" class="form-control" id="IdDocumentoBorrar" aria-describedby="basic-addon3">
						  <div class="col-12 text-center">
								<span class="modal-title text-center" style="font-size: 1rem;" >Solo se aceptan archivos con extencion csv</span>
								
						  </div>
					 </div>

					 </div>
						  <div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
						  </div>
					 </div>
				</div>
		</div>


</div>
<!-- loading -->
<div class="invoice__loading modalLoading">
            <div class="box_loading">
                <p class="text_loading">
                    Cargando Datos de Productos<br>
                    <i class="fas fa-spinner spin"></i> 
                    </p>
                <p>Se estan actualizando los registros en la tabla de productos, este proceso podria tomar algunos segundos</p>
            </div>
        </div>
<!-- Modal para imprimir folio de salida -->
<div class="modal fade" id="MoveFolioModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
            <div class="modal-header ">
            </div>
            <div class="modal-body" style="padding: 0px !important;">

            <div class="row">
                <div class="col-12 text-center">
                    <span class="modal-title text-center" style="font-size: 1.2rem;" id="BorrarPerfilLabel">Se registraron: <span id='aceptados'></span>, exitosamente</span>
					<span class="modal-title text-center" style="font-size: 1.2rem;" id="BorrarPerfilLabel">Fueron rechazados: <span id='rechazados'></span>. </span> 
                </div>
				<div class="col-12 text-center">
					<span class="modal-title text-center" style="font-size: 0.8rem;" id="duplicidad"></span>
                </div>
				<div class="col-12 text-center">
					<span class="modal-title text-center" style="font-size: 0.8rem;" id="sku"></span>
				</div>
				<div class="col-12 text-center">
					 <span class="modal-title text-center" style="font-size: 0.8rem;" id="moneda"></span>
				</div>
				<div class="col-12 text-center">
					<span class="modal-title text-center" style="font-size: 0.8rem;" id="costo"></span>
				</div>
				<div class="col-12 text-center">
					<span class="modal-title text-center" style="font-size: 0.8rem;" id="servicio"></span>
				</div>
				<div class="col-12 text-center">
					<span class="modal-title text-center" style="font-size: 0.8rem;" id="seguro"></span> 
				</div>
				<div class="col-12 text-center">
					<span class="modal-title text-center" style="font-size: 0.8rem;" id="categories"></span> 
				</div>
            </div>

            </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-primary" id="btnPrintReport">Imprimir</button> -->
                    <button type="button" class="btn btn-secondary" id="btnHideModal">Cerrar</button>
                </div>
            </div>
    </div>
</div>

<!-- MODAL PARA PODER VISUALIZAR LOS MOTIVOS DE ERROR-->
<!-- 
<div class="modal fade" id="MotivosModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
            <div class="modal-header ">
				<h2>DESCRIPCIÓN DEL ERROR</h2>
            </div>
            <div class="modal-body" style="padding: 0px !important;">

            <div class="row">

				<div class="col-12">
					<span class="modal-title objHidden" style="font-size: 0.8rem;" id="codigo-1"> 1. El SKU introducido, puede no tener una categoria o subcategoria existente en la base de datos, o los últimos 3 caracteres NO son númericos.</span>
                </div>
				<div class="col-12">
					<span class="modal-title objHidden" style="font-size: 0.8rem;" id="codigo-2"> 2. El SKU introducido, esta existente en la tabla de productos.</span>
				</div>
				<div class="col-12">
					 <span class="modal-title objHidden" style="font-size: 0.8rem;" id="codigo-3"> 3. No se encontro ninguna subcategoria relacionada con los datos introducidos en el SKU. </span>
				</div>
				<div class="col-12">
					<span class="modal-title objHidden" style="font-size: 0.8rem;" id="codigo-4"> 4. El tipo de moneda es incorrecto, revise contar con una estructura similar a 'MXN' y que esta moneda este registrado en el modulo de moneda.</span>
				</div>
				<div class="col-1">
					<span class="modal-title objHidden" style="font-size: 0.8rem;" id="codigo-5"> 5. El costo introducido NO es númerico, por favor revisar que lo sea.</span>
				</div>
				<div class="col-12">
					<span class="modal-title objHidden" style="font-size: 0.8rem;" id="codigo-6"> 6. El servicio introducido NO es númerico.</span> 
				</div>
				<div class="col-12">
					<span class="modal-title objHidden" style="font-size: 0.8rem;" id="codigo-7"> 7. El seguro solo puede tener dos valores '1' o '0', por favor de revisar.</span> 
				</div>
				
            </div>

            </div>
                <div class="modal-footer">
                   
                    <button type="button" class="btn btn-secondary btnHideModal" id="btn_hide_modal">Cerrar</button>
                </div>
            </div>
    </div>
</div> -->

<div class="overlay_background overlay_hide" id="MotivosModal" style="width: 60%; left:25%;">
        <div class="overlay_modal">
            <div class="overlay_closer"><span class="title"></span><span class="btn_close">Cerrar</span></div>
<!--             <button type="button" class="btn btn-sm btn-primary" id="btn_save">Aplicar Cambio</button>
            <div style="height:15px;"></div>  -->
            <table class="display compact nowrap"  id="tblMotivos" style="width: 100%">
                <thead>
                    <tr>
                        <th style="width:  30px">Codigo</th>
                        <th style="width: 180px">Descripcion del Error</th>			
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
<script src="<?=  PATH_ASSETS . 'lib/bs-custom-file-input.min.js' ?>"></script>

<script src="<?=  PATH_ASSETS . 'lib/functions.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/dataTable/datatables.min.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_VIEWS . 'LoadProducts/LoadProducts.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>