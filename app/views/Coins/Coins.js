let strs = null;
let strnme = '';

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
    let title = 'Lista de Monedas';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    $('#AreasTable').DataTable({
        order: [[1, 'asc']],
        dom: 'Blfrtip',
        lengthMenu: [
            [50, 100, -1],
            [50, 100, 'Todos'],
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
            {data: 'quantity', class: 'quantity'},
        ],
    });
}

// Solicita los productos de un almacen seleccionado
function getStores() {
    var pagina = 'Coins/GetCoins';
    var par = `[{"cin_id":""}]`;
    var tipo = 'json';
    var selector = putStores;
    fillField(pagina, par, tipo, selector);
}

function putStores(dt) {
    strs = dt;
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
            $('#CoinsNumber').val('');
            $('#CoinsCode').val('');
        });
}

function fillTableStores(ix) {
    let tabla = $('#AreasTable').DataTable();
    console.log(strs);
    tabla.row
        .add({
            editable: `<i class="fas fa-pen modif" id ="md${strs[ix].cin_id}"></i><i class="fas fa-times-circle kill"></i>`,
            code: strs[ix].cin_code,
            storname: strs[ix].cin_name,
            quantity: strs[ix].cin_number,
        })
        .draw();
    $('#md' + strs[ix].cin_id)
        .parents('tr')
        .attr('id', strs[ix].cin_id);
    actionButtons();
}

function saveStore() {
    var strName = $('#NomAlmacen').val();
    var CoinsNumber = $('#CoinsNumber').val();
    var CoinsCode = $('#CoinsCode').val();
    var par = `
        [{  "coinName"   : "${strName}",
            "coinsNumber"   : "${CoinsNumber}",
            "coinsCode"   : "${CoinsCode}"
        }]`;

    strs = '';
    console.log(par);
    var pagina = 'Coins/SaveCoin';
    var tipo = 'html';
    var selector = putSaveStore;
    fillField(pagina, par, tipo, selector);
}
function putSaveStore(dt) {
    getStores();
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
    var strId = $('#IdAlmacen').val();
    var strName = $('#NomAlmacen').val();
    var CoinsNumber = $('#CoinsNumber').val();
    var CoinsCode = $('#CoinsCode').val();
    var par = `
        [{  "coin_id"        : "${strId}",
            "coin_name"      : "${strName}",
            "coin_number"      : "${CoinsNumber}",
            "coin_code"      : "${CoinsCode}"
        }]`;
    console.log(par);
    strs = '';
    var pagina = 'Coins/UpdateCoin';
    var tipo = 'html';
    var selector = putUpdateStore;
    fillField(pagina, par, tipo, selector);
}
function putUpdateStore(dt) {
    getStores();
    if (strs.length > 0) {
        console.log(dt);
        let ix = goThroughStore(dt);
        $(`#${strs[ix].cin_id}`).children('td.store-name').html(strs[ix].cin_name);
        $(`#${strs[ix].cin_id}`).children('td.strid').html(strs[ix].cin_code);
        $(`#${strs[ix].cin_id}`).children('td.quantity').html(strs[ix].cin_number); 
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
    $('#NomAlmacen').val(strs[ix].cin_name);
    $('#IdAlmacen').val(strs[ix].cin_id);
    $('#CoinsNumber').val(strs[ix].cin_number);
    $('#CoinsCode').val(strs[ix].cin_code);
}

function deleteStore(strId) {
    console.log(strId);
    let cn = $(`#${strId}`).children('td.quantity').children('.toLink').html();

    
        $('#confirmModal').modal('show');

        $('#confirmModalLevel').html('¿Seguro que desea borrar la moneda?');
        $('#N').html('Cancelar');
        $('#confirmButton').html('Borrar almacen').css({display: 'inline'});
        $('#Id').val(strId);

        //   $('#BorrarAlmacenModal').modal('show');
        $('#IdAlmacenBorrar').val(strId);

        $('#confirmButton').on('click', function () {
            var pagina = 'Coins/DeleteCoin';
            var par = `[{"cin_id":"${strId}"}]`;
            var tipo = 'html';
            var selector = putDeleteStore;
            fillField(pagina, par, tipo, selector);
        });
    
}

function putDeleteStore(dt) {
    getStores();
    let tabla = $('#AreasTable').DataTable();
    tabla
        .row($(`#${dt}`))
        .remove()
        .draw();
    $('#confirmModal').modal('hide');
}

function goThroughStore(strId) {
    console.log(strs);
    let inx = -1;
    $.each(strs, function (v, u) {
        if (strId == u.cin_id) inx = v;
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
