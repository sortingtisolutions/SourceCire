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
                        <div class="col-md-12 col-lg-12 col-xl-12 mb-2 form-floating">
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
                <div class="totales">
                    
                    <div class="totales__grupo">
                        <div class="totales__grupo-label">Totales del proyecto</div>
                        <div class="totales__grupo-dato tope"  id="totProject" >0.00</div>
                    </div>

                    <div class="totales__grupo">
                        <div class="totales__grupo-label">Totales Mantenimiento</div>
                        <div class="totales__grupo-dato  tope"  id="totMaintenance" >0.00</div>
                    </div>

                    <div class="totales__grupo">
                        <div class="totales__grupo-label">Totales Expendables</div>
                        <div class="totales__grupo-dato  tope"  id="totExpendab" >0.00</div>
                    </div>

                    <div class="totales__grupo">
                        <div class="totales__grupo-label">Diesel Extra</div>
                        <div class="totales__grupo-dato  tope"  id="totDiesel" >0.00</div>
                    </div>

                    <div class="totales__grupo">
                        <div class="totales__grupo-label">Descuento a aplicar</div>
                        <div class="totales__grupo-dato  tope"  id="totDiscount" >0.00</div>
                    </div>
                    
                    <div class="totales__grupo">
                        <div class="totales__grupo-label">Totales</div>
                        <div class="totales__grupo-dato tope"  id="totals" >0.00</div>
                    </div>
                        
                </div>
                <!-- Tabla de productos del proyecto -->
                    <div class="tabla__contenedor">
                        <table  id="tblProducts">
                            <thead>
                                <tr>
                                    <th class="cn"></th>
                                    <th class="lf">SKU</th>
                                    <th class="lf">Producto</th>
                                    <th class="cn">Cantidad</th>
                                    <th class="cn">Status</th>
                                    <th class="rg">Precio</th>
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
            
                <!-- <div class="comments__addNew" style="background-color: var(--br-gray-soft); padding: 0.5rem;">
                    <label for="txtComment">Escribe comentario</label><br>
                    <textarea name="txtComment" id="txtComment" cols="100" rows="5" class="invoiceInput" ></textarea><br>
                    <span class="invoice_button" id="newComment" style="background-color: var(--in-white) !important;"><i class="fas fa-plus"></i>guardar comentario</span>
                </div> -->
            
        </div>
    </div>

<script src="<?=  PATH_ASSETS . 'lib/functions.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_ASSETS . 'lib/dataTable/datatables.min.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_VIEWS . 'ProjectClosed/ProjectClosed.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>