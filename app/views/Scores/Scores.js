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
    let title = 'Lista de Areas Operativas de la Empresa';
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
            {data: 'storesid', class: 'strid bold'},
            {data: 'storname', class: 'store-name'},
        ],
    });
}

// Solicita los productos de un almacen seleccionado
function getStores() {
    var pagina = 'Scores/GetScores';
    var par = `[{"scr_id":""}]`;
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
                if ($('#IdScore').val() == '') {
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
            $('#txtValues').val('');
            $('#IdScore').val('');
            $('#txtDescription').val('');
            $('#selectRowEncargado').val('');
        });
}

function fillTableStores(ix) {
    let tabla = $('#AreasTable').DataTable();
    if (strs[0].scr_id > 0) {
        tabla.row
        .add({
            editable: `<i class="fas fa-pen modif" id ="md${strs[ix].scr_id}"></i><i class="fas fa-times-circle kill"></i>`,
            storesid: strs[ix].scr_values,
            storname: strs[ix].scr_description,
        })
        .draw();
        $('#md' + strs[ix].scr_id)
            .parents('tr')
            .attr('id', strs[ix].scr_id);
    } 
    actionButtons();
}

function saveStore() {
    var strName = $('#txtDescription').val();
    var strtype = $('#txtValues').val();
    var par = `
        [{  "scr_description"   : "${strName}",
            "scr_values"   : "${strtype}"
        }]`;

    strs = '';
    var pagina = 'Scores/SaveScore';
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
    var strId = $('#IdScore').val();
    var strName = $('#txtDescription').val();
    var strtype = $('#txtValues').val();
    var par = `
        [{  "scr_id"        : "${strId}",
            "scr_description"      : "${strName}",
            "scr_values"      : "${strtype}"
        }]`;

    strs = '';
    var pagina = 'Scores/UpdateScore';
    var tipo = 'html';
    var selector = putUpdateStore;
    fillField(pagina, par, tipo, selector);
}

function putUpdateStore(dt) {
    getStores();
    if (strs.length > 0) {
        console.log(dt);
        let ix = goThroughStore(dt);

        $(`#${strs[ix].scr_id}`).children('td.strid').html(strs[ix].scr_values);
        $(`#${strs[ix].scr_id}`).children('td.store-name').html(strs[ix].scr_description);

        //putQuantity(strs[ix].scr_id);
        $('#LimpiarFormulario').trigger('click');
    } else {
        setTimeout(() => {
            putUpdateStore(dt);
        }, 100);
    }
}

function editStore(strId) {
    // console.log('Editando');
    let ix = goThroughStore(strId);
    $('#txtValues').val(strs[ix].scr_values);
    $('#IdScore').val(strs[ix].scr_id);
    $('#txtDescription').val(strs[ix].scr_description);
}

function deleteStore(strId) {
    // console.log(strId);
    let cn = $(`#${strId}`).children('td.quantity').children('.toLink').html();
    $('#confirmModal').modal('show');
    $('#confirmModalLevel').html('¿Seguro que desea borrar la calificación?');
    $('#N').html('Cancelar');
    $('#confirmButton').html('Eliminar').css({display: 'inline'});
    $('#Id').val(strId);
    $('#IdAlmacenBorrar').val(strId);
    $('#confirmButton').on('click', function () {
        var pagina = 'Scores/DeleteScore';
        var par = `[{"scr_id":"${strId}"}]`;
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
        if (strId == u.scr_id) inx = v;
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
