<?php

/**
 * Description of ObserverValidator
 * Classe ObserverValidator é uma implementação do padrão de projeto Observer
 * Ira verificar se as validações na aplicação estão corretas. 
 *
 * @author Carlos Anders
 * @copyright (c) 2015, Carlos Anders
 */
class ObserverValidator
{

    protected $itens;
    protected $erros;
    private $interface;
    private $metodoFilter;

    /**
     * 
     * @param <Object> $interface tem que ser uma classe ou inteface. Por padrão será
     * considerado a interface do ZF1 Zend_Validator_Abstract
     * @param type $metodoFilter - metdo que a classe implementou da Interface
     */
    public function __construct($interface = "Zend_Validate_Abstract",
            $metodoFilter = "isValid")
    {
        $this->interface = $interface;
        $this->metodoFilter = $metodoFilter;
        $this->itens = array();
        $this->erros = array();
    }

    /**
     * o metodo add registra uma interface passada no segundo paramentro, com o propósito de vrf inclusive se o obj
     * repassado eh um instancia que implementa a interface atraves da função instanceof
     * 
     * @param <Objecto> $validador, que será confrontado com a interface em questao

     */
    public function add($validador, $valor)
    {
        $validar = $this->metodoFilter;
        //$validador->setTranslator($this->tradutor);
        if ($validador instanceof $this->interface) {

            if (!$validador->{$validar}($valor)) {
                $this->itens[] = array(
                    "messeges" => $validador->getMessages(),
                    "success" => false
                );
            } else {
                $this->itens[] = array("success" => true);
            }

            //$this->itens[] = $validador->{$validar}($valor);
        }

        //var_dump($this->itens);
    }

    /**
     * o metodo percorre os itens registrados e chama o metodo validar para
     * cada um dos itens. eh possivel chamar esse metodo pq ele esta descrito
     * na interface. o validar retorna true se a validacao passar, ou uma msg
     * de texto em caso de erros
     * @return type
     */
    public function validadeAll()
    {
        //$validar = $this->metodoFilter;
        //var_dump($this->itens);
        foreach ($this->itens as $item) {
            $validou = $item["success"];
            //var_dump('aqui: ',$validou);
            if (!($validou)) {
                foreach ($item["messeges"] as $erro => $valor) {
                    $this->erros[] = $erro . ': ' . $valor;
                }
            }
        }
        //var_dump($this->erros);
        //$rs = count($this->erros);
        //var_dump(array_count_values($this->erros));
        if (count($this->erros) == 0) {
            //echo 'aqui';
            return true;
        } else {
            return false;
        }
    }

    /**
     * Retorna os erros encontrado na validacao
     * @return type
     */
    public function getErros()
    {
        return $this->erros;
    }

}
