<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->pageone = @$_SESSION['PageOne_TXT'];
        if ($this->pageone=='') {
		   $this->pageone = 'Hello Misystems!';	
		}
    }

    public function indexAction()
    {
        // action body
        $this->view->Page0 =  "<p>".$this->pageone."</p>";
    }


}

