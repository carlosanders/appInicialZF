<?php

class ApoioController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 
     * modo de uso:
     *  apoio/comboboxCidades - se usar sem parametros retornas todos em JSON
     *  apoio/comboboxCidades/1 - retorna um registro selecionado por codigo
     *  apoio/comboboxCidades/false/RJ/false - retorna todos os registros selecionado por UF
     *  apoio/comboboxCidades/2/RJ/true - retorna todos os registros das cidades e alem disso, seta selected=true no 
     * registro com o codigo passado por parametro
     * 
     * 
     * @param opcional $codigo
     * @param opcional $uf
     * @param opcional $todos
     * @return JSON ou stdClass para somente um registro
     */
    public function comboboxCidadesAction($codigo = false, $uf = false, $todos = false)
    {
        $bd = new ExemploStmt();
        $formato = ($todos == 'true') ? true : false;
        $codigo = ($codigo == 'false') ? false : $codigo;
        $uf = ($uf == 'false') ? false : $uf;

        //var_dump($codigo, $uf, $formato);        
        //echo "codigo: ".$codigo . "UF: $uf " . "Todos: $todos";

        if ($codigo != false && $uf == false && $formato == false) {
            //var_dump("retorna stdClass");
            return (($bd->getCidadesPorEstado($codigo, false, false)));
            //return die(var_dump($bd->getCidadesPorEstado($codigo, false, false)));
        } else if ($codigo == false && $uf != false && $formato == false) {
            //var_dump($codigo, $uf, $formato); 
            //return die(var_dump($bd->getCidadesPorEstado(false, $uf, false)));
            return die(Helper::safe_json_encode($bd->getCidadesPorEstado(false,
                                    $uf, false)));
        } else if ($codigo != false && $uf != false && $formato != false) {

            //return die(var_dump($bd->getCidadesPorEstado($codigo, $uf, $formato)));
            return die(Helper::safe_json_encode($bd->getCidadesPorEstado($codigo,
                                    $uf, $formato)));
        } else {

            return die(Helper::safe_json_encode($bd->getCidadesPorEstado()));
            //return die(var_dump($bd->getCidadesPorEstado()));
        }
    }

    public function comboboxEstadoAction($codigo = NULL, $todos = false)
    {
        $bd = new ExemploStmt();
        $formato = ($todos == 'true') ? true : false;
        //var_dump($codigo, $formato);
        //o retorno eh um obj stdClass com os dados do bd
        //$row->id, $row->descricao, $row->icone
        if ($codigo != NULL && $formato == false) {
            //var_dump("retorna stdClass");
            return $bd->getEstado($codigo, $formato);
            //return die(var_dump($bd->getEstado($codigo, $formato)));
            //nesse if retornara um array com todos os registros e o selecionado com
            //uma key 'selected'=true   
        } else if ($codigo != NULL && $formato == true) {
            //var_dump("retorna array com selected");
            //return die(var_dump($bd->getCategoria($codigo, $formato)));
            return die(Helper::safe_json_encode($bd->getEstado($codigo, $formato)));
        } else {
            //var_dump("retorna array com todos");
            //return die(var_dump($bd->getCategoria()));
            return die(Helper::safe_json_encode($bd->getEstado()));
        }
    }

    public function comboboxCategoriaAction($codigo = NULL, $todos = false)
    {
        $bd = new ExemploStmt();
        $formato = ($todos == 'true') ? true : false;
        //var_dump($codigo, $formato);
        //o retorno eh um obj stdClass com os dados do bd
        //$row->id, $row->descricao, $row->icone
        if ($codigo != NULL && $formato == false) {
            //var_dump("retorna stdClass");
            return $bd->getCategoria($codigo, $formato);
            //return die(var_dump($bd->getCategoria($codigo, $formato)));
            //nesse if retornara um array com todos os registros e o selecionado com
            //uma key 'selected'=true   
        } else if ($codigo != NULL && $formato == true) {
            //var_dump("retorna array com selected");
            //return die(var_dump($bd->getCategoria($codigo, $formato)));
            return die(Helper::safe_json_encode($bd->getCategoria($codigo,
                                    $formato)));
        } else {
            //var_dump("retorna array com todos");
            //return die(var_dump($bd->getCategoria()));
            return die(Helper::safe_json_encode($bd->getCategoria()));
        }
    }

    /**
     *
     * Metodo usado pelo JavaScript, portanto o retorno será
     * um die somento no formato json_encode
     */
    public function listarPostoGraduacaoAction($formato = true)
    {

        $lista = array();
        $formato = ($formato == 'true') ? true : false;
        if (isset($_GET['id'])) {
            $apoio = new MilitarStatement();

            $id = isset($_GET['id']) ? $_GET['id'] : null;
            $lista = $apoio->listarPostosGradPorCirculo($id, $formato);
        } else {
            $lista[0] = array(
                "CODPOSTOGRADUACAO" => "0",
                "DSCPOSTOGRADUACAO" => "Não Encontrado",
            );
        }

        //return die(var_dump($lista));
        return die(json_encode($lista));
    }

    /**
     * lista OM's ativas da marinha em formato vetor normal ou JSON
     * 
     * @param type $formato            
     * @return type
     */
    public function listarOMsAtivasAction($codOM = "", $formato = true)
    {
        $formato = ($formato == 'true') ? true : false;
        // recupero o campo
        $codOM = isset($_GET['codOM']) ? $_GET['codOM'] : $codOM;
        // instacia a classe de apoio ao sistema
        $apoio = new OMStatement();

        $lista = $apoio->listarOMsAtivaMB($codOM);
        // variaveis
        $listaCod = array();
        $listaDesc = array();

        for ($i = 0; $i < count($lista); $i ++) {
            array_push($listaCod, $lista[$i]['CODOM']);
            array_push($listaDesc, $lista[$i]['NOMOM']);
        }

        $result = array_combine($listaCod, $listaDesc);

        if ($formato) {
            return die(json_encode($result));
        } else {
            return die(json_encode($lista));
        }
    }

}