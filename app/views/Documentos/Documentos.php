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
						<h4 id="titulo">Alta de nuevo Documento</h4>  
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

							<div class="row">
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

							<div class="row">
								<div class="col-6">
									<button type="button"  class="btn btn-primary btn-sm btn-block" style="font-size: 0.8rem !important;" id="GuardarDocumento">Guardar</button>
								</div>
								<div class="col-6">
									<button type="button"  class="btn btn-danger btn-sm btn-block" style="font-size: 0.8rem !important;" id="LimpiarFormulario">Limpiar</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<!-- End área de formularios -->

				<!-- Start área de listado -->
				<div class="mvst_table">
					<h1>Lista de documentos disponibles</h1>

					<div class="row">
						<div class="col-12 col-md-12">		
								<table id="DocumentosTable" class="display  display compact nowrap" style="width:95%">         
										<thead>
											<tr>
													<th style="width: 35px"></th>
													<th style="width: 20px" hidden>Id</th>
													<th style="width: 200px">Nombre</th>
													<th style="width: 100px" hidden>id Tipo Documento</th>

													<th style="width: 100px">Tipo Documento</th>
													<th style="width: 100px">Codigo</th>
													<th style="width:  60px">Tipo</th>
													<th style="width: 100px">Fecha Admision</th>
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
								<span class="modal-title text-center" style="font-size: 1.2rem;" id="BorrarPerfilLabel">¿Seguro que desea borrarlo?</span>
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
<div class="modal fade" id="filtroDocumentoModal" tabindex="-1" aria-labelledby="BorrarPerfilLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-sm">
					 <div class="modal-content">
					 <div class="modal-header ">
					 </div>
					 <div class="modal-body" style="padding: 0px !important;">


					 <div class="row">
						  <input hidden type="text" class="form-control" id="IdDocumentoBorrar" aria-describedby="basic-addon3">
						  <div class="col-12 text-center">
								<span class="modal-title text-center" style="font-size: 1rem;" >Solo se aceptan archivos con extencion JPG,PNG,PDF</span>
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

<script src="<?=  PATH_ASSETS . 'lib/bs-custom-file-input.min.js' ?>"></script>

<script src="<?=  PATH_ASSETS . 'lib/functions.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/dataTable/datatables.min.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_VIEWS . 'Documentos/Documentos.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>