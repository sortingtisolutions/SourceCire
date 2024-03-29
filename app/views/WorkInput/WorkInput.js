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

    }, 100);
}

/** +++++  Obtiene los proyectos de la base */
function getProjects(catId) {
    let liststat ="8,9";
    var pagina = 'WorkInput/listProjects';
    var par = `[{"liststat":"${liststat}"}]`;
    var tipo = 'json';
    var selector = putProjects;
    fillField(pagina, par, tipo, selector);
}

/** +++++  configura la table de productos */
function settingTable() {
    let title = 'Control salida de proyectos';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    $('#tblProyects').DataTable({
        order: [[2, 'desc']],
        dom: 'Blfrtip',
        lengthMenu: [
            [50, 100, -1],
            [50, 100, 'Todos'],
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
        ],
    });

      $('.tblProdMaster')
        .delay(500)
        .slideDown('fast', function () {
            //$('.deep_loading').css({display: 'none'});
            //$('#tblProyects').DataTable().draw();
            deep_loading('C');
        });

}

/** +++++  coloca los productos en la tabla */
function putProjects(dt) {
    // console.log('putProjects',dt);
    let valstage='';
    let valicon='';
    //let tabla = $('#tblProyects').DataTable();
    if (dt[0].pjt_id != '0') {
        $('#tblProyects tbody').html('');
        console.log(dt[0].pjt_status);
        $.each(dt, function (v, u) {
           
            if (u.pjt_status == 8)
            { valstage='color: #CC0000';
              valicon='fa fa-cog toWork'; }
            else 
             { valstage='color: #FFA500';
             valicon='fa fa-solid fa-edit detail'; }
            // console.log(valstage, valicon);
            var H = `
                <tr id="${u.pjt_id}" style='${valstage}'>
                    <td class="sku"><i class="${valicon}"</td>
                    <td class="supply">${u.pjt_name}</td>
                    <td class="sku">${u.pjt_number}</td>
                    <td class="supply">${u.pjttp_name}</td>
                    <td class="date">${u.pjt_date_start}</td>
                    <td class="date">${u.pjt_date_end}</td>
                </tr>`;
            $('#tblProyects tbody').append(H); 
            /* tabla.row
            .add({
                editable: `<i class="${valicon}" id="md${u.pjt_id}"></i>`,
                pjt_name:u.pjt_name,
                pjt_number: u.pjt_number,
                pjttp_name: u.pjttp_name,
                pjt_date_start: u.pjt_date_start,
                pjt_date_end: u.pjt_date_end,
            })
            .draw();
            $('#md' + u.pjt_id)
                .parents('tr')
                .attr('id', u.pjt_id)
                .css({color: valstage}); */
        });
        settingTable();
        activeIcons();
    } else{
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
            confirm_to_work(pjtid);
        });

    $('.detail')
        .unbind('click')
        .on('click', function () {
            console.log('Pasando siguiente ventana...');
            let sltor = $(this);
            let pjtid = sltor.parents('tr').attr('id');
            let prdNm = 'Modifica proyecto';

            Cookies.set('pjtid', pjtid, {expires:1});
            window.location = 'WorkInputContent';
        });
}

function confirm_to_work(pjtid) {
    $('#starToWork').modal('show');
    $('#txtIdProductPack').val(pjtid);
    $('#btnToWork').on('click', function () {
        let Id = $('#txtIdProductPack').val();
        let tabla = $('#tblProducts').DataTable();
        $('#starToWork').modal('hide');

        var pagina = 'WorkInput/UpdateSeriesToWork';
        var par = `[{"pjtid":"${pjtid}"}]`;
        var tipo = 'json';
        var selector = putToWork;
        fillField(pagina, par, tipo, selector);
    });
}

function putToWork(dt){
    // console.log(dt)
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