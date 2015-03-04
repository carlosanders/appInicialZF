<?php

/**
 * Description of Pessoa
 *
 * @author Sg Anders
 */
abstract class Pessoa {

    private $nomePessoa;   
    private $nroCPF;
    private $nroIdentidade;
    private $naturalidade;
    private $nacionalidade;
    private $dataNascimento;
    private $sexo;
    private $estadoCivil;

    public function __construct() {

    }

    public function setEstadoCivil($estadoCivil) {
        $this->estadoCivil = $estadoCivil;
    }

    public function getEstadoCivil() {
        return $this->estadoCivil;
    }

    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    public function getSexo() {
        return $this->sexo;
    }

    public function setNomePessoa($nomePessoa) {
        $this->nomePessoa = $nomePessoa;
    }

    public function getNomePessoa() {
        return $this->nomePessoa;
    }

    public function setNroCPF($nroCPF) {
        $this->nroCPF = $nroCPF;
    }

    public function getNroCPF() {
        return $this->nroCPF;
    }

    public function setNroIdentidade($nroIdentidade) {
        $this->nroIdentidade = $nroIdentidade;
    }

    public function getNroIdentidade() {
        return $this->nroIdentidade;
    }

    public function setNaturalidade($naturalidade) {
        $this->naturalidade = $naturalidade;
    }

    public function getNaturalidade() {
        return $this->naturalidade;
    }

    public function setNacionalidade($nacionalidade) {
        $this->nacionalidade = $nacionalidade;
    }

    public function getNacionalidade() {
        return $this->nacionalidade;
    }

    public function setDataNascimento($dataNascimento) {
        $this->dataNascimento = $dataNascimento;
    }

    public function getDataNascimento() {
        return $this->dataNascimento;
    }

}

?>
