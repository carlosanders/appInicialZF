<?php

/**
 * Description of Militar
 *
 * @author Sg Anders
 */
class Militar extends Pessoa {

    private $nroNIP = null;
    private $nipCodficado = null;
    private $nomeGuerra = null;
    private $imgFoto = null;
    private $nroEscalAntiguidade = null;
    private $dataReengajamento = null;
    private $nroBolReengajamento = null;
    private $ntaPromocaoMerito = null;
    private $ntaFuncaoInstrutoria = null;
    private $ntaOficialato = null;
    private $ntaAMC = null;
    private $ntaAC = null;
    private $ntaPPComportamento = null;
    private $nroSitCarreira = null;
    private $funcao = null;
    private $situacaoFuncional = null;
    private $dataSituacao = null;
    private $patente = null;
    private $om = null;
    private $listaEventos = null;
    private $dataMovimentacao = null;
    private $dataInicio = null;
    private $dataTermino = null;
    private $dataIngresso = null;

    //construtor
    public function __construct($nroNIP = 0) {
        $this->nroNIP = $nroNIP;
        $this->om = new OM();
        $this->patente = new Patente();
        $this->listaEventos = array();
    }

    /**
     * recebe um nip e vrf se eh valor 0 se for retorna uma String 'erro'
     * senao recupero dados do banco e passo para var $retorno com um array
     * de chaves e valores do banco
     * @param type $nronip Integer
     * @return type array() ou String
     */
    public function retornaDadosUsuario() {
        try {
            //var q ira armazenar os dados do banco
            if ($this->nroNIP != 0) {
                $dao = new MilitarDAO();
                //formato e passo para Integer
                //$nronip = Helper::formataNipsSemPontos($this->nroNIP);
                //recebo na var stmt o result da consulta em OO
                //var_dump($this);
                $stmt = $dao->retornaDadosMilitar($this->nroNIP);
                //var_dump($stmt);
                //recupero a linha
                $row = $stmt->fetch();
                //var_dump($row);
                if ($row != FALSE) {
                    /* stream_get_contents($row->IMGFOTO)
                     * Lê um resource(64, stream) e passa para uma string
                     * fonte: http://php.net/manual/en/function.stream-get-contents.php
                     */
                    $stream = stream_get_contents($row->IMGFOTO);
                    //var q ira armazenar os dados do banco           

                    $this->setNipCodficado(Helper::encrypt($row->NRONIP));
                    $this->setNomeGuerra(htmlentities($row->NOMEGUERRA));
                    $this->setNomePessoa(htmlentities($row->NOMPESSOA));
                    $this->setImgFoto(base64_encode($stream));
                    //Posto/Grad
                    $this->getPatente()->setCodCorpo($row->CODCORPO);
                    $this->getPatente()->setQuadro($row->QUADRO);
                    $this->getPatente()->setCodPostoGraduacao($row->CODPOSTOGRADUACAO);
                    $this->getPatente()->setAperfeicoamento($row->APERFEICOAMENTO);
                    $this->getPatente()->setEspecialidade($row->ESPECIALIDADE);
                    $this->getPatente()->setSubEspecialidade($row->SUBESPECIALIDADE);
                    //$mil->getPatente()->setPge($row->PGE);
                    $this->getPatente()->setApEspSub($row->APESPSUB);
                    //OM
                    $this->getOm()->setCodOM($row->CODOM);
                    $this->getOm()->setNomeOM(htmlentities($row->NOMOM));
                    $this->getOm()->setNomeAbreviado(htmlentities($row->NOMABREVIADO));
                }else{
                    throw new Exception(MensagemPadrao::MILITAR_ACESSO);
                }
            }
        } catch (Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    // GET  e  SET
    public function getDataIngresso() {
        return $this->dataIngresso;
    }

    public function setDataIngresso($dataIngresso) {
        $this->dataIngresso = $dataIngresso;
    }

    public function getListaEventos() {
        return $this->listaEventos;
    }

    public function setUmEvento(Evento $eventos) {
        $this->listaEventos[] = $eventos;
    }

    public function getOm() {
        return $this->om;
    }

    public function setOm(OM $om) {
        $this->om = $om;
    }

    public function getPatente() {
        return $this->patente;
    }

    public function setPatente(Patente $patente) {
        $this->patente = $patente;
    }

    public function getNroNIP() {
        return $this->nroNIP;
    }

    public function setNroNIP($nroNIP) {
        $this->nroNIP = $nroNIP;
    }

    public function getNipCodficado() {
        return $this->nipCodficado;
    }

    public function setNipCodficado($nipCodficado) {
        $this->nipCodficado = $nipCodficado;
    }

    public function getNomeGuerra() {
        return $this->nomeGuerra;
    }

    public function setNomeGuerra($nomeGuerra) {
        $this->nomeGuerra = $nomeGuerra;
    }

    public function getImgFoto() {
        return $this->imgFoto;
    }

    public function setImgFoto($imgFoto) {
        $this->imgFoto = $imgFoto;
    }

    public function getNroEscalAntiguidade() {
        return $this->nroEscalAntiguidade;
    }

    public function setNroEscalAntiguidade($nroEscalAntiguidade) {
        $this->nroEscalAntiguidade = $nroEscalAntiguidade;
    }

    public function getDataReengajamento() {
        return $this->dataReengajamento;
    }

    public function setDataReengajamento($dataReengajamento) {
        $this->dataReengajamento = $dataReengajamento;
    }

    public function getNroBolReengajamento() {
        return $this->nroBolReengajamento;
    }

    public function setNroBolReengajamento($nroBolReengajamento) {
        $this->nroBolReengajamento = $nroBolReengajamento;
    }

    public function getNtaPromocaoMerito() {
        return $this->ntaPromocaoMerito;
    }

    public function setNtaPromocaoMerito($ntaPromocaoMerito) {
        $this->ntaPromocaoMerito = $ntaPromocaoMerito;
    }

    public function getNtaFuncaoInstrutoria() {
        return $this->ntaFuncaoInstrutoria;
    }

    public function setNtaFuncaoInstrutoria($ntaFuncaoInstrutoria) {
        $this->ntaFuncaoInstrutoria = $ntaFuncaoInstrutoria;
    }

    public function getNtaOficialato() {
        return $this->ntaOficialato;
    }

    public function setNtaOficialato($ntaOficialato) {
        $this->ntaOficialato = $ntaOficialato;
    }

    public function getNtaAMC() {
        return $this->ntaAMC;
    }

    public function setNtaAMC($ntaAMC) {
        $this->ntaAMC = $ntaAMC;
    }

    public function getNtaAC() {
        return $this->ntaAC;
    }

    public function setNtaAC($ntaAC) {
        $this->ntaAC = $ntaAC;
    }

    public function getNtaPPComportamento() {
        return $this->ntaPPComportamento;
    }

    public function setNtaPPComportamento($ntaPPComportamento) {
        $this->ntaPPComportamento = $ntaPPComportamento;
    }

    public function getNroSitCarreira() {
        return $this->nroSitCarreira;
    }

    public function setNroSitCarreira($nroSitCarreira) {
        $this->nroSitCarreira = $nroSitCarreira;
    }

    public function getFuncao() {
        return $this->funcao;
    }

    public function setFuncao($funcao) {
        $this->funcao = $funcao;
    }

    public function getSituacaoFuncional() {
        return $this->situacaoFuncional;
    }

    public function setSituacaoFuncional($situacaoFuncional) {
        $this->situacaoFuncional = $situacaoFuncional;
    }

    public function getDataSituacao() {
        return $this->dataSituacao;
    }

    public function setDataSituacao($dataSituacao) {
        $this->dataSituacao = $dataSituacao;
    }

    public function getDataMovimentacao() {
        return $this->dataMovimentacao;
    }

    public function setDataMovimentacao($dataMovimentacao) {
        $this->dataMovimentacao = $dataMovimentacao;
    }

    public function getDataInicio() {
        return $this->dataInicio;
    }

    public function setDataInicio($dataInicio) {
        $this->dataInicio = $dataInicio;
    }

    public function getDataTermino() {
        return $this->dataTermino;
    }

    public function setDataTermino($dataTermino) {
        $this->dataTermino = $dataTermino;
    }

}

?>
