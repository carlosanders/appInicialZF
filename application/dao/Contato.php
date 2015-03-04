<?php

/**
 *
 * @author sg anders
 *        
 */
class Contato extends Conexao {

    /**
     *
     * @param <string> $tipoConexao
     *        	- default ZEND
     *        	
     * @param <boolean> $persistent
     *        	- default false
     *        	
     */
    public function __construct($tipoConexao = "ZEND", $profile = false) {
        parent::__construct($tipoConexao, $profile);
    }

    /**
     *
     * @param <boolean> $todos        	
     * @return Ambigous <multitype:, multitype:mixed Ambigous <string, boolean, mixed> >|string
     */
    public function getContatos($todos = true) {
        try {
            if ($todos) {
                $select = $this->conn->select()->from("contato");
                return $this->conn->fetchAll($select);
            } else {
                return "error";
            }
        } catch (Zend_Exception $e) {
            // $log = new Log ( get_class ( $this ) . ".log" );
            // $log->insertIntoLogFile ( 'URL: ' . $_SERVER ['REQUEST_URI'] . " - Erro: " . $e->getMessage () );
            // $log->insertIntoLogFile ( 'SQL: ' . $query );
            echo $e->getMessage();
            // throw new Zend_Exception ("error");
        }
    }

    public function getContatosComPDO($todos = true) {
        try {
            if ($todos) {
                $st_query = 'SELECT * FROM contato';
                $o_data = $this->conn->query($st_query);
                return $o_data->fetchObject();
            } else {
                return "error";
            }
        } catch (Zend_Exception $e) {
            // $log = new Log ( get_class ( $this ) . ".log" );
            // $log->insertIntoLogFile ( 'URL: ' . $_SERVER ['REQUEST_URI'] . " - Erro: " . $e->getMessage () );
            // $log->insertIntoLogFile ( 'SQL: ' . $query );
            echo $e->getMessage();
            // throw new Zend_Exception ("error");
        }
    }

}