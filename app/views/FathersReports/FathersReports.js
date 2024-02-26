let pj, px, pd;
var table = null;
var positionRow = 0;

$(document).ready(function () {
    if (verifica_usuario()) {
        inicial();
    }
});
//INICIO DE PROCESOS
function inicial() {
    folio = getFolio();
    setting_table();
    get_Proyectos();
    
    //fillContent();
   /*  get_Estatus_Mant();// agregado por Edna
    get_changes(); // agregado por Edna */

    $('#txtPeriod').on('blur', function () {
        validator();
    });
    $('#txtStatus').on('blur', function () {
        validator();
    });
    $('#txtDays').on('blur', function () {
        validator();
    });
    $('#txtHrs').on('blur', function () {
        validator();
    });
    $('#txtComments').on('blur', function () {
        validator();
    });
    $('#txtCost').on('blur', function () {
        validator();
    });
    $('#txtMotivo').on('blur', function () {
        validator();
    });
    $('#btn_subletting').on('click', function () {
        let acc = $(this).attr('data_accion');
        updating_serie(acc);
    });

    $('#tblProductForSubletting').on('click', 'tr', function () {
        positionRow = table.page.info().page * table.page.info().length + $(this).index();
  
        setTimeout(() => {
           RenglonesSelection = table.rows({selected: true}).count();
           if (RenglonesSelection == 0) {
              $('.btn-apply').addClass('hidden-field');
              console.log('seccionado 0');
           } else {
              console.log('seccionado 1 o mas');
              $('.btn-apply').removeClass('hidden-field');
           }
        }, 10);
     });
}

/** ++++  Setea el calendario ++++++ */
function setting_datepicket(sl, di, df) {
    console.log(sl);
    let fc = moment(Date()).format('DD/MM/YYYY');
    $(sl).daterangepicker(
        {
            singleDatePicker: false,
            autoApply: true,
            locale: {
                format: 'DD/MM/YYYY',
                daysOfWeek: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
                monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre',
                ],
                firstDay: 1,
            },
            minDate: fc,
            startDate: moment(di, 'DD/MM/YYYY'),
            endDate: moment(df, 'DD/MM/YYYY'),
            opens: 'left',
            drops: 'auto',
        },
        function (start, end, label) {
            let sdin = start.format('DD/MM/YYYY');
            let sdfn = end.format('DD/MM/YYYY');
            $('#txtPeriod').html(sdin + ' - ' + sdfn);
            setTimeout(() => {
                validator();
            }, 500);
        }
    );
}

/** ++++  Setea la tabla ++++++ */
function setting_table() {
    let title = 'Productos en subarrendo';
    let filename =
        title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');

    table = $('#tblProductForSubletting').DataTable({
        order: [[1, 'asc']],
        dom: 'Blfrtip',
        select: {
            style: 'multi',
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
                className: 'btn-apply hidden-field',
                action: function (e, dt, node, config) {
                   // view_report();
                    var selected = table.rows({selected: true}).data();
                    var idSelected = '';
                    selected.each(function (index) {
                       idSelected += index['editable'] + ',';
                    });
                    idSelected = idSelected.slice(0, -1);
                    if (idSelected != '') {
                        let id_prj = $('#txtProject').val();
                        let projecto =  $(`#txtProject option[value="${id_prj}"]`).text();
                        getReport(idSelected,projecto,id_prj);
                       //ConfirmDeletProveedor(idSelected);
                       //console.log(idSelected);
                    }
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
            { data: 'editable', class: 'edit objHidden' },
            { data: 'number', class: 'sku' },
            { data: 'projname', class: 'product-name' },
            { data: 'location', class: 'sku' },
            { data: 'typeloc', class: 'sku' },
            { data: 'datestar', class: 'date' },
            { data: 'dateend', class: 'date' },
            { data: 'time', class: 'date' },
        ],
    });  
}

/**  +++++ Obtiene los datos de los proyectos activos +++++  */
function get_Proyectos() {
    let liststat ="40";
	var pagina = 'Commons/listProjects';
	var par = `[{"liststat":"${liststat}"}]`;
    var tipo = 'json';
    var selector = put_Proyectos;
    fillField(pagina, par, tipo, selector);
}

/**  +++++ Obtiene los datos de los proyectos activos +++++  */
function getProjects(pj) {
    console.log(pj);
    var pagina = 'FathersReports/listProjectsForProject';
    var par = `[{"pjtId":"${pj}"}]`;
    var tipo = 'json';
    var selector = putProjects;
    fillField(pagina, par, tipo, selector);
}

/**  ++++   Coloca los proyectos en el listado del input */
function put_Proyectos(dt) {
    pj = dt;
    console.log(pj);
    if (dt[0].pjt_id!=0) {
        $.each(dt, function (v, u) {
            let H = `<option data_indx="${v}" value="${u.pjt_id}">${u.pjt_name}</option>`;
            $('#txtProject').append(H);
        });
    }
    
    $('#txtProject').on('change', function () {
        px = parseInt($('#txtProject option:selected').attr('data_indx'));
        $('#txtIdProject').val(pj[px].pjt_id);
        // let period = pj[px].pjt_date_start + ' - ' + pj[px].pjt_date_end;
        $('.objet').addClass('objHidden');
        getProjects(pj[px].pjt_id);
    });
}

/**  ++++   Coloca los proyectos en el listado del input */
function putProjects(dt) {
    // console.log(pj);
    pd = dt;
    let largo = $('#tblProductForSubletting tbody tr td').html();
    largo == 'Ningún dato disponible en esta tabla'
        ? $('#tblProductForSubletting tbody tr').remove()
        : '';
    tabla = $('#tblProductForSubletting').DataTable();
    
    tabla.rows().remove().draw();
    let cn = 0;
    if(dt[0].pjt_id!=0){
        $.each(pd, function (v, u) {
            tabla.row
                .add({
                    editable: u.pjt_id,
                    number: u.pjt_number,
                    projname: u.pjt_name,
                    location: u.pjt_location,
                    typeloc: u.loc_type_location,
                    datestar: u.pjt_date_start,
                    dateend: u.pjt_date_end,
                    time: u.pjt_time
                })
                .draw();
            cn++;
        });
    } 
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

    let period = $('#txtPeriod').val().split(' - ');
    let a = moment(period[1], 'DD/MM/YYYY');
    let b = moment(period[0], 'DD/MM/YYYY');
    let dif = a.diff(b, 'days');
    if (dif < 1) {
        ky = 1;
        msg += 'La fecha final debe ser por lo menos de un día de diferencia';
    }
    if (ky == 0) {
        $('#btn_subletting').removeClass('disabled');
    } else {
        $('#btn_subletting').addClass('disabled');
    }
    // console.log(msg);
}

function getReport(idSelected,projecto,id_prj){
    let user = Cookies.get('user').split('|');
    let u = user[0];
    let n = user[2];
    let h = localStorage.getItem('host');
    window.open(
        `${url}app/views/FathersReports/FathersReportsReport.php?p=${idSelected}&u=${u}&n=${n}&h=${h}`,
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

function fillContent() {
    
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
