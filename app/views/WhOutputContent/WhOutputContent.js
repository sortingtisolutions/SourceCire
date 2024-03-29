let products;
//let prjid = window.location.pathname.split("/").pop();
let prjid, serIdNew;
let serIdAnt=0;
let user,v,u,n,em, ar;  //datos de usuaria para impresion
let pjtcn;
let colores = ["#CD6155", "#AF7AC5", "#EC7063", "#5499C7", "#48C9B0", "#34495E", "#EB984E"];
//var prjid;
let aux=0;
$(document).ready(function () {
    if (verifica_usuario()) {

        prjid=Cookies.get('pjtid');
        inicial();
    }
});

//INICIO DE PROCESOS
function inicial() {
    user = Cookies.get('user').split('|');
    u = user[0];
    n = user[2];
    em = user[3];
    ar = user[6];

    setting_table_AsignedProd();
    getProjects(prjid);
    getComments_text(prjid);
    getDetailProds(prjid,em,ar);
    getFreelances(prjid);
    getAnalysts(prjid);
    getLocations(prjid);

    // Boton para registrar la salida del proyecto y los productos
    $('#recordOutPut').on('click', function () {
        confirm_to_GetOut(prjid);
     });

    // Boton para imprimir la salida de los productos
     $('#printOutPut').on('click', function () {
        printOutPut(prjid);
     });
      // Abre el modal de comentarios // 11-10-23
    $('.sidebar__comments .toComment')
        .unbind('click')
        .on('click', function () {
            showModalComments();
    });

}


function showModalComments() {
    let template = $('#commentsTemplates');
    // let pjtId = $('.version_current').attr('data-project');
    $('.invoice__modalBackgound').fadeIn('slow');
    $('.invoice__modal-general').slideDown('slow').css({ 'z-index': 401 });
    $('.invoice__modal-general .modal__body').append(template.html());
    $('.invoice__modal-general .modal__header-concept').html('Comentarios');
    closeModals();
    // $('.comments__addNew .invoiceInput').val('COMENTARIO PRUEBA XXX');
    fillComments(prjid);
}
/** ***** CIERRA MODALES ******* */
function closeModals(table) {
    $('.invoice__modal-general .modal__header .closeModal')
        .unbind('click')
        .on('click', function () {
            automaticCloseModal();

        });
}
function automaticCloseModal() {

    $('.invoice__modal-general').slideUp(400, function () {
        $('.invoice__modalBackgound').fadeOut(400);
        $('.invoice__modal-general .modal__body').html('');
        $('#listLocationsTable').DataTable().destroy;
        let tabla=$('#listLocationsTable').DataTable();
        tabla.rows().remove().draw();
    });
}

function fillComments(pjtId) {
    // console.log(pjtId);
    $('.comments__addNew .invoice_button')
        .unbind('click')
        .on('click', function () {
            let comSrc = 'projects';
            let comComment = $('#txtComment').val();

            if (comComment.length > 3) {
                let par = `
                    [{
                        "comSrc"        : "${comSrc}",
                        "comComment"    : "${comComment}",
                        "pjtId"         : "${prjid}"
                    }]
                    `;
                var pagina = 'WhOutputContent/InsertComment';
                var tipo = 'json';
                var selector = addComment;
                fillField(pagina, par, tipo, selector);
            }
        });

    getComments(pjtId);
}

function putComments(dt) {
    $('.comments__list').html('');
    if (dt[0].com_id > 0) {
        $.each(dt, function (v, u) {
            fillCommnetElements(u);
        });
    }

}

function fillCommnetElements(u) {
    // console.log(u.com_comment);
    let H = `
        <div class="comment__group" style="border-bottom: 1px solid var(--br-gray-soft); padding: 0.2rem; width: 100%;">
            <div class="comment__box comment__box-date" style="width: 100%;text-align: right; font-size: 0.9em; color: var(--in-oxford);"><i class="far fa-clock" style="padding: 0 0.5rem;"></i>${u.com_date}</div>
            <div class="comment__box comment__box-text">${u.com_comment}</div>
            <div class="comment__box comment__box-user" style="text-align: left; font-size: 0.9em; color: var(--in-oxford);">${u.com_user}</div>
        </div>
    `;

    $('.comments__list').prepend(H);
    getComments_text(prjid);
}

function addComment(dt) {
    // console.log(dt[0]);
    fillCommnetElements(dt[0]);
    $('#txtComment').val('');
}//********** */

// Solicita los paquetes  OK
function getProjects(prjid) {
    var pagina = 'WhOutputContent/listProjects';
    var par = `[{"pjt_id":"${prjid}"}]`;
    var tipo = 'json';
    var selector = putProjects;
    fillField(pagina, par, tipo, selector);
}

// Solicita los analistas
function getAnalysts(prjid) {
    var pagina = 'WhOutputContent/listAnalysts';
    var par = `[{"pjt_id":"${prjid}"}]`;
    var tipo = 'json';
    var selector = putAnalysts;
    fillField(pagina, par, tipo, selector);
}
// Solicita los analistas
function  getLocations(prjid) {
    var pagina = 'WhOutputContent/listLocations';
    var par = `[{"pjt_id":"${prjid}"}]`;
    var tipo = 'json';
    var selector = putLocations;
    fillField(pagina, par, tipo, selector);
}
// Solicita los productos del proyecto  OK
function getDetailProds(prjid, emp_id,areid) {
    var pagina = 'WhOutputContent/listDetailProds';
    var par = `[{"pjt_id":"${prjid}", "empid":"${emp_id}", "areid":"${areid}"}]`;
    var tipo = 'json';
    var selector = putDetailsProds;
    fillField(pagina, par, tipo, selector);
}

function getFreelances(prjid) {
    var pagina = 'WhOutputContent/listFreelances';
    var par = `[{"pjt_id":"${prjid}"}]`;
    var tipo = 'json';
    var selector = putFreelances;
    fillField(pagina, par, tipo, selector);
}
//Solicita las series de los productos  OK
function getSeries(pjtcnid) {
    var pagina = 'WhOutputContent/listSeries';
    var par = `[{"pjtcnid":"${pjtcnid}"}]`;
    var tipo = 'json';
    var selector = putSeries;
    fillField(pagina, par, tipo, selector);
}

// Solicita las series disponibles
function getSerieDetail(serid, serorg, serpjtvridorg) {
    var pagina = 'WhOutputContent/listSeriesFree';
    var par = `[{"serid":"${serid}", "serorg":"${serorg}", "srpjvrorg":"${serpjtvridorg}"}]`;
    var tipo = 'json';
    var selector = putSerieDetails;
    fillField(pagina, par, tipo, selector);
}

// Solicita los comentarios al proyecto // 11-10-23
function getComments_text(prjid) {
    var pagina = 'WhOutputContent/listComments';
    var par = `[{"pjt_id":"${prjid}"}]`;
    var tipo = 'json';
    var selector = puComments;
    fillField(pagina, par, tipo, selector);
}
/** Obtiene el listado de los comentarios del proyecto */ // 11-10-23
function getComments(pjtId) {
    var pagina = 'WhOutputContent/listComments';
    var par = `[{"pjId":"${pjtId}"}]`;
    var tipo = 'json';
    var selector = putComments;
    fillField(pagina, par, tipo, selector);
}

//**************  NIVEL 1 DE DATOS *****************************************

// Configura la tabla de productos del proyecto
function setting_table_AsignedProd() {
    let title = 'Contenido de proyectos';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    $('#tblAsignedProd').DataTable({
        bDestroy: true,
        order: [[ 4, 'asc' ], [ 1, 'asc' ]],
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
                    // Boton imprimir contenido jjr
                    text: 'Print Contenido',
                    className: 'btn-apply',
                    action: function (e, dt, node, config) {
                        // printContent(prjid, 'A');
                        printReports(prjid, 'C');
                    },
                },
                {
                    // Boton imprimir detalle jjr
                    text: ' Print Detalle ',
                    className: 'btn-apply',
                    action: function (e, dt, node, config) {
                        // printDetail(prjid);
                        printReports(prjid, 'D');
                    },
                },
                 {
                    // Boton imprimir detalle jjr
                    text: ' Print Accesories ',
                    className: 'btn-apply',
                    action: function (e, dt, node, config) {
                        // printDetail(prjid);
                        printReports(prjid, 'A');
                    },
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
            {data: 'editable', class: 'edit'},
            {data: 'pack_sku', class: 'sel sku'},
            {data: 'packname', class: 'sel supply'},
            {data: 'packcount', class: 'sel sku'},
            {data: 'packstatus', class: 'sel sku'},
            {data: 'packlevel', class: 'sel sku'},
        ],
    });
}

//AGREGA LOS DATOS GENERALES DEL PROYECTO
function putProjects(dt) {
    let usrname=n.replaceAll('+',' ');
    $('#txtProjectName').val(dt[0].pjt_name);
    $('#txtProjectNum').val(dt[0].pjt_number);
    $('#txtTipoProject').val(dt[0].pjttp_name);
    $('#txtStartDate').val(dt[0].pjt_date_start);
    $('#txtEndDate').val(dt[0].pjt_date_end);
    $('#txtCustomer').val(dt[0].cus_name);
}
function putAnalysts(dt) {
    if (dt[0].emp_id != 0) {
        $.each(dt, function (v, u) {
            let H = `<option value="${u.emp_id}"> ${u.emp_fullname} - ${u.are_name}</option>`;
            $('#txtAnalyst').append(H);
        });
        $('#txtAnalyst').val(dt[0].emp_id); // 11-10-23
    }
}

function putLocations(dt) {
    if (dt[0].locations != 0) {
        $.each(dt, function (v, u) {
            let H = `<option value="${u.locations}"> ${u.locations}</option>`;
            $('#txtLocation').append(H);
        });
        $('#txtLocation').val(dt[0].locations); // 11-10-23
    }
}

// ### LISTO ### Llena la TABLA INICIAL de los detalles del proyecto
function putDetailsProds(dt) {
    let tabla = $('#tblAsignedProd').DataTable();
    tabla.rows().remove().draw();
    let valstage='';
    let locsecc='';
    let icon = '';
    if (dt[0].pjtcn_id > 0)
    {
        $.each(dt, function (v, u){
            if (u.section == 'Base') { valstage='#e2e8f8'; }
            else if (u.section == 'Extra') { valstage='#f8e2e8'; }
            else if (u.section == 'Por dia') { valstage='#e8f8c2'; }
            else { valstage='#e2f8f2'; }

            if (parseInt(u.pjtcn_quantity) == parseInt(u.cant_ser)) {
                icon = 'fas fa-regular fa-thumbs-up';
            } else{
                icon ='fas fa-edit';
            }
            let skufull = String(u.pjtcn_prod_sku).slice(7, 11) == '' ? String(u.pjtcn_prod_sku).slice(0, 7) : String(u.pjtcn_prod_sku).slice(0, 7) + '-' + String(u.pjtcn_prod_sku).slice(7, 11);
            var rownode=tabla.row
                .add({
                    editable: `<i class="${icon} toLink" id="cn-${u.pjtcn_id}"></i>`,
                    pack_sku: skufull,
                    packname: u.pjtcn_prod_name,
                    packcount: u.pjtcn_quantity,
                    packstatus: u.section,
                    packlevel: u.pjtcn_prod_level,
                })
                .draw().node();
            $(rownode).css("background-color", valstage);
        });
        activeIcons();
    }else{
        $('#recordOutPut').hide();
    }
}

function putFreelances(dt) {
    if (dt[0].free_id != 0) {
        $.each(dt, function (v, u) {
            let H = `<option value="${u.free_id}"> ${u.free_name} - ${u.are_name}</option>`;
            $('#txtFreelance').append(H);
        });
        $('#txtFreelance').val(dt[0].free_id); // 11-10-23
    }
}
// ***************** se agregan los comentarios del proyecto jjr ***************
function puComments(dt) {
    if (dt[0].com_id != '0')
    {
        let valConcat=''
        $.each(dt, function (v, u){
            valConcat=valConcat + u.com_user + ': ' + u.com_comment+'\n'; // 11-10-23
        });
    $('#txtComments').text(valConcat);
    }
}

// ### LISTO ###   habilita el botones para validar en TABLA INICIAL
function activeIcons() {
    $('.toLink')
        .unbind('click')
        .on('click', function () {
            let pjtcnid = $(this).attr('id').split('-')[1];
            pjtcn = pjtcnid;
            if (pjtcnid > 0) {
                getSeries(pjtcnid);
            }
        });
}
function getEvents(serId) {
    var pagina = 'WhOutputContent/GetEventos';
    var par = `[{"ser_id":"${serId}"}]`;
    var tipo = 'json';
    var selector = putEvents;
    fillField(pagina, par, tipo, selector);
}

function putEvents(dt) {
    let array = [];
    let i = 0;
    dt.forEach(element => {
        let x = Math.floor(Math.random()*colores.length);
        array[i]={"id": element.id, "title": element.title, "start": element.start, "end": element.end,"color" : colores[x]};
        i++;
    });
    //strs = dt;
    calendario(array);
}
function calendario(cal){
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        headerToolbar: {
            left: 'prev,next,today',
            center: 'title',
            right: 'dayGridMonth' 
        },
        navLinks: true, // can click day/week names to navigate views
        editable: true,
        selectable: true,
        height: 300,
        width:300,
        events: cal,
        eventClick: function(calEvent, jsEvent, view){
            console.log(calEvent);
        }
    }); 
    calendar.render();
}

// ### LISTO ### Llena prepara la table dentro del modal para series ### LISTO -- MODAL 1###
function putSeries(dt) {
    // console.log('putSeries');
    if (dt[0].ser_id > 0) {
        settingSeries(dt);
        build_modalSeries(dt);
        activeIconsSerie();
    }else{
        $('#SinSerieModal').removeClass('overlay_hide');
        $('#SinSerieModal .btn_close')
        .unbind('click')
        .on('click', function () {
            $('.overlay_background').addClass('overlay_hide');
            $('.overlay_closer .title').html('');
            $('#tblSerie').DataTable().destroy;
        });
    }
}

function settingSeries(dt){
    $('#SerieModal').removeClass('overlay_hide');
    $('#tblSerie').DataTable({
        // retrieve: true,
        bDestroy: true,
        // dom: 'Blfrtip',
        order: [[1, 'asc']],
        dom: 'Blfrtip',
        lengthMenu: [
            [100, 200, -1],
            [100, 200, 'Todos'],
        ],
        buttons: [
            {
                // Boton imprimir contenido jjr
                text: 'Select All OK',
                className: 'btn-apply',
                action: function (e, dt, node, config) {
                    readAceptTable();
                    // printContent(prjid);
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
            {data: 'sermodif', class: 'edit'},
            {data: 'seriesku', class: 'sku left'},
            {data: 'prdname', class: 'left'},
            {data: 'sernumber', class: 'sku'},
            {data: 'sertype', class: 'sku'},
            {data: 'serfchout', class: 'sku'},
            {data: 'serfchin', class: 'sku'},
            {data: 'serlevel', class: 'sku'},
        ],
    });

    $('#SerieModal .btn_close')
        .unbind('click')
        .on('click', function () {
            $('.overlay_background').addClass('overlay_hide');
            $('.overlay_closer .title').html('');
            $('#tblSerie').DataTable().destroy;
    });

}

function readAceptTable() {
    $('#tblSerie tbody tr').each(function (v, u) {
        let serId = $(this).attr('id');
        let serdata = $(this).attr('data');
        checkSerie(serId);
        setTimeout(function(){
            $('.overlay_background').addClass('overlay_hide');
                $('.overlay_closer .title').html('');
                $('#tblSerie').DataTable().destroy;
        }, 3000);
    });
    //getDetailProds(prjid,em, ar);
}

// ### LISTO ### Llena con datos de series la tabla del modal --- MODAL 1
function build_modalSeries(dt) {
        //  console.log('build_modalSeries',dt);
         let tabla = $('#tblSerie').DataTable();
        //  $('.overlay_closer .title').html(`ASIGNADAS: ${dt[0].pjtdt_prod_sku} - ${dt[0].prd_name}`);
        $('.overlay_closer .title').html(`ASIGNADAS: ${dt[0].prd_name}`);
        $('.overlay_closer .title_calendar').html(dt[0].prd_name);

         tabla.rows().remove().draw();
         if (dt[0].ser_id > 0) {
            $.each(dt, function (v, u){
                let skufull = String(u.pjtdt_prod_sku).slice(8, 12) == '' ? String(u.pjtdt_prod_sku).slice(0, 8) : String(u.pjtdt_prod_sku).slice(0, 8) + String(u.pjtdt_prod_sku).slice(8, 12);
                let sku = String(u.pjtdt_prod_sku).slice(0, 8);
                let acc = String(u.pjtdt_prod_sku).slice(8,9) == 'A' ? skufull : sku;
                let valstage = u.ser_stage == 'TR' ? 'color:#CC0000' : 'color:#3c5777';
                let level;
                if(u.pjtvr_section == 4){
                    level = "Subarrendo";
                }else{
                    level="Interno"
                }
                tabla.row
                    .add({
                        sermodif: `<i class="fas fa-calendar-alt choice Calendar" id="${u.ser_id}" data-serie ="${u.prd_name}"></i> 
                                    <i class="fas fa-edit toChange" data-content="${acc}|${skufull}|${u.pjtdt_id}|${u.ser_id}|${u.pjtvr_id}"></i>
                                    <i class="fas fa-check-circle toCheck" id="${u.ser_id}" style="${valstage}"></i>`,
                        seriesku: skufull,
                        prdname: u.prd_name,
                        sernumber: u.ser_no_econo,
                        sertype: u.ser_serial_number,
                        serfchout: u.pjtpd_day_start,
                        serfchin: u.pjtpd_day_end,
                        serlevel: level,
                    })
                .draw();
                $(`#${u.ser_id}`).parents('tr').attr('id',u.ser_id);
            });
         }
}

/** ### LISTO ### +++++  Activa los iconos del modal de serie */
function activeIconsSerie() {
    $('.toChange')
        .unbind('click')
        .on('click', function () {
            let serprd = $(this).attr('data-content').split('|')[0];
            let serorg = $(this).attr('data-content').split('|')[1];
            let detIdChg = $(this).attr('data-content').split('|')[2];
            let serIdChg = $(this).attr('data-content').split('|')[3];
            let serPjtvrIdOrg = $(this).attr('data-content').split('|')[4];

            if (serprd != "") {
                getSerieDetail(serprd, detIdChg, serPjtvrIdOrg);
            }
    });
    $('.Calendar')
    .unbind('click')
    .on('click', function () {
        let serSKU = $(this).attr('data-serie');
        getEvents($(this).attr('id'));
        calendario('');
        $('#CalendarModal').removeClass('overlay_hide');
        $('#CalendarModal').fadeIn('slow');
        $('#CalendarModal').draggable({
            handle: ".overlay_modal"
        });

        $('#CalendarModal .btn_close')
            .unbind('click')
            .on('click', function () {
                $('#CalendarModal').addClass('overlay_hide');
            }); 
        
    });

    $('.toCheck')
        .unbind('click')
        .on('click', function () {
        let serprd = $(this).attr('id');
            checkSerie(serprd);
            let tabla = $('#tblSerie').DataTable();
            let numRows = tabla.rows().count();
            aux++;
            if (numRows==1 || aux==numRows) {
                $('.overlay_background').addClass('overlay_hide');
                $('.overlay_closer .title').html('');
                $('#tblSerie').DataTable().destroy;
                aux=0;
            }
            //getDetailProds(prjid,em, ar);
        });
}

function checkSerie(pjtcnid) {
    var pagina = 'WhOutputContent/checkSeries';
    var par = `[{"serId":"${pjtcnid}"}]`;
    var tipo = 'html';
    var selector = myCheck;
    fillField(pagina, par, tipo, selector);
}

function myCheck(dt){
    // console.log(dt);
    // let sku = $('#'+dt).find('.toChange').attr('data-content').split('|')[0];
    //$('#'+dt).css({"color":"#CC0000"});
    $('#'+dt).children(".claseElemento").css({"color":"#CC0000"});
    $('#'+dt).find('.toCheck').css({"color":"#CC0000"});
    $('#'+dt).find('.toChange').css({"color":"#3c5878"});
    $('#cn-'+pjtcn).removeClass('fas fa-edit');
    $('#cn-'+pjtcn).addClass('fas fa-regular fa-thumbs-up');
    // getDetailProds(prjid,em);

}

//**************  NIVEL 3 DE DATOS  *****************************************
function settingChangeSerie(){
    // console.log('setting');
    $('#ChangeSerieModal').removeClass('overlay_hide');
    $('#tblChangeSerie').DataTable({
        // retrieve: true,
        bDestroy: true,
        // dom: 'Blfrtip',
        order: [[2, 'asc']],
        lengthMenu: [
            [100, 200, -1],
            [100, 200, 'Todos'],
        ],
        buttons: [
            {
                // Boton imprimir contenido jjr
                text: 'Select All OK',
                className: 'btn-apply',
                action: function (e, dt, node, config) {
                    // printContent(prjid);
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
            {data: 'serchange', class: 'edit'},
            {data: 'serdetnumber', class: 'supply'},
            {data: 'serdetsitu', class: 'sku'},
            {data:  'projectname', class: 'supply left'},
        ],
    });

    $('#ChangeSerieModal .btn_close')
        .unbind('click')
        .on('click', function () {
            $('#ChangeSerieModal').addClass('overlay_hide');
            $('#ChangeSerieModal .title').html('');
            $('#tblChangeSerie').DataTable().destroy;
        });
}


// AGREGA LAS SERIES DISPONIBLES
function putSerieDetails(dt){
    // console.log(dt);
    if(dt[0].ser_id !='0'){
        settingChangeSerie();
        let locicon='';
        let tabla = $('#tblChangeSerie').DataTable();
        // $('.overlay_closer .title').html(` ${dt[0].prd_name} - ${dt[0].prd_sku}`);
        $('#ChangeSerieModal .overlay_closer .title').html(`DISPONIBLES`);
        tabla.rows().remove().draw();
        if (dt[0].ser_id > 0) {
            $.each(dt, function (v, u) {
                tabla.row
                    .add({
                        serchange: `<i class='fas fa-check-circle toChangeSer sr${u.ser_id}' id="${u.ser_id}" seridorg="${u.id_orig}" detailId="${u.pjtdt_id}" prjVersion="${u.pjtvr_id}" pjVersionOrg="${u.pjvr_id_org}"></i>`,
                        serdetnumber: u.ser_serial_number,
                        serdetsitu: u.ser_no_econo,
                        projectname: u.pjt_name

                    })
                    .draw();
                //$(`#${u.ser_id}`).parents('tr').attr('id', u.ser_id);
            });
        }


        activeIconsNewSerie();
    } else{
        alert('Ya no existen Series Disponibles para cambiar');
    }
}

/** +++++  Activa los iconos del modal de serie free */
function activeIconsNewSerie() {
    $('.toChangeSer')
    .unbind('click')
    .on('click', function () {
        let serIdSel = $(this).attr('id');
        let serIdOrg = $(this).attr('seridorg');
        let detailId = $(this).attr('detailId');
        let prjVersion = $(this).attr('prjVersion');
        let pjVersionOrg = $(this).attr('pjVersionOrg');
        serIdNew=serIdSel;
        $('.sr'+serIdSel).css({"color":"#CC0000"});  //#3c5777  normal
        // $('#'+serIdOrig).children(".claseElemento").cssmyCheck({"color":"#CC0000"});
        changeSerieNew(serIdSel, serIdOrg, detailId, prjVersion, pjVersionOrg);
    });
}

function changeSerieNew(serIdNew,serIdOrg, detailId, prjVersion, pjVersionOrg) {
    console.log ('changeSerieNew', serIdNew,serIdOrg,detailId,prjVersion,pjVersionOrg);
    var pagina = 'WhOutputContent/changeSerieNew';
    var par = `[{"serIdNew":"${serIdNew}", 
                "serIdOrg":"${serIdOrg}", 
                "detailIdNew":"${detailId}", 
                "prjVersion":"${prjVersion}", 
                "pjVersionOrg":"${pjVersionOrg}"}]`;
    var tipo = 'html';
    var selector = myCheckUp;
    fillField(pagina, par, tipo, selector);
}

function myCheckUp(dt){
    // console.log('myCheckUp-',dt);
    if (dt > 0) {
        $('#ChangeSerieModal').addClass('overlay_hide');
        //$('.overlay_closer .title').html('');
        $('#tblChangeSerie').DataTable().destroy; 
        getSeries(pjtcn);
    }else{
        $('#rChangeSerieModal').removeClass('overlay_hide');
        if (dt == '-1') {
            $('#txtNoRealizacion').text("La serie por la que estas intentando cambiar es la misma.");
        }
        if(dt == 0){
            $('#txtNoRealizacion').text("La serie que intentas modificar podria tener series a futuro que coinciden con las fechas de uso en este proyecto");
        }

        $('#rChangeSerieModal .btn_close')
            .unbind('click')
            .on('click', function () {
                $('#rChangeSerieModal').addClass('overlay_hide');
            }); 
    }
}

/**********  Confirma salida de equipos ***********/
function confirm_to_GetOut(pjtid) {
    $('#starClosure').modal('show');
    $('#txtIdClosure').val(pjtid);

    $('#btnClosure').on('click', function () {
        $('#starClosure').modal('hide');
         modalLoading('S');

        var pagina = 'WhOutputContent/ProcessGetOutProject';
        var par = `[{"pjtid":"${pjtid}"}]`;
        var tipo = 'json';
        var selector = putToWork;
        fillField(pagina, par, tipo, selector);
        /* let Arg='23|56|PASO1,PASO2 ';
        putToWork(Arg); */
    });
}

function putToWork(dt){
    // console.log('TERMINO ACTUALIZAR', dt);
    let folio=dt;
    $('#recordOutPut').hide();
    $('.bprint').removeClass('hide-items');
    $('.resFolio').text(refil(folio, 7));
    $('#MoveFolioModal').modal('show');
    $('#btnHideModal').on('click', function () {
        // window.location = 'WhOutputs';
        $('#MoveFolioModal').modal('hide');

    });
    modalLoading('H');
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
    let h = localStorage.getItem('host');
    let v = verId;
    let nameproject = $('#txtProjectName').val();
    let numproject = $('#txtProjectNum').val();
    window.open(
        `${url}app/views/WhOutputContent/WhOutputContentReport.php?v=${v}&u=${u}&n=${n}&h=${h}&em=${em}&np=${nameproject}&nump=${numproject}`,
        '_blank'
    );
}

/**********  Impresion del contenido de un proyecto ***********/
function printReports(pjtId, typrint) {
    // let user = Cookies.get('user').split('|');
    // let u = user[0];
    // let n = user[2];
    let v = pjtId;
    let h = localStorage.getItem('host');
    let nameproject = $('#txtProjectName').val();
    let numproject = $('#txtProjectNum').val();
    switch (typrint) {
        case "C":  // Contenido global
            window.open(
                `${url}app/views/WhOutputContent/WhOutputContentReport.php?v=${v}&u=${u}&n=${n}&em=${em}&h=${h}&np=${nameproject}&nump=${numproject}`,
                '_blank'
            );
          break;
        case "D":  // Detalles del contenido con series
            window.open(
                `${url}app/views/WhOutputContent/WhOutputDetailReport.php?v=${v}&u=${u}&n=${n}&em=${em}&h=${h}&np=${nameproject}&nump=${numproject}`,
                '_blank'
            );
          break;
        case "A": // Detalles y Accesorios del contenido con series
            window.open(
                `${url}app/views/WhOutputContent/WhOutputAccesoryReport.php?v=${v}&u=${u}&n=${n}&em=${em}&h=${h}&np=${nameproject}&nump=${numproject}`,
                '_blank'
            );
          break;
        default:
        //   console.log("No Hay REPORTE");
          break;
      }

}
