<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Patente
 *
 * @author Sg Anders
 */
class Patente {

    private $codPostoGraduacao;
    private $codCorpo;
    private $especialidade;
    private $aperfeicoamento;
    private $subEspecialidade;
    private $quadro;
    private $apEspSub;
    private $pge = null;
    private $nomePostoGraduacaoAnterior;
    private $corpoAnterior;
    private $quadroAnterior;
    private $codPostoGraduacaoAnterior;
    private $especialidadeAnterior;
    private $aperfeicoamentoAnterior;
    private $subEspecialidadeAnterior;
    private $apEspSubAnterior;
    
    public function getNomePostoGraduacaoAnterior() {
        return $this->nomePostoGraduacaoAnterior;
    }

    public function setNomePostoGraduacaoAnterior($nomePostoGraduacaoAnterior) {
        $this->nomePostoGraduacaoAnterior = $nomePostoGraduacaoAnterior;
    }
        
    public function getApEspSubAnterior() {
        return $this->apEspSubAnterior;
    }

    public function setApEspSubAnterior($apEspSubAnterior) {
        $this->apEspSubAnterior = $apEspSubAnterior;
    }
    
    public function getCorpoAnterior() {
        return $this->corpoAnterior;
    }

    public function getQuadroAnterior() {
        return $this->quadroAnterior;
    }

    public function getCodPostoGraduacaoAnterior() {
        return $this->codPostoGraduacaoAnterior;
    }

    public function getEspecialidadeAnterior() {
        return $this->especialidadeAnterior;
    }

    public function getAperfeicoamentoAnterior() {
        return $this->aperfeicoamentoAnterior;
    }

    public function getSubEspecialidadeAnterior() {
        return $this->subEspecialidadeAnterior;
    }

    public function setCorpoAnterior($corpoAnterior) {
        $this->corpoAnterior = $corpoAnterior;
    }

    public function setQuadroAnterior($quadroAnterior) {
        $this->quadroAnterior = $quadroAnterior;
    }

    public function setCodPostoGraduacaoAnterior($codPostoGraduacaoAnterior) {
        $this->codPostoGraduacaoAnterior = $codPostoGraduacaoAnterior;
    }

    public function setEspecialidadeAnterior($especialidadeAnterior) {
        $this->especialidadeAnterior = $especialidadeAnterior;
    }

    public function setAperfeicoamentoAnterior($aperfeicoamentoAnterior) {
        $this->aperfeicoamentoAnterior = $aperfeicoamentoAnterior;
    }

    public function setSubEspecialidadeAnterior($subEspecialidadeAnterior) {
        $this->subEspecialidadeAnterior = $subEspecialidadeAnterior;
    }

    
    public function getApEspSub() {
        return $this->apEspSub;
    }

    public function setApEspSub($apEspSub) {
        $this->apEspSub = $apEspSub;
    }
    
    public function getPge() {
        return $this->pge;
    }

    public function setPge($pge) {
        $this->pge = $pge;
    }

    public function setCodPostoGraduacao($codPostoGraduacao) {
        $this->codPostoGraduacao = $codPostoGraduacao;
    }

    public function getCodPostoGraduacao() {
        return $this->codPostoGraduacao;
    }

    public function setCodCorpo($codCorpo) {
        $this->codCorpo = $codCorpo;
    }

    public function getCodCorpo() {
        return $this->codCorpo;
    }

    public function setEspecialidade($especialidade) {
        $this->especialidade = $especialidade;
    }

    public function getEspecialidade() {
        return $this->especialidade;
    }

    public function setAperfeicoamento($aperfeicoamento) {
        $this->aperfeicoamento = $aperfeicoamento;
    }

    public function getAperfeicoamento() {
        return $this->aperfeicoamento;
    }

    public function setSubEspecialidade($subEspecialidade) {
        $this->subEspecialidade = $subEspecialidade;
    }

    public function getSubEspecialidade() {
        return $this->subEspecialidade;
    }

    public function setQuadro($quadro) {
        $this->quadro = $quadro;
    }

    public function getQuadro() {
        return $this->quadro;
    }

}

?>
