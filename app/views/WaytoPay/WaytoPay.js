let subs = null;
let sbnme = '';

$(document).ready(function () {
    if (verifica_usuario()) {
        inicial();
    }
});
//INICIO DE PROCESOS
function inicial() {
    if (altr == 1) {
        settingTable();
        getWaytoPay();
        // confirm_alert();
    } else {
        setTimeout(() => {
            inicial();
        }, 100);
    }

    // setInterval(() => {
    //     activeIcons();
    // }, 2000);
}

/** ---- Obtiene listado de subcategorias */
function getWaytoPay() {
    var pagina = 'WaytoPay/listWaytoPay';
    var par = '[{"parm":""}]';
    var tipo = 'json';
    var selector = putWaytoPay;
    fillField(pagina, par, tipo, selector);
}

/** ---- COLOCADORES DE DATOS ---- */
function settingTable() {
    let title = 'Lista de Formas de Pago';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    $('#tblWaypay').DataTable({
        order: [[1, 'asc']],
        dom: 'Blfrtip',
        lengthMenu: [
            [50, -1],
            [50, 'Todos'],
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
        ],
        pagingType: 'simple_numbers',
        language: {
            url: 'app/assets/lib/dataTable/Spanish.json',
        },
        scrollY: 'calc(100vh - 200px)',
        scrollX: true,
        columns: [
            {data: 'editable', name: 'editable', class: 'edit', orderable: false},
            {data: 'wayname', name: 'wayname', class: 'supply center bold'},
            {data: 'waycode', name: 'waycode', class: 'supply'},
            {data: 'waystat', name: 'waystat', class: 'supply center'},
        ],
    });
    // deep_loading('C');
   /*  activeIcons(); */
}

/** ---- Almacena las subcategorias ---- */
function putWaytoPay(dt) {
    subs=dt;
    // console.log('1',subs);
    var prds=dt;
    $('#tblWaypay tbody').html('');
   
    if (prds[0].wtp_id != '0') {
        
        $.each(prds, function (v, u) {
            var H = `
                <tr id="${u.wtp_id}">
                    <td class="edit"><i class='fas fa-pen modif'></i><i class="fas fa-times-circle kill"></i></td>    
                    <td class="sku" data-content="${u.wtp_clave}">${u.wtp_clave}</td>
                    <td class="supply">${u.wtp_description}</td>
                    <td class="sku">${u.wtp_status}</td>
                </tr>`;
            $('#tblWaypay tbody').append(H);
        });
     
        // settingTable();
        activeIcons();
    } else {
        // settingTable();
    }
}


/** +++++  Activa la accion de eventos */
function activeIcons() {
    /**  ---- Acciones de Guardar categoria ----- */
    $('#btnSave')
        .unbind('click')
        .on('click', function () {
            if (ValidForm() == 1) {
                if ($('#txtIdWayPay').val() == '') {
                    //console.log('Save');
                    // saveSubcategory();
                } else {
                    //console.log('Update');
                    // updateSubcategory();
                }
            }
        });

    /**  ---- Limpia los campos del formulario ----- */
    $('#btnClean')
        .unbind('click')
        .on('click', function () {
            $('#txtWtpDescription').val('');
            $('#txtIdWayPay').val('');
            $('#txtWtpCve').val('');
            $('#txtWtpStatus').val('');
        });

    /**  ---- Habilita los iconos de control de la tabla ----- */
    $('#tblWaypay tbody tr td.edit i')
        .unbind('click')
        .on('click', function () {
            let acc = $(this).attr('class').split(' ')[2];
            let wayId = $(this).closest('tr').attr('id');
            console.log('EDIT ',wayId)
            switch (acc) {
                case 'modif':
                    editSubcategory(wayId);
                    break;
                case 'kill':
                    DeleteWayPay(wayId);
                    break;
                default:
            }
        });

    /**  ---- Habilita el bullet de cantidad para consulta de existencias ----- */
    $('#tblWaypay tbody tr td.quantity .toLink')
        .unbind('click')
        .on('click', function () {
            selectSeries($(this));
        });
}

/** -------------------------------------------------------------------------- */
/** ---- Start GRABA NUEVA SUBCATEGORIA ---- */
/** ---- Registra la nueva subcategoria ---- */
function saveSubcategory() {
    let wayName = $('#txtWtpDescription').val().toUpperCase();
    let wayCode = $('#txtWtpCve').val().toUpperCase();
    let waystat = $('#txtWtpStatus').val();

    var par = `
    [{
        "wayName"   : "${wayName}",
        "wayCode"   : "${wayCode}",
        "waystat"     : "${waystat}"
    }]`;

    subs = null;
    var pagina = 'WaytoPay/SaveWaytoPay';
    var tipo = 'html';
    var selector = putSaveWaytoPay;
    fillField(pagina, par, tipo, selector);
}
/** ---- Agrega el nuevo registro a la tabla ---- */
function putSaveWaytoPay(dt) {
    if (subs != null) {
        $('#btnClean').trigger('click');
        let ix = goThroughSubcategory(dt);
        let tabla = $('#tblWaypay').DataTable();
        tabla.draw();
    } else {
        setTimeout(() => {
            getWaytoPay();
            // putSaveWaytoPay(dt);
        }, 100);
    }
}

/** -------------------------------------------------------------------------- */

function editSubcategory(wayId) {
    let ix = goThroughSubcategory(wayId);
    console.log('Se', ix);
    $('#txtWtpDescription').val(subs[ix].wtp_description);
    $('#txtIdWayPay').val(subs[ix].wtp_id);
    $('#txtWtpCve').val(subs[ix].wtp_clave);
    $('#txtWtpStatus').val(subs[ix].wtp_status);
}

function goThroughSubcategory(wayId) {
    let inx = -1;
    $.each(subs, function (v, u) {
        if (wayId == u.wtp_id) inx = v;
    });
    return inx;
}
/** ---- Actualiza la subcategoria seleccionada ---- */
function updateSubcategory() {
    var wayId = $('#txtIdWayPay').val();
    var wayName = $('#txtWtpDescription').val();
    var wayCode = $('#txtWtpCve').val();
    var waystat = $('#txtWtpStatus').val();
    var par = `
        [{
            "wayId"    : "${wayId}",
            "wayName"  : "${wayName}",
            "wayCode"  : "${wayCode}",
            "waystat"    : "${waystat}"
        }]`;
    //console.log('Datos : ', par);
    subs = null;
    var pagina = 'WaytoPay/UpdateWaytoPay';
    var tipo = 'html';
    var selector = putUpdateWaytoPay;
    fillField(pagina, par, tipo, selector);
}

/** ---- Actualiza el registro en la tabla de subcategorias ---- */
function putUpdateWaytoPay(dt) {
    if (subs != null) {
        let ix = goThroughSubcategory(dt);
        $('#btnClean').trigger('click');
        let tabla = $('#tblWaypay').DataTable();
        tabla.draw();
        // deep_loading('C');
    } else {
        setTimeout(() => {
            getWaytoPay();
            // putUpdateWaytoPay(dt);
        }, 100);
    }
}

/** -------------------------------------------------------------------------- */
function DeleteWayPay(wayId) {
    let cn = $(`#${wayId}`).children('td.quantity').children('.toLink').html();

    if (cn != 0) {
        $('#confirmModal').modal('show');
        $('#confirmModalLevel').html('No se puede borrar el registro, porque contiene existencias.');
        $('#N').html('Cancelar');
        $('#confirmButton').html('').css({display: 'none'});
        $('#Id').val(0);
    } else {
        $('#confirmModal').modal('show');

        $('#confirmModalLevel').html('¿Seguro que desea borrar la subcategoria?');
        $('#N').html('Cancelar');
        $('#confirmButton').html('Borrar subcategoria').css({display: 'inline'});
        $('#Id').val(wayId);
        console.log('BORRAR REGISTRO');
        $('#confirmButton').on('click', function () {
            var pagina = 'WaytoPay/DeleteWayPay';
            var par = `[{"wayId":"${wayId}"}]`;
            var tipo = 'html';
            var selector = putDeleteWayPay;
            fillField(pagina, par, tipo, selector);
        });
    }
}
/** ---- Elimina el registro de la subcategoria borrada ---- */
function putDeleteWayPay(dt) {
    console.log('BORRAR LINEA');
    getCategories();
    let tabla = $('#tblWaypay').DataTable();
    tabla
        .row($(`#${dt}`))
        .remove()
        .draw();
    $('#confirmModal').modal('hide');
}

