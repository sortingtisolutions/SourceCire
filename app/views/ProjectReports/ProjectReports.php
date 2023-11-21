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

        <!-- Sidebar -->
            <div class="mvst_panel" style="width:280px; background-color: #e2edf3">
                <div class="form-group" >
                    <div class="row">
                        <h1>Condiciones del Reporte</h1>
                        <label for="txtProjects"></label>
                    </div>
                    
                    <!-- <div class="row">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                            <select id="txtProjects" class="form-select form-select-sm required"><option value="0" data-content="||||" selected>Selecciona el proyecto</option></select>
                            <label for="txtProjects">Lista de proyectos</label>
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                            <input id="fechaInitial" name="fechaInitial" type="date"  class="form-control form-control-sm required" style="text-transform: uppercase" >
                            <label for="fechaInitial">Fecha Inicial</label>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                            <input id="fechaEnd" name="fechaEnd" type="date"  class="form-control form-control-sm required" style="text-transform: uppercase" >
                            <label for="fechaEnd">Fecha Final</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                            <select id="txtAnalyst" class="form-select form-select-sm"><option value="0" data-content="||||" selected>Selecciona analista</option></select>
                            <label for="txtAnalyst">Analista</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                            <select id="txtClient" class="form-select form-select-sm"><option value="0" data-content="||||" selected>Selecciona el cliente</option></select>
                            <label for="txtClient">Cliente</label>
                        </div>
                    </div>
                    <div class="row objHidden">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                            <select id="txtSupplier" class="form-select form-select-sm">
                                <option value="0" selected>Selecciona proveedor</option>
                            </select>
                            <label for="txtSupplier">Proveedor</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                            <select id="txtReport" class="form-select form-select-sm required">
                                <option value="1" selected>Proyectos Activos</option>
                                <option value="2">Patrocinios</option>
                                <option value="3">Cierres</option>
                                <option value="4">Equipo mas rentado</option>
                                <option value="5">Proyectos Trabajados</option>
                                <option value="6">Equipo menos rentado</option>
                                <option value="7">Subarrendos</option>
                                <option value="8">Proveedores de subarrendo</option>
                                <option value="9">Clientes nuevos</option>
                                <option value="10">Productividad</option>
                                <option value="11">Proyectos por programador</option>
                            </select>
                            <label for="txtReport">Tipo de Reporte</label>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                            <textarea class="form-control form-control-sm" id="txtComments" style="height:5rem; text-transform:uppercase" autocomplete="off" rows="3"></textarea>
                            <label for="txtComments">Comentarios al cierre</label>
                        </div>
                    </div> -->
                    <div style="height:20px;"></div>
<!-- 
                    <div class="row"><h1>Seleccionar nivel de detalles</h1></div> -->

                    <!-- <div class="totales"> -->
                        <!-- <div> -->

                            <!-- <div class="totales__grupo">
                                <div class="totales__grupo-label">Activos</div>
                                <div><input class="form-check-input check-box-prj" type="checkbox" value="1" id="checkBudget" ></div>

                            </div>
                            
                            <div class="totales__grupo">
                                <div class="totales__grupo-label">Patrocinios</div>
                                <div><input class="form-check-input check-box-prj" type="checkbox" value="2" id="checkPatrocinios"></div>

                            </div>
                            <div class="totales__grupo">
                                <div class="totales__grupo-label">Productividad</div>
                                <div><input class="form-check-input check-box-prj" type="checkbox" value="10" id="checkProductivity"></div>

                            </div>
                            
                            <div class="totales__grupo">
                                <div class="totales__grupo-label">Eq. más rentado</div>
                                <div><input class="form-check check-box-prj" type="checkbox" value="4" id="checkPlans" ></div>

                            </div> -->
                        <!-- </div> -->
                        <!--<div>-->
                    <!-- 
                            <div class="totales__grupo">
                                <div class="totales__grupo-label">Proyectos trabajados</div>
                                <div><input class="form-check-input check-box-prj" type="checkbox" value="5" id="checkProjWorked"></div>

                            </div>
                            <div class="totales__grupo">
                                <div class="totales__grupo-label">Equipo menos rentado</div>
                                <div><input class="form-check-input check-box-prj" type="checkbox" value="6" id="checkInCall" ></div>

                            </div>
                            <div class="totales__grupo">
                                <div class="totales__grupo-label">Subarrendos</div>
                                <div><input class="form-check check-box-prj" type="checkbox" value="7" id="checkProyects" ></div>

                            </div>
                            <div class="totales__grupo">
                                <div class="totales__grupo-label">Proveedores de subarrendo</div>
                                <div><input class="form-check-input check-box-prj" type="checkbox" value="8" id="checkSubletting"></div>

                            </div>
                            <div class="totales__grupo">
                                <div class="totales__grupo-label">Cierres</div>
                                <div><input class="form-check-input check-box-prj" type="checkbox" value="3" id="checkClosed"></div>

                            </div>
                            <div class="totales__grupo">
                                <div class="totales__grupo-label">Clientes nuevos</div>
                                <div><input class="form-check-input check-box-prj" type="checkbox" value="9" id="checkNewCustomers"></div>

                            </div>

                           
                            <div class="totales__grupo">
                                <div class="totales__grupo-label">Por programador</div>
                                <div><input class="form-check-input check-box-prj" type="checkbox" value="11" id="checkProjDevelop"></div>
                        
                            </div> -->
                            
                        <!--</div>-->
                    <!-- <div style="height:20px;"></div> -->
                    <!-- </div> -->
                        <div class="row pos1">
                            <div class="col-md-12 mb-5">
                                <button id="btn_generate" type="button" class="btn btn-sm btn-primary" >Generar</button>
                                <button id="btn_print" type="button" class="btn btn-sm btn-primary" >Imprimir</button>
                            </div>
                        </div>
                    </div>
            </div>
        <!-- Sidebar -->

        <div class="container-fluid products">
            <div class="contenido">
                <div class="row mvst_group">
                    <div class="mvst_list projectClosed">
                        <div class="row rowTop">
                        <h1>Resultado de la consulta</h1>
                        </div>
                        <table class="display compact nowrap"  id="tblProducts" style="min-width: 1480px">
                            <thead>
                                <tr>
                                    <th style="width:  200px" >Proyecto</th>
                                    <th style="width:  50px"  >Clientes</th>
                                    <th style="width:  100px"  >Programador encargado</th>
                                    <th style="width:  30px"  >Tipo de LLamado</th>
                                    <th style="width:  40px"  >Ubicación</th>
                                    <th style="width:  40px" >Locación</th>
                                    <th style="width:  50px" >CFDI de Traslado con carta porte</th>
                                    <th style="width:  80px"  >Descuento aplicado</th>
                                    <th style="width:  100px" >Prueba de cámara y look</th>
                                    <th style="width:  100px" >Cargas</th>
                                    <th style="width:  200px">Descargas</th>
                                    <th style="width:  80px" >Con encargado</th>
                                    <th style="width:  100px">Proyectos que llevan transportes</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid objHidden divSecond">
            <div class="contenido">
                <div class="row mvst_group">
                    <div class="mvst_list projectClosed">
                        <div class="row rowTop">
                        <h1>EQUIPO MÁS RENTADO</h1>
                        </div>
                        <table class="display compact nowrap"  id="tblEquiposMasUsados" style="min-width: 1480px">
                            <thead>
                                <tr>
                                    <th style="width:  250px">Producto</th>
                                    <th style="width:  20px" >Numero de serie</th>
                                    <th style="width:  100px">Proyecto asignado</th>
                                    <th style="width:  10px" >Tiempo de uso</th>
                                    <th style="width:  10px" >Locación</th>
                                    <th style="width:  10px" >Cantidad de rentas</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid objHidden divMenosRentado">
            <div class="contenido">
                <div class="row mvst_group">
                    <div class="mvst_list projectClosed">
                        <div class="row rowTop">
                        <h1>EQUIPO MENOS RENTADO</h1>
                        </div>
                        <table class="display compact nowrap"  id="tblEquiposMenosUsados" style="min-width: 1480px">
                            <thead>
                                <tr>
                                    <th style="width:  250px">Producto</th>
                                    <th style="width:  20px" >SKU</th>
                                    <th style="width:  100px">Tiempo sin uso</th>
                                    <th style="width:  100px">Último proyecto asignado</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid objHidden divSubarrendos">
            <div class="contenido">
                <div class="row mvst_group">
                    <div class="mvst_list projectClosed">
                        <div class="row rowTop">
                        <h1>SUBARRENDOS</h1>
                        </div>
                        <table class="display compact nowrap"  id="tblSubarrendos" style="min-width: 1480px">
                            <thead>
                                <tr>
                                    <th style="width:  100px" >Producto</th>
                                    <th style="width:  20px" >SKU</th>
                                    <th style="width:  50px" >Num de serie</th>
                                    <th style="width:  100px" >Proyecto asignado</th>
                                    <th style="width:  20px" >Tiempo de uso</th>
                                    <th style="width:  100px" >Proveedor</th>
                                    <th style="width:  50px" >Locación</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid objHidden divProvSubarrendo">
            <div class="contenido">
                <div class="row mvst_group">
                    <div class="mvst_list projectClosed">
                        <div class="row rowTop">
                        <h1>PROVEEDORES DE SUBARRENDO</h1>
                        </div>
                        <table class="display compact nowrap"  id="tblProvSubarrendo" style="min-width: 1480px">
                            <thead>
                                <tr>
                                    <th style="width:  250px">Proveedor</th>
                                    <th style="width:  50px">Cantidad de subarrendo</th>
                                    <th style="width:  50px">Equipos subarrendados</th>
                                    <th style="width:  50px">Periodo de subarrendo</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid objHidden divCustomers">
            <div class="contenido">
                <div class="row mvst_group">
                    <div class="mvst_list projectClosed">
                        <div class="row rowTop">
                        <h1>CLIENTES NUEVOS</h1>
                        </div>
                        <table class="display compact nowrap"  id="tblCustomers" style="min-width: 1480px">
                            <thead>
                                <tr>
                                    <th style="width:  250px">Cliente</th>
                                    <th style="width:  250px">Proyecto</th>
                                    <th style="width:  60px">Monto</th>
                                    <th style="width:  50px">Rango del proyecto</th>
                                    <th style="width:  40px">Contacto</th>
                                    <th style="width:  100px">Programador</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid objHidden divProvSubarrendo">
            <div class="contenido">
                <div class="row mvst_group">
                    <div class="mvst_list projectClosed">
                        <div class="row rowTop">
                        <h1>Productividad</h1>
                        </div>
                        <table class="display compact nowrap"  id="tblProductivity" style="min-width: 1480px">
                            <thead>
                                <tr>
                                    <th style="width:  250px">Programador</th>
                                    <th style="width:  50px">Cantidad de proyectos</th>
                                    <th style="width:  50px">Cotización</th>
                                    <th style="width:  50px">Presupuesto</th>
                                    <th style="width:  50px">Proyecto</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid objHidden divProjDevelop">
            <div class="contenido">
                <div class="row mvst_group">
                    <div class="mvst_list projectClosed">
                        <div class="row rowTop">
                        <h1>Proyectos por programador</h1>
                        </div>
                        <table class="display compact nowrap"  id="tblProjDevelop" style="min-width: 1480px">
                            <thead>
                                <tr>
                                    <th style="width:  150px">Programador</th>
                                    <th style="width:  250px" >Proyecto</th>
                                    <th style="width:  50px">Tipo</th>
                                    <th style="width:  50px" >Fechas</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid objHidden divProjWorked">
            <div class="contenido">
                <div class="row mvst_group">
                    <div class="mvst_list projectClosed">
                        <div class="row rowTop">
                        <h1>Proyectos trabajados</h1>
                        </div>
                        <table class="display compact nowrap"  id="tblProjWorked" style="min-width: 1480px">
                            <thead>
                                <tr>
                                    <th style="width:  250px">Cliente</th>
                                    <th style="width:  250px">Proyecto</th>
                                    <th style="width:  50px">Tipo</th>
                                    <th style="width:  20px">Descuento</th>
                                    <th style="width:  100px">Programador</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid objHidden divPatrocinios">
            <div class="contenido">
                <div class="row mvst_group">
                    <div class="mvst_list projectClosed">
                        <div class="row rowTop">
                        <h1>Patrocinios</h1>
                        </div>
                        <table class="display compact nowrap"  id="tblPatrocinios" style="min-width: 1480px">
                            <thead>
                                <tr>
                                    <th style="width:  250px">Cliente</th>
                                    <th style="width:  100px" >Proyecto</th>
                                    <th style="width:  50px">Tipo</th>
                                    <th style="width:  50px">Fechas</th>
                                    <th style="width:  20px">Descuento</th>
                                    <th style="width:  100px">Programador</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid objHidden divCierres">
            <div class="contenido">
                <div class="row mvst_group">
                    <div class="mvst_list projectClosed">
                        <div class="row rowTop">
                        <h1>CIERRES</h1>
                        </div>
                        <table class="display compact nowrap"  id="tblCierres" style="min-width: 1480px">
                            <thead>
                                <tr>
                                    <th style="width:  250px">Cliente</th>
                                    <th style="width:  250px">Proyecto</th>
                                    <th style="width:  20px">Tipo</th>
                                    <th style="width:  50px">Fechas</th>
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
    
</div>

<script src="<?=  PATH_ASSETS . 'lib/functions.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/dataTable/datatables.min.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_VIEWS . 'ProjectReports/ProjectReports.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>