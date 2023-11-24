let pj, px, pd;
let user,v,u,n,em;
$(document).ready(function () {
    if (verifica_usuario()) {
        inicial();
    }
});
//INICIO DE PROCESOS
function inicial() {
    user = Cookies.get('user').split('|');
    u = user[0];
    n = user[2];
    em = user[3];

    setting_table();
    get_Proyectos();
    getCustomers();
    getRegisters(u, em);
    /* get_coins();
    get_suppliers();
    get_stores(); */
    fillContent();
    getTypeMov();// agregado por Edna
    // get_changes(); // agregado por Edna

    $('#txtCustomer').on('blur', function () {
        validator();
    });

    $('#txtPeriod').on('blur', function () {
        validator();
    });
    $('#txtTypeMov').on('blur', function () {
        validator();
    });
    
    // $('#txtComments').on('blur', function () {
    //     validator();
    // });
    $('#txtCost').on('blur', function () {
        validator();
    });
    // $('#txtMotivo').on('blur', function () {
    //     validator();
    // });
    $('#btn_subletting').on('click', function () {
        let acc = $(this).attr('data_accion');
        InsertRegister(em);
    });
}

/** ++++  Setea la tabla ++++++ */
function setting_table() {
    let title = 'Pagos adelantados';
    let filename =
        title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');

    $('#tblPrePayment').DataTable({
        order: [[1, 'desc']],
        dom: 'Blfrtip',
        select: {
            style: 'single',
            info: false,
        },
        lengthMenu: [
            [100, 200, 300, -1],
            [100, 200, 300, 'Todos'],
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
            {
                // Boton aplicar cambios
                text: 'Reportes',
                footer: true,
                className: 'btn-apply',
                action: function (e, dt, node, config) {
                    view_report();
                },
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
            { data: 'editable', class: 'edit' },
            { data: 'custnam', class: 'product-name' },
            { data: 'proynam', class: 'product-name' },
            { data: 'foldoc', class: 'sku' },
            { data: 'amount', class: 'sku' },
            { data: 'datedoc', class: 'date' },
            { data: 'status', class: 'date' },
            { data: 'datereg', class: 'date' },
            { data: 'typemov', class: 'sku' },
            { data: 'ctaroot', class: 'status' },
            { data: 'ctadest', class: 'status' },
            { data: 'comments', class: 'product-name' },
        ],
    });

    $('#tblMotivoMantenimiento').DataTable({
        order: [[1, 'desc']],
        dom: 'Blfrtip',
        select: {
            style: 'single',
            info: false,
        },
        lengthMenu: [
            [100, 200, 300, -1],
            [100, 200, 300, 'Todos'],
        ],
        buttons: [
            {
                // Boton aplicar cambios
                text: 'Aplicar subarrendos',
                footer: true,
                className: 'btn-apply hidden-field',
                action: function (e, dt, node, config) {
                    read_ProductForSubletting_table();
                },
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
            { data: 'editable', class: 'edit' },
            { data: 'prodname', class: 'product-name' },
            { data: 'prod_sku', class: 'sku' },
        ],
    });
}

/**  +++++ Obtiene los datos de los proyectos activos +++++  */
function get_Proyectos() {
    var pagina = 'PrePayments/listProyects';
    var par = `[{"store":""}]`;
    var tipo = 'json';
    var selector = put_Proyectos;
    fillField(pagina, par, tipo, selector);
}
function getCustomers() {
    var pagina = 'PrePayments/listCustomers';
    var par = '[{"parm":""}]';
    var tipo = 'json';
    var selector = putCustomers;
    fillField(pagina, par, tipo, selector);
}
function getTypeMov() {
    var pagina = 'PrePayments/listTypeMov';
    var par = `[{"store":""}]`;
    var tipo = 'json';
    var selector = putTypeMov;
    fillField(pagina, par, tipo, selector);
}

/**  +++++ Obtiene los datos de los productos activos +++++  */
function getRegisters(pj, em) {
    // console.log(pj);
    var pagina = 'PrePayments/listRegisters';
    var par = `[{"pjtId":"${pj}","em":"${em}"}]`;
    var tipo = 'json';
    var selector = putRegisters;
    fillField(pagina, par, tipo, selector);
}



/**  ++++   Coloca los proyectos en el listado del input */
function put_Proyectos(dt) {
    pj = dt;
    //console.log(pj);
    $.each(dt, function (v, u) {
        let H = `<option data_indx="${v}" value="${u.pjt_id}">${u.pjt_name}</option>`;
        $('#txtProject').append(H);
    });
    $('#txtProject').on('change', function () {
        let px = parseInt($('#txtProject option:selected').attr('data_indx'));
        let idpjy = $(this).val();
        $('#txtIdProject').val($(this).val());
        // let period = pj[px].pjt_date_start + ' - ' + pj[px].pjt_date_end;
        $('.objet').addClass('objHidden');
        // getRegisters($(this).val(), em);
        console.log('Chg-Proy',px, idpjy) ;
    });
}

function putCustomers(dt) {
    //console.log(dt);
    $.each(dt, function (v, u) {
        let H = `<option data_indx="${v}" value="${u.cus_id}">${u.cus_name}</option>`;
        $('#txtCustomer').append(H);
    });
    $('#txtCustomer').on('change', function () {
        px = parseInt($('#txtCustomer option:selected').attr('data_indx'));
        console.log('Chg-Cust',px);
    }); 
}

function putTypeMov(dt) {
    //console.log(dt);
    $.each(dt, function (v, u) {
        let H = `<option data_indx="${v}" value="${u.wtp_id}">${u.wtp_description}</option>`;
        $('#txtTypeMov').append(H);
    });
    $('#txtTypeMov').on('change', function () {
        px = parseInt($('#txtTypeMov option:selected').attr('data_indx'));
        let typemov=$(this).val(); 
        console.log('ID-',typemov);
        if (typemov == 3){
            $('.objet').removeClass('objHidden');
        }
        else{
            $('.objet').addClass('objHidden');
        }
    }); 
}

function put_changes(dt) {

    //console.log(dt);
    $.each(dt, function (v, u) {
        let H = `<option data_indx="${v}" value="${u.pjtcr_id}">${u.pjtcr_definition}</option>`;
        $('#txtMotivo').append(H);
    });
    $('#txtMotivo').on('change', function () {
        px = parseInt($('#txtMotivo option:selected').attr('data_indx'));
        
    }); 
}
function put_change_reasons(dt){
    //console.log(dt);
    
    let tabla = $('#tblMotivoMantenimiento').DataTable();
    tabla.rows().remove().draw();
    if (dt[0].pjtcr_definition != undefined) {
        $.each(dt, function (v, u) {
            tabla.row
            .add({
                editable: `<i id="${u.pjtcr_id}" class="fas fa-certificate serie motivo"></i>`,
                prodname: u.pjtcr_definition,
                prod_sku: u.pjtcr_description,
            })
            .draw();
            
        });
    }
    $('#tblPrePayment tbody tr')
        .unbind('click')
        .on('click', function (){
            activeIconsSerie2();
        });
}

/**  ++++   Coloca los productos en el listado del input */
function putRegisters(dt) {
    // console.log(dt);
    pd = dt;
    
    let largo = $('#tblPrePayment tbody tr td').html();
    largo == 'Ningún dato disponible en esta tabla'
        ? $('#tblPrePayment tbody tr').remove()
        : '';
    let tabla = $('#tblPrePayment').DataTable();
    
    tabla.rows().remove().draw();
    let cn = 0;
    if(dt[0].prp_id!=0){
        $.each(pd, function (v, u) {
            tabla.row
                .add({
                    editable: `<i id="k${u.prp_id}" class="fas fa-solid fa-file-invoice-dollar serie modif"></i>`,
                    custnam: u.cus_name,
                    proynam: u.pjt_name,
                    foldoc: u.prp_folio,
                    amount: u.prp_amount,
                    datedoc: u.prp_date_doc,
                    status: u.statprp,
                    datereg: u.prp_date_register,
                    typemov: u.wtp_description,
                    ctaroot: u.prp_root_account,
                    ctadest: u.prp_deposit_account,
                    comments: u.prp_description
                })
                .draw();
                
            // $('#k' + u.prp_id)
            //     .parents('tr')
            //     .attr({
            //         id: u.prp_id,
            //         data_serie: u.prp_id,
            //         data_proj_change: u.pjtcr_id,
            //         data_maintain: u.pmt_id,
            //         data_status: u.mts_id
            //     });
            //     //console.log(u.pmt_id);
            // cn++;
        });

    }
    
    // $('#tblPrePayment tbody tr')
    //     .unbind('click')
    //     .on('click', function () {
    //         let selected = $(this).attr('class').indexOf('selected');
    //         if (selected < 0) {
    //             $('.objet').removeClass('objHidden');
    //             let rw = $(this);
    //             let ix = rw[0].attributes[2].value;
    //             let prodsku = rw[0].cells[1].outerText;
    //             let prodname = rw[0].cells[2].outerText;
    //             let costo = rw[0].cells[3].outerText;
    //             let days = rw[0].cells[4].outerText;
    //             let hours = rw[0].cells[5].outerText;
    //             let datestar = rw[0].cells[6].outerText;
    //             let dateend = rw[0].cells[7].outerText;
    //             let no_econo = rw[0].cells[8].outerText;
    //             let comments = rw[0].cells[9].outerText;
                
    //             let situation = rw[0].cells[11].outerText;
    //             let status = rw[0].cells[12].outerText;
    //             //let no_serie = rw[0].cells[12].outerText;

    //             let seriesId = rw[0].attributes[2].value;
    //             let dataProjChange = rw[0].attributes[3].value;
    //             let datamaintain = rw[0].attributes[4].value;
    //             let idStatus = rw[0].attributes[5].value;
                
    //             let dayIni = moment(datestar).format('DD/MM/YYYY');
    //             let dayFin = moment(dateend).format('DD/MM/YYYY');
                
    //             if(dayIni =='Invalid date' || dayFin=='Invalid date'){
    //                 $('#txtPeriod').val('DD/MM/YYYY - DD/MM/YYYY');
    //             }else{
    //                 $('#txtPeriod').val(dayIni + ' - ' + dayFin);
    //             }
                
    //             $('.nameProduct').html(prodname);
    //             $('#txtIdSerie').val(seriesId);
    //             $('#txtMotivo').val(dataProjChange);
    //             $('#txtCost').val(costo);
                
    //             $('#txtComments').val(comments);
    //             $('#txtRoot').val(days);
    //             $('#txtDest').val(hours);
    //             $('#txtTypeMov').val(idStatus); 
    //             $('#txtIdMaintain').val(datamaintain);
    //             $('#txtIdStatus').val(idStatus); 

    //             if ($('#txtIdMaintain').val() == 0) {
    //                 //console.log('add');
    //                 $('#btn_subletting').attr('data_accion', 'add');
    //             } else {

    //                 $('#btn_subletting').attr('data_accion', 'chg');
    //             }
    //         } else {
    //             $('.objet').addClass('objHidden');
    //         }
    //         // get_change_reasons();
    //         activeIconsSerie();
    //     });
        
}


function InsertRegister(em) {
    let idPrj = $('#txtProject option:selected').val();
    let idcust=$('#txtCustomer option:selected').val();
    let folio = $('#txtFolio').val();
    let amount = $('#txtCost').val();
    let dtResIni = moment($('#txtPeriod').val().split(' - ')[0],'DD/MM/YYYY').format('YYYY-MM-DD');
    let idtypemov = $('#txtTypeMov option:selected').val();
    let origen = $('#txtRoot').val();
    let destiny = $('#txtDest').val();
    let comments = $('#txtComments').val();
    let empid=em;

    // let projChange = $('#txtMotivo').val();
    // let serieId = $('#txtIdSerie').val();
    // let dtResFin = moment($('#txtPeriod').val().split(' - ')[1],'DD/MM/YYYY').format('YYYY-MM-DD');
    // let idMaintain = $('#txtIdMaintain').val();

    let par = `
    [{
        "idPrj" : "${idPrj}",
        "idcust" : "${idcust}",
        "folio" : "${folio}",
        "amount" : "${amount}",
        "dtResIni"  : "${dtResIni}",
        "idtypemov"  : "${idtypemov}",
        "origen"  : "${origen}",
        "destiny"  : "${destiny}",
        "comments"  : "${comments}",
        "empid"  : "${empid}"
    }]`;
   
    
    // if (acc == 'add') {
    //     var pagina = 'PrePayments/savePrePayment';
    // } 
    // else {
    //    var pagina = 'PrePayments/changeMaintain';
    // }
    console.log(par);

    var pagina = 'PrePayments/savePrePayment';
    var tipo = 'json';
    var selector = putsavePrePayment;
    fillField(pagina, par, tipo, selector);
}
function putsavePrePayment(dt) { 
    // console.log(dt);
    $('#txtComments').val('');
    $('#txtRoot').val('');
    $('#txtDest').val('');
    $('#txtPeriod').val('DD/MM/YYYY');
    $('#txtCost').val('0.00');
    $('#txtTypeMov').val(0);
    $('#txtProject').val(0);
    $('#txtCustomer').val(0);

    getRegisters(dt,em);
}

/*  ++++++++ Valida los campos  +++++++ */
function validator() {
    let ky = 0;
    let msg = '';
    $('.required').each(function () {
        if ($(this).val() == 0) {
            msg += $(this).attr('data-mesage') + '\n';
            ky = 1;
        }
    });

    // let period = $('#txtPeriod').val().split(' - ');
    // let a = moment(period[1], 'DD/MM/YYYY');
    // let b = moment(period[0], 'DD/MM/YYYY');
    // let dif = a.diff(b, 'days');
    // if (dif < 1) {
    //     ky = 1;
    //     msg += 'La fecha final debe ser por lo menos de un día de diferencia';
    // }
    if (ky == 0) {
        $('#btn_subletting').removeClass('disabled');
    } else {
        $('#btn_subletting').addClass('disabled');
    }
    // console.log(msg);
}
function view_report(){
    title= 'Rango de Fecha de pagos adelantados';
    $('.overlay_closer .title').html(title);

    $('#ReportModal').removeClass('overlay_hide');
    $('#ReportModal .btn_close')
        .unbind('click')
        .on('click', function () {
            $('.overlay_background').addClass('overlay_hide');

        });
    $('#GenerarReporte').unbind('click')
    .on('click', function () {
        let ky = validarDatosReporte();
        if(ky == 0){
            let fi = $('#fechaIncial').val();
            let fe = $('#fechaFinal').val();
            // console.log(fi,'-',fe);
            let pjt = $('#txtProject').val();
            getReport(fi, fe, pjt);
        }
    })    
    
}
function getReport(fi, fe, pjt){
    let user = Cookies.get('user').split('|');
    let u = user[0];
    let n = user[2];
    let h = localStorage.getItem('host');
    // console.log('Param-Repor ',pjt,fi,fe);
    window.open(
        `${url}app/views/PrePayments/PrePaymentsReport.php?p=${pjt}&fi=${fi}&fe=${fe}&u=${u}&n=${n}&h=${h}`,
        '_blank'
    );
    cleanFechas();
    $('#ReportModal').addClass('overlay_hide');
}

function validarDatosReporte(){
    let ky = 0;
    $('.required2').each(function () {
        if ($(this).val() == '' || $(this).val() == 0) {
            ky = 1;
            $(this).addClass('fail').parent().children('.fail_note');
        }else{
            $(this).removeClass('fail').parent().children('.fail_note');
            ky = 0;
        }
    });
    return ky;
}
function cleanFechas(){
    $('#fechaIncial').val('');
    $('#fechaFinal').val('');
}
/*  ********* Define las fechas de inicio y de fin   +++++++ */
function define_days(st, dt, db, dr, ds) {
    let dats = '';
    let dytr = parseInt(dr) / 2;
    let dyin = parseInt(ds) + dytr;
    let dyfn = parseInt(db) + dytr;
    let dtin = moment(dt).subtract(dyin, 'days').format('DD/MM/YYYY');
    let dtfn = moment(dt)
        .add(dyfn - 1, 'days')
        .format('DD/MM/YYYY');

    if (st == 'i') {
        dats = dtin;
    } else {
        dats = dtfn;
    }
    return dats;
}

function fillContent() {
    let restdate='';
    // restdate= moment(Date())/* .subtract(1, 'days'); */
    restdate= moment().subtract(1, 'months');
    let fecha = moment(restdate).format('DD/MM/YYYY');
    let hoy=moment(Date()).format('DD/MM/YYYY');

    $('#calendar').daterangepicker(
        {
            autoApply: true,
            locale: {
                format: 'DD/MM/YYYY',
                separator: ' - ',
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom',
                weekLabel: 'W',
                daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre',
                ],
                firstDay: 1,
            },
            showCustomRangeLabel: false,
            singleDatePicker: true,
            startDate: hoy,
            endDate: fecha,
            minDate: fecha,
            maxDate:hoy,
        },
        function (start, end, label) {
            $('#txtPeriod').val(
                // start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY')
                start.format('DD/MM/YYYY') 
            );
            looseAlert($('#txtPeriod').parent());
        }
    );

}


function fillContent_old() {

    let restdate='';
    let todayweel =  moment(Date()).format('dddd');
    if (todayweel=='Monday' || todayweel=='Sunday'){
        restdate= moment().subtract(3, 'days');
    } else { restdate= moment(Date()) } 

    
    let fecha = moment(Date()).format('DD/MM/YYYY');
    $('#calendar').daterangepicker(
        {
            autoApply: true,
            locale: {
                format: 'DD/MM/YYYY',
                separator: ' - ',
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom',
                weekLabel: 'W',
                daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre',
                ],
                firstDay: 1,
            },
            showCustomRangeLabel: false,
            singleDatePicker: false,
            startDate: fecha,
            endDate: fecha,
            minDate: fecha,
        },
        function (start, end, label) {
            $('#txtPeriod').val(
                start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY')
            );
            looseAlert($('#txtPeriod').parent());

        }
    );

}


function activeIconsSerie() {
    $('.serie.modif')
        .unbind('click')
        .on('click', function () {
            let serId = $(this).attr('id').slice(1, 10);

            $('#mantenimientoModal').removeClass('overlay_hide');
            title= 'Lista de Motivos';
            $('.overlay_closer .title').html(title);
            $('#mantenimientoModal .btn_close')
                .unbind('click')
                .on('click', function () {
                    $('#mantenimientoModal').addClass('overlay_hide');
                });
            
            //getSelectSerie(serId);
        });
}

function activeIconsSerie2() {
    
    $('.serie.motivo')
        .unbind('click')
        .on('click', function () {
            let id = $(this).attr('id');
            //console.log(id);
        });
            
}


// /** ++++  Setea el calendario ++++++ */
// function setting_datepicket(sl, di, df) {
//     console.log(sl);
//     let fc = moment(Date()).format('DD/MM/YYYY');
//     $(sl).daterangepicker(
//         {
//             singleDatePicker: false,
//             autoApply: true,
//             locale: {
//                 format: 'DD/MM/YYYY',
//                 daysOfWeek: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
//                 monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
//                 'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre',
//             ],
//                 firstDay: 1,
//             },
//             minDate: fc,
//             startDate: moment(di, 'DD/MM/YYYY'),
//             endDate: moment(df, 'DD/MM/YYYY'),
//             opens: 'left',
//             drops: 'auto',
//         },
//         function (start, end, label) {
//             let sdin = start.format('DD/MM/YYYY');
//             let sdfn = end.format('DD/MM/YYYY');
//             $('#txtPeriod').html(sdin + ' - ' + sdfn);
//             setTimeout(() => {
//                 validator();
//             }, 500);
//         }
//     );
// }

/**  ++++   Coloca las monedas en el listado del input */
// function put_coins(dt) {
//     $.each(dt, function (v, u) {
//         let H = `<option value="${u.cin_id}">${u.cin_code} - ${u.cin_name}</option>`;
//         $('#txtCoinType').append(H);
//     });
//     $('#txtCoinType').on('change', function () {
//         validator();
//     });
// }
// /**  ++++   Coloca los proveedores en el listado del input */
// function put_suppliers(dt) {
//     $.each(dt, function (v, u) {
//         let H = `<option value="${u.sup_id}">${u.sup_business_name}</option>`;
//         $('#txtSupplier').append(H);
//     });
//     $('#txtSupplier').on('change', function () {
//         validator();
//     });
// }
// /**  ++++   Coloca los almacenes en el listado del input */
// function put_stores(dt) {
//     $.each(dt, function (v, u) {
//         let H = `<option value="${u.str_id}">${u.str_name}</option>`;
//         $('#txtStoreSource').append(H);
//     });
//     $('#txtStoreSource').on('change', function () {
//         validator();
//     });
// }
// function get_changes() {
//     var pagina = 'PrePayments/listChangeReasons';
//     var par = `[{"store":""}]`;
//     var tipo = 'json';
//     var selector = put_changes;
//     fillField(pagina, par, tipo, selector);
// }
// /**  +++++ Obtiene los datos los proveedores que subarrendan +++++  */
// function get_coins() {
//     var pagina = 'PrePayments/listCoins';
//     var par = `[{"store":""}]`;
//     var tipo = 'json';
//     var selector = put_coins;
//     fillField(pagina, par, tipo, selector);
// }
// /**  +++++ Obtiene los datos los proveedores que subarrendan +++++  */
// function get_suppliers() {
//     var pagina = 'PrePayments/listSuppliers';
//     var par = `[{"store":""}]`;
//     var tipo = 'json';
//     var selector = put_suppliers;
//     fillField(pagina, par, tipo, selector);
// }
// /**  +++++ Obtiene los datos los proveedores que subarrendan +++++  */
// function get_stores() {
//     var pagina = 'PrePayments/listStores';
//     var par = `[{"store":""}]`;
//     var tipo = 'json';
//     var selector = put_stores;
//     fillField(pagina, par, tipo, selector);
// }

// function get_change_reasons(pd) {
//     var pagina = 'PrePayments/listChangeReasons';
//     var par = `[{"prod_id":""}]`;
//     var tipo = 'json';
//     var selector = put_change_reasons;
//     fillField(pagina, par, tipo, selector);
// }