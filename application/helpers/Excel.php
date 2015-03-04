<?php

/**
 * Description of Excel
 *
 * 
 * @author Sg Anders
 */
class Excel {

    private $linha;
    private $celula;
    private $arquivo;
    private $mescla;
    var $fp = null;
    var $error;
    var $state = "CLOSED";
    var $newRow = false;
    public static $diretorioExcel = "excel/";

    /**
     * Inicializa a classe com o parametro nome do arquivo
     * senão for passado o nome sera usado o nome pre-definido
     * @param type $file
     */
    public function __construct($file = 'arquivo.xls') {
        $this->arquivo = $file;
        return $this->open($file);
    }

    function open($file) {
        if ($this->state != "CLOSED") {
            $this->error = "Error : Another file is opend .Close it to save the file";
            return false;
        }

        if (!empty($file)) {
            $this->fp = @fopen($file, "w+");
        } else {
            $this->error = "Usage : New ExcelWriter('fileName')";
            return false;
        }

        if ($this->fp == false) {
            $this->error = "Error: Unable to open/create File.You may not have permmsion to write the file.";
            return false;
        }
        $this->state = "OPENED";
        fwrite($this->fp, $this->GetHeader());
        return $this->fp;
    }

    function GetHeader() {
        return "";
    }

    /**
     * Funcao para criar a celula do Excel
     * 
     * @param type $tam     = tamanho da fonte;
     * @param type $largura = largura da celula;
     * @param type $font    = fonte q sera usado no arquivo
     * @param type $neg     = negrito 
     * @param type $color   = cor da fonte
     * @param type $alinha  = alinhamento da celula
     * @param type $borda   = borda da celula
     * @param type $fundo   = cor de fundo da celula
     * @param type $txt     = texto na celula
     */
    public function celula($tam, $largura, $font, $neg = 'N', $color, $alinha, $borda = 'N', $fundo = '', $txt) {

        if ($borda == 'S')
            $border = ' border-style:solid;border-width:thin; ';
        else
            $border = '';

        if ($fundo != '')
            $fundo = " background-color:$fundo; ";
        else
            $fundo = '';

        if ($neg == 'S')
            $this->celula .= "<td $this->mescla width=\"$largura\" style=\"$border $fundo\"><b><div align=\"$alinha\"><font size=\"$tam\" face=\"$font\" color=\"$color\">$txt</font></div></b></td>\n";
        else
            $this->celula .= "<td $this->mescla width=\"$largura\" style=\"$border  $fundo\"><div align=\"$alinha\"><font size=\"$tam\" face=\"$font\" color=\"$color\">$txt</font></div></td>\n";


        $this->mescla = '';
    }

    /**
     * Nova Linha em branco no arquivo
     */
    public function novaLinha() {
        $this->celula = "" . $this->celula . "<br>";
    }

    /**
     * Mesclar a celula
     * 
     * @param type $nrocol = qtd de colunas 
     */
    public function mesclarTd($nrocol = null) {
        if (isset($nrocol)) {
            $this->mescla = "colspan=" . $nrocol;
        } else
            $this->mescla = "";
    }

    /**
     * add linha na celula
     */
    public function ln() {
        $this->celula = "" . $this->celula . "</tr><tr align=\"center\">";
    }

    /**
     * Inicia a tabela com os dados
     */
    public function abreTable() {
        $this->celula = "" . $this->celula . "<table><tr align=\"center\">";
    }

    /*
     * Fecha a tabela no excel
     */

    public function fechaTable() {
        $this->celula = "" . $this->celula . "</tr></table>";
    }

    /**
     * Retorna a arquivo montado do excel 
     * 
     */
    public function saida() {
        $html = '';
        $html .= $this->celula;
        fwrite($this->fp, $html);
        // Configurações header para forçar o download
        /*
          header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
          header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
          header("Cache-Control: no-cache, must-revalidate");
          header("Pragma: no-cache");
          header("Content-type: application/x-msexcel");
          header("Content-Disposition: attachment; filename=\"{$this->arquivo}\"");
          header("Content-Description: PHP Generated Data");
         * 
         */
        // Envia o conteúdo do arquivo
        //echo $html;
        return $html;
    }

}

?>
