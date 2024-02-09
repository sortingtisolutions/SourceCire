var table = null;
var positionRow = 0;
var archivo = null;
let datos;
$(document).ready(function () {
   verifica_usuario();
   inicial();
});

//INICIO DE PROCESOS
function inicial() {
    // settingTable();
    getDocumentosTable(); 
    bsCustomFileInput.init();
    //
    // activeButtons();

    $("#cargaFiles").change(function () {
        archivo = this.files[0];
        filename = this.files[0].name;
        //var arrayName = filename.split(".");
        $('#NomDocumento').val(filename.split('.').slice(0, -1).join('.'));
        var extenArchivo =  filename.split('.').pop().toLowerCase();
        $('#ExtDocumento').val(extenArchivo);
    });

    //Open modal *
    $('#nuevaCategoria').on('click', function(){    
        LimpiaModal();
    });
    //Guardar almacen *
    $('#GuardarDocumento').on('click', function(){   
        if(validaFormulario() == 1){
            SaveDocumento();        
        }
    });

    $('#GuardarProcess').on('click', function(){   
        loadProcess();
    });   

    $('#LimpiarFormulario').on('click', function () {
      LimpiaModal();
   });

   $('#LimpiarTabla').on('click', function () {
        eliminarDatos();
        
        $('#verMotivo').addClass('objHidden');
    });

    $('#DescargarEjemplo').on('click', function(){   
        VerDocumento();
    });
    
}

function VerDocumento() {
    $.ajax({
        url: 'app/assets/csv_ejemplos/productos.csv',
        method: 'GET',
        dataType: 'text',
        success: function(data) {
            // Generar la descarga del archivo CSV
            var blob = new Blob([data], { type: 'text/csv' });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'load_products.csv';
            link.click();
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener el archivo CSV:', status, error);
        }
    });
} 
function activeButtons(){
    setTimeout(() => {
        //console.log(datos);
        if (datos[0].prd_id > 0) {
            $('#GuardarProcess').parent().removeClass('objHidden');
            $('#LimpiarTabla').parent().removeClass('objHidden');
            $('#GuardarDocumento').parent().addClass('objHidden');
            $('#LimpiarFormulario').parent().addClass('objHidden');
        }else{
            $('#GuardarProcess').parent().addClass('objHidden');
            $('#LimpiarTabla').parent().addClass('objHidden');
            $('#GuardarDocumento').parent().removeClass('objHidden');
            $('#LimpiarFormulario').parent().removeClass('objHidden');
        }
    }, 200);
    
}
//Valida los campos seleccionado *
function validaFormulario() {
    var valor = 1;
    var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                if (!form.checkValidity()) {
                    form.classList.add('was-validated')
                    valor = 0;
                }
        })
    return valor;
}

function settingTable() {
   let title = 'Lista de Tipos de Documentos';
   let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
   $('#DocumentosTable').DataTable({
        bDestroy: true,
       order: [[1, 'asc']],
       dom: 'Blfrtip',
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
       ],
       pagingType: 'simple_numbers',
        language: {
            url: 'app/assets/lib/dataTable/spanish.json',
        },
        scrollY: 'calc(100vh - 200px)',
        scrollX: true,
        fixedHeader: true,
       columns: [
            {data: 'result', class: 'result'},
            {data: 'error', class: 'Producto'},
           {data: 'SKU', class: 'SKU bold'},
           {data: 'Producto', class: 'Producto'},
           {data: 'NombreIngles', class: 'NombreIngles'},
           {data: 'CodigoProveedor', class: 'CodigoProveedor'},
           {data: 'NombreProveedor', class: 'NombreProveedor'},
           {data: 'Modelo', class: 'Modelo'},
           {data: 'Precio', class: 'Precio'},
           {data: 'Moneda', class: 'Moneda'},
           {data: 'Seguro', class: 'Seguro'},
           {data: 'Servicio', class: 'Servicio'},
       ],
   });
}

function settingTableErrores() {
    let title = 'Lista de Tipos de Documentos';
    let filename = title.replace(/ /g, '_') + '-' + moment(Date()).format('YYYYMMDD');
    $('#tblMotivos').DataTable({
         bDestroy: true,
        order: [[1, 'asc']],
        dom: 'Blfrtip',
        lengthMenu: [
            [500, 1000, -1],
            [500, 1000, 'Todos'],
        ],
        buttons: [
        ],
        pagingType: 'simple_numbers',
        language: {
            url: 'app/assets/lib/dataTable/spanish.json',
        },
        scrollY: 'calc(100vh - 200px)',
        scrollX: true,
        fixedHeader: true,
        columns: [
             {data: 'code', class: 'result'},
             {data: 'nameError', class: 'Producto'},
        ],
    });
 }

//Guardar Almacen **
function SaveDocumento() {
   var location = "LoadProducts/SaveDocumento";
   var idDocumentos = $('#IdDocumentNew').val();
   var NomDocumento = $('#NomDocumento').val();
   var ExtDocumento = $('#ExtDocumento').val();
   /* var CodDocumento = $('#CodDocumento').val().trim().toUpperCase(); */
   var Fechaadmision = $('#fechaadmision').val();
   var tipoDocumento = $('#selectRowTipoDocumento option:selected').attr('id');
   var tipoDocumentoText = $('#selectRowTipoDocumento option:selected').text();
  
   var data = new FormData();
   data.append("file",archivo);
   data.append("NomDocumento", NomDocumento);
   data.append("Ext", ExtDocumento);
   data.append("idDocumento", idDocumentos);
   /* data.append("CodDocumento", CodDocumento); */
   data.append("tipoDocumento", tipoDocumento);
   data.append("fechaadmision",Fechaadmision);
   modalLoading('S');
   if(ExtDocumento == "csv"){
       $.ajax({
           type: "POST",
           dataType: 'JSON',
           enctype: 'multipart/form-data',
           cache: false,
           contentType: false,
           processData: false,
           data:data,
           url: location,
       success: function (respuesta) {
          // console.log(respuesta);
          
           getDocumentosTable();
           $('#aceptados').text(respuesta.aceptados);
           $('#rechazados').text(respuesta.rechazados);
           /* if (respuesta.rechazados > 0) {
                $('#estatus').text('Revisa que el sku, el codigo de la moneda, el precio, el servicio y el seguro esten correctamente');
           } */
           
           showResults();
           modalLoading('H');
           $('#MoveFolioModal').modal('show');
            $('#btnHideModal').on('click', function () {
                $('#MoveFolioModal').modal('hide');
                LimpiaModal();
            });
            activeButtons();
            
       },
       error: function (EX) {console.log(EX);}
       }).done(function () {}); 
   }else {
       $('#filtroDocumentoModal').modal('show');
   }
} 
function limpiarResults(){
    $('#duplicidad').text('');
    $('#sku').text('');
    $('#moneda').text('');
    $('#costo').text('');
    $('#servicio').text('');
    $('#seguro').text('');
    $('#categories').text('');
}
function showResults(){
    var pagina = 'LoadProducts/listResults';
    var par = `[{"prod_id":""}]`;
    var tipo = 'json';
    var selector = put_results;
    fillField(pagina, par, tipo, selector);
}
/**  ++++    */
function put_results(dt) {
    //console.log(dt);
    limpiarResults();
    if (dt[0].results > 0) {
        if (dt[0].SKU > 0) {
            if (dt[0].duplicidad > 0) {
                $('#duplicidad').text('Por duplicidad en sku: '+ dt[0].duplicidad);
            }else{
                $('#sku').text('Por problemas con el sku: '+  dt[0].SKU);
            }
        }
        if (dt[0].moneda > 0) {
            $('#moneda').text('Por problemas con moneda: '+ dt[0].moneda);
        }
        if (dt[0].costo > 0) {
            $('#costo').text('Por problemas con costo: '+ dt[0].costo);
        }
        if (dt[0].servicio > 0) {
            $('#servicio').text('Por problemas con servicio: '+ dt[0].servicio);
        }
        if (dt[0].seguro > 0) {
            $('#seguro').text('Por problemas con seguro: '+ dt[0].seguro);
        }
        if (dt[0].categoria) {
            $('#categories').text('Por problemas con la subcategoria/categoria: '+ dt[0].categoria);
        }
    }
}

function UnSelectRowTable() {
    setTimeout(() => {table.rows().deselect();}, 10);
}


//Limpia datos en modal  **
function LimpiaModal() {
    $('#cargaFiles').val('');
    $('#IdDocumentNew').val("");
    $('#NomDocumento').val("");
    $('#IdDocumento').val("");
    $('#CodDocumento').val("");
    $('#fechaadmision').val('');
    $('#formDocumento').removeClass('was-validated');
    $('#titulo').text('Nuevo Documento');
}

//obtiene la informacion de tabla documentos *
function getDocumentosTable() {
    
   var pagina = 'LoadProducts/GetDocumentos';
   var par = `[{"dot_id":""}]`;
   var tipo = 'json';
   var selector = putFiles;
   fillField(pagina, par, tipo, selector);
}
//Envia los datos almacenados a la tabla de productos *
function loadProcess() {
    $('#confirmarCargaModal').modal('show');
    $('#confirmLoad')
    .unbind('click').on('click', function () {
        //modalLoading('S');
        var pagina = 'LoadProducts/loadProcess';
        var par = `[{"dot_id":""}]`;
        var tipo = 'json';
        var selector = put_load_process;
        fillField(pagina, par, tipo, selector); 
        // activeButtons();
        window.location.reload();
        $('#confirmarCargaModal').modal('hide');
        /* setTimeout(() => {
            modalLoading('H');
        }, 100); */
    });
 }

 function put_load_process(dt){
    console.log(dt);
    //getDocumentosTable();
 }
 function eliminarDatos(){
    $('#BorrarDocumentosModal').modal('show');

    $('#BorrarProveedor').unbind('click').on('click', function () {
        var pagina = 'LoadProducts/DeleteData';
        var par = `[{"ass_id":""}]`;
        var tipo = 'html';
        var selector = put_load_process;
        fillField(pagina, par, tipo, selector); 
        //console.log('eliminar');
        $('#BorrarDocumentosModal').modal('hide');
        window.location.reload();
        //getDocumentosTable();
    });
 }
function putFiles(dt) {
   console.log(dt);
   modalLoading('S');
   pd = dt;
   datos = dt;
   $('#DocumentosTable tbody').html('');

   if(dt[0].prd_id > 0){
       $.each(pd, function (v, u) {
            let icon;
            if (u.result == 'EXITOSO') {
                icon = "fas fa-check-circle";
                valstage='color:#008000';
            } else {
                icon = "fas fa-exclamation-circle";
                valstage='color:#CC0000';
            }
               let H = `
                <tr>
                    <td><i class="${icon} showMerror" style="${valstage}"></i></td>
                    <td>${u.result}</td>
                    <td>${u.prd_sku}</td>
                    <td>${u.prd_name}</td>
                    <td>${u.prd_english_name}</td>
                    <td>${u.prd_code_provider}</td>
                    <td>${u.prd_name_provider}</td>
                    <td>${u.prd_model}</td>
                    <td>${u.prd_price}</td>
                    <td>${u.cin_code}</td>
                    <td>${u.prd_insured}</td>
                    <td>${u.srv_id}</td>
                </tr>
            `;
            $('#DocumentosTable tbody').append(H);
           
       });
       
    settingTable();
    
   }else{
    //settingTable();
   }
   activarBoton();
   activeButtons();
   modalLoading('H');
}

function activarBoton(){
    $('.showMerror')
    .unbind('click')
    .on('click', function(){
        var tr = $(this).closest('tr');
        var errores = tr.find('td').eq(1).text().split(',');
        var motivosError = 0;
        settingTableErrores();
        $.each(errores, function(v,u){
            if(u > 0){
                motivosError = motivosError + ',' + u;
            }
            if (u == 'EXITOSO') {
                motivosError = 0;
            }
        });
        if (motivosError != 0 || motivosError != '') {
            getErrores(motivosError);
            $('#MotivosModal').removeClass('overlay_hide');
            $('#MotivosModal .btn_close')
            .unbind('click')
            .on('click', function () {
                $('.overlay_background').addClass('overlay_hide');
            }); 
        }
        
    });
}

function getErrores(errores){
    console.log(errores);
    var pagina = 'LoadProducts/listErrores';
    var par = `[{"errores":"${errores}"}]`;
    var tipo = 'json';
    var selector = put_errores;
    fillField(pagina, par, tipo, selector);
}
function put_errores(dt){
    
    let tabla = $('#tblMotivos').DataTable();
    $('.overlay_closer .title').html('MOTIVOS DE ERROR');
    tabla.rows().remove().draw();
    if(dt[0].erm_id > 0){
        $.each(dt, function (v, u) {
            tabla.row
            .add({
                code: u.erm_id,
                nameError: u.erm_title,
            })
            .draw();
        });
    }
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


