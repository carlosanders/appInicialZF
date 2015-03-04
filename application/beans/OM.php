<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OM
 *
 * @author Sg Anders
 */
class OM {

    private $codOM;
    private $nomeAbreviado;
    private $nomeOM;

    function __construct($codOM = NULL) {
        if ($codOM != NULL) {
            $this->codOM = $codOM;
        }
    }
    
    /**
     * carrega os dados da OM se acionado e caso exista o codOM
     */
    public function carregarDados() {
        $dao = new OMStatement();
        $lista = $dao->listarOMsAtivaMB();
        
        foreach ($lista as $valor) {
            if($valor["CODOM"] == $this->codOM){
                $this->setNomeAbreviado($valor["NOMABREVIADO"]);
                $this->setNomeOM($valor["NOMOM"]);
                break;
            }
        }
    }
    
    public function carregarOM($codigo = "") {
        
        $dao = new OMStatement();
        if($codigo != ""){
            $lista = $dao->listarOMMilitar($codigo);
        }else{
            $lista = $dao->listarOMsAtivaMB($this->codOM);
        }
        
        return $lista;
    }

    public function setCodOM($codOM) {
        $this->codOM = $codOM;
    }

    public function getCodOM() {
        return $this->codOM;
    }

    public function setNomeAbreviado($nomeAbreviado) {
        $this->nomeAbreviado = $nomeAbreviado;
    }

    public function getNomeAbreviado() {
        return $this->nomeAbreviado;
    }

    public function setNomeOM($nomeOM) {
        $this->nomeOM = $nomeOM;
    }

    public function getNomeOM() {
        return $this->nomeOM;
    }

}

?>
