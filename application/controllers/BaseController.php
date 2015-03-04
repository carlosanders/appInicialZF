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
 * Controller - Base - Responsável pelo gerenciamento de tudo que deve 
 * existir na applicação no modo geral
 */
class BaseController
{

    protected $data;
    protected $locale;
    private $viewVars;
    protected $config;

    public function __construct()
    {
        $this->viewVars = array();
        try {
            Zend_Session::start();
            $this->locale = new Zend_Locale('pt_BR');
            $this->data = new Zend_Date();
        } catch (Zend_Session_Exception $e) {
            echo $e->getMessage();
        }
        $this->config = Zend_Registry::get("config");
    }

    
    public function getRequest($param, $method = "POST")
    {
        
        if ((null === $param)) {
            return null;
        }

        switch ($method) {
            case 'GET': 
                $the_request = $_GET[$param];
                break;
            case 'POST': 
                $the_request = $_POST[$param];
                break;

            default: 
                return null;
        }
        
        return $the_request;

        
    }
    /**
     * Adiciona uma variavel a ser exposta na view
     *
     * @param
     *            string O nome da variavel
     * @param
     *            object O valor da variavel
     */
    protected function setVar($name, $value)
    {
        $this->viewVars[$name] = $value;
    }

    /**
     * Retorna um array com as variaveis a
     * serem expostas para a view
     *
     * @return array
     */
    public function getVars()
    {
        return $this->viewVars;
    }

}
