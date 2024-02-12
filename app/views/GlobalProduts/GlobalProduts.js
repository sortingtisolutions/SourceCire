let seccion = '';
let docs;
let grp = 50;
let num = 0;
let cats, subs, sku1, sku2, sku3, sku4;
let table;
let id_selected;
$(document).ready(function () {
    if (verifica_usuario()) {
        inicial();
    }
});
//INICIO DE PROCESOS
function inicial() {
    setTimeout(() => {
       
        getProducts(0);
        $('.tblProyects').css({display: 'none'});  
    }, 100);
}

/** +++++  Obtiene los proyectos de la base */
function getProducts(catId) {
    var pagina = 'GlobalProduts/listProducts';
    var par = `[{"catId":"${catId}"}]`;
    var tipo = 'json';
    var selector = putProducts;
    fillField(pagina, par, tipo, selector);
}

/** +++++  configura la table de productos */
function settingTable() {
    let title = 'Control salida de proyectos';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    //console.log('555');
    table = $('#tblProyects').DataTable({
        bDestroy: true,
        order: [[2, 'desc']],
        dom: 'Blfrtip',
        select: {
            style: 'multi',
            info: false,
        },
        lengthMenu: [
            [500, 1000, 2000, -1],
            [500, 1000, 2000, 'Todos'],
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
                text: 'Subir Todos los Datos',
                className: 'btn-apply submit',
                action: function (e, dt, node, config) {  
                    
                    loadProcessAll();         
                    // printProduct();
                },
            },
            {
                // Boton aplicar cambios
                text: 'Subir Datos',
                className: 'btn-apply submit asignar hidden-field',
                action: function (e, dt, node, config) {  
                    
                    loadProcess();         
                    // printProduct();
                },
            },
            {
                // Boton aplicar cambios
                text: 'Asignar',
                className: 'btn-apply asignar hidden-field',
                action: function (e, dt, node, config) { 
                    asignarSubcategoria();     
                             
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
            {data: 'editable',      class: 'edit', orderable: false},          
            {data: 'sku',      class: 'supply'},    
            {data: 'pjt_name',      class: 'supply'},
            {data: 'Precio',    class: 'sku'},
            {data: 'Tipo',    class: 'supply'},
            {data: 'Servicio', class: 'date'},
            {data: 'Seguro',  class: 'date'},
            {data: 'Moneda', class: 'date'},
            {data: 'Categoria',  class: 'supply'},
            {data: 'Subcategoria',  class: 'supply'},
        ],
    });

      $('.tblProyects')
        .delay(500)
        .slideDown('fast', function () {
            deep_loading('C');
        });

}
function settingTableSub() {  
    $('#tblSubcategories').DataTable().destroy();
    $('#tblSubcategories').DataTable({
        bDestroy: true,
        order: [[1, 'desc']],
        select: {
            style: 'single',
        },
        dom: 'Blfrtip',
        
        lengthMenu: [
            [50, 100, 200, -1],
            [50, 100, 200, 'Todos'],
        ],
        buttons: [
        ],
        pagingType: 'simple_numbers',
        language: {
            url: 'app/assets/lib/dataTable/spanish.json',
        },
        scrollY: 'calc(70vh - 150px)',
        scrollX: true,
        fixedHeader: true,
        columns: [  
            {data: 'code',      class: 'supply'},        
            {data: 'subcname',      class: 'supply'},    
        ],
    });

}
function asignarSubcategoria() {
    getCategories();
    settingTableSub();
    $('#ChangeModal').removeClass('overlay_hide');
    $('#ChangeModal .btn_close')
    .unbind('click')
    .on('click', function () {
        $('.overlay_background').addClass('overlay_hide');
    });
    $('#txtCategoryList')
    .unbind('change')
    .on('change', function(){
        let catId = $(this).val();
        getSubCategories(catId);
    });

}

function getCategories() {
    var pagina = 'GlobalProduts/listCategories';
    var par = '[{"parm":""}]';
    var tipo = 'json';
    var selector = putCategories;
    fillField(pagina, par, tipo, selector);
}
function getSubCategories(catId) {
    //console.log(catId);
    var pagina = 'GlobalProduts/listSubCategories';
    var par = `[{"catId":"${catId}"}]`;
    var tipo = 'json';
    var selector = putSubCategories;
    fillField(pagina, par, tipo, selector);
}
function updateData(sbcId,idSelected, num) {
    var pagina = 'GlobalProduts/updateData';
    var par = `[{"sbcId":"${sbcId}", "idSelected":"${idSelected}", "nxtSku":"${num}"}]`;
    var tipo = 'html';
    var selector = putData;
    fillField(pagina, par, tipo, selector);
}
function putCategories(dt) {
    console.log(dt);
    if (dt[0].cat_id != '0') {
        let catId = dt[0].cat_id;
        $.each(dt, function (v, u) {
            var H = `<option value="${u.cat_id}">${u.cat_name}</option>`;
            $('#txtCategoryList').append(H);
        });
    }
}

function putData(dt) {
    console.log(dt);
    let id= dt.split('|')[0];
    let sku= dt.split('|')[1];
    let $cat_name= dt.split('|')[2];
    let $sbc_name= dt.split('|')[3];

    let tabla = $('#tblSubcategories').DataTable();
    tabla.rows().remove().draw();
    $('.overlay_background').addClass('overlay_hide');

    let el = $(`#tblProyects tr[id="${id}"]`);

    $(el.find('td')[1]).text(sku);
    $(el.find('td')[8]).text($cat_name);
    $(el.find('td')[9]).text($sbc_name);
    
}
function putSubCategories(dt) {
    //console.log('putSubCategories',dt);
    let tabla = $('#tblSubcategories').DataTable();
    tabla.rows().remove().draw();
    if (dt[0].sbc_id != 0) {
        $.each(dt, function (v, u) {
            var row = tabla.row
            .add({
                code: u.sbc_code,
                subcname: u.sbc_name,
            })
            .draw();
            $(row.node()).attr('data-content', u.sbc_id);
        });
    }

    $('#tblSubcategories tbody tr')
        // .unbind('click')
        .on('click', function () {
            // console.log('Click Producto');
            let inx = $(this).attr('data-content');           
            getNextSku(inx);
            setTimeout(() => {
                var num = $('#txtNext').val(); 
                let filas = $('#tblProyects .selected');
                filas.each(function(v,u){
                    var idSelected = $(this).attr('id');
                    updateData(inx,idSelected, num);
                    num++;
                });
            }, 100);
        });
}
function getNextSku(sbcId){
    var pagina = 'GlobalProduts/getNextSku';
    var par = `[{"sbcId":"${sbcId}"}]`;
    var tipo = 'json';
    var selector = putNextSku;
    fillField(pagina, par, tipo, selector);
}

function putNextSku(dt){
    $('#txtNext').val(dt);
}

//Envia los datos almacenados a la tabla de productos *
function loadProcess() {
    $('#confirmarCargaModal').modal('show');
    $('#confirmLoad')
    .unbind('click').on('click', function () {
        let filas = $('#tblProyects .selected');
        var idsSelected = '';
        filas.each(function(v,u){
            idSelected = $(this).attr('id');
            idsSelected += idSelected + ',';
        });
        idsSelected = idsSelected.slice(0, -1);
        console.log(idsSelected);
        if (idsSelected != '') {
            var pagina = 'GlobalProduts/loadProcess';
            var par = `[{"idSelected":"${idsSelected}"}]`;
            var tipo = 'json';
            var selector = put_load_process;
            fillField(pagina, par, tipo, selector); 
        } 
    });
 }

 function loadProcessAll() {
    $('#confirmarCargaModal').modal('show');
    $('#confirmLoad')
    .unbind('click').on('click', function () {
        var pagina = 'GlobalProduts/loadProcessAll';
        var par = `[{"idSelected":""}]`;
        var tipo = 'json';
        var selector = put_load_process;
        fillField(pagina, par, tipo, selector); 
        
    });
 }
 function put_load_process(dt){
    console.log(dt); 
    window.location.reload();
    $('#confirmarCargaModal').modal('hide');
    //getDocumentosTable();
 }
/** +++++  coloca los productos en la tabla */
function putProducts(dt) {
    console.log(dt);
    let valstage='';
    let valicon='';
    let etiquetai = '';
    modalLoading('S');
    if (dt[0].prd_id > 0) {        
        $('#tblProyects tbody').html('');
        $.each(dt, function (v, u) {
            var H = `
                <tr id="${u.prd_id}">
                    <td class="sku"><div id="txtPrdLevel"  class="checkbox"><i id='checkPrdLevel${u.prd_id}' class="far fa-square" data_val="0"></i></div></td>
                    <td class="sku">${u.prd_sku}</td>
                    <td class="supply">${u.prd_name}</td>
                    <td class="supply">${u.prd_price}</td>
                    <td class="date">${u.prd_type_asigned}</td>
                    <td class="date">${u.srv_name}</td>
                    <td class="date">${u.prd_insured}</td>
                    <td class="date">${u.cin_code}</td>
                    <td class="supply">${u.cat_name}</td>
                    <td class="supply">${u.sbc_name}</td>
                </tr>`;
            $('#tblProyects tbody').append(H);
        });
        settingTable();
        activeIcons();
    } else {
        settingTable();
    }
    modalLoading('H');
}

/** +++++  Activa los iconos */
function activeIcons() {
    $('.toWork')
        .unbind('click')
        .on('click', function () {
            let locID = $(this);
            let pjtid = locID.parents('tr').attr('id');
            let verid = locID.parents('tr').attr('data-version');
            confirm_to_work(pjtid, verid);
        });

    $('.detail')
        .unbind('click')
        .on('click', function () {
            let sltor = $(this);
            let pjtid = sltor.parents('tr').attr('id');
            let prdNm = 'Modifica proyecto';
            // console.log(pjtid);
            Cookies.set('pjtid', pjtid, {expires:1});
            window.location = 'WhOutputContent';
        });
    $('.print')
    .unbind('click')
    .on('click', function () {
        let h = localStorage.getItem('host');
        let sltor = $(this);
        let pjtid = sltor.parents('tr').attr('id');
        let user = Cookies.get('user').split('|');
        let u = user[0];
        let n = user[2];
        let em = user[3];
        // console.log('Datos', v, u, n, h);
        window.open(
            `${url}app/views/OutputReprint/OutputReprintContentReport.php?v=${pjtid}&u=${u}&n=${n}&h=${h}&em=${em}`,
            '_blank'
        );
    });

    $('#tblProyects tbody tr')
    .unbind('click')
    .on('click', function(){
        let id = $(this).attr('id');
        let val = $('#checkPrdLevel' + id).attr('data_val');
        let cont = 0;
       // $('.btn-apply').removeClass('hidden-field');
        if (val == 1) {
            $('#checkPrdLevel'+ id).attr('data_val', 0);
            $('#checkPrdLevel'+ id).removeClass('fas fa-check-square');
            $('#checkPrdLevel'+ id).addClass('far fa-square');
            //$('.btn-apply').addClass('hidden-field');
        }else{
            $('#checkPrdLevel'+ id).attr('data_val', 1);
            $('#checkPrdLevel'+ id).removeClass('far fa-square');
            $('#checkPrdLevel'+ id).addClass('fas fa-check-square');
        }

        setTimeout(() => {
            RenglonesSelection = table.rows({selected: true}).count();
            if (RenglonesSelection == 0) {
               $('.asignar').addClass('hidden-field');
            } else {
               $('.asignar').removeClass('hidden-field');
            }
         }, 10);
    });
}

function confirm_to_work(pjtid, verid) {
    $('#starToWork').modal('show');
    $('#txtIdProductPack').val(pjtid);
    //borra paquete +
    $('#btnToWork').on('click', function () {
        let Id = $('#txtIdProductPack').val();
        let tabla = $('#tblProducts').DataTable();
        $('#starToWork').modal('hide');
        //console.log('Datos',pjtid,Id);

        var pagina = 'GlobalProduts/UpdateSeriesToWork';
        var par = `[{"pjtid":"${pjtid}","verid":"${verid}"}]`;
        var tipo = 'json';
        var selector = putToWork;
        fillField(pagina, par, tipo, selector);
        // putToWork(pjtid);
    });
}

function putToWork(dt){
    // console.log('Resultado Update',dt)
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

