<?php
/**
 * @example Projeto appexemplomvc 
 * @author Carlos Anders 
 * @copyright Copyright (c) 2013, Carlos Anders 
 * 
 * Aplicacao exemplo de mvc com framework jQueryUI e easyUI 
 * falta continuar implementação - Anders - 20/11/2013
 */
header('Content-Type: text/html; charset=utf-8');
require_once ('../application/configs/configuracao.php');
$o_Application = new Application();

try {
    /**
     * passa o comando para o controlador Principal da aplicacao chamado de "Application"
     * onde esse controlador irá delegar para outros controladores os quais irão chamar
     * os comandos desejados chamados actions da classe controller
     */    
    $o_Application->dispatch();
} catch (Zend_Exception $e) {
    //caso ocorra algum erro na aplicacao relativo ao zend sera redirecionado para o 
    //tratamento do erro pelo controlador principal
    $o_Application->dispatchErro($e, 'erroJanela');
} catch (Exception $e) {
    //caso ocorra algum erro na aplicacao relativo sera redirecionado para o 
    //tratamento do erro pelo controlador principal
    $o_Application->dispatchErro($e, 'erroMetodo');
}
                