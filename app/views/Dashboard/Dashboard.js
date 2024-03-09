let tc = 0;
let st = 0;
$(document).ready(function () {
    // cargaIndicadores();
    verifica_usuario();
    // setInterval(cargaIndicadores, 15000);
});

//Lama a todos los KPI
// function cargaIndicadores() {
//     //getTotalProyects();
//     getKPIS();
//     getProyects();
//     $('#projects').unbind('click')
//     .on('click', function () {
//         console.log('Total');
//         getProjectsList();
//         $('#ProjectsData').removeClass('overlay_hide');
//             //getProjects(st);
//             $('#ProjectsData .btn_close')
//                 .unbind('click')
//                 .on('click', function () {
//                     // console.log('Click Close 2');
//                     $('#ProjectsData').addClass('overlay_hide');
//                     $('#tblProjects').DataTable().destroy;
//                 }); 
//     });

//     $('#products').unbind('click')
//     .on('click', function () {
//         console.log('Total');
//         getProductsList();
//         $('#ProductsData').removeClass('overlay_hide');
//             //getProjects(st);
//             $('#ProductsData .btn_close')
//                 .unbind('click')
//                 .on('click', function () {
//                     // console.log('Click Close 2');
//                     $('#ProductsData').addClass('overlay_hide');
//                     $('#tblProducts').DataTable().destroy;
//                 }); 
//     });

//     $('#camaras').unbind('click')
//     .on('click', function () {
//         console.log('Total');
//         st = 1;
//         tc = 'C';
//         getCamarasList(st);
        
//         $('#CamarasData').removeClass('overlay_hide');
//             //getProjects(st);
//             $('#CamarasData .btn_close')
//                 .unbind('click')
//                 .on('click', function () {
//                     // console.log('Click Close 2');
//                     $('#CamarasData').addClass('overlay_hide');
//                     $('#tblCamaras').DataTable().destroy;
//                 }); 
//     });

//     $('#iluminacion').unbind('click')
//     .on('click', function () {
//         console.log('Total');
//         st = 3;
//         tc = 'I';
//         getCamarasList(st);
//         $('#CamarasData').removeClass('overlay_hide');
//             //getProjects(st);
//             $('#CamarasData .btn_close')
//                 .unbind('click')
//                 .on('click', function () {
//                     // console.log('Click Close 2');
//                     $('#CamarasData').addClass('overlay_hide');
//                     $('#tblCamaras').DataTable().destroy;
//                 }); 
//     });


//     $('#uniMoviles').unbind('click')
//     .on('click', function () {
//         console.log('Total');
//         st = 11;
//         tc = 'M';
//         getProductsCat(st);
        
//         $('#CamarasData').removeClass('overlay_hide');
//             //getProjects(st);
//             $('#CamarasData .btn_close')
//                 .unbind('click')
//                 .on('click', function () {
//                     // console.log('Click Close 2');
//                     $('#CamarasData').addClass('overlay_hide');
//                     $('#tblCamaras').DataTable().destroy;
//                 }); 
//     });

//     $('#especiales').unbind('click')
//     .on('click', function () {
//         console.log('Total');
//         st = '9,10';
//         tc = 'E';
//         getProductsCat(st);
//         $('#CamarasData').removeClass('overlay_hide');
//             //getProjects(st);
//             $('#CamarasData .btn_close')
//                 .unbind('click')
//                 .on('click', function () {
//                     // console.log('Click Close 2');
//                     $('#CamarasData').addClass('overlay_hide');
//                     $('#tblCamaras').DataTable().destroy;
//                 }); 
//     });

// }

// //Total de projectos
// /*function getTotalProyects() {
//     var pagina = 'Dashboard/getTotalProjects';
//     var par = '[{"parm":""}]';
//     var tipo = 'json';
//     var selector = putInCardTotal;
//     fillField(pagina, par, tipo, selector);
// }

// function putInCardTotal(dt) {
//     $('#totalProyects').text(dt[0].Total);
// }*/

// //Total de projectos cifras
// function getKPIS() {
//     var pagina = 'Dashboard/getkpis';
//     var par = '[{"parm":""}]';
//     var tipo = 'json';
//     var selector = putInKPIS;
//     fillField(pagina, par, tipo, selector);
// }

// function putInKPIS(dt) {
//     // console.log('putInKPIS',dt);
//     if (dt[0].Total == 0) {
//         $('#totalProyects').text(0);
//         $('#totalCamaras').text(0);
//         $('#totalIluminacion').text(0);
//         $('#totalMoviles').text(0);
//         $('#equiposEspeciales').text(0);
//         $('#plantas').text(0);
//         $('#foro').text(0);
//     } else {
//         $.each(dt, function (ind, elem) {
//             console.log(elem);
//             elem.total == null
//                 ? $('#totalProyects').text(0)
//                 : $('#totalProyects').text(elem.total);
//             elem.Camaras == null
//                 ? $('#totalCamaras').text(0)
//                 : $('#totalCamaras').text(elem.Camaras);
//             elem.Iluminación == null
//                 ? $('#totalIluminacion').text(0)
//                 : $('#totalIluminacion').text(elem.Iluminación);
//             elem.moviles == null
//                 ? $('#totalMoviles').text(0)
//                 : $('#totalMoviles').text(elem.moviles);
//             elem.especiales == null
//                 ? $('#equiposEspeciales').text(0)
//                 : $('#equiposEspeciales').text(elem.especiales);
//             elem.plantas == null
//                 ? $('#plantas').text(0)
//                 : $('#plantas').text(elem.plantas);
//             elem.foro == null
//                 ? $('#foro').text(0)
//                 : $('#foro').text(elem.foro);
//             $( "#foroClass" ).removeClass( "bg-c-yellow bg-c-green bg-c-red bg-c-gray " );  
//             switch (elem.foro)
//             {
//                 case 'V':
//                     $( "#foroClass" ).addClass( "bg-c-green" );
//                     break;
//                 case 'A':
//                     $( "#foroClass" ).addClass( "bg-c-yellow " );
//                     break;
//                 case 'R':
//                     $( "#foroClass" ).addClass( "bg-c-red" );
//                     break;
//                 case 'N':
//                     $( "#foroClass" ).addClass( "bg-c-gray " );
//                     break;
//                 default:
//                     $( "#foroClass" ).addClass( "bg-c-gray " );
//             } 
                              
//         });
//     }
// }

// //Total de projectos contemplados
// function getProyects() {
//     var pagina = 'Dashboard/getProjects';
//     var par = '[{"parm":""}]';
//     var tipo = 'json';
//     var selector = putInRow;
//     fillField(pagina, par, tipo, selector);
// }

// function putInRow(dt) {
//     console.log('putInRow',dt);
//     $('.contenedorLlamado').html('');
//     $('.contenedorProyecto').html('');
//     if (dt[0].pjt_id != 0) {
//         $.each(dt, function (ind, elem) {
//             var color = getColor(elem.movement);
//             // var selector = comparaFecha(elem.pjt_date_start);
//             var selector = comparaFecha(elem.pjt_status);
//             elem.analista == null
//                 ? (elem.analista = '')
//                 : (elem.analista = elem.analista);
//             fillCards(
//                 selector,
//                 elem.pjt_name,
//                 color,
//                 elem.TotalFull,
//                 elem.Camaras,
//                 elem.Expendables,
//                 elem.Iluminación,
//                 elem.analista,
//                 elem.pjt_id,
//                 elem.movement
//             );
//         });
//         $('.GaugeMeter').gaugeMeter();

//         $('.GaugeMeter').click(function () {
//             var color = $(this).attr('data-color');
//             if (color == '#FD971F') {
//                 cambiaEstado(this.id);
//             }
//         });
//     }
// }

// //Total de projectos contemplados
// function cambiaEstado(id) {
//     var pagina = 'Dashboard/changeStatus';
//     var par = '[{"pjt_id":"' + id + '"}]';
//     var tipo = 'json';
//     var selector = putEstado;
//     fillField(pagina, par, tipo, selector);
// }
// function getProjectsList(status) {
//     console.log(status);
//     var pagina = 'Dashboard/listProjects';
//     var par = `[{"pjtsta":"${status}"}]`;
//     var tipo = 'json';
//     var selector = putProjectsList;
//     fillField(pagina, par, tipo, selector);
// }

// function getProductsList(status) {
//     console.log(status);
//     var pagina = 'Dashboard/listProducts';
//     var par = `[{"pjtsta":"${status}"}]`;
//     var tipo = 'json';
//     var selector = putProductsList;
//     fillField(pagina, par, tipo, selector);
// }
// function getCamarasList(status) {
//     console.log(status);
//     var pagina = 'Dashboard/listCamaras';
//     var par = `[{"pjtsta":"${status}"}]`;
//     var tipo = 'json';
//     var selector = putCamarasList;
//     fillField(pagina, par, tipo, selector);
// }
// function getProductsCat(status) {
//     console.log(status);
//     var pagina = 'Dashboard/listProductsCat';
//     var par = `[{"pjtsta":"${status}"}]`;
//     var tipo = 'json';
//     var selector = putCamarasList;
//     fillField(pagina, par, tipo, selector);
// }
// function putEstado(dt) {
//     cargaIndicadores();
//     console.log('llego',dt);
// }

// function putProjectsList(dt) {
//     console.log('putSubarrendos', dt);
//     settingProjects();
    
//     let tablaChg = $('#tblProjects').DataTable();
//     $('#ProjectsData .overlay_closer .title').html(`PROYECTOS:`);
//     tablaChg.rows().remove().draw();
//     if (dt[0].pjt_id > 0) {
//         $.each(dt, function (v, u) {
//             // console.log(u);
//             tablaChg.row
//              .add({
//                  prjname: u.pjt_name,
//                  dtstart: u.pjt_date_start,
//                  dtend: u.pjt_date_end,
//                  pjtname: u.pjttc_name,
//                  analyst: u.emp_fullname,
//              })
//              .draw();
//          //$(`#${u.pjt_id}`).parents('tr').attr('id',u.pjt_id);
//         });
//     } 
// }

// function putProductsList(dt) {
//     console.log('ProductsData', dt);
//     settingProdSub();
    
//     let tablaChg = $('#tblProducts').DataTable();
//     $('#ProductsData .overlay_closer .title').html(`PRODUCTOS:`);
//     tablaChg.rows().remove().draw();
//     if (dt[0].pjt_id > 0) {
//         $.each(dt, function (v, u) {
//             // console.log(u);
//             tablaChg.row
//              .add({
//                 prdname: u.prd_name,
//                 price: u.prd_price,
//                 qty: u.pjtcn_quantity,
//                 pjtname: u.pjt_name,
//                 call: u.pjttc_name,
//                 analyst: u.emp_fullname,
//              })
//              .draw();
//          //$(`#${u.pjt_id}`).parents('tr').attr('id',u.pjt_id);
//         });
//     } 
// }

// function putCamarasList(dt) {
//     console.log('CamarasData', dt);
//     settingCamaras();
    
//     let tablaChg = $('#tblCamaras').DataTable();
//     $('#CamarasData .overlay_closer .title').html(`PRODUCTOS:`);
//     tablaChg.rows().remove().draw();
//     if (dt[0].prd_id > 0) {
//         $.each(dt, function (v, u) {
//             // console.log(u);
//             tablaChg.row
//              .add({
//                  cusname: u.prd_name,
//                  email: u.prd_price,
//                  phone: u.pjt_name,
//                  rfc: u.pjtcn_quantity,
//                  address: u.pjttc_name,
//              })
//              .draw();
//          //$(`#${u.pjt_id}`).parents('tr').attr('id',u.pjt_id);
//         });
//     } 
// }
// function comparaFecha(fecha) {
//     // console.log('comparaFecha',fecha);
//     // var selector = '';
//     // var array = fecha.split(' ');
//     // var array2 = array[0].split('-');

//     // var Hoy = new Date(); //Fecha actual del sistema
//     // var AnyoHoy = Hoy.getFullYear();
//     // var MesHoy = Hoy.getMonth();
//     // var DiaHoy = Hoy.getDate();

//     // var fechaProyecto = new Date(array2[0], parseInt(array2[1]) - 1, array2[2]);
//     // var fechaActual = new Date(AnyoHoy, MesHoy, DiaHoy);

//     // if (fechaProyecto <= fechaActual) {
//     //     selector = '.contenedorLlamado';
//     // } else {
//     //     selector = '.contenedorProyecto';
//     // }

//     if (fecha == 8) {
//         selector = '.contenedorLlamado';
//     } else {
//         selector = '.contenedorProyecto';
//     }

//     return selector;
// }

// /*function getProyectsOrigen(pjt_id) {
//     var respuesta= "";
//     var location = 'Dashboard/getProjectOrigen';
//     $.ajax({
//        type: 'POST',
//        dataType: 'JSON',
//        async: false,
//        data: {
//             pjt_id: pjt_id,
//        },
//        url: location,
//        success: function (resp) {
//             //console.log(resp); 
//             respuesta = resp;
//        },
//        error: function (EX) {
//           //console.log(EX);
//        },
//     }).done(function () {});


//     //console.log(respuesta); 
//     return respuesta;
// }*/


// function settingProjects(){
    
//     $('#ProjectsData').removeClass('overlay_hide');
//     $('#tblProjects').DataTable({
//         order: [[1, 'asc']],
//         dom: 'Blfrtip',
//         bDestroy: true,
//         lengthMenu: [
//             [200, 400, -1],
//             [200, 400, 'Todos'],
//         ],
//         buttons: [
//             {
//                 // Boton aplicar cambios
//                 text: 'Reporte',
//                 className: 'btn-apply ',
//                 action: function (e, dt, node, config) {
//                     getReportProjects(tc);
//                 },
//             },
//         ],
//         pagingType: 'simple_numbers',
//         language: {
//             url: 'app/assets/lib/dataTable/spanish.json',
//         },
//         scrollY: 'calc(100vh - 290px)',
//         scrollX: true,
//         fixedHeader: true,
//         columns: [
//             {data: 'prjname', class: 'edit'},
//             {data: 'dtstart', class: 'sku left'},
//             {data: 'dtend', class: 'supply left'},
//             {data: 'pjtname', class: 'sku left'},
//             {data: 'analyst', class: 'supply left'},
//         ],
//     });

// }
// function getReportProjects(){
//     let user = Cookies.get('user').split('|');
//     let u = user[0];
//     let n = user[2];
//     let h = localStorage.getItem('host');
    
//     window.open(
//         `${url}app/views/Dashboard/DashboardReport.php?p=1&u=${u}&n=${n}&h=${h}`,
//         '_blank'
//     ); 
    

//     $('#ProjectsData').addClass('overlay_hide');
// }
// function getReportProducts(){
//     let user = Cookies.get('user').split('|');
//     let u = user[0];
//     let n = user[2];
//     let h = localStorage.getItem('host');
    
//     window.open(
//         `${url}app/views/Dashboard/DashboardReportProduct.php?p=1&u=${u}&n=${n}&h=${h}`,
//         '_blank'
//     ); 

//     $('#ProjectsData').addClass('overlay_hide');
// }
// function getReportCamaras(tc){
//     let user = Cookies.get('user').split('|');
//     let u = user[0];
//     let n = user[2];
//     let h = localStorage.getItem('host');
//     if(tc == 'C' || tc == 'I'){
//         window.open(
//             `${url}app/views/Dashboard/DashboardReportCamaras.php?p=${st}&u=${u}&n=${n}&h=${h}`,
//             '_blank'
//         );
//     }else{
//         window.open(
//             `${url}app/views/Dashboard/DashboardReportProdCat.php?p=${st}&u=${u}&n=${n}&h=${h}`,
//             '_blank'
//         );
//     }
     

//     $('#ProjectsData').addClass('overlay_hide');
// }
// function settingCamaras(){
    
//     $('#CamarasData').removeClass('overlay_hide');
//     $('#tblCamaras').DataTable({
//         order: [[1, 'asc']],
//         dom: 'Blfrtip',
//         bDestroy: true,
//         lengthMenu: [
//             [200, 400, -1],
//             [200, 400, 'Todos'],
//         ],
//         buttons: [
//             {
//                 // Boton aplicar cambios
//                 text: 'Reporte',
//                 className: 'btn-apply ',
//                 action: function (e, dt, node, config) {
//                     getReportCamaras(tc);
//                 },
//             },
//         ],
//         pagingType: 'simple_numbers',
//         language: {
//             url: 'app/assets/lib/dataTable/spanish.json',
//         },
//         scrollY: 'calc(100vh - 290px)',
//         scrollX: true,
//         fixedHeader: true,
//         columns: [
//             {data: 'cusname', class: 'edit'},
//             {data: 'email', class: 'sku left'},
//             {data: 'phone', class: 'supply left'},
//             {data: 'rfc', class: 'sku left'},
//             {data: 'address', class: 'supply left'},
//         ],
//     });

// }

// function settingProdSub(){
    
//     $('#ProductsData').removeClass('overlay_hide');
//     $('#tblProducts').DataTable({
//         order: [[1, 'asc']],
//         dom: 'Blfrtip',
//         bDestroy: true,
//         lengthMenu: [
//             [200, 400, -1],
//             [200, 400, 'Todos'],
//         ],
//         buttons: [
//             {
//                 // Boton aplicar cambios
//                 text: 'Reporte',
//                 className: 'btn-apply ',
//                 action: function (e, dt, node, config) {
//                     getReportProducts();
//                 },
//             },
//         ],
//         pagingType: 'simple_numbers',
//         language: {
//             url: 'app/assets/lib/dataTable/spanish.json',
//         },
//         scrollY: 'calc(100vh - 290px)',
//         scrollX: true,
//         fixedHeader: true,
//         columns: [
//             {data: 'prdname', class: 'edit'},
//             {data: 'price', class: 'sku left'},
//             {data: 'qty', class: 'supply left'},
//             {data: 'pjtname', class: 'sku left'},
//             {data: 'call', class: 'supply left'},
//             {data: 'analyst', class: 'supply left'},
//         ],
//     });

// }
// //Total de projectos selecciona el color
// function getColor(valor) {
//     var color = '';
//     if (valor == 0) {
//         color = '#86B42B';
//     } else if (valor == 1) {
//         color = '#FD971F';
//     } else {
//         color = '#585858';
//     }
//     return color;
// }
// /**funcion para llenar los valores de los cards */

// function fillCards(
//     contenedor,
//     titulo,
//     color,
//     valorTotal,
//     origenCamara,
//     origenExpendable,
//     origenIluminacion,
//     analista,
//     id,
//     movement
// ) {
//     var stringBotones = '';
//     var clikPointer = '';
//     if (color == '#FD971F') {
//         clikPointer = 'cursor:Pointer';
//     }

//     if (movement > 0) {
//         if (origenCamara > 0)
//             stringBotones += `<div class="col-4"><button type="button" class="btn btn-info btn-sm"  style="width: 2.3rem; padding: 0rem; font-size: .8rem;" disabled >C</button></div>`;

//         if (origenIluminacion > 0)
//             stringBotones += `<div class="col-4"><button type="button" class="btn btn-warning btn-sm" style="width: 2.3rem; padding: 0rem; font-size: .8rem;" disabled >I</button></div>`;

//         if (origenExpendable > 0)
//             stringBotones += `<div class="col-4"><button type="button" class="btn btn-success btn-sm" style="width: 2.3rem; padding: 0rem; font-size: .8rem;" disabled>E</button></div>`;
//     }
//     //console.log(destino);
//     var cardString = `<div class="card"  style="margin: 5px 10px 5px 0;">
//             <div class="card-body" style="padding: 0.35rem;">
//                 <div class="row" style="height: 4rem;">
//                     <div class="col-3" style="text-align: center;">
//                         <div class="GaugeMeter" 
//                             data-percent="100" 
//                             data-text_size="0.27" 
//                             data-animationstep =0 
//                             data-width=5	 
//                             data-showvalue="true" 
//                             id="${id}" 
//                             data-used="${valorTotal}" 
//                             data-size="65"  
//                             data-color="${color}" 
//                             style="margin: 0 10 10 0 ; width: 20px !important; ${clikPointer}">
//                         </div>
//                     </div>  
//                     <div class="col-9">
//                         <div class="row">
//                             <div class="col-12" style="margin: 0px 0px 0px 0px !important; font-size: 13px; text-align: left;"> ${titulo}</div>
//                             <div class="col-12" style="margin: 0px 0px 4px 0px !important; font-size: 10px; text-align: left; font-weight: 700; color: #5a5a5a;"> ${analista}</div>
//                             <div class="col-12">
//                                 <div class="row">${stringBotones} </div>
//                             </div>
//                         </div>
//                     </div>
//                 </div>
//             </div>
//         </div>
//         `;

//     $(contenedor).append(cardString);
//     /*if(color != "#FD971F"){
//         $(this).click(function(){
//                 cambiaEstado(this.id);
//        });
//     }*/
// }

// // Hide submenus
// $('#body-row .collapse').collapse('hide');

// // Collapse/Expand icon
// $('#collapse-icon').addClass('fa-angle-double-left');

// // Collapse click
// $('[data-toggle=sidebar-colapse]').click(function () {
//     SidebarCollapse();
// });

// function SidebarCollapse() {
//     $('.menu-collapsed').toggleClass('d-none');
//     $('.sidebar-submenu').toggleClass('d-none');
//     $('.submenu-icon').toggleClass('d-none');
//     $('#sidebar-container').toggleClass('sidebar-expanded sidebar-collapsed');

//     // Treating d-flex/d-none on separators with title
//     var SeparatorTitle = $('.sidebar-separator-title');
//     if (SeparatorTitle.hasClass('d-flex')) {
//         SeparatorTitle.removeClass('d-flex');
//     } else {
//         SeparatorTitle.addClass('d-flex');
//     }

//     // Collapse/Expand icon
//     $('#collapse-icon').toggleClass(
//         'fa-angle-double-left fa-angle-double-right'
//     );
// }
