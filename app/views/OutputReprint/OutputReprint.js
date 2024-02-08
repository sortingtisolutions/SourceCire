let products;
//let prjid = window.location.pathname.split("/").pop();
let gblprjid,user,usrid;
let prjid, v,u,n,em;
$(document).ready(function () {
    if (verifica_usuario()) {
        prjid=Cookies.get('pjtid');
        inicial();
    }
});
//INICIO DE PROCESOS
function inicial() {
    setting_table_AsignedProd();
    getDetailProds();
    user = Cookies.get('user').split('|');
    usrid = user[0];
    n = user[2];
    em = user[3];
    
     $('#cleanForm')
        .unbind('click')
        .on('click', function () {
            CleanCombos();
     });
}

// Solicita los productos del proyecto  OK
function getDetailProds() {
    var pagina = 'OutputReprint/listDetailProds';
    var par = `[{"pjt_id":"${prjid}"}]`;
    var tipo = 'json';
    var selector = putDetailsProds;
    fillField(pagina, par, tipo, selector);
}


// Configura la tabla de productos del proyecto

function setting_table_AsignedProd() {
    let title = 'Proyectos Atendidos';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    $('#tblAsignedProd').DataTable({
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
        scrollY: 'calc(100vh - 240px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
            {data: 'editable',  class: 'edit'},
            {data: 'pjtname',   class: 'pjtname supply'},
            {data: 'pjtnum',    class: 'pjtnum supply'},
            {data: 'pjttpy',    class: 'pjttpy sku'},
            {data: 'pjtfini',   class: 'pjtfini date'},
            {data: 'pjtffin',   class: 'pjtffin date'},
        ],
    });

    $('#recordChgUser').unbind('click').on('click', function () {
        let prjId = $('#txtIdProject').val();
        if (prjId != 0) {
            printOutPut(prjId);
        }
     });

}

// ### LISTO ### Llena la TABLA INICIAL de los detalles del proyecto
function putDetailsProds(dt) {
    console.log(dt);
    if (dt[0].pjt_id > 0)
    {
        let tabla = $('#tblAsignedProd').DataTable();
        
        tabla.rows().remove().draw();
        //$('#tblAsignedProd tbody').html('');
        $.each(dt, function (v, u){
            tabla.row
            .add({
                editable: `<i class='fas fa-edit toLink' id ="${u.pjt_id}"></i><i class="fas fa-times-circle kill"></i>`,
                pjtname: u.pjt_name,
                pjtnum: u.pjt_number,
                pjttpy: u.pjttp_name,
                pjtfini: u.pjt_date_start,
                pjtffin: u.pjt_date_end,
            })
            .draw();
            $('#' + u.pjt_id)
            .parents('tr')
            .attr('id', u.pjt_id)
            .attr('name', u.pjt_name);
        });
        activeIcons();
    }
}

// ### LISTO ###   habilita el botones para validar en TABLA INICIAL
function activeIcons() {
    // console.log('ActivaIcon');
    $('.toLink')
        .unbind('click')
        .on('click', function () {
            CleanCombos();
            let id = $(this).parents('tr');
            // console.log(id);
            let pjtid = id.attr('id');
            let pjtnm = id.children('td.pjtname').text();
            gblprjid=pjtid;
            console.log(prjid);
            // console.log('Cont-Producto', pjtid,pjtnm);
            if (pjtid > 0) {
                editProject(pjtid,pjtnm);
            }
        });
}

function editProject(pjtid,pjtnm) {
    $('#txtProjectName').val(pjtnm);
    $('#txtIdProject').val(pjtid);
}

function confirm_toChgUsr(pjtid) {
    $('#starClosure').modal('show');
    $('#txtIdClosure').val(pjtid);
    //borra paquete +
    $('#btnClosure').on('click', function () {
        $('#starClosure').modal('hide');
        // console.log('Datos CLICK',pjtid);
        datasUser(pjtid);
    });
}

function CleanCombos() {
    $('#txtProjectName').val('');
    $('#txtIdProject').val(0);
    $('#selUsrP').val(0);
    $('#selUsrA').val(0);
    $('#selUsrC').val(0);
}

function putupdateUser(dt){
    console.log('TERMINO ACTUALIZAR', dt);
    CleanCombos();
    let folio=dt;
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

/**********  Impresion de la salida de un proyecto ***********/  
function printOutPut(verId) {
    // let user = Cookies.get('user').split('|');
    // let u = user[0];
    // let n = user[2];
    let h = localStorage.getItem('host');
    let v = verId;
    // console.log('Datos', v, u, n, h);
    window.open(
        `${url}app/views/OutputReprint/OutputReprintContentReport.php?v=${v}&u=${u}&n=${n}&h=${h}&em=${em}`,
        '_blank'
    );
}
