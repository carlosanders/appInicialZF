<?php

/**
 * Description of FiltraCep
 *
 * @author carlosanders
 */
class FiltraCep implements Zend_Filter_Interface
{

    private $cepFiltrado = "Cep inválido";

    public function filter($cep = "")
    {
        if ($cep == "") {
            return $this->cepFiltrado;
        } else {
            $inicio = substr($cep, 0, 5);
            $termino = substr($cep, 5);
            $this->cepFiltrado = $inicio . '-' . $termino;
            return $this->cepFiltrado;
        }
    }

}
