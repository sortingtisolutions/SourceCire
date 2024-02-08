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
        getMenuParents();
        getModules();
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
    let title = 'Lista de menús';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    $('#MenuTable').DataTable({
        order: [[1, 'asc']],
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
            {data: 'men_parent', class: 'men-parent bold'},
            {data: 'men_items', class: 'men-items'},
            {data: 'men_description', class: 'men-description'},
            {data: 'men_order', class: 'men-order'},
            {data: 'men_module', class: 'men-module'},
        ],
    });
}

// Solicita los productos de un almacen seleccionado
function getStores() {
    var pagina = 'ModMenu/GetMenus';
    var par = `[{"mnu_id":""}]`;
    var tipo = 'json';
    var selector = putStores;
    fillField(pagina, par, tipo, selector);
}
function getMenuParents() {
    var pagina = 'ModMenu/listMenuParents';
    var par = `[{"store":""}]`;
    var tipo = 'json';
    var selector = putMenuParents;
    fillField(pagina, par, tipo, selector);
}

function getModules() {
    var pagina = 'ModMenu/listModules';
    var par = `[{"store":""}]`;
    var tipo = 'json';
    var selector = putModules;
    fillField(pagina, par, tipo, selector);
}
function putStores(dt) {
    strs = dt;
}
function putMenuParents(dt) {
    if (dt[0].mnu_id != 0) {
        $.each(dt, function (v, u) {
            let H = `<option value="${u.mnu_id}">${u.mnu_item}</option>`;
            $('#mnuParent').append(H);
        });
    }
}

function putModules(dt) {
    if (dt[0].mod_id != 0) {
        $.each(dt, function (v, u) {
            let H = `<option value="${u.mod_id}">${u.mod_code} - ${u.mod_name}</option>`;
            $('#txtModule').append(H);
        });
    }
}
function fillStores() {
    if (strs != null) {
        let tabla = $('#MenuTable').DataTable();
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
                if ($('#txtIdMenu').val() == '') {
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
            $('#txtIdMenu').val('');
            $('#mnuParent').val('');
            $('#txtItems').val('');
            $('#txtDescription').val('');
            $('#txtOrder').val('');
            $('#txtModule').val('');
        });
}

function fillTableStores(ix) {
    let tabla = $('#MenuTable').DataTable();
    // console.log(strs.length);
    tabla.row
        .add({
            editable: `<i class="fas fa-pen modif" id ="md${strs[ix].mnu_id}"></i><i class="fas fa-times-circle kill"></i>`,
            men_parent: strs[ix].mnu_parent,
            men_items: strs[ix].mnu_item,
            men_description: strs[ix].mnu_description,
            men_order: strs[ix].mnu_order,
            men_module: strs[ix].mod_name,
        })
        .draw();
    $('#md' + strs[ix].mnu_id).parents('tr').attr('id', strs[ix].mnu_id);
    actionButtons();
}

function saveStore() {
    var mnuParent = $('#mnuParent').val();
    var txtItems = $('#txtItems').val();
    var txtDescription = $('#txtDescription').val();
    var txtOrder = $('#txtOrder').val();
    var txtModule = $('#txtModule').val();
    //var strtype = $('#selectTipoAlmacen').val();
    var par = `
        [{  "mnuParent"   : "${mnuParent}",
            "mnuItems"   : "${txtItems}",
            "mnuDescription"   : "${txtDescription}",
            "mnuOrder"   : "${txtOrder}",
            "mnuModule"   : "${txtModule}"
        }]`;

    strs = '';
    // console.log(par);
    var pagina = 'ModMenu/SaveMenu';
    var tipo = 'html';
    var selector = putSaveStore;
    fillField(pagina, par, tipo, selector);
}
function putSaveStore(dt) {
    // console.log(dt);
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
    var mnuId = $('#txtIdMenu').val();
    var mnuParent = $('#mnuParent').val();
    var txtItems = $('#txtItems').val();
    var txtDescription = $('#txtDescription').val();
    var txtOrder = $('#txtOrder').val();
    var txtModule = $('#txtModule').val();
    if (txtModule == 0) {
        txtModule=999;
    }
    var par = `
        [{  "mnuId"        : "${mnuId}",
            "mnuParent"      : "${mnuParent}",
            "mnuItems"      : "${txtItems}",
            "mnuDescription"      : "${txtDescription}",
            "mnuOrder"      : "${txtOrder}",
            "mnuModule"      : "${txtModule}"
        }]`;

    strs = '';
    var pagina = 'ModMenu/UpdateMenu';
    var tipo = 'html';
    var selector = putUpdateStore;
    fillField(pagina, par, tipo, selector);
}
function putUpdateStore(dt) {
    getStores();
    if (strs.length > 0) {
        // console.log(dt);
        let ix = goThroughStore(dt);

        $(`#${strs[ix].mnu_id}`).children('td.men-parent').html(strs[ix].mnu_parent);
        $(`#${strs[ix].mnu_id}`).children('td.men-items').html(strs[ix].mnu_item);
        $(`#${strs[ix].mnu_id}`).children('td.men-description').html(strs[ix].mnu_description);
        $(`#${strs[ix].mnu_id}`).children('td.men-order').html(strs[ix].mnu_order);
        $(`#${strs[ix].mnu_id}`).children('td.men-module').html(strs[ix].mod_name);

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
    $('#txtIdMenu').val(strs[ix].mnu_id);
    $('#mnuParent').val(strs[ix].mnu_parent);
    $('#txtItems').val(strs[ix].mnu_item);
    $('#txtDescription').val(strs[ix].mnu_description);
    $('#txtOrder').val(strs[ix].mnu_order);
    $('#txtModule').val(strs[ix].mod_id);
}

function deleteStore(strId) {
    // console.log(strId);
    let cn = $(`#${strId}`).children('td.quantity').children('.toLink').html();
        $('#confirmModal').modal('show');
        $('#confirmModalLevel').html('¿Seguro que desea borrar el Menu?');
        $('#N').html('Cancelar');
        $('#confirmButton').html('Borrar menu').css({display: 'inline'});
        $('#Id').val(strId);
        $('#IdAlmacenBorrar').val(strId);

        $('#confirmButton').on('click', function () {
            var pagina = 'ModMenu/DeleteMenu';
            var par = `[{"mnu_id":"${strId}"}]`;
            var tipo = 'html';
            var selector = putDeleteStore;
            fillField(pagina, par, tipo, selector);
        });
    
}

function putDeleteStore(dt) {
    getStores();
    let tabla = $('#MenuTable').DataTable();
    tabla
        .row($(`#${dt}`))
        .remove()
        .draw();
    $('#confirmModal').modal('hide');
}

function goThroughStore(strId) {
    let inx = -1;
    $.each(strs, function (v, u) {
        if (strId == u.mnu_id) inx = v;
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
