let seccion = '';
let docs;
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

        $('.tblProdMaster').css({display: 'none'});
        // setting_table();
        getProjects(0);
    }, 100);
}

/** +++++  Obtiene los proyectos de la base */
function getProjects(catId) {
    var pagina = 'CollectAccounts/listProjects';
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
    $('#tblCollets').DataTable({
        order: [[2, 'desc']],
        dom: 'Blfrtip',
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
            {data: 'clt_folio',      class: 'supply'},
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

/** +++++  coloca los productos en la tabla */
function putProducts(dt) {
    //console.log('DOS',dt);
    $('#tblCollets tbody').html('');
    if (dt[0].clt_id != '0') {
        //var catId = dt[0].cat_id;
        //console.log('444',dt);
        //<i class='fas fa-edit detail'>
        $.each(dt, function (v, u) {
            var H = `
                <tr id="${u.clt_id}">
                    <td></i><i class='fas fa-door-open toWork'></i></td> 
                    <td class="supply">${u.clt_folio}</td>
                    <td class="date">${u.clt_date_generated}</td>
                    <td class="supply">${u.cus_name}</td>
                    <td class="supply">${u.pjt_name}</td>
                    <td class="sku">${mkn(u.ctl_amount_payable,'n')}</td> 
                    <td class="sku">${mkn(u.ctl_amount_payable,'n')}</td>
                    <td class="sku">${mkn(u.ctl_amount_payable,'n')}</td>
                    <td class="date">${u.clt_deadline}</td>
                    <td class="date">${u.clt_deadline}</td>
                </tr>`;
            $('#tblCollets tbody').append(H);
        });
        settingTable();
        activeIcons();
    } else {
        settingTable();
    }
}

/** +++++  Activa los iconos */
function activeIcons() {
    $('.toWork')
        .unbind('click')
        .on('click', function () {
            let locID = $(this);
            let pjtid = locID.parents('tr').attr('id');

            //console.log('Paso ToWork..', pjtid);
            confirm_to_work(pjtid);
        });

    $('.detail')
        .unbind('click')
        .on('click', function () {
            
        });

}

function confirm_to_work(pjtid) {
    $('#starToWork').modal('show');
    $('#txtIdProductPack').val(pjtid);
    //borra paquete +
    $('#btnToWork').on('click', function () {
        let Id = $('#txtIdProductPack').val();
        let tabla = $('#tblProducts').DataTable();
        $('#starToWork').modal('hide');

        //console.log('Datos',pjtid,Id);
        var pagina = 'CollectAccounts/UpdateSeriesToWork';
        var par = `[{"pjtid":"${pjtid}"}]`;
        var tipo = 'json';
        var selector = putToWork;
        fillField(pagina, par, tipo, selector);
    });
}

function putToWork(dt){
    console.log(dt)
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