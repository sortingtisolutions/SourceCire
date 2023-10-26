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
        <div class="row">
            <div class="block_01">
                <div class="row" >
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 blocks" style="width: 100%">
                        <span class="titleSection">Datos Generales del Proyecto</span>
                        <table class="table_information form-floating " >
                            <tr>
                                <td class="formSales">
                                </td>
                            </tr>
                        
                            <tr>
                                <td class="formSales" >

                                    <form id="formSales" >
                                        <div class="form_group">
                                            <label for="lstProject">Nombre de proyecto:</label> 
                                            <select id="lstProject" name="lstProject" >
                                                <option value="">Selecciona...</option>
                                            </select>
                                        </div>

                                        <div class="form_group">
                                            <label for="txtCustomer">Nombre Cliente:</label> 
                                            <input id="txtCustomer" name="txtCustomer" type="text" class="textbox required">
                                        </div>

                                        <div class="form_group">
                                            <label for="txtDateStar">Fecha Inicio Proyecto:</label> 
                                            <input id="txtDateStar" name="txtDateStar" type="text" class="textbox required">
                                        </div>

                                        <div class="form_group">
                                            <label for="txtDateEnd">Fecha final Proyecto:</label> 
                                            <input id="txtDateEnd" name="txtDateEnd" type="text" class="textbox required">
                                        </div>

                                        <div class="form_group">
                                            <label for="txtRepresen">Representante Legal:</label> 
                                            <input id="txtRepresen" name="txtRepresen" type="text" class="textbox required">
                                        </div>

                                        <div class="form_group">
                                            <label for="txtAdress">Domicilio:</label> 
                                            <input id="txtAdress" name="txtAdress" type="text" class="textbox required">
                                        </div>

                                        <div class="form_group">
                                            <label for="txtRespProg">Analista Programacion:</label> 
                                            <input id="txtRespProg" name="txtRespProg" type="text" class="textbox required">
                                        </div>

                                        <!-- <div class="form_group">
                                            <label for="lstPayForm">tipo de pago:</label> 
                                            <select id="lstPayForm" name="lstPayForm" class="required">
                                                <option value="">Selecciona...</option>
                                                <option value="EFECTIVO">EFECTIVO</option>
                                                <option value="TARJETA DE CREDITO">TARJETA DE CREDITO</option>
                                            </select>
                                        </div> -->

                                       <!--  <div class="form_group hide">
                                            <label for="txtProject">Proyecto:</label> 
                                            <input id="txtProject" name="txtProject" type="text" class="textbox">
                                        </div> -->
                                        
                                        <div class="form_group hide">
                                            <label for="txtInvoice">Num Factura:</label> 
                                            <input id="txtInvoice" name="txtInvoice" type="text" class="textbox">
                                        </div>

                                    </form>

                                </td>
                            </tr>
 
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 blocks">
                        <div class="block_01-02">
                            <div class="menu_control"></div>
                            <div id="tbl_dynamic" class="tbl_dynamic"></div>
                        </div>   
                    </div>
                </div>

            </div>

            <!-- <div class="block_02">
                <div class="blocks">
                    <button class="btn-add" id="newQuote"> + nueva venta</button>
                </div>
                <hr>
                <div class="blocks total">
                    <div class="half left concepto">Subtotal</div>
                    <div class="half right total dato" id="total">0</div>
                </div>
                <div class="blocks total">
                    <div class="half left concepto">IVA</div>
                    <div class="half right total dato" id="total">0</div>
                </div>
                <div class="blocks total">
                    <div class="half left concepto">Total</div>
                    <div class="half right total dato" id="total">0</div>
                </div>
                <div class="blocks total">
                    <div class="half left concepto">&nbsp;</div>
                    <div class="half right total dato" >&nbsp;</div>
                </div>
                <div class="blocks">
                    <div class="half left concepto">Num. productos</div>
                    <div class="half right dato" id="ttlproducts">0</div>
                </div>

                <div class="blocks">
                    <div class="full text_center">
                        <button class="bn enable" id="addPurchase"> Guardar Cambio</button>
                    </div>
                </div>
                <div class="blocks">
                    <button class="btn-add" id="newComment"> + agregar observaciones</button>
                </div>
                <hr>
            </div> -->
        </div>
    </div>
</div>


<!-- Start Ventana modal AGREGA O MODIFICA PRODUCTO -->
<div class="overlay_background overlay_hide"id="newValuesModal" style="width: 70%; left:25%;" >
        <div class="overlay_modal">
            <div class="overlay_closer"><span class="title"></span><span class="btn_close">Cerrar</span></div>
            <div class="formButtons">
                <button type="button" class="btn btn-sm btn-primary" id="btn_save">Guardar</button>
            </div>
            <div class="formContent">
                <table id="tblNewValues">

                    <tr >
                        <td class="concept"> Nombre del Proyecto:</td>
                        <!-- <td class=""> -->
                            <!--         <div class="row list-finder pos2 "> -->
                                <td class="data">
                                    <input id="txtProducts" type="text" class="textbox" style="width:250px;" autocomplete="off" disabled>
                                    <span class="fail_note hide"><i class="fas fa-arrow-left"></i> Campo requerido</span>
                                    <span class="intructions">Nombre correspondiente al producto</span>
                                </td>
<!--                                 <div id="listProduct" class="list-group list-hide">
                                    <div class="list-items" ></div>
                                </div>
                            </div> -->
                            <!-- <span class="fail_note hide"></span>
                            <span style="font-size: 0.7em; color: #999999; margin-bottom: 10px;	margin-top: 2px;" >Listado de productos para crear un accesorio</span> -->
                        <!-- </td> -->
                    </tr>

                    <tr>
                        <td class="concept"><span class="reqsign">*</span> Total del Proyecto:</td>
                        <td class="data">
                            <input type="hidden" id="txtPrdId" name="txtPrdId" autocomplete="nope" >
                            <input type="text" id="txtPrdName" name="txtPrdName" class="textbox required" style="width:150px; text-transform:uppercase" autocomplete="off">
                            <span class="fail_note hide"><i class="fas fa-arrow-left"></i> Campo requerido</span>
                            <span class="intructions">Nombre correspondiente al producto</span>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="concept"><span class="reqsign">*</span> Total Mantenimiento:</td>
                        <td class="data">
                            <input type="text" id="txtPrdSku" name="txtPrdSku" class="textbox" style="width:150px" autocomplete="nope">
                            <span class="fail_note hide"><i class="fas fa-arrow-left"></i> Campo requerido</span>
                            <span class="intructions">SKU del producto</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="concept"><span class="reqsign">&nbsp;</span> Total Expendables:</td>
                        <td class="data">
                            <input type="text" id="txtPrdModel" name="txtPrdModel" class="textbox" style="width:150px" autocomplete="nope">
                            <span class="fail_note hide"></span>
                            <span class="intructions">Modelo del producto</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="concept"><span class="reqsign">&nbsp;</span> Total Diesel:</td>
                        <td class="data">
                            <input type="text" id="txtPrdPrice" name="txtPrdPrice" class="textbox" style="width:150px" autocomplete="nope">
                            <span class="fail_note hide"></span>
                            <span class="intructions">Precio de renta del producto</span>
                        </td>
                    </tr>

                    <tr>
                        <td class="concept"><span class="reqsign">&nbsp;</span> Monto Descuento:</td>
                        <td class="data">
                            <input type="text" id="txtPrdCodeProvider" name="txtPrdCodeProvider" class="textbox" style="width:150px;" autocomplete="nope">
                            <span class="fail_note hide"></span>
                            <span class="intructions">Código del producto definido por el proveedor</span>
                        </td>
                    </tr>

                    <tr>
                        <td class="concept"><span class="reqsign">&nbsp;</span> Total Documento:</td>
                        <td class="data">
                            <input type="text" id="txtPrdNameProvider" name="txtPrdCodeProvider" class="textbox" style="width:150px;" autocomplete="nope" disabled>
                            <span class="fail_note hide"></span>
                            <span class="intructions">Nombre descriptivo segun el proveedor</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

<!-- Start Lista de productos -->
<div class="box_list_products" id="Products" >
    <div class="sel_product" contenteditable="true"></div>
    <div class="list_products">
        <ul></ul>
    </div>
</div>
<!-- End Lista de productos -->

<script src="<?=  PATH_ASSETS . 'lib/functions.js?v=1.0.0.0' ?>"></script>
<script src="<?=  PATH_VIEWS . 'ClosedProyectChange/ClosedProyectChange.js?v=1.0.0.0' ?>"></script>

<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>