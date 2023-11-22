let products;
//let prjid = window.location.pathname.split("/").pop();
let gblprjid,user,usrid;
let gblprjname;
//var prjid;

$(document).ready(function () {
    if (verifica_usuario()) {
        // let temporal=Cookies.get('user');
        // console.log(temporal);
        prjid=Cookies.get('pjtid');
        inicial();
    }
});
//INICIO DE PROCESOS
function inicial() {
    setting_table_AsignedProd();/* 
    getUsersP();
    getUsersA();
    getUsersC(); */
    getDetailProds();
    getCustomers();
    getProjectType();
    getProjectTypeCalled();
    
    getLocationType();
    user = Cookies.get('user').split('|');
    usrid = user[0];
    // Boton para registrar la salida del proyecto y los productos
    $('#recordChgUser')
        .unbind('click')
        .on('click', function () {
            console.log(gblprjid);
            confirm_toChgUsr(gblprjid, gblprjname);
     });

     $('#cleanForm')
        .unbind('click')
        .on('click', function () {
            CleanCombos();
     });
}

// Solicita los productos del proyecto  OK
function getDetailProds() {
    var pagina = 'ParentsProjects/listDetailProds';
    var par = `[{"pjt_id":"${prjid}"}]`;
    var tipo = 'json';
    var selector = putDetailsProds;
    fillField(pagina, par, tipo, selector);
} 
/**  Obtiene el listado de clientes */
function getCustomers() {
    var pagina = 'ParentsProjects/listCustomers';
    var par = `[{"prm":""}]`;
    var tipo = 'json';
    var selector = putCustomers;
    fillField(pagina, par, tipo, selector);
}
function getProjectType() {
    var pagina = 'ParentsProjects/listProjectsType';
    var par = `[{"pjt":""}]`;
    var tipo = 'json';
    var selector = putProjectsType;
    fillField(pagina, par, tipo, selector);
}
function getProjectTypeCalled() {
    var pagina = 'ParentsProjects/listProjectsTypeCalled';
    var par = `[{"pjt":""}]`;
    var tipo = 'json';
    var selector = putProjectsTypeCalled;
    fillField(pagina, par, tipo, selector);
}

function getLocationType() {
    var pagina = 'ParentsProjects/getLocationType';
    var par = `[{"prm":""}]`;
    var tipo = 'json';
    var selector = putLocationType;
    fillField(pagina, par, tipo, selector);
}

function getLocations(prjid) {
    console.log(prjid);
    var pagina = 'ParentsProjects/getLocations';
    var par = `[{"pjtid":"${prjid}"}]`;
    var tipo = 'json';
    var selector = putLocations;
    fillField(pagina, par, tipo, selector);
}

function updateUsers(pjtid,areid,empid,empname) {
    var par = `
        [{  "pjtid"     : "${pjtid}",
            "areid"     : "${areid}",
            "empid"     : "${empid}",
            "empname"   : "${empname}",
            "usrid"   : "${usrid}"
        }]`;
    var pagina = 'ParentsProjects/updateUsers';
    var tipo = 'json';
    var selector = putupdateUser;
    fillField(pagina, par, tipo, selector);
}

// Configura la tabla de productos del proyecto

function setting_table_AsignedProd() {
    let title = 'Proyectos Atendidos';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    $('#tblAsignedProd').DataTable({
        order: [[1, 'asc']],
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
        scrollY: 'calc(100vh - 240px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
            {data: 'editable',  class: 'edit'},
            {data: 'pjtname',   class: 'pjtname supply'},
            {data: 'pjtnum',    class: 'pjtnum supply'},
            {data: 'pjttpy',    class: 'pjttpy sku'},
            {data: 'pjtfini',   class: 'pjtfini date'},
            {data: 'pjtffin',   class: 'pjtffin date'},
        ],
    });
}
function putCustomers(dt) {
    cust = dt;
    // Llena el selector de clientes
    $.each(cust, function (v, u) {
        if (u.cut_id == 1) {
            let H = `<option value="${u.cus_id}"> ${u.cus_name}</option>`;
            $('#txtCustomer').append(H);
        }
    });
    // Llena el selector de relacion de clientes
    $.each(cust, function (v, u) {
        if (u.cut_id == 2) {
            let H = `<option value="${u.cus_id}"> ${u.cus_name}</option>`;
            $('#txtProductor').append(H);
        }
    });
}
function putProjectsType(dt) {
    tpprd = dt;
    $.each(tpprd, function (v, u) {
        let H = `<option value="${u.pjttp_id}"> ${u.pjttp_name}</option>`;
        $('#txtProjectType').append(H);
    });
}
function putProjectsTypeCalled(dt) {
    tpcall = dt;
    $.each(tpcall, function (v, u) {
        let H = `<option value="${u.pjttc_id}"> ${u.pjttc_name}</option>`;
        $('#txttypeCall').append(H);
    });
}
function putLocationType(dt) {
    loct =dt;
    $.each(loct, function (v, u) {
        let H = `<option value="${u.loc_id}">${u.loc_type_location}</option>`;
        $('#txtLocation').append(H);
        $('#txtLocation').val(1);
    });
}

function putLocations(dt) {
    $('#txtLocationsEdt').html('');
    $.each(dt, function (v, u) {
        let H = `<option value="${u.lce_id}">${u.lce_location}</option>`;
        $('#txtLocationsEdt').append(H);
        
    });
    $('#txtLocationsEdt').val(dt[0].lce_id);
}
// ### LISTO ### Llena la TABLA INICIAL de los detalles del proyecto
function putDetailsProds(dt) {
    let valstage='';
    let valicon='';
    if (dt[0].pjt_id != '0')
    {
        // let tabla = $('#tblAsignedProd').DataTable();
        $('#tblAsignedProd tbody').html('');
        $.each(dt, function (v, u){
            if (u.pjt_status == 4)
                { valstage='color:#008000';
                valicon='fa fa-cog toWork'; }
            else if (u.pjt_status == 7)
                { valstage='color:#FFA500';
                valicon='fa fa-solid fa-dolly detail'; }
            else
                { valstage='color:#CC0000';
                valicon='fa fa-solid fa-dolly detail'; }
                // style='${valstage}'
            var H = `
                <tr id="${u.pjt_id}" name="${u.pjt_name}">  
                    <td class="supply"><i class="fas fa-edit toLink" id="${u.pjt_id}"></i> <i class="fas fa-times-circle kill" id=${u.pjt_id}></i></td>
                    <td class="pjtname">${u.pjt_name}</td>
                    <td class="pjtnum">${u.pjt_number}</td>
                    <td class="pjttpy">${u.pjttp_name}</td>
                    <td class="pjtfini">${u.pjt_date_start}</td>
                    <td class="pjtffin">${u.pjt_date_end}</td>
                </tr>`;
            $('#tblAsignedProd tbody').append(H);
        });
        activeIcons();
    }
}

// ### LISTO ###   habilita el botones para validar en TABLA INICIAL
function activeIcons() {
    // console.log('ActivaIcon');
    $('.toLink')
        .unbind('click')
        .on('click', function () {
            CleanCombos();
            let id = $(this).parents('tr');
            //console.log(id);
            let pjtid = id.attr('id');
            let pjtnm = id.children('td.pjtname').text();
            gblprjid=pjtid;
            gblprjname = pjtnm;
             console.log('Cont-Producto', pjtid,pjtnm);
            if (pjtid > 0) {
                //getUsersOnProject(pjtid);
                limpiarDatos();
                editProject(pjtid);
            }
        });
        $('.kill')
        .unbind('click')
        .on('click', function () {
            let id = $(this).parents('tr');
            console.log(id);
            let pjtid = id.attr('id');
            console.log('To Kill ' + pjtid);
            if (pjtid != undefined){
            $('#delProdModal').modal('show');
                /* $('#txtIdProduct').val(prdId); */
                //borra paquete +
                $('#btnDelProduct').on('click', function () {
                    
                    $('#delProdModal').modal('hide');

                    var pagina = 'ParentsProjects/CancelParentsProjects';
                    var par = `[{"pjtid":"${pjtid}"}]`;
                    var tipo = 'html';
                    var selector = putDelFreelances;
                    fillField(pagina, par, tipo, selector); 
                });
            }
        });
        $('#txtLocation')
        .unbind('change')
        .on('change', function(){
            let selection = $(this).val();
            if (selection == 2 || selection == 4) {
                console.log(selection);
                $('#txtLocationEdt').addClass('hide');
                /* $('#addLocation').removeClass('hide'); */
            }else{
                $('#txtLocationEdt').removeClass('hide');
                /* $('#addLocation').addClass('hide'); */
            } 
            
        });

}
function putDelFreelances(dt) {
    console.log('Id Cliente Borrado', dt);
}
function editProject(pjtid,pjtnm) {
    var pagina = 'ParentsProjects/getProject';
    var par = `[{"pjt_id":"${pjtid}"}]`;
    var tipo = 'json';
    var selector = putDataProject;
    fillField(pagina, par, tipo, selector);
}

function putDataProject(dt){
    console.log(dt);
    $('#txtProjectId').val(dt[0].pjt_id);
    $('#txtProjectName').val(dt[0].pjt_name);
    $('#txtProjectType').val(dt[0].pjttp_id);
    $('#txtTime').val(dt[0].pjt_time);
    $('#txtLocation').val(dt[0].loc_id);
    $('#txtLocationEdt').val(dt[0].pjt_location);
    $('#txtCustomer').val(dt[0].cus_id);
    $('#txtProductor').val(dt[0].cus_parent);
    $('#txtTripDays').val(dt[0].pjt_trip_go);
    $('#txtReturnDays').val(dt[0].pjt_trip_back);
    $('#txtLoad').val(dt[0].pjt_to_carry_on);
    $('#txtDownload').val(dt[0].pjt_to_carry_out);
    $('#txtTestTec').val(dt[0].pjt_test_tecnic);
    $('#txtTestLook').val(dt[0].pjt_test_look);
    $('#txttypeCall').val(dt[0].pjttc_id);
    $('#txtCustomerId').val(dt[0].cuo_id);
    $('#txtHowRequired').val(dt[0].pjt_how_required);
    if (dt[0].loc_id == 2 || dt[0].loc_id == 4) {
        getLocations(dt[0].pjt_id);
        $('#txtLocationEdt').addClass('hide');
        $('#txtLocationsEdt').removeClass('hide');
    } else {
        $('#txtLocationEdt').removeClass('hide');
        $('#txtLocationsEdt').addClass('hide');
    }
}

function limpiarDatos(){
    $('#txtProjectId').val('');
    $('#txtProjectName').val('');
    $('#txtProjectType').val('');
    $('#txtTime').val('');
    $('#txtLocation').val('');
    $('#txtLocationEdt').val('');
    $('#txtCustomer').val('');
    $('#txtProductor').val('');
    $('#txtTripDays').val('');
    $('#txtReturnDays').val('');
    $('#txtLoad').val('');
    $('#txtDownload').val('');
    $('#txtTestTec').val('');
    $('#txtTestLook').val('');
    $('#txttypeCall').val('');
    $('#txtCustomerId').val('');
    $('#txtHowRequired').val('');
}
function putUsersOnProject(dt) {
    console.log('putUsersOnProject', dt);
    if (dt[0].whoatd_id != '0') {
        // let cinId = dt[0].usr_id;
        $.each(dt, function (v, u) {
            switch (u.are_id) {
                case '1':
                    let locselP = document.querySelector('#selUsrP');
                    locselP.value = u.emp_id;
                    break;
                case '2':
                    let locselC = document.querySelector('#selUsrC');
                    locselC.value = u.emp_id;
                    break;
                case '3':
                    let locselA = document.querySelector('#selUsrA');
                    locselA.value = u.emp_id;
                    break;
                case '5':
                    let locselUP = document.querySelector('#selUsrP');
                    locselUP.value = u.emp_id;
                    break;
                default:
            }
        });
    }
}

function confirm_toChgUsr(pjtid,prjname) {
    $('#starClosure').modal('show');
    $('#txtIdClosure').val(pjtid);
    $('#ProjectName').text(prjname+'?')
    //borra paquete +
    $('#btnClosure')
    .unbind('click')
    .on('click', function () {
        $('#starClosure').modal('hide');
        // console.log('Datos CLICK',pjtid);
        datasUser(pjtid);
    });
}

function CleanCombos() {
    $('#txtProjectId').val('');
    $('#txtProjectName').val('');
    $('#txtProjectType').val('');
    $('#txtTime').val('');
    $('#txtLocation').val('');
    $('#txtLocationEdt').val('');
    $('#txtCustomer').val('');
    $('#txtProductor').val('');
    $('#txtTripDays').val('');
    $('#txtReturnDays').val('');
    $('#txtLoad').val('');
    $('#txtDownload').val('');
    $('#txtTestTec').val('');
    $('#txtTestLook').val('');
    $('#txttypeCall').val('');
    $('#txtCustomerId').val('');
    $('#txtHowRequired').val('');
}

function datasUser(pjtid) {
    let empid='';
    let empname='';
    let areid=0;
    let projId = $('#txtProjectId').val();
    let projName = $('#txtProjectName').val();
    let projType = $('#txtProjectType').val();
    let projTime = $('#txtTime').val();
    let location = $('#txtLocation').val();
    let loc_name = $('#txtLocationEdt').val();
    let customer = $('#txtCustomer').val();

    let projProductor = $('#txtProductor').val();
    let tripGo = $('#txtTripDays').val();
    let tripBack = $('#txtReturnDays').val();
    let toCarryOn =  $('#txtLoad').val();
    let toCarryOut = $('#txtDownload').val();

    let testTecnic = $('#txtTestTec').val();
    let testLook= $('#txtTestLook').val();
    let projTypeCalled=  $('#txttypeCall').val();
    let howRequired = $('#txtHowRequired').val();
    let cuoId = $('#txtCustomerId').val();

    let par = `
    [{
        "projId"         : "${projId}",
        "pjtName"        : "${projName.toUpperCase()}",
        "pjtTime"        : "${projTime}",
        "pjtType"        : "${projType}",
        "locId"          : "${location}",
        "pjtLocation"       : "${loc_name}",
        "cuoId"          : "${cuoId}",
        "cusId"          : "${customer}",
        "cusParent"      : "${projProductor}",
        "pjttcId"        : "${projTypeCalled}",
        "pjtHowRequired" : "${howRequired.toUpperCase()}",
        "pjtTripGo"      : "${tripGo}",
        "pjtTripBack"    : "${tripBack}",
        "pjtToCarryOn"   : "${toCarryOn}",
        "pjtToCarryOut"  : "${toCarryOut}",
        "pjtTestTecnic"  : "${testTecnic}",
        "pjtTestLook"    : "${testLook}"
    }]`;
    console.log(par);
    var pagina = 'ParentsProjects/UpdateProject';
    var tipo = 'html';
    var selector = loadProject;
    fillField(pagina, par, tipo, selector);
    
}
function loadProject(dt) {
    console.log(dt);
    
}

function putupdateUser(dt){
    console.log('TERMINO ACTUALIZAR', dt);
    CleanCombos();
    let folio=dt;
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

function printOutPutContent(verId) {
    let user = Cookies.get('user').split('|');
    let v = verId;
    let u = user[0];
    let n = user[2];
    let h = localStorage.getItem('host');
    // console.log('Datos', v, u, n, h);
    window.open(
        `${url}app/views/ParentsProjects/ParentsProjectsReport.php?v=${v}&u=${u}&n=${n}&h=${h}`,
        '_blank'
    );
}