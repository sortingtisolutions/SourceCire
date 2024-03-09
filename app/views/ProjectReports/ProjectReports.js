let pjts = null;
let bn = 0;
// const exp = $('#txtExpendab');
// const man = $('#txtMaintenance');
// const dis = $('#txtDiscount');
// const com = $('#txtComments');
// const tblprod = $('#tblProducts');

const fchini = $('#fechaInitial');
const fchfin = $('#fechaEnd');
const findana = $('#txtAnalyst');
const findcli = $('#txtClient');
// const totals = $('#totals');

const size = [
    { s: 20 },
    { s: 250 },
    { s: 250 },
    { s: 100 },
    { s: 100 },
    { s: 500 },
];

$('document').ready(function () {
    url = getAbsolutePath();
    if (verifica_usuario()) {
        inicial();
    }
});

//INICIO DE PROCESOS
function inicial() {

    if (altr == 1) {
        deep_loading('C');
        settingTable();
        getAnalysts();
        getCustomers();
        getSuppliers();
        $('#btn_generate')
        .unbind('click')
        .on('click', function () {
            let pjtId = '0';
            findCampos();
            // getProjectContent(pjtId);
        });

        $('#btn_print')
        .unbind('click')
        .on('click', function () {
            let pjtId = '0';
            // console.log('Print');
            printReport();
            // getProjectContent(pjtId);
        });

        $('#txtReport')
        .on('change', function () {
            if($('#txtReport').val() == 8){
                $('#txtSupplier').parents().removeClass('objHidden');
                $('#txtAnalyst').parent().addClass('objHidden');
                $('#txtClient').parent().addClass('objHidden');
            }else{
                $('#txtSupplier').parent().addClass('objHidden');
                $('#txtAnalyst').parent().removeClass('objHidden');
                $('#txtClient').parent().removeClass('objHidden');
            }
        });
    } else {
        setTimeout(() => {
            inicial();
        }, 100);
    }
}

function settingTable() {
    let title = 'Lista de Proyectos';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    // console.log('PASO 1');
    $('#tblProducts').DataTable({
        bDestroy: true,
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
        scrollY: 'calc(100vh - 200px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
            { data: 'nameproject', class: 'sku' },
            { data: 'customers', class: 'category-name' },
            { data: 'developer', class: 'store-name' },
            { data: 'typecall', class: 'quantity' },
            { data: 'address', class: 'store-name' },
            { data: 'location', class: 'quantity' },
            { data: 'cfdi', class: 'sku' },
            { data: 'discount', class: 'category-name' },
            { data: 'dummy', class: 'store-name' },
            { data: 'loads', class: 'quantity' },
            { data: 'downloads', class: 'store-name' },
            { data: 'manager', class: 'quantity' },
            { data: 'transports', class: 'store-name' },
        ],
    });
}

function settingTable2() {
    let title = 'Lista de Productos';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    // console.log('PASO 1');
    $('#tblEquiposMasUsados').DataTable({
        bDestroy: true,
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
        scrollY: 'calc(100vh - 200px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
            { data: 'namedev', class: 'store-name left', orderable: false  },
            { data: 'serie', class: 'category-name' },
            { data: 'namepjt', class: 'store-name' },
            { data: 'time', class: 'quantity' },
            { data: 'location', class: 'quantity' },
            { data: 'quantity', class: 'quantity' },
        ],
    });
}
function settingTable3() {
    let title = 'Lista de Subarrendos';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    // console.log('PASO 1');
    $('#tblSubarrendos').DataTable({
        bDestroy: true,
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
        scrollY: 'calc(100vh - 200px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
            { data: 'namepjt', class: 'store-name left', orderable: false  },
            { data: 'sku', class: 'category-name' },
            { data: 'serie', class: 'store-name' },
            { data: 'project', class: 'quantity' },
            { data: 'time', class: 'quantity' },
            { data: 'supplier', class: 'quantity' },
            { data: 'location', class: 'quantity' },
        ],
    });
}

function settingTable4() {
    let title = 'Lista de Equipos Menos Usados';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    // console.log('PASO 1');
    $('#tblEquiposMenosUsados').DataTable({
        bDestroy: true,
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
        scrollY: 'calc(100vh - 200px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
            { data: 'namedev', class: 'store-name left', orderable: false  },
            { data: 'serie', class: 'category-name' },
            { data: 'namepjt', class: 'store-name' },
            { data: 'time', class: 'quantity' },
        ],
    });
}
function settingTable5() {
    let title = 'Lista de Equipos Menos Usados';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    // console.log('PASO 1');
    $('#tblPatrocinios').DataTable({
        bDestroy: true,
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
        scrollY: 'calc(100vh - 200px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
            { data: 'Cliente', class: 'store-name left', orderable: false  },
            { data: 'Proyecto', class: 'category-name' },
            { data: 'Tipo', class: 'store-name' },
            { data: 'Fechas', class: 'quantity' },
            { data: 'Descuento', class: 'category-name' },
            { data: 'Programador', class: 'quantity' },
        ],
    });
}
function settingTable6() {
    let title = 'Lista de Cierres';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    // console.log('PASO 1');
    $('#tblCierres').DataTable({
        bDestroy: true,
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
        scrollY: 'calc(100vh - 200px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
            { data: 'Cliente', class: 'store-name left' },
            { data: 'Proyecto', class: 'category-name' },
            { data: 'Tipo', class: 'store-name' },
            { data: 'Fechas', class: 'quantity' },
        ],
    });
}

function settingTable7() {
    let title = 'Lista de Proveedores de Subarrendo';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    // console.log('PASO 1');
    $('#tblProvSubarrendo').DataTable({
        bDestroy: true,
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
        scrollY: 'calc(100vh - 200px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
            { data: 'nombre', class: 'store-name left', orderable: false  },
            { data: 'cantidad', class: 'category-name' },
            { data: 'equipos', class: 'store-name' },
            { data: 'periodo', class: 'quantity' },
        ],
    });
}
function settingTable8() {
    let title = 'Lista de Subarrendos';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    // console.log('PASO 1');
    $('#tblCustomers').DataTable({
        bDestroy: true,
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
        scrollY: 'calc(100vh - 200px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
            { data: 'Nombre', class: 'store-name left', orderable: false  },
            { data: 'Proyecto', class: 'category-name' },
            { data: 'Monto', class: 'store-name' },
            { data: 'rango', class: 'quantity' },
            { data: 'Contacto', class: 'quantity' },
            { data: 'Programador', class: 'quantity' },
        ],
    });
}
function settingTable9() {
    let title = 'Lista de Cierres';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    // console.log('PASO 1');
    $('#tblProjWorked').DataTable({
        bDestroy: true,
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
        scrollY: 'calc(100vh - 200px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
            { data: 'Cliente', class: 'store-name left', orderable: false  },
            { data: 'Proyecto', class: 'category-name' },
            { data: 'Tipo', class: 'store-name' },
            { data: 'Descuento', class: 'quantity' },
            { data: 'Programador', class: 'store-name' },
        ],
    });
}
function settingTable10() {
    let title = 'Lista de Subarrendos';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    // console.log('PASO 1');
    $('#tblProductivity').DataTable({
        bDestroy: true,
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
        scrollY: 'calc(100vh - 200px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
            { data: 'nombre', class: 'store-name left' },
            { data: 'cantidad', class: 'category-name' },
            { data: 'cotizacion', class: 'store-name' },
            { data: 'presupuesto', class: 'quantity' },
            { data: 'proyecto', class: 'quantity' },
        ],
    });
}
function settingTable11() {
    let title = 'Proyectos por programador';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    // console.log('PASO 1');
    $('#tblProjDevelop').DataTable({
        bDestroy: true,
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
        scrollY: 'calc(100vh - 200px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
            { data: 'namedev', class: 'category-name', orderable: false  },
            { data: 'namepjt', class: 'category-name' },
            { data: 'type', class: 'store-name' },
            { data: 'date', class: 'quantity' },
        ],
    });
}
// Obtiene el listado de los proyectos en etapa de pryecto
function getAnalysts() {
    let data = [
        { pjtId: '', },
    ];
    var pagina = 'ProjectReports/listAnalysts';
    var par = '[{"parm":""}]';
    var tipo = 'JSON';
    var selector = putAnalyst;
    fillField(pagina, par, tipo, selector);
}

function putAnalyst(dt) {
    if (dt[0].emp_id > 0) {
        $.each(dt, function (v, u) {
            let H = `<option value="${u.emp_id}">${u.emp_fullname}</option>`;
            findana.append(H);
        });
    }
}

function getCustomers() {
    let data = [
        { pjtId: '', },
    ];
    var pagina = 'ProjectReports/listCustomers';
    var par = '[{"parm":""}]';
    var tipo = 'JSON';
    var selector = putCustomers;
    fillField(pagina, par, tipo, selector);
}

function putCustomers(dt) {
    if (dt[0].cus_id > 0) {
        $.each(dt, function (v, u) {
            let H = `<option value="${u.cus_id}">${u.cus_name}</option>`;
            findcli.append(H);
        });
    }

}

function getSuppliers() {
    // let data = [{ pjtId: '', }];
    var pagina = 'Commons/listSuppliers';
    var par = '[{"parm":""}]';
    var tipo = 'JSON';
    var selector = putSuppliers;
    fillField(pagina, par, tipo, selector);
}

function putSuppliers(dt) {
    if (dt[0].sup_id > 0) {
        $.each(dt, function (v, u) {
            let H = `<option value="${u.sup_id}">${u.sup_business_name}</option>`;
            $('#txtSupplier').append(H);
        });
    }

}

function getProjectContent(fechaIni,fechaFin,findAna,findCli,bandera) {
    let data = [
        {
            fechaIni: fechaIni,
            fechaFin: fechaFin,
            findAna: findAna,
            findCli: findCli,
            bandera: bandera,
            bn : bn
        },
    ];
    if(bandera > 0){
        var pagina = 'ProjectReports/projectContent';
        var par = `[{"fechaIni":"${fechaIni}", "fechaFin":"${fechaFin}", "findAna":"${findAna}", "findCli":"${findCli}", "bandera":"${bandera}", "bn":"${bn}" }]`;
        var tipo = 'JSON';
        var selector = putProjectContent;
        fillField(pagina, par, tipo, selector);
    }


    function putProjectContent(dt) {
        // console.log('Registros', dt);
        let tabla;

        switch (bn) {
            case '1':
                tabla = $('#tblProducts').DataTable();
                tabla.rows().remove().draw();
                if (dt[0].pjt_id > 0) {
                    $.each(dt, function (v, u) {
                        let discount = parseFloat(u.discount);
                        let transport;
                        let cfdi;
                        let empleados;
                        if(u.transport >0){
                            transport = 'Sí';
                        }else{
                            transport ='No';
                        }
                        if(u.cfdi >0){
                            cfdi = 'Sí';
                        }else{
                            cfdi ='No';
                        }
                        if(u.empleados >1){
                            empleados = 'Sí';
                        }else{
                            empleados ='No';
                        }
                        tabla.row
                    .add({
                        nameproject: u.pjt_name,
                        customers: u.cus_name,
                        developer:u.emp_fullname,
                        typecall: u.pjttc_name,
                        address: u.pjt_location,
                        location: u.pjttp_name,
                        cfdi: cfdi,
                        discount: discount,
                        dummy: u.pjt_test_look,
                        loads: u.pjt_to_carry_on,
                        downloads: u.pjt_to_carry_out,
                        manager: empleados,
                        transports: transport,
                    })
                    .draw();
                    });
                }
                $('#tblProjDevelop').parents().addClass('objHidden');
                $('#tblEquiposMasUsados').parents().addClass('objHidden');
                $('#tblEquiposMenosUsados').parents().addClass('objHidden');
                $('#tblCierres').parents().addClass('objHidden');

                $('#tblPatrocinios').parents().addClass('objHidden');
                $('#tblProjWorked').parents().addClass('objHidden');
                $('#tblProvSubarrendo').parents().addClass('objHidden');
                $('#tblSubarrendos').parents().addClass('objHidden');
                $('#tblProductivity').parents().addClass('objHidden');

                $('#tblCustomers').parents().addClass('objHidden');
                $('#tblProducts').parents().removeClass('objHidden');

                break;
                case '2':
                    settingTable5();
                    tabla = $('#tblPatrocinios').DataTable();
                    tabla.rows().remove().draw();
                    if (dt[0].cus_id > 0) {
                        $.each(dt, function (v, u) {
                            tabla.row
                        .add({
                            Cliente: u.cus_name,
                            Proyecto: u.pjt_name,
                            Tipo: u.pjttp_name,
                            Fechas:u.dates,
                            Descuento: u.discount,
                            Programador: u.emp_fullname,
                        })
                        .draw();
                        });


                    }
                    $('#tblProducts').parents().addClass('objHidden');
                    $('#tblProjDevelop').parents().addClass('objHidden');
                    $('#tblEquiposMasUsados').parents().addClass('objHidden');
                    $('#tblEquiposMenosUsados').parents().addClass('objHidden');

                    $('#tblCierres').parents().addClass('objHidden');
                    $('#tblProjWorked').parents().addClass('objHidden');
                    $('#tblProvSubarrendo').parents().addClass('objHidden');
                    $('#tblSubarrendos').parents().addClass('objHidden');
                    $('#tblProductivity').parents().addClass('objHidden');

                    $('#tblCustomers').parents().addClass('objHidden');

                    $('#tblPatrocinios').parents().removeClass('objHidden');
                    break;
                case '3':
                    settingTable6();
                    tabla = $('#tblCierres').DataTable();
                    tabla.rows().remove().draw();
                    if (dt[0].cus_id > 0) {
                        $.each(dt, function (v, u) {
                            tabla.row
                        .add({
                            Cliente: u.cus_name,
                            Proyecto: u.pjt_name,
                            Tipo:u.pjttp_name,
                            Fechas: u.dates,
                        })
                        .draw();
                        });

                    }
                    $('#tblProducts').parents().addClass('objHidden');
                    $('#tblProjDevelop').parents().addClass('objHidden');
                    $('#tblEquiposMasUsados').parents().addClass('objHidden');
                    $('#tblEquiposMenosUsados').parents().addClass('objHidden');

                    $('#tblPatrocinios').parents().addClass('objHidden');
                    $('#tblProjWorked').parents().addClass('objHidden');
                    $('#tblProvSubarrendo').parents().addClass('objHidden');
                    $('#tblSubarrendos').parents().addClass('objHidden');
                    $('#tblProductivity').parents().addClass('objHidden');

                    $('#tblCustomers').parents().addClass('objHidden');
                    $('#tblCierres').parents().removeClass('objHidden');
                    break;
                case '4':
                    settingTable2();
                    tabla = $('#tblEquiposMasUsados').DataTable();
                    tabla.rows().remove().draw();
                    if (dt[0].ser_id > 0) {
                        $.each(dt, function (v, u) {
                            tabla.row
                        .add({
                            namedev: u.prd_name,
                            serie: u.ser_sku,
                            namepjt:u.pjt_name,
                            time: u.tiempo,
                            location: u.loc_type_location,
                            quantity: u.ser_reserve_count,
                        })
                        .draw();
                        });

                    }
                    $('#tblProducts').parents().addClass('objHidden');
                    $('#tblProjDevelop').parents().addClass('objHidden');
                    $('#tblEquiposMenosUsados').parents().addClass('objHidden');
                    $('#tblCierres').parents().addClass('objHidden');

                    $('#tblPatrocinios').parents().addClass('objHidden');
                    $('#tblProjWorked').parents().addClass('objHidden');
                    $('#tblProvSubarrendo').parents().addClass('objHidden');
                    $('#tblSubarrendos').parents().addClass('objHidden');
                    $('#tblProductivity').parents().addClass('objHidden');

                    $('#tblCustomers').parents().addClass('objHidden');
                    $('#tblEquiposMasUsados').parents().removeClass('objHidden');
                    break;
                case '5':
                    settingTable9();
                    tabla = $('#tblProjWorked').DataTable();
                    tabla.rows().remove().draw();
                    if (dt[0].cus_id > 0) {
                        $.each(dt, function (v, u) {
                            tabla.row
                        .add({
                            Cliente: u.cus_name,
                            Proyecto: u.pjt_name,
                            Tipo: u.pjttp_name,
                            Descuento:u.discount,
                            Programador: u.emp_fullname,
                        })
                        .draw();
                        });

                    }
                    $('#tblProducts').parents().addClass('objHidden');
                    $('#tblProjDevelop').parents().addClass('objHidden');
                    $('#tblEquiposMasUsados').parents().addClass('objHidden');
                    $('#tblEquiposMenosUsados').parents().addClass('objHidden');

                    $('#tblCierres').parents().addClass('objHidden');
                    $('#tblPatrocinios').parents().addClass('objHidden');
                    $('#tblProvSubarrendo').parents().addClass('objHidden');
                    $('#tblSubarrendos').parents().addClass('objHidden');
                    $('#tblProductivity').parents().addClass('objHidden');

                    $('#tblCustomers').parents().addClass('objHidden');
                    $('#tblProjWorked').parents().removeClass('objHidden');
                    break;
                case '6':
                    settingTable4();
                    tabla = $('#tblEquiposMenosUsados').DataTable();
                    tabla.rows().remove().draw();
                    if (dt[0].prd_id > 0) {
                        $.each(dt, function (v, u) {
                            tabla.row
                        .add({
                            namedev: u.prd_name,
                            serie: u.prd_sku,
                            namepjt:u.timedif,
                            time: u.pjt_name,
                        })
                        .draw();
                        });

                    }
                    $('#tblProducts').parents().addClass('objHidden');
                    $('#tblProjDevelop').parents().addClass('objHidden');
                    $('#tblEquiposMasUsados').parents().addClass('objHidden');
                    $('#tblCierres').parents().addClass('objHidden');

                    $('#tblPatrocinios').parents().addClass('objHidden');
                    $('#tblProjWorked').parents().addClass('objHidden');
                    $('#tblProvSubarrendo').parents().addClass('objHidden');
                    $('#tblSubarrendos').parents().addClass('objHidden');
                    $('#tblProductivity').parents().addClass('objHidden');

                    $('#tblCustomers').parents().addClass('objHidden');

                    $('#tblEquiposMenosUsados').parents().removeClass('objHidden');
                    break;
                case '7':
                    settingTable3();
                    tabla = $('#tblSubarrendos').DataTable();
                    tabla.rows().remove().draw();
                    if (dt[0].ser_id > 0) {
                        $.each(dt, function (v, u) {
                            tabla.row
                        .add({
                            namepjt: u.prd_name,
                            sku: u.prd_sku,
                            serie:u.ser_sku,
                            project: u.pjt_name,
                            time: u.tiempo,
                            supplier: u.sup_business_name,
                            location: u.loc_type_location,
                        })
                        .draw();
                        });

                    }
                    $('#tblProducts').parents().addClass('objHidden');
                    $('#tblProjDevelop').parents().addClass('objHidden');
                    $('#tblEquiposMasUsados').parents().addClass('objHidden');
                    $('#tblEquiposMenosUsados').parents().addClass('objHidden');

                    $('#tblCierres').parents().addClass('objHidden');
                    $('#tblPatrocinios').parents().addClass('objHidden');
                    $('#tblProjWorked').parents().addClass('objHidden');
                    $('#tblProvSubarrendo').parents().addClass('objHidden');
                    $('#tblProductivity').parents().addClass('objHidden');

                    $('#tblCustomers').parents().addClass('objHidden');
                    $('#tblSubarrendos').parents().removeClass('objHidden');
                    break;
                case '8':
                    settingTable7();
                    tabla = $('#tblProvSubarrendo').DataTable();
                    tabla.rows().remove().draw();
                    if (dt[0].sub_id > 0) {
                        $.each(dt, function (v, u) {
                            tabla.row
                        .add({
                            nombre: u.sup_business_name,
                            cantidad: u.qty,
                            equipos:u.prd_name,
                            periodo: u.dates,
                        })
                        .draw();
                        });


                    }
                    $('#tblProducts').parents().addClass('objHidden');
                    $('#tblProjDevelop').parents().addClass('objHidden');
                    $('#tblEquiposMasUsados').parents().addClass('objHidden');
                    $('#tblEquiposMenosUsados').parents().addClass('objHidden');

                    $('#tblCierres').parents().addClass('objHidden');
                    $('#tblPatrocinios').parents().addClass('objHidden');
                    $('#tblProjWorked').parents().addClass('objHidden');
                    $('#tblSubarrendos').parents().addClass('objHidden');
                    $('#tblProductivity').parents().addClass('objHidden');

                    $('#tblCustomers').parents().addClass('objHidden');
                    $('#tblProvSubarrendo').parents().removeClass('objHidden');
                    break;
                case '9':
                    settingTable8();
                    tabla = $('#tblCustomers').DataTable();
                    tabla.rows().remove().draw();
                    if (dt[0].cus_id > 0) {

                        $.each(dt, function (v, u) {
                            let monto =parseFloat(u.monto);
                            let pagoTotal = '$ ' + Intl.NumberFormat('es-MX').format(monto);
                            tabla.row
                        .add({
                            Nombre: u.cus_name,
                            Proyecto: u.pjt_name,
                            Monto: pagoTotal,
                            rango:u.dates,
                            Contacto: u.cus_contact_name,
                            Programador: u.emp_fullname,
                        })
                        .draw();
                        });

                    }
                    $('#tblProducts').parents().addClass('objHidden');
                    $('#tblProjDevelop').parents().addClass('objHidden');
                    $('#tblEquiposMasUsados').parents().addClass('objHidden');
                    $('#tblEquiposMenosUsados').parents().addClass('objHidden');
                    $('#tblCierres').parents().addClass('objHidden');

                    $('#tblPatrocinios').parents().addClass('objHidden');
                    $('#tblProjWorked').parents().addClass('objHidden');
                    $('#tblProvSubarrendo').parents().addClass('objHidden');
                    $('#tblSubarrendos').parents().addClass('objHidden');
                    $('#tblProductivity').parents().addClass('objHidden');

                    $('#tblCustomers').parents().removeClass('objHidden');
                    break;
                case '10':
                    settingTable10();
                    tabla = $('#tblProductivity').DataTable();
                    tabla.rows().remove().draw();
                    if (dt[0].emp_id > 0) {
                        $.each(dt, function (v, u) {
                            tabla.row
                        .add({
                            nombre: u.emp_fullname,
                            cantidad: u.cantidad,
                            cotizacion:u.budget,
                            presupuesto: u.plans,
                            proyecto: u.projects,
                        })
                        .draw();
                        });

                    }
                    $('#tblProducts').parents().addClass('objHidden');
                    $('#tblProjDevelop').parents().addClass('objHidden');
                    $('#tblEquiposMasUsados').parents().addClass('objHidden');
                    $('#tblEquiposMenosUsados').parents().addClass('objHidden');

                    $('#tblCierres').parents().addClass('objHidden');
                    $('#tblPatrocinios').parents().addClass('objHidden');
                    $('#tblProjWorked').parents().addClass('objHidden');
                    $('#tblProvSubarrendo').parents().addClass('objHidden');
                    $('#tblCustomers').parents().addClass('objHidden');

                    $('#tblSubarrendos').parents().addClass('objHidden');
                    $('#tblProductivity').parents().removeClass('objHidden');
                    break;
                case '11':
                    settingTable11();
                    tabla = $('#tblProjDevelop').DataTable();
                    tabla.rows().remove().draw();
                    if (dt[0].emp_id > 0) {
                        $.each(dt, function (v, u) {
                            tabla.row
                        .add({
                            namedev: u.emp_fullname,
                            namepjt:u.pjt_name,
                            type: u.pjttp_name,
                            date: u.dates,
                        })
                        .draw();
                        });

                    }
                    $('#tblProductivity').parents().addClass('objHidden');
                        $('#tblProducts').parents().addClass('objHidden');
                        $('#tblEquiposMasUsados').parents().addClass('objHidden');
                        $('#tblEquiposMenosUsados').parents().addClass('objHidden');

                        $('#tblCierres').parents().addClass('objHidden');
                        $('#tblPatrocinios').parents().addClass('objHidden');
                        $('#tblProjWorked').parents().addClass('objHidden');
                        $('#tblProvSubarrendo').parents().addClass('objHidden');
                        $('#tblCustomers').parents().addClass('objHidden');

                        $('#tblSubarrendos').parents().addClass('objHidden');
                        $('#tblProjDevelop').parents().removeClass('objHidden');
                    break;
            default:
                break;
        }
    }
}


function findCampos(pjtId) {
    let fechaIni = fchini.val();
    const valorfchin = (fechaIni) => isNaN(Date.parse(fechaIni));
    let fechaFin = fchfin.val();
    let findAna  = findana.val();
    let findCli  = findcli.val();
    let bandera  = 0;
    bn = $('#txtReport').val();
    if(bn == '8'){
        findAna = $('#txtSupplier').val();
    }

    if (fechaIni && fechaFin) {
        bandera  = 1;
    }

    if (fechaIni && fechaFin && findAna && findAna != 0) {
        bandera  = 2;
    }
    if (fechaIni && fechaFin && findCli && findCli != 0) {
        bandera  = 3;
    }
    if (fechaIni && fechaFin && findAna && findCli && fechaIni != 0 && fechaFin != 0 && findAna != 0 && findCli != 0) {
        bandera  = 4;
    }
    if ($('#txtSupplier').val()>0 && bn == '8') {
        bandera  = 2;
    }
    //  console.log(valorfchin(fechaIni),fechaFin,findAna,findCli,bandera, bn);
    if(bn >0 ){
        getProjectContent(fechaIni,fechaFin,findAna,findCli,bandera);
    }
    //

}

function printReport() {
    let user = Cookies.get('user').split('|');
    // let v = verId;
    let v=5;
    let u = user[0];
    let n = user[2];
    let h = localStorage.getItem('host');
    let e = findana.val();
    let c = findcli.val();
    let p =  $('#txtReport').val();
    let fs = fchini.val();
    let fe = fchfin.val();
    let ba= 0;

    if(bn == '8'){
        e = $('#txtSupplier').val();
    }
    if (fs && fe) {
        ba  = 1;
    }

    if (fs && fe && e && e != 0) {
        ba = 2;
    }
    if (fs && fe && c && c != 0) {
        ba  = 3;
    }
    if (fs && fe && e && c && fs != 0 && fe != 0 && e != 0 && c != 0) {
        ba  = 4;
    }
    if ($('#txtSupplier').val()>0 && bn == '8') {
        ba  = 2;
    }
    // console.log('Id-User', u, 'Name', n);
    window.open(
        `${url}app/views/ProjectReports/ProjectReportsReport.php?v=${v}&u=${u}&n=${n}&h=${h}&e=${e}&c=${c}&p=${p}&fs=${fs}&fe=${fe}&ba=${ba}`,
        '_blank'
    );
}
