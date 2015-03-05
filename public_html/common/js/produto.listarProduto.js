$(document).ready(function () {

    //criado o metodo focus para easyUI
    $('#inptCodProd').numberbox('focus');
    //$('#inptCodProd').next().find('input').focus();

    //ao carregar a pagina desabilita a validacao
    $("#frmProduto").form("disableValidation");
});

function onDblClickRowDG(index, row) {

    if (row) {
        $('#frmProduto').form('load', {
            inptCodProd: row.productid,
            inptPrecoAnt: row.unitcost,
            inptPrecoAtual: row.listprice,
            inptDescricao: row.productname,
            cbCategoria: row.attr1,
            status: row.status,
            cbEstados: row.uf_estado
        });
    }


}

function salvarProduto(idFrm, url, idDG) {
    var iconeCarregando = retornaItemCarregando();

    //console.log( $( idFrm ).serialize() );    
    //console.log($( idFrm ).serializeArray());
    //inica a validacao
    $(idFrm).form("enableValidation");
    //processa o form
    $(idFrm).form('submit', {
        //url:"http://appdpmm.dev:81/index.php?pg=produto/salvar",
        url: getURLApp() + 'index.php?pg=' + url,
        queryParams: {
            //registros:$( idFrm ).serializeArray()
        },
        onSubmit: function (param) {

            param.url = url;
            param.idForm = idFrm;

            var isValid = $(this).form('validate');
            //enquando nao valida pelo js o form eh apresentado na tela
            if (!isValid) {
                $("#processando").hide();
                $(idFrm).show();
                return false;
            } else {//se validou pelo js entao escondemos o form da tela e exibe carregando...
                $(idFrm).hide();
                $("#processando").show();
                $("#processando").html(iconeCarregando);
                return true;
            }


        },
        success: function (result) {
            var rs = $.parseJSON(result); //transforma string em objeto json
            // jAlert('Quadro só poderá ser vazio quando o corpo for preenchido com EN', "Erros encontrados!");
            $("#processando").hide();
            if (rs.success) {
                $.messager.alert('Sucesso', rs.msg, 'info', function () {
                    //window.location.reload();
                    //$(window.document.location).attr('href', result.redirect);
                    $(idFrm).form('clear');
                    $(idFrm).form("disableValidation");
                    $(idFrm).show();
                    $(idDG).datagrid('reload');    // reload the user data
                });

            } else {
                $.messager.alert('Erros Encotrados', rs.msg, 'error', function () {
                    $(idFrm).show();
                });
            }

        }
    });
}

function exibiAcaoDGProdutos(value, row, index) {
    //var stringOBJ = JSON.stringify(dados);
    //console.log(stringOBJ);
    var e = '<a class="linkAcao" title="Exibir Eventos" \n\
                href="javascript:modalProdutos('
            + "'main/semtoporodape2'," // url
            + "'POST'," // type GET or POST
            + "'Justificativa!'," // titulo            
            + "'#dgListarProdutos'," // id do DG
            + "'#dlg1'" // id do Dialog
            + ');">\n\
            <img border="0" src="' + getURLApp() + 'common/imagens/zoom_in.png" class="first" border="0"/></a>';
    return e;
}

function modalProdutos(url, type, tituloDLG, idDG, idDlg) {

    //retorno = (retorno === "") ? "html" : retorno;
    type = (type === null) ? "GET" : type;

    //apresento a tela modal para o usuario
    $(idDlg).dialog({
        modal: true,
        title: tituloDLG,
        href: getURLApp() + 'index.php?pg=' + url,
        loadingMessage: "Aguarde carregando os dados...",
        closed: false,
        buttons: [{
                text: 'Cancelar',
                iconCls: 'icon-cancel',
                handler: function () {
                    $('#dlg1').dialog('close');
                    $('#rs').empty();
                }
            }],
        method: type,
        queryParams: {
            rows: $(idDG).datagrid('getSelected')
        } //objeto com os dados que precisa ser enviado
    });

    $(idDlg).dialog('center'); //colocar janela no centro da região que eu estou na tela

}


function recarregarDadosDG(idDG) {


    var status = $('#status').combobox('getValue');
    var estados = $('#cbEstados').combobox('getValue');

    $(idDG).datagrid({
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

function clearFormDadosDG(idDG) {

    $(idDG).datagrid({
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


