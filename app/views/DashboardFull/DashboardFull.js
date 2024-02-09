let st = 0;
$(document).ready(function () {
    /* url = getAbsolutePath(); */
    limpiarProgress();
    getProgress(1);
    cargaIndicadores();
    verifica_usuario();
    getDataBudgets(1); 
    setInterval(cargaIndicadores, 12000); 
    $('.project-report').unbind('click')
    .on('click', function () {
        st = $(this).attr('id');
        $('#SerieData').removeClass('overlay_hide');
            getProjects(st);
            $('#SerieData .btn_close')
                .unbind('click')
                .on('click', function () {
                    // console.log('Click Close 2');
                    $('#SerieData').addClass('overlay_hide');
                    $('#tblDataChg').DataTable().destroy;
                }); 
    });
    $('#cierres').unbind('click')
    .on('click', function () {
        st = $(this).attr('id');
        $('#SerieData').removeClass('overlay_hide');
            getProjectsCierres(st);
            $('#SerieData .btn_close')
                .unbind('click')
                .on('click', function () {
                    // console.log('Click Close 2');
                    $('#SerieData').addClass('overlay_hide');
                    $('#tblDataChg').DataTable().destroy;
                }); 
    });

    $('#transporte')
    .on('click', function () {
        
        st = 'T';
        $('#SerieData').removeClass('overlay_hide');
            getProjectsTransport(st);
            $('#SerieData .btn_close')
                .unbind('click')
                .on('click', function () {
                    // console.log('Click Close 2');
                    $('#SerieData').addClass('overlay_hide');
                    $('#tblDataChg').DataTable().destroy;
                });  
    });
    $('#subarrendos')
    .on('click', function () {
        st = 'S';
        $('#SublettingData').removeClass('overlay_hide');
            getProjectsSubarrendos(st);
            $('#SublettingData .btn_close')
                .unbind('click')
                .on('click', function () {
                    // console.log('Click Close 2');
                    $('#SublettingData').addClass('overlay_hide');
                    $('#tblDataSub').DataTable().destroy;
                }); 
    });
        
});

//Lama a todos los KPI
function cargaIndicadores() {
    getKPIS();
    progress();
    ButtonsActives();
    getTotales();
    //
    //
}

//Total de projectos cifras
function getKPIS() {
    var pagina = 'Dashboard/getkpis';
    var par = '[{"parm":""}]';
    var tipo = 'json';
    var selector = putInKPIS;
    fillField(pagina, par, tipo, selector);
}

//Total de projectos cifras
function getTotales() {
    //console.log('TOTALES');
    var pagina = 'DashboardFull/getTotales';
    var par = '[{"parm":""}]';
    var tipo = 'json';
    var selector = putTotales;
    fillField(pagina, par, tipo, selector);
}


//Total de cotizaciones
function getDataBudgets(pjtsta) {
    var pagina = 'DashboardFull/getDatosBudget';
    var par = `[{"pjtsta":"${pjtsta}"}]`;
    var tipo = 'json';
    var selector = putDataBudgets;
    fillField(pagina, par, tipo, selector);
}
// PROGRESS BAR
function getProgressData(status, type) {
    console.log(status);
    var pagina = 'DashboardFull/getProgressData';
    var par = `[{"pjtsta":"${status}", "type":"${type}"}]`;
    var tipo = 'json';
    var selector = putProgressData;
    fillField(pagina, par, tipo, selector);
}


function getProgressDataTypeAnalyst(status,type) {
    //console.log('Data');
    var pagina = 'DashboardFull/getProgressData';
    var par = `[{"pjtsta":"${status}", "type":"${type}"}]`;
    var tipo = 'json';
    var selector = putProgressDataAnalyst;
    fillField(pagina, par, tipo, selector);
}

function getProgress(status,type) {
    var pagina = 'DashboardFull/getProgressData';
    var par = `[{"pjtsta":"${status}", "type":"${type}"}]`;
    var tipo = 'json';
    var selector = putProgress;
    fillField(pagina, par, tipo, selector);
}
function getSubarrendos(status) {
    var pagina = 'DashboardFull/getSublettingData';
    var par = `[{"pjtsta":"${status}"}]`;
    var tipo = 'json';
    var selector = putDataBudgets;
    fillField(pagina, par, tipo, selector);
}
function getPrjTrans(status) {
    var pagina = 'DashboardFull/getPrjTransData';
    var par = `[{"pjtsta":"${status}"}]`;
    var tipo = 'json';
    var selector = putDataBudgets;
    fillField(pagina, par, tipo, selector);
}
function getDummy(status) {
    var pagina = 'DashboardFull/getDummyData';
    var par = `[{"pjtsta":"${status}"}]`;
    var tipo = 'json';
    var selector = putDataBudgets;
    fillField(pagina, par, tipo, selector);
}

function getTypeLocation(status) {
    var pagina = 'DashboardFull/getTypeLocation';
    var par = `[{"pjtsta":"${status}"}]`;
    var tipo = 'json';
    var selector = putDataBudgets;
    fillField(pagina, par, tipo, selector);
}

function getTypeCall(status) {
    var pagina = 'DashboardFull/getTypeCall';
    var par = `[{"pjtsta":"${status}"}]`;
    var tipo = 'json';
    var selector = putDataBudgets;
    fillField(pagina, par, tipo, selector);
}


/** Obtiene el listado de los proyectos */
function getProjects(status) {
    var pagina = 'DashboardFull/listProjects';
    var par = `[{"pjtsta":"${status}"}]`;
    var tipo = 'json';
    var selector = putProjects;
    fillField(pagina, par, tipo, selector);
}

/** Obtiene el listado de los proyectos */
function getProjectsCierres(status) {
    var pagina = 'DashboardFull/listProjectsCierres';
    var par = `[{"pjtsta":"${status}"}]`;
    var tipo = 'json';
    var selector = putProjectsCierres;
    fillField(pagina, par, tipo, selector);
}
function getProjectsTransport(status) {
    var pagina = 'DashboardFull/listProjectsTransport';
    var par = `[{"pjtsta":"${status}"}]`;
    var tipo = 'json';
    var selector = putProjects;
    fillField(pagina, par, tipo, selector);
}
function getProjectsSubarrendos(status) {
    var pagina = 'DashboardFull/listSubarrendos';
    var par = `[{"pjtsta":"${status}"}]`;
    var tipo = 'json';
    var selector = putSubarrendos;
    fillField(pagina, par, tipo, selector);
}
function putProjects(dt) {
    console.log('putChangeProd', dt);
    settingProdChg();
    
    let tablaChg = $('#tblDataChg').DataTable();
    $('#SerieData .overlay_closer .title').html(`PROYECTOS:`);
    tablaChg.rows().remove().draw();
    if (dt[0].pjt_id > 0) {
        $.each(dt, function (v, u) {
            // console.log(u);
            tablaChg.row
             .add({
                 projname: u.pjt_name,
                 datestr: u.pjt_date_start,
                 dateend: u.pjt_date_end,
                 call: u.pjttc_name,
                 analyst: u.emp_fullname,
             })
             .draw();
         $(`#${u.pjt_id}`).parents('tr').attr('id',u.pjt_id);
        });
    } 
}

function putProjectsCierres(dt) {
    console.log('putChangeProd', dt);
    settingCierre();
    
    let tablaChg = $('#tblDataCierre').DataTable();
    $('#SerieData .overlay_closer .title').html(`PROYECTOS:`);
    tablaChg.rows().remove().draw();
    if (dt[0].pjt_id > 0) {
        $.each(dt, function (v, u) {
            // console.log(u);
            tablaChg.row
             .add({
                 projname: u.pjt_name,
                 datestr: u.pjt_date_start,
                 dateend: u.pjt_date_end,
                 call: u.pjttc_name,
                 analyst: u.emp_fullname,
                 cliente: u.cus_name,
             })
             .draw();
         $(`#${u.pjt_id}`).parents('tr').attr('id',u.pjt_id);
        });
    } 
}

function putSubarrendos(dt) {
    console.log('putSubarrendos', dt);
    settingProdSub();
    
    let tablaChg = $('#tblDataSub').DataTable();
    $('#SublettingData .overlay_closer .title').html(`PROYECTOS:`);
    tablaChg.rows().remove().draw();
    if (dt[0].pjt_id > 0) {
        $.each(dt, function (v, u) {
            // console.log(u);
            tablaChg.row
             .add({
                 prdname: u.prd_name,
                 price: u.sub_price,
                 qty: u.sub_quantity,
                 pjtname: u.pjt_name,
                 call: u.pjttc_name,
                 analyst: u.emp_fullname,
             })
             .draw();
         $(`#${u.pjt_id}`).parents('tr').attr('id',u.pjt_id);
        });
    } 
}
function putDummy(dt) {
    var percent = parseInt(dt[0].percent);
    var cantTotal = parseInt(dt[0].canTotal);
    var cantBudget = parseInt(dt[0].cantBudget);
    $('.GaugeMeter').data('percent', percent);
    $('.GaugeMeter').data('used', percent+'% '+cantBudget+'/'+cantTotal);
    $('.GaugeMeter').gaugeMeter();
    //console.log('putDataBudgets', dt);
}
function putDataBudgets(dt) {
    var percent = parseInt(dt[0].percent);
    var cantTotal = parseInt(dt[0].canTotal);
    var cantBudget = parseInt(dt[0].cantBudget);
    $('.GaugeMeter').data('percent', percent);
    $('.GaugeMeter').data('used', percent+'% '+cantBudget+'/'+cantTotal);
    $('.GaugeMeter').gaugeMeter();
    
    console.log('putDataBudgets', percent);
}
function putProgressData(dt) {
    console.log('putProgressData', dt)
    jQuery(document).ready(function(){
        jQuery('.progressbar').each(function(i){
            
            jQuery(this).find('.progressbar-bar').animate({
                width:0
            },1);

            dt.forEach(element => {
                if(element.loc_id == i+1){
                    $(this).attr('data-percent',parseInt(element.percent)+"%");
                    $(this).attr('data-used',parseInt(element.percent)+'%'+element.cant+'/'+element.total);
                    
                    $(this).find('.progress-bar-percent').text(parseInt(element.percent)+"%");
                }
            });
            
        });
    }); 
    jQuery(document).ready(function(){
        jQuery('.progressbar').each(function(){
            jQuery(this).find('.progressbar-bar').animate({
                width:0
            },1);

            jQuery(this).find('.progressbar-bar').animate({
                width:jQuery(this).attr('data-percent')
            },1500);
            
        });
    });
}
function putProgressDataAnalyst(dt) {
    console.log('putProgressData', dt);
    var i = 0;
    dt.forEach(element => {
        $('.budget-percent').eq(i).text(parseInt(element.cantBudget) );
        $('.plans-percent').eq(i).text(parseInt(element.cantPlans) );
        $('.details-percent').eq(i).text(parseInt(element.cantDetails) );
        console.log(element.percentBudget,element.percentPlans,element.percentDetails);
        i++;
    });
    $('.progressbar-title').each(function(i){
        $(this).addClass('objHidden');
        $(this).parent('div').parent('div').addClass('objHidden');
        $('.objHidden-title').addClass('objHidden');
    });
    $('.analyst-name').each(function(i){
        $(this).removeClass('objHidden');
        $(this).parent('div').parent('div').removeClass('objHidden');
        $('.objHidden-title').removeClass('objHidden');
    });
    
}
function putProgress(dt) {
    jQuery(document).ready(function(){
        jQuery('.progressbar').each(function(i){
            
            jQuery(this).find('.progressbar-bar').animate({
                width:0
            },1);
            dt.forEach(element => {
                if(element.loc_id == i+1){
                    $(this).attr('data-percent',parseInt(element.percent)+"%");
                    $(this).find('.progress-bar-percent').text(parseInt(element.percent)+"%");
                }
            });
        });
    }); 
    
    jQuery(document).ready(function(){
        jQuery('.progressbar').each(function(){
            jQuery(this).find('.progressbar-bar').animate({
                width:0
            },1);

            jQuery(this).find('.progressbar-bar').animate({
                width:jQuery(this).attr('data-percent')
            },1500);
            
        });
    });
}
function putInKPIS(dt) {
    
    if (dt[0].Total == 0) {
        
        $('#totalCamaras').text(0);
        $('#totalIluminacion').text(0);
        $('#totalMoviles').text(0);
        $('#equiposEspeciales').text(0);
    } else {
        $.each(dt, function (ind, elem) {
            
            elem.Camaras == null
                ? $('#totalCamaras').text(0)
                : $('#totalCamaras').text(elem.Camaras);
            elem.Iluminación == null
                ? $('#totalIluminacion').text(0)
                : $('#totalIluminacion').text(elem.Iluminación);
            elem.moviles == null
                ? $('#totalMoviles').text(0)
                : $('#totalMoviles').text(elem.Moviles);
            elem.especiales == null
                ? $('#equiposEspeciales').text(0)
                : $('#equiposEspeciales').text(elem.especiales);
            

            $( "#foroClass" ).removeClass( "bg-c-yellow bg-c-green bg-c-red bg-c-gray " );  
            switch (elem.foro)
            {
                case 'V':
                    $( "#foroClass" ).addClass( "bg-c-green" );
                    break;
                case 'A':
                    $( "#foroClass" ).addClass( "bg-c-yellow " );
                    break;
                case 'R':
                    $( "#foroClass" ).addClass( "bg-c-red" );
                    break;
                case 'N':
                    $( "#foroClass" ).addClass( "bg-c-gray " );
                    break;
                default:
                    $( "#foroClass" ).addClass( "bg-c-gray " );
            }
                              
        });
    }
}
function putTotales(dt) {
    if (dt[0].total == 0) {
        $('#totalbudgets').text(0);
        $('#totalProyects').text(0);
        $('#totalProyectsA').text(0);
        $('#totalProyectsT').text(0);
        $('#totalProyectsS').text(0);
        $('#totalProyectsD').text(0);
        $('#totalProyectsTypeLoc').text(0);
        $('#totalProyectsTypeCall').text(0);
        
        $('#totalProyectsClose').text(0);
        $('#totalProyUniM').text(0);
        
    } else {
        
        dt[0].cotizacion == null
                ? $('#totalbudgets').text(0)
                : $('#totalbudgets').text(dt[0].cotizacion);
        dt[0].presupuesto == null
                ? $('#totalProyects').text(0)
                : $('#totalProyects').text(dt[0].presupuesto);
        dt[0].aprobados == null
                ? $('#totalProyectsA').text(0)
                : $('#totalProyectsA').text(dt[0].aprobados);
        dt[0].subletting == null
                ? $('#totalProyectsS').text(0)
                : $('#totalProyectsS').text(dt[0].subletting);
        dt[0].analyst == null
                ? $('#totalProyectsD').text(0)
                : $('#totalProyectsD').text(dt[0].analyst);
        dt[0].transport == null
                ? $('#totalProyectsT').text(0)
                : $('#totalProyectsT').text(dt[0].transport);
        dt[0].call == null
                ? $('#totalProyectsTypeLoc').text(0)
                : $('#totalProyectsTypeLoc').text(dt[0].call);
        dt[0].call == null
                ? $('#totalProyectsTypeCall').text(0)
                : $('#totalProyectsTypeCall').text(dt[0].call);
        dt[0].close == null
                ? $('#totalProyectsClose').text(0)
                : $('#totalProyectsClose').text(dt[0].close);
        dt[0].uniMoviles == null
                ? $('#totalProyUniM').text(0)
                : $('#totalProyUniM').text(dt[0].uniMoviles);
    } 
}

// Hide submenus
$('#body-row .collapse').collapse('hide');

// Collapse/Expand icon
$('#collapse-icon').addClass('fa-angle-double-left');

// Collapse click
$('[data-toggle=sidebar-colapse]').click(function () {
    SidebarCollapse();
});

function SidebarCollapse() {
    $('.menu-collapsed').toggleClass('d-none');
    $('.sidebar-submenu').toggleClass('d-none');
    $('.submenu-icon').toggleClass('d-none');
    $('#sidebar-container').toggleClass('sidebar-expanded sidebar-collapsed');

    // Treating d-flex/d-none on separators with title
    var SeparatorTitle = $('.sidebar-separator-title');
    if (SeparatorTitle.hasClass('d-flex')) {
        SeparatorTitle.removeClass('d-flex');
    } else {
        SeparatorTitle.addClass('d-flex');
    }

    // Collapse/Expand icon
    $('#collapse-icon').toggleClass(
        'fa-angle-double-left fa-angle-double-right'
    );
}

function ButtonsActives(){

    $('.project-change')
        .unbind('click')
        .on('click', function(){
           
            let objgauge=document.querySelector('.GaugeMeter');
            let typedoc = $(this).attr('id');
            
            limpiarProgress();
            
            getProgressData(typedoc);
            getDataBudgets(typedoc); 
            $('.title-table').text($(this).text());
           
           
    })

    $('#projTrans')
        .unbind('click')
        .on('click', function(){
            console.log('transport');
            getPrjTrans('17,18');
            limpiarProgress();
            getProgressData('17,18','T');
            $('.title-table').text($(this).text());
           
    })

    $('#unidadesM')
        .unbind('click')
        .on('click', function(){
            console.log('transport');
            getPrjTrans('11,12');
            limpiarProgress();
            getProgressData('11,12','T');
            $('.title-table').text($(this).text());
           
    })

    $('#subarrendosA')
        .unbind('click')
        .on('click', function(){
            console.log('subarrendos');
            getSubarrendos();
            limpiarProgress();
            getProgressData(0,'S');
            $('.title-table').text($(this).text());
    })

    $('#dummy')
        .unbind('click')
        .on('click', function(){
            console.log('dummy');
            getDummy();
            limpiarProgress();
            getProgressDataTypeAnalyst(0,'D');
            $('.title-table').text($(this).text());
            
            $('.progress-bars-title').addClass('objHidden');
    })

    $('#typeLocation')
        .unbind('click')
        .on('click', function(){
            console.log('typeLocation');
            getTypeLocation(8);
            limpiarProgress();
            getProgressData(8);
            $('.title-table').text($(this).text());
            
    })

    $('#typeCall')
        .unbind('click')
        .on('click', function(){
            console.log('typeCall');
            getTypeCall();
            limpiarProgress();
            getProgressData(8,'TC');
            $('.title-table').text($(this).text());
            let array = [
                'Diurno',
                'Nocturno',
                'Mixto',
            ];
            $('.progressbar-title').each(function(i){
                if(i==3){
                    $(this).addClass('objHidden');
                    $(this).parent('div').parent('div').addClass('objHidden');
                }else{
                    $(this).find('span').text(array[i]);
                }
                
            });
            $('.progress-bars-title').text('Porcentajes por tipo de llamado');
    })

    $('#ProjectToClose')
        .unbind('click')
        .on('click', function(){
            console.log('ProjectToClose');
            getTypeLocation(9);
            limpiarProgress();
            getProgressData(9)
            $('.title-table').text($(this).text());
    })
    // 
}

function progress(){
    $('.GaugeMeter').gaugeMeter();
    jQuery(document).ready(function(){
        jQuery('.progressbar').each(function(){
            jQuery(this).find('.progressbar-bar').animate({
                width:0
            },1);

            jQuery(this).find('.progressbar-bar').animate({
                width:jQuery(this).attr('data-percent')
            },1500);
        });
    });
}

function limpiarProgress(){
    let array = [
        'Local',
        'Foraneos',
        'Foro',
        'Mixtos',
    ];
    $('.progress-bars-title').text('Porcentajes por tipo de locacion');
    $('.progress-bars-title').removeClass('objHidden');
    $('.progressbar-title').each(function(i){
       
        $(this).removeClass('objHidden');
        $(this).find('span').text(array[i]);
        $(this).parent('div').parent('div').removeClass('objHidden');
        $('.objHidden-title').removeClass('objHidden');
    });

    $('.analyst-name').each(function(i){
        $(this).addClass('objHidden');
        $(this).parent('div').parent('div').addClass('objHidden');
        $('.objHidden-title').addClass('objHidden');
    });
    jQuery(document).ready(function(){
        jQuery('.progressbar').each(function(){
            jQuery(this).find('.progressbar-bar').animate({
                width:0
            },1);
            
            $(this).attr('data-percent',"0%");

            $(this).find('.progress-bar-percent').text("0%");
           
        });
    });
}
$( ".actualizaGrafica" ).click(function() {
    cargaIndicadores();
  });

  function settingProdChg(){
    
    $('#SerieData').removeClass('overlay_hide');
    $('#tblDataChg').DataTable({
        order: [[1, 'asc']],
        dom: 'Blfrtip',
        bDestroy: true,
        lengthMenu: [
            [50, 100, -1],
            [50, 100, 'Todos'],
        ],
        buttons: [
            {
                // Boton aplicar cambios
                text: 'Reporte',
                className: 'btn-apply ',
                action: function (e, dt, node, config) {
                    getReport(st);
                },
            },
        ],
        pagingType: 'simple_numbers',
        language: {
            url: 'app/assets/lib/dataTable/spanish.json',
        },
        scrollY: 'calc(100vh - 290px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
            {data: 'projname', class: 'left'},
            {data: 'datestr', class: 'sku left'},
            {data: 'dateend', class: 'supply left'},
            {data: 'call', class: 'sku left'},
            {data: 'analyst', class: 'supply left'},
        ],
    });

}

function settingProdSub(){
    
    $('#SublettingData').removeClass('overlay_hide');
    $('#tblDataSub').DataTable({
        order: [[1, 'asc']],
        dom: 'Blfrtip',
        bDestroy: true,
        lengthMenu: [
            [50, 100, -1],
            [50, 100, 'Todos'],
        ],
        buttons: [
            {
                // Boton aplicar cambios
                text: 'Reporte',
                className: 'btn-apply ',
                action: function (e, dt, node, config) {
                    getReport(st);
                },
            },
        ],
        pagingType: 'simple_numbers',
        language: {
            url: 'app/assets/lib/dataTable/spanish.json',
        },
        scrollY: 'calc(100vh - 290px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
            {data: 'prdname', class: 'left'},
            {data: 'price', class: 'sku left'},
            {data: 'qty', class: 'supply left'},
            {data: 'pjtname', class: 'sku left'},
            {data: 'call', class: 'supply left'},
            {data: 'analyst', class: 'supply left'},
        ],
    });

}

function settingCierre(){
    
    $('#CierreData').removeClass('overlay_hide');
    $('#tblDataCierre').DataTable({
        order: [[1, 'asc']],
        dom: 'Blfrtip',
        bDestroy: true,
        lengthMenu: [
            [50, 100, -1],
            [50, 100, 'Todos'],
        ],
        buttons: [
            {
                // Boton aplicar cambios
                text: 'Reporte',
                className: 'btn-apply ',
                action: function (e, dt, node, config) {
                    getReport(st);
                },
            },
        ],
        pagingType: 'simple_numbers',
        language: {
            url: 'app/assets/lib/dataTable/spanish.json',
        },
        scrollY: 'calc(100vh - 290px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
            {data: 'projname', class: 'left'},
            {data: 'datestr', class: 'sku left'},
            {data: 'dateend', class: 'supply left'},
            {data: 'call', class: 'sku left'},
            {data: 'analyst', class: 'supply left'},
            {data: 'cliente', class: 'supply left'},
        ],
    });

}
function getReport(status,projecto){
    let user = Cookies.get('user').split('|');
    let u = user[0];
    let n = user[2];
    let h = localStorage.getItem('host');
    if (status != 'T' && status != 'S') {
        window.open(
            `${url}app/views/DashboardFull/DashboardFullReport.php?p=${status}&u=${u}&n=${n}&h=${h}`,
            '_blank'
        ); 
    }else{
        if(status == 'T'){
            window.open(
                `${url}app/views/DashboardFull/DashboardFullReportTransport.php?p=${status}&u=${u}&n=${n}&h=${h}`,
                '_blank'
            );
        } 
        if(status == 'S'){
            window.open(
                `${url}app/views/DashboardFull/DashboardFullReportSubletting.php?p=${status}&u=${u}&n=${n}&h=${h}`,
                '_blank'
            );
        } 
    }
    
    $('#SerieData').addClass('overlay_hide');
}







