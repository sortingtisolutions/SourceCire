let pj, px, pd, glbdata;
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
    $('#txtCost').val('0.0');
    fillContent();
    getTypeMov();   // agregado por Edna
        
    $('#txtCustomer').on('blur', function () {
        validator();
    });

    $('#txtPeriod').on('blur', function () {
        validator();
    });
    $('#txtTypeMov').on('blur', function () {
        validator();
    });
    
    $('#txtCost').on('blur', function () {
        validator();
    });

    $('#btn_register').on('click', function () {
        let acc = $(this).attr('data_accion');
        InsertRegister(em);
    });

    $('#btn_clean').on('click', function () {
        applyClean();(em);
    });
       
}

/**  +++++ Obtiene los datos de los proyectos activos +++++  */
function get_Proyectos() {
    let liststat ="2,4,7,8,9";
	var pagina = 'Commons/listProjects';
	var par = `[{"liststat":"${liststat}"}]`;
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
    var pagina = 'PrePayments/listRegisters';
    var par = `[{"pjtId":"${pj}","em":"${em}"}]`;
    var tipo = 'json';
    var selector = putRegisters;
    fillField(pagina, par, tipo, selector);
}

function getDataProyects(prpid) {
    var pagina = 'PrePayments/listDataProyects';
    var par = `[{"prpid":"${prpid}"}]`;
    var tipo = 'json';
    var selector = putDataProyects;
    fillField(pagina, par, tipo, selector);
}

function getCustomersProj(pjtId) {
    var pagina = 'PrePayments/getCustomersProj';
    var par = `[{"pjtId":"${pjtId}"}]`;
    var tipo = 'json';
    var selector = putCustomersProj;
    fillField(pagina, par, tipo, selector);
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

}

/**  ++++   Coloca los proyectos en el listado del input */
function put_Proyectos(dt) {
    pj = dt;
    //console.log(pj);
    if (dt[0].pjt_id > 0) {
        $.each(dt, function (v, u) {
            let H = `<option data_indx="${v}" value="${u.pjt_id}">${u.pjt_name}</option>`;
            $('#txtProject').append(H);
        });
    }
    $('#txtProject').on('change', function () {
        $('#txtCustomer').val(0);
        // let px = parseInt($('#txtProject option:selected').attr('data_indx'));
        let pjtId = $(this).val();
        $('#txtIdProject').val($(this).val());
        $('.objet').addClass('objHidden');
        // getRegisters($(this).val(), em);
        getCustomersProj(pjtId);
        console.log('Chg-Proy', pjtId) ;
    });
}

function putCustomers(dt) {
    //console.log(dt);
    if (dt[0].cus_id > 0) {
        $.each(dt, function (v, u) {
            let H = `<option data_indx="${v}" value="${u.cus_id}">${u.cus_name}</option>`;
            $('#txtCustomer').append(H);
        });
    }
    $('#txtCustomer').on('change', function () {
        px = parseInt($('#txtCustomer option:selected').attr('data_indx'));
        console.log('Chg-Cust',px);
    }); 
}

function putCustomersProj(dt) {
    // console.log(dt);
    $("#txtCustomer option[value='" + dt[0].cus_id + "']").attr('selected', 'selected');

}

function putTypeMov(dt) {
    //console.log(dt);
    if (dt[0].wtp_id > 0) {
        $.each(dt, function (v, u) {
            let H = `<option data_indx="${v}" value="${u.wtp_id}">${u.wtp_description}</option>`;
            $('#txtTypeMov').append(H);
        });
    }

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
    if (dt[0].pjtcr_id > 0) {
        $.each(dt, function (v, u) {
            let H = `<option data_indx="${v}" value="${u.pjtcr_id}">${u.pjtcr_definition}</option>`;
            $('#txtMotivo').append(H);
        });
    }
    
    $('#txtMotivo').on('change', function () {
        px = parseInt($('#txtMotivo option:selected').attr('data_indx'));
        
    }); 
}


/**  ++++   Coloca los productos en el listado del input */
function putRegisters(dt) {
    // console.log(dt);
    pd = dt;
    
    let largo = $('#tblPrePayment tbody tr td').html('');
    largo == 'Ningún dato disponible en esta tabla' ? $('#tblPrePayment tbody tr').remove() : '';
    let tabla = $('#tblPrePayment').DataTable();
    if(dt[0].prd_id != '0'){
    tabla.rows().remove().draw();
    let cn = 0;
    
        $.each(dt, function (v, u) {
            tabla.row
                .add({
                    editable: `<i id="${u.prp_id}" data="${u.prp_folio}|${u.prp_amount}|${u.prp_date_doc}|${u.pjt_id}|${u.wtp_id}" class="fas fa-solid fa-file-invoice-dollar modif"></i>`,
                    custnam: u.cus_name,
                    proynam: u.pjt_name,
                    foldoc: u.prp_folio,
                    amount: mkn(u.prp_amount,'n'),
                    datedoc: u.prp_date_doc,
                    status: u.statprp,
                    datereg: u.prp_date_register,
                    typemov: u.wtp_description,
                    ctaroot: u.prp_root_account,
                    ctadest: u.prp_deposit_account,
                    comments: u.prp_description
                })
                .draw();
        });
        activeIcons();
    }   
}

/**  ++++   Coloca los productos en el listado del input */
function putDataProyects(dt) {
    // console.log(dt);
    settingTableSeg();
    putBasesVal(dt);
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
   
    // console.log(par);

    var pagina = 'PrePayments/savePrePayment';
    var tipo = 'json';
    var selector = putsavePrePayment;
    fillField(pagina, par, tipo, selector);
}
function putsavePrePayment(dt) { 
    // console.log(dt);
    applyClean();
    getRegisters(dt,em);
}

function applyClean(){
    $('#txtProject').val(0);
    $('#txtFolio').val('');
    $('#txtRoot').val('');
    $('#txtDest').val('');
    $('#txtPeriod').val('DD/MM/YYYY');
    $('#txtCost').val('0.00');
    $('#txtTypeMov').val(0);
    $('#txtCustomer').val(0);
    $('#txtComments').val('');

}

function settingTableSeg(){
    $('#addSegmentModal').removeClass('overlay_hide');
    $('#listTable').DataTable ({
        bDestroy: true,
        dom: 'Brti',
        pagingType: 'simple_numbers',
        language: {
            url: 'app/assets/lib/dataTable/spanish.json',
        },
        scrollY: 'calc(100vh - 200px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
            { data: 'editable',  class: 'edit' },
            { data: 'numpay',    class: 'sku' },
            { data: 'frepay',    class: 'sku' },
            { data: 'cantpay',   class: 'sku' },
            { data: 'datepay',   class: 'date' },
        ],
    });

    $('#addAsigModal .btn_close')
        .unbind('click')
        .on('click', function () {
            $('#addAsigModal').addClass('overlay_hide');

    });
}

function activeIcons() {
    $('.modif')
        .unbind('click')
        .on('click', function () {
            let prpId = $(this).attr('id');
            glbdata = $(this).attr('data');
            getDataProyects(prpId);
            settingTableSeg();
            $('#txtMontoPre').val(mkn(glbdata.split('|')[1],'n'));
            $('#addAsigModal').removeClass('overlay_hide');
        });

    $('#btn_applyAmount')
        .unbind('click')
        .on('click', function (){
        let prpId = $(this).attr('id');
        let montoasig = $('#txtMontoAsig').val();
        let referen = glbdata.split('|')[0];
        let montopayed =glbdata.split('|')[1];
        let DateStart = glbdata.split('|')[2];
        let pjtId = glbdata.split('|')[3];
        let wayPay = glbdata.split('|')[4];
        let foldoc = 0;
        let montodiff = montopayed - montoasig;
        // let projPeriod = moment(Date()).format('DD/MM/YYYY');
        // DateStart = moment(projPeriod,'DD/MM/YYYY').format('YYYYMMDD');
        
        let par = `
        [{  "prpId"          : "${prpId}"
            "referen"       : "${referen}",
            "DateStart"     :"${DateStart}",
            "montopayed"     : "${montopayed}",
            "montoasig"     : "${montoasig}",
            "montodiff"     : "${montodiff}",
            "foldoc"         : "${foldoc}",
            "pjtId"          : "${pjtId}",
            "wayPay"         : "${wayPay}",
            "empId"          : "${em}"
        }]`;
        // console.log(par);
        var pagina = 'PrePayments/insertPayAplied';
        var tipo = 'html';
        var selector = putToWork;
        fillField(pagina, par, tipo, selector);
        
    });
}

function putToWork(dt){
    // console.log('putToWork',dt)
    glbdata='';
    // $('#addAsigModal .btn_close').trigger('click');
}

function putBasesVal(dt){ 
    let tabla = $('#listTable').DataTable();
    // $('.overlay_closer .title').html(`Catalogo - ${catnme}`); ${mkn(u.ctl_amount_payable,'n')}
    tabla.rows().remove().draw();
    $.each(dt, function (v, u) {
        tabla.row
        .add({
            editable: `<i class="fas fa-info-circle kill" id ="${u.pjt_id}"></i>`,
            numpay:     u.pjt_name,
            frepay:     u.pjs_name,
            cantpay:    mkn(u.totbase,'n'),
            datepay:    u.pjt_date_start,
        })
        .draw();
        $('#md' + u.pjt_id,).parents('tr').attr('id', u.pjt_id,).attr('data-content', 1);

        $('.kill')
            .unbind('click')
            .on('click', function () {
                tabla.row($(this).parent('tr')).remove().draw();
        });
 
    });

}

function fill_table(par) { //** AGREGO ED */
    par = JSON.parse(par);
    let tabla = $('#listTable').DataTable();
    tabla.row
        .add({
            editable: `<i class="fas fa-times-circle kill" id ="md${par[0].numpay}"></i>`,
            numpay:     par[0].numpay,
            frepay:     par[0].valfrec,
            cantpay:    par[0].montind,
            datepay:    par[0].datepay,
        })
        .draw();

    $('#md' + par[0].numpay).parents('tr').attr('id', par[0].numpay).attr('data-content', 1);

    $('.kill')
        .unbind('click')
        .on('click', function () {
            tabla.row($(this).parent('tr')).remove().draw();
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
    
    if (ky == 0) {
        $('#btn_register').removeClass('disabled');
    } else {
        $('#btn_register').addClass('disabled');
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
