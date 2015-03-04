<?php

/*
 * TODO:: configuracao das bibliotecas do Zend @category Configuracao do Zend na applicacao para usar as bibliotecas disponiveis @copyright DPMM 71 @license SG Anders @version 1.0 @description: o arquivo usa o metodo set_include_path q adiciona caminhos relativos aos comandos requerie e include. Quando um arquivo nao estiver presente em um comando o php vrf tb nos paths que foram definidos no set_include_path
 */
/* defino a localidade para trabalhar com a hora certa do Brasil */
setlocale(LC_ALL, 'BRA');
date_default_timezone_set('America/Sao_Paulo');
// segunda tarefa: adicionar os diretorios das classes PHP para que o ZendLoader
// possa acha-los
$path = dirname(realpath($_SERVER['SCRIPT_FILENAME']));
$path = dirname($path);
// echo realpath(dirname(__FILE__));
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
// desabilita por existir a biblioteca no php.ini do wampserver
// set_include_path(get_include_path() . PATH_SEPARATOR . $path . '/libs/ZendLibrary_1.12.3' . PATH_SEPARATOR);
set_include_path(get_include_path() . PATH_SEPARATOR . $path . '/application/app' . PATH_SEPARATOR);
set_include_path(get_include_path() . PATH_SEPARATOR . $path . '/application/controllers' . PATH_SEPARATOR);
set_include_path(get_include_path() . PATH_SEPARATOR . $path . '/application/models' . PATH_SEPARATOR);
set_include_path(get_include_path() . PATH_SEPARATOR . $path . '/application/dao' . PATH_SEPARATOR);
set_include_path(get_include_path() . PATH_SEPARATOR . $path . '/application/helpers' . PATH_SEPARATOR);
set_include_path(get_include_path() . PATH_SEPARATOR . $path . '/application/uteis' . PATH_SEPARATOR);
set_include_path(get_include_path() . PATH_SEPARATOR . $path . '/application/beans' . PATH_SEPARATOR);
// incluindo no path as libs de terceiros
set_include_path(get_include_path() . PATH_SEPARATOR . $path . '/library/PHPMailer_5.2.4' . PATH_SEPARATOR);

/*
 * SG Anders 04/07/12 - desativado pq as bibliotecas estao sendo carregadas no php.ini 
 * direto, assim e possive usar uma so biblioteca para cada projeto do php Caso deseja 
 * configurar para cada projeto um biblioteca do zend tem que add na pasta library do projeto 
 * individuar e habilitar o set_include_path abaixo

  set_include_path(get_include_path() . PATH_SEPARATOR . $path . '/library' . PATH_SEPARATOR . get_include_path());
 */

// inclui o autoloader da biblioteca Zend
require_once ('Zend/Loader/Autoloader.php');
// carrega a classe do zend resp. fazer o autoload automaticamente a medida que for necessario
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

// definido constantes disponiveis na aplicacao
define('APPLICATION_PATH', $path);

// Ler arquivo de configuracao da aplicacao
$config = new Zend_Config_Ini('../application/configs/application.ini');
// salva no registro para ser usado em qq tempo da requisicao php
Zend_Registry::set("config", $config);
// lendo as informacoes do arquivo INI para servir de base para conexao com o banco dados
$dbConfig = new Zend_Config_Ini('../application/configs/application.ini',
        'staging');
// usado o factory do Zend_Db para instanciar um adaptador, de acordo com as
// configuracoes do arquivo INI
$db = Zend_Db::factory($dbConfig->database->adapter,
                $dbConfig->database->params->toArray());
//die(var_dump($dbConfig->database->adapter, $dbConfig->database->params->toArray()));
try {
    // setando do adaptar para a conexao com o banco de dados
    Zend_Db_Table::setDefaultAdapter($db);
//definindo o locale para toda a aplicação
    Zend_Locale::setDefault('pt_BR');
    //Codigo para traduzir as mensagens do ZF1
    $translate = new Zend_Translate('Array',
            APPLICATION_PATH . '/library/resources/languages/pt_BR/Zend_Validate.php',
            'pt_BR');
    Zend_Validate_Abstract::setDefaultTranslator($translate);
} catch (Exception $e) {
    //exibe o erro na tela
    die($e->getMessage());
    //throw new Exception($e->getMessage());
}
// setando a exibicao de erro na aplicacao
error_reporting(E_ALL & ~ E_STRICT);
ini_set("display_errors", $config->application->phpSettings->display_errors);
ini_set("display_startup_errors",
        $config->application->phpSettings->display_startup_errors);
ini_set("log_errors", $config->application->phpSettings->log_errors);
ini_set("log_errors_max_len", 1024);
ini_set("error_log", $path . "/logs/php_error_log.txt");
