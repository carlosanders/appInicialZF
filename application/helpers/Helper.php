<?php

/**
 * A classe Helper utilizada para realizar tarefas rotineiras
 * no sistema de um modo geral 
 *
 * @author SG PL Anders
 */
class Helper {    

    /**
     * Converte um arquvio binario em jpg
     * @param type $base64_string
     * @param type $output_file
     * @return type
     */
    public static function base64_to_jpeg($base64_string, $output_file) {
        $ifp = fopen($output_file, "wb");

        $data = explode(',', $base64_string);

        fwrite($ifp, base64_decode($data[1]));
        fclose($ifp);

        return $output_file;
    }

    public static function convertBinario($size) {
        $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
        return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }

    /**
     * Recebe uma massa de nips via text area e retorna os nips 
     * separados por virgulas
     * @param <string> $massa - contendo uma massa de nips
     * de um textArea
     * @return type
     * fonte: http://www.diogomatheus.com.br/blog/php/expressoes-regulares-no-php/
     */
    public static function retornaNipsSeparadosVirgula($massa) {
        //preg_replace - Busca e substitui, retornando todas as opções.
        //so considera numeros na string
        $nova_string = preg_replace("/[^0-9\s]/", "", $massa);
        //retira sinais de pontucao e caracteres brancos
        $er = '/[[:punct:][:space:]\s]\s*/';
        //logo apos substitui os espacos brancos e ponto por ","
        $pregFilter = preg_replace($er, ',', $nova_string);

        return $pregFilter;
    }

    /**
     * Recebe o nip menor que 8 caracteres e acrescenta o numero "0",
     * até completar os 8 caracteres depois acrescenta os "." no
     * formato correto do NIP
     * @example formataNips(6840680) - > 06.8406.80
     * 
     * @param type $nip - 6840680
     * @return type $nip - 06.8406.80
     */
    public static function formataNips($nip, $grid = false) {
        $tam = strlen($nip);

        if (strlen($nip) < 8) {
            for ($i = 0; $i < 8 - $tam; $i++) {
                $nip = substr_replace($nip, '0', 0, 0);
            }
        }

        if ($grid) {
            $nip = substr_replace($nip, '.', 3, 0);
            $nip = substr_replace($nip, '.', 8, 0);
        } else {
            $nip = substr_replace($nip, '.', 2, 0);
            $nip = substr_replace($nip, '.', 7, 0);
        }


        //echo $nip;
        return $nip;
    }

    public static function formataBoletim($str = "") {

        if ($str != "" && strlen($str) < 6) {
            $str = substr_replace($str, '0', 0, 0);
        }

        if (strlen($str) == 6) {
            $str = substr_replace($str, '-', 2, -4);
        }

        return $str;
    }

    /**
     * Recebe uma string e codifica na base64 com uma chave para 
     * para dificultar o decrypt
     * @param type $string
     * @param type $key - a chave padrao eh dpmm
     * @return String codificado 
     * referencia: http://tech.chitgoks.com/2008/03/24/php-encrypt-decrypt-using-base64/
     */
    public static function encrypt($string, $key = 'dpmm') {
        $result = '';
        //o loop diz que enquanto o '$i < $string' que o tamanho da string a criptografar 
        for ($i = 0; $i < strlen($string); $i++) {
            //retorna parte da string sempre retirando a 1ª posicao a cada interação
            $char = substr($string, $i, 1);
            //retorna parte da string, sendo q '$i Modulo $key' (resto da divisao $i por tam($key))
            //tamanho da chave - 1, retirando a 1ª posicao a cada interação
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            //a funcao ord($char) - retorna o valor ASCII da string $char
            //a funcao ord($keychar) - retorna o valor ASCII da string $keychar
            //a funcao chr(ord($char) + ord($keychar)) - retorna um caracter específico do
            //resultado das funcoes ord();
            $char = chr(ord($char) + ord($keychar));
            //a cada interação e concatenado ao result
            $result.=$char;
        }
        //apos a interaçao e feito ainda um outro encode na base 64
        //q Codifica dados com MIME base64 e durem no transporte sobre a camada de transporte
        $base64 = base64_encode($result);
        //a funcao strtr(), traduz q se encontrar na $base64 os valores '+/='
        //substitua por '-_,', para nao dar problemas na url pois a mesma usa esse tipo de caracter
        //e daí nao confunda como parte da url.
        $base64url = strtr($base64, '+/=', '-_,');
        //retorna a var codificada com a chave passada
        return $base64url;
    }

    /**
     * Recebe a string codificada na base64 e decodifica com a chave 
     * OBS.: A chave passado na var $key tem que ser a mesma da 
     * que foi codificado
     * @param type $string = codificado
     * @param type $key
     * @return String decodificado
     * referencia: referencia: http://tech.chitgoks.com/2008/03/24/php-encrypt-decrypt-using-base64/
     */
    public static function decrypt($string, $key = 'dpmm') {
        $result = '';
        //strtr -  traduz +/= por -_,
        $base64url = strtr($string, '-_,', '+/=');
        $string = base64_decode($base64url);

        //strlen - retorna o tamanho da string                   
        for ($i = 0; $i < strlen($string); $i++) {
            //substr - retorna uma parte de uma string
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            //chr - Retorna uma string de um único caractere contendo o caracter especificado pelo ascii
            //ord - Retorna o valor ASCII do primeiro caractere de string
            $char = chr(ord($char) - ord($keychar));

            $result.=$char;
        }

        return $result;
    }

    /**
     * Esta funcao recebe um nip com ponto como parametro 
     * Ex.: 06.8406.80 retira os pontos e retorna o NIP
     * sem os pontos para passar para o banco
     * @example formataNipsSemPontos(06.8406.80) -> retorna 06840680
     * referencia: http://php.net/manual/pt_BR/language.types.type-juggling.php
     * @param type $nip
     * @return $nip - 06840680
     */
    public static function formataNipsSemPontos($nip) {
        $nip = str_replace('.', '', $nip);
        return (Integer) $nip;
    }

    /**
     * Esta funceo "codifica" um parametro em forma de XML, pra ser mandado como 
     * servico pra algum outro lugar. Esta funceo 
     * recebe um OU dois parametros
     * @param mixed 
     * @return String o parametro dentro de tags XML
     *
     * @example toXML("nome","felipe") -> retorna "<nome>felipe</nome>"
     * @example toXML($stmt_ja_parseado_e_executado);
     * o certo e que, se o usuario passar $stmt retornado de uma query como unico parametro, 
     * a funceo retornasse 
     * todo o resultado formatado como XML
     */
    public static function toXML() {
        $numeroDeParametros = func_num_args();

        if ($numeroDeParametros == 2) {
            $tag = func_get_arg(0);
            $conteudo = func_get_arg(1);

            $output = "<" . $tag . ">";
            $output.= $conteudo;
            $output.= "</" . $tag . ">";

            return $output;
        } elseif ($numeroDeParametros == 1) {
            //NAO TESTADA
            $stmt = func_get_arg(0);
            $buffer = "";
            foreach ($stmt as $coluna => $valor) {
                $buffer .=funcoesUteis::toXML($coluna, $valor);
            }
            $output = $buffer;
            return $output;
        }
    }

    /**
     * Retorna um URL válida no sitema
     * @param type $controller
     * @param type $action
     * @param type $item
     */
    public static function getURL($controller, $action = null, $item = null) {
        $action = $action ? $action : DEFAULT_ACTION;

        return ROOT_URL . "/" . "/{$controller}/{$action}/{$item}";
    }

    /**
     * Função para transformar strings em Maiúscula ou Minúscula com acentos 
     * $palavra = a string propriamente dita 
     * @param type $term (String) com a palavra ou texto para conversao
     * @param type $tp (Integer) tipo da conversão: 1 para maiúsculas e 0 para minúsculas 
     * @return type $palavra convertida.
     * Fonte:http://dourado.net/2007/05/15/php-converter-string-para-maiuscula-ou-minuscula-com-acentos/
     */
    public static function converteMaiscMinusc($term, $tp) {
        if ($tp == 1)
            $palavra = strtr(strtoupper($term), "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
        elseif ($tp == 0)
            $palavra = strtr(strtolower($term), "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß", "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ");
        return $palavra;
    }

    /**
     * Converta acentos para padrao iso-8951
     * @param type $txt
     * @return type
     */
    public static function converterAcentos($txt) {

        $acentos = array('á', 'à', 'ã', 'â', 'ä', 'é', 'è', 'ê', 'ë', 'í', 'ì', 'î', 'ï', 'ó', 'ò', 'õ', 'ô', 'ö', 'ú', 'ù', 'û', 'ü', 'ç', 'Á', 'À', 'Ã', 'Â', 'Ä', 'É', 'È', 'Ê', 'Ë', 'Í', 'Ì', 'Î', 'Ï', 'Ó', 'Ò', 'Õ', 'Ô', 'Ö', 'Ú', 'Ù', 'Û', 'Ü', 'Ç', 'ñ', 'Ñ', '\'', '\'');

        $acentos_html = array('&aacute;', '&agrave;', '&atilde;', '&acirc;', '&auml;',
            '&eacute;', '&egrave;', '&ecirc;', '&euml;', '&iacute;', '&igrave;',
            '&icirc', '&iuml;', '&oacute;', '&ograve;', '&otilde;', '&ocirc;',
            '&ouml;', '&uacute;', '&ugrave;', '&ucirc;', '&uuml;', '&ccedil;',
            '&Aacute;', '&Agrave;', '&Atilde;', '&Acirc;', '&Auml;', '&Eacute;',
            '&Egrave;', '&Ecirc;', '&Euml;', '&Iacute;', '&Igrave;', '&Icirc',
            '&Iuml;', '&Oacute;', '&Ograve;', '&Otilde;', '&Ocirc;', '&Ouml',
            '&Uacute;', '&Ugrave;', '&Ucirc;', '&Uuml;', '&Ccedil;', '&ntilde;', '&Ntilde;', '&acute;', '&acute;');

        $texto_convertido = str_replace($acentos, $acentos_html, $txt);

        return $texto_convertido;
    }

    /**
     * Traduz o valor do operador do filtro
     * @param type String
     * @return string
     */
    public static function traduzirOperador($operador) {

        switch ($operador) {

            case "igual": return "=";
            case "diferente": return "<>";
            case "maior": return ">";
            case "maior_igual": return ">=";
            case "menor": return "<";
            case "menor_igual": return "<=";
            case "=": return "=";
            case "<>": return "<>";
            case ">": return ">";
            case ">=": return ">=";
            case "<": return "<";
            case "<=": return "<=";

            default: return "";
        }
    }

    /**
     * 
     * @param int $total_linhas
     * @param int $linhas_por_pagina
     * @return int arredondado para cima
     */
    public static function total_paginas($total_linhas, $linhas_por_pagina) {
        if ($total_linhas < 1)
            $total_linhas = 1;
        return ceil($total_linhas / $linhas_por_pagina);
    }

    /**
     * 
     * @param int $pagina_corrente
     * @param int $linhas_por_pagina
     * @return int
     */
    public static function linhas_paginacao($pagina_corrente, $linhas_por_pagina) {
        $linha_inicial = ($pagina_corrente - 1) * $linhas_por_pagina + 1;
        return $linha_inicial;
    }

    /**
     * Lista de Operacoes do Sistemas de Eventos
     * @return type
     */
    public static function listaOperacoes() {
        $lista = array(
            'I' => self::converterAcentos('Inserção'),
            'A' => self::converterAcentos('Alteração'),
            'E' => self::converterAcentos('Exclusão')
        );

        return $lista;
    }

    /**
     * lista cículos da mb
     * @return type
     */
    public static function listaCirculosMB() {
        $lista = array(
            1 => self::converterAcentos('Oficiais'),
            2 => self::converterAcentos('Praças'),
            3 => self::converterAcentos('Praças Especiais')
        );

        return $lista;
    }

    public function estadoCivl() {
        $lista = array(
            1 => self::converterAcentos('Solteiro'),
            2 => self::converterAcentos('Casado'),
            3 => self::converterAcentos('Viúvo'),
            4 => self::converterAcentos('Separado Judicialmente'),
            5 => self::converterAcentos('Divorciado'),
            9 => self::converterAcentos('Não Informado')
        );

        return $lista;
    }

    public static function listaSexo() {
        $lista = array(
            'm' => 'Masculino',
            'f' => 'Feminino',
        );

        return $lista;
    }

    /**
     * Essa funçao retorna vrf erros de JSON e corrige caso o erro seja o 5, retornando
     * o valor correto do caracter e apresentando os outros erros caso eles ocorram
     * 
     * @link http://php.net/manual/pt_BR/function.json-last-error.php Manual
     * @link http://stackoverflow.com/questions/10199017/how-to-solve-json-error-utf8-error-in-php-json-decode Fonte
     * 
     * Os erros comuns no JSON são:
     * 0 = JSON_ERROR_NONE - Não ocorreu nenhum erro
     * 1 = JSON_ERROR_DEPTH - A profundidade máxima da pilha foi excedida
     * 2 = JSON_ERROR_STATE_MISMATCH - JSON inválido ou mal formado
     * 3 = JSON_ERROR_CTRL_CHAR - Erro de caractere de controle, possivelmente codificado incorretamente
     * 4 = JSON_ERROR_SYNTAX - Erro de sintaxe
     * 5 = JSON_ERROR_UTF8 - caracteres UTF-8 malformado , possivelmente codificado incorretamente
     * 
     * @param string $value - que deseja ser codificado com json_encode
     * @return string  - codificado com JSON
     */
    public static function safe_json_encode($value)
    {
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            $encoded = json_encode($value, JSON_PRETTY_PRINT);
            //var_dump($encoded);
        } else {
            $encoded = json_encode($value);
        }
        //var_dump("erro:", json_last_error());
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $encoded;
            case JSON_ERROR_DEPTH:
                        //A profundidade máxima da pilha foi excedida
                return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
            case JSON_ERROR_STATE_MISMATCH:
                        //JSON inválido ou mal formado
                return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
            case JSON_ERROR_CTRL_CHAR:
                //Erro de caractere de controle, possivelmente codificado incorretamente
                return 'Unexpected control character found';
            case JSON_ERROR_SYNTAX:
                 //Syntax error, malformed JSON
                return 'Erro de sintaxe, JSON malformado'; // or trigger_error() or throw new Exception()
            case JSON_ERROR_UTF8:
                //caracteres UTF-8 malformado , possivelmente codificado incorretamente
                $clean = self::utf8ize($value);
                return self::safe_json_encode($clean);
            default:
                //Unknown error
                return 'Erro desconhecido no JSON'; // or trigger_error() or throw new Exception()
        }
    }

    /**
     * Auxilia na formatação correta do caracter invalido
     * @param type $mixed
     * @return type
     */
    private static function utf8ize($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = self::utf8ize($value);
            }
        } else if (is_string($mixed)) {
            return utf8_encode($mixed);
        }
        return $mixed;
    }

}
