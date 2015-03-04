<?php

/**
 * Description of FireBug
 *
 * @author sg anders
 */
class FireBug {

    protected static $instacia;
    private $writer;
    private static $logger;
    private static $request;
    private static $response;
    private static $channel;

    private function __construct() {
        
        $config = Zend_Registry::get("config");        
        $visible = $config->get("site");

        $this->writer = new Zend_Log_Writer_Firebug();
        $this->writer->setEnabled($visible->log->firebug);
        self::$logger = new Zend_Log($this->writer);
        //start the wildfire component
        self::$request = new Zend_Controller_Request_Http();
        self::$response = new Zend_Controller_Response_Http();
        self::$channel = Zend_Wildfire_Channel_HttpHeaders::getInstance();
        self::$channel->setRequest(self::$request);
        self::$channel->setResponse(self::$response);
    }

    /**
     * Para pegar um instância da classe para habilitar
     * o log pelo FireBug e FirePHP
     */
    public static function getInstacia() {
        if (null === self::$instacia) {
            self::$instacia = new self();
        }

        return self::$instacia;
    }

    /**
     * Cria o log para ser exibido no navegador firebug com firephp
     * @param string $msg 
     * @param string $tipo = "ingo" | "err" | "warn"
     * A classe Zend possui as seguintes constantes Zend_Log::INFO | Zend_Log::ERR | Zend_Log::INFO Zend_Log::WARN
     * @example FireBug::getInstacia()->addMensagem("Sua Mensagem", Zend_Log::INFO);
     */
    public function addMensagem($msg = "", $tipo = "") {

        $tipo = ($tipo == "") ? Zend_Log::INFO : $tipo;
        // Now you can make calls to the logger
        self::$logger->log($msg, $tipo);
        //$this->logger->info('Informational message');
        //$this->logger->err('error message');
        //$this->logger->warn('atençao message');
        // Flush log data to browser
        self::$channel->flush();
        self::$response->sendHeaders();
    }

}

?>
