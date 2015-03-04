<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExemploStmt
 *
 * @author carlosanders
 */
class ExemploStmt
{

    private $dao;

    function __construct()
    {
        $this->dao = new DatagridDAO();
    }
    
    public function getCidadesPorEstado($codigo = false, $uf = false, $todos = false)
    {
        $resultado = array();
        
        if ($codigo != false && $uf == false && $todos == false) {
            
            $stmt = $this->dao->getCidadesPorEstado($codigo, false, false);
            $row = $stmt->fetch();
            //var_dump($row);
            return $row;
        }else if($codigo == false && $uf != false && $todos == false){
            $stmt = $this->dao->getCidadesPorEstado(false, $uf, false);   
            die();
            while ($row = $stmt->fetch()) {
                $registro = array();
                $registro["ID"] = $row->id;
                $registro["NOME"] = ($row->nome);
                $registro["UF_ESTADO"] = ($row->uf_estado);
               
                array_push($resultado, $registro);
            }            
            return $resultado;
        }  else if($codigo != false && $uf != false && $todos == true){
            $stmt = $this->dao->getCidadesPorEstado($codigo, $uf, $todos); 
            
            while ($row = $stmt->fetch()) {
                $registro = array();
                $registro["ID"] = $row->id;
                $registro["NOME"] = ($row->nome);
                $registro["UF_ESTADO"] = ($row->uf_estado);
                
                if ($codigo == $row->id) {
                    $registro["selected"] = true;
                }
               
                array_push($resultado, $registro);
            }            
            return $resultado;
        }else{
            
            $stmt = $this->dao->getCidadesPorEstado();
            
            while ($row = $stmt->fetch()) {
                $registro = array();
                $registro["ID"] = $row->id;
                $registro["NOME"] = ($row->nome);
                $registro["UF_ESTADO"] = ($row->uf_estado);
                              
                array_push($resultado, $registro);
            }            
            return $resultado;
        }
            
    }

    
    public function getEstado($codigo = NULL, $todos = false)
    {
        $resultado = array();
        //nesse if quero retornar somente uma categoria
        if ($codigo != NULL && $todos == false) {
            $stmt = $this->dao->getEstado($codigo, $todos);
            $row = $stmt->fetch();
            //var_dump($row);
            return $row;

            //nesse if quero retornar o registro com o codigo selected e os demais registro
        } else if ($codigo != NULL && $todos == true) {
            $stmt = $this->dao->getEstado($codigo, $todos);            
            while ($row = $stmt->fetch()) {

                $registro = array();
                $registro["UF"] = $row->uf;
                $registro["NOME"] = ($row->nome);

                if ($codigo == $row->uf) {
                    $registro["selected"] = true;
                }
                
                array_push($resultado, $registro);
            }
            return $resultado;
        } else {
            $stmt = $this->dao->getEstado();
            while ($row = $stmt->fetch()) {
                //array_push($resultado, $row);
                $resultado[] = array(
                    "UF" => $row->uf,
                    "NOME" => ($row->nome)
                );
            }
            return $resultado;
        }
    }

    public function getCategoria($codigo = NULL, $todos = false)
    {
        $resultado = array();
        //nesse if quero retornar somente uma categoria
        if ($codigo != NULL && $todos == false) {
            $stmt = $this->dao->getCategoria($codigo, $todos);
            $row = $stmt->fetch();
            //var_dump($row);
            return $row;

            //nesse if quero retornar o registro com o codigo selected e os demais registro
        } else if ($codigo != NULL && $todos == true) {
            $stmt = $this->dao->getCategoria($codigo, $todos);            
            while ($row = $stmt->fetch()) {

                $registro = array();
                $registro["ID"] = $row->id;
                $registro["DESCRICAO"] = utf8_encode($row->descricao);
                $registro["ICONE"] = ($row->icone);

                if ($codigo == $row->id) {
                    $registro["selected"] = true;
                }
                
                array_push($resultado, $registro);
            }
            return $resultado;
        } else {
            $stmt = $this->dao->getCategoria();
            while ($row = $stmt->fetch()) {
                //array_push($resultado, $row);
                $resultado[] = array(
                    "ID" => $row->id,
                    "DESCRICAO" => utf8_encode($row->descricao),
                    "ICONE" => ($row->icone)
                );
            }
            return $resultado;
        }
    }
    
    public function salvarProduto(Produto $p)
    {
        $obj = new stdClass();
        
        if($p->getInptCodProd() != ""){
            $r = $this->dao->update($p);
        }else{
            $r = $this->dao->add($p);
        }
        
        if($r > 0){
            $obj->success = TRUE;
            $obj->msg = "registro salvo com sucesso!";
        }else{
            $obj->success = FALSE;
            $obj->msg = "não foi possível salvar o registro.";
        }
        
        return $obj;
    }

}
