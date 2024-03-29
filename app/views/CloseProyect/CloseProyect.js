var seccion = '';
let folio;
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
        validator();
    });
    $('#txtSerie').on('blur', function () {
        validator();
    });

    $('#txtQuantity').on('blur', function () {
        validator();
    });
}
// Setea de la tabla
function setting_table() {
    let title = 'Entradas de Almacen';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');

    $('#tblExchanges').DataTable({
        order: [[0, 'desc']],
        dom: 'Blfrtip',
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
            {data: 'prodseri', class: 'serie-product'},
        ],
    });
}

// Solicita los tipos de movimiento
function getExchange() {
    var pagina = 'MoveStoresIn/listExchange';
    var par = '[{"parm":""}]';
    var tipo = 'json';
    var selector = putTypeExchange;
    fillField(pagina, par, tipo, selector);
}
// Solicita el listado de almacenes
function getStores() {
    var pagina = 'Commons/listStores';
    var par = '[{"parm":""}]';
    var tipo = 'json';
    var selector = putStores;
    fillField(pagina, par, tipo, selector);
}

// Solicita los documentos factura
function getCoins() {
    var pagina = 'Commons/listCoins';
    var par = `[{"store":""}]`;
    var tipo = 'json';
    var selector = putCoins;
    fillField(pagina, par, tipo, selector);
}
// Solicita las categorias
function getCategories() {
    console.log('categos');
    var pagina = 'Commons/listCategories';
    var par = `[{"store":""}]`;
    var tipo = 'json';
    var selector = putCategories;
    fillField(pagina, par, tipo, selector);
}
// Solicita los productos de un almacen seleccionado
function getProducts(catId) {
    var pagina = 'MoveStoresIn/listProducts';
    var par = `[{"catId":"${catId}"}]`;
    var tipo = 'json';
    var selector = putProducts;
    fillField(pagina, par, tipo, selector);
}

/*  LLENA LOS DATOS DE LOS ELEMENTOS */
// Dibuja los tipos de movimiento
function putTypeExchange(dt) {
    if (dt[0].ext_id != 0) {
        $.each(dt, function (v, u) {
            if (u.ext_elements.substring(0, 1) != '0') {
                let H = `<option value="${u.ext_id}" data-content="${u.ext_code}|${u.ext_type}|${u.ext_link}|${u.ext_code_a}|${u.ext_type_a}|${u.ext_elements}">${u.ext_code} - ${u.ext_description}</option>`;
                $('#txtTypeExchange').append(H);
            }
        });
    }

    $('#txtTypeExchange').on('change', function () {
        let id = $(this).val();
        link = $(`#txtTypeExchange option[value="${id}"]`).attr('data-content').split('|')[2];
        code = $(`#txtTypeExchange option[value="${id}"]`).attr('data-content').split('|')[5];
        setting_interface(code,id);
        relocation_products();
        validator();
    });
}
/**  ++++++  configura la interfasede inputs requeridos */
function setting_interface(code,id) {
    //console.log('CODE ', code);
    code.substring(1, 2) == '0' ? $('.pos1').addClass('hide-items') : $('.pos1').removeClass('hide-items');
    code.substring(2, 3) == '0' ? $('.pos2').addClass('hide-items') : $('.pos2').removeClass('hide-items');
    code.substring(3, 4) == '0' ? $('.pos3').addClass('hide-items') : $('.pos3').removeClass('hide-items');
    code.substring(4, 5) == '0' ? $('.pos4').addClass('hide-items') : $('.pos4').removeClass('hide-items');
    code.substring(5, 6) == '0' ? $('.pos5').addClass('hide-items') : $('.pos5').removeClass('hide-items');
    code.substring(6, 7) == '0' ? $('.pos6').addClass('hide-items') : $('.pos6').removeClass('hide-items');
    getSuppliers();
    getInvoice(id);
}

// Dibuja los almacenes
function putStores(dt) {
    if (dt[0].str_id != 0) {
        $.each(dt, function (v, u) {
            let H = `<option value="${u.str_id}">${u.str_name}</option>`;
            $('#txtStoreSource').append(H);
        });
    }

    $('#txtStoreSource').on('change', function () {
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
            $('#txtProducts').val('     Cargando Informacion . . . .');
            getProducts(catId);
        });
    }
}

// Almacena los registros de productos en un arreglo
function putProducts(dt) {
    var ps = $('#txtProducts').offset();
    $('#listProducts .list-items').html('');
    $('#listProducts').css({top: ps.top + 30 + 'px'});
    $('#listProducts').slideUp('100', function () {
        $('#listProducts .list-items').html('');
    });

    $.each(dt, function (v, u) {
        let H = `<div class="list-item" id="P-${u.prd_id}" data_serie="${u.serNext}" data_complement="${u.prd_sku}|${u.prd_name}">${u.prd_sku}-${u.prd_name}</div>`;
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
        let prdNm = $(this).html();
        let prdId = $(this).attr('id') + '|' + $(this).attr('data_complement');
        let serie = $(this).attr('data_serie');
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
    var fc = $('#txtInvoice').offset();
    $('#listInvoice .list-items').html('');
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

    if ($('#txtTypeExchange').val() == 0) {
        ky = 1;
        msg += 'Debes seleccionar un tipo de movimiento';
    }

    if ($('#txtStoreSource').val() == 0 && $('.pos1').attr('class').indexOf('hide-items') < 0) {
        ky = 1;
        msg += 'Debes seleccionar un almacen destino';
    }

    if ($('#txtSuppliers').val() == 0 && $('.pos2').attr('class').indexOf('hide-items') < 0) {
        // && $('.pos2').attr('class').indexOf('hide-items') < 0
        ky = 1;
        msg += 'Debes seleccionar el proveedor';
    }

    if ($('#txtIdInvoice').val() == 0 && $('.pos3').attr('class').indexOf('hide-items') < 0) {
        ky = 1;
        msg += 'Debes seleccionar un producto';
    }

    if ($('#txtIdProducts').val() == 0 && $('.pos1').attr('class').indexOf('hide-items') < 0) {
        ky = 1;
        msg += 'Debes seleccionar un producto';
    }

    if ($('#txtCoin').val() == 0 && $('.pos5').attr('class').indexOf('hide-items') < 0) {
        ky = 1;
        msg += 'Debes indicar el tipo de moneda';
    }
   
    //validacion de cantidad para agregar serie mayor a 1
    if ($('#txtQuantity').val() > 1) {
        // && $('#txtSerie').val() == 0
        $('#txtSerie').attr('disabled', true).val('');
        //$('#txtCostImp').attr('disabled', true).val('');
        
    } else if ($('#txtQuantity').val() == 1) {
        $('#txtSerie').attr('disabled', false);
        //$('#txtCostImp').attr('disabled', false);

    } else {
        ky = 1;
        msg += ' Las series se capturan individualmente en la tabla';
    }

    if ($('#txtSerie').val() == '' && $('#txtSerie').attr('disabled') == undefined && $('.pos6').attr('class').indexOf('hide-items') < 0) {
        ky = 1;
        msg += 'Debes indicar la serie del producto';
    }

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
    let serie = parseInt($('#txtNextSerie').val());
    let sersku = prdSku + refil(serie, 4);
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
    let excId = $('#txtTypeExchange').val();
    let exccode = $(`#txtTypeExchange option[value="${excId}"]`).attr('data-content').split('|')[0];
    let strid = $('#txtStoreSource').val();
    let strName = $(`#txtStoreSource option[value="${strid}"]`).text();
    //let prodmarc = $('#txtMarca').val();
    let comment = $('#txtComments').val();
    let serbran = $('#txtMarca').val();
    let sercostimp = $('#txtCostImp').val();
    let serpetimp = $('#txtPedimento').val();

    serie++;
    //update_array_products(prdId, serie);  // REVISAR EL DETALLE DE ESTA FUNCION

    if (quantity > 1) {
        for (var i = 0; i < quantity; i++) {
            sersku = prdSku + refil(serie++, 4);
            update_array_products(prdId, serie); // REVISAR EL DETALLE DE ESTA FUNCION
            let par = `
        [{
            "support"  : "${prdId}|${excId}|${strid}|${sersku}|${sercoin}|${supplier}|${docinvoice}",
            "sersku"   : "${sersku}",
            "prodser"  : "${serser}",
            "prodpeti" : "${prodpeti}",
            "prodimpo" : "${prodimpo}",
            "sercost"  : "${sercost}",
            "prodnme"  : "${prdName}",
            "prodqty"  : "${'1'}",
            "excodsr"  : "${exccode}",
            "stnmesr"  : "${strName}",
            "provname" : "${suppliernm}",
            "factname" : "${docinvoicenm}",
            "comment"  : "${comment}",
            "serbran"  : "${serbran}",
            "sercostimp"  : "${sercostimp}",
            "serpetimp"  : "${serpetimp}"

        }]`;
            fill_table(par);
        }
    } else {
        let par = `
        [{
            "support"  : "${prdId}|${excId}|${strid}|${sersku}|${sercoin}|${supplier}|${docinvoice}",
            "sersku"   : "${sersku}",
            "prodser"  : "${serser}",
            "sercost"  : "${sercost}",
            "prodnme"  : "${prdName}",
            "prodqty"  : "${quantity}",
            "excodsr"  : "${exccode}",
            "stnmesr"  : "${strName}",
            "provname" : "${suppliernm}",
            "factname" : "${docinvoicenm}",
            "comment"  : "${comment}",
            "serbran"  : "${serbran}",
            "sercostimp"  : "${sercostimp}",
            "serpetimp"  : "${serpetimp}"
        }]`;
        fill_table(par);
    }
    clean_selectors();
}

// Llena la tabla de movimientos
function fill_table(par) {
    //console.log('Paso 3 ', par);
    let largo = $('#tblExchanges tbody tr td').html();
    largo == 'Ningún dato disponible en esta tabla' ? $('#tblExchanges tbody tr').remove() : '';
    par = JSON.parse(par);

    let tabla = $('#tblExchanges').DataTable();

    tabla.row
        .add({
            editable: `<i class="fas fa-times-circle kill"></i>`,
            prod_sku: `<span class="hide-support" id="SKU-${par[0].sersku}"></span>${par[0].sersku.slice(0, 7)}-${par[0].sersku.slice(7, 11)}`,
            prodname: par[0].prodnme,
            prodcant: `<span>${par[0].prodqty}</span>`,
            prodcost: par[0].sercost,
            //prodseri: par[0].prodser, 
            prodseri: '<input class="serprod fieldIn" type="text" id="PS-' + par[0].prodser + '" value="' + par[0].prodser + '">',
            prodpeti: par[0].serpetimp,
            prodimpo: '<input class="serprod fieldIn" type="text" id="PS-' + par[0].sercostimp + '" value="' + par[0].sercostimp + '">',
            //prodimpo: par[0].sercostimp,
            codexcsc: par[0].excodsr,
            stnamesc: par[0].stnmesr,
            provname: par[0].provname,
            factname: par[0].factname,
            prodmarc: par[0].serbran,
            comments: `<div>${par[0].comment}</div>`
        })
        .draw();
    $(`#SKU-${par[0].sersku}`).parent().parent().attr('data-content', par[0].support);
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
    $('#txtQuantity').val('');
    $('#txtSerie').attr('disabled', false);
    $('#txtSerie').val('');
    $('#txtCost').val('');
    $('#txtQuantityStored').html('&nbsp;');
    $('#txtComments').val('');
    $('#txtMarca').val('');
    $('#txtCostImp').val('');
    $('#txtPedimento').val('');
}
/** Actualiza la cantidad de cada producto dentro del arreglo */
function update_array_products(id, sr) {
    //console.log('Paso 2 ', id, sr);
    $('#txtNextSerie').val(sr);
    $(`#P-${id}`).attr('data_serie', sr);
}

function read_exchange_table() {
    if (folio == undefined) {
        var pagina = 'MoveStoresIn/NextExchange';
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
            let serienum = $($(u).find('td')[5]).children('.serprod').val();
            //let serienum = $('.serprod').val();
            let petition = $($(u).find('td')[6]).text();
            let costpeti = $($(u).find('td')[7]).children('.serprod').val();
            let codeexch = $($(u).find('td')[8]).text();
            let storname = $($(u).find('td')[9]).text();
            let serbrand = $($(u).find('td')[12]).text();
            let comments = $($(u).find('td')[13]).text();
           
            let typeexch = $(this).attr('data-content').split('|')[1];
            let producid = $(this).attr('data-content').split('|')[0];
            let storesid = $(this).attr('data-content').split('|')[2];
            let sericoin = $(this).attr('data-content').split('|')[4];
            let suppliid = $(this).attr('data-content').split('|')[5];
            let docinvoi = $(this).attr('data-content').split('|')[6];

            let truk = `${folio}|${seriesku}|${prodname}|${quantity}|${serienum}|${storname}|${comments}|${codeexch}|${typeexch}|${producid}|${storesid}|${sericost}|${sericoin}|${suppliid}|${docinvoi}|${petition}|${costpeti}|${serbrand}`;
            // console.log(truk);
            build_data_structure(truk);
        });
    }
}

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
    "ser" :  "${el[4]}",
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
    "bra" :  "${el[17]}"
}]`;
    // console.log(' Antes de Insertar', par);
    save_exchange(par);
}

/** Graba intercambio de almacenes */
function save_exchange(pr) {
    var pagina = 'MoveStoresIn/SaveExchange';
    var par = pr;
    var tipo = 'html';
    var selector = exchange_result;
    fillField(pagina, par, tipo, selector);
}

function exchange_result(dt) {
    $('.resFolio').text(refil(folio, 7));
    $('#MoveFolioModal').modal('show');
    $('#btnHideModal').on('click', function () {
        window.location = 'MoveStoresIn';
    });
    $('#btnPrintReport').on('click', function () {
        $('.btn-print').trigger('click');
    });
}

function updated_stores(dt) {
    // console.log(dt);
    $('.resFolio').text(refil(folio, 7));
    $('#MoveFolioModal').modal('show');
    $('#btnHideModal').on('click', function () {
        window.location = 'MoveStoresIn';
    });
    $('#btnPrintReport').on('click', function () {
        $('.btn-print').trigger('click');
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
    if (res.length < 1) {
        $('#listInvoice .list-items div.list-item').css({display: 'block'});
    } else {
        $('#listInvoice .list-items div.list-item').css({display: 'none'});
    }

    $('#listInvoice .list-items div.list-item').each(function (index) {
        var cm = $(this).attr('data_complement').toUpperCase().replace(/|/g, '');

        cm = omitirAcentos(cm);
        var cr = cm.indexOf(res);
        if (cr > -1) {
            //            alert($(this).children().html())
            $(this).css({display: 'block'});
        }
    });
}

function sel_suppliers(res) {
    //console.log('SELECC',res);
    if (res.length < 1) {
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
