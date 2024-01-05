let pjtgbl,verIdgbl,cusgbl,prdCmgbl, concept;
let em, u, n,user,usrid;

const pjs = $('#txtProjects');
const exp = $('#txtExpendab');
const man = $('#txtMaintenance');
const dis = $('#txtDiscount');
const com = $('#txtComments');
const extd = $('#txtDiesel');

const tblprod = $('#tblProducts');
const totprj = $('#totProject');
const totexp = $('#totExpendab');
const totman = $('#totMaintenance');
const totdie = $('#totDiesel');
const totdis = $('#totDiscount');
const totdoc = $('#totals');
const totals = $('#totals');
const totprjIva = $('#totProjectIva');


const totBase = $('#totBase');
const totExtra = $('#totExtra');
const totDias = $('#totDias');
const totSubarrendo = $('#totSubarrendo');
const totPrepago = $('#totPrepago');

let monto = 0.16;

const size = [
    { s: 20 },
    { s: 100 },
    { s: 400 },
    { s: 70 },
    { s: 70 },
    { s: 90 },
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
        
        user = Cookies.get('user').split('|');
        usrid = user[0];
        n = user[2];
        em = user[3];
        widthTable(tblprod);
        listChgStatus();

        $('#GuardarClosure').on('click', function () {
            let locID = $(this);
            let pjtid = locID.parents('tr').attr('id');
            //console.log('Paso ToWork..', pjtid);
            confirm_to_Closure(pjtid);
           
         });
      
         $('#PrintClosure').on('click', function () {
            let pjt= $('#txtProjects').val();
            let typeDes = $('#txtReport').val();
            let pjtid = pjt.split('|')[0];
            let prjType;

            if ($('#RadioConceptos1').prop('checked')) {
                prjType = 1;
            } else {
                prjType = 2;
            }
            printReport(pjtid,prjType, typeDes);
           
         });
         if ($('#RadioConceptos1').prop('checked')) {
            concept = '8,9';
            getProjects(concept);
        } 

         $('#RadioConceptos1')
         .unbind('change')
         .on('change', function () {
            if ($('#RadioConceptos1').prop('checked')) {
                concept = '8,9';
            } else {
                concept = 0;
            }
            getProjects(concept);
            
             clean();
         });
         $('#RadioConceptos2')
         .unbind('change')
         .on('change', function () {
            if ($('#RadioConceptos2').prop('checked')) {
                concept = '40';
            } else {
                concept = 0;
            }
            getProjects(concept);
            
            clean();
         });
        
         
         
    } else {
        setTimeout(() => {
            inicial();
        }, 100);
    }
}

function showModalComments() {
    let template = $('#commentsTemplates');

    $('.invoice__modalBackgound').fadeIn('slow');
    $('.invoice__modal-general').slideDown('slow').css({ 'z-index': 401 });
    $('.invoice__modal-general .modal__body').append(template.html());
    $('.invoice__modal-general .modal__header-concept').html('Comentarios');
    closeModals();
    
    fillComments(pjtgbl);
}

function fillComments(pjtId) {
    
    getComments(pjtId);
}

function getComments(pjtId) {
    var pagina = 'ProjectClosed/listComments';
    var par = `[{"pjId":"${pjtId}"}]`;
    var tipo = 'json';
    var selector = putComments;
    fillField(pagina, par, tipo, selector);
}

function putComments(dt) {
    $('.comments__list').html('');
    if (dt[0].com_id > 0) {
        $.each(dt, function (v, u) {
            fillCommnetElements(u);
        });
    }
    
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
        
    });
}

function fillCommnetElements(u) {
    let H = `
        <div class="comment__group" style="border-bottom: 1px solid var(--br-gray-soft); padding: 0.2rem; width: 100%;">
            <div class="comment__box comment__box-date" style="width: 100%;text-align: right; font-size: 0.9em; color: var(--in-oxford);"><i class="far fa-clock" style="padding: 0 0.5rem;"></i>${u.com_date}</div>
            <div class="comment__box comment__box-text">${u.com_comment}</div>
            <div class="comment__box comment__box-user" style="text-align: left; font-size: 0.9em; color: var(--in-oxford);">${u.com_user}</div>
        </div>
    `;

    $('.comments__list').prepend(H);
    //getComments_text(prjid);
}

function listChgStatus() {
    var pagina = 'ProjectClosed/listChgStatus';
    var par = '[{"parm":""}]';
    var tipo = 'json';
    var selector = putChgStatus;
    fillField(pagina, par, tipo, selector);
}

// Obtiene el listado de los proyectos en etapa de pryecto
function getProjects(concept) {
    //let data = [{  pjtId: ${concept}', }, ];

    var pagina = 'ProjectClosed/listProjects';
    var par = `[{"pjtId":"${concept}"}]`;
    var tipo = 'JSON';
    var selector = putProjects;
    fillField(pagina, par, tipo, selector);
}

function putProjects(dt) {
    pjs.html('');
    pjs.append('<option value="0">Selecciona el proyecto</option>');
    if (dt[0].pjt_id > 0) {
        $.each(dt, function (v, u) {
            let H = `<option value="${u.pjt_id}|${u.cus_id}">${u.pjt_name}</option>`;
            pjs.append(H);
        });
    }
    
    $('#txtExpendab').val('0.00');

    pjs.unbind('change').on('change', function () {
        deep_loading('O');
        
        // console.log('VAL-', $(this).val());
        let pjtId = $(this).val().split('|')[0];
        let cusId = $(this).val().split('|')[1];
        pjtgbl=pjtId;
        cusgbl=cusId;
        $('#txtReport').val(1);
        // console.log('Variables',pjtgbl, cusgbl);
        $('#txtExpendab').html('0.00');
        let radio =  $('#RadioConceptos1').prop('checked');
        let type = radio == true ? 1 : 2;
        console.log('type: ', type,'pjt', pjtId);
        activeProjectsFunctions(pjtId, type);

    });
    
}

function activeProjectsFunctions(pjtId, type) {
    
    findExpenda(pjtId);
    findExtraDiesel(pjtId);
    findDiscount(pjtId);
    if (pjtId > 0 ) {
        getTotalMantenance(pjtId,type);
        getTotalPrepago(pjtId,type);
        activaCampos(pjtId, 1, type);
        getTotalEquipo(pjtId,'1', type);
        getTotalEquipo(pjtId,'2', type);
        getTotalEquipo(pjtId,'3', type);
        getTotalEquipo(pjtId,'4', type);
        getTotalesProyecto(pjtId, type);
    }else{
       clean();
    }
    

    $('.sidebar__comments .toComment')
    .unbind('click')
    .on('click', function () {
        showModalComments();
        
    });
    $('#txtReport')
         .unbind('change')
         .on('change', function () {
            let prjType;
            if ($('#RadioConceptos1').prop('checked')) {
                prjType = 1;
            } 
            if ($('#RadioConceptos2').prop('checked')) {
                prjType = 2;
            } 
            
            getProjectContent(pjtId, $('#txtReport').val(), prjType);
         });
    $('#txtIva')
         .unbind('change')
         .on('change', function () {
            changeIva($('#txtIva').val());
         });
    /* setTimeout(() => {
        updateTotals();
    }, 1000); */
    deep_loading('C');
}

function changeIva(iva){
    let liva= (parseFloat(monto * iva) + parseFloat(monto));
    totprjIva.html(fnm(liva, 2, '.', ','));
    updateTotals();
}
function activaCampos(pjtId, type, prjType) {
    $('.list-finder').removeClass('hide-items');
    
    getProjectContent(pjtId, type, prjType);
}

function getProjectContent(pjtId, type, prjType) {
    let data = [
        {pjtId: pjtId, type: type, prjType: prjType},
        ];

    var pagina = 'ProjectClosed/projectContent';
    var par = JSON.stringify(data);
    var tipo = 'JSON';
    var selector = putProjectContent;
    fillField(pagina, par, tipo, selector);
}

function putProjectContent(dt) {
    if (dt[0].pjtdt_id > 0){
       
        tblprod.find('tbody').html('');
        verIdgbl=dt[0].ver_id;
        $.each(dt, function (v, u) {
            let costins=parseFloat(u.costo) + parseFloat(u.seguro);
            // console.log(costins);
            let H = `<tr id=${u.prd_id}, iname="${u.pjtcn_prod_name}">
                        <td class="cn"><i class='fas fa-pen modif'></i></td>
                        <td class="lf">${u.prd_sku}</td>
                        <td class="lf">${u.pjtcn_prod_name}</td>
                        <td class="cn">${u.quantity}</td>
                        <td class="cn">${u.ser_situation}</td>
                        <td class="cn">${fnm(costins, 2, '.', ',')}</td>
                        <td class="rg">${u.pjt_name}</td>
                        <td class="lf">${u.ser_comments}</td>
                    </tr>`;
            tblprod.append(H);
        });
    }    
    widthTable(tblprod);

    activeIcons();
}

function activeIcons() {
    // console.log('Activa Iconos');
    $('.modif')
        .unbind('click')
        .on('click', function () {
            console.log('Click Iconos');
            let sltor = $(this);
            let prdId = sltor.parents('tr').attr('id'); 
            let Lname = sltor.parents('tr').attr('iname');
            let prdNm = 'Anexo de comentarios a: ' + Lname ; //+ '-' + Lname
            // $('#txtPrdName').val(Lname);

            $('#ProductModal').removeClass('overlay_hide');
            $('.overlay_closer .title').html(prdNm);
            putSelectProduct(prdId);

            $('#ProductModal .btn_close')
                .unbind('click')
                .on('click', function () {
                    $('.overlay_background').addClass('overlay_hide');
                });
        });

}

function findExpenda(pjtId) {
    let data = [
        { pjtId: pjtId, },
    ];
    var pagina = 'ProjectClosed/saleExpendab';
    var par = JSON.stringify(data);
    var tipo = 'JSON';
    var selector = putSaleExpendab;
    fillField(pagina, par, tipo, selector);
}

function putSaleExpendab(dt) {
    let cfr = dt[0].expendables;
    exp.val(fnm(dt[0].expendables, 2, '.', ','));
    totexp.html(fnm(cfr, 2, '.', ','));
    exp.unbind('keyup').on('keyup', function () {
        let val = $(this).val();
        if (val == '') {
            val = cfr;
        }
        totexp.html(fnm(val, 2, '.', ','));
        updateTotals();
    });
}

function getTotalMantenance(pjtId, type){
    var pagina = 'ProjectClosed/totalMantenimiento';
    var par = `[{"pjtId":"${pjtId}", "type":"${type}"}]`;
    var tipo = 'json';
    var selector = putTotalMaintenance;
    fillField(pagina, par, tipo, selector); 
}

function putTotalMaintenance(dt){
    // console.log(dt);
    let cfr = 0;
    man.val(fnm(dt[0].maintenance, 2, '.', ','));
    totman.html(fnm(dt[0].maintenance, 2, '.', ','));

    man.unbind('keyup').on('keyup', function () {
        let val = $(this).val();
        if (val == '') {
            val = cfr;
        }
        totman.html(fnm(val, 2, '.', ','));
        updateTotals();
    }); 
}

// Obtener los datos totales para Equipo Base, Extra, Dias, Subarrendo
function getTotalEquipo(pjtId, equipo, type){
    var pagina = 'ProjectClosed/totalEquipo';
    var par = `[{"pjtId":"${pjtId}", "equipo":"${equipo}", "type":"${type}"}]`;
    var tipo = 'json';
    console.log(par);
    var selector = putTotalEquipo;
    fillField(pagina, par, tipo, selector); 
}

// getTotalesProyecto
// Obtener los datos totales para el proyecto
function getTotalesProyecto(pjtId, type){
    var pagina = 'ProjectClosed/totalesProyecto';
    var par = `[{"pjtId":"${pjtId}", "type":"${type}"}]`;
    var tipo = 'json';
    var selector = putTotalesProyecto;
    fillField(pagina, par, tipo, selector); 
}
function putTotalEquipo(dt){
    let cfr = 0;
    if (dt[0].section == '1') {
        totBase.html(fnm(dt[0].monto, 2, '.', ','));

    }
    if (dt[0].section == '2') {
        totExtra.html(fnm(dt[0].monto, 2, '.', ','));

    }
    if (dt[0].section == '3') {
        totDias.html(fnm(dt[0].monto, 2, '.', ','));

    }
    if (dt[0].section == '4') {
        totSubarrendo.html(fnm(dt[0].monto, 2, '.', ','));

    }
    
}

function putTotalesProyecto(dt){
    let iva = parseFloat($('#txtIva').val());
    let liva=dt[0].monto * iva + parseFloat(dt[0].monto);
    totprj.html(fnm(dt[0].monto, 2, '.', ','));
    totprjIva.html(fnm(liva, 2, '.', ','));
    monto = dt[0].monto;
    updateTotals();
}

// OBTENER LOS TOTALES POR PREPAGOS DENTRO DEL PROYECTO
function getTotalPrepago(pjtId, type){
    var pagina = 'ProjectClosed/totalPrepago';
    var par = `[{"pjtId":"${pjtId}", "type":"${type}"}]`;
    var tipo = 'json';
    var selector = putTotalPrepago;
    fillField(pagina, par, tipo, selector); 
}

function putTotalPrepago(dt){
    totPrepago.html(fnm(dt[0].prp_amount, 2, '.', ','));
    
}
function findDiscount(pjtId) {
    let cfr = 0;
    dis.val("0.00", 2, '.', ',');
    dis.unbind('keyup').on('keyup', function () {
        let val = $(this).val();
        if (val == '') {
            val = cfr;
        }
        totdis.html(fnm(val, 2, '.', ','));
        updateTotals();
    });
}

function findExtraDiesel(pjtId) {
    let cfr = 0;
    extd.val("0.00", 2, '.', ',');
    extd.unbind('keyup').on('keyup', function () {
        let val = $(this).val();
        if (val == '') {
            val = cfr;
        }
        totdie.html(fnm(val, 2, '.', ','));
        updateTotals();
    });
}

function updateTotals() {
    let total = parseFloat(totprjIva.html().replace(/,/g, ''));

    total += parseFloat(totexp.html().replace(/,/g, ''));
    total += parseFloat(totman.html().replace(/,/g, ''));
    total += parseFloat(totdie.html().replace(/,/g, ''));
    total -= parseFloat(totdis.html().replace(/,/g, ''));
    total -= parseFloat(totPrepago.html().replace(/,/g, ''));

    totals.html(fnm(total, 2, '.', ','));
}

function widthTable(tbl) {
    $.each(size, (i, v) => {
        let thcel = tbl.find('thead tr').children('th').eq(i);
        let tdcel = tbl.find('tbody tr').children('td').eq(i);
        // console.log('tdcel-',tdcel);
        thcel.css({ width: v.s + 'px' });
        tdcel.css({ width: v.s + 'px' });
    });

    let wdt = size.reduce((acc, sz) => acc + sz.s, 0);
    tbl.css({ width: wdt + 'px' });
    tbl.sticky({ top: 'thead tr:first-child' });
}

function saveDocumentClosure() {
    let user = Cookies.get('user').split('|');
    let u = user[0];
    let n = user[2];
   
    parseFloat(totprj.html().replace(/,/g, ''));
    let cloTotProy = parseFloat(totprj.html().replace(/,/g, ''));
    let cloTotMaint =parseFloat(totman.html().replace(/,/g, ''));
    let cloTotExpen = parseFloat(totexp.html().replace(/,/g, ''));
    let cloTotCombu =parseFloat(totdie.html().replace(/,/g, ''));
    let cloTotDisco = parseFloat(totdis.html().replace(/,/g, ''));
    let cloTotDocum = parseFloat(totals.html().replace(/,/g, ''));
    let cloCommen = $(`#txtComments`).val();
    let pjtId = pjtgbl;
    let usrid = u;
    let verid = verIdgbl;
    let cusId = cusgbl;
    let type;

    if ($('#RadioConceptos1').prop('checked')) {
        type = 1;
    } else {
        type = 2;
    }

    var par = `
        [{  "cloTotProy" : "${cloTotProy}",
            "cloTotMaint" : "${cloTotMaint}",
            "cloTotExpen" : "${cloTotExpen}",
            "cloTotCombu" : "${cloTotCombu}",
            "cloTotDisco" : "${cloTotDisco}",
            "cloTotDocum" : "${cloTotDocum}",
            "cloCommen" : " ${cloCommen}",
            "cusId" :   "${cusId}",
            "pjtid" : "${pjtId}",
            "usrid" : "${usrid}",
            "verid" : "${verid}",
            "type" : "${type}"
        }] `;
    var pagina = 'ProjectClosed/saveDocumentClosure';
    var tipo = 'html';
    var selector = resSaveClosure;
    fillField(pagina, par, tipo, selector);
}

function resSaveClosure(dt) {
    setTimeout(() => {
        window.location.reload();
    }, 1000);
    
}

function clean(){
    findExpenda(0);
    $('#txtDiscount').val('');
    $('#txtDiesel').val('');
    $('#txtComments').val('');
    $('#txtReport').val('1');
    $('#txtIva').val(0.16); 
    tblprod.find('tbody').html('');

    getTotalMantenance(0, 1);
    activaCampos(0, 1, 1);
    getTotalEquipo(0,'1', 1);
    getTotalEquipo(0,'2', 1);
    getTotalEquipo(0,'3', 1);
    getTotalEquipo(0,'4', 1);
    getTotalesProyecto(0, 1); 
    getTotalPrepago(0,1);
    $('#totDiesel').html("0.00");
    $('#totDiscount').html("0.00");
    $('#totals').html("0.00");
    $('#totPrepago').html("0.00");
}
function confirm_to_Closure(pjtid) {
    $('#starClosure').modal('show');
    $('#txtIdClosure').val(pjtid);
    $('#btnClosure').on('click', function () {
    $('#starClosure').modal('hide');
    saveDocumentClosure();
    });
}

function putChgStatus(dt) {
    if (dt[0].cin_id != '0') {
        let cinId = dt[0].cin_id;
        $.each(dt, function (v, u) {
            var H = `<option value="${u.pjtcr_id}">${u.pjtcr_definition}-${u.pjtcr_description}</option>`;
            $('#txtCinId').append(H);
        });
    }
}

function putSelectProduct(prdId) {
    // listChgStatus();

    $('#btn_save')
        .unbind('click')
        .on('click', function () {
        // PASA LOS VALORES DEL COMENTARIO A LA TABLA PRINCIPAL
            let prdCmgbl = $('#txtCommentPrd').val().toUpperCase();
            let tbl = $(`#tblProducts tr[id="${prdId}"]`);
            $(tbl.find('td')[6]).text(prdCmgbl);
            $('#ProductModal .btn_close').trigger('click');
        //LIMPIA EL CAMPO DEL MODAL
            $('#txtCommentPrd').val('');
            // saveEditProduct(prdId);
        });
}
function printReport(verId, prjType, typeDes) {
    // let user = Cookies.get('user').split('|');
    // let u = user[0];
    // let n = user[2];
    console.log(verId);
    let h = localStorage.getItem('host');
    let v = verId;
    // console.log('Datos', v, u, n, h);
    window.open(
        `${url}app/views/ProjectClosed/ProjectClosedReport.php?v=${v}&u=${u}&n=${n}&h=${h}&em=${em}&t=${prjType}&d=${typeDes}`,
        '_blank'
    );
}

function resEdtProduct(dt) {
    $('#txtCinId').val('');
    $('#ProductModal .btn_close').trigger('click');
    activeIcons();
}
