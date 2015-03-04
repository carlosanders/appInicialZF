<?php

/**
 * Classe para controle de gravação de logs e TXT
 * @version 1.2 - 30/01/2015
 * @author Carlos Anders <carlosanders@gmail.com>
 * 
 * 
 * @referencia
 * -------------------------------------------------
 * @author Orlando Xavier <ox@orlandoxavier.com>
 * http://www.vivaolinux.com.br/script/Classe-para-gerar-logs
 * -------------------------------------------------
 * 
 */
class Log
{
    /*
     * Arquivo de logs
     */
    private $file;

    private $arquivo;

    private $path;

    private $modo;

    /**
     *
     *
     *
     *
     *
     *
     * Modo	Descrição
     * 'w' - Abre o arquivo para apenas escrita; coloca o ponteiro do arquivo no começo do arquivo e diminui (trunca)
     * o tamanho do arquivo para zero. Se o arquivo não existe, tenta criá-lo.
     * 'w+'	Abre o arquivo para leitura e escrita; coloca o ponteiro do arquivo no começo e diminui (trunca)
     * o tamanho do arquivo para zero. Se o arquivo não existe, tenta criá-lo.
     * 'r' Abre o arquivo somente para leitura; coloca o ponteiro de escrita no começo do arquivo.
     * [Retorna um erro caso o arquivo não exista e não tenta cria-lo.]
     * 'r+' Abre para leitura e escrita; coloca o ponteiro de escrita no começo do arquivo. [Retorna um erro
     * caso o arquivo não exista e não tenta cria-lo.]
     * 'a'	Abre para somente para escrita somente; coloca o ponteiro do arquivo no final. Se o arquivo não existir,
     * tenta criá-lo.
     * 'a+'	Abre o arquivo para leitura e escrita; coloca o ponteiro do arquivo no final. Se o arquivo não existir,
     * tenta criá-lo.
     * 'x'	Cria e abre o arquivo para escrita somente; coloca o ponteiro no início do arquivo.
     * Se o arquivo já existe, a chamada a fopen() irá falhar, retornando FALSE, gerando um erro nível E_WARNING.
     * Se o arquivo não existe, tenta criá-lo. Esta opção é suportada no PHP 4.3.2 e posteriores, e somente funciona em arquivos locais.
     * 'x+'	Cria e abre um arquivo para escrita e leitura; coloca o ponteiro do arquivo no início.
     * Se o arquivo já existe, a chamada a fopen() irá falhar, retornando FALSE, gerando um erro nível E_WARNING. Se o arquivo não existe, tenta criá-lo. Esta opção é suportada no PHP 4.3.2 e posteriores, e somente funciona em arquivos locais.
     *
     * @param <string> $arquivo
     *            Arquivo a ser criado e/ou usado
     * @param <string> $path
     *            Arquivo a ser criado e/ou usado
     * @param <string> $modo            
     */
    public function __construct($arquivo = NULL, $path = NULL, $modo = NULL)
    {
        $dataHj = date("d-m-Y_H-i");
        $this->arquivo = ($arquivo == NULL) ? "LogSistema" . $dataHj : $arquivo;
        $this->path = ($path == NULL) ? "../logs/" : $path;
        $this->modo = $modo;
        $file = $this->path . $this->arquivo;
        // Deixa o arquivo oculto caso o sistema seja UNIX-like
        if (strtoupper(substr(PHP_OS, 0, 3)) != 'WIN') {
            $this->file = '.' . $file;
        } else {
            $this->file = $file;
        }
    }

    /**
     * Prepara o arquivo para gravação
     */
    private function fileCheckAndPrepare()
    {
        if ($this->modo == "") {
            if (! file_exists($this->file)) {
                $f = fopen($this->file, 'w');
            } else {
                $f = fopen($this->file, 'a');
            }
        } else {
            $f = fopen($this->file, $this->modo);
        }
        return $f;
    }

    /**
     * Insere texto no arquivo
     *
     * @param <string> $event            
     * @return boolean
     */
    public function insertIntoFile($event)
    {
        $msg = "";
        $f = $this->fileCheckAndPrepare();
        // $logText = '(' . date('d/m/Y H:i:s') . ') ' . '[IP] ' . $_SERVER['REMOTE_ADDR'] . ' => [Evento] ' . $event . "\n";
        $msg .= $event . "\n";
        
        if (! fwrite($f, $msg)) {
            $r = false;
        } else {
            $r = true;
        }
        fclose($f);
        
        return $r;
    }

    /**
     *
     * @param <string> $event
     *            Evendo definido pelo programador
     * @return <bool> $r
     */
    public function insertIntoLogFile($event)
    {
        $f = $this->fileCheckAndPrepare();
        $logText = '(' . date('d/m/Y H:i:s') . ') ' . '[IP] ' . $_SERVER['REMOTE_ADDR'] . ' => [Evento] ' . $event . "\n";
        if (! fwrite($f, $logText)) {
            $r = false;
        } else {
            $r = true;
        }
        fclose($f);
        
        return $r;
    }

    /**
     * Se passar o paramentro retornara somente a qtd solicigtada
     * senao retorna todo o arquivo
     * 
     * @param <bytes> $tamanho            
     * @return string
     */
    public function exibirConteudo($tamanho = FALSE)
    {
        $file = $this->path . $this->arquivo;
        
        $arquivo = fopen($file, 'r');
        if ($arquivo == false)
            die('Não foi possível abrir o arquivo.');
        
        if ($tamanho) {
            
            $linha = fgets($arquivo, $tamanho);
            fclose($arquivo);
            return $linha;
        } else {
            
            $string = file_get_contents($file);
            fclose($arquivo);
            return $string;
        }
    }
}