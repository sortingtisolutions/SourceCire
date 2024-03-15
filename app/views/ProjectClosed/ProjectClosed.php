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
            <div class="mvst_panel" style="width:280px; background-color: #EAFC98">
                <div class="form-group" >

                    <div class="row">
                        <label for="txtGroupProjects" style="font-size: 16px; font-weight: bold; ">Grupo de proyectos</label>
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating" style="display:flex; gap:1rem; font-size:12px; margin-top:10px; border-radius:4px; ">
                            
                            <div class="" style="margin-left:5px; margin-top:5px; margin-bottom:15px;">
                                <input class="form-check-input checkTipe" type="radio" name="RadioConceptos" id="RadioConceptos1" val="1" checked>
                                <label class="form-check-label" for="RadioConceptos1">
                                Proyecto individual
                                </label>
                            </div>
                            <div class="" style="margin-left:15px; margin-top:5px; margin-bottom:15px;">
                                <input class="form-check-input checkTipe" type="radio" name="RadioConceptos" id="RadioConceptos2" val="2" >
                                <label class="form-check-label" for="RadioConceptos2">
                                Proyecto Padre
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating" style="margin-top: 5px;">
                            <select id="txtProjects" class="form-select form-select-sm required"><option value="0" data-content="||||" selected>Selecciona el proyecto</option></select>
                            <label for="txtProjects">Lista de proyectos</label>
                        </div>
                    </div>

                    <div class="row list-finder pos3 hide-items">
                            <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                                <input id="txtDiscount" type="text" class="form-control form-control-sm " autocomplete="off">
                                <label for="txtDiscount">Descuento despues de Entrada</label>                    
                            </div>
                            <div id="listInvoice" class="list-group list-hide">
                                <div class="list-items" ></div>
                            </div>
                    </div>

                    <div class="row list-finder pos3 hide-items"> 
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating ">   <!-- pos5 hide-items -->
                        <input id="txtDiesel" type="text" class="form-control form-control-sm text number" >
                            <label for="txtDiesel">Extras Diesel</label>
                        </div>
                     
                    </div>
                   
                    <div class="row list-finder pos1 hide-items">
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
                            <textarea class="form-control form-control-sm" id="txtComments" style="height:5rem; text-transform:uppercase" autocomplete="off" rows="3"></textarea>
                            <label for="txtComments">Comentarios al documento</label>
                        </div>
                    </div>
                        <div class="row pos1 hide-items">
                            <div class="col-md-12 mb-5">
                                <button id="btn_exchange" type="button" class="btn btn-sm btn-primary" >Agregar</button>
                            </div>
                        </div>
                    </div>
                    <div style="height:10px;"></div>

                    <div class="row list-finder pos1 hide-items">
                        <div class="col-md-6 col-lg-6 col-xl-6 mb-2 form-floating">
                            <select id="txtReport" class="form-select form-select-sm required">
                                <option value="1" selected>Si</option>
                                <option value="2">No</option>
                            </select>
                            <label for="txtReport">Desglosar Paquetes</label>
                        </div>
                    </div>
                    <div class="row list-finder pos1 hide-items">
                        <div class="col-md-6 col-lg-6 col-xl-6 mb-2 form-floating">
                            <select id="txtIva" class="form-select form-select-sm required">
                                <option value="0.16" selected>16%</option>
                                <option value="0">0%</option>
                                <option value="0">Sin Iva</option>
                            </select>
                            <label for="txtIva">IVA</label>
                        </div>
                    </div>

                    <div style="height:20px;"></div>
                    <div class="row">
                        <div class="col-6">
                            <button type="button"  class="btn btn-primary btn-sm btn-block" style="font-size: 0.7rem !important;" id="PrintClosure">Imprimir Cierre</button>
                        </div>
                        <div class="col-6">
                            <button type="button"  class="btn btn-primary btn-sm btn-block" style="font-size: 0.7rem !important;" id="GuardarClosure">Cerrar Proyecto</button>
                        </div>
                       
					</div>
            </div>
        <!-- Sidebar -->

        <!-- contenido de operación -->
            <div class="mvst_table projectClosed">
                <h1>Detalles del Proyecto</h1>
                    <div class="sidebar__comments" style="position: absolute;right: 15px;"> 
                        <span class="invoice_button toComment">
                            <i class="far fa-comment-alt"></i> Comentarios del proyecto
                        </span> 
                    </div>
                <!-- caja de totales del reporte -->
                <div class="totales" style="height: 150px;">
                    <div class="" style="display: contents; ">
                        <div class="totales__grupo" style="display: inline-block;">
                            <div class="totales__grupo-label">Totales Equipo Base</div>
                            <div class="totales__grupo-dato  tope"  id="totBase" >0.00</div>
                        </div>
                        
                        <div class="totales__grupo" style="display: inline-block;">
                            <div class="totales__grupo-label">Totales Equipo Extra</div>
                            <div class="totales__grupo-dato  tope"  id="totExtra" >0.00</div>
                        </div>
                        
                        <div class="totales__grupo" style="display: inline-block;">
                            <div class="totales__grupo-label">Totales Equipo x Día</div>
                            <div class="totales__grupo-dato  tope"  id="totDias" >0.00</div>
                        </div>
                        
                        <div class="totales__grupo" style="display: inline-block;">
                            <div class="totales__grupo-label">Totales de Subarrendos</div>
                            <div class="totales__grupo-dato  tope"  id="totSubarrendo" >0.00</div>
                        </div>
                        <div class="totales__grupo" style="display: inline-block;">
                            <div class="totales__grupo-label">Totales del proyecto</div>
                            <div class="totales__grupo-dato tope"  id="totProject" >0.00</div>
                        </div>
                        <div class="totales__grupo" style="display: inline-block;">
                            <div class="totales__grupo-label">Totales del proyecto con IVA</div>
                            <div class="totales__grupo-dato tope"  id="totProjectIva" >0.00</div>
                        </div>
                        <div style="height:10px; width: 10% "></div><!-- Agregar un espacio -->
                        <div class="totales__grupo" style="display: inline-block;">
                            <div class="totales__grupo-label">Totales Mantenimiento</div>
                            <div class="totales__grupo-dato  tope"  id="totMaintenance" >0.00</div>
                        </div>

                        <div class="totales__grupo" style="display: inline-block;">
                            <div class="totales__grupo-label">Totales Expendables</div>
                            <div class="totales__grupo-dato  tope"  id="totExpendab" >0.00</div>
                        </div>

                        <div class="totales__grupo" style="display: inline-block;">
                            <div class="totales__grupo-label">Total de Diesel Extra</div>
                            <div class="totales__grupo-dato  tope"  id="totDiesel" >0.00</div>
                        </div>

                        <div class="totales__grupo" style="display: inline-block;">
                            <div class="totales__grupo-label">Descuento a aplicar</div>
                            <div class="totales__grupo-dato  tope"  id="totDiscount" >0.00</div>
                        </div>

                        <div class="totales__grupo" style="display: inline-block;">
                            <div class="totales__grupo-label">Monto de Prepago </div>
                            <div class="totales__grupo-dato  tope"  id="totPrepago" >0.00</div>
                        </div>
                        
                        <div class="totales__grupo" style="display: inline-block;">
                            <div class="totales__grupo-label">Monto total a pagar</div>
                            <div class="totales__grupo-dato tope"  id="totals" >0.00</div>
                        </div>
                    </div>
                </div>
                
                <!-- Tabla de productos del proyecto -->
                    <div class="tabla__contenedor" style="height: 1000px; padding: 3rem 0px 0px 0px">
                        <table  id="tblProducts">
                            <thead>
                                <tr>
                                    <th class="cn"></th>
                                    <th class="lf">SKU</th>
                                    <th class="lf">Producto</th>
                                    <th class="cn">Cantidad</th>
                                    <th class="cn">Status</th>
                                    <th class="cn">Precio</th>
                                    <th class="rg">Proyecto</th>
                                    <th class="lf">Comentarios individuales</th>
                                </tr>
                            </thead>
                            <tbody><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tbody>
                        </table>
                    </div>
                <!-- Tabla de productos del proyecto -->
            </div>        
        <!-- contenido de operación -->
        </div>
    </div>
</div>

<div class="modal fade" id="starClosure" tabindex="-1" aria-labelledby="BorrarPerfilLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
            <div class="modal-header ">
            </div>
            <div class="modal-body" style="padding: 0px !important;">

            <div class="row">
                <input type="hidden" class="form-control" id="txtIdClosure" aria-describedby="basic-addon3">
                <div class="col-12 text-center">
                    <span class="modal-title text-center" style="font-size: 1.2rem;" id="BorrarPerfilLabel">¿Estas seguro de Cerrar el proyecto?</span>
                </div>
            </div>

            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="btnClosure">Si</button>
                </div>
            </div>
    </div>
</div>

<!-- Start Ventana modal AGREGA O MODIFICA PRODUCTO -->
<div class="overlay_background overlay_hide"id="ProductModal" style="width:55%; left:25%; height:70%;">
    <div class="overlay_modal">
        <div class="overlay_closer"><span class="title"></span><span class="btn_close">Cerrar</span></div>
        <div class="formButtons">
            <button type="button" class="btn btn-sm btn-primary" id="btn_save">Guardar</button>
        </div>
        <div class="formContent">
            <table id="tblEditProduct">
                <tr>
                    <td class="concept"><span class="reqsign">&nbsp;</span> Comentario al producto:</td>
                    <td class="data">
                        <input type="text" id="txtCommentPrd" name="txtPrdCodeProvider" class="textbox" style="width:300px; text-transform:uppercase">
                        <span class="fail_note hide"></span>
                        <span class="intructions">Comentario individual al Producto seleccionado</span>
                    </td>
                </tr>

            </table>
        </div>
    </div>
</div>
<!-- End Ventana modal AGREGA O MODIFICA PRODUCTO -->
<!-- Fondo obscuro -->
<div class="invoice__modalBackgound"></div>
<!-- Modal General  -->
<div class="invoice__modal-general invoice-border modalTable">
        <div class="modal__header invoice-border">
            <div class="modal__header-concept" style="font-weight: 700">&nbsp;Listados de productos</div>
            <i class="far fa-window-close closeModal"></i>
        </div>
        <div class="modal__body">
        </div>
    </div>
    <div id="commentsTemplates" class="table_hidden box_template">
        <div class="comments__box" style=" width: 100%; height: 100%; padding: 1.1rem; display: grid; grid-template-rows: 1fr 170px;">
            
            <div class="comments__list" style="border: 1px solid var(--br-gray-soft); border-radius: 0.5rem; margin-bottom: 0.5rem; padding: 0.5rem; overflow-y: scroll;">
            </div>
            
        </div>
    </div>

<script src="<?=  PATH_ASSETS . 'lib/functions.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/dataTable/datatables.min.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_VIEWS . 'ProjectClosed/ProjectClosed.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>