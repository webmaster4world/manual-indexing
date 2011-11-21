<?php

class App_Form_LoginForm extends Zend_Form  
{ 
    public function __construct($options = null) 
    { 
        
        parent::__construct($options);
        $this->setName('Login');
        
    	// recupera dalla tabella "Lingue" tutte le lingue disponibili da associare alla voce di menu
    	
    	$lingue_table = new Lingue();
        $lingue_rows = $lingue_table->fetchAll();
        $lingue_rows = $lingue_rows->toArray(); // converti l'oggetto rowset in un array in modo da passarlo come parametro all'oggetto selectbox
        $lingue_rows_array = array();
        foreach ($lingue_rows as  $lingue_row_value) {        	
        	$lingue_rows_array[$lingue_row_value['id']] = $lingue_row_value['nome'];
        }
    
        $nomeutente = new Zend_Form_Element_Text('nomeutente');
        $nomeutente->setLabel('Nome Utente:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('Alpha', true, array('messages' => array('notAlpha' => '&uarr; Il campo deve contenere solo lettere &uarr;', 'stringEmpty' => '&uarr; Il campo è obbligatorio &uarr;')))
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
        
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password:')
                  ->setRequired(true)
                  ->addFilter('StripTags')
                  ->addFilter('StringTrim')
                  ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
		
        $lingue = new Zend_Form_Element_Select('lingua');
        $lingue	->setLabel('Seleziona lingua:')
        	 	->addMultiOptions($lingue_rows_array)
              	->setRequired(true)->addFilter('StripTags')
              	->addFilter('StringTrim')
              	->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));                  
              
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Login');
        
        $this->addElements(array($nomeutente, $password, $lingue, $submit));

        
                                    
        $this->clearDecorators();
        $this->addDecorator('FormElements')
         	 ->addDecorator('HtmlTag', array('tag' => 'table', 'style' => 'width:350px', 'align'=>'left'))
         	 ->addDecorator('Form');
                 	 
        $this->setElementDecorators(array(
            'ViewHelper',
		    'Errors',
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'width' => '185', 'class' => 'element')),
		    array('Label', array('tag' => 'td')),
		    array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
        ));

        // buttons do not need labels
        $submit->setDecorators(array(
            'ViewHelper',
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'align'=>'right', 'class' => 'element')),
		    array(array('label' => 'HtmlTag'), array('tag' => 'td', 'placement' => 'prepend')),
		    array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
        ));
    } 
}
