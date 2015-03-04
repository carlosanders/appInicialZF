<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DatagridDAO
 *
 * @author carlosanders
 */
class DatagridDAO extends Conexao
{

    private $table = "datagrid";

    public function __construct($tipoConexao = "ZEND", $profile = false)
    {
        parent::__construct($tipoConexao, $profile);
    }

    public function getCidadesPorEstado($codigo = false, $uf = false, $todos = false)
    {
        $parametros = array();

        $whereId = NULL;
        if ($codigo != false && $uf == false && $todos == false) {
            $parametros[':codigo'] = $codigo;
            $whereId = "AND c.id = :codigo";
        } else if ($codigo == false && $uf != false && $todos == false) {
            $parametros[':uf'] = $uf;
            $whereId = "AND c.uf_estado = :uf";
        }

        $query = "select c.id, c.nome, c.uf_estado
                    from cidade c
                    where 1 = 1   
                  {$whereId}";
        try {
            $stmt = new Zend_Db_Statement_Pdo($this->conn, $query);
            $stmt->execute($parametros);
            $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
            return $stmt;
        } catch (Zend_Exception $e) {
            $this->chamarLOG();
            $this->log->info('Metodo: ' . __METHOD__);
            $this->log->info('SQL: ' . $query);
            $this->log->info("Erro: " . $e->getMessage());
        }
    }

    public function getCidadesPorEstado1($codigo = false, $uf = false, $todos = false)
    {
        $parametros = array();

        $whereId = NULL;
        if ($codigo != false && $uf == false && $todos == false) {
            $parametros[':codigo'] = $codigo;
            $whereId = "AND c.id = :codigo";
        } else if ($codigo == false && $uf != false && $todos == false) {
            $parametros[':uf'] = $uf;
            $whereId = "AND c.uf_estado = :uf";
        }

        $query = "INSERT INTO cidade (uf_estado, nome) "
                . "VALUES('RJ', 'Iconha')";

        $this->conn->beginTransaction();
        try {

            //Prepara e executa uma instrução SQL com dados vinculados.
            $stmt = $this->conn->query($query);
            //$stmt->execute();
            (var_dump($stmt->rowCount()));
            FireBug::getInstacia()->addMensagem($stmt->rowCount(),
                    Zend_Log::INFO);
//            $stmt = new Zend_Db_Statement_Pdo($this->conn, $query);
//            $stmt->execute($parametros);
//            $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
            $this->conn->commit();
            return die();
        } catch (Zend_Exception $e) {
            $this->chamarLOG();
            $this->log->info('Metodo: ' . __METHOD__);
            $this->log->info('SQL: ' . $query);
            $this->log->info("Erro: " . $e->getMessage());


            $this->conn->rollBack();

            return die($e->getMessage());
        }
    }

    public function getEstado($uf = NULL, $todos = false)
    {
        $parametros = array();

        $whereId = NULL;
        if ($uf != NULL && $todos == false) {
            $parametros[':sigla'] = $uf;
            $whereId = "AND uf.uf = :sigla";
        }

        $query = "select uf.uf, uf.nome
                    from estado uf
                    where 1 = 1  
                  {$whereId}";
        try {

            $stmt = new Zend_Db_Statement_Pdo($this->conn, $query);
            $stmt->execute($parametros);
            $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
            //$rows = $stmt->fetchAll();
            return $stmt;
        } catch (Zend_Exception $e) {
            $this->chamarLOG();
            $this->log->info('Metodo: ' . __METHOD__);
            $this->log->info('SQL: ' . $query);
            $this->log->info("Erro: " . $e->getMessage());
        }
    }

    public function getCategoria($codigo = NULL, $todos = false)
    {
        $parametros = array();

        $whereId = NULL;
        if ($codigo != NULL && $todos == false) {
            $parametros[':codigo'] = $codigo;
            $whereId = "AND ct.id = :codigo";
        }

        $query = "select ct.id, ct.descricao, ct.icone
                  from categoria ct
                  where 1 = 1 
                  {$whereId}";
        try {

            $stmt = new Zend_Db_Statement_Pdo($this->conn, $query);
            $stmt->execute($parametros);
            $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
            //$rows = $stmt->fetchAll();
            return $stmt;
        } catch (Zend_Exception $e) {
            $this->chamarLOG();
            $this->log->info('Metodo: ' . __METHOD__);
            $this->log->info('SQL: ' . $query);
            $this->log->info("Erro: " . $e->getMessage());
        }
    }

    /**
     * 
     * @param <boolean> $todos        	
     * @return Ambigous <multitype:, multitype:mixed Ambigous <string, boolean, mixed> >|string
     */
    public function getDataGrid($todos = true, $satus = "", $estados = "")
    {

        $parametros = array();



        if ($satus != "" && $satus != "undefined") {
            $parametros[':status'] = $satus;
            $whereStatus = "and dg.status = :status";
        } else {
            $whereStatus = NULL;
        }

        if ($estados != "" && $estados != "undefined") {
            $parametros[':atribute'] = $estados;
            $whereAttr1 = "and dg.attr1 = :atribute";
        } else {
            $whereAttr1 = NULL;
        }

        $query = "select dg.productid, dg.productname, dg.unitcost, dg.status, dg.listprice, dg.attr1, dg.uf_estado,
                            ct.descricao, ct.icone, uf.nome, uf.uf
                     from datagrid dg,
                          categoria ct,     
                          estado uf
                     where 1 = 1 
                     AND dg.attr1 = ct.id
                     AND dg.uf_estado = uf.uf
                    {$whereStatus}
                    {$whereAttr1}";

        try {
            if ($todos) {
                $stmt = new Zend_Db_Statement_Pdo($this->conn, $query);
                $stmt->execute($parametros);
                $stmt->setFetchMode(Zend_Db::FETCH_OBJ);

                //FireBug::getInstacia()->addMensagem($query, Zend_Log::INFO);
                //$countRow = $rs->count();
// Ctrl-Shift-C           
//                $countRowsSelect = $this->conn->select()
//                        ->setIntegrityCheck(false)
//                        ->from(array('cnt' => $select),
//                        array('row_count' => 'COUNT(*)'));
//                
//                $countRow = $countRowsSelect->query()->fetchAll();
                $rows = $stmt->fetchAll();
                return $rows;
                //return $this->conn->fetchAll($select);
            } else {
                //productid
                //                $select = $this->conn->select()->from($this->table)
//                        ->where( /*...specify search criteria... */)
//                        ->order( /*...specify sorting criteria...*/ );
//                $rs = $this->conn->fetchAll($select);
                return "error";
            }
        } catch (Zend_Exception $e) {
            // $log = new Log ( get_class ( $this ) . ".log" );
            // $log->insertIntoLogFile ( 'URL: ' . $_SERVER ['REQUEST_URI'] . " - Erro: " . $e->getMessage () );
            // $log->insertIntoLogFile ( 'SQL: ' . $query );
            //echo $e->getMessage();
            // throw new Zend_Exception ("error");
            $this->chamarLOG();
            $this->log->info('Metodo: ' . __METHOD__);
            $this->log->info('SQL: ' . $query);
            $this->log->info("Erro: " . $e->getMessage());
        }
    }

    public function add(Produto $p)
    {

        $dados = array();
        
        //$dados['productid'] = $p->getInptCodProd();
        $dados['productname'] = $p->getInptDescricao();
        $dados['unitcost'] = $p->getInptPrecoAnt();
        $dados['status'] = $p->getStatus();
        $dados['listprice'] = $p->getInptPrecoAtual();
        $dados['attr1'] = $p->getCbCategoria();
        $dados['uf_estado'] = $p->getCbEstados();
        
        FireBug::getInstacia()->addMensagem($dados, Zend_Log::INFO);

        try {
            $linhas = $this->conn->insert('datagrid', $dados);
        } catch (Zend_Exception $e) {
            $linhas = 0;
            $this->chamarLOG();
            $this->log->info('Metodo: ' . __METHOD__);
            $this->log->info("Erro: " . $e->getMessage());
        }

        return $linhas;
    }
    
    public function update(Produto $p)
    {

        $dados = array();
        
        //$dados['productid'] = $p->getInptCodProd();
        $dados['productname'] = $p->getInptDescricao();
        $dados['unitcost'] = $p->getInptPrecoAnt();
        $dados['status'] = $p->getStatus();
        $dados['listprice'] = $p->getInptPrecoAtual();
        $dados['attr1'] = $p->getCbCategoria();
        $dados['uf_estado'] = $p->getCbEstados();
        
        FireBug::getInstacia()->addMensagem($dados, Zend_Log::INFO);

        try {
            $linhas = $this->conn->update('datagrid', $dados, 'productid=' . $p->getInptCodProd());
        } catch (Zend_Exception $e) {
            $linhas = 0;
            $this->chamarLOG();
            $this->log->info('Metodo: ' . __METHOD__);
            $this->log->info("Erro: " . $e->getMessage());
        }

        return $linhas;
    }

}
