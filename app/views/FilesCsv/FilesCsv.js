var table = null;
var positionRow = 0;
var archivo = null;

$(document).ready(function () {
   verifica_usuario();
   inicial();
});

//INICIO DE PROCESOS
function inicial() {
    settingTable();
    getDocumentosTable(); 
    bsCustomFileInput.init();
    GetTypeDocumento();

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
    //borra almacen +
    $('#BorrarProveedor').on('click', function(){    
        DeleteDocumentos();
    });  

    $('#LimpiarFormulario').on('click', function () {
      LimpiaModal();
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
//Edita el Proveedores *
function EditDocumento(id) {
    UnSelectRowTable();
    LimpiaModal();
    $('#titulo').text('Editar Documento');
    var location = "FilesCsv/GetDocumento";
    $.ajax({
            type: "POST",
            dataType: 'JSON',
            data: { id : id
             },
            url: location,
        success: function (respuesta) {
            $('#NomDocumento').val(respuesta.doc_name);
            $('#IdDocumentNew').val(respuesta.doc_id);
            $('#ExtDocumento').val(respuesta.doc_type);
            $('#CodDocumento').val(respuesta.doc_code);
            $('#fechaadmision').val(respuesta.doc_admission_date);
            GetTypeDocumento(respuesta.dot_id);
        },
        error: function (EX) {console.log(EX);}
    }).done(function () {});
}

//Guardar Almacen **
function SaveDocumento() {
   var location = "FilesCsv/SaveDocumento";
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
           if (respuesta.rechazados > 0) {
                $('#estatus').text('Revisa que el sku, el codigo de la moneda, el precio, el servicio y el seguro esten correctamente');
           }
           $('#MoveFolioModal').modal('show');
            $('#btnHideModal').on('click', function () {
                $('#MoveFolioModal').modal('hide');

            });
       },
       error: function (EX) {console.log(EX);}
       }).done(function () {}); 
   }else {
       $('#filtroDocumentoModal').modal('show');
   }
} 

//confirm para borrar **
function ConfirmDeletDocumento(id) {
    //UnSelectRowTable();
    $('#BorrarDocumentosModal').modal('show');
    $('#IdDocumentoBorrar').val(id);
}

function UnSelectRowTable() {
    setTimeout(() => {table.rows().deselect();}, 10);
}

//BORRAR  * *
function DeleteDocumentos() {
    var location = "FilesCsv/DeleteDocumentos";
    IdDocumento = $('#IdDocumentoBorrar').val();
    //console.log(IdDocumento);
    $.ajax({
            type: "POST",
            dataType: 'JSON',
            data: { 
                    IdDocumento : IdDocumento
             },
            url: location,
        success: function (respuesta) {

            if ((respuesta = 1)) {
               var arrayObJ = IdDocumento.split(',');
               if(arrayObJ.length == 1){
                  table.row(':eq('+positionRow+')').remove().draw();
               }else{
                  table.rows({ selected: true }).remove().draw();
               }
               $('#BorrarDocumentosModal').modal('hide');
            }
            LimpiaModal();
        },
        error: function (EX) {console.log(EX);}
        }).done(function () {});
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
    GetTypeDocumento();
}

//ver Documentos
function VerDocumento(id) {
    var location = "FilesCsv/VerDocumento";
    $.ajax({
        type: "POST",
        dataType: 'JSON',
        data: {
            id: id
        },
        url: location,
        success: function (respuesta) {
            //console.log(respuesta.doc_type);
            var a = document.createElement('a');
            a.href= "data:application/octet-stream;base64,"+respuesta.doc_document;
            a.target = '_blank';
           // a.download = respuesta.doc_name;

            //a.download = respuesta.doc_name + "."+ respuesta.doc_type.trim();
            a.download = respuesta.doc_name;
            a.click();
        },
        error: function (jqXHR, textStatus, errorThrown){
            console.log( jqXHR, textStatus, errorThrown);
        }
    }).done(function () { });
} 

//obtiene la informacion de tabla Proveedores *
function getDocumentosTable() {
   var pagina = 'FilesCsv/GetDocumentos';
   var par = `[{"dot_id":""}]`;
   var tipo = 'json';
   var selector = putFiles;
   fillField(pagina, par, tipo, selector);
}


function putFiles(dt) {
   console.log(dt);
   pd = dt;
   let largo = $('#DocumentosTable tbody tr td').html();
   largo == 'Ningún dato disponible en esta tabla'
       ? $('#DocumentosTable tbody tr').remove()
       : '';
   tabla =  $('#DocumentosTable').DataTable();
   
   tabla.rows().remove().draw();
   let cn = 0;
   if(dt[0].prd_id !=0){
       $.each(pd, function (v, u) {
           tabla.row
               .add({
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
// Optiene los tipos de documentos
function GetTypeDocumento(id) {
    $('#selectRowTipoDocumento').html("");
    var location = 'FilesCsv/GetTypeDocumento';
    $.ajax({
       type: 'POST',
       dataType: 'JSON',
       data: {id: id},
       url: location,
       success: function (respuesta) {
          var renglon = "<option id='0'  value=''>Seleccione...</option> ";
          respuesta.forEach(function (row, index) {
             renglon += '<option id=' + row.dot_id + '  value="' + row.dot_id + '">' + row.dot_name + '</option> ';
          });
          $('#selectRowTipoDocumento').append(renglon);
          if (id != undefined) {
             $("#selectRowTipoDocumento option[value='" + id + "']").attr('selected', 'selected');
          }
       },
       error: function () {},
    }).done(function () {});
 }

