<?php

class OMDAO extends Conexao
{

    function __construct()
    {
        parent::__construct();
    }

    public function listarOMsSubAdm($codom)
    {
        // --relação das OM ativas na MB
        try {
            $query = "\nSELECT o.codom, o.nomom, o.nomabreviado, o.txtendtelegrafico
                          FROM tb55_om o
                         WHERE  o.codom IN (SELECT codomsubordinada
                                              FROM tb710_omsubordinadaadm
                                             WHERE codom = :codom
                                            UNION
                                            SELECT codom
                                              FROM tb55_om
                                             WHERE codom = :codom)";
            
            $stmt = new Zend_Db_Statement_Pdo($this->conn, $query);
            $parametros = array(
                ':codom' => $codom
            );
            
            $stmt->execute($parametros);
            $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
            $this->conn->closeConnection();
            // retorna o statement
            return $stmt;
        } catch (Zend_Exception $e) {
            $this->chamarLOG();
            $this->log->info('Metodo: listarOMsSubAdm()');
            $this->log->info("Erro: " . $e->getMessage());
        }
    }

    public function listarOMsAtivaMB($codigo = "")
    {
        // --relação das OM ativas na MB
        try {
            $parametros = array();
            if ($codigo != "") {
                $parametros[':codOM'] = $codigo;
                $codOM = " AND a.codom = :codOM";
            } else {
                $codOM = NULL;
            }
            
            $query = "\nSELECT   a.codom, a.nomom, a.nomabreviado, a.txtendtelegrafico
                        FROM tb55_om a
                        WHERE a.situacao = 1
                        {$codOM}
                        ORDER BY a.nomom ASC";
            
            $stmt = new Zend_Db_Statement_Pdo($this->conn, $query);
            $stmt->execute($parametros);
            $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
            $this->conn->closeConnection();
            // retorna o statement
            return $stmt;
        } catch (Zend_Exception $e) {
            $this->chamarLOG();
            $this->log->info('Metodo: listarOMsAtivaMB()');
            $this->log->info("Erro: " . $e->getMessage());
        }
    }
}

?>