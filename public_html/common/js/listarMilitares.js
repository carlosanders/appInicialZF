/* 
 * JS personalizado da aplicacaos
 */
$(function() {

	// config para DG militares da OM - tela Eventos/doListarEventos.tpl
	$('#dgMilitares').datagrid({
		striped : false,
		singleSelect : true,
		// loadMsg: "Processando por-favor aguarde ...",
		pageSize : 20,
		pageList : [ 20, 50, 100, 500, 1000, 5000, 10000 ]
	});

	// funcao para colocar botoes na paginacao do datagrid
	$(function() {
		if (existeSeletor("#dgMilitares")) {
			var pager = $('#dgMilitares').datagrid('getPager'); // get the pager
			// of datagrid
			pager.pagination({
				showPageList : true,
				buttons : [ {
					iconCls : 'icon-xls16',
					handler : function() {
						// alert('search');
						getXLS();
					}
				} ]
			});
		}

	});

	$(function() {
		// $("#nroNIP").mask("99.9999.99");
		$('#nroNIP').bind('keydown', soNums); // o "#nroNIP" é o input que vc
		// quer aplicar a funcionalidade
	});

	// altera o radio button para estilo jQueryUI personalizado
	$("#rad").buttonset();
	$("#carrega, #selecionado, #voltar, #salvar").button();

	// altera o combobox html para jQueryUI
	// $("#postoGraducacao").combobox();
	// $("#omsAtivas").combobox();
	$("#evento").combobox();
	$("#omDoc").combobox();
	$("#tipo").combobox();
	$("#parametro, #tipoOP").combobox();

	// ToolTip 2 do proprio jQuery
	/* SG Anders */
	// exemplo 2 do proprio jQuery
	$("label, #salvar, #link-text-sistema").tooltip({// Esta linha cria
		// tooltip e passa os
		// paramentros abaixo
		track : true, // Para que possa se movimentar
		delay : 0, // Tempo de carregamento
		showURL : false,
		showBody : "-", // o que esta dividindo o titulo e o conteudo
		top : -15, // Espaçamento do topo
		// tooltipClass: "ui-state-highlight",
		left : 5, // Espaçamento da direita
		fixPNG : true
	// extraClass: "right",
	// fade: 250
	});

	// var codOM = $('input[name=codOM]').val();
	// jAlert("anders"+codOM, "Erros encontrados!");

	$('#omsAtivas').combobox(
			{
				url : '/index.php?pg=Apoio/listarOMsAtivas/'
						+ $('input[name=codOM]').val() + '/false',
				valueField : 'CODOM',
				textField : 'NOMABREVIADO',
				panelWidth : 400,
				panelHeight : 300,
				formatter : formataCombo,
				mode : 'local', // qndo for definido como local eh possivel usar
				// um filtro no campo
				// que o usario esta digitando - q: é o q o usuario esta
				// digitando e row: lista de dados
				filter : function(q, row) {
					var opts = $(this).combobox('options');// var com as opcoes
					// da combo
					var r = q.toUpperCase();// convertendo para maisuculo o q o
					// usu digitou
					return row[opts.textField].indexOf(r) === 0;// retorna os
					// dados
					// encontrados
				}
			});

	// funcao para submeter o form atraves do tecla "enter"
	// ref.:
	// http://forum.baboo.com.br/index.php?/topic/766706-jquery-validar-formul%C3%A1rio-com-tecla-enter/
	$('input').keypress(function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code === 13) { // Enter keycode
			// Do something
			$(this).parents('form').find('#btnConsulta').trigger('click');
		}
	});

	// config. para o action do form da tela Eventos/listarEventos.tpl
	$("#resultadoFiltro").fadeOut("fast");// oculto com efeito rapido a div
	// Função $.get() ao clique do botao do formulario
	$("#listaMil").bind(
			'submit',
			function(e) {
				e.preventDefault();
				// pegando os dados dos campos do formulario
				// var circuloHierarquico = $('input[name="divisao"]').val();
				var circuloHierarquico = $('input[name=divisao]:radio:checked')
						.val();
				// var filtro = $('input[name=filtro]:radio:checked').val();
				// dados do combobox
				// var sel = $('#postoGraducacao').get(0);
				// var txt = sel.options[sel.selectedIndex].text;
				// var val = sel.options[sel.selectedIndex].value;
				var val = $('#postoGraducacao').combobox('getValue');
				var nronip = $('#nroNIP').val();
				var pessoa = $('#pessoa').val();
				var user = $('#userCOD').val();
				var codOM = $('#codOM').val();
				// var item = $('#omsAtivas').get(0);
				// var valorItemOM = item.options[item.selectedIndex].value;
				// var txtOM = item.options[item.selectedIndex].text;

				var erros = 0;
				var msg = '';
				var acao = false;

				// se o codOm for vazio eh porque o perfil eh geral
				if (codOM === '' || codOM === null) {
					// entao existe uma comboBox omsAtivas
					var comboOM = $('#omsAtivas').combobox('getValue');
				}

				var om = (codOM === '') ? comboOM : codOM;

				if (circuloHierarquico === undefined && val === ''
						&& nronip === '' && pessoa === '' && om === '') {
					acao = true;
				}

				var tit = '';
				if (acao) {
					tit += 'Por favor entre com algum dado para a consula!';
					jAlert(tit, "Erros encontrados!");
				} else {
					// var rows = $('#dgMilitares').datagrid('getRows');
					// console.log(rows.length);
					// config para DG militares da OM - tela
					// Eventos/doListarEventos.tpl
					$('#dgMilitares').datagrid('load', {
						nip : nronip,
						pessoa : pessoa,
						codOM : (codOM === '') ? comboOM : codOM,
						tipo : circuloHierarquico,
						val : val,
						pageList : [ 20, 50, 100, 500, 1000, 5000, 10000 ]
					/*
					 * , onLoadSuccess: function() { var d =
					 * $('#dgMilitares').datagrid('getData'); if (d.total == 0) {
					 * $.messager.show({ title: 'Info', msg: 'Nenhum dado ' +
					 * d.total + ' carregado!' }); } }
					 */
					});
				}
				return false;
			});

});

// funcao para preencher a combobox do posto e graduação com o valor passado
function getValor(valor) {
	// monto a combo com os postos e graduacoes
	$('#postoGraducacao').combobox({
		url : '/index.php?pg=Apoio/listarPostoGraduacao/false/&id=' + valor,
		valueField : 'CODPOSTOGRADUACAO',
		textField : 'DSCPOSTOGRADUACAO'
	});
	// recarrego o datagrid com os dados do banco
	$('#dgMilitares').datagrid({
		queryParams : {// caso deseja passar outros paramentros do form pelo
			// componente datagrid
			tipo : valor
		},

	// url: "/index.php?pg=Main/dgMilitaresOM/" + valor + "&user=",
	});

}

function clearForm(codOM) {

	var user = $('#userCOD').val();
	// remove selecao jqueryUI
	$('#rad input').removeAttr('checked');
	// recarrega o radio button jqueryUI
	$("#rad").buttonset('refresh');
	$('#nroNIP').val('');
	$('#pessoa').val('');
	$('#postoGraducacao').combobox('clear');
	$('#omsAtivas').combobox('clear');
	$('#postoGraducacao').combobox('reload',
			'/index.php?pg=Apoio/listarPostoGraduacao/false/&id=');
	$('#omsAtivas').combobox('reload',
			'/index.php?pg=Apoio/listarOMsAtivas//false&codOM=');
	// limpa os campos jeasyUI
	// $('#listaMil').form('clear');
	// $('#dgMilitares').datagrid('reload', '/index.php?pg=Main/dgMilitaresOM/'
	// + codOM + '/&user=' + user);
	$('#dgMilitares').datagrid('load', {
		tipo : "",
		codOM : codOM
	});
}

// func para localizar nip/nome no DataGrid da tela eventos_doListarEventos.tpl
function doSearch() {
	$('#dgMilitares').datagrid('load', {
		nip : $('#nip').val(),
		pessoa : $('#pessoa').val(),
		pageList : [ 20, 50, 100, 500, 1000, 5000, 10000 ]
	});
}

function formataCombo(row) {
	var s = '<span style="font-weight:bold">' + row.NOMABREVIADO
			+ '</span><br/>' + '<span style="color:#888">' + row.NOMOM
			+ '</span>';
	return s;
}

// funcao para formatar as linhas do grid de miltares
function formatAction(value, row, index) {
	var e = '<a class="linkAcao" title="Exibir Eventos" href="javascript:void(0)" \n\
              onclick="editrow(\''
			+ row.NIPCODFICADO
			+ '\', '
			+ row.USERID
			+ ')">\n\
                <img border="0" src="/common/imagens/zoom_in.png" class="first" border="0"/></a>';
	return e;
}

// funcao para pegar o indice da linha do datagrid
function getRowIndex(target) {
	var tr = $(target).closest('tr.datagrid-row');
	return parseInt(tr.attr('datagrid-row-index'));
}
// funcao para chamar a tela para exibir os
// eventos para edicao do evento do militar
function editrow(nronip, userid) {
	// $(window.document.location).attr('href',
	// 'index.php?pg=Eventos/listarEventosMilitar/' + nronip + '&user=' +
	// userid);
	$.messager.show({
		title : 'Título do Dialog',
		msg : 'Mensagem simples NIP: ' + nronip,
		showType : 'slide',
		style : {
			right : '',
			//top : document.body.scrollTop + document.documentElement.scrollTop,
			bottom : ''
		}
	});
}

function getXLS() {
	var iconeCarregando = retornaItemCarregando();
	var ss = [];
	var rows = $('#dgMilitares').datagrid('getRows');
	// console.log(rows.length);
	var codOM = $('#codOM').val();
	if (codOM === '' || codOM === null) {
		// entao existe uma comboBox omsAtivas
		var comboOM = $('#omsAtivas').combobox('getValue');
	}

	$.ajax({
		url : '/index.php?pg=Main/gerarXLS/&user=' + rows[0].USERID,
		dataType : 'html', // tipo de dado esperado como retorno pelo servidor
		type : 'POST', // tipo de envio de dado para o servidor
		// timeout: 106000, //usado como tempo maximo de resposta da consulta da
		// url
		// se for passar na url nao precisa desse parametro, se for passar
		// muitos parametros
		// sugiro usar essa funcao comentada - Anders
		data : {
			nip : $('#nroNIP').val(),
			pessoa : $('#pessoa').val(),
			codOM : (codOM === '') ? comboOM : codOM,
			tipo : $('input[name=divisao]:radio:checked').val(),
			val : $('#postoGraducacao').combobox('getValue'),
			rows : rows.length
		},
		beforeSend : function() {// apresenta o icone animado antes de
			// carregar os dados
			$('#resultadoFiltro').show();
			$('#resultadoFiltro').html(iconeCarregando);
		},
		complete : function() {// ao completar a requisicao remove o icone
			// animado
			$(iconeCarregando).remove();
		},
		success : function(dados, textStatus) {// carrega dos dados na div
			// $.messager.alert('Info', rows.length);
			//$('#resultadoFiltro').html(dados);
			window.location = "/excel/" + dados;
		},
		error : function(xhr, er) {// caso tenha erro apresenta o erro na tela
			if (er == 'timeout') {
				$('#resultadoFiltro').html(
						'Erro! ocorreu um erro de timeout com o Servidor!');
			} else {
				$('#resultadoFiltro').html(
						'<p>Error: ' + xhr.status + ' - ' + xhr.statusText
								+ '< /br>tipo erro: ' + er + '</p>');
			}
		}
	});

	// $.messager.alert('Info', ss.join('<br/>'));
	// $.messager.alert('Info', rows[0].USERID);
}
