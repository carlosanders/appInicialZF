<?php

/* Observacoes importantes

 * Zend_Db::FETCH_NUM: retorna dados em um matriz de matrizs.
  Os matrizs sao indexados por inteiros, correspondendo a  posicao
  do respectivo campo na lista de selecao da consulta.
 * Zend_Db::FETCH_BOTH: retorna dados em um matriz de matrizs.
  As chaves de matriz sao tanto strings como as usadas no modo
  FETCH_ASSOC, como inteiros como os usados no modo FETCH_NUM.
  Note que o numero de elementos na matriz Ã© o dobro do que
  seria se voce usasse FETCH_ASSOC ou FETCH_NUM.
 * Zend_Db::FETCH_COLUMN: retorna dados em um matriz de valores.
  O valor em cada matriz e o valor retornado pela coluna do conjunto
  de resultados. Por padrao, essa e a primeira coluna, indexada por 0.
 * Zend_Db::FETCH_OBJ: retorna dados em um matriz de objetos.
  A classe padrao e a classe interna PHP stdClass.
  Colunas do conjunto de resultados estao disponiveis
  como propriedades publicas do objeto.
 */

class ConnectionOCI {

    protected $conn = null;
    //private $host = "10.5.48.163";//homolog
    private $host = "10.5.48.30"; //desenv
    //private $host = "10.10.16.11";
    private $port = "1521";
    private $username = "USERTFT"; // "USERTFT";
    private $password = "l0taca0TFT";          // "l0taca0tft";
    private $dbname = "desenv"; //"PROD"; //
    //private $banco = "(DESCRIPTION=(ADDRESS = (PROTOCOL = TCP)(HOST=10.5.48.163)(PORT=1521))(CONNECT_DATA=(SID=PROD)))";
    //private $banco = "(DESCRIPTION=(ADDRESS = (PROTOCOL = TCP)(HOST=10.10.16.11)(PORT=1521))(CONNECT_DATA=(SID=PROD)))";
    protected $log;
    private $config;

    public function __construct() {
        try {

            $this->config = Zend_Registry::get("config");
            $visible = $this->config->get("site");

            // Nos nao precisamos seguir a declaracao a seguir porque o
            // arquivo Zend_Db_Adapter_Pdo_Mysql sera carregado para nos pelo Metodo Zend_Db
            // factory.
            // require_once 'Zend/Db/Adapter/Pdo/Mysql.php';
            // Carrega automaticamente a classe Zend_Db_Adapter_Pdo_Mysql
            // e cria uma instancia dela.
            $this->conn = Zend_Db::factory('oracle', array(
                        'host' => $this->host,
                        'port' => $this->port,
                        'dbname' => "(DESCRIPTION=(ADDRESS = (PROTOCOL = TCP)(HOST={$this->host})(PORT={$this->port}))(CONNECT_DATA=(SID={$this->dbname})))",
                        'username' => $this->username,
                        'password' => $this->password
            ));

            //http://devutils.blogspot.com.br/2009_08_01_archive.html
            $profiler = new Zend_Db_Profiler_Firebug('All DB Queries'); 
            $profiler->setEnabled($visible->log->firebug);           
            $this->conn->setProfiler($profiler);

            //$profiler->getLastQueryProfile()->getQueryParams()->toArray();
            //$this->conn->getProfiler()->setEnabled(true);
            //var_dump($this->conn);
            //$this->conn->nextSequenceId($sequenceName);
            //$this->conn->prepare($sql)->fbindParam($parameter, $variable);
            /* foi comentado para que em cada metodo de consulta
             * possamos escolher qual padrao usaremos
             */
            //$this->conn->setFetchMode(Zend_Db::FETCH_OBJ)->;
        } catch (Zend_Db_Adapter_Exception $e) {
            // talvez uma credencial de login falhou, ou talvez o SGBDR nao esta rodando
            //echo "Erro1: " . $e->getMessage();
            $this->chamarLOG();
            $this->log->info('Metodo: construtor()');
            $this->log->info("Erro Zend_Db_Adapter_Exception: " . $e->getMessage());
            throw new Zend_Exception($e->getMessage());
        } catch (Zend_Exception $e) {
            // talvez factory() falhou em carregar a classe adaptadora especificada
            //echo "Erro2: " . $e->getMessage();
            $this->chamarLOG();
            $this->log->info('Metodo: construtor()');
            $this->log->info("Erro Zend_Exception: " . $e->getMessage());
            throw new Zend_Exception($e->getMessage());
        }
    }

    //retorna uma conexao OCI sem ser no formato PDO Anders
    public function retornaConexaoOracleOCI() {
        $banco = "(DESCRIPTION=(ADDRESS = (PROTOCOL = TCP)(HOST={$this->host})(PORT={$this->port}))(CONNECT_DATA=(SID={$this->dbname})))";
        return ocilogon($this->username, $this->password, $banco);
    }

    /**
     * Configuracao do Log para o Sistema
     */
    protected function chamarLOG() {
        // configura o LOG
        $writer = new Zend_Log_Writer_Stream('../logs/ConnectionOCI.log');
        $this->log = new Zend_Log($writer);
        $this->log->setTimestampFormat("d.m.Y H:i:s");
    }

}

?>