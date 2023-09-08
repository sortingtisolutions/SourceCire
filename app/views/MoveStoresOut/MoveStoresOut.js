var seccion = '';
///const folio = uuidv4();
let folio,idstr;
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
    //getCategories();
    eventsAction();
    setting_table();

    $('#btn_exchange').on('click', function () {
        let id = $('#boxIdProducts').val();
        // console.log('id-val', id);
        exchange_apply(id);
    });
}

// Solicita los tipos de movimiento
function getExchange() {
    var pagina = 'MoveStoresOut/listExchange';
    var par = '[{"parm":""}]';
    var tipo = 'json';
    var selector = putTypeExchange;
    fillField(pagina, par, tipo, selector);
}
// Solicita el listado de almacenes
function getStores() {
    var pagina = 'MoveStoresOut/listStores';
    var par = '[{"parm":""}]';
    var tipo = 'json';
    var selector = putStores;
    fillField(pagina, par, tipo, selector);
}

function getCategories() {
    //console.log('categos');
    var pagina = 'MoveStoresOut/listCategories';
    var par = `[{"store":""}]`;
    var tipo = 'json';
    var selector = putCategories;
    fillField(pagina, par, tipo, selector);
}
// Solicita los productos de un almacen seleccionado
function getProducts(strId,word) {
    var pagina = 'MoveStoresOut/listProducts';
    var par = `[{"strId":"${strId}"},{"word":"${word}"}]`;
    var tipo = 'json';
    var selector = putProducts;
    fillField(pagina, par, tipo, selector);
}

// Solicita los movimientos acurridos
function getExchanges() {
    var pagina = 'MoveStoresOut/listExchanges';
    var par = `[{"folio":"${folio}"}]`;
    var tipo = 'json';
    var selector = putExchanges;
    fillField(pagina, par, tipo, selector);
}
function setting_table() {
    let title = 'Salidas de Almacen';
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
            {
                data: 'supports',
                class: 'support',
                visible: false,
                searchable: false,
            },
            {data: 'editable', class: 'edit'},
            {data: 'prod_sku', class: 'sku'},
            {data: 'prodname', class: 'product-name'},
            {data: 'prodcant', class: 'quantity'},
            {data: 'prodseri', class: 'serie-product'},
            {data: 'codexcsc', class: 'code-type_s'},
            {data: 'stnamesc', class: 'store-name_s'},
            {data: 'codexctg', class: 'code-type_t'},
            {data: 'stnametg', class: 'store-name_t'},
            {data: 'comments', class: 'comments'},
        ],
    });
}


/*  LLENA LOS DATOS DE LOS ELEMENTOS */
// Dibuja los tipos de movimiento
function putTypeExchange(dt) {
    if (dt[0].ext_id != 0) {
        $.each(dt, function (v, u) {
            let H = `<option value="${u.ext_id}" data-content="${u.ext_code}|${u.ext_type}|${u.ext_link}|${u.ext_code_a}|${u.ext_type_a}">${u.ext_code} - ${u.ext_description}</option>`;
            $('#txtTypeExchange').append(H);
        });
    }
    $('#txtTypeExchange').on('change', function () {
        let id = $(this).val();
        link = $(`#txtTypeExchange option[value="${id}"]`).attr('data-content').split('|')[2];

        if (link == 'null') {
            $('#txtStoreTarget').parent().css({display: 'none'});
            var ps = $('#boxProducts').offset();
            $('.list-group').css({top: ps.top + 30 + 'px', display: 'none'});
        } else {
            $('#txtStoreTarget').parent().css({display: 'block'});
            var ps = $('#boxProducts').offset();
            $('.list-group').css({top: ps.top + 30 + 'px', display: 'none'});
        }
        $('#txtStoreTarget').val(0);
    });
}

// Dibuja los almacenes
function putStores(dt) {
    if (dt[0].str_id != 0) {
        $.each(dt, function (v, u) {
            let H = `<option value="${u.str_id}">${u.str_name}</option>`;
            $('#txtStoreSource').append(H);
            $('#txtStoreTarget').append(H);
        });
    }
    // $('#boxProducts').parents('.list-finder').removeClass('hide-items');

    $('#txtStoreSource').on('change', function () {
        // $('#boxProducts').parents('.list-finder').addClass('hide-items');
        $('#boxProducts').parents('.list-finder').removeClass('hide-items');
        let id = $(this).val();
        idstr=id;
        console.log(idstr);
        $(`#txtStoreTarget option`).css({display: 'block'});
        $(`#txtStoreTarget option[value="${id}"]`).css({display: 'none'});
       //console.log('ID Almacen', id);
        // modalLoading('S');
        // getProducts(id);
        //drawProducts(id);
    });
}

/*function putCategories(dt) {
    if (dt[0].cat_id != 0) {
        $.each(dt, function (v, u) {
            let H = `<option value="${u.cat_id}"> ${u.cat_name}</option>`;
            $('#txtCategory').append(H);
        });

        $('#txtCategory').on('change', function () {
            let catId = $(this).val();
            console.log(catId);
            getProducts(catId);
        });
    }
}*/

function putProducts(dt) {
    // console.log('putProducts',dt);

    // $('#boxProducts').parents('.list-finder').removeClass('hide-items');
    var sl = $('#boxProducts').offset();

    $.each(dt, function (v, u) {
        let H = `<div class="list-item" id="${u.ser_id}" data-store="${u.str_id}" data-content="${u.ser_id}|${u.ser_sku}|${u.ser_serial_number}|${u.prd_name}|${u.ser_cost}|${u.prd_coin_type}|${u.prd_id}">${u.ser_sku} - ${u.prd_name} - ${u.ser_serial_number}</div>`;
        $('#listProducts .list-items').append(H);
    });

    // console.log('Lista llena');
    $('#listProducts').css({top: sl.top + 30 + 'px'});
    $('#listProducts').show();

    // modalLoading('H');
    $('#boxProducts').on('focus', function () {
        $('#listProducts').slideDown('fast');
    });

    $('#listProducts').on('mouseleave', function () {
        $('#listProducts').slideUp('fast');
    });

    $('#listProducts .list-item').on('click', function () {
        // console.log('Paso list');
        let prdNm = $(this).html();
        let prdId = $(this).attr('id') + '|' + $(this).attr('data-content');
        $('#boxProducts').val(prdNm);
        $('#boxIdProducts').val(prdId);
        $('#listProducts').slideUp(100);
        //validator();
    });
}

// Almacena los registros de productos en un arreglo

function xdrawProducts(str) {
    $('#txtProducts').html('<option value="0" selected>Selecciona producto</option>');
    if (pr[0][1].prd_id != 0) {
        $.each(pr, function (v, u) {
            if (str == u[1].str_id) {
                let H = `<option value="${u[1].ser_id}" data-content="${u[1].prd_sku}|${u[1].stp_quantity}|${u[1].ser_serial_number}|${u[1].stp_id}">${u[1].ser_serial_number} - ${u[1].prd_name}</option>`;
                $('#txtProducts').append(H);
            }
        });
    }

    $('#txtProducts').on('change', function () {
        let cant = $('#txtProducts option:selected').attr('data-content').split('|')[1];
        $('#txtQuantityStored').html(cant);
        $('#txtQuantity').val(cant);
    });
}
// Valida los campos
function validator(prId) {
    let ky = 0;
    let msg = '';

    if ($('#txtTypeExchange').val() == 0) {
        ky = 1;
        msg += 'Debes seleccionar un tipo de movimiento';
        $('#txtTypeExchange').addClass('fail');
    }

    if ($('#txtStoreTarget').val() == 0 && link != '' && link != 'null') {
        ky = 1;
        msg += 'Debes seleccionar un almacen destino';
        $('#txtStoreTarget').addClass('fail');
    }

    $('.fail')
        .unbind('focus')
        .on('focus', function () {
            $(this).removeClass('fail');
        });
    return ky;
}
// Aplica la seleccion para la tabla de movimientos
function exchange_apply(prId) {
        //console.log('exchange_apply',prId);
    if (validator(prId) == 0) {
        let typeExchangeCodeSource = $('#txtTypeExchange option:selected').attr('data-content').split('|')[0];
        let typeExchangeCodeTarget = $('#txtTypeExchange option:selected').attr('data-content').split('|')[3];
        let typeExchangeIdSource = $('#txtTypeExchange option:selected').val();
        let typeExchangeIdTarget = $('#txtTypeExchange option:selected').attr('data-content').split('|')[2];

        let storeNameSource = $('#txtStoreSource option:selected').text();
        let storeNameTarget = $('#txtStoreTarget option:selected').text();
        let storeIdSource = $('#txtStoreSource option:selected').val();
        let storeIdTarget = $('#txtStoreTarget option:selected').val();

        if (link == 'null' || link == '') {
            typeExchangeCodeTarget = '';
            storeNameTarget = '';
        }
        //let prod = prId.attr('data-content').split('|');
        let prod = prId.split('|');
        let serId = prod[0];
        let productSKU = prod[2];
        let productName = prod[4];
        //let productQuantity = prId.children().children('.quantity').text();
        let productQuantity = '1';
        let productSerie = prod[3];
        let prdId = prod[7];

        let commnets = $('#txtComments').val();
        let project = '';
        //console.log('DATOS- ', serId, productSKU, productName,productSerie,commnets);
        //update_array_products(serId, productQuantity);
        let par = `
        [{
            "support"    :  "${folio}|${productSKU}|${typeExchangeIdSource}|${typeExchangeIdTarget}|${serId}|${storeIdSource}|${storeIdTarget}|${prdId}",
            "prodsku"	: 	"${productSKU}",
            "prodnme"	:	"${productName}",
            "prodqty"	:	"${productQuantity}",
            "prodser"	:	"${productSerie}",
            "excodsr"	:	"${typeExchangeCodeSource}",
            "stnmesr"	:	"${storeNameSource}",
            "excodtg"	:	"${typeExchangeCodeTarget}",
            "stnmetg"	:	"${storeNameTarget}",
            "comment"	:	"${commnets}",
            "project"	:	"${project}",
            "excidsr"	:	"${typeExchangeIdSource}",
            "excidtg"	:	"${typeExchangeIdTarget}",
            "stoidsr"	:	"${storeIdSource}",
            "stoidtg"	:	"${storeIdTarget}",
            "dtfolio"	:	"${folio}"
        }]
            `;
        // console.log('exchange_apply',par);
        fill_table(par);
    }
}

// Llena la tabla de movimientos
function fill_table(par) {
    let largo = $('#tblExchanges tbody tr td').html();
    largo == 'Ningún dato disponible en esta tabla' ? $('#tblExchanges tbody tr').remove() : '';
    par = JSON.parse(par);

    let tabla = $('#tblExchanges').DataTable();
    tabla.row
        .add({
            supports: par[0].support,
            editable: '<i class="fas fa-times-circle kill"></i>',
            prod_sku: `<span class="hide-support">${par[0].support}</span>${par[0].prodsku}`,
            prodname: par[0].prodnme,
            prodcant: `<span>${par[0].prodqty}</span>`,
            prodseri: par[0].prodser,
            codexcsc: par[0].excodsr,
            stnamesc: par[0].stnmesr,
            codexctg: par[0].excodtg,
            stnametg: par[0].stnmetg,
            comments: `<div>${par[0].comment}</div>`,
        })
        .draw();
    btn_apply_appears();
    clean_selectors();
    $('#listProducts .list-items').html('');

    $('.edit')
        .unbind('click')
        .on('click', function () {
            console.log('CLICK EDIT');
            let qty = parseInt($(this).parent().children('td.quantity').text()) * -1;
            let pid = $(this).parent().children('td.sku').children('span.hide-support').text().split('|')[4];
           // update_array_products(pid, qty);
            tabla.row($(this).parent('tr')).remove().draw();
            btn_apply_appears();
        });
}

function btn_apply_appears() {
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
    // $('#txtTypeExchange').val(0);
    // $('#txtStoreSource').val(0);
    $('#txtStoreTarget').val(0);
    // $('#txtProducts').html('<option value="0" selected>Selecciona producto</option>');
    // $('#txtQuantity').val('');
    // $('#txtQuantityStored').html('&nbsp;');
    $('#txtComments').val('');
    $('#boxProducts').val('');
    $('#boxIdProducts').val('');
}

/** Actualiza la cantidad de cada producto dentro del arreglo */
function update_array_products(id, cn) {
    let prId = $('#P-' + id);
    let qtystk = prId.children().children('.quantity').text();
    prId.children()
        .children('.quantity')
        .text(qtystk - cn);
}

function read_exchange_table() {
    if (folio == undefined) {
        var pagina = 'MoveStoresOut/NextExchange';
        var par = '[{"par":""}]';
        var tipo = 'html';
        var selector = putNextExchangeNumber;
        fillField(pagina, par, tipo, selector);
    } else {
        $('#tblExchanges tbody tr').each(function (v, u) {
            //let folio = $($(u).find('td')[1]).children('span.hide-support').text().split('|')[0];
            let sku = $($(u).find('td')[1]).children('span.hide-support').text().split('|')[1];
            let product = $($(u).find('td')[2]).text();
            let quantity = $($(u).find('td')[3]).text();
            let serie = $($(u).find('td')[4]).text();
            let codeTypeExchangeSource = $($(u).find('td')[5]).text();
            let storeSource = $($(u).find('td')[6]).text();
            let codeTypeExchangeTarget = $($(u).find('td')[7]).text();
            let storeTarget = $($(u).find('td')[8]).text();
            let comments = $($(u).find('td')[9]).text();
            let idTypeExchangeSource = $($(u).find('td')[1]).children('span.hide-support').text().split('|')[2];
            let idTypeExchangeTarget = $($(u).find('td')[1]).children('span.hide-support').text().split('|')[3];
            let serId = $($(u).find('td')[1]).children('span.hide-support').text().split('|')[4];
            let storeIdSource = $($(u).find('td')[1]).children('span.hide-support').text().split('|')[5];
            let storeIdTarget = $($(u).find('td')[1]).children('span.hide-support').text().split('|')[6];
            let prodId = $($(u).find('td')[1]).children('span.hide-support').text().split('|')[7];

            let exchstruc1 = `${folio}|${sku}|${product}|${quantity}|${serie}|${storeSource}|${comments}|${codeTypeExchangeSource}|${idTypeExchangeSource}`;
            let exchstruc2 = `${folio}|${sku}|${product}|${quantity}|${serie}|${storeTarget}|${comments}|${codeTypeExchangeTarget}|${idTypeExchangeTarget}`;
            let exchupda1 = `${serId}|${quantity}|${storeIdSource}|${prodId}`;
            let exchupda2 = `${serId}|${quantity}|${storeIdTarget}|${prodId}`;

            if (codeTypeExchangeSource != '') { 
                build_data_structure(exchstruc1);
                build_update_store_data(`${exchupda1}|S`);   // source store
            }
            if (codeTypeExchangeTarget != '') {  
                build_data_structure(exchstruc2);
                build_update_store_data(`${exchupda2}|T`);   // target store
            }
        });
    }
}

function putNextExchangeNumber(dt) {
    // console.log(dt);
    folio = dt;
    read_exchange_table();
}

function build_data_structure(pr) {
    let el = pr.split('|');
    let par = `
    [{
        "fol" :  "${el[0]}",
        "sku" :  "${el[1]}",
        "pnm" :  "${el[2]}",
        "qty" :  "${el[3]}",
        "ser" :  "${el[4]}",
        "str" :  "${el[5]}",
        "com" :  "${el[6]}",
        "cod" :  "${el[7]}",
        "idx" :  "${el[8]}",
        "prj" :  ""
    }]`;
    // console.log('STRUCTURE-',par);
    save_exchange(par);
}

function build_update_store_data(pr) {
    let el = pr.split('|');
    let par = `
    [{
        "serid" :  "${el[0]}",
        "qty"   :  "${el[1]}",
        "strid" :  "${el[2]}",
        "prdid" :  "${el[3]}",
        "mov"   :  "${el[4]}"
    }]`;
    console.log('STORE-DATA',par);
    update_store(par);
}

/** Graba intercambio de almacenes */
function save_exchange(pr) {
    //   console.log(pr);
    var pagina = 'MoveStoresOut/SaveExchange';
    var par = pr;
    var tipo = 'html';
    var selector = exchange_result;
    fillField(pagina, par, tipo, selector);
}

function exchange_result(dt) {
    // console.log(dt);
    $('.resFolio').text(refil(folio, 7));

    $('#MoveFolioModal').modal('show');
    $('#btnHideModal').on('click', function () {
        window.location = 'MoveStoresOut';
    });
    $('#btnPrintReport').on('click', function () {
        // console.log('Lanza Print Exchange', folio);
        printInfoGetOut(folio);
    });
}

function update_store(ap) {
    // console.log(ap);
    var pagina = 'MoveStoresOut/UpdateStoresSource';
    var par = ap;
    var tipo = 'html';
    var selector = putUpdatedstores;
    fillField(pagina, par, tipo, selector);
}

function putUpdatedstores(dt) {
    // console.log('putUpdatedstores', dt);

    $('.resFolio').text(refil(folio, 7));

    $('#MoveFolioModal').modal('show');
    $('#btnHideModal').on('click', function () {
        window.location = 'MoveStoresOut';
    });

    $('#btnPrintReport').on('click', function () {
        // console.log('Lanza Print Update', folio);
        printInfoGetOut(folio);
    });
}

/* Generación del folio  */
function uuidv4() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = (Math.random() * 16) | 0,
            v = c == 'x' ? r : (r & 0x3) | 0x8;
        return v.toString(16);
    });
}
function omitirAcentos(text) {
    var acentos = 'ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç';
    var original = 'AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc';
    for (var i = 0; i < acentos.length; i++) {
        text = text.replace(acentos.charAt(i), original.charAt(i));
    }
    return text;
}

function sel_products(res) {
    res = res.toUpperCase();
    let rowCurr = $('#listProducts .list-items div.list-item');

    if (res.length > 2) {
        let dstr = 0;
        let dend = 0;
        if (res.length == 3) {
            // console.log('Paso2',idstr,res);

                getProducts(idstr,res);

        } else {
            // console.log('Paso3');
            rowCurr.css({ display: 'none' });
            rowCurr.each(function (index) {
                var cm = $(this)
                    .data('content')
                    .toUpperCase()
                    .replace(/|/g, '');

                cm = omitirAcentos(cm);
                var cr = cm.indexOf(res);
                if (cr > -1) {
                    $(this).show();
                }
            });
        }
        // rowCurr.show();
    } else {
        $(`#listProductsTable table tbody`).html('');
        rowCurr.addClass('oculto');
    }
}

/**  +++ Ocultalos productos del listado que no cumplen con la cadena  */
function sel_products_old(res) {
    if (res.length < 3) {
        $('#listProducts .list-items div.list-item').css({display: 'block'});
    } else {
        $('#listProducts .list-items div.list-item').css({display: 'none'});
    }

    $('#listProducts .list-items div.list-item').each(function (index) {
        var cm = $(this).attr('data-content').toUpperCase().replace(/|/g, '');

        cm = omitirAcentos(cm);
        var cr = cm.indexOf(res);
        if (cr > -1) {
            //            alert($(this).children().html())
            $(this).css({display: 'block'});
        }
    });
}

function modalLoading(acc) {
    if (acc == 'S') {
        $('.invoice__modalBackgound').fadeIn('slow');
        $('.invoice__loading')
            .slideDown('slow')
            .css({ 'z-index': 401, display: 'flex' });
    } else {
        $('.invoice__loading').slideUp('slow', function () {
            $('.invoice__modalBackgound').fadeOut('slow');
        });
    }
}

function printInfoGetOut(verId) {
    let user = Cookies.get('user').split('|');
    let v = verId;
    let u = user[0];
    let n = user[2];
    let h = localStorage.getItem('host');
    // console.log('Lanza Reporte',v,u,n,h);
    window.open(
        `${url}app/views/MoveStoresOut/MoveStoresOutReport.php?v=${v}&u=${u}&n=${n}&h=${h}`,
        '_blank'
    );
}

function eventsAction() {

        $('#boxProducts')
        .unbind('keyup')
        .on('keyup', function () {
            let text = $(this).val().toUpperCase();
            console.log(text);
            sel_products(text);
        });
}

