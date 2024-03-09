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
    let title = 'Lista de Tipos de Documentos';
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
            {data: 'storescode', class: 'codigo bold'},
            {data: 'storname', class: 'store-name'},
        ],
    });
}

// Solicita los productos de un almacen seleccionado
function getStores() {
    var pagina = 'DocumentType/GetDocumentTypes';
    var par = `[{"dot_id":""}]`;
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
    /**  ---- Presenta series relacionadas al almacen ----- */
    $('.toLink')
        .unbind('click')
        .on('click', function () {
            let strId = $(this).parents('tr').attr('id');
            let quant = $(this).html();
            let ctnme = $(this).parents('tr').children('td.store-name').html();
            strnme = ctnme;
            // console.log(strId, quant, ctnme);
            if (quant > 0) {
                setting_modalseries(strId);
            }
        });

    /**  ---- Acciones de Guardar categoria ----- */
    $('#GuardarAlmacen')
        .unbind('click')
        .on('click', function () {
            if (validaFormulario() == 1) {
                if ($('#IdDoc').val() == '') {
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
            $('#txtNomDoc').val('');
            $('#IdDoc').val('');
            $('#txtStatus option[value="0"]').attr('selected', true);
            $('#txtCodigo').val('');
        });
}

function fillTableStores(ix) {
    let tabla = $('#AreasTable').DataTable();
    // console.log(strs.length);
    if (strs[0].dot_id > 0) {
        tabla.row
        .add({
            editable: `<i class="fas fa-pen modif" id ="md${strs[ix].dot_id}"></i><i class="fas fa-times-circle kill"></i>`,
            storescode: strs[ix].dot_code,
            storname: strs[ix].dot_name,
        })
        .draw();
        $('#md' + strs[ix].dot_id)
            .parents('tr')
            .attr('id', strs[ix].dot_id);
    }
    actionButtons();
}

function saveStore() {
    var strName = $('#txtNomDoc').val();
    var strtype = $('#txtStatus').val();
    var codigo = $('#txtCodigo').val();
    var par = `
        [{  "dot_name"   : "${strName}",
            "dot_status"   : "${strtype}",
            "doc_code"         : "${codigo}"
        }]`;

    strs = '';
    var pagina = 'DocumentType/SaveDocumentType';
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
    var strId = $('#IdDoc').val();
    var strName = $('#txtNomDoc').val();
    var strType = $('#txtStatus option:selected').val();
    var codigo = $('#txtCodigo').val();
    var par = `
        [{  "dot_id"        : "${strId}",
            "dot_name"      : "${strName}",
            "dot_status"      : "${strType}",
            "doc_code"         : "${codigo}"
        }]`;

    strs = '';
    var pagina = 'DocumentType/UpdateDocumentType';
    var tipo = 'html';
    var selector = putUpdateStore;
    fillField(pagina, par, tipo, selector);
}
function putUpdateStore(dt) {
    getStores();
    if (strs.length > 0) {
        console.log(dt);
        let ix = goThroughStore(dt);

        $(`#${strs[ix].dot_id}`).children('td.store-name').html(strs[ix].dot_name);
        $(`#${strs[ix].dot_id}`).children('td.codigo').html(strs[ix].dot_code);

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
    $('#txtNomDoc').val(strs[ix].dot_name);
    $('#IdDoc').val(strs[ix].dot_id);
    $('#txtStatus').val(strs[ix].dot_status);
    $('#txtCodigo').val(strs[ix].dot_code);
}

function deleteStore(strId) {
    console.log(strId);
    let cn = $(`#${strId}`).children('td.quantity').children('.toLink').html();
        $('#confirmModal').modal('show');
        $('#confirmModalLevel').html('¿Seguro que desea borrar el tipo de documento?');
        $('#N').html('Cancelar');
        $('#confirmButton').html('Borrar tipo de documento').css({display: 'inline'});
        $('#Id').val(strId);
        $('#IdAlmacenBorrar').val(strId);

        $('#confirmButton').on('click', function () {
            var pagina = 'DocumentType/DeleteDocumentType';
            var par = `[{"dot_id":"${strId}"}]`;
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
    $('#LimpiarFormulario').trigger('click');
}

function goThroughStore(strId) {
    let inx = -1;
    $.each(strs, function (v, u) {
        if (strId == u.dot_id) inx = v;
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
