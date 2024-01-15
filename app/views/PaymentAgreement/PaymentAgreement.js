var seccion = '';
let folio, mthseries;
let = pr = [];
let = link = '';

$(document).ready(function () {
    // folio = getFolio();
    if (verifica_usuario()) {
        inicial();
    }
});

//INICIO DE PROCESOS
function inicial() {
    getExchange();
    getStores();
    getCoins();
    getCategories();
    setting_table();

    $('#btn_exchange').on('click', function () {
        exchange_apply(0);
    });

    $('#txtCost').on('blur', function () {
        let costo_import =parseFloat($('#txtCostImp').val());
        let cant = parseInt($('#txtQuantity').val());
        let costo_uni = parseFloat($('#txtCost').val());
        
        validator();
        
        if (costo_import) {
            //$('#txtCostTot').val((costo_import+costo_uni)*cant);
            if (costo_uni && costo_import) {
                $('#txtCostTot').val((costo_import+costo_uni)*cant);
            }else{
                if(!costo_uni){
                    $('#txtCostTot').val((costo_import)*cant);
                }
                if (!costo_import) {
                    $('#txtCostTot').val((costo_uni)*cant);
                }
                
            }
        } else {
            if (costo_uni) {
                $('#txtCostTot').val(costo_uni*cant);
            }else{
                $('#txtCostTot').val(0);
            }
            
        }
        // console.log($('#txtCostImp').val(), costo_uni);
    });
    $('#txtCostImp').on('blur', function () {
        let costo_import = parseInt($('#txtCostImp').val());
        let cant = parseInt($('#txtQuantity').val());
        let costo_uni = parseInt($('#txtCost').val());
        let costoTotal = costo_import + cant + costo_uni;
        
        if (costo_import) {
            
            if (costo_uni && costo_import) {
                console.log(cant);
                $('#txtCostTot').val((costo_import+costo_uni)*cant);
            }else{
                if(!costo_uni){
                    $('#txtCostTot').val((costo_import)*cant);
                }
                if (!costo_import) {
                    $('#txtCostTot').val((costo_uni)*cant);
                } 
            }
        } else {
            if (costo_uni) {
                $('#txtCostTot').val(costo_uni*cant);
            }else{
                $('#txtCostTot').val(0);
            }
            
        }
        
    });
    $('#txtSerie').on('blur', function () {
        validator();
    });

    $('#txtQuantity').on('blur', function () {
        let costo_import =parseFloat($('#txtCostImp').val());
        let cant = parseInt($('#txtQuantity').val());
        let costo_uni = parseFloat($('#txtCost').val());
        let costoTotal = costo_import + costo_uni;
        validator();
       // $('#txtCostTot').val(costo_uni*cant);
       if (costo_import) {
            //$('#txtCostTot').val((costo_import+costo_uni)*cant);
            if (costo_uni && costo_import) {
                $('#txtCostTot').val((costo_import+costo_uni)*cant);
            }else{
                if(!costo_uni){
                    $('#txtCostTot').val((costo_import)*cant);
                }
                if (!costo_import) {
                    $('#txtCostTot').val((costo_uni)*cant);
                }
                
            }
        } else {
            if (costo_uni) {
                $('#txtCostTot').val(costo_uni*cant);
            }else{
                $('#txtCostTot').val(0);
            }
            
        }
    });
}
// Setea de la tabla
function setting_table() {
    let title = 'Entradas de Almacen';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');

    $('#tblExchanges').DataTable({
        order: [[0, 'desc']],
        dom: 'Blfrtip',
        lengthMenu: [
            [200, 400, -1],
            [200, 400, 'Todos'],
        ],
        buttons: [
        {
            //Botón para Excel
            extend: 'excel',
            footer: true,
            title: title,
            filename: filename,

            //Aquí es donde generas el botón personalizado
            text: '<button class="btn btn-excel"><i class="fas fa-file-excel"></i></button>',
        },
        {
            //Botón para PDF
            extend: 'pdf',
            footer: true,
            title: title,
            filename: filename,

            //Aquí es donde generas el botón personalizado
            text: '<button class="btn btn-pdf"><i class="fas fa-file-pdf"></i></button>',
        },
        {
            //Botón para imprimir
            extend: 'print',
            footer: true,
            title: title,
            filename: filename,

            //Aquí es donde generas el botón personalizado
            text: '<button class="btn btn-print"><i class="fas fa-print"></i></button>',
        },
        {
            // Boton aplicar cambios
            text: 'Aplicar movimientos',
            className: 'btn-apply hidden-field',
            action: function (e, dt, node, config) {
                read_exchange_table();
            },
        },
        ],
        pagingType: 'simple_numbers',
        language: {
            url: 'app/assets/lib/dataTable/spanish.json',
        },
        scrollY: 'calc(100vh - 190px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
            {data: 'editable', class: 'edit'},
            {data: 'prod_sku', class: 'sku'},
            {data: 'prodname', class: 'product-name'},
            {data: 'prodcant', class: 'quantity'},
            {data: 'prodcost', class: 'price left'},
            {data: 'comments', class: 'comments'},
        ],
    });
    $('#addButtonSegm')
    .unbind('click')
    .on('click', function () {
        // console.log('Agregar a TBL');
        putSegments();
    });

    // $('#btn_saveSegment')
    // .unbind('click')
    // .on('click', function (){
    //     let user = Cookies.get('user').split('|');
    //     let em = user[3];
    //     $('#listTable tbody tr').each(function (v, u) {
    //         let lnumpay=$($(u).find('td')[1]).text();
    //         let lfrecpay=$($(u).find('td')[2]).text();
    //         let lcantpay=$($(u).find('td')[3]).text();
    //         let ldatepay=$($(u).find('td')[4]).text();

    //         let truk = `${lnumpay}|${ldatepay}|${lcantpay}|${glbpjtid}|${numcloid}|${em}`;
    //         console.log('TRUK ',truk);
    //         build_data_structure(truk);
    //     });
    // });

    // $('#addSegmentModal .btn_close')
    //     .unbind('click')
    //     .on('click', function () {
    //         $('#addSegmentModal').addClass('overlay_hide');

    // });
}

// Solicita los tipos de movimiento
function getExchange() {
    var pagina = 'PaymentAgreement/listExchange';
    var par = '[{"parm":""}]';
    var tipo = 'json';
    var selector = putFrecuency;
    fillField(pagina, par, tipo, selector);
}
// Solicita el listado de almacenes
function getStores() {
    var pagina = 'PaymentAgreement/listStores';
    var par = '[{"parm":""}]';
    var tipo = 'json';
    var selector = putProyects;
    fillField(pagina, par, tipo, selector);
}
// // Solicita los provedores
// function getSuppliers() {
//     var pagina = 'PaymentAgreement/listSuppliers';
//     var par = `[{"store":""}]`;
//     var tipo = 'json';
//     //var selector = putSuppliers;
//     var selector = putSupplierList;
//     fillField(pagina, par, tipo, selector);
// }
// // Solicita los documentos factura
// function getInvoice(id) {
//     console.log(id);
//     var pagina = 'PaymentAgreement/listInvoice';
//     var par = `[{"extId":"${id}"}]`;
//     var tipo = 'json';
//     var selector = putInvoiceList;
//     fillField(pagina, par, tipo, selector);
// }
// // Solicita los documentos factura
// function getCoins() {
//     var pagina = 'PaymentAgreement/listCoins';
//     var par = `[{"store":""}]`;
//     var tipo = 'json';
//     var selector = putCoins;
//     fillField(pagina, par, tipo, selector);
// }
// // Solicita las categorias
// function getCategories() {
//     console.log('categos');
//     var pagina = 'PaymentAgreement/listCategories';
//     var par = `[{"store":""}]`;
//     var tipo = 'json';
//     var selector = putCategories;
//     fillField(pagina, par, tipo, selector);
// }
// // Solicita los productos de un almacen seleccionado
// function getProducts(catId) {
//     var pagina = 'PaymentAgreement/listProducts';
//     var par = `[{"catId":"${catId}"}]`;
//     var tipo = 'json';
//     var selector = putProducts;
//     fillField(pagina, par, tipo, selector);
// }
// Solicita los movimientos acurridos

/*  LLENA LOS DATOS DE LOS ELEMENTOS */
// Dibuja los tipos de movimiento
function putFrecuency(dt) {
    // console.log(dt);
    // if (dt[0].ext_id != 0) {
    //     $.each(dt, function (v, u) {
    //         if (u.ext_elements.substring(0, 1) != '0') {
    //             let H = `<option value="${u.ext_id}" data-content="${u.ext_code}|${u.ext_type}|${u.ext_link}|${u.ext_code_a}|${u.ext_type_a}|${u.ext_elements}">${u.ext_code} - ${u.ext_description}</option>`;
    //             $('#txtFrecuency').append(H);
    //         }
    //     });
    // }

    $('#txtFrecuency').on('change', function () {
        let id = $(this).val();
        id == 4 ? code='1011' : code='1101';
        
        setting_interface(code,id);
        // relocation_products();
        // validator();

        $('#txtCostImp').val(0);
        $('#txtCostProy').val(0);
        $('#txtCostTot').val(0);
    });
}
/**  ++++++  configura la interfasede inputs requeridos */
function setting_interface(code,id) {
    // console.log('CODE ', code,'-', id);
    code.substring(1, 2) == '0' ? $('.pos1').addClass('hide-items') : $('.pos1').removeClass('hide-items');
    code.substring(2, 3) == '0' ? $('.pos2').addClass('hide-items') : $('.pos2').removeClass('hide-items');
    code.substring(3, 4) == '0' ? $('.pos3').addClass('hide-items') : $('.pos3').removeClass('hide-items');
    code.substring(4, 5) == '0' ? $('.pos4').addClass('hide-items') : $('.pos4').removeClass('hide-items');
}

// Dibuja los almacenes
function putProyects(dt) {
    if (dt[0].pjt_id != 0) {
        $.each(dt, function (v, u) {
            let H = `<option value="${u.pjt_id}">${u.pjt_name}</option>`;
            $('#txtProyects').append(H);
        });
    }

    $('#txtProyects').on('change', function () {
        validator();
    });
}

function putCoins(dt) {
    if (dt[0].cin_id != 0) {
        $.each(dt, function (v, u) {
            let H = `<option value="${u.cin_id}">${u.cin_code} - ${u.cin_name}</option>`;
            $('#txtCoin').append(H);
        });
    }

    $('#txtCoin').on('change', function () {
        validator();
    });
}

function putCategories(dt) {
    if (dt[0].cat_id != 0) {
        $.each(dt, function (v, u) {
            let H = `<option value="${u.cat_id}"> ${u.cat_name}</option>`;
            $('#txtCategory').append(H);
        });

        $('#txtCategory').on('change', function () {
            let catId = $(this).val();
            /* NOTA EN EL CAMPO DE PRODUCTOS PARA QUE NO ESCRIBAN */
            $('#txtProducts').val('   Cargando Informacion . . . .');
            getProducts(catId);
        });
    }
}






function putSegments(){ 
    let MontoTot=parseFloat($('#txtMontoTotSeg').val().replace(/,/g, ''));
    let cantFrec =$('#txtFrecuency').val();
    let cantSegm =$('#txtSegment').val();
    let frecDesc =$(`#txtFrecuency option[value="${cantFrec}"]`).text();
    let cantDesc =$(`#txtSegment option[value="${cantSegm}"]`).text();
    let numpedido;
    let montoInd= parseFloat(MontoTot) / parseFloat(cantSegm);
    montoInd =montoInd.toFixed(2);

    let unittime='';
    switch (cantFrec) {
        case '01':
            unittime='w';
            break;
        case '2':
            unittime='M';
            break;
        case '3':
            unittime='Q';
            break;
        case '4':
            unittime='y';
            break;
        default:
    }
    // let hoy=moment(Date()).format('DD/MM/YYYY');
    let Period = $('#txtPeriodPayed').val();
    let DateStart = moment(Period,'DD/MM/YYYY').format('YYYY-MM-DD');

    for (var i = 0; i < cantSegm; i++){
        numpedido=parseFloat(i) + 1;
        fechapago=moment([DateStart],'YYYY-MM-DD').add(numpedido, unittime).format('YYYY-MM-DD');
        par = ` [{
                    "montind"   : "${montoInd}",
                    "numpay"    : "${numpedido}",
                    "valfrec"   : "${frecDesc}",
                    "datepay"   : "${fechapago}"
                }]`;

        // console.log(par);
        fill_table(par);
    }
}

function fill_table(par) { //** AGREGO ED */

    par = JSON.parse(par);
    let tabla = $('#listTable').DataTable();
    tabla.row
        .add({
            editable: `<i class="fas fa-times-circle kill" id ="md${par[0].numpay}"></i>`,
            numpay:     par[0].numpay,
            frepay:     par[0].valfrec,
            cantpay:    par[0].montind,
            datepay:    par[0].datepay,
        })
        .draw();

    $('#md' + par[0].numpay).parents('tr').attr('id', par[0].numpay).attr('data-content', 1);

    $('.kill')
        .unbind('click')
        .on('click', function () {
            tabla.row($(this).parent('tr')).remove().draw();
    });
}










// Almacena los registros de productos en un arreglo
function putProducts(dt) {
    var ps = $('#txtProducts').offset();
    $('#listProducts .list-items').html('');
    //console.log(dt);
    $('#listProducts').css({top: ps.top + 30 + 'px'});
    $('#listProducts').slideUp('100', function () {
        $('#listProducts .list-items').html('');
    });

    $.each(dt, function (v, u) {
        let H = `<div class="list-item" id="P-${u.prd_id}" data_serie="${u.serNext}" data_complement="${u.prd_sku}|${u.prd_name}|${u.prd_id}">${u.prd_sku}-${u.prd_name}</div>`;
        $('#listProducts .list-items').append(H);
    });

    /* QUITA NOTA EN EL CAMPO DE PRODUCTOS */
    $('#txtProducts').val('');
    
    $('#txtProducts').on('focus', function () {
        $('#listProducts').slideDown('slow');
    });

    $('#listProducts').on('mouseleave', function () {
        $('#listProducts').slideUp('slow');
    });

    $('#txtProducts').keyup(function (e) {
        var res = $(this).val().toUpperCase();
        if (res == '') {
            $('#listProducts').slideUp(100);
        } else {
            $('#listProducts').slideDown(400);
        }
        res = omitirAcentos(res);
        sel_products(res);
    });

    $('#listProducts .list-item').on('click', function () {
        console.log('SELECC-',$(this).text().split('-')[0].slice(10,11), $(this).text().split('-')[0].slice(7,10));
        console.log('data_complement-',$(this).attr('data_complement'))
        let prdNm = $(this).html();
        let prdId = $(this).attr('id') + '|' + $(this).attr('data_complement');
        let serie = $(this).attr('data_serie');
        let accesorio = $(this).text().split('-')[0].slice(7,10);
        console.log('prdId-',prdId)
        
        $('#txtProducts').val(prdNm);
        $('#txtIdProducts').val(prdId);
        $('#txtNextSerie').val(serie);
        $('#txtPrice').val($(this).attr('data_complement').split('|')[3]);
        $('#txtCoinType').val($(this).attr('data_complement').split('|')[4]);
        $('#listProducts').slideUp(100);
        validator();
        
    });
}
// AGREGA LAS FACTURAS CON TEXTO SELECTIVO
function putInvoiceList(dt) {
    //console.log(dt);
    var fc = $('#txtInvoice').offset();
    $('#listInvoice .list-items').html('');

    //$('.list-group #listInvoice').css({top: fc.top + 40 + 'px'});
    $('#listInvoice').css({top: fc.top + 30 + 'px'});
    $('#listInvoice').slideUp('100', function () {
        //$('.list-group #listInvoice').slideUp('100', function () {
        $('#listInvoice .list-items').html('');
    });

    $.each(dt, function (v, u) {
        let H = `<div class="list-item" id="${u.doc_id}" data_complement="${u.doc_id}|${u.doc_name}">${u.doc_name}</div>`;
        $('#listInvoice .list-items').append(H);
    });

    $('#txtInvoice').on('focus', function () {
        //$('.list-group #listInvoice').slideDown('slow');
        $('#listInvoice').slideDown('slow');
    });

    $('#listInvoice').on('mouseleave', function () {
        $('#listInvoice').slideUp('slow');
    });

    $('#txtInvoice').keyup(function (e) {
        var res = $(this).val().toUpperCase();
        if (res == '') {
            $('#listInvoice').slideUp(100);
        } else {
            $('#listInvoice').slideDown(400);
        }
        res = omitirAcentos(res);
        sel_invoice(res);
    });

    $('#listInvoice .list-item').on('click', function () {
        let prdNm = $(this).html();
        let prdId = $(this).attr('id');
        //console.log(prdId);
        $('#txtInvoice').val(prdNm);
        $('#txtIdInvoice').val(prdId);
        $('#listInvoice').slideUp(100);
        validator();
    });
}

// CARGA LA INFORMACION DE LOS PROVEEDORES DE PRODUCTOS
function putSupplierList(dt) {
    var sl = $('#txtSuppliers').offset();
    $('#listSupplier .list-items').html('');
    //console.log(sl);
    $('#listSupplier').css({top: sl.top + 30 + 'px'});
    $('#listSupplier').slideUp('100', function () {
        $('#listSupplier .list-items').html('');
    });

    $.each(dt, function (v, u) {
        let H = `<div class="list-item" id="${u.sup_id}" data_complement="${u.sup_id}|${u.sup_business_name}">${u.sup_business_name}</div>`;
        $('#listSupplier .list-items').append(H);
    });

    $('#txtSuppliers').on('focus', function () {
        $('#listSupplier').slideDown('fast');
    });

    $('#listSupplier').on('mouseleave', function () {
        $('#listSupplier').slideUp('fast');
    });

    $('#txtSuppliers').keyup(function (e) {
        var res = $(this).val().toUpperCase();
        if (res == '') {
            $('#listSupplier').slideUp(100);
        } else {
            $('#listSupplier').slideDown(400);
        }
        res = omitirAcentos(res);
        sel_suppliers(res);
    });

    $('#listSupplier .list-item').on('click', function () {
        let prdNm = $(this).html();
        let prdId = $(this).attr('id');
        //console.log('selecciona elemento', prdId,'---', prdNm);
        $('#txtSuppliers').val(prdNm);
        $('#txtIdSuppliers').val(prdId);
        $('#listSupplier').slideUp(100);
        validator();
    });
}

// reubica el input de los productos
function relocation_products() {
    var ps = $('#txtProducts').offset();
    $('#listProducts').css({top: ps.top + 30 + 'px'});
}

// Valida los campos
function validator() {
    let ky = 0;
    let msg = '';

    if ($('#txtFrecuency').val() == 0) {
        ky = 1;
        msg += 'Debes seleccionar un tipo de movimiento';
    }

    if ($('#txtProyects').val() == 0 && $('.pos1').attr('class').indexOf('hide-items') < 0) {
        ky = 1;
        msg += 'Debes seleccionar un almacen destino';
    }
    // COMENTADO TEMPORALMENTE POR JJR
    /* if ($('#txtSuppliers').val() == 0 && $('.pos2').attr('class').indexOf('hide-items') < 0) {
        // && $('.pos2').attr('class').indexOf('hide-items') < 0
        ky = 1;
        msg += 'Debes seleccionar el proveedor';
    } */
    // COMENTADO TEMPORALMENTE POR JJR
    /* if ($('#txtIdInvoice').val() == 0 && $('.pos3').attr('class').indexOf('hide-items') < 0) {
        ky = 1;
        msg += 'Debes seleccionar un producto';
    }
 */
    if ($('#txtIdProducts').val() == 0 && $('.pos1').attr('class').indexOf('hide-items') < 0) {
        ky = 1;
        msg += 'Debes seleccionar un producto';
    }
    // COMENTADO TEMPORALMENTE POR JJR
    /* if ($('#txtCoin').val() == 0 && $('.pos5').attr('class').indexOf('hide-items') < 0) {
        ky = 1;
        msg += 'Debes indicar el tipo de moneda';
    } */
            //console.log(ky, msg);

            // if ($('#txtCost').val() == 0 && $('.pos5').attr('class').indexOf('hide-items') < 0) {
            //     ky = 1;
            //     msg += 'Debes indicar el costo del producto';
            // }

    //validacion de cantidad para agregar serie mayor a 1
    if ($('#txtQuantity').val() > 1) {
        // && $('#txtSerie').val() == 0
        $('#txtSerie').attr('disabled', true).val('');
        $('#txtNoEco').attr('disabled', true).val('');
        
    } else if ($('#txtQuantity').val() == 1) {
        $('#txtSerie').attr('disabled', false);
        $('#txtNoEco').attr('disabled', false);

    } else {
        ky = 1;
        msg += ' Las series se capturan individualmente en la tabla';
    }

            //if ($('#txtSerie').val() == 0 && $('.pos6').attr('class').indexOf('hide-items') < 0) {
            //console.log($('#txtSerie').val(), $('#txtSerie').attr('disabled'));

    // COMENTADO TEMPORALMENTE POR JJR
    /* if ($('#txtSerie').val() == '' && $('#txtSerie').attr('disabled') == undefined && $('.pos6').attr('class').indexOf('hide-items') < 0) {
        ky = 1;
        msg += 'Debes indicar la serie del producto';
    } */

    if (ky == 0) {
        $('#btn_exchange').removeClass('disabled');
    } else {
        $('#btn_exchange').addClass('disabled');
        //console.clear();
        //console.log(msg);
    }
}

// Aplica la seleccion para la tabla de movimientos
function exchange_apply() {
    let prdId = $('#txtIdProducts').val().split('|')[0].substring(2, 100);
    let prdSku = $('#txtIdProducts').val().split('|')[1];
    let prdName = $('#txtIdProducts').val().split('|')[2];
    let prdidacc = $('#txtIdProducts').val().split('|')[3];
    console.log('ID-ACC',prdidacc);
    let serie = parseInt($('#txtNextSerie').val());
    let sersku;
    let serser = $('#txtSerie').val();
    let prodpeti = $('#txtPedimento').val();
    let prodimpo = $('#txtCostImp').val();
    let sercost = $('#txtCost').val();
    let sercoin = $('#txtCoin').val();
    let quantity = $('#txtQuantity').val();
    let supplier = $('#txtIdSuppliers').val();
    let suppliernm = $('#txtSuppliers').val();
    let docinvoice = $('#txtIdInvoice').val();
    let docinvoicenm = $('#txtInvoice').val();
    let excId = $('#txtFrecuency').val();
    let exccode = $(`#txtFrecuency option[value="${excId}"]`).attr('data-content').split('|')[0];
    let strid = $('#txtProyects').val();
    let strName = $(`#txtProyects option[value="${strid}"]`).text();
    //let prodmarc = $('#txtMarca').val();
    let comment = $('#txtComments').val();
    let serbran = $('#txtMarca').val();
    let sercostimp = $('#txtCostImp').val();
    let serpetimp = $('#txtPedimento').val();
    let sercosttot = $('#txtCostTot').val();
    let sernumeco = $('#txtNoEco').val();

     // Modificar para el caso de accesorios a base de la longitud de los sku
    
    mthseries=quantity;
    if (quantity > 1) {
        for (var i = 0; i < quantity; i++) {
            // sersku = prdSku + refil(serie++, 3);
            if(prdSku.length==7){
                sersku= prdSku + refil(serie++, 3);
            }else{
                sersku = prdSku + 'XXX' + refil(serie++, 2);
                console.log(sersku);
            }
            update_array_products(prdId, serie); // REVISAR EL DETALLE DE ESTA FUNCION
            let par = `
            [{
                "support"       : "${prdId}|${excId}|${strid}|${sersku}|${sercoin}|${supplier}|${docinvoice}|${prdidacc}",
                "sersku"        : "${sersku}",
                "prodser"       : "${serser.toUpperCase()}",
                "prodpeti"      : "${prodpeti}",
                "prodimpo"      : "${prodimpo}",
                "sercost"       : "${sercost}",
                "prodnme"       : "${prdName}",
                "prodqty"       : "${'1'}",
                "excodsr"       : "${exccode}",
                "stnmesr"       : "${strName}",
                "provname"      : "${suppliernm}",
                "factname"      : "${docinvoicenm}",
                "comment"       : "${comment}",
                "serbran"       : "${serbran}",
                "sercostimp"    : "${sercostimp}",
                "serpetimp"     : "${serpetimp}",
                "sercosttot"    : "${sercosttot}",
                "sernumeco"     : "${sernumeco}"
            }]`;
            fill_table(par); 
        }
    } else {
        
        if(prdSku.length==7){
            sersku= prdSku + refil(serie, 3);
        }else{
            
            sersku = prdSku + 'XXX' + refil(serie++, 2);
            // console.log(sersku);
        }
        serie++;
        let par = `
        [{
            "support"  : "${prdId}|${excId}|${strid}|${sersku}|${sercoin}|${supplier}|${docinvoice}|${prdidacc}",
            "sersku"   : "${sersku}",
            "prodser"  : "${serser.toUpperCase()}",
            "sercost"  : "${sercost}",
            "prodnme"  : "${prdName}",
            "prodqty"  : "${quantity}",
            "excodsr"  : "${exccode}",
            "stnmesr"  : "${strName}",
            "provname" : "${suppliernm}",
            "factname" : "${docinvoicenm}",
            "comment"  : "${comment}",
            "serbran"  : "${serbran}",
            "sercostimp"    : "${sercostimp}",
            "serpetimp"     : "${serpetimp}",
            "sercosttot"    : "${sercosttot}",
            "sernumeco"     : "${sernumeco}"
        }]`;
        // console.log(par);
        fill_table(par);
    }
    clean_selectors();
}

// Llena la tabla de los datos de movimientos
function fill_table(par) {
    // console.log('Paso 3 ', par);
    let largo = $('#tblExchanges tbody tr td').html();
    largo == 'Ningún dato disponible en esta tabla' ? $('#tblExchanges tbody tr').remove() : '';
    par = JSON.parse(par);

    let tabla = $('#tblExchanges').DataTable();
    if(mthseries==1){
        tabla.row
        .add({
            editable: `<i class="fas fa-times-circle kill"></i>`,
            prod_sku: `<span class="hide-support" id="SKU-${par[0].sersku}"></span>${par[0].sersku.slice(0, 16)}`,
            prodname: par[0].prodnme,
            prodcant: `<span>${par[0].prodqty}</span>`,
            prodcost: par[0].sercost, 
            prodseri: par[0].prodser,
            prodpeti: par[0].serpetimp,
            prodimpo: par[0].sercostimp,
            costtota: par[0].sercosttot,
            codexcsc: par[0].excodsr,
            stnamesc: par[0].stnmesr,
            provname: par[0].provname,
            factname: par[0].factname,
            prodmarc: par[0].serbran,
            numecono: par[0].sernumeco,
            comments: `<div>${par[0].comment}</div>`
        })
        .draw();

        $(`#SKU-${par[0].sersku}`).parent().parent().attr('data-content', par[0].support);
    } else{
        tabla.row
            .add({
                editable: `<i class="fas fa-times-circle kill"></i>`,
                prod_sku: `<span class="hide-support" id="SKU-${par[0].sersku}"></span>${par[0].sersku.slice(0, 16)}`,
                prodname: par[0].prodnme,
                prodcant: `<span>${par[0].prodqty}</span>`,
                prodcost: par[0].sercost, 
                prodseri: '<input class="serprod fieldIn" type="text" id="PS-' + par[0].prodser + '" value="' + par[0].prodser + '">',
                prodpeti: par[0].serpetimp,
                prodimpo: '<input class="sercpet fieldIn" type="text" id="PS-' + par[0].sercostimp + '" value="' + par[0].sercostimp + '">',
                costtota: par[0].sercosttot,
                codexcsc: par[0].excodsr,
                stnamesc: par[0].stnmesr,
                provname: par[0].provname,
                factname: par[0].factname,
                prodmarc: par[0].serbran,
                numecono: '<input class="serecono fieldIn" type="text" id="PS-' + par[0].sernumeco + '" value="' + par[0].sernumeco + '">',
                comments: `<div>${par[0].comment}</div>`
            })
            .draw();

        $(`#SKU-${par[0].sersku}`).parent().parent().attr('data-content', par[0].support);
    }

    btn_apply_appears();

    $('.edit')
        .unbind('click')
        .on('click', function () {
                        
            tabla.row($(this).parent('tr')).remove().draw();
            btn_apply_appears();
        });
}

function btn_apply_appears() {
    // console.log('Paso 4 ');
    let tabla = $('#tblExchanges').DataTable();
    let rengs = tabla.rows().count();
    if (rengs > 0) {
        $('.btn-apply').removeClass('hidden-field');
    } else {
        $('.btn-apply').addClass('hidden-field');
    }
}

// Limpia los campos para uns nueva seleccion
function clean_selectors() {

    $('#txtProducts').html('<option value="0" selected>Selecciona producto</option>');
    $('#txtProducts').val('');
    $('#txtIdProducts').val(0);
    $('#txtQuantity').val('1');
    $('#txtQuantity').attr('disabled',false);
    $('#txtSerie').attr('disabled', false);
    $('#txtSerie').val('');
    $('#txtNoEco').attr('disabled', false);
    $('#txtNoEco').val('');

    mthseries=0;
    $('#txtCost').val('');
    $('#txtQuantityStored').html('&nbsp;');
    $('#txtComments').val('');
    $('#txtMarca').val('');
    $('#txtCostImp').val('');
    $('#txtPedimento').val('');
    $('#txtCostTot').val('');
}
/** Actualiza la cantidad de cada producto dentro del arreglo */
function update_array_products(id, sr) {
    //console.log('Paso 2 ', id, sr);
    $('#txtNextSerie').val(sr);
    $(`#P-${id}`).attr('data_serie', sr);
}

function read_exchange_table() {
    if (folio == undefined) {
        var pagina = 'PaymentAgreement/NextExchange';
        var par = '[{"par":""}]';
        var tipo = 'html';
        var selector = putNextExchangeNumber;
        fillField(pagina, par, tipo, selector);
    } else {
        $('#tblExchanges tbody tr').each(function (v, u) {
            let seriesku = $(this).attr('data-content').split('|')[3];
            let prodname = $($(u).find('td')[2]).text();
            let quantity = $($(u).find('td')[3]).text();
            let sericost = $($(u).find('td')[4]).text();
            let serienum=0;
            let numecono=0;
            let costpeti=0;
            if($($(u).find('td')[5]).text()!=''){
                serienum = $($(u).find('td')[5]).text();
                numecono = $($(u).find('td')[14]).text();
                costpeti = $($(u).find('td')[7]).text();
            }else{
                serienum = $($(u).find('td')[5]).children('.serprod').val();
                numecono = $($(u).find('td')[14]).children('.serecono').val();
                costpeti= $($(u).find('td')[7]).children('.sercpet').val();
                console.log(serienum, numecono, costpeti);
                if (!serienum) {
                    serienum='';
                }
                if (!numecono) {
                    numecono='';
                }
                if (!costpeti) {
                    costpeti='';
                }
            }
            //let serienum = $('.serprod').val();
            let petition = $($(u).find('td')[6]).text();
            let costtota = $($(u).find('td')[8]).text();
            let codeexch = $($(u).find('td')[9]).text();
            let storname = $($(u).find('td')[10]).text();
            let serbrand = $($(u).find('td')[13]).text();
            let comments = $($(u).find('td')[15]).text();
           
            let producid = $(this).attr('data-content').split('|')[0];
            let typeexch = $(this).attr('data-content').split('|')[1];
            let storesid = $(this).attr('data-content').split('|')[2];
            let sericoin = $(this).attr('data-content').split('|')[4];
            let suppliid = $(this).attr('data-content').split('|')[5];
            let docinvoi = $(this).attr('data-content').split('|')[6];
            let prdidacc = $(this).attr('data-content').split('|')[7];

            let truk = `${folio}|${seriesku}|${prodname}|${quantity}|${serienum}|${storname}|${comments}|${codeexch}|${typeexch}|${producid}|${storesid}|${sericost}|${sericoin}|${suppliid}|${docinvoi}|${petition}|${costpeti}|${serbrand}|${costtota}|${numecono}|${prdidacc}`;
            console.log(truk);
            build_data_structure(truk);
        });
    }
}

/* Generación del folio  */
function putNextExchangeNumber(dt) {
    //console.log(dt);
    folio = dt;
    read_exchange_table();
}

function build_data_structure(pr) {
    let el = pr.split('|');
    let par = `
    [{
        "fol" :  "${el[0]}",
        "sku" :  "${el[1]}",
        "pnm" :  "${el[2].toUpperCase()}",
        "qty" :  "${el[3]}",
        "ser" :  "${el[4].toUpperCase()}",
        "str" :  "${el[5]}",
        "com" :  "${el[6]}",
        "cod" :  "${el[7]}",
        "idx" :  "${el[8]}",
        "prd" :  "${el[9]}",
        "sti" :  "${el[10]}",
        "cos" :  "${el[11]}",
        "cin" :  "${el[12]}",
        "sup" :  "${el[13]}",
        "doc" :  "${el[14]}",
        "pet" :  "${el[15]}",
        "cpe" :  "${el[16]}",
        "bra" :  "${el[17]}",
        "cto" :  "${el[18]}",
        "nec" :  "${el[19]}",
        "acc" :  "${el[20]}"
    }]`;
    console.log(' Antes de Insertar', par);
    save_exchange(par);
}

/** Graba intercambio de almacenes */
function save_exchange(pr) {
    console.log(pr);
    var pagina = 'PaymentAgreement/SaveExchange';
    var par = pr;
    var tipo = 'html';
    var selector = exchange_result;
    fillField(pagina, par, tipo, selector);
}

function exchange_result(dt) {
    $('.resFolio').text(refil(folio, 7));
    $('#MoveFolioModal').modal('show');
    $('#btnHideModal').on('click', function () {
        window.location = 'PaymentAgreement';
    });
    $('#btnPrintReport').on('click', function () {
        // $('.btn-print').trigger('click');
        printInfoGetOut(folio);
    });
}

function updated_stores(dt) {
    // console.log(dt);

    $('.resFolio').text(refil(folio, 7));
    $('#MoveFolioModal').modal('show');
    $('#btnHideModal').on('click', function () {
        window.location = 'PaymentAgreement';
    });
    $('#btnPrintReport').on('click', function () {
        // $('.btn-print').trigger('click');
        printInfoGetOut(folio);
    });
}

/**  ++++ Omite acentos para su facil consulta */
function omitirAcentos(text) {
    var acentos = 'ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç';
    var original = 'AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc';
    for (var i = 0; i < acentos.length; i++) {
        text = text.replace(acentos.charAt(i), original.charAt(i));
    }
    return text;
}

/**  +++ Ocultalos productos del listado que no cumplen con la cadena  */
function sel_products(res) {
    if (res.length < 1) {
        $('#listProducts .list-items div.list-item').css({display: 'block'});
    } else {
        $('#listProducts .list-items div.list-item').css({display: 'none'});
    }

    $('#listProducts .list-items div.list-item').each(function (index) {
        var cm = $(this).attr('data_complement').toUpperCase().replace(/|/g, '');

        cm = omitirAcentos(cm);
        var cr = cm.indexOf(res);
        if (cr > -1) {
            //            alert($(this).children().html())
            $(this).css({display: 'block'});
        }
    });
}

function sel_invoice(res) {
    //console.log('SELECC',res);
    if (res.length < 2) {
        $('#listInvoice .list-items div.list-item').css({display: 'block'});
    } else {
        $('#listInvoice .list-items div.list-item').css({display: 'none'});
    }

    $('#listInvoice .list-items div.list-item').each(function (index) {
        var cm = $(this).attr('data_complement').toUpperCase().replace(/|/g, '');

        cm = omitirAcentos(cm);
        var cr = cm.indexOf(res);
        if (cr > -1) {
            //        alert($(this).children().html())
            $(this).css({display: 'block'});
        }
    });
}

function sel_suppliers(res) {
    //console.log('SELECC',res);
    if (res.length < 2) {
        $('#listSupplier .list-items div.list-item').css({display: 'block'});
    } else {
        $('#listSupplier .list-items div.list-item').css({display: 'none'});
    }

    $('#listSupplier .list-items div.list-item').each(function (index) {
        var cm = $(this).attr('data_complement').toUpperCase().replace(/|/g, '');

        cm = omitirAcentos(cm);
        var cr = cm.indexOf(res);
        if (cr > -1) {
            //            alert($(this).children().html())
            $(this).css({display: 'block'});
        }
    });
}

function printInfoGetOut(verId) {
    let user = Cookies.get('user').split('|');
    let v = verId;
    let u = user[0];
    let n = user[2];
    let h = localStorage.getItem('host');
    // console.log('Lanza Reporte',v,u,n,h);
    window.open(
        `${url}app/views/PaymentAgreement/PaymentAgreementReport.php?v=${v}&u=${u}&n=${n}&h=${h}`,
        '_blank'
    );
}