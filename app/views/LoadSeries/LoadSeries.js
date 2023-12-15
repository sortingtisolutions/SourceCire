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
    activeButtons();

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
function activeButtons(){
    setTimeout(() => {
        if (datos[0].ser_id > 0) {
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
        {data: 'Sku', class: 'Sku'},
        {data: 'NumeroSerie', class: 'NumeroSerie'},
        {data: 'Costo', class: 'Costo'},
        {data: 'FechaRegistro', class: 'FechaRegistro'},
        {data: 'FechaBaja', class: 'FechaBaja'},
        {data: 'Marca', class: 'Marca'},
        {data: 'NumeroImportacion', class: 'NumeroImportacion'},
        {data: 'CostoImportacion', class: 'CostoImportacion'},
        {data: 'CostoTotal', class: 'CostoTotal'},
        {data: 'NumeroEconomico', class: 'NumeroEconomico'},
        {data: 'Comentarios', class: 'Comentarios'},
        {data: 'Moneda', class: 'Moneda'},
        {data: 'Almacen', class: 'Almacen'},
        {data: 'Proveedor', class: 'Proveedor'},
       ],
   });
}

//Guardar Almacen **
function SaveDocumento() {
   var location = "LoadSeries/SaveDocumento";
   var NomDocumento = $('#NomDocumento').val();
   var ExtDocumento = $('#ExtDocumento').val();
   var data = new FormData();
   data.append("file",archivo);
   data.append("NomDocumento", NomDocumento);
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
            
            modalLoading('H');
            showResults();
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
    var pagina = 'LoadSeries/listResults';
    var par = `[{"prod_id":""}]`;
    var tipo = 'json';
    var selector = put_results;
    fillField(pagina, par, tipo, selector);
}
function put_results(dt) {
    console.log(dt);
    if (dt[0].results > 0) {
        if (dt[0].duplicidad > 0) {
            $('#duplicidad').text('Por duplicidad en sku: '+ dt[0].duplicidad);
        }
        if (dt[0].SKU > 0) {
            $('#sku').text('Por problemas con el sku: '+  dt[0].SKU);
        }
        if (dt[0].costo > 0) {
            $('#costo').text('Por problemas con costo: '+ dt[0].costo);
        }
        if (dt[0].moneda > 0) {
            $('#moneda').text('Por problemas con moneda: '+ dt[0].moneda);
        }
        if (dt[0].almacen > 0) {
            $('#almacen').text('Por problemas con almacen: '+ dt[0].almacen);
        }
        if (dt[0].proveedor > 0) {
            $('#proveedor').text('Por problemas con proveedor: '+ dt[0].proveedor);
        }
    }
    
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

//ver Documentos
function VerDocumento(id) {
    var location = "LoadSeries/VerDocumento";
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
   var pagina = 'LoadSeries/GetDocumentos';
   var par = `[{"dot_id":""}]`;
   var tipo = 'json';
   var selector = putFiles;
   fillField(pagina, par, tipo, selector);
  
}

//Envia los datos almacenados a la tabla de productos *
function loadProcess() {
    $('#confirmarCargaModal').modal('show');
    
    $('#confirmLoad').on('click', function () {
        modalLoading('S');
        console.log('subir datos');
        var pagina = 'LoadSeries/loadProcess';
        var par = `[{"dot_id":""}]`;
        var tipo = 'json';
        var selector = putFiles;
        fillField(pagina, par, tipo, selector); 
        $('#confirmarCargaModal').modal('hide');
        activeButtons();
        setTimeout(() => {
            modalLoading('H');
        }, 100);
    });
 }
 function eliminarDatos(){
    $('#BorrarDocumentosModal').modal('show');

    $('#BorrarProveedor').on('click', function () {
        var pagina = 'LoadSeries/DeleteData';
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
   if(dt[0].ser_id > 0){
        
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
                   Sku: u.ser_sku,
                   NumeroSerie: u.ser_serial_number,
                   Costo: u.ser_cost,
                   FechaRegistro: u.ser_date_registry,

                   FechaBaja: u.ser_date_down,
                   Marca: u.ser_brand,
                   NumeroImportacion: u.ser_import_petition,
                   CostoImportacion: u.ser_cost_import,
                   CostoTotal: u.ser_sum_ctot_cimp,
                   
                   NumeroEconomico: u.ser_no_econo,
                   Comentarios: u.ser_comments,
                   Moneda: u.cin_code,
                   Almacen: u.sup_business_name,
                   Proveedor: u.str_name
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