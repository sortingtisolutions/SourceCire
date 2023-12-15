
let strnme = '';
let array = [];

let i =0;
let colores = ["#CD6155", "#AF7AC5", "#EC7063", "#5499C7", "#48C9B0", "#34495E", "#EB984E"];
$(document).ready(function () {
    if (verifica_usuario()) {
        inicial();
        
    }
});

    

function inicial() {
    if (altr == 1) {
        
        /* settingTable();
        getStores();
        fillStores();
        confirm_alert(); */
        //getEvents();
        get_Proyectos();
        calendario('');
        setTimeout(() => {
        
    }, 800);
          
    } else {
        setTimeout(() => {
            inicial();
        }, 100);
    }
}
function calendario(cal){
    var calendarEl = document.getElementById('calendarPrueba');
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
        height: 560,
        eventClick: function(calEvent, jsEvent, view){
            console.log(calEvent);
        }
    }); 
    calendar.render();
}

//CONFIGURACION DE DATATABLE
function settingTable() {
    let title = 'Lista de Areas Operativas de la Empresa';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    $('#AreasTable').DataTable({
        order: [[1, 'asc']],
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
                //Botón para descargar PDF
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
                text: 'Borrar seleccionados',
                
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
            {data: 'editable', class: 'edit', orderable: false},
            {data: 'storesid', class: 'strid bold'},
            {data: 'storname', class: 'store-name'},
        ],
    });
}

// Solicita los productos de un almacen seleccionado
function getEvents(pjtId) {
    var pagina = 'CalendarProyects/GetEventos';
    var par = `[{"pjt_id":"${pjtId}"}]`;
    var tipo = 'json';
    var selector = putEvents;
    fillField(pagina, par, tipo, selector);
}
function get_Proyectos() {
    var pagina = 'CalendarProyects/listProyects';
    var par = `[{"store":""}]`;
    var tipo = 'json';
    var selector = put_Proyectos;
    fillField(pagina, par, tipo, selector);
}
function putEvents(dt) {
    //strs = dt;
    dt.forEach(element => {
        let x = Math.floor(Math.random()*colores.length);
        array[i]={"id": element.id, "title": element.title, "start": element.start, "end": element.end,"color" : colores[x]};
       
    });
    i++;
    calendario(array);
}
/**  ++++   Coloca los proyectos en el listado del input */
function put_Proyectos(dt) {
    pj = dt;
    //console.log(pj);
    if (dt[0].pjt_id > 0) {
        $.each(dt, function (v, u) {
            // let H = `<option data_indx="${v}" value="${u.pjt_id}">${u.pjt_name}</option>`;
            let H = `<div class="" style="display:flex; gap: 0.3rem; margin-bottom: 5px;">
                    <div style=""><input class="form-check-input check-box-prj" type="checkbox" value="${u.pjt_id}" id="checkProjects" >
                    </div>
                    <div class="totales__grupo-label" style="text-align: left;padding-left: 5px;">${u.pjt_name}</div>
                </div>`;
            $('#txtProject').append(H);
        });
        $('input[type="checkbox"]').on('change', function(){
            let aux = 0;
            //getEvents($(this).val());
            if (this.checked) {
                getEvents($(this).val());
            }else{
                for (let j = 0; j < array.length; j++) {
                    if (array[j].id == $(this).val()) {
                        aux= j;
                    }
                }
                array.splice(aux,1);
                i--;
                calendario(array);
            } 

            /* $('input[type="checkbox"]:checked').each(function(){
                prjs = prjs + "," +$(this).val() ;
            }); */
            
            //console.log(this.checked,array);
        }); 
        /* $('#txtProject').on('change', function () {
            px = parseInt($('#txtProject option:selected').attr('data_indx'));
            $('#txtIdProject').val($(this).val());
            // let period = pj[px].pjt_date_start + ' - ' + pj[px].pjt_date_end;
            //$('.objet').addClass('objHidden');
            //get_products($(this).val(), em);
            console.log('Value',$(this).val());
            getEvents($(this).val());
        }); */

    }
    
}
function fillStores() {
    if (strs != null) {
        let tabla = $('#AreasTable').DataTable();
        $.each(strs, function (v, u) {
            fillTableStores(v);
        });
        
    } else {
        setTimeout(() => {
            fillStores();
        }, 100);
    }
}

function actionButtons() {
    /**  ---- Acciones de edición ----- */
    $('td.edit i')
        .unbind('click')
        .on('click', function () {
            let acc = $(this).attr('class').split(' ')[2];
            let strId = $(this).parents('tr').attr('id');

            switch (acc) {
                case 'modif':
                    editStore(strId);
                    break;
                case 'kill':
                    deleteStore(strId);
                    break;
                default:
            }
        });
    /**  ---- Presenta series relacionadas al almacen ----- */
    $('.toLink')
        .unbind('click')
        .on('click', function () {
            let strId = $(this).parents('tr').attr('id');
            let quant = $(this).html();
            let ctnme = $(this).parents('tr').children('td.store-name').html();
            strnme = ctnme;
            // console.log(strId, quant, ctnme);
            if (quant > 0) {
                setting_modalseries(strId);
            }
        });

    /**  ---- Acciones de Guardar categoria ----- */
    $('#GuardarAlmacen')
        .unbind('click')
        .on('click', function () {
            if (validaFormulario() == 1) {
                if ($('#IdAlmacen').val() == '') {
                    saveStore();
                } else {
                    updateStore();
                }
            }
        });
    /**  ---- Lismpia los campos ----- */
    $('#LimpiarFormulario')
        .unbind('click')
        .on('click', function () {
            $('#NomAlmacen').val('');
            $('#IdAlmacen').val('');
            $('#selectTipoAlmacen option[value="0"]').attr('selected', true);
            $('#selectRowEncargado').val('');
        });
}

function fillTableStores(ix) {
    let tabla = $('#AreasTable').DataTable();
    // console.log(strs.length);
    tabla.row
        .add({
            editable: `<i class="fas fa-pen modif" id ="md${strs[ix].are_id}"></i><i class="fas fa-times-circle kill"></i>`,
            storesid: strs[ix].are_id,
            storname: strs[ix].are_name,
        })
        .draw();
    $('#md' + strs[ix].are_id)
        .parents('tr')
        .attr('id', strs[ix].are_id);
    actionButtons();
}

function saveStore() {
    var strName = $('#NomAlmacen').val();
    var strtype = $('#selectTipoAlmacen').val();
    var par = `
        [{  "are_name"   : "${strName}",
            "are_status"   : "${strtype}"
        }]`;

    strs = '';
    var pagina = 'CalendarProyects/SaveArea';
    var tipo = 'html';
    var selector = putSaveStore;
    fillField(pagina, par, tipo, selector);
}
function putSaveStore(dt) {
    getStores();
    if (strs.length > 0) {
        let ix = goThroughStore(dt);
        fillTableStores(ix);
        $('#LimpiarFormulario').trigger('click');
    } else {
        setTimeout(() => {
            putSaveStore(dt);
        }, 100);
    }
}

function updateStore() {
    var strId = $('#IdAlmacen').val();
    var strName = $('#NomAlmacen').val();
    var strType = $('#selectTipoAlmacen option:selected').val();
    var par = `
        [{  "are_id"        : "${strId}",
            "are_name"      : "${strName}",
            "are_status"      : "${strType}"
        }]`;

    strs = '';
    var pagina = 'CalendarProyects/UpdateArea';
    var tipo = 'html';
    var selector = putUpdateStore;
    fillField(pagina, par, tipo, selector);
}
function putUpdateStore(dt) {
    getStores();
    if (strs.length > 0) {
        console.log(dt);
        let ix = goThroughStore(dt);

        $(`#${strs[ix].are_id}`).children('td.store-name').html(strs[ix].are_name);
        $(`#${strs[ix].are_id}`).children('td.store-type').html(strs[ix].are_status);

        //putQuantity(strs[ix].are_id);
        $('#LimpiarFormulario').trigger('click');
    } else {
        setTimeout(() => {
            putUpdateStore(dt);
        }, 100);
    }
}

function editStore(strId) {
    console.log('Editando');
    let ix = goThroughStore(strId);
    $('#NomAlmacen').val(strs[ix].are_name);
    $('#IdAlmacen').val(strs[ix].are_id);
    $('#selectTipoAlmacen').val(strs[ix].are_status);
}

function deleteStore(strId) {
    console.log(strId);
    let cn = $(`#${strId}`).children('td.quantity').children('.toLink').html();

    
        $('#confirmModal').modal('show');

        $('#confirmModalLevel').html('¿Seguro que desea borrar el area?');
        $('#N').html('Cancelar');
        $('#confirmButton').html('Borrar almacen').css({display: 'inline'});
        $('#Id').val(strId);

        
        $('#IdAlmacenBorrar').val(strId);

        $('#confirmButton').on('click', function () {
            var pagina = 'CalendarProyects/DeleteArea';
            var par = `[{"are_id":"${strId}"}]`;
            var tipo = 'html';
            var selector = putDeleteStore;
            fillField(pagina, par, tipo, selector);
        });
    
}

function putDeleteStore(dt) {
    getStores();
    let tabla = $('#AreasTable').DataTable();
    tabla
        .row($(`#${dt}`))
        .remove()
        .draw();
    $('#confirmModal').modal('hide');
}

function goThroughStore(strId) {
    let inx = -1;
    $.each(strs, function (v, u) {
        if (strId == u.are_id) inx = v;
    });
    return inx;
}

//Valida los campos seleccionado *
function validaFormulario() {
    var valor = 1;
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function (form) {
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            valor = 0;
        }
    });
    return valor;
}


/// CALENDARIO ///
