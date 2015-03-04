<?php

class MilitarStatement
{

    private $dao;

    function __construct()
    {
        $this->dao = new MilitarDAO();
    }
    
    /**
     * Lista os postos/Grad em 2 tipos de formato.
     * para o jQueryUI a @param type $formato = true
     * para o jQueryEasyUI @param type $formato = false
     * @param type $codCirculo
     * @param type $formato boolean
     * @return array lista
     */
    public function listarPostosGradPorCirculo($codCirculo, $formato) {
        //echo $formato;
        //retorno o valor somente o valor inteiro da variavel
        $codCirculo = intval($codCirculo);
        // --relação postos e graduacoes validos
        //$resultado = array();
        $listaObj = array();
        $stmt = $this->dao->listarPostosGradPorCirculo($codCirculo);
    
        while ($row = $stmt->fetch()) {
            //var_dump($formato);
            if ($formato) {
                //para o formato jQueryUI
                $listaObj[$row->CODPOSTOGRADUACAO] = utf8_encode($row->DSCPOSTOGRADUACAO);
            } else {
                //para o formato jQueryEasyUI
                $listaObj[] = array(
                    "CODPOSTOGRADUACAO" => $row->CODPOSTOGRADUACAO,
                    "DSCPOSTOGRADUACAO" => utf8_encode($row->DSCPOSTOGRADUACAO)
                    //"NROINDEXADOR" => $row->NROINDEXADOR,
                    //"CIRCULO" => $row->CIRCULO
                );
            }
        }
    
    
        return $listaObj;
    }

    public function militaresPorPostoGraduacaoDaOMeOMSubordAministrativamentePag($codOM, $postoGraduacao, $linha_inicial, $linhas_por_pagina, $buscaNIP, $buscaPessoa, $tipo)
    {
        
        // --relacao de militares da OM e militares de OM subordinadas administrativamente
        $buscaNIP = Helper::formataNipsSemPontos($buscaNIP);
        $resultado = array();
        $stmt = $this->dao->militaresPorPostoGraduacaoDaOMeOMSubordAministrativamentePag($codOM, $postoGraduacao, $linha_inicial, $linhas_por_pagina, $buscaNIP, $buscaPessoa, $tipo);
        
        // var_dump($codOM, $postoGraduacao, $linha_inicial, $linhas_por_pagina, $buscaNIP, $buscaPessoa);
        $lista = array();
        while ($row = $stmt->fetch()) {
            
            $mil = new Militar($row->NRONIP);
            $mil->setNipCodficado(Helper::encrypt($row->NRONIP));
            $mil->setNomeGuerra(utf8_encode($row->NOMEGUERRA));
            $mil->setNomePessoa(utf8_encode($row->NOMPESSOA));
            // Posto/Grad
            $mil->getPatente()->setCodCorpo($row->CODCORPO);
            $mil->getPatente()->setQuadro($row->QUADRO);
            $mil->getPatente()->setCodPostoGraduacao($row->CODPOSTOGRADUACAO);
            $mil->getPatente()->setAperfeicoamento($row->APERFEICOAMENTO);
            $mil->getPatente()->setEspecialidade($row->ESPECIALIDADE);
            $mil->getPatente()->setPge($row->PGE);
            // OM
            $mil->getOm()->setCodOM($row->CODOM);
            $mil->getOm()->setNomeOM(utf8_encode($row->NOMOM));
            $mil->getOm()->setNomeAbreviado(utf8_encode($row->NOMABREVIADO));
            
            $lista[] = $mil;
        }
        
        // return $resultado;
        return $lista;
    }

    public function totalMilitaresPorPostoGraduacaoDaOMeOMSubordAministrativamente($codOM, $postoGraduacao, $buscaNIP, $buscaPessoa, $tipo)
    {
        $buscaNIP = Helper::formataNipsSemPontos($buscaNIP);
        $stmt = $this->dao->totalMilitaresPorPostoGraduacaoDaOMeOMSubordAministrativamente($codOM, $postoGraduacao, $buscaNIP, $buscaPessoa, $tipo);
        $result = $stmt->fetch();
        
        return $result->TOTAL;
    }
}

?>