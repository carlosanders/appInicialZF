<?php

/**
 * @author Carlos Anders 
 * @copyright Copyright (c) 2013, Carlos Anders
 * @package application/controllers
 * @version 1.0 
 * 
 * Camada - Sistema / Aplicação / Controller
 * Diretório Pai - application 
 * 
 * Controller - Main - Responsável pelo o inicio da aplicação
 */
class MainController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function usarValidadorAction()
    {

        //o obj observer ira acumular em um array os erros passados para ele
        //e ao usar o metodo validadeAll(), será apresentado os erros caso existam
        $observer = new ObserverValidator();

        $nronip = "08.8406.80";
        $nronip1 = "57231aaa";
        $observer->add(new ValidateNIP(), Helper::formataNipsSemPontos($nronip));
        $observer->add(new ValidateNIP(), $nronip1);


        $email = "12332";
        //retorno emailAddressInvalidFormat: '12332' não é um endereço de e-mail válido no formato local-part@hostname
        $observer->add(new Zend_Validate_EmailAddress(), $email);

        //retorno stringLengthTooLong: O tamanho de 'worddsdsdsdsdsdsdsdsd' é superior a 12 caracteres
        $observer->add(new Zend_Validate_StringLength(array('min' => 8, 'max' => 12)),
                "worddsdsdsdsdsdsdsdsd");

        //retorno stringLengthTooShort: O tamanho de 'Test' é inferior a 5 caracteres
        $observer->add(new Zend_Validate_StringLength(array('min' => 5)), "Test");

        //reorno stringLengthTooLong: O tamanho de 'Testing' é superior a 6 caracteres
        $observer->add(new Zend_Validate_StringLength(array('max' => 6)),
                "Testing");

        //retorno notAlnum: 'Abcd12^..' contém caracteres que não são alfabéticos e nem dígitos
        $observer->add(new Zend_Validate_Alnum(), "Abcd12^..");

        //retorno notAlnum: 'Abcd and 12' contém caracteres que não são alfabéticos e nem dígitos
        $observer->add(new Zend_Validate_Alnum(array('allowWhiteSpace' => false)),
                'Abcd and 12');

        //retorno - notAlpha: 'Abcd123' contém caracteres não alfabéticos
        $observer->add(new Zend_Validate_Alpha(), "Abcd123");

        //retrono notBetweenStrict: '10' não está exatamente entre '0' e '10'
        $valid = new Zend_Validate_Between(
                array(
            'min' => 0,
            'max' => 10,
            'inclusive' => false
                )
        );
        $value = 10;
        $observer->add($valid, $value);

        //retorno dateFalseFormat: '2000/10/10' não se encaixa no formato de data 'dd/MM/yyyy'
        $observer->add(new Zend_Validate_Date(array('locale' => 'pt_BR', 'format' => 'dd/MM/yyyy')),
                "2000/10/10");

        //retorno notFloat: '1234.5' não parece ser um float
        $observer->add(new Zend_Validate_Float(array('locale' => 'pt_BR')),
                '1234.5');

        //retorno notHex: '123ABCG' não contém somente caracteres hexadecimais
        $observer->add(new Zend_Validate_Hex(), "123ABCG");

        //retorno notSame: Os dois tokens fornecidos não combinam
        $observer->add(new Zend_Validate_Identical('origin'), "ORIGIN");

        //retorno notSame: Os dois tokens fornecidos não combinam
        $observer->add(new Zend_Validate_Identical(array('token' => 123, 'strict' => TRUE)),
                '123');

        //sera feito uma busca recursiva nos arrays
        $validator = new Zend_Validate_InArray(
                array(
            'haystack' => array(
                'firstDimension' => array('key' => 'value',
                    'otherkey' => 'othervalue'),
                'secondDimension' => array('some' => 'real',
                    'different' => 'key')),
            'recursive' => true
                )
        );
        //retorno notInArray: 'aqui' não faz parte dos valores esperados
        $observer->add($validator, 'aqui');

        //retorno notInt: '1234.5' não parece ser um número inteiro
        $observer->add(new Zend_Validate_Int(), 1234.5);

        //retorno notIpAddress: '2001:0DB8:0000:0000:130F:0000:0000:140B--' não parece ser um endereço de IP válido
        $observer->add(new Zend_Validate_Ip(),
                "2001:0DB8:0000:0000:130F:0000:0000:140B--"); //vrf IPv4 e IPv6
        //notIpAddress: '192.168.0.1111' não parece ser um endereço de IP válido
        $observer->add(new Zend_Validate_Ip(array('allowipv6' => false)),
                "192.168.0.1111"); //só IPv4
        //retorno postcodeNoMatch: '20.725-001' não parece ser um código postal
        $observer->add(new Zend_Validate_PostCode('pt_BR'), "20.725-001");

        //retorno regexNotMatch: 'Pest' não corresponde ao padrão '/^Test/'
        $observer->add(new Zend_Validate_Regex(array('pattern' => '/^Test/')),
                "Pest");

        if ($observer->validadeAll()) {
            // email appears to be valid
            var_dump($email);
        } else {
            //var_dump($validator->getMessages());
            // email is invalid; print the reasons
            echo "Erros encontrados: <br />";
            foreach ($observer->getErros() as $message) {
                echo("Falha na validação - $message<br />");
            }
        }

        return die();
    }

    public function filtros_ValidadoresAction($filtro = "", $validador = "")
    {
        //Converte caracters "especiais" em entidades HTML
        $htmlEntities = new Zend_Filter_HtmlEntities();
        //exemplo 1
        var_dump($htmlEntities->filter('&'), // string '&amp;'
                $htmlEntities->filter('"') // string '&quot;'
        );

        // Se preferir em alguns caso pode-se utilizar o metodo estático da classe Zend_Filter.
        var_dump(Zend_Filter::filterStatic('&', //1° arg: o valor da entrada de dados
                        'HtmlEntities' //2° arg.: string que corresponde ao nome da classe de filtro
                        //em relacao ao Zend_Filter
                )
        );
        //$filter = new Zend_Filter_Int;
        var_dump(Zend_Filter::filterStatic('0234', //1° arg: o valor da entrada de dados
                        'Int' //2° arg.: string que corresponde ao nome da classe de filtro
                        //em relacao ao Zend_Filter
                ), 0234
        );
        
        //Pode-se também passar um  array de argumentos do construtor, se forem necessários para a classe d
        //e filtro em questao
        var_dump(Zend_Filter::filterStatic('"', 'HtmlEntities',
                        array('quotestyle' => ENT_QUOTES)));

        //Filtro que retorna só digitos numéricos
        $filter = new Zend_Filter_Digits();
        //exemplo 2
        var_dump($filter->filter('Valor com Números 123')); // 123

        var_dump(Zend_Filter::filterStatic('Valor com números 123', //1° arg: o valor da entrada de dados
                        'Digits' //2° arg.: string que corresponde ao nome da classe de filtro
                        //em relacao ao Zend_Filter
                )
        );

        $filter = new Zend_Filter_Alnum();
        $return = $filter->filter('This is (my) content: 123');
        // returns 'Thisismycontent123'
        var_dump("original: 'This is (my) content: 123'", $return);
        //retornando com espaços
        $filter = new Zend_Filter_Alnum(array('allowwhitespace' => true));
        $return = $filter->filter('This is (my) content: 123');
        // returns 'This is my content 123'
        var_dump($return);


        $filter = new Zend_Filter_BaseName();
        var_dump('Diretorio = /vol/tmp/filename',
                $filter->filter('/vol/tmp/filename'));
        var_dump('Diretorio = /vol/tmp/filename.txt',
                $filter->filter('/vol/tmp/filename.txt'));

        $filter = new Zend_Filter_Boolean();
        $value = '';
        // returns false
        $result = $filter->filter($value);
        var_dump('', $result);

        $filter = new Zend_Filter_Callback('strrev');
        // returns "!olleH"
        print $filter->filter('Hello!');


        /**
         * Ao usar 2 filtros após tem que ter em mente que muitas vezes não eh possivel obter o resultado original
         * usando o filtro oposto. ex:

          $original = "my_original_content";
          // Attach a filter
          $filter = new Zend_Filter_Word_UnderscoreToCamelCase();
          $filtered = $filter->filter($original);
          var_dump($original, //valor original
          $filtered// valor convertido
          );
          // Use it's opposite
          $filter2 = new Zend_Filter_Word_CamelCaseToUnderscore();
          $filtered = $filter2->filter($filtered);
          var_dump($original, //valor original
          $filtered //valor aplicado o 2° filtro
          );
         */
        //Exemplo de Filtros em Cascata
        $string = "&palavra&";
        $cascata = new Zend_Filter();
        //repare que sera aplicado o filtro em ordem que esta add as classes de filtros
        $cascata->addFilter(new Zend_Filter_Alpha())//retorna dados alfabéticos de determinado localidade
                ->addFilter(new Zend_Filter_StringToUpper()); //retorna texto convertido para maiúscula

        $stringFiltrada = $cascata->filter($string);

        var_dump($string, // valor original
                $stringFiltrada //valor retornado pelo filtro em cascata
        );

        //Converte caracters "especiais" em entidades HTML
        $cep = new FiltraCep();
        //exemplo 1
        var_dump($cep->filter('20725000') // string '&amp;'
        );


        return die();
    }

    public function textoAction()
    {
        $table = new Zend_Text_Table(array(
            'columnWidths' => array(
                10,
                20
            )
        ));

        // Either simple
        $table->appendRow(array(
            'Zend',
            'Framework'
        ));

        // Or verbose
        $row = new Zend_Text_Table_Row();

        $row->appendColumn(new Zend_Text_Table_Column('Zend'));
        $row->appendColumn(new Zend_Text_Table_Column('Framework'));

        $table->appendRow($row);

        //exemplo 2		
        $writer = new Log('arquivo.txt', // nome do arquivo do log 
                '../public_html/arquivos/', //path onde ficará o aquivo, se for 'NULL' será setado o padrao ../logs/ da aplicacao
                "w" //Abre o arquivo para leitura e escrita; coloca o ponteiro do arquivo no final. Se o arquivo não existir, tenta criá-lo.
        );
        //insere o texto no arquivo, ressalto que se for usar o metodo mais uma vez ele ira apagar e deixar somente o último registro 
        $writer->insertIntoFile("Teste de Texto no arquivo 1!");
        $writer->insertIntoFile("Teste de Texto no arquivo 2!");


        $writer1 = new Log('arquivo.log', // nome do arquivo do log
                NULL, //path onde ficará o aquivo, se for 'NULL' será setado o padrao ../logs/ da aplicacao
                null //se null assumo o modo padrao da classe
        );
        //insere o texto no arquivo, ressalto que se for usar o metodo mais uma vez ele ira apagar e deixar somente o último registro
        $writer1->insertIntoLogFile("Teste de LOG 1");
        $writer1->insertIntoLogFile("Teste de LOG 2");
        $writer1->insertIntoLogFile("Teste de LOG 3");


        return die(var_dump($writer1->exibirConteudo()));
    }

    public function indexAction()
    {
        $parametros = array();
        $parametros['ativo'] = "inicio da aplicação!";


        // var_dump(Application::getAcao(), Application::getControlador());

        $o_view = new View('meio.phtml');
        // echo $this->getRequest("user");//pega a requisao via POST ou GET

        $o_view->setParams($parametros);
        // Imprimindo código HTML
        $o_view->showContents();
    }
    
    public function semTopoRodapeAction()
    {
        //sleep(2);
        $parametros = array();
        $parametros['ativo'] = "Exemplo de aplicação sem o topo e rodape!";
        $parametros['estadosFixo'] = $_POST['estadosFixo'];
        $parametros['cbCategoria'] = $_POST['cbCategoria'];
        $parametros['cc1Fixo'] = $_POST['cc1Fixo'];
        $parametros['cbEstados'] = $_POST['cbEstados'];
        $parametros['status'] = $_POST['status'];
        $parametros['registroDG'] = $_POST['rows'];
        
        
        // $o_view->setViewHeader($viewHeader);
        //var_dump($_POST);
        // Imprimindo código HTML
        
        //$obj = $this->enviarRequisicaoAction();
        $o_view = new View('semTopoRodape1.phtml', $parametros, false);
        $o_view->showContents();
    }

    public function semTopoRodape2Action()
    {
        //sleep(2);
        $parametros = array();
        $parametros['ativo'] = "Exemplo de aplicação sem o topo e rodape!";
        // $o_view->setViewHeader($viewHeader);
        //var_dump($_POST);
        // Imprimindo código HTML
        
        //$obj = $this->enviarRequisicaoAction();
        $o_view = new View('semTopoRodape2.phtml', $parametros, false);
        $o_view->showContents();
    }
    
    public function enviarRequisicaoAction()
    {
        $parametros = array();
        $parametros['ativo'] = "Exemplo de aplicação sem o topo e rodape!";
        //$o_view = new View('semTopoRodape.phtml', $parametros, false);
        // $o_view->setViewHeader($viewHeader);
        // Imprimindo código HTML
        //$o_view->showContents();
        
        $url = "main/semTopoRodapeLoad";
        

        $parametros['success'] = true;
        $parametros['lista'] = $url;
        //var_dump(Helper::utf8ize("\xB1\x31"));
        //var_dump(json_encode($parametros));

        return die(Helper::safe_json_encode($parametros));

    }
    
    public function semTopoRodapeLoadAction()
    {
        $parametros = array();
        $parametros['ativo'] = "Exemplo de aplicação sem o topo e rodape! Load";
        $o_view = new View('semTopoRodape.phtml', $parametros, false);
        // $o_view->setViewHeader($viewHeader);
        // Imprimindo código HTML
        $o_view->showContents();
    }

    public function inicio2Action()
    {
        $parametros = array();
        $parametros['ativo'] = "inicio2 da aplicação!";

        $o_view = new View('meio.phtml');
        $o_view->setParams($parametros);
        // Imprimindo código HTML
        $o_view->showContents();
    }

    public function listarMilitaresAction()
    {
        $parametros = array();
        $parametros['ativo'] = "inicio2 da aplicação!";
        $parametros['titulo'] = "DataGrid Militares";
        $parametros['codOM'] = "0626";
        $parametros['user'] = "286761";
        $parametros['graduacao'] = "";
        $parametros['acesso'] = true;

        $o_view = new View('dgMilitaresOM.phtml');
        $o_view->setParams($parametros);
        // Imprimindo código HTML
        $o_view->showContents();
    }

    public function jsonAction()
    {
        $obj = new stdClass();
        $obj->pais = 25;

        $obj1 = new stdClass();
        $obj1->pais = 26;

        //$lista = [$obj, $obj1];
        $lista = array($obj, $obj1);

        $parametros = array();
        $parametros['ativo'] = "inicio2 da aplicação!''";
        $parametros['sexo'] = "\xB1\x31";
        $parametros['obj'] = $obj;
        $parametros['lista'] = $lista;

        //var_dump(Helper::utf8ize("\xB1\x31"));

        var_dump(json_encode($parametros));

        return die(Helper::safe_json_encode($parametros));
    }

    public function dgExemploAction($item = "", $saida = true)
    {
        $page = !empty($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = !empty($_POST['rows']) ? intval($_POST['rows']) : 20;
        $satus = !empty($_POST['satus']) ? ($_POST['satus']) : $item;
        //$estados = !empty($_POST['estados']) ? ($_POST['estados']) : "Diversos";
        $estados = !empty($_POST['estados']) ? ($_POST['estados']) : "";
        
        $json = new stdClass();
        
        //var_dump(php://input);
        
        try {
            $dao = new DatagridDAO("ZEND", true);
            //var_dump($dao->getDataGrid(true));
            $result = $dao->getDataGrid(true, $satus, $estados);
            
             $json->total = count($result);
             $json->rows = $result;
             
             //return die(var_dump($json));
             return die(Helper::safe_json_encode($json));
            
        } catch (Exception $e) {
            return die(var_dump($e->getMessage()));
        }
    }
}