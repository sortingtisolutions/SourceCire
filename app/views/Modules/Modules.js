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
    let title = 'Lista de Modulos Operativos en la Empresa';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    $('#ModulesTable').DataTable({
        order: [[1, 'asc']],
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
            {data: 'modCode', class: 'module-code'},
            {data: 'modName', class: 'module-name'},
            {data: 'modItem', class: 'module-item'},
            {data: 'modDescription', class: 'module-description'},
        ],
    });
}

// Solicita los productos de un almacen seleccionado
function getStores() {
    var pagina = 'Modules/GetModules';
    var par = `[{"mod_id":""}]`;
    var tipo = 'json';
    var selector = putStores;
    fillField(pagina, par, tipo, selector);
}

function putStores(dt) {
    strs = dt;
}
function fillStores() {
    if (strs != null) {
        let tabla = $('#ModulesTable').DataTable();
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
            let ctnme = $(this).parents('tr').children('td.module-name').html();
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
                if ($('#txtIdModule').val() == '') {
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
            $('#txtNamModule').val('');
            $('#txtIdModule').val('');
            $('#txtCode').val('');
            $('#txtNameItems').val('');
            $('#txtDescription').val('');
        });
}

function fillTableStores(ix) {
    let tabla = $('#ModulesTable').DataTable();
    tabla.row
        .add({
            editable: `<i class="fas fa-pen modif" id ="mod${strs[ix].mod_id}"></i><i class="fas fa-times-circle kill"></i>`,
            modCode: strs[ix].mod_code,
            modName: strs[ix].mod_name,
            modItem: strs[ix].mod_item,
            modDescription: strs[ix].mod_description,
        })
        .draw();
    $('#mod' + strs[ix].mod_id)
        .parents('tr')
        .attr('id', strs[ix].mod_id);
    actionButtons();
}

function saveStore() {
    var txtNamModule = $('#txtNamModule').val();
    var txtCode = $('#txtCode').val();
    var txtNameItems = $('#txtNameItems').val();
    var txtDescription = $('#txtDescription').val();
   // var strtype = $('#selectTipoAlmacen').val();
    var par = `
        [{  "mod_name"   : "${txtNamModule}",
            "mod_code"   : "${txtCode}",
            "mod_item"   : "${txtNameItems}",
            "mod_description"   : "${txtDescription}"
        }]`;

    strs = '';
    var pagina = 'Modules/SaveModule';
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
    
    var txtIdModule = $('#txtIdModule').val();
    var txtNamModule = $('#txtNamModule').val();
    var txtCode = $('#txtCode').val();
    var txtNameItems = $('#txtNameItems').val();
    var txtDescription = $('#txtDescription').val();
    var par = `
        [{  "mod_id"        : "${txtIdModule}",
            "mod_name"      : "${txtNamModule}",
            "mod_code"      : "${txtCode}",
            "mod_item"      : "${txtNameItems}",
            "mod_description"      : "${txtDescription}"
        }]`;

    strs = '';
    var pagina = 'Modules/UpdateModule';
    var tipo = 'html';
    var selector = putUpdateStore;
    fillField(pagina, par, tipo, selector);
}
function putUpdateStore(dt) {
    getStores();
    if (strs.length > 0) {
        let ix = goThroughStore(dt);

        $(`#${strs[ix].mod_id}`).children('td.module-name').html(strs[ix].mod_name);
        $(`#${strs[ix].mod_id}`).children('td.module-code').html(strs[ix].mod_code);
        $(`#${strs[ix].mod_id}`).children('td.module-item').html(strs[ix].mod_item);
        $(`#${strs[ix].mod_id}`).children('td.module-description').html(strs[ix].mod_description);

        //putQuantity(strs[ix].are_id);
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
    $('#txtNamModule').val(strs[ix].mod_name);
    $('#txtIdModule').val(strs[ix].mod_id);
    $('#txtCode').val(strs[ix].mod_code);
    $('#txtNameItems').val(strs[ix].mod_item);
    $('#txtDescription').val(strs[ix].mod_description);
    
}

function deleteStore(strId) {
    let cn = $(`#${strId}`).children('td.quantity').children('.toLink').html();

    
        $('#confirmModal').modal('show');

        $('#confirmModalLevel').html('¿Seguro que desea borrar el Module?');
        $('#N').html('Cancelar');
        $('#confirmButton').html('Borrar modulo').css({display: 'inline'});
        $('#Id').val(strId);

        
        $('#IdAlmacenBorrar').val(strId);

        $('#confirmButton').on('click', function () {
            var pagina = 'Modules/DeleteModule';
            var par = `[{"mod_id":"${strId}"}]`;
            var tipo = 'html';
            var selector = putDeleteStore;
            fillField(pagina, par, tipo, selector);
        });
    
}

function putDeleteStore(dt) {
    getStores();
    let tabla = $('#ModulesTable').DataTable();
    tabla
        .row($(`#${dt}`))
        .remove()
        .draw();
    $('#confirmModal').modal('hide');
}

function goThroughStore(strId) {
    let inx = -1;
    $.each(strs, function (v, u) {
        if (strId == u.mod_id) inx = v;
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
