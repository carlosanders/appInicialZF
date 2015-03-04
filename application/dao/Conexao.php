<?php

/**
 * Description of Conexao
 *
 * @author sg anders
 */
class Conexao
{

    protected $conn = null;
    protected $log;
    private $config;

    /**
     * O construtor da classe ao ser instanciada vira sempre com 
     * a conexao PDO caso o desenvolvedor nao defina qual conexao 
     * @param <string> $tipoConexao - default ZEND
     * @param <boolean> $profiler - default false
     * @param <boolean> $persistent - default false
     */
    function __construct($tipoConexao = "ZEND", $profiler = false, $persistent = false)
    {
        if ($profiler) {
            $this->config = Zend_Registry::get("config");
            $visible = $this->config->get("site");
            //http://devutils.blogspot.com.br/2009_08_01_archive.html
            $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
            $profiler->setEnabled($visible->log->firebug);
        }
        // verifico a persistência da conexao
        if ($persistent != false) {
            $this->persistent = true;
        }
        //vrf qual foi o tipo de conexao
        if ($tipoConexao == "ZEND") {
            $this->conexaoZend($profiler);
        } else {
            //echo 'aqui';
            $this->conexaoPDO();
        }
    }

    public function conexaoPDO()
    {
        try {
            /*
              $pdoParams = array(
              PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8;',
              PDO::ATTR_PERSISTENT => $this->persistent
              );
             */
            // realiza a conexão
            //$this->conn = new PDO("sqlite:../database/db.sq3");
            $this->conn = new PDO("sqlite:../database/bd_projeto.sq3");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Armazenar a instância de conexao ao BD no Registry
            //$registry = Registry::getInstance();
            //$registry->set('Connection', $this->conn);
            //a declaracao acima foi desabilitada pois essa classe sera uma classe PAI e as
            //classes DAO irão herdar dessa classe usando assim a conexao
        } catch (PDOException $e) {
            echo $e->getMessage();
            $log = new Log(get_class($this) . ".log");
            $log->insertIntoLogFile('URL: ' . $_SERVER['REQUEST_URI'] . " - Erro: " . $e->getMessage());
            //throw new PDOException(MensagemPadrao::ERRO_CONN);
        }
    }

    public function conexaoZend($profiler)
    {
        try {

            // Carrega automaticamente a classe Zend_Db_Adapter_Pdo_Mysql
            // e cria uma instancia dela.
            $this->conn = Zend_Db_Table::getDefaultAdapter();

            //$this->conn->getConnection();
            $this->conn->getProfiler()->setEnabled($profiler);
            
            //$this->conn->

            //classes DAO irão herdar dessa classe usando assim a conexao
        } catch (Zend_Db_Adapter_Exception $e) {
            echo $e->getMessage();
            //registra o erro no log do sistema
            $log = new Log(get_class($this) . ".log");
            $log->insertIntoLogFile('URL: ' . $_SERVER['REQUEST_URI'] . " - Erro: " . $e->getMessage());
            //throw new PDOException(MensagemPadrao::ERRO_CONN);
        }
    }

    /**
     * Configuracao do Log para o Sistema
     */
    protected function chamarLOG()
    {
        // configura o LOG
        $writer = new Zend_Log_Writer_Stream('../logs/'. __CLASS__ .'.log');
        $this->log = new Zend_Log($writer);
        $this->log->setTimestampFormat("d.m.Y H:i:s");
    }

}
