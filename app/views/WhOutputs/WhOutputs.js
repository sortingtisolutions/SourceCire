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
       
        getProjects(0);
        $('.tblProyects').css({display: 'none'});  
    }, 100);
}

/** +++++  Obtiene los proyectos de la base */
function getProjects(catId) {
    let liststat ="4,7,8";
    var pagina = 'WhOutputs/listProjects';
    var par = `[{"liststat":"${liststat}"}]`;
    var tipo = 'json';
    var selector = putProducts;
    fillField(pagina, par, tipo, selector);
}

/** +++++  configura la table de productos */
function settingTable() {
    let title = 'Control salida de proyectos';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    //console.log('555');
    $('#tblProyects').DataTable({
        order: [[2, 'desc']],
        dom: 'Blfrtip',
        lengthMenu: [
            [50, 100, 200, -1],
            [50, 100, 200, 'Todos'],
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
            {data: 'pjt_name',      class: 'supply'},
            {data: 'pjt_number',    class: 'sku'},
            {data: 'pjttp_name',    class: 'supply'},
            {data: 'pjt_date_start', class: 'date'},
            {data: 'pjt_date_end',  class: 'date'},
            {data: 'pjt_date_project', class: 'date'},
            {data: 'pjt_location',  class: 'supply'},
        ],
    });

      $('.tblProyects')
        .delay(500)
        .slideDown('fast', function () {
            deep_loading('C');
        });

}

/** +++++  coloca los productos en la tabla */
function putProducts(dt) {
    // console.log(dt);
    let valstage='';
    let valicon='';
    let etiquetai = '';
    
    if (dt[0].pjt_id > 0) {        
        $('#tblProyects tbody').html('');
        $.each(dt, function (v, u) {
            // <i class="fa-solid fa-dolly"></i>
            if (u.pjt_status == 4)
                { valstage='color:#008000';
                valicon='fa fa-cog toWork'; }
            else if (u.pjt_status == 7)
                { valstage='color:#FFA500';
                valicon='fa fa-solid fa-dolly detail'; }
            else
                { valstage='color:#CC0000';
                valicon='fa fa-solid fa-dolly detail';
                }
                 //valicon='fa fa-solid fa-print detail';
            if (u.pjt_status == 8){
                etiquetai = '<i class="fa fa-solid fa-print print"></i>';
            }else{
                etiquetai = '';
            }
            var H = `
                <tr id="${u.pjt_id}" style='${valstage}' data-version='${u.ver_id}'>
                    <td class="sku">${etiquetai}<i class="${valicon}"></i></td>
                    <td class="supply">${u.pjt_name}</td>
                    <td class="sku">${u.pjt_number}</td>
                    <td class="supply">${u.pjttp_name}</td>
                    <td class="date">${u.pjt_date_start}</td>
                    <td class="date">${u.pjt_date_end}</td>
                    <td class="date">${u.pjt_date_project}</td>
                    <td class="supply">${u.pjt_location}</td>
                </tr>`;
            $('#tblProyects tbody').append(H);
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
            let verid = locID.parents('tr').attr('data-version');
            confirm_to_work(pjtid, verid);
        });

    $('.detail')
        .unbind('click')
        .on('click', function () {
            let sltor = $(this);
            let pjtid = sltor.parents('tr').attr('id');
            let prdNm = 'Modifica proyecto';

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

        window.open(
            `${url}app/views/OutputReprint/OutputReprintContentReport.php?v=${pjtid}&u=${u}&n=${n}&h=${h}&em=${em}`,
            '_blank'
        );
    });
}

function confirm_to_work(pjtid, verid) {
    $('#starToWork').modal('show');
    $('#txtIdProductPack').val(pjtid);
    $('#btnToWork').on('click', function () {
        let Id = $('#txtIdProductPack').val();
        let tabla = $('#tblProducts').DataTable();
        $('#starToWork').modal('hide');
        modalLoading('S');

        var pagina = 'WhOutputs/UpdateSeriesToWork';
        var par = `[{"pjtid":"${pjtid}","verid":"${verid}"}]`;
        var tipo = 'json';
        var selector = putToWork;
        fillField(pagina, par, tipo, selector);
    });
}

function putToWork(dt){
    // console.log('Resultado Update',dt)
    modalLoading('H');
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

