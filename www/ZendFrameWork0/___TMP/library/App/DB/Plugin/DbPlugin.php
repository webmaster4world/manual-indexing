<?php
class App_DB_Plugin_DbPlugin extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $frontController = Zend_Controller_Front::getInstance();
        if ($frontController->getParam('db_error') || $frontController->getParam('db_syntax_error')) {
	        $this->getRequest()->setControllerName('errore');
	        $this->getRequest()->setActionName('errore-db');
        }
    }
}