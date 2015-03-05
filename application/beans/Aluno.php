<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Aluno
 *
 * @author carlosanders
 */
class Aluno
{
    private $nome;
    private $matricula;
    private $dataIngresso;
    
    public function __construct($dateTime = null)
    {
        if ($dateTime == null){
            //um exemplo de tipagem simples de uma classe interna do PHP
            $dateTime = new DateTime();
        }
        $this->dataIngresso = $dateTime;
    }
    
    function getNome()
    {
        return $this->nome;
    }

    function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * Perceba que somente esse comentário é o suficiente para tipar o atributo
     * com a classe que queremos retornar pelo get
     * @return DateTime
     */
    function getDataIngresso()
    {
        return $this->dataIngresso;
    }

    function setNome($nome)
    {
        $this->nome = $nome;
    }

    function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    function setDataIngresso(DateTime $dataIngresso)
    {
        $this->dataIngresso = $dataIngresso;
    }
}
