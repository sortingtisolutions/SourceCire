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
        // get_stores();
        get_projects();
        fill_dinamic_table();
        
        // $('#lstPayForm')
        //     .unbind('change')
        //     .on('change', function () {
        //         var tp = $(this).val();
        //         if (tp == 'TARJETA DE CREDITO') {
        //             $('#txtInvoice').addClass('required').parents('div.form_group').removeClass('hide');
        //         } else {
        //             $('#txtInvoice').removeClass('required').parents('div.form_group').addClass('hide');
        //         }
        //     });

        // $('#addPurchase').on('click', function () {
        //     saleApply();
        // });

        // $('#newQuote').on('click', function () {
        //     window.location = 'ProductsSalables';
        // });
        // $('#newComment').on('click', function () {
        //     addComments('sales', '');
        // });

        $('.required').on('focus', function () {
            $(this).removeClass('forValid');
            $(this).parent().children('.novalid').remove();
        });
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

    } else {
        $('#lstProject').html('');
    }

    $('#lstProject')
        .unbind('change')
        .on('change', function () {
            fill_dinamic_table();
            // console.log('Change Proj',$(this).val());
            // var ix = $(this).val();
            // let lpjt =  $(this).attr('data');

            let lpjt = $(this).val();
            let glbpjtid=lpjt;
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
        
    } else {
        $('#lstProject').html('');
    }

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

/**  +++++   Arma el escenario de la cotizacion  */
function fill_dinamic_table() {
    caching_events('fill_dinamic_table');
    let H = `
        <table class="table_control" id="tblControl" style="width: 1000px;">
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
    // add_boton();
    
    // fill_purchase(prod[inx], inx);
}


/**  +++++ Guarda el producto en la cotización +++++ */
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
        // updateTotals();
        let locid = $(this).parents('tr').attr('id');
        gblcloid=locid;
        // console.log('addData',locid);
        let prdNm="Agrega nuevos valores de cierre";

        let el = $(`#tbl_dynamic tr[id="${locid}"]`);
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
            
        $('#newValuesModal').removeClass('overlay_hide');
        $('.overlay_closer .title').html(prdNm);

    // setTimeout(() => {
    //     updateTotals();
    // }, 100);

    });

    $('.segment')
        .unbind('click')
        .on('click', function () {
            let locid = $(this).parents('tr').attr('id');
            gblcloid=locid;
            let el = $(`#tbl_dynamic tr[id="${locid}"]`);
            // $('#txtProject').val($('#txtProject').val());
            // let montproy=$(el.find('td')[2]).text();
            // let montmant=$(el.find('td')[3]).text();
            // let montexpe=$(el.find('td')[4]).text();
            // let montdies=$(el.find('td')[5]).text();
            // let montdesc=$(el.find('td')[6]).text();
            let monttota=$(el.find('td')[7]).text();
            settingTableSeg();
            let id = $(this).parents('tr').attr('id');
            console.log('segment',id);
            // let prdNm="Segmenta valores para cobrar"
            $('#txtMontoTotSeg').val(monttota);
            $('#addSegmentModal').removeClass('overlay_hide');
            $('.overlay_closer .title').html('');

            // settingChangeSerie();
            // let id = $(this).parents('tr').attr('id');
            // console.log('segment',id);
            // let prdNm="Segmenta valores para cobrar"
            
            // $('#toSegmentModal').removeClass('overlay_hide');
            // $('.overlay_closer .title').html(prdNm);

        });
    
    
    $('#btn_save')
        .unbind('click')
        .on('click', function () {
            // console.log('BOTON SAVE O GUARDAR');
            confirm_to_Save(gblcloid);
        });

    $('#newValuesModal .btn_close')
        .unbind('click')
        .on('click', function () {
            // console.log('BOTON close O cerrar');
            $('.overlay_background').addClass('overlay_hide');
        });

    $('#toSegmentModal .btn_close')
        .unbind('click')
        .on('click', function () {
            // console.log('BOTON close O cerrar');
            $('.overlay_background').addClass('overlay_hide');
        });
}

function updateTotals() {
    let total = parseFloat($('#txtMontoProy').html().replace(/,/g, ''));
        total += parseFloat($('#txtMontoexpe').html().replace(/,/g, ''));
        total += parseFloat($('#txtMontoMant').html().replace(/,/g, ''));
        total += parseFloat($('#txtMontoDies').html().replace(/,/g, ''));
        total -= parseFloat( $('#txtMontoDesc').html().replace(/,/g, ''));

    $('#txtMontoTotal').html(fnm(total, 2, '.', ','));
}

function saveNewDocument(dt) {
    let user = Cookies.get('user').split('|');
    let u = user[0];
    let n = user[2];
    
    let cloTotProy = parseFloat($('#txtMontoProy').val().replace(/,/g, ''));
    let cloTotMaint =parseFloat($('#txtMontoMant').val().replace(/,/g, ''));
    let cloTotExpen = parseFloat($('#txtMontoexpe').val().replace(/,/g, ''));
    let cloTotCombu =parseFloat($('#txtMontoDies').val().replace(/,/g, ''));
    let cloTotDisco = parseFloat($('#txtMontoDesc').val().replace(/,/g, ''));
    let cloTotDocum = parseFloat($('#txtMontoTotal').val().replace(/,/g, ''));
    let pjtId = gblcloid;
    let usrid = u;

    var par = `
        [{  "cloTotProy" : "${cloTotProy}",
            "cloTotMaint" : "${cloTotMaint}",
            "cloTotExpen" : "${cloTotExpen}",
            "cloTotCombu" : "${cloTotCombu}",
            "cloTotDisco" : "${cloTotDisco}",
            "cloTotDocum" : "${cloTotDocum}",
            "pjtid" : "${pjtId}",
            "usrid" : "${usrid}"
        }] `;
    console.log('Save Doc-',par);
    var pagina = 'ClosedProyectChange/saveDocumentClosure';
    var tipo = 'html';
    var selector = putToWork;
    fillField(pagina, par, tipo, selector);
    // putToWork('45');
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

function confirm_to_Save(pjtid) {
    $('#starClosure').modal('show');
    $('#txtIdClosure').val(pjtid);

    $('#btnClosure').on('click', function () {
        $('#starClosure').modal('hide');

        console.log('Valor CloID',pjtid);
        //  modalLoading('S');
         saveNewDocument(pjtid);
    });
}

function putToWork(dt){
    console.log('TERMINO ACTUALIZAR ', dt);
    let folio=dt;
    $('#recordOutPut').hide();
    $('.bprint').removeClass('hide-items');
    $('.resFolio').text(refil(folio, 7));
    $('#MoveFolioModal').modal('show');
    $('#btnHideModal').on('click', function () {
        // window.location = 'WhOutputs';
        $('#MoveFolioModal').modal('hide');

    });
    $('#newValuesModal .btn_close').trigger('click');
        fill_dinamic_table();
        // getDataProjects(lpjt);
        // get_montos(glbpjtid);
        // modalLoading('H');
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

/**  ++++ Omite acentos para su facil consulta */
function omitirAcentos(text) {
    var acentos = 'ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç';
    var original = 'AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc';
    for (var i = 0; i < acentos.length; i++) {
        text = text.replace(acentos.charAt(i), original.charAt(i));
    }
    return text;
}

/**  +++++ Cachando eventos   */
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

function settingChangeSerie(){
    // console.log('setting');
    $('#toSegmentModal').removeClass('overlay_hide');
    $('#tblSegmentVal').DataTable({
        // retrieve: true,
        bDestroy: true,
        // dom: 'Blfrtip',
        order: [[2, 'asc']],
        lengthMenu: [
            [-1],
            ['Todos'],
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
        ],
    });

    $('#toSegmentModal .btn_close')
        .unbind('click')
        .on('click', function () {
            $('.overlay_background').addClass('overlay_hide');
            $('.overlay_closer .title').html('');
            $('#tblSegmentVal').DataTable().destroy;
        });
}

function settingTableSeg(){

    $('#addSegmentModal').removeClass('overlay_hide');
    $('#listTable').DataTable ({
        bDestroy: true,
        dom: 'Brti',
        lengthMenu: [
            [100, 200, -1],
            [100, 200, 'Todos'],
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
            { data: 'loc',      class: 'product-name' },
            { data: 'edoRep',   class: 'sku' },
            { data: 'edo',      class: 'sku' },
        ],
    });
    
    $('#addRowTbl')
        .unbind('click')
        .on('click', function () {
            putLocations();
        });

    $('#addSegmentModal .btn_close')
        .unbind('click')
        .on('click', function () {
            $('#addSegmentModal').addClass('overlay_hide');

        }); 

    $('#btn_save_locations')
    .unbind('click')
    .on('click', function (){
        $('#listTable tbody tr').each(function (v, u) {
            let loc=$($(u).find('td')[1]).text();
            let edo =$(this).attr('id');
            let  aux = $(this).attr('data-content');
            let truk = `${loc}|${edo}`;
            //console.log(truk);
            console.log(aux);
            if(aux == 1){
                build_data_structure(truk);
            }
        });
    });

}

function putLocations(){ //** AGREGO ED */
    let loc=$('#txtLocationExtra').val();
    let edo =$('#txtEdosRepublic_2').val();
    let name_edo =$(`#txtEdosRepublic_2 option[value="${edo}"]`).text(); // por alguna razon cada que se cierra el modal principal, y se reabre para generar un nuevo proyecto se genera una repeticion de datos
    
    par = `
            [{
                "support"  : "${edo}",
                "loc"       : "${loc}",
                "edoRep"       : "${name_edo}"
            }]`;
    
    console.log(par);
        
        fill_table(par);
        clean_selectors();
    
}

function fill_table(par) { //** AGREGO ED */
    // let largo = $('#listTable tbody tr td').html();
    // largo == 'Ningún dato disponible en esta tabla' ? $('#listTable tbody tr').remove() : '';
    par = JSON.parse(par);
    let tabla = $('#listTable').DataTable();
    tabla.row
        .add({
            editable: `<i class="fas fa-times-circle kill" id ="md${par[0].support}"></i>`,
            loc:par[0].loc,
            edoRep: par[0].edoRep,
            edoRep: par[0].edoRep,
        })
        .draw();

    $('#md' + par[0].support)
        .parents('tr')
        .attr('id', par[0].support)
        .attr('data-content', 1);

    $('.edit')
    .unbind('click')
    .on('click', function () {
        tabla.row($(this).parent('tr')).remove().draw();
    });

}

// function put_stores(dt) {
//     if (dt[0].str_id > 0) {
//         $.each(dt, function (v, u) {
//             let H = ` <option value="${u.str_id}">${u.str_name}</option>`;
//             $('#lstStore').append(H);
//         });
//     } else {
//         $('#lstStore').html('');
//     }

//     $('#lstStore')
//         .unbind('change')
//         .on('change', function () {
//             let strId = $(this).val();
//             get_products(strId);
//         });
// }


// function add_boton() {
//     let H = `<br><button class="btn-add" id="addProduct">+ modificar montos</button>`;
//     $('.frame_fix_top #tblControl thead th.product').append(H);

//     $('.frame_fix_top #addProduct').on('click', function (e) {
//         var posLeft = $('.frame_fix_top #addProduct').offset().left;
//         var posTop = $('.frame_fix_top #addProduct').offset().top;

//         let hg = parseFloat($('.frame_fix_top').css('height'));
//         let pt = $('.frame_fix_top').offset().top;
//         let pb = hg + pt;
//         let lm = (pb / 4) * 3;

//         let h1 = parseFloat($('.box_list_products').css('height'));

//         if (posTop > lm) {
//             posTop = posTop - (h1 - 20);
//             $('.list_products').css({bottom: '26px'});
//             $('.sel_product').css({top: h1 - 26 + 'px'});
//         } else {
//             $('.list_products').css({top: '20px'});
//             $('.sel_product').css({top: 0});
//         }

//         $('.box_list_products')
//             .css({top: posTop + 'px', left: posLeft + 'px'})
//             .slideDown(200);
//         $(`.list_products`).css({display: 'none'});

//         $('.box_list_products')
//             .unbind('mouseleave')
//             .on('mouseleave', function () {
//                 $('.box_list_products').slideUp(200);
//                 $('.sel_product').text('');
//             });

//         $('#Products .sel_product')
//             .unbind('keyup')
//             .on('keyup', function () {
//                 let text = $(this).text().toUpperCase();
//                 sel_product(text);
//             });
//     });
// }


/** Actualiza los totales */
// function update_totals() {
//     let ttlrws = $('.frame_content').find('tbody tr').length;
//     if (rgcnt == 1) {
//         rgcnt = 0;
//         caching_events('update_totals');

//         let total = 0;
//         $('.frame_content #tblControl tbody tr').each(function (v) {
//             let pid = $(this).attr('id');
//             if ($(this).children('td.quantity').html() != undefined) {
//                 qtybs = parseInt(pure_num($(this).children('td.quantity').text()));
//                 prcbs = parseFloat(pure_num($(this).children('td.price').text()));
//                 costs = qtybs * prcbs;

//                 $('#' + pid)
//                     .children('td.cost')
//                     .html(mkn(costs, 'n'));

//                 total += costs;
//             }
//         });
//         $('#total').html(mkn(total, 'n'));
//         $('#ttlproducts').html(ttlrws);
//     }
// }

// function saleApply() {
//     deep_loading('O');

//     if (folio == undefined) {
//         var pagina = 'ClosedProyectChange/NextExchange';
//         var par = '[{"par":""}]';
//         var tipo = 'html';
//         var selector = putNextExchangeNumber;
//         fillField(pagina, par, tipo, selector);
//     } else {
//         let ky = validator();
//         if (ky == 1) {
//             let pix = $('#lstProject').val();
//             console.log(pix);
//             let payForm = $('#lstPayForm').val();
//             let invoice = $('#txtInvoice').val();
//             let customer = $('#txtCustomer').val().toUpperCase();
//             let pjtName = pix == '' ? '' : proj[pix - 1].pjt_name.toUpperCase();
//             let pjtId = pix == '' ? 0 : proj[pix - 1].pjt_id;
//             let strId = $('#lstStore').val();

//             let par = `
//             [{
//                 "salPayForm"        : "${payForm}",
//                 "salNumberInvoice"  : "${invoice}",
//                 "salCustomerName"   : "${customer}",
//                 "pjtName"           : "${pjtName}",
//                 "strId"             : "${strId}",
//                 "pjtId"             : "${pjtId}",
//                 "comId"             : "${comids}"
//             }]`;

//             console.log(par);

//             // console.log(par);
//             clean_required();
//             let rws = $('.frame_content #tblControl tbody tr').length;
//             if (rws > 0) {
//                 var pagina = 'ProductsSalables/SaveSale';
//                 var tipo = 'html';
//                 var selector = saleDetailApply;
//                 fillField(pagina, par, tipo, selector);
//             } else {
//                 alert('Tabla vacia');
//                 deep_loading('C');
//             }
//         } else {
//             deep_loading('C');
//         }
//     }
// }


// function putNextExchangeNumber(dt) {
//     console.log(dt);
//     folio = dt;
//     saleApply();
// }



// function validator() {
//     var vl = 1;

//     var form = $('#formSales .required');

//     form.each(function () {
//         var k = $(this).val();
//         if (k == '') {
//             $(this).addClass('forValid');
//             $(this).parent().append('<i class="fas fa-times-circle novalid"></i>');
//             vl = 0;
//         }
//     });

//     return vl;
// }

// function clean_required() {
//     $('.required').removeClass('forValid');
//     $('.required').parent().children('.novalid').remove();
// }

// function saveComment(scc, mid, cmm) {
//     let par = `
//     [{
//         "section"       : "${scc}",
//         "sectnId"       : "${mid}",
//         "comment"       : "${cmm}"
//     }]`;

//     var pagina = 'ClosedProyectChange/SaveComments';
//     var tipo = 'html';
//     var selector = setSaveComment;
//     fillField(pagina, par, tipo, selector);
// }

// function setSaveComment(dt) {
//     comids.push(dt);
// }

