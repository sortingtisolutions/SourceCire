let strs = null;
let strnme = '';

$(document).ready(function () {
    if (verifica_usuario()) {
        inicial();
    }
});


function inicial() {
    if (altr == 1) {
        
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
            {data: 'email', class: 'email-main'},
        ],
    });
}

// Solicita los productos de un almacen seleccionado
function getStores() {
    var pagina = 'Commons/listAreas';
    var par = `[{"are_id":""}]`;
    var tipo = 'json';
    var selector = putStores;
    fillField(pagina, par, tipo, selector);
}

function putStores(dt) {
    // console.log('putStores',dt);
    strs = dt;
}

function fillStores() {
    if (strs != null) {
        let tabla = $('#AreasTable').DataTable();
        $.each(strs, function (v, u) {
            fillTableStores(v);
        });
        
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
                if ($('#IdArea').val() == '') {
                    if (validateEmail() == true) {
                        saveStore();
                    } else {
                        alert('Formato de email NO es valido');
                    }
                    // saveStore();
                } else {
                    updateStore();
                }
            }
        });
    /**  ---- Lismpia los campos ----- */
    $('#LimpiarFormulario')
        .unbind('click')
        .on('click', function () {
            $('#nomArea').val('');
            $('#IdArea').val('');
            $('#emailArea').val('');
            $('#selectTipoAlmacen option[value="0"]').attr('selected', true);
            $('#selectRowEncargado').val('');
            
        });
}

function fillTableStores(ix) {
    let tabla = $('#AreasTable').DataTable();
    // console.log('fillTableStores',strs.length);
    if (strs[0].are_id > 0) {
        tabla.row
        .add({
            editable: `<i class="fas fa-pen modif" id ="md${strs[ix].are_id}"></i><i class="fas fa-times-circle kill"></i>`,
            storesid: strs[ix].are_id,
            storname: strs[ix].are_name,
            email: strs[ix].are_email_main,
        })
        .draw();

        $('#md' + strs[ix].are_id)
            .parents('tr')
            .attr('id', strs[ix].are_id);
    }
    actionButtons();
}

function saveStore() {
    var strName = $('#nomArea').val();
    var stremail = $('#emailArea').val();
    var strtype = $('#selectTipoAlmacen').val();
    var par = `
        [{  "are_name"   : "${strName}",
            "are_status"   : "${strtype}",
            "are_email_main"   : "${stremail}"
        }]`;

    strs = '';
    // console.log('par', par);
    var pagina = 'Areas/SaveArea';
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
    var strId = $('#IdArea').val();
    var strName = $('#nomArea').val();
    var stremail = $('#emailArea').val();
    var strType = $('#selectTipoAlmacen option:selected').val();
    var par = `
        [{  "are_id"        : "${strId}",
            "are_name"      : "${strName}",
            "are_status"      : "${strType}",
            "are_email_main"   : "${stremail}"
        }]`;

    strs = '';
    // console.log('par', par);
    var pagina = 'Areas/UpdateArea';
    var tipo = 'html';
    var selector = putUpdateStore;
    fillField(pagina, par, tipo, selector);
}
function putUpdateStore(dt) {
    // console.log('putUpdateStore',dt);
    getStores();
    if (strs.length > 0) {
       
        let ix = goThroughStore(dt);
        // console.log('XXX- ',ix, ' >>',strs[ix].are_email_main);
        $(`#${strs[ix].are_id}`).children('td.store-name').html(strs[ix].are_name);
        $(`#${strs[ix].are_id}`).children('td.store-type').html(strs[ix].are_status);
        $(`#${strs[ix].are_id}`).children('td.email-main').html(strs[ix].are_email_main);
        //putQuantity(strs[ix].are_id);
        $('#LimpiarFormulario').trigger('click');
    } else {
        setTimeout(() => {
            putUpdateStore(dt);
        }, 100);
    }
}

function validateEmail(){    
    var validEmail =  /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;
    // Using test we can check if the text match the pattern
    if( validEmail.test( jQuery('#emailArea').val() ) ){
        return true;
    }else{
        return false;
    }
}

function editStore(strId) {
    // console.log('Editando');
    let ix = goThroughStore(strId);
    $('#nomArea').val(strs[ix].are_name);
    $('#IdArea').val(strs[ix].are_id);
    $('#emailArea').val(strs[ix].are_email_main);
    $('#selectTipoAlmacen').val(strs[ix].are_status);
}

function deleteStore(strId) {
    // console.log(strId);
        let cn = $(`#${strId}`).children('td.quantity').children('.toLink').html();

        $('#confirmModal').modal('show');
        $('#confirmModalLevel').html('¿Seguro que desea borrar el area?');
        $('#N').html('Cancelar');
        $('#confirmButton').html('Borrar area').css({display: 'inline'});
        $('#Id').val(strId);
        $('#IdAreaBorrar').val(strId);

        $('#confirmButton').on('click', function () {
            var pagina = 'Areas/DeleteArea';
            var par = `[{"are_id":"${strId}"}]`;
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
        if (strId == u.are_id) inx = v;
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
