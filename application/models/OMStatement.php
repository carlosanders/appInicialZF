<?php

class OMStatement
{

    private $dao;

    // construtor
    public function __construct()
    {
        // chamo a classe ApoioDAO que fara a
        // comunicacao com o banco
        $this->dao = new OMDAO();
    }

    public function listarOMMilitar($codOM = "")
    {
        $resultado = array();
        //var_dump($codOM);
        if ($codOM != "") {
            $stmt = $this->dao->listarOMsAtivaMB($codOM);
            $row = $stmt->fetch();
            //var_dump($row);
            return $row;
        } else {
            $stmt = $this->dao->listarOMsAtivaMB();
            while ($row = $stmt->fetch()) {
                //array_push($resultado, $row);
                $resultado[] = array(
                    "CODOM" => $row->CODOM,
                    "NOMOM" => utf8_encode($row->NOMOM),
                    "NOMABREVIADO" => utf8_encode($row->NOMABREVIADO),
                    "TXTENDTELEGRAFICO" => $row->TXTENDTELEGRAFICO
                );
            }
        }
    }

    public function listarOMsAtivaMB($codOM = "")
    {
        //--relação das OM ativas na MB
        $resultado = array();
        //faco a vrf se eh vazio se for listo todas as OM senao somente a OM
        //do usuario logado e suas subordinadas
        if ($codOM == "") {
            $stmt = $this->dao->listarOMsAtivaMB();
        } else {
            $stmt = $this->dao->listarOMsSubAdm($codOM);
        }

        while ($row = $stmt->fetch()) {
            //array_push($resultado, $row);
            $resultado[] = array(
                "CODOM" => $row->CODOM,
                "NOMOM" => utf8_encode($row->NOMOM),
                "NOMABREVIADO" => utf8_encode($row->NOMABREVIADO),
                "TXTENDTELEGRAFICO" => $row->TXTENDTELEGRAFICO
            );

        }

        return $resultado;
    }

}

?>