<?php

/**
 * Description of ValidateNIP
 * Valida o nronip pelo PHP, no padrao do ZF1, implementando a classe abstrata
 * Zend_Validate_Abstract
 *
 * @author Carlos Anders
 * @copyright (c) 2015, Carlo Anders
 * @license http://framework.zend.com/manual/1.12/en/zend.validate.introduction.html
 */
class ValidateNIP extends Zend_Validate_Abstract
{

    const NRONIP = 'nronipInvalid';
    const MSG_NUMERIC = 'nronipNotNumeric';

    protected $_messageTemplates = array(
        self::NRONIP => "O valor '%value%' não correponde a um NIP válido",
        self::MSG_NUMERIC => "O valor '%value%' não corresponde a um valor numérico válido"
    );

    public function isValid($value)
    {
        $this->_setValue($value);

        if (!is_numeric($value)) {
            $this->_error(self::MSG_NUMERIC);
            return false;
        }
     
        $d1 = 0;
        $d2 = 0;
        $total = strlen($value);
        if ($total > 8) {
            $this->_error(self::NRONIP);
            return false;
        } else {
            for ($i = 1; $i <= (8 - $total); $i++) {
                $value = "0" . $value;
                //var_dump($value);
            }
            $d2 = substr($value, 7, 1);
            //var_dump($d2);
            for ($i = 0; $i <= 6; $i++) {
                $d1 += substr($value, $i, 1) * (8 - $i);
            }
            //var_dump($d1);
            $d1 = 11 - ($d1 % 11);
            //var_dump($d1, $d2);
            if ($d1 == 11) {
                $d1 = 1;
            }
            if ($d1 == 10) {
                $d1 = 0;
            }
            if ($d1 != $d2) {
                $this->_error(self::NRONIP);
                return false;
            } else {
                return true;
            }
        }
    }

}
