let pj, px, pd;
let colores = ["#CD615","#AF7AC5","#EC7063", "#5499C7", "#48C9B0", "#EB984E"];
$(document).ready(function () {
    if (verifica_usuario()) {
        inicial();
    }
});
//INICIO DE PROCESOS
function inicial() {
    setting_table();
    

    $('#txtPrice').on('blur', function () {
        validator();
    });

    $('#btn_subletting').on('click', function () {
        let acc = $(this).attr('data_accion');
        updating_serie(acc);
    });

    $('#txtProducts')
    .unbind('keyup')
    .on('keyup', function () {
        let text = $(this).val().toUpperCase();
        selProduct(text);
    });
}

/**  +++++ Obtiene los datos de los proyectos activos +++++  */

function getListProducts(word) {
    var pagina = 'SearchIndividualProducts/listProductsWord';
    var par = `[{"word":"${word}"}]`;
    var tipo = 'json';
    var selector = putProductsList;
    fillField(pagina, par, tipo, selector); 
}
/**  +++++ Obtiene los datos de los productos activos +++++  */
function get_products(pj) {
    var pagina = 'SearchIndividualProducts/listSeriesProd';
    var par = `[{"pjtId":"${pj}"}]`;
    var tipo = 'json';
    var selector = put_Products;
    fillField(pagina, par, tipo, selector);
}

function getListRelation(serid, type) {
    var pagina = 'SearchIndividualProducts/listRelation';
    var par = `[{"prdId":"${serid}", "type":"${type}"}]`;
    var tipo = 'json';
    var selector = putListRelation;
    fillField(pagina, par, tipo, selector);
}

/** ++++  formatea la tabla ++++++ */
function setting_table() {
    let title = 'Productos ';
    let filename =
        title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');

    $('#tblProductForSubletting').DataTable({
        order: [[1, 'desc']],
        dom: 'Blfrtip',
        select: {
            style: 'single',
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
                text: 'Imprimir',
                className: 'btn-print hidden-field',
                action: function (e, dt, node, config) {                    
                    printProduct();
                },
            },
            {
                // Boton aplicar cambios
                text: 'Calendario',
                className: 'btn-print hidden-field',
                action: function (e, dt, node, config) {  
                    let id = $('#txtIdProducts').val();
                    getCalendar(id);                  
                    // printProduct();
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
            { data: 'editable', class: 'edit' },
            { data: 'prodname', class: 'product-name' },
            { data: 'prod_sku', class: 'sku' },
            { data: 'serie',    class: 'sku' },
            { data: 'proyecto', class: 'supply' },
            { data: 'fechaInicio', class: 'stores' },
            { data: 'fechaFin', class: 'date' },
            { data: 'estatus',  class: 'date' },
            { data: 'reserve',  class: 'date' },
        ],
    });
}

function putProductsList(dt) {
    var sl = $('#txtProducts').offset();
    $('#listProduct .list-items').html('');
    $('#listProduct').css({top: sl.top + 32 + 'px'});// volver a tomar al hacer scroll.

    if (dt[0].prd_id > 0) {
        $.each(dt, function (v, u) {
            let H = `<div class="list-item" id="${u.prd_id}" data_complement="${u.prd_sku}|${u.prd_id}|${u.prd_name.replace(/"/g, '')}">${u.prd_name}</div>`;
            $('#listProduct .list-items').append(H);
        });
    }
    
    $('#txtProducts').on('focus', function () {
        $('#listProduct').slideDown('fast');
    });

    $('#txtProducts').on('scroll', function(){
        sl = $('#txtProducts').offset();
        $('#listProduct').css({top: sl.top + 32 + 'px'});
    });
    $('#listProduct').on('mouseleave', function () {
        $('#listProduct').slideUp('fast');
    });

    $('#listProduct .list-item').on('click', function () {
        let prdNm = $(this).html();
        let prdId = $(this).attr('id');
        $('#txtProducts').val(prdNm);
        $('#txtIdProducts').val(prdId);
        $('#listProduct').slideUp(100);
        get_products(prdId);
    });
    activeList();
}

function selProduct(res) {
    
    res = res.toUpperCase();
    if (res == '') {
        $('#listProduct').slideUp(100);
    } else {
        $('#listProduct').slideDown(400);
    }
    if (res.length > 2) {
        if (res.length == 3) {
            getListProducts(res);
            $('#listProduct .list-items div.list-item').css({display: 'block'});
        } else {
            $('#listProduct .list-items div.list-item').css({display: 'none'});
            $('#listProduct .list-items div.list-item').each(function (index) {
                var cm = $(this).attr('data_complement').toUpperCase().replace(/|/g, '');
                
                cm = omitirAcentos(cm);
                var cr = cm.indexOf(res);
                if (cr > -1) {
                    $(this).css({display: 'block'});
                }
            });
        }
        // rowCurr.show();
    }else {
        var sl = $('#txtProducts').offset();
        $('#listProduct').css({top: sl.top + 32 + 'px'});// volver a tomar al hacer scroll.
        $('#listProduct .list-items div.list-item').css({display: 'none'});
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
function getEvents(prdId) {
    var pagina = 'SearchIndividualProducts/GetEventos';
    var par = `[{"prd_id":"${prdId}"}]`;
    var tipo = 'json';
    var selector = putEvents;
    fillField(pagina, par, tipo, selector);
}
function putEvents(dt) {
    let array = [];
    let i = 0;
    dt.forEach(element => {
        // let x = Math.floor(Math.random()*colores.length);
        let color ='';
        if (element.sttd_id == 4) {
            color = "#34495E";
        }else{
            let x = Math.floor(Math.random()*colores.length);
            color =  colores[x];
        }
        //array[i]={"id": element.id, "title": element.title, "start": element.start, "end": element.end,"color" : colores[x]};
        array[i]={"id": element.id, "title": element.title, "start": element.start, "end": element.end,"color" : color};
        i++;
    });
    calendario(array);
}
/**  ++++   Coloca los productos en el listado del input */
function put_Products(dt) {
    // console.log('put_Products-', dt);
    pd = dt;
    
    let largo = $('#tblProductForSubletting tbody tr td').html();
    largo == 'Ningún dato disponible en esta tabla'
        ? $('#tblProductForSubletting tbody tr').remove()
        : '';
    let tabla = $('#tblProductForSubletting').DataTable();
    tabla.rows().remove().draw();
    let cn = 0;
    $('.btn-print').removeClass('hidden-field');
    $('.btn-calendar').removeClass('hidden-field');
    if (pd[0].prd_name != undefined) {
        $.each(pd, function (v, u) 
        {
            let serId = u.ser_id;
            tabla.row
                .add({ // <i class="fa-solid fa-calendar-days"></i>
                    editable: `<i class="fas fa-solid fa-clipboard toList" id="${u.ser_id}"  
                        data-element="${u.ser_type_asigned}|${u.prd_id}|${u.prd_name}|${u.prd_sku}|${u.ser_sku}"></i>`,
                    // editable: '',
                    prodname:   u.prd_name,
                    prod_sku:   u.ser_sku,
                    serie:      u.ser_serial_number,
                    proyecto:   u.pjt_name,
                    fechaInicio: u.pjtpd_day_start,
                    fechaFin:   u.pjtpd_day_end,
                    estatus:    u.ser_situation,
                    reserve:    u.total_days,
                })
                .draw();
        });
        activeList();
    }
}
function getCalendar(id){
    getEvents(id);
    $('#CalendarModal').removeClass('overlay_hide');
    $('#CalendarModal').fadeIn('slow');
    $('#CalendarModal').draggable({
        handle: ".overlay_modal"
    });
    //title= 'Serie';
    $('.overlay_closer .title').html('');
    $('#CalendarModal .btn_close')
        .unbind('click')
        .on('click', function () {
            $('#CalendarModal').addClass('overlay_hide');
        });
}

function calendario(cal){
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        headerToolbar: {
            left: 'prev,next,today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek,dayGridDay' 
        },
        navLinks: true, // can click day/week names to navigate views
        editable: true,
        selectable: true,
        events: cal,
        height: 500,
        eventClick: function(calEvent, jsEvent, view){
            console.log(calEvent);
        }
    }); 
    calendar.render();
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
}

function printProduct() {
    let user = Cookies.get('user').split('|');
    let p = $('#txtIdProducts').val();
    let u = user[0];
    let n = user[2];
    let h = localStorage.getItem('host');
    console.log(p);   
    window.open(
        `${url}app/views/SearchIndividualProducts/SearchIndividualProductsReport.php?p=${p}&u=${u}&n=${n}&h=${h}`,
        '_blank'
    );
    
}

function activeList(){
    $('.toList')
    .unbind('click')
    .on('click', function () {

        let serid = $(this).attr('id');
        let type = $(this).attr('data-element').split('|')[0];
        let prdID = $(this).attr('data-element').split('|')[1];
       
        if (type == 'PV') {
            console.log('VIRTUAL -', type, prdID);
            getListRelation(prdID, type);
        } else {
            console.log('FIJO -', serid, type);
            getListRelation(serid, type);
        }
        


        // $('#SerieData').removeClass('overlay_hide');
        //     $('#SerieData .btn_close')
        //         .unbind('click')
        //         .on('click', function () {
        //             // console.log('Click Close 2');
        //             $('#SerieData').addClass('overlay_hide');
        //             $('#tblDataChg').DataTable().destroy;
        //         });
        // infoDetallePkt(lcatsub);
        // alert('Seleccion de Producto a cambiar ' + lcatsub + ' disponible');
    });
}

function settingTblRelations(){
    // console.log('settingTblRelations');
    $('#relationModal').removeClass('overlay_hide');

    $('#tblrelations').DataTable({
        bDestroy: true,
        order: [[0, 'asc']],
        lengthMenu: [
            [50, 100, -1],
            [50, 100, 'Todos'],
        ],
        pagingType: 'simple_numbers',
        language: {
            url: 'app/assets/lib/dataTable/spanish.json',
        },
        scrollY: 'calc(100vh - 290px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
            // {data: 'serchange', class: 'edit'},
            {data: 'serdetsku', class: 'sku left'},
            {data: 'serchoose', class: 'supply left'},
            {data: 'serdetname', class: 'sku left'},
            // {data: 'serdetstag', class: 'sku'},
        ],
    });

    $('#relationModal .btn_close')
        .unbind('click')
        .on('click', function () {
           $('.overlay_background').addClass('overlay_hide');
           $('.overlay_closer .title').html('');
           $('#tblrelations').DataTable().destroy;
        });
}

function putListRelation(dt){
    console.log('putListRelation', dt);
    if (dt[0].prd_id != '0') {
        settingTblRelations();
        let product_name = dt[0].pname;
        let relation = dt[0].vtype == 'PV' ? 'vIRTUAL' : 'FIJA';
        // let situation = u.ser_situation != 'D' ? 'class="levelSituation rw' + pending + '"' : 'class="rw' + pending + '"';
        let tabla = $('#tblrelations').DataTable();
        $('.overlay_closer .title').html(`RELACION DE ACCESORIOS : ${relation}`);
        tabla.rows().remove().draw();
        $.each(dt, function (v, u) {
            // let catsub=u.sku.substring(0,4);
            // let locsku=u.sku.substring(0,8);
            // let valicon='';

            // if(catsub=='010A' || catsub=='010B' || catsub=='010C'){
            // valicon=`<i class='fas fa-edit changePk' data_cat="${catsub}" data_sku="${locsku}" ></i>`
            tabla.row
                .add({
                    // serchange: u.ser_id,
                    serdetsku: u.sku,
                    serchoose: u.pname,
                    serdetname: u.pck_quantity,
                    // serdetstag: valicon,
                })
                .draw();
                // $(`#${u.pjt_id}`).parents('tr').attr('data_cat', catsub, 'data_sku', gblsku);
            // }else{
            // tabla.row
            //     .add({
            //         // serchange: u.ser_id,
            //         serdetsku: locsku,
            //         serchoose: u.prd_name,
            //         serdetname: u.pck_quantity,
            //         // serdetstag: valicon,
            //     })
            //     .draw();
            //     // $(`#${u.pjt_id}`).parents('tr').attr('data_cat', catsub, 'data_sku', gblsku);
            // } 
        });
    }
    else{
        alert('NO HAY UNA RELACION CON ESTA SERIE')
    }
    // ActiveChangePKT();
    // modalLoading('H');
}

