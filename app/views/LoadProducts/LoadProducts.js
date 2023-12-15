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
    settingTable();
    getDocumentosTable(); 
    bsCustomFileInput.init();
    //
    activeButtons();

    $("#cargaFiles").change(function () {
        archivo = this.files[0];
        filename = this.files[0].name;
        //var arrayName = filename.split(".");
        $('#NomDocumento').val(filename.split('.').slice(0, -1).join('.'));
        var extenArchivo =  filename.split('.').pop().toLowerCase();
        $('#ExtDocumento').val(extenArchivo);

        /* if(extenArchivo == "jpg" || extenArchivo == "pdf" || extenArchivo == "png"){
        }else{
            $('#filtroDocumentoModal').modal('show');
            $('#cargaFiles').val('');
        } */

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
    //borra almacen +
    

    $('#LimpiarFormulario').on('click', function () {
      LimpiaModal();
   });
   $('#LimpiarTabla').on('click', function () {
        eliminarDatos();
       
    });

    $('#DocumentosTable tbody').on('click', 'tr', function () {
      positionRow = (table.page.info().page * table.page.info().length) + $(this).index();

      setTimeout(() => {
         RenglonesSelection = table.rows({ selected: true }).count();
         if (RenglonesSelection == 0 || RenglonesSelection == 1) {
            $('.btn-apply').addClass('hidden-field');
         } else {
            $('.btn-apply').removeClass('hidden-field');
         }
     }, 10);
   });
}

function activeButtons(){
    setTimeout(() => {
        console.log(datos);
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
       order: [[1, 'asc']],
       dom: 'Blfrtip',
       lengthMenu: [
           [500, 1000, -1],
           [500, 1000, 'Todos'],
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
            {data: 'result', class: 'result'},
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
          console.log(respuesta);
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
function showResults(){
    var pagina = 'LoadProducts/listResults';
    var par = `[{"prod_id":""}]`;
    var tipo = 'json';
    var selector = put_results;
    fillField(pagina, par, tipo, selector);
}
/**  ++++    */
function put_results(dt) {
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
    $('#confirmLoad').on('click', function () {
        console.log('subir datos');
        modalLoading('S');
        var pagina = 'LoadProducts/loadProcess';
        var par = `[{"dot_id":""}]`;
        var tipo = 'json';
        var selector = putFiles;
        fillField(pagina, par, tipo, selector); 
        
        activeButtons();
        $('#confirmarCargaModal').modal('hide');
        setTimeout(() => {
            modalLoading('H');
        }, 100);
    });
 }
 function eliminarDatos(){
    $('#BorrarDocumentosModal').modal('show');

    $('#BorrarProveedor').on('click', function () {
        var pagina = 'LoadProducts/DeleteData';
        var par = `[{"ass_id":""}]`;
        var tipo = 'html';
        var selector = putFiles;
        fillField(pagina, par, tipo, selector); 
        console.log('eliminar');
        $('#BorrarDocumentosModal').modal('hide');
        activeButtons();
        
    });
 }

function putFiles(dt) {
   console.log(dt);
   pd = dt;
   datos = dt;
   let largo = $('#DocumentosTable tbody tr td').html();
   largo == 'Ningún dato disponible en esta tabla'
       ? $('#DocumentosTable tbody tr').remove()
       : '';
   tabla =  $('#DocumentosTable').DataTable();
   
   tabla.rows().remove().draw();
   let cn = 0;
   if(dt[0].prd_id > 0){
    
       $.each(pd, function (v, u) {
            let icon;
            if (u.result == 'EXITOSO') {
                icon = "fas fa-check-circle";
                valstage='color:#008000';
            } else {
                icon = "fas fa-times-circle";
                valstage='color:#CC0000';
            }
           tabla.row
               .add({
                   result: `<i class="${icon}" style="${valstage}"></i>`,
                   SKU: u.prd_sku,
                   Producto: u.prd_name,
                   NombreIngles: u.prd_english_name,
                   CodigoProveedor: u.prd_code_provider,
                   NombreProveedor: u.prd_name_provider,
                   Modelo: u.prd_model,
                   Precio: u.prd_price,
                   Moneda: u.cin_code,
                   Seguro: u.prd_insured,
                   Servicio: u.srv_id
               })
               .draw();
           cn++;
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


