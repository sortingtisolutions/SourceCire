let strs = null;
let strnme = '';
let idstr;
$(document).ready(function () {
    if (verifica_usuario()) {
        inicial();
    }
});


function inicial() {
    if (altr == 1) {
        deep_loading('O');
        settingTable();
        getStores();
        eventsAction()
        getMovStores();
        fillStores();
        confirm_alert();
    } else {
        setTimeout(() => {
            inicial();
        }, 100);
    }
}

//CONFIGURACION DE DATATABLE
function settingTable() {
    let title = 'Lista de la relacion entre unidad movil y almacen';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    $('#AreasTable').DataTable({
        //order: [[1, 'asc']],
        dom: 'Blfrtip',
        lengthMenu: [
            [100, 200, -1],
            [100, 200, 'Todos'],
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
                //Botón para descargar PDF
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
                text: 'Borrar seleccionados',
               
            },
        ],
        pagingType: 'simple_numbers',
        language: {
            url: 'app/assets/lib/dataTable/spanish.json',
        },
        scrollY: 'calc(100vh - 200px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
            {data: 'editable', class: 'edit', orderable: false},
            {data: 'code', class: 'strid center bold'},
            {data: 'storname', class: 'store-name'},
            {data: 'prod', class: 'store-prod'},
            {data: 'category', class: 'category'},
            {data: 'subcategory', class: 'quantity'},
        ],
    });
}

// Solicita los productos de un almacen seleccionado
function getMovStores() {
    var pagina = 'MobilStore/GetMobilStores';
    var par = `[{"movstr_id":""}]`;
    var tipo = 'json';
    var selector = putMovStores;
    fillField(pagina, par, tipo, selector);
}
// Solicita el listado de almacenes
function getStores() {
    var pagina = 'MobilStore/listStores';
    var par = '[{"parm":""}]';
    var tipo = 'json';
    var selector = putStores;
    fillField(pagina, par, tipo, selector);
}

function putMovStores(dt) {
    strs = dt;
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

    $('#txtStoreSource').on('change', function () {
        $('#boxProducts').parents('.list-finder').removeClass('hide-items');
        // 
        let id = $(this).val();
        idstr=id;
        // console.log(idstr);
        $(`#txtStoreTarget option`).css({display: 'block'});
        $(`#txtStoreTarget option[value="${id}"]`).css({display: 'none'});
    });
}

function fillStores() {
    if (strs != null) {
        let tabla = $('#AreasTable').DataTable();
        $.each(strs, function (v, u) {
            fillTableStores(v);
        });
        deep_loading('C');
    } else {
        setTimeout(() => {
            fillStores();
        }, 100);
    }
}

function actionButtons() {
    /**  ---- Acciones de edición ----- */
    $('td.edit i')
        .unbind('click')
        .on('click', function () {
            let acc = $(this).attr('class').split(' ')[2];
            let strId = $(this).parents('tr').attr('id');

            switch (acc) {
                case 'modif':
                    editStore(strId);
                    break;
                case 'kill':
                    deleteStore(strId);
                    break;
                default:
            }
        });
    
    /**  ---- Acciones de Guardar categoria ----- */
    $('#GuardarAlmacen')
        .unbind('click')
        .on('click', function () {
            if (validaFormulario() == 1) {
                if ($('#IdAlmacen').val() == '') {
                    saveStore();
                } else {
                    updateStore();
                }
            }
        });
    /**  ---- Lismpia los campos ----- */
    $('#LimpiarFormulario')
        .unbind('click')
        .on('click', function () {
            $('#NomAlmacen').val('');
            $('#IdAlmacen').val('');
            $('#txtStoreSource').val('');
            $('#boxIdProducts').val('');
            $('#boxProducts').val('');
            //$('#txtCategories').val('');
            //$('#txtSubcategories').val('');
        });
}

function fillTableStores(ix) {
    let tabla = $('#AreasTable').DataTable();
    if (strs[0].movstr_id > 0) {
        tabla.row
        .add({
            editable: `<i class="fas fa-pen modif" id ="md${strs[ix].movstr_id}"></i><i class="fas fa-times-circle kill"></i>`,
            code: strs[ix].movstr_placas,
            storname: strs[ix].str_name,
            prod: strs[ix].prd_name,
            category: strs[ix].cat_name,
            subcategory: strs[ix].sbc_name,
        })
        .draw();
        $('#md' + strs[ix].movstr_id)
            .parents('tr')
            .attr('id', strs[ix].movstr_id)
            .attr('str-id', strs[ix].str_id)
            .attr('ser-id', strs[ix].ser_id)
            .attr('prd-id', strs[ix].prd_id);
    }
    
        
    actionButtons();
}

function saveStore() {
    var strName = $('#NomAlmacen').val();
    var store = $('#txtStoreSource').val();
    var products = $('#boxIdProducts').val().split('|')[1];
    var ser_id = $('#boxIdProducts').val().split('|')[0];
    var par = `
        [{ "placas"   : "${strName}",
            "str_id"   : "${store}",
            "prd_id"   : "${products}",
            "ser_id"   : "${ser_id}"
        }]`;

    strs = '';
    // console.log(par);
    var pagina = 'MobilStore/SaveMobilStore';
    var tipo = 'html';
    var selector = putSaveStore;
    fillField(pagina, par, tipo, selector);
}
function putSaveStore(dt) {
    getMovStores();
    if (strs.length > 0) {
        let ix = goThroughStore(dt);
        fillTableStores(ix);
        $('#LimpiarFormulario').trigger('click');
    } else {
        setTimeout(() => {
            putSaveStore(dt);
        }, 100);
    }
}

function updateStore() {
    var movId = $('#IdAlmacen').val();
    var strName = $('#NomAlmacen').val();
    var store = $('#txtStoreSource').val();
    var products = $('#boxIdProducts').val().split('|')[1];
    var ser_id = $('#boxIdProducts').val().split('|')[0];
    var par = `
        [{  "movstr_id"   : "${movId}",
            "placas"   : "${strName}",
            "str_id"   : "${store}",
            "prd_id"   : "${products}",
            "ser_id"   : "${ser_id}"
        }]`;

    // console.log(par);
    strs = '';
    var pagina = 'MobilStore/UpdateMobilStore';
    var tipo = 'html';
    var selector = putUpdateStore;
    fillField(pagina, par, tipo, selector);
}
function putUpdateStore(dt) {
    getMovStores();
    if (strs.length > 0) {
        console.log(dt);
        let ix = goThroughStore(dt);
        $(`#${strs[ix].movstr_id}`).children('td.strid').html(strs[ix].movstr_placas);
        $(`#${strs[ix].movstr_id}`).children('td.store-name').html(strs[ix].str_name);
        /* $(`#${strs[ix].movstr_id}`).children('td.quantity').html(strs[ix].ser_sku); */ 
        $(`#${strs[ix].movstr_id}`).children('td.store-prod').html(strs[ix].prd_name); 
        $(`#${strs[ix].movstr_id}`).children('td.category').html(strs[ix].cat_name);  
        $(`#${strs[ix].movstr_id}`).children('td.quantity').html(strs[ix].sbc_name);
        $('#LimpiarFormulario').trigger('click');
    } else {
        setTimeout(() => {
            putUpdateStore(dt);
        }, 100);
    }
}

function editStore(strId) {
    console.log('Editando');
    let ix = goThroughStore(strId);
    $('#NomAlmacen').val(strs[ix].movstr_placas);
    $('#IdAlmacen').val(strs[ix].movstr_id);
    $('#txtStoreSource').val(strs[ix].str_id);
    $('#txtCategories').val(strs[ix].cat_id);
    $('#txtSubcategories').val(strs[ix].sbc_id);
    idstr = strs[ix].str_id;
    //getProducts(idstr,'');
    $('#boxProducts').val(strs[ix].ser_sku+' - '+strs[ix].prd_name);
    $('#boxIdProducts').val(strs[ix].ser_id+'|'+strs[ix].prd_id);
}

function deleteStore(strId) {
    console.log(strId);
    let cn = $(`#${strId}`).children('td.quantity').children('.toLink').html();
        $('#confirmModal').modal('show');
        $('#confirmModalLevel').html('¿Seguro que desea borrar la Almacen y Unidad Móvil?');
        $('#N').html('Cancelar');
        $('#confirmButton').html('Borrar').css({display: 'inline'});
        $('#Id').val(strId);
        $('#IdAlmacenBorrar').val(strId);

        $('#confirmButton').on('click', function () {
            var pagina = 'MobilStore/DeleteMobilStore';
            var par = `[{"movstr_id":"${strId}"}]`;
            var tipo = 'html';
            var selector = putDeleteStore;
            fillField(pagina, par, tipo, selector);
        });
    
}

function putDeleteStore(dt) {
    getMovStores();
    let tabla = $('#AreasTable').DataTable();
    tabla
        .row($(`#${dt}`))
        .remove()
        .draw();
    $('#confirmModal').modal('hide');
}

function goThroughStore(strId) {
    let inx = -1;
    $.each(strs, function (v, u) {
        if (strId == u.movstr_id) inx = v;
    });
    return inx;
}

//Valida los campos seleccionado *
function validaFormulario() {
    var valor = 1;
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function (form) {
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            valor = 0;
        }
    });
    return valor;
}
function sel_products(res) {
    res = res.toUpperCase();
    let rowCurr = $('#listProducts .list-items div.list-item');

    if (res.length > 2) {
        let dstr = 0;
        let dend = 0;
        if (res.length == 3) {
                getProducts(idstr,res);
        } else {
            // console.log('Paso3');
            rowCurr.css({ display: 'none' });
            rowCurr.each(function (index) {
                var cm = $(this)
                    .attr('data-content')
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


/**  ++++ Omite acentos para su facil consulta */
function omitirAcentos(text) {
    var acentos = 'ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç';
    var original = 'AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc';
    for (var i = 0; i < acentos.length; i++) {
        text = text.replace(acentos.charAt(i), original.charAt(i));
    }
    return text;
}
// Solicita los productos de un almacen seleccionado
function getProducts(strId,word) {
    var pagina = 'MobilStore/listProducts';
    var par = `[{"strId":"${strId}"},{"word":"${word}"}]`;
    var tipo = 'json';
    var selector = putProducts;
    fillField(pagina, par, tipo, selector);
}

function putProducts(dt) {
    var sl = $('#boxProducts').offset();
    $('#listProducts .list-items').html('');
    $.each(dt, function (v, u) {
        let H = `<div class="list-item" id="${u.ser_id}" data-store="${u.str_id}" data-content="${u.ser_id}|${u.prd_id}|${u.ser_sku}">${u.ser_sku} - ${u.prd_name}</div>`;
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
        let prdId = $(this).attr('data-content');
        $('#boxProducts').val(prdNm);
        $('#boxIdProducts').val(prdId);
        $('#listProducts').slideUp(100);
        //validator();
    });
}

function eventsAction() {
    $('#boxProducts')
    .unbind('keyup')
    .on('keyup', function () {
        let text = $(this).val().toUpperCase();
        // console.log(text);
        
        sel_products(text);
    });
}