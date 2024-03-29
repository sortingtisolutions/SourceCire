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
				<div class="mvst_panel" style="background-color: #E8DC9F">
					<div class="form-group">
						<h4 id="titulo">Nuevo Usuario</h4> 						 
						<form id="formUsuario" class="row g-3 needs-validation" novalidate>

							<div class="row hide">
								<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
									<input id="EmpIdUsuario" name="EmpIdUsuario" type="text" class="form-control form-control-sm"  autocomplete="off">
									<input id="lastDate"     name="lastDate"     type="text" class="form-control form-control-sm"  autocomplete="off">
									<input id="userRegistry" name="userRegistry" type="text" class="form-control form-control-sm"  autocomplete="off">
									<input id="IdUsuario"    name="IdUsuario"    type="text" class="form-control form-control-sm"  autocomplete="off">
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
									<input id="NomUsuario" name="NomUsuario" type="text" class="form-control form-control-sm"  autocomplete="off" required >
									<label for="NomUsuario">Nombre Usuario</label>
								</div>
							</div>

                    		 <div class="row">
								<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
									<input id="UserNameUsuario" name="tuti" type="text" class="form-control form-control-sm"  autocomplete="none" required >
									<label for="UserNameUsuario">UserName</label>
								</div>
							</div>

                    		 <div class="row" id="PassUsuarioRow">
								<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
									<input id="PassUsuario" name="Pato" type="password" class="form-control form-control-sm" autocomplete="off" >
									<label for="PassUsuario">Password </label>
								</div>
							</div>


                     		<div class="row">
								<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
									<select id="AreaEmpUsuario" class="form-select form-select-sm"><option value="0" selected>Selecciona el area</option></select>
									<label for="AreaEmpUsuario">Area</label>
								</div>
							</div>


							<div class="row">
								<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
									<input id="NumEmpUsuario" name="NumEmpUsuario" type="text" class="form-control form-control-sm"  autocomplete="off" required >
									<label for="NumEmpUsuario">Numero Empleado</label>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
									<input id="empEmail" name="empEmail" type="text" class="form-control form-control-sm"  autocomplete="off" required >
									<label for="empEmail">Email del empleado</label>
								</div>
							</div>

							<div class="row">
									<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
										<div class="input-group">
											<select class="form-select form-select-sm" id="selectPerfilUsuario" name="selectPerfilUsuario" required>
											</select>

											<div class="input-group-append">
												<button id="modulesUserPerfil" class="btn btn-primary btn-Edit-Perfil" type="button" data-bs-toggle="collapse" data-target="#modulesUserColapse" data-bs-target="#modulesUserColapse" aria-expanded="false" aria-controls="modulesUserColapse">Editar</button>
											</div>
										</div>
									</div>
							</div>

							<div class="row">
									<div class="col-md-12 col-lg-12 col-xl-12 form-floating">
										<div class="collapse" id="modulesUserColapse">
											<div class="card card-body" style="padding: .4rem !important;">
											<div class="row">
												<div class="col-12 text-center colorSecundario" style="font-weight: 900 !important;">
													Modulos:
												</div>
											</div>
											<div class="row">
												<div class="col-12 col-md-12 ">
													<div class="col-12 text-center colorSecundario" style="font-weight: 600 !important;">
														Disponibles para asignar
													</div>
													<div class="card listContainer">
														<div class="card-body card-body-add">
														<div class="list-group" id="listDisponible">
														</div>
														</div>
													</div>
												</div>
												<div class="col-12 col-md-12 ">
													<div class="col-12 text-center colorSecundario" style="font-weight: 600 !important;">
														Asignados
													</div>
													<div class="card listContainer">
														<div class="card-body card-body-add">
														<div class="list-group" id="listAsignado">                     
														</div>
														</div>
													</div>
												</div>
											</div>
											</div>
										</div>
									</div>

							</div>

                            <div class="row">
								<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
									<select id="selectRowUserReporta"  name="selectRowUserReporta"  class="form-select form-select-sm" ></select>
									<label for="selectRowUserReporta" class="form-label">Usuario reporta</label>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
									<select id="selectRowPuestos"  name="selectRowPuestos"  class="form-select form-select-sm" ></select>
									<label for="selectRowPuestos" class="form-label">Puesto</label>
								</div>
							</div>

							<div class="row">
								<div class="col-6">
									<button type="button" class="btn btn-primary btn-sm btn-block" style="font-size: 1rem !important;" id="GuardarUsuario">Guardar</button>
								</div>
								<div class="col-6">
									<button type="button" class="btn btn-danger btn-sm btn-block" style="font-size: 1rem !important;" id="LimpiarFormulario">Limpiar</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<!-- End área de formularios -->

				<!-- Start área de listado -->
				<div class="mvst_table">
					<h1>Datos principales de los usuarios</h1>

					<div class="row">
						<div class="col-12 col-md-12">		
								<table id="usuariosTable" class="display nowrap" style="width:100%">         
										<thead>
											<tr>
												<th style="width: 30px"></th>
												<th style="width: 30px">Id</th>
												<th style="width: auto">Nombre Emp</th>
												<th style="width: auto">Numero Emp</th>
												<th style="width: auto">Email Empleado</th>
												<th style="width: auto">Perfil </th>
												<th style="width: auto">User name</th>
												<th style="width: auto">Ultimo Acceso</th>
												<th style="width: auto">Ultimo Registro</th>
											</tr>
										</thead>
										<tbody id="tablaUsuariosRow">
										</tbody>
									</table>
							</div>
					</div>
				</div>
				<!-- End área de listado -->
			</div>
	</div>
</div>

<!-- Modal Editar Usuario -->
<div class="modal fade" id="EditarUsuariosModal" tabindex="-1" aria-labelledby="BorrarPerfilLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-sm">
					 <div class="modal-content">
					 <div class="modal-header ">
					 </div>
					 <div class="modal-body" style="padding: 0px !important;">

					 <div class="row">
						  <input hidden type="text" class="form-control" id="IdUsuarioEditar" aria-describedby="basic-addon3">
						  <div class="col-12 text-center">
								<span class="modal-title text-center" style="font-size: 1rem;" id="EditarPerfilLabel">Editar no permite ver la contraseña, si agrega una nueva y le da guardar se sustituira por la nueva agregada</span>
						  </div>
					 </div>

					 </div>
						  <div class="modal-footer">
								<!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button> -->
								<button type="button" class="btn btn-secondary" id="ContinueUsuario">Continuar</button>
						  </div>
					 </div>
				</div>
		</div>
</div>


<!-- Modal Borrar -->
<div class="modal fade" id="BorrarUsuariosModal" tabindex="-1" aria-labelledby="BorrarPerfilLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-sm">
					 <div class="modal-content">
					 <div class="modal-header ">
					 </div>
					 <div class="modal-body" style="padding: 0px !important;">


					 <div class="row">
						  <input hidden type="text" class="form-control" id="IdUsuarioBorrar" aria-describedby="basic-addon3">
						  <div class="col-12 text-center">
								<span class="modal-title text-center" style="font-size: 1.0rem;" id="BorrarPerfilLabel">Va a eliminar usuario y sus privilegios ¿Seguro desea borrarlo?</span>
						  </div>
					 </div>

					 </div>
						  <div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
								<button type="button" class="btn btn-danger" id="BorrarUsuario">Borrar</button>
						  </div>
					 </div>
				</div>
		</div>
</div>

<script src="<?=  PATH_ASSETS . 'lib/functions.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/dataTable/datatables.min.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_VIEWS . 'Usuarios/Usuarios.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>