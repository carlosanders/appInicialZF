//(function ($) {
//    $.fn.iframe = function (o) {
//        return $('<iframe />').appendTo(this).each(function () {
//            var win = $(this).contents()
//            win[0].write('<!doctype html><html><head></head><body></body></html>')
//            $.each(o, function (k, v) {
//                win.find(k).html(v)
//            })
//        })
//    }
//})(jQuery)
//
//jQuery.fn.extend({
//    iframe: function (doc, head, body) {
//        if (!doc) {
//            doc = '<!doctype html><html><head>' + (head || "") + '</head><body>' + (body || "") + '</body></html>';
//        }
//        var iframeDoc = this.contents()[0];
//        iframeDoc.open();
//        iframeDoc.write(doc);
//        iframeDoc.close();
//        return this;
//    }
//});

/* 
 * JS personalizado da aplicacaos
 */
$(function () {
    $("#check").button();
    $("#format").buttonset();

    $("#btnSubmit").click(function (e) {

        var botoes = [
            {
                text: 'Salvar',
                iconCls: 'icon-login',
                handler: function () {
                    //$('#dlg1').dialog('close');
                    //$('#rs').empty();
                    //$('#dlg1').dialog('refresh', 'index.php?pg=Relatorios/folhaCRMilitarPDF/'+nip + '/'+ fechamento + '&user=' + user);
                    //$(window.document.location).attr('href', 'index.php?pg=Relatorios/folhaCRMilitarPDF/'+nip + '/'+ fechamento + '&user=' + user);
                    window.open("http://appdpmm.dev:81/main/texto", '_parent');
                }
            }, {
                text: 'Cancelar',
                iconCls: 'icon-cancel',
                handler: function () {
                    $('#dlg1').dialog('close');
                    $('#rs').empty();
                }
            }
        ];

//url, dados, retorno, nomeForm, type, tituloDLG, botoes
        e.preventDefault();// cancele o comportamento padrao de acao do form
        var dados = {
            codigo: "123",
            tftCoddio: "456",
            sucesso: true
        };
        //$.messager.alert('Erros Encotrados', "aqui", 'error');
        //nomeForm, type, tituloDLG, botoes
        enviarRequisicao1(
                "main/semtoporodape", // url
                dados, //dados
                "html", //retorno
                null, // nome form
                "POST", // type GET or POST
                "Justificativa!", // titulo
                botoes //botoes
                );
    });

    $(".checkbox").click(function (e) {
        e.preventDefault();// cancele o comportamento padrao de acao do form
        jAlert("Mensagem para o Alert", "Sucesso!");
    });

//    var nip = "57231";
//    console.log(validanip(nip));

    $('#estados').combobox('clear');
    $('#status').combobox('clear');

//    var dados = {
//        codigo: "123",
//        tftCoddio: "456",
//        sucesso: true
//    };
//
//    var stringOBJ = JSON.stringify(dados);
//    console.log("'" + stringOBJ + "'");
//
//    var obj = $.parseJSON("'" + stringOBJ + "'");
//    console.log(obj);
//
//    var dados1 = '{' +
//            '"codigo": "123",' +
//            '"tftCoddio": "456",' +
//            '"sucesso": true' +
//            '}';
//
//    console.log(dados1);


});

function recarregarDados() {

    var seletor = $('#status');
//    var status = "";
//    if(seletor.combobox('getValue') !== 'undefined' ){
//        status = seletor.combobox('getValue');
//    }

    var status = $('#status').combobox('getValue');
    var estados = $('#estados').combobox('getValue');

    console.log(status, estados);

    $('#dgBasico').datagrid({
        //ressalta-se que esses parans passdos para o PHP, serão recuperado no PHP pelo $_POST['codOM']
        queryParams: {//caso deseja passar outros paramentros do form pelo componente datagrid
            estados: estados,
            satus: status //sendo passado via POST, nao necessita via paramentro do metodo ok
        },
        url: getURLApp() + 'index.php?pg=main/dgExemplo/' + status,
        //url: getURLApp() + 'index.php?pg=main/dgExemplo',
        striped: false,
        singleSelect: true,
        //selectOnCheck: true,
        //checkOnSelect: false,
        rownumbers: true,
        sortName: null,
        method: 'post',
        fitColumns: true,
        rowStyler: rowFormater,
        pagination: false
                //loadMsg: "Processando por-favor aguarde ...",
                //pageSize: 20,
                //pageList: [20, 50, 100, 500, 1000, 5000],
                //rowStyler: function(index, row) {
                //    if (row.DATTERMINO === null || row.DATTERMINO === "") {
                //        return 'background-color:#fff; color:#ccc;'; // return inline style
                //    }
                //},        
    });
}


function clearFormDados(codOM) {

    $('#estados').combobox('clear');
    $('#status').combobox('clear');
    //para remover as linhas do DG
    //$('#dgBasico').datagrid('loadData',[]);
    //$('#dgBasico').datagrid('loadData', {"total":0,"rows":[]});

    //para carregar a pagina com os dados da URL do phtml
    //$('#dgBasico').datagrid('load', {});
    // para ordenar
    //$('#dgBasico').datagrid('sort', 'itemid');	// sort on a column

    $('#dgBasico').datagrid({
        //data:[],
        url: getURLApp() + 'index.php?pg=main/dgExemplo/',
        //ressalta-se que esses parans passdos para o PHP, serão recuperado no PHP pelo $_POST['codOM']
        queryParams: {},
        sortName: null,
        onLoadSuccess: function (data) {
            //console.log(data.rows.length);
            $.messager.show({
                title: 'Sucesso',
                msg: 'Carregados ' + data.total + " registros!",
                showType: 'fade',
                style: {
                    right: '',
                    bottom: ''
                }
            });
        }
    });

}

/**
 * Funçao que aplica um css na linha do dataGrid, baseado em algum field 
 * @param {type} index - retorna o índice de linha, começa com 0
 * @param {type} row - o registro correspondente a row do dataGrid
 * @returns {String} - com o css aplicando no componente DataGrid
 */
function rowFormater(index, row) {
    var css = '';
    if (row.status == 'F') {
        //css = 'color:#FF0000;font-weight:normal; background-color: #ffffff;';
        css = 'color:#FF0000;font-weight:normal;';
    } else if (row.status == 'E') {
        css = 'color:#006699;font-weight:normal;';
    }
    //console.log(index, row);
    return css;
}

/**
 * Funcao para formatar a grid colocando botão de acões em cada registro 
 * de acordo com um condição no caso da funça é se o status for um dos 
 * que o if deseja então não apresenta o botão e sim um '-', senão monta um 
 * HTML para ser inserido pelo componente jEasyUI no DG
 * @param {string} value - indica o valor do campo em foi incluído a tab formatter
 * Ex.: <th field="NRONIP" width="60" align="center" formatter="formataAcaoEvento">Ação</th>
 * Perceba nesse exemplo que no JS que possui a tag o value é o NRONIP 
 * @param {type} row - é todo o registro correspondente a row do dataGrid, perceba que faço um if baseado no campo de um
 * registro do DG
 * @param {type} index - retorna o índice de linha, começa com 0
 * @returns HTML - retorno os valores de 2 variaveis concatenadas e com os respectiovs código HTML para montar o DG
 */
function formataAcaoEvento(value, row, index) {
    if (row.CODSTATUS === "3" || row.CODSTATUS === "4" || row.CODSTATUS === "7") {
        var e = '-';
        var d = '-';
    } else {
        var e = '<a class="linkAcao" title="Editar Evento" \n\
                    href="index.php?pg=Eventos/editarEvCarreiraMilitar/' + row.NIP_CRIPT + '/' + row.EVENTOID + '&user=' + row.USERID + '" >\n\
                <img src="common/imagens/edit-validated-icon.png" border="0" class="first" border="0"/></a> ';
        var d = '<a class="linkAcao" title="Remover Evento" \n\
                    href="javascript:confimarExclusaoEvento(' + row.EVENTOID + ',\'' + row.NRONIP + '\',\'' + row.USERID + '\')" >\n\
                <img src="common/imagens/delete.gif" border="0" class="first" border="0"/></a>';
    }
    return e + d;

}

/**
 * funcao generica para enviar requisica via ajax no site
 * @param string url exemplo: "main/enviarPedido"
 * @param ObjetoJS dados ex: dados = {
 inputNome: inputNome3,
 inputEmail: inputEmail3,
 inputTel: inputTel3,
 inputTexto: inputTexto3
 };
 * @param string retorno ex: (xml, json, script, or html)
 * @param string nomeForm - Nome do ID do Formulario ex: "#formPedidoOracaoMain"
 * @param {type} type - O tipo de pedido a fazer ex:  (e.g. "POST", "GET", "PUT"); default is "GET"
 * @param {string} tituloDLG - Nome do Painel
 * @param {string} botoes - São os botões que serão exibidos no DLG 
 * ex: {
 text: 'Cancelar',
 iconCls: 'icon-cancel',
 handler: function () {
 $('#dlg1').dialog('close');
 $('#rs').empty();
 }
 }
 * @returns {undefined}
 */
function enviarRequisicao(url, dados, retorno, nomeForm, type, tituloDLG, botoes) {

    retorno = (retorno === "") ? "html" : retorno;
    type = (type === null) ? "GET" : type;

    var iconeCarregando = retornaItemCarregando();

    $.ajax({
        url: getURLApp() + 'index.php?pg=' + url,
        //url: getURLApp() + 'index.php?pg=enviarRequisicao' + url,
        dataType: retorno, // tipo de dado esperado como retorno pelo servidor (html, xml json)
        type: type, // tipo de envio de dado para o servidor
        timeout: 90000, //usado como tempo maximo de resposta da consulta da url - 90 segundos
        // url enviarRequisicao
        // se for passar na url nao precisa desse parametro, se for passar
        // muitos parametros
        // sugiro usar essa funcao comentada - Anders
        data: dados, //objeto com os dados que precisa ser enviado
        beforeSend: function () {// apresenta o icone animado antes de
            // carregar os dados
            $('#rs').empty();//limpo a div 
            $('#rs').html(iconeCarregando);//inclui o icone carregando na div
            //apresento a tela modal para o usuario
            $("#dlg1").dialog({
                modal: true,
                title: tituloDLG,
                closed: false,
                buttons: botoes

            });
        },
        complete: function () {// ao completar a requisicao remove o icone
            // animado
            $(iconeCarregando).remove();
        },
        success: function (dados, textStatus) {// carrega dos dados na div

            //se for o retorn json entao dispara um alerta na tela
            if (retorno === "json") {

//                $('#alerta').fadeOut(1);
//                //limpo o form
//                $(nomeForm).each(function () {
//                    this.reset();
//                });

                //mostro o form
                //$(nomeForm).show();

                //se o retorno for true dispara um alerta de sucesso
                if (dados.success) {
                    //exibo um alerta
                    jAlert(dados.msg, "Sucesso!");
                    //senao dispara um alerta de erro 
                } else {
                    jError(dados.msg, "Erros encontrados!");
                }

                // se for html entao imprime na div o resultado   
            } else {
                //console.log(dados);
                $('#rs').html(dados);//insiro os dados do arquivo listaDetalheEventos.php
                //$("#rs").load(getURLApp() + 'index.php?pg=' + dados);
                //jQuery('#dialog').load('path to my page').dialog('open');
                //$('#rs').iframe({body: "<html><body>Some text</body></html>"}) // this variant moves a p to the iframe.


            }
        },
        error: function (xhr, er) {// caso tenha erro apresenta o erro na tela
            if (er === 'timeout') {
                $('#rs').html(
                        'Erro! ocorreu um erro de timeout com o Servidor!');
            } else {
                $('#rs').html(
                        '<p>Error: ' + xhr.status + ' - ' + xhr.statusText
                        + '< /br>tipo erro: ' + er + '</p>');
            }
        }
    });
}

// funcao para formatar as linhas do grid de miltares
function formatAction1(value, row, index) {


    //$.messager.alert('Erros Encotrados', "aqui", 'error');
    //nomeForm, type, tituloDLG, botoes
//    enviarRequisicao1(
//            "main/semtoporodape", // url
//            dados, //dados
//            "html", //retorno
//            null, // nome form
//            "POST", // type GET or POST
//            "Justificativa!", // titulo
//            botoes //botoes
//            );
//    });

    var dados = {
        codigo: $('#estados').combobox('getValue'),
        tftCoddio: "456",
        sucesso: true
    };

    //var stringOBJ = JSON.stringify(dados);
    //console.log(stringOBJ);

    var e = '<a class="linkAcao" title="Exibir Eventos" \n\
                href="javascript:enviarRequisicaoHtmlExemplo('
            + "'main/semtoporodape'," // url
            + null + "," //dados
            + "null," //retorno
            + "null," //retorno
            + "'POST'," // type GET or POST
            + "'Justificativa!'," // titulo
            + "'A'"//botoes
            + ');">\n\
            <img border="0" src="' + getURLApp() + 'common/imagens/zoom_in.png" class="first" border="0"/></a>';
    return e;
}

function enviarRequisicaoHtmlExemplo(url, dados, retorno, nomeForm, type, tituloDLG, botoes) {

    //retorno = (retorno === "") ? "html" : retorno;
    type = (type === null) ? "GET" : type;
    //var iconeCarregando = retornaItemCarregando();

    //var botoes = null;
    if (botoes === "C") {
        botoes = [{
                text: 'Cancelar',
                iconCls: 'icon-cancel',
                handler: function () {
                    $('#dlg1').dialog('close');
                    $('#rs').empty();
                }
            }];
    } else {
        botoes = [{
                text: 'Salvar',
                iconCls: 'icon-save',
                handler: function () {
                    //implementar
                    salvarTft('main/enviarRequisicao&user=');
                }
            }, {
                text: 'Cancelar',
                iconCls: 'icon-cancel',
                handler: function () {
                    $('#dlg1').dialog('close');
                    $('#rs').empty();
                }
            }
        ];
    }

    dados = {
        estadosFixo: $("#estados").combobox('getValue'),
        cbCategoria: $("#cbCategoria").combobox('getValue'),
        cc1Fixo: $("#cc1").combobox('getValue'),
        cbEstados: $("#cbEstados").combobox('getValue'),
        status: $("#status").combobox('getValue'),
        rows: $("#dgBasico").datagrid('getSelected')
    };

    //var obj = $.parseJSON(dados);
    //console.log(obj);

    //$('#rs').empty();//limpo a div 
    //$('#rs').html(iconeCarregando);//inclui o icone carregando na div
    //apresento a tela modal para o usuario
    $("#dlg1").dialog({
        modal: true,
        title: tituloDLG,
        href: getURLApp() + 'index.php?pg=' + url,
        loadingMessage: "Aguarde carregando os dados...",
        closed: false,
        buttons: botoes,
        method: type,
        queryParams: dados, //objeto com os dados que precisa ser enviado
        onLoad: function () {
            //return false;
            //return $('#rs').html(iconeCarregando);//inclui o icone carregando na div
            //jAlert("Carregado", "Sucesso!");
        },
//        loader: function (param) {//param: o objeto de parâmetro para passar para o servidor remoto. 
//            function success(data) {
//                console.log(data, param);
//            };
//            
//            function error(){
//                
//            }
//        },
//        onBeforeLoad: function (param) {
//            console.log(param);
//            //return false;
//            return $('#rs').html(iconeCarregando);//inclui o icone carregando na div
//        }

    });

    $("#dlg1").dialog('center'); //colocar janela no centro da região que eu estou na tela

}

function enviarRequisicao1(url, dados, retorno, nomeForm, type, tituloDLG, botoes) {

    //retorno = (retorno === "") ? "html" : retorno;
    type = (type === null) ? "GET" : type;
    //var iconeCarregando = retornaItemCarregando();

    //var botoes = null;
    if (botoes === "C") {
        botoes = [{
                text: 'Cancelar',
                iconCls: 'icon-cancel',
                handler: function () {
                    $('#dlg1').dialog('close');
                    $('#rs').empty();
                }
            }];
    } else {
        botoes = [{
                text: 'Salvar',
                iconCls: 'icon-save',
                handler: function () {
                    //implementar
                    salvarTft('main/enviarRequisicao&user=');
                }
            }, {
                text: 'Cancelar',
                iconCls: 'icon-cancel',
                handler: function () {
                    $('#dlg1').dialog('close');
                    $('#rs').empty();
                }
            }
        ];
    }

    dados = '{' +
            '"codigo": "123",' +
            '"tftCoddio": "456",' +
            '"sucesso": true' +
            '}';

    var obj = $.parseJSON(dados);
    //console.log(obj);

    //$('#rs').empty();//limpo a div 
    //$('#rs').html(iconeCarregando);//inclui o icone carregando na div
    //apresento a tela modal para o usuario
    $("#dlg1").dialog({
        modal: true,
        title: tituloDLG,
        href: getURLApp() + 'index.php?pg=' + url,
        loadingMessage: "Aguarde carregando os dados...",
        closed: false,
        buttons: botoes,
        method: type,
        queryParams: obj, //objeto com os dados que precisa ser enviado
        onLoad: function () {
            //return false;
            //return $('#rs').html(iconeCarregando);//inclui o icone carregando na div
            //jAlert("Carregado", "Sucesso!");
        },
//        loader: function (param) {//param: o objeto de parâmetro para passar para o servidor remoto. 
//            function success(data) {
//                console.log(data, param);
//            };
//            
//            function error(){
//                
//            }
//        },
//        onBeforeLoad: function (param) {
//            console.log(param);
//            //return false;
//            return $('#rs').html(iconeCarregando);//inclui o icone carregando na div
//        }

    });

    $("#dlg1").dialog('center'); //colocar janela no centro da região que eu estou na tela


//    $.ajax({
//        url: getURLApp() + 'index.php?pg=' + url,
//        //url: getURLApp() + 'index.php?pg=enviarRequisicao' + url,
//        dataType: retorno, // tipo de dado esperado como retorno pelo servidor (html, xml json)
//        type: type, // tipo de envio de dado para o servidor
//        timeout: 90000, //usado como tempo maximo de resposta da consulta da url - 90 segundos
//        // url enviarRequisicao
//        // se for passar na url nao precisa desse parametro, se for passar
//        // muitos parametros
//        // sugiro usar essa funcao comentada - Anders
//        data: dados, //objeto com os dados que precisa ser enviado
//        beforeSend: function () {// apresenta o icone animado antes de
//            // carregar os dados
//            $('#rs').empty();//limpo a div 
//            $('#rs').html(iconeCarregando);//inclui o icone carregando na div
//            //apresento a tela modal para o usuario
//            $("#dlg1").dialog({
//                modal: true,
//                title: tituloDLG,
//                closed: false,
//                buttons: botoes
//
//            });
//        },
//        complete: function () {// ao completar a requisicao remove o icone
//            // animado
//            $(iconeCarregando).remove();
//        },
//        success: function (dados, textStatus) {// carrega dos dados na div
//
//            //se for o retorn json entao dispara um alerta na tela
//            if (retorno === "json") {
//
////                $('#alerta').fadeOut(1);
////                //limpo o form
////                $(nomeForm).each(function () {
////                    this.reset();
////                });
//
//                //mostro o form
//                //$(nomeForm).show();
//
//                //se o retorno for true dispara um alerta de sucesso
//                if (dados.success) {
//                    //exibo um alerta
//                    jAlert(dados.msg, "Sucesso!");
//                    //senao dispara um alerta de erro 
//                } else {
//                    jError(dados.msg, "Erros encontrados!");
//                }
//
//                // se for html entao imprime na div o resultado   
//            } else {
//                //console.log(dados);
//                $('#rs').html(dados);//insiro os dados do arquivo listaDetalheEventos.php
//                //$("#rs").load(getURLApp() + 'index.php?pg=' + dados);
//                //jQuery('#dialog').load('path to my page').dialog('open');
//                //$('#rs').iframe({body: "<html><body>Some text</body></html>"}) // this variant moves a p to the iframe.
//
//
//            }
//        },
//        error: function (xhr, er) {// caso tenha erro apresenta o erro na tela
//            if (er === 'timeout') {
//                $('#rs').html(
//                        'Erro! ocorreu um erro de timeout com o Servidor!');
//            } else {
//                $('#rs').html(
//                        '<p>Error: ' + xhr.status + ' - ' + xhr.statusText
//                        + '< /br>tipo erro: ' + er + '</p>');
//            }
//        }
//    });
}

function salvarTft(url) {

    var iconeCarregando = retornaItemCarregando();

    $('#alterararTft').form('submit', {
        url: getURLApp() + 'index.php?pg=' + url,
        onSubmit: function () {
            var isValid = $(this).form('validate');
            //enquando nao valida pelo js o form eh apresentado na tela
            if (!isValid) {
                $("#boxProcessando").hide();
                $("#alterararTft").show();
                return false;
            } else {//se validou pelo js entao escondemos o form da tela e exibe carregando...
                $("#alterararTft").hide();
                $("#boxProcessando").show();
                $("#processando").html(iconeCarregando);
                return true;
            }

        },
        success: function (result) {
            var obj = $.parseJSON(result);
            //var result = eval('(' + result + ')');  // change the JSON string to javascript object
            //console.log(obj);
            $("#boxProcessando").hide();
            if (obj.success) {
                $.messager.alert('Sucesso', obj.lista, 'info', function () {
                    //window.location.reload();
                    //$(window.document.location).attr('href', result.redirect);
                    $("#alterararTft").form('clear');
                    $("#alterararTft").show();
                    $('#dlg1').dialog('close');
                    $('#dgBasico').datagrid('reload');    // reload the user data
                });

            } else {
                $.messager.alert('Erros Encotrados', obj.lista, 'error', function () {
                    $("#alterararTft").show();
                });
            }

        }
    });
}