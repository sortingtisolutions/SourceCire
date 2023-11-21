let products;
//let prjid = window.location.pathname.split("/").pop();
let prjid;
let glbcnid,glbprjnum;
let motmanteince;
let user,v,u,n,em;  //datos de usuaria para impresion
let aux=0;
$(document).ready(function () {
    if (verifica_usuario()) {
        
        prjid=Cookies.get('pjtid');
        inicial();
    }
});
//INICIO DE PROCESOSprjid
function inicial() {
    user = Cookies.get('user').split('|');
    u = user[0];
    n = user[2];
    em = user[3];

    setting_table_AsignedProd();
    getProjects(prjid);
    getDetailProds(prjid,em);
    getFreelances(prjid);
    getReason();
    getComments_text(prjid); // 11-10-23
    getAnalysts(prjid);
    
    $('#recordInPut').on('click', function () {
        confirm_to_GetOut(prjid);
     });

     $('#printInPut').on('click', function () {
        printOutPutContent(prjid);
    
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
    console.log(pjtId);
    
    // Agrega nuevo comentario
    $('.comments__addNew .invoice_button')
        .unbind('click')
        .on('click', function () {
           
            let comSrc = 'projects';
            let comComment = $('#txtComment').val();

            console.log(comComment);
            if (comComment.length > 3) {
                let par = `
                    [{
                        "comSrc"        : "${comSrc}",
                        "comComment"    : "${comComment}",
                        "pjtId"         : "${prjid}"
                    }]
                    `;
                var pagina = 'WorkInputContent/InsertComment';
                var tipo = 'json';
                console.log(par);
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
            console.log(u);
            fillCommnetElements(u);
        });
    }
    
}
// ***************** se agregan los comentarios del proyecto jjr ***************
function puComments(dt) {
    if (dt[0].com_id != '0')
    {
        let valConcat=''
        $.each(dt, function (v, u){
            valConcat=valConcat + u.com_user + ': ' + u.com_comment+'\n';
        });
    $('#txtComments').text(valConcat);
    }
}
function fillCommnetElements(u) {
    console.log(u.com_comment);
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
    console.log(dt[0]);
    fillCommnetElements(dt[0]);
    $('#txtComment').val('');
}


// Solicita los comentarios al proyecto
function getComments_text(prjid) {
    //console.log(prjid)
    var pagina = 'WhOutputContent/listComments';
    var par = `[{"pjt_id":"${prjid}"}]`;
    var tipo = 'json';
    var selector = puComments;
    fillField(pagina, par, tipo, selector);
}
/** Obtiene el listado de los comentarios del proyecto */
function getComments(pjtId) {
    var pagina = 'WorkInputContent/listComments';
    var par = `[{"pjId":"${pjtId}"}]`;
    var tipo = 'json';
    var selector = putComments;
    fillField(pagina, par, tipo, selector);
} //************* */
// Solicita los paquetes  OK
function getProjects(prjid) {
    // console.log(prjid)
    var pagina = 'WorkInputContent/listProjects';
    var par = `[{"pjt_id":"${prjid}"}]`;
    var tipo = 'json';
    var selector = putProjects;
    fillField(pagina, par, tipo, selector);
}

// Solicita los productos del proyecto  OK
function getDetailProds(prjid,empid) {
    var pagina = 'WorkInputContent/listDetailProds';
    var par = `[{"pjt_id":"${prjid}", "empid":"${empid}"}]`;
    var tipo = 'json';
    var selector = putDetailsProds;
    fillField(pagina, par, tipo, selector);
}

function getSeries(pjtcnid) {
    // console.log('ID-Contenido Producto', pjtcnid);
    var pagina = 'WorkInputContent/listSeries';
    var par = `[{"pjtcnid":"${pjtcnid}"}]`;
    var tipo = 'json';
    var selector = putSeries;
    fillField(pagina, par, tipo, selector);
}

function getFreelances(prjid) {
    //console.log(prjid)
    var pagina = 'WorkInputContent/listFreelances';
    var par = `[{"pjt_id":"${prjid}"}]`;
    var tipo = 'json';
    var selector = putFreelances;
    fillField(pagina, par, tipo, selector);
}

//Solicita las series de los productos  OK
function getReason() {
    // console.log('ID-Contenido Producto', pjtcnid);
    var pagina = 'WorkInputContent/listReason';
    var par = `[{"pjtcnid":""}]`;
    var tipo = 'json';
    var selector = putReason;
    fillField(pagina, par, tipo, selector);
}

// Solicita las series disponibles
function getSerieDetail(serid, serorg) {
    var pagina = 'WorkInputContent/listSeriesFree';
    var par = `[{"serid":"${serid}", "serorg":"${serorg}" }]`;
    var tipo = 'json';
    var selector = putSerieDetails;
    fillField(pagina, par, tipo, selector);
}

// Solicita los analistas  
function getAnalysts(prjid) {
    //console.log(prjid)
    var pagina = 'WorkInputContent/listAnalysts';
    var par = `[{"pjt_id":"${prjid}"}]`;
    var tipo = 'json';
    var selector = putAnalysts;
    fillField(pagina, par, tipo, selector);
}

function createTblRespaldo(prjid,prjnum) {
    // console.log('createTblRespaldo', prjid,prjnum)
    var pagina = 'WorkInputContent/createTblResp';
    var par = `[{"prjid":"${prjid}", "prjnum":"${prjnum}"}]`;
    var tipo = 'json';
    var selector = putCreateTbl;
    fillField(pagina, par, tipo, selector);
}

// Listar analistas
function putAnalysts(dt) {
    if (dt[0].emp_id != 0) {
        $.each(dt, function (v, u) {
            let H = `<option value="${u.emp_id}"> ${u.emp_fullname} - ${u.are_name}</option>`;
            $('#txtAnalyst').append(H);
        });
        $('#txtAnalyst').val(dt[0].emp_id); // 11-10-23
    }
    
}

//**************  NIVEL 1 DE DATOS *****************************************
// Configura la tabla de productos del proyecto
function setting_table_AsignedProd() {
    
    let tabla = $('#tblAsigInput').DataTable({
        bDestroy: true,
        /* order: [[1, 'asc']], */
        dom: 'Blfrtip',
        lengthMenu: [
                [100, 200, -1],
                [100, 200, 'Todos'],
            ],
        buttons: [
            {
                // Boton aplicar cambios
                text: 'Generar paquete',
                className: 'btn-apply hidden-field',
                action: function (e, dt, node, config) {
                    /*read_package_table();*/
                },
            },{
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
            {data: 'packlevel', class: 'sel sku'},
            {data: 'packEquip', class: 'sel sku'},   
            {data: 'packstatus', class: 'sel supply'},
            
        ],// 11-10-23
    });
}

//AGREGA LOS DATOS GENERALES DEL PROYECTO
function putProjects(dt) {
    // console.log('DATOS del PROYECTO', dt);
    let usrname=n.replaceAll('+',' ');
    $('#txtProjectName').val(dt[0].pjt_name);
    $('#txtProjectNum').val(dt[0].pjt_number);
    glbprjnum=dt[0].pjt_number;
    $('#txtTipoProject').val(dt[0].pjttp_name);
    $('#txtEndDate').val(dt[0].pjt_date_end);
    /* $('#txtAnalyst').val(dt[0].emp_fullname);// 11-10-23
    $('#txtFreelance').val(dt[0].free_id);// 11-10-23 */
}

function putFreelances(dt) {
    if (dt[0].free_id != 0) {
        $.each(dt, function (v, u) {
            let H = `<option value="${u.free_id}"> ${u.free_name} - ${u.are_name}</option>`;
            $('#txtFreelance').append(H);
        });
        $('#txtFreelance').val(dt[0].free_id);// 11-10-23
    }
}

// ### LISTO ### Llena la TABLA INICIAL de los detalles del proyecto

function putDetailsProds(dt) {
    let tabla = $('#tblAsigInput').DataTable();
    tabla.rows().remove().draw();
    
    if (dt[0].pjtpd_id != '0')
    {
        let valstage='';// 11-10-23
        let tabla = $('#tblAsigInput').DataTable();
        let valcontent='';

        $.each(dt, function (v, u)         {
            // 11-10-23
            if (u.section == 'Base') { valstage='#e2e8f8'; }
            else if (u.section == 'Extra') { valstage='#f8e2e8'; }
            else if (u.section == 'Por dia') { valstage='#e8f8c2'; }
            else { valstage='#e2f8f2'; }
            if (u.pjtcn_quantity == u.cant_ser) {
                icon = 'fas fa-regular fa-thumbs-up';
            } else{
                icon ='fas fa-edit';
            }
            //****** */
            // let skufull = u.pjtcn_prod_sku.slice(7, 11) == '' ? u.pjtcn_prod_sku.slice(0, 7) : u.pjtcn_prod_sku.slice(0, 7) + '-' + u.pjtcn_prod_sku.slice(7, 11);
            let skufull = u.pjtcn_prod_sku;
            // 11-10-23
            var rownode=tabla.row
                .add({
                    editable: `<i class="${icon} toLink" id="${u.pjtcn_id}"></i>`,
                    pack_sku: skufull,
                    packname: u.pjtcn_prod_name,
                    packcount: u.pjtcn_quantity,
                    packlevel: u.pjtcn_prod_level,
                    packEquip: u.section,
                    packstatus: valcontent,
                })
                .draw().node();
            $(rownode).css("background-color", valstage);
                
            //$(rownode).find('td').eq(0).find('.toLink').css("color", color);
            $(`#SKU-${u.pjtcn_prod_sku}`).parent('tr').attr('id', u.pjtcn_id).addClass('indicator');
        });
        //********** */
        activeIcons();
    }
}
// ### LISTO ###   habilita el botones para validar en TABLA INICIAL

function activeIcons() {
    $('.toLink')
        .unbind('click')
        .on('click', function () {
            //let selected = $(this).parent().attr('id');
            let pjtcnid = $(this).attr('id');
            // console.log('Click Nivel 2', pjtcnid);
            if (pjtcnid > 0) {
                glbcnid=pjtcnid;
                getSeries(pjtcnid);
            }
        });
}


//**************  NIVEL 2 DE DATOS  *****************************************
function putSeries(dt) {
    // console.log('putSeries');
    settingSeries();
    build_modal_serie(dt);
}

function putCreateTbl(dt) {
    let result=dt;
    // console.log('putCreateTbl',result);
}
function settingSeries(){
    $('#SerieModal').removeClass('overlay_hide');
    $('#tblSerie').DataTable({
        // retrieve: true,
        bDestroy: true,
        dom: 'Blfrtip',
        order: [[1, 'asc']],
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
                },
            },
            /* {
                // Boton imprimir detalle jjr
                text: ' Print Detalle ',
                className: 'btn-apply',
                action: function (e, dt, node, config) {
                    printDetail(prjid);;
                },
            }, */
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
            /* {data: 'sername', class: 'supply left'}, */
            {data: 'sernumber', class: 'sku'},
            {data: 'sereconum', class: 'sku'},
            {data: 'sertype', class: 'sku'},
            
        ],
    });

    $('#SerieModal .btn_close')
        .unbind('click')
        .on('click', function () {
            //console.log('Cierra Series');
            $('.overlay_background').addClass('overlay_hide');
            $('.overlay_closer .title').html('');
            $('#tblSerie').DataTable().destroy;
    });
   
}

// ### LISTO ### Llena con datos de series la tabla del modal --- MODAL 1
function build_modal_serie(dt) {
        //  console.log('build_modal_serie',dt);
        let valstage='';
        let valmant='';
        let tabla = $('#tblSerie').DataTable();
        // $('#tblSerie').DataTable();
        $('.overlay_closer .title').html(`ASIGNADAS: ${dt[0].prd_name}`);
         tabla.rows().remove().draw();
        //  $('#tblSerie tbody').html('');
         $.each(dt, function (v, u){
             let skufull = u.pjtdt_prod_sku;
             let sku = u.pjtdt_prod_sku.slice(0, 7);
             if (u.ser_situation=='D'){
                valstage='color:#CC0000';
                valmant='color:#3c5777';
             } else if(u.ser_situation=='M'){
                valstage='color:#3c5777';
                valmant='color:#CC0000';
             } else {
                valstage='color:#3c5777';
                valmant='color:#3c5777';
             }
             
             tabla.row
                 .add({
                     sermodif: `<i class="fas fa-wrench toChange" data-content="${skufull}|${u.pjtdt_id}|${u.ser_id}" style="${valmant}"></i> 
                                <i class="fas fa-check-circle toAcept" id="${u.ser_id}" data-content="${skufull}|${u.pjtdt_id}|${u.ser_id}" style="${valstage}"></i>`,
                     seriesku: skufull,
                     sernumber: u.ser_serial_number,
                     sereconum: u.ser_serial_number,
                     sertype: u.prd_level,
                 })
                 .draw();
             $(`#${u.ser_id}`).parents('tr').attr('id', u.ser_id);
         });
         activeIconsSer();
}

/** ### LISTO ### +++++  Activa los iconos del modal de serie */
function activeIconsSer() {
    $('.toChange')
        .unbind('click')
        .on('click', function () {
            let serprd = $(this).attr('data-content').split('|')[0];
            let serorg = $(this).attr('data-content').split('|')[1];
            let serId = $(this).attr('data-content').split('|')[2];
            // console.log('Click Nivel-3', serprd, serorg, serId);
            if (serprd != "") {
                // getReason(serId);
                openReason(serId);
            }
    });
    
    $('.toAcept')
        .unbind('click')
        .on('click', function () {
            let serprd = $(this).attr('data-content').split('|')[0];
            let serorg = $(this).attr('data-content').split('|')[1];
            let serId = $(this).attr('data-content').split('|')[2];
            console.log("Para aceptar: ",serprd, serorg, serId);
            checkSerie(serId, serprd);

            let tabla = $('#tblSerie').DataTable();
            let numRows = tabla.rows().count();
            aux++;
            if (numRows==1 || aux==numRows) {
                $('.overlay_background').addClass('overlay_hide');
                $('.overlay_closer .title').html('');
                $('#tblSerie').DataTable().destroy;
                aux=0;
            }else{
                console.log(numRows, aux);
            }
        });
}

function checkSerie(serId, serSku) {
    //console.log('ID-Producto-Check', serId);
    var pagina = 'WorkInputContent/checkSeries';
    var par = `[{"serId":"${serId}", "serSku":"${serSku}", "prjid":${prjid}}]`;
    var tipo = 'html';
    var selector = myCheck; 
    fillField(pagina, par, tipo, selector);
}

function myCheck(dt){
    console.log('myCheck', dt);
    $('#'+dt).css({"color":"#CC0000"});
    $('#'+dt).find('.toAcept').css({"color":"#CC0000"});
    $('#'+dt).children(".claseElemento").css({"color":"#CC0000"});
    getDetailProds(prjid,em);
}

function readAceptTable() {
    $('#tblSerie tbody tr').each(function (v, u) {
        
        // console.log("DENTRO EACH: ", $(this).find('td')[0].children);
        let serId = $(this).attr('id');
        let serdata = $($(this).find('td')[1]).text();
        console.log("readAceptTable: ", serId, serdata);
        checkSerie(serId, serdata);
        setTimeout(function(){
            console.log('');
        }, 3000);
        /* $('.overlay_background').addClass('overlay_hide');
        $('.overlay_closer .title').html('');
        $('#tblSerie').DataTable().destroy; */
    });
}

// ### LISTO ### Llena prepara la table dentro del modal para series ### LISTO -- MODAL 1###
function putReason(dt) {
    settingReason();
    build_modalReason(dt);
}

function settingReason(){
        // console.log('Setting-Series');
        // $('#ReasonMtModal').removeClass('overlay_hide');
        $('#tblMaintenance').DataTable({
            bDestroy: true,
            order: [[1, 'asc']],
            lengthMenu: [
                [100, 150, 200, -1],
                [100, 150, 200, 'Todos'],
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
                {data: 'seriesku', class: 'supply'},
                {data: 'sername', class: 'supply'},
            ],
        } );

        $('#ReasonMtModal .btn_close')
        .unbind('click')
        .on('click', function () {
            // console.log('Cierra Motivos');
            $('.overlay_background').addClass('overlay_hide');
            $('.overlay_closer .title').html('');
            $('#tblMaintenance').remove;
            $('#tblMaintenance').DataTable().destroy;
            
        });
        activeIcons();
}
// ### LISTO ### Llena con datos de series la tabla del modal --- MODAL 1
function build_modalReason(dt) {
        // console.log('Nivel 2', dt);
        $('#ReasonMtModal .overlay_closer .title').html('LISTADO DE MOTIVOS:');
        let tabla = $('#tblMaintenance').DataTable();
        // tabla.rows().remove().draw();
        $.each(dt, function (v, u) {
            tabla.row
                .add({
                    sermodif: `<i class='fas fa-check-circle toCheck' id="${u.pjtcr_id}" data="${u.pjtcr_definition}|${u.pjtcr_code_stage}"></i>`,
                    seriesku: u.pjtcr_definition,
                    sername: u.pjtcr_description,
                })
                .draw();
            //$(`#E${u.pjtcn_id}`).parents('tr').attr('data-product', u.pjtcn_id);
        });
        // activeIconsReason();
}

function openReason(serId){
    $('#ReasonMtModal').removeClass('overlay_hide');
    $('#ReasonMtModal .overlay_closer .title').html('LISTADO DE MOTIVOS:');
    let tabla = $('#tblMaintenance').DataTable().draw();
    activeIconsReason(serId);
}

/** ### LISTO ### +++++  Activa los iconos del modal de serie */
function activeIconsReason(serId) {
    // console.log('ACTIVA MOTIVOS DE MANTENIMIENTO');
    $('.toCheck')
        .unbind('click')
        .on('click', function () {
        let codmot = $(this).attr('id');
        let motdesc = $(this).attr('data').split('|')[0];
        let codstag = $(this).attr('data').split('|')[1];
        console.log('Cierra Motivo seleccionado',codmot ,motdesc,'TR',glbcnid, 'SER-ID',serId);
        regMaintenance(serId,codmot,codstag);

        let el = $(`#tblAsigInput tr[id="${glbcnid}"]`);
        $(el.find('td')[5]).html(motdesc);
        $('.overlay_background').addClass('overlay_hide');
        $('.overlay_closer .title').html('');
        $('#tblMaintenance').DataTable().destroy;
    
    });
}

function regMaintenance(serId,codmot,codstag) {
    var par = `[  {"serId":     "${serId}"},
                  {"codmot":    "${codmot}"},
                  {"codstag":   "${codstag}"},
                  {"prjid":     "${prjid}"}
               ]`;
    // console.log('regMaintenance', serId,codmot,codstag,prjid);
    var pagina = 'WorkInputContent/regMaintenance';
    // var par = `[{"serId":"${serId}"},{"codmot":"${codmot}"},{"codstag":"${codstag}"}]`;
    var tipo = 'html';
    var selector = myCheck; 
    fillField(pagina, par, tipo, selector);
}

/**********  Confirma salida de equipos ***********/    
function confirm_to_GetOut(pjtid) {
    $('#starClosure').modal('show');
    $('#txtIdClosure').val(pjtid);

    $('#btnClosure').on('click', function () {
        $('#starClosure').modal('hide');
        // console.log('Datos CLICK',pjtid,glbprjnum);
         modalLoading('S');

         createTblRespaldo(prjid,glbprjnum);

        var pagina = 'WorkInputContent/RegisterGetIn';
        var par = `[{"pjtid":"${pjtid}"}]`;
        var tipo = 'json';
        var selector = putToWork;
        fillField(pagina, par, tipo, selector); 

    });
}

function putToWork(dt){
    // console.log('TERMINO ACTUALIZAR', dt);
    // console.log('Regreso', folio);
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

/**********  Impresion del contenido de un proyecto ***********/    
function printReports(pjtId, typrint) {
    // let user = Cookies.get('user').split('|');
    // let u = user[0];
    // let n = user[2];
    
    let v = pjtId;
    let h = localStorage.getItem('host');
    let nameproject = $('#txtProjectName').val();
    let numproject = $('#txtProjectNum').val();
    // console.log('Datos', v, u, n, h);
    switch (typrint) {
        case "C":  // Contenido global
            window.open(
                `${url}app/views/WhOutputContent/WhOutputContentReport.php?v=${v}&u=${u}&n=${n}&em=${em}&h=${h}&np=${nameproject}&nump=${numproject}`,
                '_blank'
            );
        //   console.log("Contenido");
          break;
        case "D":  // Detalles del contenido con series
            window.open(
                `${url}app/views/WhOutputContent/WhOutputDetailReport.php?v=${v}&u=${u}&n=${n}&em=${em}&h=${h}&np=${nameproject}&nump=${numproject}`,
                '_blank'
            );
        //   console.log("Detalle");
          break;
        case "A": // Detalles y Accesorios del contenido con series
            window.open(
                `${url}app/views/WhOutputContent/WhOutputAccesoryReport.php?v=${v}&u=${u}&n=${n}&em=${em}&h=${h}&np=${nameproject}&nump=${numproject}`,
                '_blank'
            );
        //   console.log("Con Accesorios");
          break;
        default:
          console.log("No Hay REPORTE");
          break;
      }


    // window.open(
    //     `${url}app/views/WhOutputContent/WhOutputContentReport.php?v=${v}&u=${u}&n=${n}&h=${h}`,
    //     '_blank'
    // );
}


