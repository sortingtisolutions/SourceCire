var pos = 0;
let altr = '0';
function fillField(pagina, par, tipo, selector) {
    // console.log(pagina);
    $.ajax({
        url: pagina,
        type: 'post',
        data: parse_data(par),
        dataType: tipo,
        cache: false,
        success: function (dt) {
            bufunc(selector(dt));
        },
        error: function (xhr, textStatus, error) {
            show_error(xhr, textStatus, error, selector);
        },
    });
}

function bufunc(fnn) {
    /*alert(fnn);*/ var fnt = fnn;
    calfun(fnt);
}
function calfun(fnc) {
    fnc;
}

function secfunc(fnn) {
    /*alert(fnn);*/ var fnt = fnn();
    flwfun(fnt);
}
function flwfun(fnc) {
    fnc;
}
function endsec() {}

function parse_data(P) {
    var PR = $.parseJSON(P);
    var par = '';
    //var cobj = PR.prmt
    $.each(PR, function (v, ob) {
        $.each(ob, function (k, u) {
            par += k + '=' + u + '&';
        });
    });
    par = par.substring(0, par.length - 1);
    return par;
}

function show_error(xhr, textStatus, error, selector) {
    var H = `
    Selector: ${selector.name} <br>
    StatusText: ${xhr.statusText} <br>
    Status: ${xhr.textStatus} <br>
    textStatus: ${textStatus} <br>
    Error: ${error} <br>
    ResponseText: ${xhr.responseText} <br>
    final<br>`;
    const btn_close = $('<button></button>')
      .attr('id', 'btn_close')
      .append($('<i></i>').addClass('far fa-window-close'))
      .on('click', function() {
        $('#msgError').hide();
        }).css({
            'background-color': '#f5eec0',
            'color': '#000000',
            'border': 'none',
            'padding': '6px 10px',
            'cursor': 'pointer',
            'position': 'absolute',
            'top': '0',
            'right': '0'
        });
    btn_close.find('i').css('font-size', '20px');
    $('#msgError').empty().append(H,btn_close).removeClass('reposo');
    $('#msgError').show();
    // $('#msgError').html(H).removeClass('reposo');
}

// RELLENA CON CEROS A LA IZQUIERDA
function refil(number, length) {
    var str = '' + number;
    while (str.length < length) str = '0' + str;
    return str;
}

function pure_num(nm) {
    let num = nm.replace(/,/g, '');
    return num;
}

function fnm(c, d, sd, sm) {
    return formato_numero(c, d, sd, sm);
}

// DA FORMATO A LOS NUMEROS
function formato_numero(numero, decimales, separador_decimal, separador_miles) {
    // v2007-08-06

    numero = parseFloat(numero);

    if (isNaN(numero)) {
        return '';
    }

    if (decimales !== undefined) {
        // Redondeamos
        numero = numero.toFixed(decimales);
    }

    // Convertimos el punto en separador_decimal
    numero = numero
        .toString()
        .replace(
            ',',
            separador_decimal !== undefined ? separador_decimal : '.'
        );

    if (separador_miles) {
        // Añadimos los separadores de miles
        var miles = new RegExp('(-?[0-9]+)([0-9]{3})');
        while (miles.test(numero)) {
            numero = numero.replace(miles, '$1' + separador_miles + '$2');
        }
    }

    return numero;
}

/** Genera el folio de los movimientos */
function getFolio() {
    let fl = moment(Date()).format('YYMMDDHHmmss');
    return fl;
}

/* ---- ----  IMPORTA LOS SCRIPTS NECESARIOS ----- ------ */

function importarScript(nombre) {
    var s = document.createElement('script');
    s.src = nombre;
    document.querySelector('head').appendChild(s);
}

function importarStyleSheet(nombre) {
    var s = document.createElement('link');
    s.href = nombre;
    document.querySelector('head').appendChild(s);
}

// Limpia los campos de entrada de datos
function limpia_campos() {
    $('input').val('');
    $('input').removeAttr('data_selection');
    $('.icon_check').removeAttr('class').addClass('icon_uncheck');
    $('textarea').val('');
}

function verifica_usuario() {
    altr = '0';
    url = getAbsolutePath();
    importarScript(url + 'app/assets/lib/alerts.js?v=1.0.0.0');
    var galleta = Cookies.get('user');
    if (galleta == undefined) {
        window.location = 'Login';
    } else {
        var pagina = 'Menu/Menu';
        var par = `[{"userid":"${galleta.split('|')[0]}"}]`;
        var tipo = 'json';
        var selector = set_menu_on_page;
        fillField(pagina, par, tipo, selector);
        return true;
    }
}

function set_menu_on_page(dt) {
    build_menu(dt);

    var galleta = Cookies.get('user');
    var usuario = galleta.split('|')[2].replace(/\+/g, ' ');
    var H = `<div class="user_space">${usuario} <i class="fas fa-power-off salir"></i></div>`;

    $('.sign-out').html(H);

    $('.salir').on('click', function () {
        window.location = 'Login/logout';
    });
}

function build_menu(dt) {
    $.each(dt, function (v, u) {
        if (u.mnu_parent == 0) {
            let H = `<li><a href="${u.mod_item}">${u.mnu_item}</a>${sublevel(
                u.mnu_id,
                u.sons,
                dt
            )}</li>`;
            $('ul.menu').append(H);
        }
    });
}

function sublevel(id, sn, dt) {
    let H = '';
    if (sn > 0) {
        H += `<ul>`;
        $.each(dt, function (v, u) {
            if (u.mnu_parent == id) {
                let sons =
                    u.sons > 0 ? '<i class="fas fa-angle-right"></i>' : '';
                H += `<li><a href="${u.mod_item}">${
                    u.mnu_item
                }${sons}</a>${sublevel(u.mnu_id, u.sons, dt)}</li>`;
            }
        });
        H += `</ul>`;
    }
    return H;
}

function getAbsolutePath() {
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    return loc.href.substring(
        0,
        loc.href.length -
            ((loc.pathname + loc.search + loc.hash).length - pathName.length)
    );
}

function addComments(scc, mid) {
    let H = `
    <div class="box_comments">
        <div class="form_comments">
            <h1>Notificaciones</h1>
            <textarea id="txtComments"></textarea>
            <button class="bn btn-ok" id="comApply">Aceptar</button>
            <button class="bn btn-cn" id="comCancel">Cancelar</button>
        </div>
    </div>
    `;

    $('body').append(H);

    $('#comCancel').on('click', function () {
        $('.box_comments').remove();
    });

    $('#comApply').on('click', function () {
        let cmm = $('#txtComments').val();
        // saveComment(scc, mid, cmm);
        $('.box_comments').remove();
    });
}

function showComments(mid) {
    console.log(mid);
    let H = `
    <div class="box_comments_show">
        <div class="form_comments_show">
            <h1>Observaciones</h1>
            <div class="comments_area"></div>
            <button class="bn btn-ok" id="comClose">Cerrar</button>
        </div>
    </div>
    `;

    $('body').append(H);

    getComments(mid);

    $('#comClose').on('click', function () {
        $('.box_comments_show').remove();
    });
}

function looseAlert(grp) {
    grp.children('.textbox').removeClass('textbox-alert');

    grp.children('.textAlert').css({ visibility: 'hidden' });

    // console.log(grp.attr('class'));
}

function getSendEmails(areId,codEmail){

    // altr = '0';
    // url = getAbsolutePath();
    // importarScript(url + 'app/assets/lib/alerts.js?v=1.0.0.0');
    // var galleta = Cookies.get('user');
    console.log('getSendEmails',areId,codEmail);
    var pagina = 'Commons/listEmails';
    var par = `[{"are_id":"${areId}","cod_email":"${codEmail}"}]`;
    var tipo = 'json';
    var selector = putsendemails;
    fillField(pagina, par, tipo, selector);
    // return true;

    // glbsendmail=true;
    // subjectmess="PROGRAMACION: Sistema, Nuevo Proyecto"
    // bodymail="Se realizo un cambio en el modulo de menu que afecta a un modulo y que debe de validarse por una persona autorizada";
    // ctaemail='inventarios-cire@ctt-app.com,programacion-cire@ctt-app.com'
    // sendEmail(glbsendmail,subjectmess,bodymail,ctaemail);
}

function putsendemails(dt) {
    // console.log(dt);
    if (dt[0].fem_id != "0") {
        var turnOn=true;
        var subjectmess=dt[0].are_name + ': ' + dt[0].fem_subject;
        var bodymail=dt[0].fem_body;
        var ctaemail=dt[0].are_email_main;
        $.each(dt, function (v, u) {
            ctaemail=ctaemail + ', ' + u.emp_email;
        });
    // console.log('putSendEmails-1',turnOn);
    // console.log('putSendEmails-2', subjectmess, );
    // console.log('putSendEmails-3', bodymail);
    // console.log('putSendEmails-4', ctaemail);
    sendEmail(turnOn, subjectmess, bodymail, ctaemail);
    } 
    else{
        var turnOn=false;
        sendEmail(turnOn, subjectmess, bodymail, ctaemail);
    } 
}

function sendEmail(turnOn, subjectmess, bodymail, ctaemail) {

    if (turnOn == true) {
        Email.send({
            Host: "smtp.elasticemail.com",
            Username: "notificationcire@ctt-app.com",
            Password: "36AAF404B1F0EA4FE8BB21E24EC62990528E",
            To: ctaemail,
            From: "notificationcire@ctt-app.com",
            Subject: subjectmess, 
            Body: bodymail,
          })
            .then(function (message) {
                noticeEmail('Mensajes de correos enviados correctamente, OK');
            //   alert("MAIL ENVIADO CORRECTAMENTE a....");
            });
    } else {
        noticeEmail('Mensaje de correo NO se envio');
        // alert("Bandera de Correos no esta encendida OFF");
    }
    
  }
//   width:70%; height: 70%
  function noticeEmail(scc) {
    let H = `
    <div class="box_comments" style="width:300px; height:210px; left:40%; background-color:rgba(#transparent, 0.05); align-items:flex-start;">
        <div class="form_comments" style="height:190px; align-items:baseline;">
            <h1>Notificaciones email</h1>
            <label for="txtItems" style="font-size=large; font-weight:bold">${scc}</label><br><br>
            <button class="bn btn-ok" id="comApply">Aceptar</button>
        </div>
    </div> `;

    $('body').append(H);

    $('#comApply').on('click', function () {
    // $('#confirmButton').on('click', function () {
        // let cmm = $('#txtComments').val();
        // saveComment(scc, mid, cmm);
        $('.box_comments').remove();
    });
}