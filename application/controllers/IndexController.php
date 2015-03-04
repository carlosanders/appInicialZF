<?php

/**
 * Description of IndexController
 *
 * @author carlosanders
 */
class IndexController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function indexAction()
    {
        // echo $this->getRequest("user");//pega a requisao via POST ou GET
        $parametros = array();
        $parametros['metodo'] = "Mêtodo inicial da aplicação!";

        
        $o_view = new View('index_index.phtml');
        $o_view->setParams($parametros);
        // Imprimindo código HTML
        $o_view->showContents();
    }

}
