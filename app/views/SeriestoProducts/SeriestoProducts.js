let subcategos, proddatos;
let products;
var productoSelectId = 0;
var productoSelectSKU = '';
var prd_id = 0;
var accesorioExist = 0;
var lsbc_id = 0;

var accesorioSkuNew = '';

$(document).ready(function () {
    if (verifica_usuario()) {
        inicial();
    }
});
//INICIO DE PROCESOS
function inicial() {
    setting_table_product();
    setting_table_accesorys();
    getCategory();
    getCategoryAcc();
    getSubcategory();

    $('#RadioConceptos1')
    .unbind('change')
    .on('change', function () {
        clean();
    });

    $('#RadioConceptos2')
    .unbind('change')
    .on('change', function () {
        clean();
    });
}

function clean(){
    $('#txtCategoryProd').val(0);
    $('#txtSubcategoryProd').val(0);
    $('#txtProductSubCat').val(0);

    
    $('#txtCategoryAcce').val(0);
    $('#txtSubcategoryAcce').val(0);

    $('.list-item').addClass('hide-items');

    deleteTablaAccesorios();
    deleteTablaProducts();

    $('#selectAccesorios').css('visibility', 'hidden');
}
// Solicita las categorias
function getCategory() {
    var pagina = 'Commons/listCategories';
    var par = '[{"parm":""}]';
    var tipo = 'json';
    var selector = putCategory;
    fillField(pagina, par, tipo, selector);
}

function getCategoryAcc() {
    var pagina = 'SeriestoProducts/listCategoriesAcc';
    var par = '[{"parm":""}]';
    var tipo = 'json';
    var selector = putCategoryAcc;
    fillField(pagina, par, tipo, selector);
}
// Solicita las subcategorias
function getSubcategory() {
    var pagina = 'Commons/listSubCategoriesAll';
    var par = `[{"catId":""}]`;
    var tipo = 'json';
    var selector = putSubCategory;
    fillField(pagina, par, tipo, selector);
}

// SOLICITA LOS PRODUCTOS SEGUN SU SUBCATEGORIA ID
function getProducts(sbc_id) {
    var pagina = 'SeriestoProducts/listProductsById';
    var par = '[{"sbc_id":"' + sbc_id + '"}]';
    var tipo = 'json';
    var selector = putProducts;
    fillField(pagina, par, tipo, selector);
}

function getSeriesProd(prdId, opc) {
    var pagina = 'SeriestoProducts/listSeriesProd';
    var par = `[{"prdId":"${prdId}", "opc":"${opc}"}]`;
    var tipo = 'json';
    var selector = putSeriesProd;
    fillField(pagina, par, tipo, selector);
}

function getAccesoriesById(prdId) {
    var pagina = 'SeriestoProducts/getAccesoriesById';
    var par = `[{"prdId":"${prdId}"}]`;
    var tipo = 'json';
    var selector = putAccesoriesById;
    fillField(pagina, par, tipo, selector);
}

function getProdAccesoriesById(prdId) {
    //console.log(prdId);
    var pagina = 'SeriestoProducts/getProdAccesoriesById';
    var par = `[{"prdId":"${prdId}"}]`;
    var tipo = 'json';
    var selector = putAccesoriesById;
    fillField(pagina, par, tipo, selector);
}

// Configura la tabla de paquetes
function setting_table_product() {
    let tabla = $('#tblPackages').DataTable({
        order: [[0, 'asc']],
        pageLength: 1000,
        select: true,
        dom: 'Brti',
        buttons: [
            {
                // Boton aplicar cambios
                text: 'Generar paquete',
                className: 'btn-apply hidden-field',
                action: function (e, dt, node, config) {
                    read_package_table();
                },
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
            {data: 'pack_sku', class: 'sel sku'},
            {data: 'packname', class: 'sel product-name'},
        ],
    });
}

// Configura la tabla de productos
function setting_table_accesorys() {
    $('#tblProducts').DataTable({
        order: [[0, 'asc']],
        pageLength: 1000,
        dom: 'Brti',
        buttons: [
            {
                // Boton aplicar cambios
                text: 'Generar paquete',
                className: 'btn-apply hidden-field',
                action: function (e, dt, node, config) {
                    read_product_table();
                },
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
            {data: 'editable', class: 'edit'},
            {data: 'prod_sku', class: 'sku'},
            {data: 'prodname', class: 'product-name'},
            {data: 'prodquant', class: 'product-name'},
        ],
    });
}

// llena el selector de categorias
function putCategory(dt) {
    if (dt[0].cat_id != 0) {
        $.each(dt, function (v, u) {
            let H = `<option value="${u.cat_id}" data-content="${u.cat_id}">${u.cat_name}</option>`;
            $('#txtCategoryProd').append(H);
        });
    }

    $('#txtCategoryProd').on('change', function () {
        let ops = `<option value="0" selected>Selecciona una subcategoría</option>`;
        $('#txtSubcategoryProd').html(ops);
        let id = $(this).val();
        lsbc_id = id;
        $('#txtProductSubCat').html(`<option value="0" selected>Selecciona una subcategoría</option>`);
        selSubcategoryPack(id);
        //validator_part01();
    });

    $('#txtCategoryAcce').on('change', function () {
        let ops = `<option value="0" selected>Selecciona</option>`;
        $('#txtSubcategoryAcce').html(ops);
        let id = $(this).val();
        lsbc_id = id;
        selSubcategoryPackAcc(id);
        //validator_part01();
    });

}

function putCategoryAcc(dt) {
    if (dt[0].cat_id != 0) {
        $.each(dt, function (v, u) {
            let H = `<option value="${u.cat_id}" data-content="${u.cat_id}">${u.cat_name}</option>`;
            $('#txtCategoryAcce').append(H);
        });
    }

    $('#txtCategoryAcce').on('change', function () {
        let ops = `<option value="0" selected>Selecciona</option>`;
        $('#txtSubcategoryAcce').html(ops);
        let id = $(this).val();
        lsbc_id = id;
        selSubcategoryPackAcc(id);
        //validator_part01();
    });

}
// Mantiene en memoria el set de subcategorias
function putSubCategory(dt) {
    //lsbc_id = dt[0].sbc_id;
    subcategos = dt;
}

// Llena el selector de subcategorias
function selSubcategoryPack(id) {
    // deleteTablaAccesorios();
    // deleteTablaProducts();
    // console.log('selSubcategoryPack');
    if (subcategos[0].sbc_id != 0) {
        $.each(subcategos, function (v, u) {
            if (u.cat_id === id) {
                let H = `<option value="${u.sbc_id}" data-content="${u.sbc_id}|${u.cat_id}|${u.sbc_code}">${u.sbc_code} - ${u.sbc_name}</option>`;
                $('#txtSubcategoryProd').append(H);
                
            }
        });
    }

    $('#txtSubcategoryProd').change(function () {
        // let id = $(this).val();
        let id = $(this).val();
        lsbc_id = id;
        getProducts(id);
    });

}

// Llena el selector de subcategorias
function selSubcategoryPackAcc(id) {
    // deleteTablaAccesorios();
    // deleteTablaProducts();
    // console.log('selSubcategoryPack');
    if (subcategos[0].sbc_id != 0) {
        $.each(subcategos, function (v, u) {
            if (u.cat_id === id) {
                let H = `<option value="${u.sbc_id}" data-content="${u.sbc_id}|${u.cat_id}|${u.sbc_code}">${u.sbc_code} - ${u.sbc_name}</option>`;
                $('#txtSubcategoryAcce').append(H);
            }
        });
    }


    $('#txtSubcategoryAcce').unbind('change').change(function () {
        let id = $(this).val();
        lsbc_id = id;
        // console.log('subcategoria seleccionada-',id);
        // getProducts(id);
        if ($('#RadioConceptos1').prop('checked')) {
            load_Accesories(lsbc_id);
        } 
        if ($('#RadioConceptos2').prop('checked')) {
            load_prd_accesories(lsbc_id);
        } 
        
    });
}

function putProducts(dt) {
    // console.log('putProducts',dt);
    proddatos = dt;
    selProductsSub(dt);
}

/* carga de accesorios por id */
function putAccesoriesById(dt) {
    // console.log('putAccesoriesById',dt);
    deleteTablaAccesorios();

    if (dt[0].prd_id != '') {
        $.each(dt, function (v, u) {
            putNewAccesorio(u.prd_id, u.prd_sku, u.prd_name, u.quantity);
        });
    }
}

// Llena el selector de subcategorias
function selProductsSub(dt) {
    // console.log('selProductsSub',dt);

    $('#txtProductSubCat').html(''); // Edna
    if (dt[0].prd_id != 0) {
        $.each(dt, function (v, u) {
                let H = `<option value="${u.prd_id}" data-content="${u.prd_id}|${u.sbc_id}|${u.prd_sku}">${u.prd_sku} - ${u.prd_name}</option>`;
                $('#txtProductSubCat').append(H);
        });

        if ($('#RadioConceptos1').prop('checked')) { opc = 1; } 
        if ($('#RadioConceptos2').prop('checked')) { opc = 2; } 
        getSeriesProd(dt[0].prd_id, opc);
    }else{
        if ($('#RadioConceptos1').prop('checked')) { opc = 1; } 
        if ($('#RadioConceptos2').prop('checked')) { opc = 2; } 
        getSeriesProd(0, opc);
    }

    $('#txtProductSubCat').change(function () {
        // let id = $(this).val();
        let id = $(this).val();
        let opc = 1;
        lsbc_id = id;
        if ($('#RadioConceptos1').prop('checked')) {
            opc = 1;
        } 
        if ($('#RadioConceptos2').prop('checked')) {
            opc = 2;
        } 
        //console.log('GET SERIES-',id);
        getSeriesProd(id, opc);

        $('#txtCategoryAcce').val(0);
        $('#txtSubcategoryAcce').val(0);
        $('.list-item').addClass('hide-items');
    });
}

//llena la tabla de productos
function putSeriesProd(dt) {
    deleteTablaAccesorios();
    deleteTablaProducts();
    if (dt[0].ser_id != '0') {
        let tabla = $('#tblPackages').DataTable();
        tabla.clear().draw(); //BORRA LOS REGISTROS EXISTENTES
        $.each(dt, function (v, u) {
            var rowNode = tabla.row
                .add({
                    pack_sku: `<span class="hide-support" id="SKU-${u.ser_id}" data-sku="${u.ser_sku}" data-prdid="${u.prd_id}">${u.ser_id}</span>${u.ser_sku}`,
                    packname: u.ser_serial_number,
                })
                .draw()
                .node();
            // $(rowNode).find('td').eq(1).attr('hidden', true);

            $(`#SKU-${u.ser_id}`)
                .parent()
                .parent()
                .attr('id', u.ser_id + '-' + u.ser_sku + '-' + u.prd_id)
                .addClass('indicator');
        });

        action_selected_packages();
    } else {
        console.log('llego al else');
    }
}

function putAccesorios(dt) {
    // console.log(dt);
    $('#txtProducts').val('   Cargando Informacion....');
    var sl = $('#txtProducts').offset();
    $('#listProduct .list-items').html('');
    $('#listProduct').css({top: sl.top + 30 + 'px'});// volver a tomar al hacer scroll.
    if (dt[0].prd_id > 0) {
        $.each(dt, function (v, u) {
            let H = `<div class="list-item" id="${u.prd_id}|${u.prd_sku}|${u.prd_name}|1" data-subcateg="${u.prd_id}" data_complement="${u.prd_sku}|${u.prd_id}|${u.prd_name.replace(/"/g, '')}">${u.prd_sku} / ${u.prd_name}</div>`;
            $('#listProduct .list-items').append(H);
        });
        $('#txtProducts').val('');
    }
    
    $('#txtProducts').on('focus', function () {
        $('#listProduct').slideDown('fast');
    });

    $('#txtProducts').on('scroll', function(){
        sl = $('#txtProducts').offset();
        $('#listProduct').css({top: sl.top + 30 + 'px'});
    });
    $('#listProduct').on('mouseleave', function () {
        $('#listProduct').slideUp('fast');
    });

    $('#txtProducts')
    .unbind('keyup')
    .on('keyup', function () {
        let text = $(this).val().toUpperCase();
        selProduct(text);
    });

    $('#listProduct .list-item').on('click', function () {
        let prdId = $(this).attr('id');
        console.log(prdId);
        product_apply(prdId);
    });
    /* $('#listProducts').html('');
    if (dt[0].prd_id != '0') {
        $.each(dt, function (v, u) {
            let H = `
            <div class="list-item" id="${u.prd_id}|${u.prd_sku}|${u.prd_name}|${u.quantity}" data-subcateg="${u.prd_id}" >
            ${u.prd_sku} / ${u.prd_name}<div class="items-just"><i class="fas fa-arrow-circle-right"></i></div>
            </div>`;
            $('#listProducts').append(H);
        });
    } */
    // drawProducts();
}

function selProduct(res) {
    
    res = res.toUpperCase();
    if (res == '') {
        $('#listProduct').slideUp(100);
    } else {
        $('#listProduct').slideDown(400);
    }
    
    if (res.length > 3) {
        
        $('#listProduct .list-items div.list-item').css({display: 'none'});
        $('#listProduct .list-items div.list-item').each(function (index) {
            var cm = $(this).attr('data_complement').toUpperCase().replace(/|/g, '');
            
            cm = omitirAcentos(cm);
            var cr = cm.indexOf(res);
            if (cr > -1) {
                $(this).css({display: 'block'});
            }
        });
        
        // rowCurr.show();
    }else {
        var sl = $('#txtProducts').offset();
        $('#listProduct').css({top: sl.top + 30 + 'px'});// volver a tomar al hacer scroll.
        $('#listProduct .list-items div.list-item').css({display: 'none'});
    }
}
/**  ++++ Omite acentos para su facil consulta */
function omitirAcentos(text) {
    var acentos = 'ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç';
    var original = 'AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc';
    for (var i = 0; i < acentos.length; i++) {
        text = text.replace(acentos.charAt(i), original.charAt(i));
    }
    return text;
}
/*  */
// Dibuja los productos
function drawProducts() {
    $('.list-item').addClass('hide-items');

    $('.list-item').removeClass('hide-items');

    var ps = $('#boxProducts').offset();
    $('.list-group').css({top: ps.top + 30 + 'px', display: 'none'});
    $('.box-items-list i').removeClass('rotate');
    $('#boxProducts')
        .unbind('click')
        .on('click', function () {
            $('.list-group').slideToggle('slow');
            $('.box-items-list i').toggleClass('rotate');
        });

    $('.list-item .items-just i')
        .unbind('click')
        .on('click', function () {
            let id = $(this).parents('.list-item');
            console.log('SE DIO' + id);
            //console.log( $(this).parents('.list-item').attr('id'));
            product_apply(id);
            
        });

}

let sbccnt = 0;

//***************************************** */
function action_selected_packages() {
    $('.indicator td')
        .unbind('click')
        .on('click', function () {
            var prdId = $(this).parent().attr('id');
            var arraryPrd = prdId.split('-');
            //$("#selectAccesorios").css('visibility', 'visible');;
            productoSelectId = arraryPrd[0];
            productoSelectSKU = arraryPrd[1];
            prd_id = arraryPrd[2];
            let tabla = $('#tblPackages').DataTable();

            setTimeout(() => {
                var RenglonesSelection = tabla.rows({selected: true}).count();
                if (RenglonesSelection == 0) {
                    $('#selectAccesorios').css('visibility', 'hidden');
                    deleteTablaAccesorios();
                } else {
                    $('#selectAccesorios').css('visibility', 'visible');
                }
            }, 100);
            // console.log(productoSelectId);
            if ($('#RadioConceptos1').prop('checked')) {
                getAccesoriesById(productoSelectId);
            } 
            if ($('#RadioConceptos2').prop('checked')) {
                getProdAccesoriesById(productoSelectId);
            } 
        });
}

// Llena el selector de subcategorias
function selSubcategoryProduct(id) {
    // console.log('SELECC SUBCATE');
    if (subcategos[0].sbc_id != 0) {
        $.each(subcategos, function (v, u) {
            if (u.cat_id === id) {
                let H = `<option value="${u.sbc_id}" data-content="${u.sbc_id}|${u.cat_id}|${u.sbc_code}">${u.sbc_code} - ${u.sbc_name}</option>`;
                $('#txtSubcategoryProduct').append(H);
            }
        });
    }

    $('#txtSubcategoryProduct')
        .unbind('change')
        .on('change', function () {
            let id = $(this).val();
            lsbc_id = id;
            // console.log(id);
            // drawProducts(id);
        });
}


function saveAccesoryId(serId) {
    var pagina = 'SeriestoProducts/saveAccesorioByProducto';
    var par = `[{"serId":"${serId}","parentId":"${productoSelectId}","prdId":"${prd_id}","skuPrdPadre":"${productoSelectSKU}","lsbc_id":"${lsbc_id}"}]`;
    var tipo = 'json';
    var selector = putAccesoriesRes;
    fillField(pagina, par, tipo, selector);
}
function savePrdAccesoryId(prdId){
    var pagina = 'SeriestoProducts/saveAccesorioProducto';
    var par = `[{"prdId":"${prdId}","parentId":"${productoSelectId}","skuPrdPadre":"${productoSelectSKU}","lsbc_id":"${lsbc_id}"}]`;
    var tipo = 'json';
    var selector = putAccesoriesRes;
    fillField(pagina, par, tipo, selector);
}

function putAccesoriesRes(dt) {
    // console.log('putAccesoriesRes ', dt);
    accesorioExist = dt;
}

function action_selected_products() {
    $('#tblProducts .choice')
        .unbind('click')
        .on('click', function () {
            let edt = $(this).attr('class').indexOf('kill');
            // console.log(edt);
            let prdId = $(this).attr('id');
            confirm_delet_product(prdId);
        });

    $('#tblProducts .quantity')
        .unbind('blur')
        .on('blur', function () {
            let qty = $(this).val();
            let prdId = $(this).attr('id').substring(3, 20);
            console.log(prdId);
            editProdAsoc(prdId, qty);
        });
}
function editProdAsoc(Id, prdQty) {
    let prdId = Id.split('-')[0];
    let prdParent = Id.split('-')[1];
    var pagina = 'SeriestoProducts/updateQuantityProds';
    var par = `[{"prdId":"${prdId}","prdParent":"${prdParent}","prdQty":"${prdQty}"}]`;
    var tipo = 'json';
    var selector = putUpdatePackages;
    fillField(pagina, par, tipo, selector);
} 
function putUpdatePackages(dt) {
    console.log('Dentro', dt);
}
function load_Accesories(prdId) {
    var pagina = 'SeriestoProducts/listAccesorios';
    var par = `[{"prdId":"${prdId}"}]`;
    var tipo = 'json';
    var selector = putAccesorios;
    fillField(pagina, par, tipo, selector);
}

function load_prd_accesories(prdId) {
    var pagina = 'SeriestoProducts/listPrdAccesorios';
    var par = `[{"prdId":"${prdId}"}]`;
    var tipo = 'json';
    var selector = putAccesorios;
    fillField(pagina, par, tipo, selector);
}

//revisar aqui si no graba producto
function product_apply(prId) {
    // console.log('product_apply',prId);
    let acce = prId.split('|');
    let tabla = $('#tblProducts').DataTable();
    let filas = tabla.rows().count();

    console.log(acce);

    if ($('#RadioConceptos1').prop('checked')) {
        saveAccesoryId(acce[0]);
    }else if ($('#RadioConceptos2').prop('checked')) {
        savePrdAccesoryId(acce[0]);
    }
    
    console.log('ACCE',acce[0], acce[1], acce[2], acce[3]);
    setTimeout(() => {
        if (accesorioExist != 0) {
            putNewAccesorio(acce[0], acce[1], acce[2], acce[3]);
            //$(`.list-item[data-subcateg^="accesorio 41"]`).attr("hidden",true);
            $(`.list-item[data-subcateg^="${acce[0]}"]`).attr('hidden', true);
        }
        $('#txtProducts').val("");
        $('#txtIdProducts').val(0);
        // $('#txtCategoryAcce').val(0);
        // $('#txtSubcategoryAcce').val(0);
        // $('#txtProducts').val('');
        // $('#listProduct .list-items').html('');

    }, 500);
}

function putNewAccesorio(idAccesorio, idSku, idName, qty) {
    //inserta el renglon en base
    let tabla = $('#tblProducts').DataTable();
    if ($('#RadioConceptos2').prop('checked')) {
        qty = '<input class="quantity fieldIn" type="text" id="QY-' + idAccesorio +'-'+ productoSelectId + '" value="' + qty + '">';
        
    }else{
        qty = '1';
    }
    tabla.row
        .add({
            editable: `<i class="fas fa-times-circle choice prod kill" id="${idAccesorio}-${productoSelectId}"></i>`,
            prod_sku: `<span >${idSku}</span>`,
            prodname: idName,
            prodquant: qty,
        })
        .draw();
    action_selected_products();
}

function confirm_delet_product(id) {
    $('#delProdModal').modal('show');
    $('#txtIdProductPack').val(id);
    $('#btnDelProduct').on('click', function () {
        let Id = $('#txtIdProductPack').val();
        var arrayID = Id.split('-');
        let prdId = arrayID[0];
        let prdParent = arrayID[1];
        let opc;
        let tabla = $('#tblProducts').DataTable();
        $('#delProdModal').modal('hide');

        let prdRow = $(`#${Id}`).parents('tr');
        tabla.row(prdRow).remove().draw();
        if ($('#RadioConceptos1').prop('checked')) {
            opc = 1;
        } 
        if ($('#RadioConceptos2').prop('checked')) {
            opc = 2;
        } 

        var pagina = 'SeriestoProducts/deleteProduct';
        var par = `[{"prdId":"${prdId}","prdParent":"${prdParent}","opc":"${opc}"}]`;
        var tipo = 'json';
        var selector = putDelPackages;
        fillField(pagina, par, tipo, selector);
    });
}

function putDelPackages(dt) {
    $('#delPackModal').modal('hide');
    load_Accesories(0);
    $('#txtCategoryAcce').val(0);
    $('#txtSubcategoryAcce').val(0);
    $('#txtProducts').val('');
    $('#listProduct .list-items').html('');
}

function deleteTablaAccesorios() {
    let tabla = $('#tblProducts').DataTable();
    tabla.rows().remove().draw();
}

function deleteTablaProducts() {
    let tabla = $('#tblPackages').DataTable();
    tabla.rows().remove().draw();
}