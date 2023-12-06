let prod, gblcloid, proj, folio, comids = [], glbpjtid;

$('document').ready(function () {
    url = getAbsolutePath();
    if (verifica_usuario()) {
        inicial();
    }
});
//INICIO DE PROCESOS
function inicial() {
    if (altr == 1) {
        get_projects();
        fill_dinamic_table();
        confirm_alert();
    } else {
        setTimeout(() => {
            inicial();
        }, 100);
    }
}

/** OBTENCION DE DATOS */
/**  Obtiene el listado de almacenes */
function get_projects() {
    var pagina = 'ClosedProyectChange/listProjects';
    var par = `[{"strId":""}]`;
    var tipo = 'json';
    var selector = put_projects;
    caching_events('get_projects');
    fillField(pagina, par, tipo, selector);
}

function getDataProjects(pjtId) {
    var pagina = 'ClosedProyectChange/listDataProjects';
    var par = `[{"pjtId":"${pjtId}"}]`;
    var tipo = 'json';
    var selector = putdataprojects;
    caching_events('get_projects');
    fillField(pagina, par, tipo, selector);
}

function get_montos(pjtId) {
    var pagina = 'ClosedProyectChange/getMontos';
    var par = `[{"pjtId":"${pjtId}"}]`;
    var tipo = 'json';
    var selector = fill_purchase;
    caching_events('get_montos');
    fillField(pagina, par, tipo, selector);
}

/**  Obtiene el listado de productos */
function get_products(strId) {
    var pagina = 'ClosedProyectChange/listProducts';
    var par = `[{"strId":"${strId}"}]`;
    var tipo = 'json';
    var selector = put_products;
    caching_events('get_products');
    fillField(pagina, par, tipo, selector);
}

/**  Obtiene el listado de proyectos */
function put_projects(dt) {
    proj = dt;
    if (dt[0].pjt_id > 0) {
        $.each(proj, function (v, u) {
            let H = `<option value="${u.pjt_id}" >${u.pjt_number}-${u.pjt_name}</option>`;
            $('#lstProject').append(H);
        });
    } else { $('#lstProject').html('');  }

    $('#lstProject')
        .unbind('change')
        .on('change', function () {
            fill_dinamic_table();
            let lpjt = $(this).val();
            glbpjtid=lpjt;
            getDataProjects(lpjt);
            get_montos(lpjt);

        });
}

function putdataprojects(dt) {
    proj = dt;
    if (dt[0].pjt_id > 0) {
        $.each(proj, function (v, u) {
                // $('#txtProject').parents('div.form_group').removeClass('hide');
                $('#txtProject').val(u.pjt_name.toUpperCase());
                $('#txtCustomer').val(u.cus_name);
                $('#txtDateStar').val(u.pjt_date_start.toUpperCase());
                $('#txtDateEnd').val(u.pjt_date_end.toUpperCase());
                $('#txtRepresen').val(u.cus_legal_representative);
                $('#txtAdress').val(u.cus_address);
                $('#txtRespProg').val(u.emp_fullname.toUpperCase());
        });
    } else { $('#lstProject').html(''); }

    $('#txtProject').on('change', function () {
        let id = $(this).val();
        console.log('Change',id);
        fill_dinamic_table();
        getDataProjects(lpjt);
        // get_montos(lpjt);
    });
}

function put_products(dt) {
    console.log(dt);
}

/** *****  Arma el escenario de la cotizacion  */
function fill_dinamic_table() {
    caching_events('fill_dinamic_table');
    let H = `
        <table class="table_control" id="tblControl" style="width: 1100px;">
            <thead>
                <tr class="headrow">
                    <th class="w4 zone_01" ></th>
                    <th class="w4 zone_01" >VERSION </th>
                    <th class="w3 zone_01" >TOTAL PROYECTO </th>
                    <th class="w3 zone_01" >TOTAL MANTENIMIENTO </th>
                    <th class="w3 zone_03" >TOTAL EXPENDABLES</th>
                    <th class="w3 zone_03" >TOTAL DIESEL</th>
                    <th class="w3 zone_03" >MONTO DESCUENTO</th>
                    <th class="w3 zone_03" >TOTAL COBRO</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    `;
    $('#tbl_dynamic').html(H);
    tbldynamic('tbl_dynamic');
}

/**  ***** Guarda el producto en la cotización ***** */
function fill_purchase(dt) {
    // console.log('fill_purchase',dt);
    if (dt[0].clo_id > 0) {
        $.each(dt, function (v, u) {
        let H = `
            <tr id="${u.clo_id}" data_index="${u.clo_id}">
                <td><i class="fas fa-penfa-solid fa-chart-pie segment"></i><i class="fas fa-solid fa-money-check addData"></i></td>
                <td class="cost" >${u.clo_id}</td>
                <td class="cost" >${mkn(u.clo_total_proyects,'n')}</td>
                <td class="cost" >${mkn(u.clo_total_maintenance,'n')}</td>
                <td class="cost" >${mkn(u.clo_total_expendables,'n')}</td>
                <td class="cost" >${mkn(u.clo_total_diesel,'n')}</td>
                <td class="cost" >${mkn(u.clo_total_discounts,'n')}</td>
                <td class="cost" >${mkn(u.clo_total_document,'n')}</td>
            </tr> `;
            $('#tbl_dynamic tbody').append(H);
            $(`.frame_content #tblControl tbody #${u.clo_id} td.quantity`).attr({contenteditable: 'true'});
        });
    }

    $('.addData')
    .unbind('click')
    .on('click', function () {

        let cloid = $(this).parents('tr').attr('id');
        let nomproy = $(`#lstProject option[value="${glbpjtid}"]`).text().split('-')[1];
        gblcloid=cloid;
        console.log('addData',nomproy);
        let el = $(`#tbl_dynamic tr[id="${cloid}"]`);
            $('#txtProject').val($('#txtProject').val());
            let montproy=$(el.find('td')[2]).text();
            let montmant=$(el.find('td')[3]).text();
            let montexpe=$(el.find('td')[4]).text();
            let montdies=$(el.find('td')[5]).text();
            let montdesc=$(el.find('td')[6]).text();
            let monttota=$(el.find('td')[7]).text();
            // let monttota= (parseFloat(montproy)+parseFloat(montmant)+parseFloat(montexpe)+parseFloat(montdies))-parseFloat(montdesc);
            // console.log('col2',montproy);
            $('#txtMontoProy').val(montproy);
            $('#txtMontoMant').val(montmant);
            $('#txtMontoexpe').val(montexpe);
            $('#txtMontoDies').val(montdies);
            $('#txtMontoDesc').val(montdesc);
            $('#txtMontoTotal').val(monttota);
        let prdNm="Agrega nuevos valores de cierre al proyecto: " + nomproy + "";
        $('#newValuesModal').removeClass('overlay_hide');
        $('.overlay_closer .title').html(prdNm);
        activeProjectsFunctions();

    });

    $('.segment')
        .unbind('click')
        .on('click', function () {
            let cloid = $(this).parents('tr').attr('id');
            gblcloid=cloid;
            let el = $(`#tbl_dynamic tr[id="${cloid}"]`);
            let monttota=$(el.find('td')[7]).text();
            settingTableSeg(gblcloid);
            let id = $(this).parents('tr').attr('id');
            // console.log('segment',id);
            // let prdNm="Segmenta valores para cobrar"
            $('#txtMontoTotSeg').val(monttota);
            $('#addSegmentModal').removeClass('overlay_hide');
            $('.overlay_closer .title').html('');
            fillContent();
        });

    $('#btn_save')
        .unbind('click')
        .on('click', function () {
            confirm_to_Save(gblcloid);
        });

    $('#newValuesModal .btn_close')
        .unbind('click')
        .on('click', function () {
            $('.overlay_background').addClass('overlay_hide');
        });

    $('#toSegmentModal .btn_close')
        .unbind('click')
        .on('click', function () {
            $('.overlay_background').addClass('overlay_hide');
        });
}

function activeProjectsFunctions() {
    console.log('ACTIVA');
    putSaleExpendab();
    putTotalMaintenance();
    findExtraDiesel();
    findDiscount();

    setTimeout(() => {
        updateTotals();
    }, 100);

}


function putSaleExpendab() {
    let cfr = 0;
    $('#txtMontoexpe').html(fnm($('#txtMontoexpe').val(), 2, '.', ','));
    $('#txtMontoexpe').unbind('keyup').on('keyup', function () {
        let val = $(this).val();
        if (val == '') {
            val = cfr;
        }
        $('#txtMontoexpe').html(fnm(val, 2, '.', ','));
        updateTotals();
    });
}

function putTotalMaintenance(){
    let cfr = 0;
    $('#txtMontoMant').unbind('keyup').on('keyup', function () {
        let val = $(this).val();
        if (val == '') {
            val = cfr;
        }
        $('#txtMontoMant').html(fnm(val, 2, '.', ','));
        updateTotals();
    }); 
}

function findDiscount() {
    let cfr = 0;
    $('#txtMontoDesc').unbind('keyup').on('keyup', function () {
        let val = $(this).val();
        if (val == '') {
            val = cfr;
        }
        $('#txtMontoDesc').html(fnm(val, 2, '.', ','));
        updateTotals();
    });
}

function findExtraDiesel() {
    let cfr = 0;
    $('#txtMontoDies').unbind('keyup').on('keyup', function () {
        let val = $(this).val();
        if (val == '') {
            val = cfr;
        }
        $('#txtMontoDies').html(fnm(val, 2, '.', ','));
        updateTotals();
    });
}

function updateTotals() {
    let total = parseFloat($('#txtMontoProy').val().replace(/,/g, ''));
        total += parseFloat($('#txtMontoexpe').val().replace(/,/g, ''));
        total += parseFloat($('#txtMontoMant').val().replace(/,/g, ''));
        total += parseFloat($('#txtMontoDies').val().replace(/,/g, ''));
        total -= parseFloat($('#txtMontoDesc').val().replace(/,/g, ''));

        $('#txtMontoTotal').val(fnm(total, 2, '.', ','));
}

 /** */
 function confirm_to_Save(pjtid) {
    $('#starClosure').modal('show');
    $('#txtIdClosure').val(pjtid);

    $('#btnClosure').on('click', function () {
        $('#starClosure').modal('hide');
        // console.log('Valor CloID',pjtid);
         saveNewDocument(pjtid);
    });
}
/*** */
function saveNewDocument(dt) {
    let user = Cookies.get('user').split('|');
    let u = user[0];
    let n = user[2];
    let em = user[3];

    let cloTotProy = parseFloat($('#txtMontoProy').val().replace(/,/g, ''));
    let cloTotMaint =parseFloat($('#txtMontoMant').val().replace(/,/g, ''));
    let cloTotExpen = parseFloat($('#txtMontoexpe').val().replace(/,/g, ''));
    let cloTotCombu =parseFloat($('#txtMontoDies').val().replace(/,/g, ''));
    let cloTotDisco = parseFloat($('#txtMontoDesc').val().replace(/,/g, ''));
    let cloTotDocum = parseFloat($('#txtMontoTotal').val().replace(/,/g, ''));
    let cloId = gblcloid;
    let usrid = u;

    var par = `
        [{  "cloTotProy" : "${cloTotProy}",
            "cloTotMaint" : "${cloTotMaint}",
            "cloTotExpen" : "${cloTotExpen}",
            "cloTotCombu" : "${cloTotCombu}",
            "cloTotDisco" : "${cloTotDisco}",
            "cloTotDocum" : "${cloTotDocum}",
            "cloId" : "${cloId}",
            "usrid" : "${usrid}"
        }] `;
    // console.log('Save Doc-',par);
    var pagina = 'ClosedProyectChange/saveDocumentClosure';
    var tipo = 'html';
    var selector = putToWork;
    fillField(pagina, par, tipo, selector);
    // putToWork('45');
}

function putToWork(dt){
    console.log('putToWork ', dt);
    let folio=dt;

    // $('#lstProject').html('');
    $('#newValuesModal .btn_close').trigger('click');
    // inicial();
    // get_projects();
    // fill_dinamic_table();
    window.location.reload();
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

/** Configuracion de la tabla para segmentar*/
function settingTableSeg(cloid){
    let numcloid=cloid;
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

    $('#addButtonSegm')
        .unbind('click')
        .on('click', function () {
            // console.log('Agregar a TBL');
            putSegments();
        });

    $('#btn_saveSegment')
    .unbind('click')
    .on('click', function (){
        let user = Cookies.get('user').split('|');
        let em = user[3];
        $('#listTable tbody tr').each(function (v, u) {
            let lnumpay=$($(u).find('td')[1]).text();
            let lfrecpay=$($(u).find('td')[2]).text();
            let lcantpay=$($(u).find('td')[3]).text();
            let ldatepay=$($(u).find('td')[4]).text();

            let truk = `${lnumpay}|${ldatepay}|${lcantpay}|${glbpjtid}|${numcloid}|${em}`;
            console.log('TRUK ',truk);
            build_data_structure(truk);
        });
    });

    $('#addSegmentModal .btn_close')
        .unbind('click')
        .on('click', function () {
            $('#addSegmentModal').addClass('overlay_hide');

    });
}

/**Agrega los datos segmentado en la tabla */
function putSegments(){ 
    let MontoTot=parseFloat($('#txtMontoTotSeg').val().replace(/,/g, ''));
    let cantFrec =$('#txtFrecuency').val();
    let cantSegm =$('#txtSegment').val();
    let frecDesc =$(`#txtFrecuency option[value="${cantFrec}"]`).text();
    let cantDesc =$(`#txtSegment option[value="${cantSegm}"]`).text();
    let numpedido;
    let montoInd= parseFloat(MontoTot) / parseFloat(cantSegm);
    montoInd =montoInd.toFixed(2);

    let unittime='';
    switch (cantFrec) {
        case '01':
            unittime='w';
            break;
        case '2':
            unittime='M';
            break;
        case '3':
            unittime='Q';
            break;
        case '4':
            unittime='y';
            break;
        default:
    }
    // let hoy=moment(Date()).format('DD/MM/YYYY');
    let Period = $('#txtPeriodPayed').val();
    let DateStart = moment(Period,'DD/MM/YYYY').format('YYYY-MM-DD');

    for (var i = 0; i < cantSegm; i++){
        numpedido=parseFloat(i) + 1;
        fechapago=moment([DateStart],'YYYY-MM-DD').add(numpedido, unittime).format('YYYY-MM-DD');
        par = ` [{
                    "montind"   : "${montoInd}",
                    "numpay"    : "${numpedido}",
                    "valfrec"   : "${frecDesc}",
                    "datepay"   : "${fechapago}"
                }]`;

        // console.log(par);
        fill_table(par);
    }
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

/** Guarda los datos de la tabla ***** */
function build_data_structure(pr) {
    // console.log(pr);
    let el = pr.split('|');
    let folid =  el[0];
    let deadpay =  el[1];
    let amoupay= el[2];
    let pjtId =  el[3];
    let cloid =  el[4];
    let empid =  el[5];
    let cusid =  '';
    
    par = `
        [{  "folid" :  "${folid}",
            "cusid" :  "${cusid}",
            "amoupay" :  "${amoupay}",
            "pjtId" : "${pjtId}",
            "cloid" :  "${cloid}",
            "empid" : "${empid}",
            "deadpay" : "${deadpay}"
        }]`;
    // console.log(' Antes de Insertar', par);
    save_exchange(par);
}

function save_exchange(pr) {
    // console.log(pr);
    var pagina = 'ClosedProyectChange/insertCollectPays';
    var par = pr;
    var tipo = 'html';
    var selector = exchange_result;
    fillField(pagina, par, tipo, selector);
}

function exchange_result(dt) {
    // console.log('exchange_result',dt);
    $('#listTable').DataTable().destroy; 
    $('#addSegmentModal').addClass('overlay_hide');
    
}

function setReport(dt) {
    console.log(dt);
    deep_loading('C');

    let sal = dt.split('|')[0];
    let usr = dt.split('|')[1];
    let nme = dt.split('|')[2];
    let hst = localStorage.getItem('host');
    window.open(url + 'app/views/ClosedProyectChange/ClosedProyectChangeReport.php?i=' + sal + '&u=' + usr + '&n=' + nme + '&h=' + hst, '_blank');
    window.location = 'ClosedProyectChange';
}

/**  ****** Cachando eventos   */
function caching_events(ev) {
    // console.log(ev);
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

function fillContent() {
    let restdate='';
    restdate= moment(Date())/* .subtract(1, 'days'); */
    let fecha = moment(restdate).format('DD/MM/YYYY');
    // let hoy=moment(Date()).format('DD/MM/YYYY');
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
            startDate: fecha,
            endDate: fecha,
            minDate: fecha,
            // maxDate:hoy,
        },
        function (start, end, label) {
            $('#txtPeriodPayed').val(
                // start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY')
                start.format('DD/MM/YYYY') 
            );
            looseAlert($('#txtPeriodPayed').parent());
        }
    );

}

