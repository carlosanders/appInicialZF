<?php

/**
 * @author Carlos Anders 
 * @copyright Copyright (c) 2013, Carlos Anders
 * @example appexemplomvc
 * @package application/app
 * @version 1.0 
 * 
 * Camada - Sistema / Aplicação
 * Diretório Pai - application 
 * 
 * Verifica qual classe controlador (Controller) o usuário deseja chamar
 * e qual método dessa classe (Action) deseja executar
 * Caso o controlador (controller) não seja especificado, o IndexControllers será o padrão
 * Caso o método (Action) não seja especificado, o indexAction será o padrão
 */
class Application
{

    /**
     * Usada pra guardar o nome da classe
     * de controle (Controller) a ser executada
     *
     * @var string
     */
    protected $st_controller;

    /**
     * Usada para guardar o nome do metodo da
     * classe de controle (Controller) que deverá ser executado
     *
     * @var string
     */
    protected $st_action;

    /**
     * Usado para guardar os parametros das variáveis
     *
     * @var string
     */
    protected $vars;

    /**
     * Usado para receber um arquivo de configuracao que sera
     * um objeto da classe Zend_Config_Ini
     *
     * @var class Zend_Config_Ini
     */
    protected $config;
    
    private $erro;

    /**
     * 
     * @var constante controlador
     */
    private static $CONTROLLER;
    /**
     * 
     * @var constante acao
     */
    private static $ACTION;

    /**
     */
    function __construct()
    {
        // recebe o obj Zend_Config_Ini do registro
        $this->config = Zend_Registry::get("config");
        // seta somente a secao 'geral', na aplicacao
        // $this->config = $this->config->get("geral");
        // $this->config = new Zend_Config_Ini();
    }

    /**
     * Verifica se os parametros de controlador (Controller) e acao (Action) foram
     * passados via parametros "Post" ou "Get" e os carrega tais dados
     * nos respectivos atributos da classe
     */
    private function loadRoute()
    {
        /*
         * crio a var $pg que é um paramentro GET da URL, lembrando que a URL esta reescrita, entao se existe um conjunto de "pastas", na url como "/Pessoas/find/1", na verdade teremos é a var "index.php?pg=/Pessoas/find/1". Se não houver nada, retorna nulo.
         */
        $pg = array_key_exists("pg", $_GET) ? $_GET['pg'] : null;
        // var_dump($pg);
        /**
         * transforma a var $pg em um array atraves do comando explode do php
         * o delimitador é "/", ou seja se tem um string "/controller/action/var",
         * será transformada em um array contendo 3 elementos:
         * [0] = controller;
         * [1] = action;
         * [2] = var;
         */
        $array_pg = explode("/", $pg);
        // var_dump($array_pg);
        /**
         * criamos o primeiro indice do array, veja que se o valor for nulo,
         * eh usado o comando "?:", para atribuir o controller padrao da applicacao
         * que está no arquivo application.ini pela chave "default.controller"
         */
        $this->st_controller = ucwords($array_pg[0] ? $array_pg[0] : $this->config->application->default->controller);
        /**
         * usamos o array_shift para retirar o indice [0] do $array_pg
         * que é o controller da aplicacao
         */
        array_shift($array_pg);
        /**
         * usamos a mesma estratégia do controller para capturar o action
         * atribuí-lo a var $action, atraves do count conseguimos descobrir
         * se ainda temos algum item no array, caso nao haja retornamos action
         * default definida no arquivo application.ini pela chave "default.action"
         */
        $this->st_action = (count($array_pg) > 0) ? $array_pg[0] : $this->config->application->default->action;
        // retiramos o item do array
        array_shift($array_pg);
        /**
         * atribuímos todo o resto do array a variavel $vars
         */
        //var_dump($array_pg);
        $this->vars = $array_pg;
    }

    /**
     *
     * O método dispatch(), responsável pelo roteamento da aplicacao e por
     * chamar o Controller responsável pela ação requerida do usuário da aplicação.
     * 
     * Instancia classe referente ao Controlador (Controller) e executa
     * método referente e acao (Action)
     *
     * @throws Exception
     */
    public function dispatch()
    {
        try {
            // chamo o metodo para carregar roteamento
            $this->loadRoute();
            // verificando se na url existe '-' se sim substitui por '_'
            $this->st_controller = preg_replace('/[^A-z0-9]/', '_', $this->st_controller);
            // verificando se o arquivo de controle existe
            $st_controller_file = $this->config->application->path->controller . $this->st_controller . 'Controller.php';
            if (file_exists($st_controller_file)) {
                require_once $st_controller_file;
            } else {
                throw new Exception('O Arquivo <strong>' . $this->st_controller . '</strong> não encontra-se disponível!');
            }
            // verificando se a classe existe
            $st_class = $this->st_controller . 'Controller';
            //tornando o nome do controlador disponivel na aplicacao
            self::$CONTROLLER = $this->st_controller;
            
            if (class_exists($st_class)) {
                $o_class = new $st_class();
            } else {
                throw new Exception("O Controlador <strong>'$st_class'</strong> não encontra-se disponível no arquivo '$this->st_controller'");
            }
            
            // verificando se o metodo existe
            // nessa etapa se houver na url "-" substitui por "_"
            $methodName = preg_replace('/[^A-z0-9]/', '_', $this->st_action). "Action";
            //tornando o nome da acao disponivel para aplicacacao
            self::$ACTION = $this->st_action;
            
            //instanciando a classe e o metodo
            $ref = new ReflectionMethod($st_class, $methodName);
            // verifico se o metodo eh puclibo
            if (! $ref->isPublic()) {
                throw new Exception("A ação do controlador <strong>{$this->st_controller}::{$this->st_action}</strong> não é público.");
            }
            // invoca o metodo da classe passando os parametros em array
            $ref->invokeArgs($o_class, $this->vars);
        } catch (ReflectionException $e) {
            $this->erro = $e;
            //var_dump($e->getTrace());
            //var_dump($e->getTraceAsString());
            throw new Exception("A ação: <strong>{$this->st_controller}::{$this->st_action}</strong>, não existe no controlador.");
        }
    }

    /**
     * Responsavel para exibir erros na tela
     *
     * @param type $ex            
     * @param type $acao            
     */
    public function dispatchErro($ex, $acao = false)
    {
        //var_dump($ex->getTraceAsString());
        $o_class = new ErroController();
        $st_method = (($acao != false) ? $acao : 'erroMetodo') . 'Action';
        // var_dump($ex, $st_method);
        call_user_func_array(array(
            $o_class,
            $st_method
        ), array(
            $ex,
            $this->erro
        ));
    }

    /**
     * Redireciona a chamada http para outra página
     *
     * @example : Application::redirect("index.php?pg=main/json");
     * @param string $st_uri            
     */
    public static function redirect($st_uri)
    {
        header("Location: $st_uri");
        // echo "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=$st_uri'>";
    }

    /**
     * Retorna o controlador chamado na aplicacao
     *
     * @return o retorno sera exibido sem a complementação do controller
     */
    public static function getControlador()
    {
        return preg_replace('/[^A-z0-9]/', '_', self::$CONTROLLER);
    }

    /**
     * Retorna a acao chamada na aplicacao
     *
     * @return mixed
     */
    public static function getAcao()
    {
        return preg_replace('/[^A-z0-9]/', '_', self::$ACTION);
    }
}