<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Produto
 *
 * @author carlosanders
 */
class Produto
{
    private $inptCodProd;
    private $inptPrecoAtual;
    private $inptDescricao;
    private $cbCategoria;
    private $status;
    private $url;
    private $idForm;
    private $cbEstados;
    private $inptPrecoAnt;
    
    function getInptPrecoAnt()
    {
        return $this->inptPrecoAnt;
    }

    function setInptPrecoAnt($inptPrecoAnt)
    {
        $this->inptPrecoAnt = $inptPrecoAnt;
    }

        
    function getInptCodProd()
    {
        return $this->inptCodProd;
    }

    function getInptPrecoAtual()
    {
        return $this->inptPrecoAtual;
    }

    function getInptDescricao()
    {
        return $this->inptDescricao;
    }

    function getCbCategoria()
    {
        return $this->cbCategoria;
    }

    function getStatus()
    {
        return $this->status;
    }

    function getUrl()
    {
        return $this->url;
    }

    function getIdForm()
    {
        return $this->idForm;
    }

    function getCbEstados()
    {
        return $this->cbEstados;
    }

    function setInptCodProd($inptCodProd)
    {
        $this->inptCodProd = $inptCodProd;
    }

    function setInptPrecoAtual($inptPrecoAtual)
    {
        $this->inptPrecoAtual = $inptPrecoAtual;
    }

    function setInptDescricao($inptDescricao)
    {
        $this->inptDescricao = $inptDescricao;
    }

    function setCbCategoria($cbCategoria)
    {
        $this->cbCategoria = $cbCategoria;
    }

    function setStatus($status)
    {
        $this->status = $status;
    }

    function setUrl($url)
    {
        $this->url = $url;
    }

    function setIdForm($idForm)
    {
        $this->idForm = $idForm;
    }

    function setCbEstados($cbEstados)
    {
        $this->cbEstados = $cbEstados;
    }


}
