<?php

/**
 * Description of FuncoesMatematicas Úteis
 *
 * @author Sg Anders
 */
class FuncoesMatematicas {

    /**
     * Calcula a media de um vetor de numeros
     * @param array $a Vetor de numeros
     * @return number Retorna a media dos valores do vetor
     */
    public static function media_aritmetica(array $a) {
        return array_sum($a) / count($a);
    }

    /**
     * Retorna o valor que mais aparece no vetor (moda estatistica)
     * @param array $a Vetor de valores
     * @param int $quantidade Quantidade de vezes que a moda foi observada
     * @return array Valores mais observados no vetor
     */
    public static function moda(array $a, &$quantidade = 0) {
        $moda = array();
        if (empty($a)) {
            return $moda;
        }

        // Calcular quantidade de ocorrencias de cada valor
        $ocorrencias = array();
        foreach ($a as $valor) {
            $valor_str = var_export($valor, true);
            if (!isset($ocorrencias[$valor_str])) {
                $ocorrencias[$valor_str] = array(
                    'valor' => $valor,
                    'ocorrencias' => 0
                );
            }
            $ocorrencias[$valor_str]['ocorrencias'] += 1;
        }

        // Determinar maior ocorrencia
        $quantidade = null;
        foreach ($ocorrencias as $item) {
            if ($quantidade === null || $item['ocorrencias'] >= $quantidade) {
                $quantidade = $item['ocorrencias'];
            }
        }

        // Obter valores com a maior ocorrencia
        foreach ($ocorrencias as $item) {
            if ($item['ocorrencias'] == $quantidade) {
                $moda[] = $item['valor'];
            }
        }
        return $moda;
    }

    /**
     * Obtem a mediana de um vetor de numeros.
     * @param array $a Vetor de numeros
     * @param callback $comparacao Funcao de comparacao para ordenar o vetor (ou null para usar a funcao sort para ordenar)
     * @return number || bool Mediana do vetor ou false, caso seja passado um vetor vazio
     */
    public static function mediana(array $a, $comparacao = null) {
        if (empty($a)) {
            return false;
        }

        // Ordenar o vetor
        if ($comparacao === null) {
            sort($a);
        } else {
            usort($a, $comparacao);
        }

        $tamanho = count($a);

        // Tamanho impar: obter valor mediano
        if ($tamanho % 2) {
            $mediana = $a[(($tamanho + 1) / 2) - 1];

            // Tamanho par: obter a media simples entre os dois valores medianos
        } else {
            $v1 = $a[($tamanho / 2) - 1];
            $v2 = $a[$tamanho / 2];
            $mediana = ($v1 + $v2) / 2;
        }
        return $mediana;
    }

    /**
     * Obtem o Desvio Padrao de um vetor de numeros.
     * @param array $valores - Vetor de numeros
     * @return number || false, caso seja passa um vetor vazio
     */
    public static function desvioPadrao(array $valores, $arredonda) {
        if (empty($valores)) {
            return false;
        }

        $tamanho = count($valores);
        $media = array_sum($valores) / $tamanho;
        $fVariance = 0.0;

        //para cada valor, soma a diferença entre o valor e a média
        foreach ($valores as $valor) {
            $fVariance += pow($valor - $media, 2);
        }
        //divide o valor total da variança pelo quantidade de itens do array menos 1
        $fVariance /= ($tamanho - 1);
        
        // se a variavel for true ele arredonda
        if($arredonda) {
            //retorna a raiz quadrada da variança
            return (float) FuncoesMatematicas::arredondarDoisDecimal(sqrt($fVariance));
        }else{
            //retorna a raiz quadrada da variança
            return (float) sqrt($fVariance);
        }
    }

    /**
     * Retorna o valor arredondado com dois decimais, através da funcao round()
     * do PHP.
     * Dado um valor, primeiro vamos multiplicá-lo por 100, vamos arredondar e finalmente, 
     * vamos dividir o resultado outra vez por 100.
     * http://www.criarweb.com/artigos/arredondar-decimais-em-php.html
     * @param type $valor - Um valor float ou number
     * @return number
     */
    public static function arredondarDoisDecimal($valor) {

        $result = round($valor * 100) / 100;

        return (float) $result;
    }
    
    /**
     * Recebe valor em fomator com ou sem ","
     * substitui a "," por "." e formata para 2 casas 
     * decimais com ","
     * 
     * @example casaDescimal(150) -> 150,00
     * @param type $valor
     * @return type
     * @tutorial http://blog.thiagobelem.net/formatando-fracoes-e-casas-decimais-no-php/
     */
    public static function casaDecimal($valor) {
        
        $valor = str_replace(',', '.', $valor);
        return number_format($valor, 2, ',', '.');
    }

}

?>
