<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProdutoController
 *
 * @author carlosanders
 */
class ProdutoController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function indexAction()
    {
//        Application::redirect("index.php?pg=produto/listarProdutos");
        Application::redirect("produto/listarProdutos");
    }

    public function listarProdutosAction()
    {
        // echo $this->getRequest("user");//pega a requisao via POST ou GET
        $parametros = array();
        $parametros['titulo'] = "Listando os Produtos!";
        $parametros['subtitulo'] = "Produtos no BD SQLite";


        $o_view = new View('listarProdutos.phtml', NULL, true);
        $o_view->setParams($parametros);
        // Imprimindo código HTML
        $o_view->showContents();
    }

    public function salvarAction()
    {
//        $retorno = array();

        $p = new Produto();
        $p->setInptCodProd($this->getRequest("inptCodProd"));
        $p->setInptPrecoAtual($this->getRequest("inptPrecoAtual"));
        $p->setInptPrecoAnt($this->getRequest("inptPrecoAnt"));
        $p->setInptDescricao($this->getRequest("inptDescricao"));
        $p->setCbCategoria($this->getRequest("cbCategoria"));
        $p->setStatus($this->getRequest("status"));
        $p->setUrl($this->getRequest("url"));
        $p->setIdForm( $this->getRequest("idForm"));
        $p->setCbEstados($this->getRequest("cbEstados"));

        $model = new ExemploStmt();
        $r = $model->salvarProduto($p);

//        $retorno["success"] = true;
//        $retorno["msg"] = "teste de Retorno";


        return die(json_encode($r));
    }

}
