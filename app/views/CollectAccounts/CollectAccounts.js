let seccion = '';
let docs, gblcloid, gblpjtid;
let grp = 50;
let num = 0;
let cats, subs, sku1, sku2, sku3, sku4;

$(document).ready(function () {
    if (verifica_usuario()) {
        inicial();
    }
});
//INICIO DE PROCESOS
function inicial() {
    setTimeout(() => {
        settingTable();
        $('.tblProdMaster').css({display: 'none'});
        getProjects(0);
        getWayToPay();
    }, 100);
}

/** +++++  Obtiene los proyectos de la base */
function getProjects(catId) {
    var pagina = 'CollectAccounts/listProjects';
    var par = `[{"catId":"${catId}"}]`;
    var tipo = 'json';
    var selector = putProjects;
    fillField(pagina, par, tipo, selector);
}

function getWayToPay() {
    var pagina = 'CollectAccounts/getWayToPay';
    var par = `[{"wtpId":""}]`;
    var tipo = 'json';
    var selector = putWayToPay;
    fillField(pagina, par, tipo, selector);
}

/** +++++  configura la table de productos */
function settingTable() {
    let title = 'Control salida de proyectos';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    $('#tblCollets').DataTable({
        bDestroy: true,
        order:  [[ 1, 'asc' ], [ 8, 'asc' ]],
        dom: 'Blfrtip',
        lengthMenu: [
            [200, 400, 600, -1],
            [200, 400, 600, 'Todos'],
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
            {data: 'editable',      class: 'edit', orderable: false},      
            {data: 'clt_folio',      class: 'sku'},
            {data: 'clt_create',    class: 'date'},
            {data: 'clt_namecli',    class: 'supply'},
            {data: 'clt_namepjt',   class: 'supply'},
            {data: 'clt_payall',    class: 'sku'},
            {data: 'clt_paid',      class: 'sku'},
            {data: 'clt_pending',   class: 'sku'},
            {data: 'clt_limitpay',  class: 'date'},
            {data: 'clt_lastpay',   class: 'date'},
        ],
    });

    $('.tblProdMaster')
        .delay(500)
        .slideDown('fast', function () {
            //$('.deep_loading').css({display: 'none'});
            //$('#tblCollets').DataTable().draw();
            deep_loading('C');
    });
}

function putWayToPay(dt) {
    tpprd = dt;
}

/** +++++  coloca los productos en la tabla */
function putProjects(dt) {
    console.log('putProjects',dt);
    $('#tblCollets tbody').html('');
    
    if (dt[0].clt_id != undefined) {
        console.log('each',dt[0].clt_id);
        //<i class='fas fa-edit detail'>
        $.each(dt, function (v, u) {
            var H = `
                <tr id="${u.clt_id}" data_pjt="${u.pjt_id}">
                    <td></i><i class='fas fa-door-open toWork'></i></td> 
                    <td class="sku">${u.clt_folio}</td>
                    <td class="date">${u.clt_date_generated}</td>
                    <td class="supply">${u.cus_name}</td>
                    <td class="supply">${u.pjt_name}</td>
                    <td class="sku">${mkn(u.amount_payable,'n')}</td> 
                    <td class="sku">${mkn(u.pym_amount,'n')}</td>
                    <td class="sku">${mkn(u.pending,'n')}</td>
                    <td class="date">${u.clt_deadline}</td>
                    <td class="date">${u.pym_date_paid}</td>
                </tr>`;
            $('#tblCollets tbody').append(H);
        });
        // settingTable();
        activeIcons();
    } else {
        // settingTable();
    }
}

/** +++++  Activa los iconos */
function activeIcons() {
    $('.toWork')
        .unbind('click')
        .on('click', function () {
          
        let cltid = $(this).parents('tr').attr('id');
        let pjtId = $(this).parents('tr').attr('data_pjt');
        gblcloid=cltid;
        gblpjtid=pjtId;
        console.log('Globales',gblcloid, gblpjtid );
        confirm_to_work(cltid);

        });

    $('.detail')
        .unbind('click')
        .on('click', function () {
        });

    $('#registPayModal .btn_close')
        .unbind('click')
        .on('click', function () {
            $('.overlay_background').addClass('overlay_hide');
        });

    
    $('#savePayed.update')
        .unbind('click')
        .on('click', function () {
            let user = Cookies.get('user').split('|');
            let em = user[3];
            let referen = $('#txtRefPayed').val();
            let montopayed = $('#txtMontoPayed').val();
            // let foldoc = $('#txtNumFol').val();
            let foldoc = gblcloid;
            let pjtId = gblpjtid;
            let wayPay = $('#txtWayPay option:selected').val();
            let projPeriod = $('#txtPeriodPayed').val();
            let montoTotal = $('#txtMontoTotal').val();
            let montoRestante = (parseFloat(montoTotal) - parseFloat(montopayed));
            let DateStart = moment(projPeriod,'DD/MM/YYYY').format('YYYYMMDD');
            
            let par = `
            [{  "referen"       : "${referen}",
                "DateStart"     :"${DateStart}",
                "montopayed"     : "${montopayed}",
                "foldoc"         : "${foldoc}",
                "pjtId"          : "${pjtId}",
                "wayPay"         : "${wayPay}",
                "empId"          : "${em}",
                "montoRest"      : "${montoRestante}"
            }]`;
            console.log(par);
            var pagina = 'CollectAccounts/insertPayAplied';
            var tipo = 'html';
            var selector = putToWork;
            fillField(pagina, par, tipo, selector);
            
        });
}

function confirm_to_work(cltid) {
    $('#starToWork').modal('show');
    $('#txtIdProductPack').val(cltid);
    //borra paquete +
    $('#btnToWork').on('click', function () {
        $('#starToWork').modal('hide');

        let prdNm="Registro de pago para proyecto concluido";
        $('#registPayModal').removeClass('overlay_hide');
        $('.overlay_closer .title').html(prdNm);

        let el = $(`#tblCollets tr[id="${cltid}"]`);
            $('#txtNumFol').val($(el.find('td')[1]).text());
            $('#txtProject').val($(el.find('td')[4]).text());
            $('#txtMontoTotal').val($(el.find('td')[5]).text());
        fillContent();
    });
}

function fillContent() {
    
    let restdate='';
    let todayweel =  moment(Date()).format('dddd');
    restdate= moment().subtract(3, 'months');
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
            $('#txtPeriodPayed').val(
                // start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY')
                start.format('DD/MM/YYYY') 
            );
            looseAlert($('#txtPeriodPayed').parent());
        }
    );

    // Llena el selector de tipo de proyecto
    $.each(tpprd, function (v, u) {
        let H = `<option value="${u.wtp_id}"> ${u.wtp_description}</option>`;
        $('#txtWayPay').append(H);
    });
}

function putToWork(dt){
    console.log(dt)
    $('#registPayModal .btn_close').trigger('click');
    getProjects(0);
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

function fillData(inx) {

$('#savePayed.update')
        .unbind('click')
        .on('click', function () {
            let foldoc = $('#txtNumFol').val();
            let projId = $('#txtProjectEdt').val();
            let montopayed = $('#txtMontoPayed').val();
            let referen = $('#txtRefPayed').val();
            let WayPay = $('#txtWayPay option:selected').val();
            let projPeriod = $('#txtPeriodPayed').val();
            let montoTotal = $('#txtMontoTotal').val();
            let montoRestante = (parseFloat(montoTotal) - parseFloat(montopayed));

            let projDateStart = moment(projPeriod,'DD/MM/YYYY').format('YYYYMMDD');
            
            let par = `
            [{
                "projId"         : "${projId}",
                "foldoc"         : "${foldoc}",
                "montopayed"     : "${montopayed}",
                "referen"        : "${referen}",
                "WayPay"        : "${WayPay}",
                "projDateStart"  : "${projDateStart}",
                "cuoId"          : "${cuoId}",
                "cusId"          : "${cusCte}",
                "cusParent"      : "${cusCteRel}",
                "montoRest"      : "${montoRestante}",
            }]`;
            
            console.log(par);
            var pagina = 'Budget/UpdateProject';
            var tipo = 'html';
            var selector = loadProject;
            fillField(pagina, par, tipo, selector);
            
        });
}

