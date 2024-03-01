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
        deep_loading('O');
        settingTable();
        getlistProjects();
    } else {
        setTimeout(() => {
            inicial();
        }, 100);
    }

    setInterval(() => {
        activeIcons();
    }, 2000);
}

/** ---- PETICIÓN DE DATOS ----*/
function getlistProjects() {
    let liststat ="9,10,99";
    var pagina = 'Commons/listProjects';
    var par = `[{"liststat":"${liststat}"}]`;
    var tipo = 'json';
    var selector = putlistProjects;
    fillField(pagina, par, tipo, selector);
}

function getPaymentsAplied(pjtId) {
    var pagina = 'PaymentsApplied/listPaymentsApplied';
    var par = `[{"pjtId":"${pjtId}"}]`;
    var tipo = 'json';
    var selector = putPayments;
    fillField(pagina, par, tipo, selector);
}

/** ---- COLOCADORES DE DATOS ---- */
function settingTable() {
    let title = 'Lista de Formas de Pago';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    $('#tblPymApplied').DataTable({
        bDestroy: true,
        order: [
            [1, 'asc'],
        ],
        dom: 'Blfrtip',
        lengthMenu: [
            [50, 100, 200, -1],
            [50, 100, 200, 'Todos'],
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
            url: 'app/assets/lib/dataTable/spanish.json',
        },
        scrollY: 'calc(100vh - 200px)',
        scrollX: true,
        columns: [
            /* {data: 'editable', name: 'editable', class: 'edit', orderable: false}, */
            {data: 'subccode', name: 'subccode', class: 'supply'},
            {data: 'subcname', name: 'subcname', class: 'subName'},
            {data: 'subcname', name: 'subcname', class: 'subName'},
            {data: 'subcname', name: 'subcname', class: 'subName'},
            {data: 'catgcode', name: 'catgcode', class: 'catCode center'},
            {data: 'subcname', name: 'subcname', class: 'subName'},
            {data: 'catgcode', name: 'catgcode', class: 'catCode center'},
        ],
    });
    deep_loading('C');
   /*  activeIcons(); */
}

function putlistProjects(dt) {
    // console.log('putlistProjects',dt);
    if (dt[0].pjt_id > 0) {
        $('#lstProjects').html('');
        $.each(dt, function (v, u) {
            var H = `<option value="${u.pjt_id}">${u.pjt_id} - ${u.pjt_name}</option>`;
            $('#lstProjects').append(H);
        });
    }

    $('#lstProjects')
    .unbind('change')
    .on('change', function () {
        let lpjt = $(this).val();
        glbpjtid=lpjt;
        getPaymentsAplied(lpjt);

    });
}

/** ---- Almacena las subcategorias ---- */
function putPayments(dt) {
    console.log('1',dt);
    // $('#tblPymApplied tbody').html('');
    let tabla = $('#tblPymApplied').DataTable();
    tabla.rows().remove().draw();
    var prds=dt;
    if (prds[0].pym_id > 0) {
        
        $.each(prds, function (v, u) {
                tabla.row
                .add({
                    //editable: `<i class='fas fa-edit toLink' id ="${u.pjt_id}"></i><i class="fas fa-times-circle kill"></i>`,
                    subccode: u.pjt_name,
                    subcname: u.pym_folio,
                    subcname: mkn(u.pym_amount,'n'),
                    subcname: u.pym_date_paid,
                    catgcode: u.wtp_description,
                    subcname: u.pym_date_done,
                    catgcode: u.emp_reg,
                })
                .draw();
                /*  */
            }
        );
        // console.log('2', prds);
        activeIcons();
    } else {
        settingTable();
    }
}

/** +++++  Activa la accion de eventos */
function activeIcons() {
    $('#btnSave')
        .unbind('click')
        .on('click', function () {
            if (ValidForm() == 1) {
                if ($('#txtIdSubcategory').val() == '') {
                    
                } else {
                   
                }
            }
        });

    /**  ---- Limpia los campos del formulario ----- */
    $('#btnClean')
        .unbind('click')
        .on('click', function () {
            $('#lstProjects').val('');
            getPaymentsAplied();
            getlistProjects();
            
        });
}

function mkn(cf, tp) {
    let nm = cf;
    switch (tp) {
        case 'n':
            nm = formato_numero(cf, '2', '.', ',');
            break;
        case 'p':
            nm = formato_numero(cf, '1', '.', ',');
            break;
        default:
    }
    return nm;
}

