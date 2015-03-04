<?php

/**
 * @author Carlos Anders 
 * @copyright Copyright (c) 2013, Carlos Anders
 * @package application/controllers
 * @version 1.0 
 * 
 * Camada - Sistema / Aplicação / Controller
 * Diretório Pai - application 
 * 
 * Controller responsável por gerenciar todos os erros que ocorram na aplicação
 */
class ErroController {

    /**
     * erro comum onde o usuario acessa um recuros inexistente
     * @param type $erro
     */
    public function erroMetodoAction(Exception $erro, Exception $e) {
        
        $parametros = array();
        $parametros['o_erro'] = $erro->getMessage();
        $parametros['erro_pilha'] = $e->getTraceAsString();
        $o_view = new View('erroApp.phtml', $parametros, true, "erro");
        $o_view->showContents();
    }    

}