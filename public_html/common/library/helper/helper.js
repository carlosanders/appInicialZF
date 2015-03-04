/**
 * O tempo de incremento para jQuery Timer é em milissegundos. Assim, um
 * O tempo de entrada de 1000 seria igual a uma vez por segundo. neste
 * Exemplo usamos um tempo incremento de 70, que é de aproximadamente 14
 * Vezes por segundo. Você pode ajustar o timer, se desejar.
 * ref.: http://jchavannes.com/jquery-timer/demo
 * @type @new;_L4
 
*/

/**
 * funcao para retornar
 * @returns {String}
 */
function getURLApp(){
    var rootURL = "/";
    
    return rootURL;
}

// Common functions
function pad(number, length) {
    var str = '' + number;
    while (str.length < length) {
        str = '0' + str;
    }
    return str;
}
function formatTime(time) {
    var min = parseInt(time / 6000),
            sec = parseInt(time / 100) - (min * 60),
            hundredths = pad(time - (sec * 100) - (min * 6000), 2);
    return (min > 0 ? pad(min, 2) : "00") + ":" + pad(sec, 2) + ":" + hundredths;
}

/*metodo para vrf se a string eh nr inteiro */
function isInteger(s) {
    var i;
    if ((s == null) || (s.length == 0))
        if (isInteger.arguments.length == 1)
            return false;
    for (i = 0; i < s.length; i++)
    {
        var c = s.charAt(i);
        if (!((c >= "0") && (c <= "9")))
            return false;
    }
    return true;
}
/* validar nip */
function validanip(id) {
    var d1 = 0;
    var d2 = 0;
    var total = id.length;
    if (total > 8) {
        return false;
    } else {
        for (var i = 1; i <= (8 - total); i++)
            id = "0" + id;
        d2 = id.charAt(7);
        //console.log(d2);
        for (i = 0; i <= 6; i++)
            d1 += id.charAt(i) * (8 - i);
        //console.log(d1);
        d1 = 11 - (d1 % 11);
        //console.log(d1);
        if (d1 == 11)
            d1 = 1;
        if (d1 == 10)
            d1 = 0;
        if (d1 != d2)
            return false;
        else
            return true;
    }

}
/* metodo para validar cpf*/
function valida_cpf(cpf) {
    var numeros, digitos, soma, i, resultado, digitos_iguais;
    digitos_iguais = 1;
    if (cpf.length < 11 || cpf == "01234567890" || cpf == "12345678909")
        return false;

    for (i = 0; i < cpf.length - 1; i++)
        if (cpf.charAt(i) != cpf.charAt(i + 1))
        {
            digitos_iguais = 0;
            break;
        }
    if (!digitos_iguais)
    {
        numeros = cpf.substring(0, 9);
        digitos = cpf.substring(9);
        soma = 0;
        for (i = 10; i > 1; i--)
            soma += numeros.charAt(10 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
            return false;
        numeros = cpf.substring(0, 10);
        soma = 0;
        for (i = 11; i > 1; i--)
            soma += numeros.charAt(11 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
            return false;
        return true;
    }
    else
        return false;
}
/* metodo par retirar acentos da string*/
function retiraAcento(obj) {
    var palavra = String.fromCharCode(event.keyCode);

    var caracteresInvalidos = 'àèìòùâêîôûäëïöüáéíóúãõÀÈÌÒÙÂÊÎÔÛÄËÏÖÜÁÉÍÓÚÃÕ';
    var caracteresValidos = 'aeiouaeiouaeiouaeiouaoAEIOUAEIOUAEIOUAEIOUAO';
    var acento = "´`^¨~";
    if (acento.indexOf(palavra) != -1)
    {
        window.event.keyCode = 0;
    }

    if (caracteresInvalidos.indexOf(palavra) == -1)
    {
        if (caracteresValidos.indexOf(palavra) != -1) {
            window.event.keyCode = 0;
            obj.value = obj.value + palavra;
        }
    }
    else
    {
        window.event.keyCode = 0;
        var nova = caracteresValidos.charAt(caracteresInvalidos.indexOf(palavra));
        obj.value = obj.value + nova;
    }

}
/* metodo para encotrar e substituir caracteres
 * @param {string} string
 * @param <string> encontrar
 * @param {type} substituir
 * @returns {unresolved}
 */
function replace_all(string, encontrar, substituir) {
    while (string.indexOf(encontrar) >= 0)
        string = string.replace(encontrar, substituir);
    return string;
}

// converte minusculas em maiusculas
function up(lstr) {
    var str = lstr.value; //obtem o valor
    lstr.value = str.toUpperCase(); //converte as strings e retorna ao campo
}

// converte maiusculas em minusculas
function down(lstr) {
    var str = lstr.value; //obtem o valor
    lstr.value = str.toLowerCase(); //converte as strings e retorna ao campo
}

function retornaItemCarregando() {
    var iconeCarregando = $('<center><img src="/common/imagens/loading2.gif" class="icon" border="0" /><br />' +
            '<span class="destaque">Processando, aguarde ...</sapn></center>');
    return iconeCarregando;
}

function retornaItemOk() {
    var iconeCarregando = $('<center><img src="/common/imagens/tree_dnd_yes.png" class="icon" border="0" /><br />' +
            '<span class="destaque">Arquivo processado, com sucesso!</sapn></center>');
    return iconeCarregando;
}

/**
 * Verifica se o caracter eh numero se for retorna true senao false
 * @param {type} e
 * @returns {Boolean}
 */
function soNums(e) {
    //teclas adicionais permitidas (tab,delete,backspace,setas direita e esquerda)
    var keyCodesPermitidos = new Array(8, 9, 13, 37, 39, 46);

    //numeros e 0 a 9 do teclado alfanumerico
    for (var x = 48; x <= 57; x++) {
        keyCodesPermitidos.push(x);
    }

    //numeros e 0 a 9 do teclado numerico
    for (var x = 96; x <= 105; x++) {
        keyCodesPermitidos.push(x);
    }

    //Pega a tecla digitada
    var keyCode = e.which;

    //Verifica se a tecla digitada é permitida
    if ($.inArray(keyCode, keyCodesPermitidos) != -1) {
        return true;
    }
    return false;
}

/**
 * Verifica se existe a tag html
 * @param {type} passar o id da tag html
 * @returns {Boolean} true se existir ou false caso contrario
 */
function existeSeletor(sel) {
    if (typeof $(sel).prop('id') !== 'undefined') {
        return true;
    } else {
        return false;
    }
}

/**
 * formato de entrada 02/07/2014
 * Valida a data se eh válida
 * @param {type} text
 * @returns {Boolean}]
 */
function vrfData(text) {

    var date = Date.parse(text);

    if (isNaN(date)) {
        return false;
    }
    //divido a string delimitando pela barra /
    var comp = text.split('/');

    //vrf se existem 3 elementos que representa a data
    if (comp.length !== 3) {
        return false;
    }

    //atribuo cada elemento e transformo em int para repassar ao objeto data
    var m = parseInt(comp[1], 10);
    var d = parseInt(comp[0], 10);
    var y = parseInt(comp[2], 10);
    //construo o objeto data no js
    var date = new Date(y, m - 1, d);

    //vrf se a data eh maior que a data de hj do sistema do usuario
    if (date.getTime() > new Date().getTime()) {
        return false;
    }
    //vrf se a data informa eh invalida se for sera retornado false
    return (date.getFullYear() == y && date.getMonth() + 1 == m && date.getDate() == d);
}

/**
 * Valida a data se eh válida
 * @param {type} text
 * @returns {Boolean}]
 */
function validDate(text) {

    var date = Date.parse(text);

    if (isNaN(date)) {
        return false;
    }

    var comp = text.split('/');

    if (comp.length !== 3) {
        return false;
    }

    var m = parseInt(comp[1], 10);
    var d = parseInt(comp[0], 10);
    var y = parseInt(comp[2], 10);
    var date = new Date(y, m - 1, d);

    return (date.getFullYear() == y && date.getMonth() + 1 == m && date.getDate() == d);
}
/**
 * Verifica se existe a tag html
 * @param {type} passar o id da tag html
 * @returns {Boolean} true se existir ou false caso contrario
 */
function existeSeletor(sel) {
    if (typeof $(sel).prop('id') !== 'undefined') {
        return true;
    } else {
        return false;
    }
}

/**
 * Funcao que retira os pontos do nip e valida
 * @param String nroNIP
 * ser o retorno = true esta válido
 *       retorno = false erro
 * @returns {Boolean}
 */
function formataEVerificaNIP(nroNIP) {
    nroNIP = replace_all(nroNIP, ".", "");
    var vNIP = !isInteger(nroNIP) ? "" : parseInt(nroNIP, 10);
    
    if ((nroNIP !== "") && !isInteger(nroNIP)) {
        return false;
    }
    
    return vNIP;
}