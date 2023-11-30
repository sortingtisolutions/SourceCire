let subcategos;
let products;

$(document).ready(function () {
    if (verifica_usuario()) {
        inicial();
    }
});
//INICIO DE PROCESOS
function inicial() {
    if (altr == 1) {
    setting_table_products();
    setting_table_packages();
    getProducts();
    deep_loading('O');
    getPackages(0);
    //console.log('PASO 1');
    $('#txtPackageName').on('change', function () {
        validator_part01();
    });
    $('#txtPackagePrice').on('change', function () {
        validator_part01();
    });

    $('#btn_packages').on('click', function () {
        let name = $(this).text();
        if (name == 'Aplicar') {
            packages_edit();
        } else {
            packages_apply();
        }
    });

    $('#btn_packages_cancel').on('click', function () {
        active_params();
    });
    } else {
        setTimeout(() => {
            inicial();
        }, 100);
    }
}
// Configura la tabla de paquetes
function setting_table_packages() {
    let tabla = $('#tblPackages').DataTable({
        order: [[1, 'asc']],
        pageLength: 1000,
        select: true,
        dom: 'Blfrtip',
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
            {data: 'packpric', class: 'sel price'},
            {data: 'dateend', class: 'sel dateend'},
        ],
    });
}

// Configura la tabla de productos
function setting_table_products() {
    $('#tblProducts').DataTable({
        order: [[1, 'asc']],
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
            {data: 'prodpric', class: 'price'},
            {data: 'prodcant', class: 'quantity'},
        ],
    });
}

// Solicita los paquetes
function getPackages(catId) {
    var pagina = 'UnicProjectstoParent/listPackages';
    var par = `[{"catId":"${catId}"}]`;
    var tipo = 'json';
    var selector = putPackages;
    fillField(pagina, par, tipo, selector);
}
// Solicita los paquetes
function getProducts() {
    var pagina = 'UnicProjectstoParent/listProducts';
    var par = `[{"prdId":""}]`;
    var tipo = 'json';
    var selector = putProducts;
    fillField(pagina, par, tipo, selector);
}
function putProducts(dt) {
    $.each(dt, function (v, u) {
        let H = `<div class="list-item" id="P-${u.pjt_id}" data-content="${u.pjt_id}|${u.pjt_number}|${u.pjt_name}">
                    ${u.pjt_number} - ${u.pjt_name}<div class="items-just"><i class="fas fa-arrow-circle-right"></i></div>
                </div>`;
        $('#listProducts').append(H);
    });
}

// Dibuja los productos
function drawProducts(str) {
    $('.list-item').addClass('hide-items');
    $(`.list-item`).removeClass('hide-items');

    var ps = $('#boxProducts').offset();
    $('.list-group').css({top: ps.top + 30 + 'px', display: 'none'});
    $('.box-items-list i').removeClass('rotate');
    $('#boxProducts')
        .unbind('click')
        .on('click', function () {
            $('.list-group').slideToggle('slow');
            console.log('Click lista');
            $('.box-items-list i').toggleClass('rotate');
        });

    $('.list-item .items-just i')
        .unbind('click')
        .on('click', function () {
            let id = $(this).parents('.list-item');
            //console.log(id);
            product_apply(id);
        });
}

// Llena la tabla de paquetes
function putPackages(dt) {
    $('#txtIdPackages').val(0);
    $('.form_secundary').slideUp('slow', function () {
        $('.form_primary').slideDown('slow');
        active_params();
    });
    $('#tblProducts').DataTable().rows().remove().draw();

    let tabla = $('#tblPackages').DataTable();
    tabla.rows().remove().draw();
    if (dt[0].prd_id != '0') {
        $.each(dt, function (v, u) {
            tabla.row
                .add({
                    
                    pack_sku: `<span class="hide-support" id="SKU-${u.pjt_number}">${u.pjt_id}</span>${u.pjt_number}`,
                    packname: u.pjt_name,
                    packpric: u.pjt_date_start,
                    dateend : u.pjt_date_end,
                })
                .draw();

            $(`#SKU-${u.pjt_number}`).parent().parent().attr('id', u.pjt_id).addClass('indicator');
        });
        action_selected_packages();
    }
    deep_loading('C');
}
function active_params() {
    $('#txtIdPackages').val(0);
    $('#txtPackageName').val('');
    $('#txtPackagePrice').val('');
    $('.mainTitle').html('Generar paquete');
    $(`#txtCategoryPack`).attr('disabled', false);
    $(`#txtSubcategoryPack`).attr('disabled', false);
    $('#btn_packages').html('Crear paquete').addClass('disabled');
    $(`#txtCategoryPack`).val(0);
    $(`#txtCategoryPack option[value="0"]`).trigger('change');
    $(`#txtSubcategoryPack`).val(0);
    $('#btn_packages_cancel').addClass('hide-items');
}

function build_sku_product(sbcId) {
    var pagina = 'UnicProjectstoParent/lastIdSubcategory';
    var par = `[{"sbcId":"${sbcId}"}]`;
    var tipo = 'json';
    var selector = putIdSubcategory;
    fillField(pagina, par, tipo, selector);
}

function putIdSubcategory(dt) {
    packages_apply(dt[0].nextId);
}

function fill_table_packs(par) {
    let largo = $('#tblPackages tbody tr td').html();
    largo == 'Ningún dato disponible en esta tabla' ? $('#tblPackages tbody tr').remove() : '';

    pr = JSON.parse(par);

    var pagina = 'UnicProjectstoParent/savePack';
    var par = par;
    var tipo = 'html';
    var selector = putNewPackage;
    fillField(pagina, par, tipo, selector);
}

function putNewPackage(dt) {
    let id = dt.split('|')[0];
    let sku = dt.split('|')[1];
    let name = dt.split('|')[2];
    let price = dt.split('|')[3];
    $(`#SKU-${sku}`).text(id);

    let tabla = $('#tblPackages').DataTable();

    tabla.row
        .add({
            editable: `<i class="fas fa-pen choice pack modif" id="E-${id}"></i>
            <i class="fas fa-times-circle choice pack kill" id="D-${id}"></i>`,
            pack_sku: `<span class="hide-support" id="SKU-${sku}"></span>${sku}`,
            packname: name,
            packpric: price,
        })
        .draw();
    $(`#SKU-${sku}`).parent().parent().attr('id', id).addClass('indicator');
    action_selected_packages();

    /* tabla.on('select', function (e, dt, type, i) {
        $('#txtCategoryProduct').val(0);
        $('#txtSubcategoryProduct').val(0);
        $('#txtIdPackages').val(0);
    }); */
}

function action_selected_packages() {
    $('.indicator td.sel')
        .unbind('click')
        .on('click', function () {
            let selected = $(this).parent().attr('class').indexOf('selected');
            if (selected < 0) {
                let prdId = $(this).parent().attr('id');
                select_products(prdId);

                $('#txtCategoryProduct').val(0);
                $('#txtSubcategoryProduct').val(0);

                drawProducts(0);
                $('.form_primary').slideUp('slow', function () {
                    $('.form_secundary').slideDown('slow');
                    $('#txtIdPackages').val(prdId);
                });
            } else {
                $('#txtIdPackages').val(0);
                $('.form_secundary').slideUp('slow', function () {
                    $('.form_primary').slideDown('slow');
                    active_params();
                });
                $('#tblProducts').DataTable().rows().remove().draw();
            }
        });

}

function action_selected_products() {
    $('#tblProducts .choice')
        .unbind('click')
        .on('click', function () {
            let acc = $(this).attr('class').split(' ')[4];
            let prdId = $(this).attr('id');
            //console.log($(this).attr('class').split(' '));
            //console.log(acc);
            console.log(prdId);
            switch (acc) {
                case 'kill':
                    confirm_delet_product(prdId);
                    break;
                default:
            }
        });

}

function select_products(prdId) {
    var pagina = 'UnicProjectstoParent/listProductsPack';
    var par = `[{"prjId":"${prdId}"}]`;
    var tipo = 'json';
    console.log(par);
    var selector = putProductsPack;
    fillField(pagina, par, tipo, selector);
}

function putProductsPack(dt) {
    console.log(dt);
    let tabla = $('#tblProducts').DataTable();
    tabla.rows().remove().draw();
    if (dt[0].pjt_id != '') {
        $.each(dt, function (v, u) {
            tabla.row
                .add({
                    editable: `<i class="fas fa-times-circle choice prod kill" id="D-${u.pjt_id}-${u.pjt_parent}"></i>`,
                    prod_sku: `<span class="hide-support" id="SKU-${u.pjt_number}">${u.pjt_id}</span>${u.pjt_number}`,
                    prodname: u.pjt_name,
                    prodpric: u.pjt_date_start,
                    prodcant: u.pjt_date_end,
                })
                .draw();
        });
        action_selected_products();
    }
}

function product_apply(prId) {
    console.log(prId);
    let proj = prId.attr('data-content').split('|');
    let productId = proj[0];
    let projParent = $('#txtIdPackages').val();
    //let productQuantity = $('#txtQtyPrds').val();
    var pagina = 'UnicProjectstoParent/SaveProject';
    var par = `[{"prjId":"${productId}","prjParent":"${projParent}"}]`;
    console.log(par);
    var tipo = 'json';
    var selector = putNewProductsPack;
    fillField(pagina, par, tipo, selector);
}

function putNewProductsPack(dt) {
    console.log(dt);
    let tabla = $('#tblProducts').DataTable();
    tabla.row
        .add({
            editable: `<i class="fas fa-times-circle choice prod kill" id="D-${dt[0].pjt_id}-${dt[0].pjt_parent}"></i>`,
            prod_sku: `<span class="hide-support" id="SKU-${dt[0].pjt_number}">${dt[0].pjt_id}</span>${dt[0].pjt_number}`,
            prodname: dt[0].pjt_name,
            prodpric: dt[0].pjt_date_start,
            prodcant: dt[0].pjt_date_end,
        })
        .draw();
    action_selected_products();
}


function confirm_delet_product(id) {
    $('#delProdModal').modal('show');
    $('#txtIdProductPack').val(id);
    //borra paquete +
    $('#btnDelProduct').on('click', function () {
        let Id = $('#txtIdProductPack').val();
        let prdId = Id.split('-')[1];
        let prdParent = Id.split('-')[2];
        let tabla = $('#tblProducts').DataTable();
        $('#delProdModal').modal('hide');

        let prdRow = $(`#${Id}`).parents('tr');

        tabla.row(prdRow).remove().draw();

        var pagina = 'UnicProjectstoParent/deleteProduct';
        var par = `[{"prdId":"${prdId}"}]`;
        var tipo = 'json';
        var selector = putDelPackages;
        fillField(pagina, par, tipo, selector);
    });
}

function putDelPackages(dt) {
    $('#delPackModal').modal('hide');
}
