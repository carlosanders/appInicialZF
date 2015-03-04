<?php

/**
 * Diretório Pai - app  
 * @author Carlos Anders 
 * @copyright Copyright (c) 2013, Carlos Anders
 * @package application/app
 * @version 1.0 
 * 
 * Camada - Sistema / Aplicação
 * Diretório Pai - application
 * 
 * Classe responsavel por renderizar os arquivos HTML 
 */
class View {

    /**
     * Armazena o conteúdo HTML
     * @var string
     */
    private $st_contents;

    /**
     * Armazena o nome do arquivo de visualização
     * @var string
     */
    private $st_view;

    /**
     * Armazena o nome do arquivo de visualização para o topo da pagina
     * @var string
     */
    private $viewHeader;

    /**
     * Armazena o nome do arquivo de visualização o rodape
     * @var string
     */
    private $viewFooter;

    /**
     * Armazena os dados que devem ser mostrados ao reenderizar o 
     * arquivo de visualização
     * @var Array
     */
    private $v_params;

    /**
     * Usado para receber um arquivo de configuracao que sera 
     * um objeto da classe Zend_Config_Ini
     * @var class Zend_Config_Ini
     */
    private $config;

    /**
     * usado para passar para a view se a pagina renderizada 
     * terá ou nao cabeçalho ou rodapé
     * o default eh - false
     * @var boolean 
     */
    private $cabecalhos = false;
    
    /**
     * usado para passar de qual o controlador você quer usar sua view correspondente
     * @var boolean 
     */
    private $pathController = false;

    /**
     * E possivel efetuar a parametrização do objeto ao instanciar o mesmo,
     * $st_view é o nome do arquivo de visualização a ser usado e 
     * $v_params são os dados que devem ser utilizados pela camada de visualização
     * $cabecalhos é opcional - true ou false, o default é true sempre ira existir cabecalho
     * 
     * @param string $st_view
     * @param Array $v_params
     * @param boolean $cabecalhos - opcional 
     * @param boolean $pathController - opcional; usado para setar de qual controller se quer usar a view
     */
    function __construct($st_view = null, $v_params = null, $cabecalhos = true, $pathController = false) {
        //recebe o obj Zend_Config_Ini do registro
        $this->config = Zend_Registry::get("config");
        //e chama os datos toda ves que o a classe eh instaciada
        //$this->setDados();
        //var_dump($st_view, $v_params, $cabecalhos, $pathController);        
        if($pathController){
            $this->pathController = $pathController;
        }else{
            $this->pathController = strtolower(Application::getControlador());
        }
        
        if ($st_view != null) {
            $this->setView($st_view);
        }

        $this->v_params = $v_params;
        //var_dump($cabecalhos);
        //echo Application::getControlador();
        if ($cabecalhos) {
            $this->cabecalhos = $cabecalhos;
            $this->viewHeader = $this->config->application->file->default->cabecalho; //recebe o cabecalho definido no INI
            $this->viewFooter = $this->config->application->file->default->rodape; //recebe o rodape definido no INI
        }
        
        
        
    }

    /**
     * Retorna uma string contendo todo 
     * o conteudo do arquivo de visualização
     * $this->config->path->controller .
     * strtolower(Application::getControlador()) . DIRECTORY_SEPARATOR .
     * @return string
     */
    public function getContents() {
        //Um ob_start() irá pegar todos os dados de saída e guardar em buffer. Os dados só serão enviados ao navegador 
        //no momento em que você encerrar o buffer.
        ob_start();
        if (isset($this->st_view)) {
            ($this->cabecalhos == TRUE) ? require_once $this->config->application->path->view . $this->viewHeader : NULL;
            require_once $this->config->application->path->view. $this->pathController . DIRECTORY_SEPARATOR . $this->st_view; //incluindo a visao dinamico            
            ($this->viewFooter == TRUE) ? require_once $this->config->application->path->view . $this->viewFooter : NULL;
        }
        $this->st_contents = ob_get_contents();
        //Esvazia o buffer e encerra-o. Nenhuma saída é enviada.
        ob_end_clean();
        return $this->st_contents;
    }

    /**
     * Define qual arquivo html deve ser renderizado
     * @param string $st_view
     * @throws Exception
     * strtolower(Application::getControlador()) . DIRECTORY_SEPARATOR .
     */
    public function setView($st_view) {
        //if (file_exists("../application/views/{$st_view}"))
        //echo $this->config->application->path->view . strtolower(Application::getControlador()) . DIRECTORY_SEPARATOR . $st_view;
        //echo $this->config->application->path->view . $this->pathController . DIRECTORY_SEPARATOR . $st_view;
        if (file_exists($this->config->application->path->view . $this->pathController . DIRECTORY_SEPARATOR . $st_view)){
            $this->st_view =  $st_view;
        }else{
            throw new Exception("O arquivo da visão '$st_view' não existe!");
        }
    }

    //GET & SET

    /**
     * Imprime o arquivo de visualização 
     */
    public function showContents() {
        echo $this->getContents();
        exit;
    }

    /**
     * Retorna o nome do arquivo que deve ser renderizado
     * @return string 
     */
    public function getView() {
        return $this->st_view;
    }

    /**
     * Define os dados que devem ser repassados é view
     * @param Array $v_params
     */
    public function setParams(Array $v_params) {
        $this->v_params = $v_params;
    }

    /**
     * Retorna os dados que foram ser repassados ao arquivo de visualização
     * @return Array
     */
    public function getConfigs() {
        //add as configurações disponiveis na visao
        return $this->config->get("site");
    }

    /**
     * Retorna os dados que foram ser repassados ao arquivo de visualização
     * @return Array
     */
    public function getParams() {
        return $this->v_params;
    }

    public function getViewHeader() {
        return $this->viewHeader;
    }

    public function setViewHeader($viewHeader) {
        $this->viewHeader = $viewHeader;
    }

    public function getViewFooter() {
        return $this->viewFooter;
    }

    public function setViewFooter($viewFooter) {
        $this->viewFooter = $viewFooter;
    }

}