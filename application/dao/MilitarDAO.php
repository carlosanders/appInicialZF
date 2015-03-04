<?php

class MilitarDAO extends ConnectionOCI {

    function __construct() {
        parent::__construct();
    }

    public function listarPostosGradPorCirculo($codCirculo) {
        //--relação postos e graduacoes validos com o circulo
        //-- 1 Oficiais
        //-- 2 Praças
        //-- 3 Praças Especiais
        //where circulo=:circulo --quando o militar selecionar um circulo esta clausula devera estar habilitada

        try {
            $query = "\nSELECT   *
                            FROM (SELECT nroindexador, codpostograduacao, dscpostograduacao,
                                        (CASE
                                            WHEN nroindexador IN (2, 3, 4, 5, 6, 7, 8, 9, 10) THEN '1' --oficiais
                                            WHEN nroindexador IN (16, 17, 18, 19, 20, 21, 24) THEN '2' --pracas
                                            ELSE '3'--pracas especiais
                                        END
                                        ) circulo
                                    FROM tb60_postosgraduacoes
                                WHERE nroindexador IN (2,3,4,5,6,7,8,9,10,11,12,13,16,17,18,19,20,21,22,23,24,29,30)
                                )
                        WHERE circulo = " . $this->conn->quote($codCirculo, Zend_Db::INT_TYPE) . "--quando o militar selecionar um circulo esta clausula devera estar habilitada
                        ORDER BY nroindexador";

            $stmt = new Zend_Db_Statement_Oracle($this->conn, $query);
            $stmt->execute();
            $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
            $this->conn->closeConnection();
            //retorna o statement
            return $stmt;
        } catch (Zend_Exception $e) {
            $this->chamarLOG();
            $this->log->info('Metodo: listarPostosGraduacoesValidos()');
            $this->log->info('SQL: ' . $query);
            $this->log->info("Erro: " . $e->getMessage());
        }
    }

    public function militaresPorPostoGraduacaoDaOMeOMSubordAministrativamentePag($codOM, $postoGraduacao, $linha_inicial, $linhas_por_pagina, $buscaNIP, $buscaPessoa, $tipo = "") {
        // --relacao de militares por posto e graudacao da OM e militares de OM subordinadas administrativamente
        try {

            // Calcula o número da última linha na pagina
            $linha_final = $linha_inicial + $linhas_por_pagina - 1;
            // convertendo para maiusculo
            $buscaPessoa = utf8_decode(Helper::converteMaiscMinusc($buscaPessoa, 1));
            $buscaNIP = intval($buscaNIP);

            $parametros = array();
            $parametros[':linha_inicial'] = $linha_inicial;
            $parametros[':linha_final'] = $linha_final;

            if ($buscaNIP != '' || $buscaPessoa != '') {
                $busca = "AND (mi.nronip like '%{$buscaNIP}%') AND (pe.nompessoa like '%{$buscaPessoa}%')";
            } else {
                $busca = '';
            }

            switch ($tipo) {
                case 1:
                    $buscaIndexador = " AND po.nroindexador in (2, 3, 4, 5, 6, 7, 8, 9, 10)--oficiais";
                    break;
                case 2:
                    $buscaIndexador = " AND po.nroindexador in (16, 17, 18, 19, 20, 21, 24)--pracas";
                    break;
                case 3:
                    $buscaIndexador = " AND po.nroindexador in (11,12,13,22,23,29,30)--pracas espciais";
                    break;
                default:
                    $buscaIndexador = "";
                    break;
            }

            if ($codOM != '') {
                // echo 'OM: ' . $codOM;
                $parametros[':om'] = $codOM;
                $condOM = " AND mi.omatual IN (SELECT codomsubordinada
                                                FROM tb710_omsubordinadaadm
                                               WHERE codom = :om
                                              UNION
                                              SELECT codom
                                                FROM tb55_om
                                               WHERE codom = :om)";
            } else {
                $condOM = "";
            }

            if ($postoGraduacao != '') {
                // echo 'P/G: ' . $postoGraduacao;
                $parametros[':posto'] = $postoGraduacao;
                $condPosto = "AND me.codpostograduacao = :posto --lista por posto e graduacao Anders";
            } else {
                $condPosto = "";
            }

            $query = "\nSELECT   s.*
                        FROM (SELECT   s.*, ROWNUM AS numero_linha
                                  FROM (
                                        --início parte alterada
                                        SELECT   po.nroindexador,
                                                 TO_CHAR (mi.nronip, '09999999') AS nronip,
                                                 me.codsitmilitar, me.codcorpo, me.quadro,
                                                 me.codpostograduacao, me.nomeguerra,
                                                 me.aperfeicoamento, me.especialidade,
                                                 pe.nompessoa, o.codom, o.nomom, o.nomabreviado,
                                                 mi.nroescalaantiguidade,
                                                 (   me.codpostograduacao
                                                  || ''
                                                  || DECODE (me.aperfeicoamento,
                                                             '', DECODE (me.especialidade,
                                                                         '', '',
                                                                         '-' || me.especialidade
                                                                        ),
                                                             '-' || me.aperfeicoamento
                                                            )
                                                 ) AS pge
                                            FROM tb49_militares mi,
                                                 tb48_mestre me,
                                                 tb59_pessoa pe,
                                                 tb55_om o,
                                                 tb60_postosgraduacoes po
                                           WHERE mi.nronip = me.nronip
                                             AND me.nronip = pe.nronip
                                             AND mi.omatual = o.codom                                             
                                             AND me.codpostograduacao = po.codpostograduacao                         
                                             AND me.codsitmilitar IN
                                                    (1110,
                                                     1130,
                                                     1140,
                                                     1210,
                                                     1220,
                                                     1240,
                                                     1250,
                                                     1310,
                                                     1320,
                                                     1350,
                                                     1430,
                                                     1550,
                                                     1560,
                                                     1360
                                                    )
                                             {$condPosto}
                                             {$busca}                                             
                                             {$condOM}
                                             {$buscaIndexador}
                                        UNION
                                        SELECT   po.nroindexador,
                                                 TO_CHAR (mi.nronip, '09999999') AS nronip,
                                                 me.codsitmilitar, me.codcorpo, me.quadro,
                                                 me.codpostograduacao, me.nomeguerra,
                                                 me.aperfeicoamento, me.especialidade,
                                                 pe.nompessoa, o.codom, o.nomom, o.nomabreviado,
                                                 mi.nroescalaantiguidade,
                                                 (   me.codpostograduacao
                                                  || ''
                                                  || DECODE (me.aperfeicoamento,
                                                             '', DECODE (me.especialidade,
                                                                         '', '',
                                                                         '-' || me.especialidade
                                                                        ),
                                                             '-' || me.aperfeicoamento
                                                            )
                                                 ) AS pge
                                            FROM tb262_militaresreserva mi,
                                                 tb48_mestre me,
                                                 tb891_eventos ev,
                                                 tb59_pessoa pe,
                                                 tb55_om o,
                                                 tb60_postosgraduacoes po
                                           WHERE mi.nronip = me.nronip
                                             AND me.nronip = pe.nronip
                                             AND me.nronip = ev.nronip
                                             AND ev.fechamentoid IS NULL
                                             AND me.datdesligamento >= (SYSDATE - 30)
                                             AND mi.omatual = o.codom
                                             AND me.codpostograduacao = po.codpostograduacao
                                             {$condPosto}
                                             {$busca}                                             
                                             {$condOM}
                                             {$buscaIndexador}    
                                        ORDER BY nroindexador, nroescalaantiguidade
                                        --fim parte alterada
                                       ) s
                                 WHERE (ROWNUM <= :linha_final)
                              ORDER BY s.nroindexador, s.nroescalaantiguidade) s
                       WHERE (:linha_inicial <= numero_linha)
                    ORDER BY s.nroindexador, s.nroescalaantiguidade";

            $stmt = new Zend_Db_Statement_Oracle($this->conn, $query);
            $stmt->execute($parametros);
            $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
            // o codigo abaixo retorna um profiler do zend_db
            $profiler = $this->conn->getProfiler();
            $query1 = $profiler->getLastQueryProfile();
            // retorna a query
            //var_dump($profiler->getEnabled());
            // tempo execucao da query
            // echo $profiler->getTotalElapsedSecs();
            // retorna os parametros somente
            // var_dump($query1->getQueryParams());
            // retorna
            // var_dump($profiler->getQueryProfiles());
            // $this->log->info('SQL: ' . $query1->getQuery());
            // $this->log->info('PARANS: ' . $query1->getQueryParams());
            //retorna a query em firePHP no FireBUG
            if ($profiler->getEnabled()) {
                FireBug::getInstacia()->addMensagem($query1->getQuery(), Zend_Log::WARN);
                //FireBug::getInstacia()->addMensagem("Parametros do SQL:". $query1->getQueryParams(), Zend_Log::INFO);
                FireBug::getInstacia()->addMensagem("Tempo de consulta: " . $profiler->getTotalElapsedSecs(), Zend_Log::NOTICE);
                //$this->log->info('SQL: ' . $query);
                FireBug::getInstacia()->addMensagem($parametros, Zend_Log::NOTICE);
            }

            // retorna o statement
            return $stmt;
        } catch (Exception $e) {
            $this->chamarLOG();
            $this->log->info('Metodo: militaresPorPostoGraduacaoDaOMeOMSubordAministrativamentePag()');
            // $this->log->info('SQL: ' . $query);
            $this->log->info("Erro1: " . $e->getChainedException());
            $this->log->info("Erro2: " . $e->getCode());
            $this->log->info("Erro3: " . $e->getFile());
            $this->log->info("Erro4: " . $e->getMessage());
            $this->log->info("Erro5: " . $e->getPrevious());
            $this->log->info("Erro6: " . $e->getPrevious());
            $this->log->info("Erro7: " . $e->getTrace());
            $this->log->info("Erro8: " . $e->getTraceAsString());
        }
    }

    public function totalMilitaresPorPostoGraduacaoDaOMeOMSubordAministrativamente($codOM, $postoGraduacao, $buscaNIP, $buscaPessoa, $tipo = "") {
        // --relacao de militares por posto e graudacao da OM e militares de OM subordinadas administrativamente
        try {

            $parametros = array();
            if ($buscaNIP != '' || $buscaPessoa != '') {

                if (strlen($buscaNIP) < 8) {
                    $busca = "AND (mi.nronip like '%{$buscaNIP}%') AND (pe.nompessoa like upper('%{$buscaPessoa}%'))";
                } else {
                    $busca = "AND (mi.nronip = {$buscaNIP}) AND (pe.nompessoa like upper('%{$buscaPessoa}%'))";
                }
            } else {
                $busca = '';
            }

            switch ($tipo) {
                case 1:
                    $buscaIndexador = " AND po.nroindexador in (2, 3, 4, 5, 6, 7, 8, 9, 10)--oficiais";
                    break;
                case 2:
                    $buscaIndexador = " AND po.nroindexador in (16, 17, 18, 19, 20, 21, 24)--pracas";
                    break;
                case 3:
                    $buscaIndexador = " AND po.nroindexador in (11,12,13,22,23,29,30)--pracas espciais";
                    break;
                default:
                    $buscaIndexador = "";
                    break;
            }

            if ($codOM != '') {
                // echo 'OM: ' . $codOM;
                $parametros[':om'] = $codOM;
                $condOM = " AND mi.omatual IN (SELECT codomsubordinada
                                                FROM tb710_omsubordinadaadm
                                               WHERE codom = :om
                                              UNION
                                              SELECT codom
                                                FROM tb55_om
                                               WHERE codom = :om)";
            } else {
                $condOM = "";
            }

            if ($postoGraduacao != '') {
                // echo 'P/G: ' . $postoGraduacao;
                $parametros[':posto'] = $postoGraduacao;
                $condPosto = "AND me.codpostograduacao = :posto --lista por posto e graduacao Anders";
            } else {
                $condPosto = "";
            }
            $query = "\nSELECT   COUNT (*) AS total
                            FROM (
                                  --início parte alterada
                                  SELECT   mi.nronip
                                      FROM tb49_militares mi,
                                           tb48_mestre me,
                                           tb59_pessoa pe,
                                           tb55_om o,
                                           tb60_postosgraduacoes po
                                     WHERE mi.nronip = me.nronip
                                       AND me.nronip = pe.nronip
                                       AND mi.omatual = o.codom
                                       {$condPosto}
                                       AND me.codpostograduacao = po.codpostograduacao                         
                                       AND me.codsitmilitar IN
                                              (1110,
                                               1130,
                                               1140,
                                               1210,
                                               1220,
                                               1240,
                                               1250,
                                               1310,
                                               1320,
                                               1350,
                                               1430,
                                               1550,
                                               1560,
                                               1360
                                              )
                                       {$condOM}
                                       {$buscaIndexador}
                                       {$busca}
                                  UNION
                                  SELECT   mi.nronip
                                      FROM tb262_militaresreserva mi,
                                           tb48_mestre me,
                                           tb891_eventos ev,
                                           tb59_pessoa pe,
                                           tb55_om o,
                                           tb60_postosgraduacoes po
                                     WHERE mi.nronip = me.nronip
                                       AND me.nronip = pe.nronip
                                       AND me.nronip = ev.nronip
                                       AND ev.fechamentoid IS NULL
                                       AND me.datdesligamento >= (SYSDATE - 30)
                                       AND mi.omatual = o.codom                         
                                       {$condPosto}
                                       AND me.codpostograduacao = po.codpostograduacao
                                       {$condOM}
                                       {$buscaIndexador}
                                       {$busca}
                                  --fim parte alterada
                                 )";

            $stmt = new Zend_Db_Statement_Oracle($this->conn, $query);

            $stmt->execute($parametros);
            $stmt->setFetchMode(Zend_Db::FETCH_OBJ);

            return $stmt;
        } catch (Zend_Exception $e) {
            $this->chamarLOG();
            $this->log->info('Metodo: listarPostosGraduacoesValidos()');
            $this->log->info('SQL: ' . $query);
            $this->log->info("Erro: " . $e->getMessage());
        }
    }

    /**
     * Ao ser passado o NIP do miltar o metodo retorna dados basicos do militar
     * 
     * @param type $nronip
     *            Integer
     * @return um objeto Zend_Db_Statement_Pdo
     */
    public function retornaDadosMilitar($nronip) {
        try {
            $query = "\nSELECT p.nompessoa,
                                m.nronip,
                                m.codpostograduacao,
                                (   DECODE (m.aperfeicoamento, '', '', m.aperfeicoamento)
                                 || DECODE (m.especialidade, '', '', m.especialidade)
                                 || DECODE (m.subespecialidade, '', '', '-' || m.subespecialidade))
                                   AS apespsub,
                                m.nomeguerra,
                                m.codcorpo,
                                m.quadro,
                                mil.CODOM,
                                mil.NOMABREVIADO,
                                mil.NOMOM,
                               -- f.imgfoto,
                                m.aperfeicoamento,
                                m.especialidade,
                                m.subespecialidade
                           FROM tb48_mestre m,
                                tb59_pessoa p,
                               -- tb83_fotos f,
    
                                (select * from
                                tb49_militares ml,tb55_om om
                                where ml.omatual = om.codom)mil
    
                          WHERE     m.nronip = p.nronip
                                AND m.nronip = mil.nronip(+)
                               -- AND m.nronip = f.nronip(+)
                                --AND m.codsitmilitar NOT IN (1410, 1420)
                                AND m.nronip = :nronip";

            $stmt = new Zend_Db_Statement_Oracle($this->conn, $query);

            $parametros = array(
                ":nronip" => $this->conn->quote($nronip, Zend_Db::INT_TYPE)
            );

            $stmt->execute($parametros);
            $stmt->setFetchMode(Zend_Db::FETCH_OBJ);

            // retorna o statement
            return $stmt;
        } catch (Zend_Db_Adapter_Exception $e) {
            $this->chamarLOG();
            $this->log->info('Metodo: retornaDadosMilitar()');
            $this->log->info('SQL: ' . $query);
            $this->log->info("Erro: " . $e->getMessage());
        } catch (Zend_Exception $e) {
            $this->chamarLOG();
            $this->log->info('Metodo: retornaDadosMilitar()');
            $this->log->info('SQL: ' . $query);
            $this->log->info("Erro: " . $e->getMessage());
        }
    }

    public function retornaCorpoOf($codposto) {
        try {
            $query = "\n SELECT distinct(CODCORPO) CODCORPO 
                       FROM TB31_cqeposto
                       where CODPOSTOGRADUACAO = :posto 
                       order by CODCORPO     ";


            $stmt = new Zend_Db_Statement_Oracle($this->conn, $query);

            $parametros = array();
            $parametros[':posto'] = $codposto;
            $stmt->execute($parametros);
            $stmt->setFetchMode(Zend_Db::FETCH_OBJ);

            // retorna o statement
            return $stmt;
        } catch (Zend_Db_Adapter_Exception $e) {
            $this->chamarLOG();
            $this->log->info('Metodo: retornaCorpoOf()');
            $this->log->info('SQL: ' . $query);
            $this->log->info("Erro: " . $e->getMessage());
        } catch (Zend_Exception $e) {
            $this->chamarLOG();
            $this->log->info('Metodo: retornaCorpoOf()');
            $this->log->info('SQL: ' . $query);
            $this->log->info("Erro: " . $e->getMessage());
        }
    }

    public function retornaQuadroOf($posto, $corpo) {
        try {
            $query = "\n SELECT distinct(CODQUADRO) CODQUADRO
                       FROM TB31_cqeposto
                       where  CODPOSTOGRADUACAO = :posto and CODCORPO = :corpo 
                       order by CODQUADRO           ";

//upper(':codposto')
            $stmt = new Zend_Db_Statement_Oracle($this->conn, $query);

            $parametros = array();
            $parametros[':posto'] = $posto;
            $parametros[':corpo'] = $corpo;

            $stmt->execute($parametros);
            $stmt->setFetchMode(Zend_Db::FETCH_OBJ);

            // retorna o statement
            return $stmt;
        } catch (Zend_Db_Adapter_Exception $e) {
            $this->chamarLOG();
            $this->log->info('Metodo: retornaQuadroOf()');
            $this->log->info('SQL: ' . $query);
            $this->log->info("Erro: " . $e->getMessage());
        } catch (Zend_Exception $e) {
            $this->chamarLOG();
            $this->log->info('Metodo: retornaQuadroOf()');
            $this->log->info('SQL: ' . $query);
            $this->log->info("Erro: " . $e->getMessage());
        }
    }

    public function retornaPosto() {
        try {
            $query = "SELECT POS.CODPOSTOGRADUACAO,POS.DSCPOSTOGRADUACAO
                      FROM TB60_POSTOSGRADUACOES POS,
                     (SELECT  DISTINCT(CODPOSTOGRADUACAO) CODPOSTOGRADUACAO FROM TB31_cqeposto)cqe 
                      WHERE CQE.CODPOSTOGRADUACAO = POS.CODPOSTOGRADUACAO
                      AND POS.NROINDEXADOR<=10
                      ORDER BY NROINDEXADOR ";


            $stmt = new Zend_Db_Statement_Oracle($this->conn, $query);


            $stmt->execute();
            $stmt->setFetchMode(Zend_Db::FETCH_OBJ);

            // retorna o statement
            return $stmt;
        } catch (Zend_Db_Adapter_Exception $e) {
            $this->chamarLOG();
            $this->log->info('Metodo: retornaPosto()');
            $this->log->info('SQL: ' . $query);
            $this->log->info("Erro: " . $e->getMessage());
        } catch (Zend_Exception $e) {
            $this->chamarLOG();
            $this->log->info('Metodo: retornaPosto()');
            $this->log->info('SQL: ' . $query);
            $this->log->info("Erro: " . $e->getMessage());
        }
    }

}

?>