let pjtgbl,verIdgbl,cusgbl,prdCmgbl;

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
       
        getProjects();
        widthTable(tblprod);
        listChgStatus();

        $('#GuardarClosure').on('click', function () {
            let locID = $(this);
            let pjtid = locID.parents('tr').attr('id');
            //console.log('Paso ToWork..', pjtid);
            confirm_to_Closure(pjtid);
           
         });
      
         $('#PrintClosure').on('click', function () {
            /* LimpiaModal();
            getTipoProveedor();
            getOptionYesNo(); */
         });
         

    } else {
        setTimeout(() => {
            inicial();
        }, 100);
    }
}
function showModalComments() {
    let template = $('#commentsTemplates');
    // let pjtId = $('.version_current').attr('data-project');

    $('.invoice__modalBackgound').fadeIn('slow');
    $('.invoice__modal-general').slideDown('slow').css({ 'z-index': 401 });
    $('.invoice__modal-general .modal__body').append(template.html());
    $('.invoice__modal-general .modal__header-concept').html('Comentarios');
    closeModals();
    /* $('.comments__addNew .invoiceInput').val('COMENTARIO PRUEBA XXX');

    console.log( $('#txtComment').val()); */
    //console.log(prjid);
    fillComments(pjtgbl);
}

function fillComments(pjtId) {
    console.log(pjtId);
    
    // Agrega nuevo comentario
    /* $('.comments__addNew .invoice_button')
        .unbind('click')
        .on('click', function () {
            
            //let pjtId = $('.version_current').attr('data-project');

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
        }); */

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
            console.log(u);
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
    console.log(u.com_comment);
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
function getProjects() {
    let data = [{  pjtId: '', }, ];

    var pagina = 'ProjectClosed/listProjects';
    var par = JSON.stringify(data);
    var tipo = 'JSON';
    var selector = putProjects;
    fillField(pagina, par, tipo, selector);
}

function putProjects(dt) {
    
    $.each(dt, function (v, u) {
        let H = `<option value="${u.pjt_id}|${u.cus_id}">${u.pjt_name}</option>`;
        pjs.append(H);
    });
    $('#txtExpendab').val('0.00');

    pjs.unbind('change').on('change', function () {
        deep_loading('P');
        // console.log('VAL-', $(this).val());
        let pjtId = $(this).val().split('|')[0];
        let cusId = $(this).val().split('|')[1];
        pjtgbl=pjtId;
        cusgbl=cusId;
        // console.log('Variables',pjtgbl, cusgbl);
        $('#txtExpendab').html('0.00');
        activeProjectsFunctions(pjtId);
    });
}

function activeProjectsFunctions(pjtId) {
    findExpenda(pjtId);
    getTotalMantenance(pjtId);
    findExtraDiesel(pjtId);
    findDiscount(pjtId);
    activaCampos(pjtId);
    $('.sidebar__comments .toComment')
    .unbind('click')
    .on('click', function () {
        showModalComments();
        
    });
    setTimeout(() => {
        updateTotals();
    }, 1000);
    deep_loading('C');
}


function activaCampos(pjtId) {
    $('.list-finder').removeClass('hide-items');
    getProjectContent(pjtId);
}

function getProjectContent(pjtId) {
    let data = [
        {pjtId: pjtId, },
        ];

    var pagina = 'ProjectClosed/projectContent';
    var par = JSON.stringify(data);
    var tipo = 'JSON';
    var selector = putProjectContent;
    fillField(pagina, par, tipo, selector);
}

function putProjectContent(dt) {
    console.log(dt);
    if (dt[0].pjtdt_id!=''){
       
        tblprod.find('tbody').html('');
        verIdgbl=dt[0].ver_id;
        $.each(dt, function (v, u) {
            let costins=parseFloat(u.costo) + parseFloat(u.seguro);
            // console.log(costins);
            let H = `<tr id=${u.prd_id}, iname="${u.pjtcn_prod_name}">
                        <td class="cn"><i class='fas fa-pen modif'></i></td>
                        <td class="lf">${u.pjtdt_prod_sku}</td>
                        <td class="lf">${u.pjtcn_prod_name}</td>
                        <td class="cn">1</td>
                        <td class="cn">${u.ser_situation}</td>
                        <td class="rg">${fnm(costins, 2, '.', ',')}</td>
                        <td class="lf">${u.ser_comments}</td>
                    </tr>`;
            tblprod.append(H);
        });
    }    
    widthTable(tblprod);

    let tot = dt.reduce((tt, pc) => tt + parseFloat(pc.costo) + parseFloat(pc.seguro), 0);
    let liva=tot*(0.16)+parseFloat(tot);
    tot=liva;
    totprj.html(fnm(tot, 2, '.', ','));
    // console.log(tot);
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
            console.log('Click Iconos',prdId, Lname);
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

function getTotalMantenance(pjtId){
    var pagina = 'ProjectClosed/totalMantenimiento';
    var par = `[{"pjtId":"${pjtId}"}]`;
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
    let total = parseFloat(totprj.html().replace(/,/g, ''));

    total += parseFloat(totexp.html().replace(/,/g, ''));
    total += parseFloat(totman.html().replace(/,/g, ''));
    total += parseFloat(totdie.html().replace(/,/g, ''));
    total -= parseFloat(totdis.html().replace(/,/g, ''));

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
            "verid" : "${verid}"
        }] `;
    console.log('Save-',par);
    var pagina = 'ProjectClosed/saveDocumentClosure';
    var tipo = 'html';
    var selector = resSaveClosure;
    fillField(pagina, par, tipo, selector);
}

function resSaveClosure(dt) {
    console.log('resSaveClosure',dt);
    setTimeout(() => {
        window.location.reload();
    }, 1000);
    
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


function resEdtProduct(dt) {
    $('#txtCinId').val('');
    $('#ProductModal .btn_close').trigger('click');
    activeIcons();
}
