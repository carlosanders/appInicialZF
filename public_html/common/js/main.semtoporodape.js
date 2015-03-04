$(document).ready(function () {

});

function getSelectEstado(registro) {
    
    console.log(registro);
    //monto a combo com os corpo refernte ao posto selecionado
    $('#cidaddessModal').combobox('clear');
    //$('#corpo').combobox('reset');
    $('#cidaddessModal').combobox('reload', getURLApp() + 'index.php?pg=apoio/comboboxCidades/false/' +  registro.UF + '/false/');
    
    //jAlert('resultado'+registro.POSTO, "Erros encontrados!");

}