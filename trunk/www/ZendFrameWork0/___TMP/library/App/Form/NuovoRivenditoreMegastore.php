<?php

class App_Form_NuovoRivenditoreMegastore extends Zend_Form  
{ 
    public function __construct($options = null) 
    { 
    	
    	// recupera dalla tabella "menu" tutti i menu disponibili da associare al link di una nuova pagina e usali per popolare la relativa select box   	       
        parent::__construct($options);
        $this->setName('Nuova Scheda');
           
        $nome = new Zend_Form_Element_Text('nome');
        $nome->setLabel('Nome:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => 'Il campo è obbligatorio')));
              
     	$indirizzo = new Zend_Form_Element_Text('indirizzo');
        $indirizzo->setLabel('Indirizzo:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => 'Il campo è obbligatorio')));		 		        
                    
        $cap = new Zend_Form_Element_Text('cap');
        $cap->setLabel('Cap:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => 'Il campo è obbligatorio')));
        
        $localita = new Zend_Form_Element_Text('localita');
        $localita->setLabel('Località:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => 'Il campo è obbligatorio')));            	    
              
        $provincia = new Zend_Form_Element_Text('provincia');
        $provincia->setLabel('Provincia:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => 'Il campo è obbligatorio')));
        
        $regione = new Zend_Form_Element_Text('regione');
        $regione->setLabel('Regione:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => 'Il campo è obbligatorio')));
        
        $telefono = new Zend_Form_Element_Text('telefono');
        $telefono->setLabel('Telefono:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => 'Il campo è obbligatorio')));
        
        $fax = new Zend_Form_Element_Text('fax');
        $fax->setLabel('Fax:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => 'Il campo è obbligatorio')));
        
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => 'Il campo è obbligatorio')));
        
        $sito = new Zend_Form_Element_Text('sito');
        $sito->setLabel('Sito:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => 'Il campo è obbligatorio')));
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Inserisci');
        

        $this->addElements(array($nome, $indirizzo, $cap, $localita, $provincia, $regione, $telefono, $fax, $email, $sito, $submit));
       
        $this->clearDecorators();
        $this->addDecorator('FormElements')
         ->addDecorator('HtmlTag', array('tag' => '<ul>'))
         ->addDecorator('Form');
        
        $this->addDecorator('FormElements')
         	 ->addDecorator('HtmlTag', array('tag' => 'table', 'style' => 'width:700px'))
         	 ->addDecorator('Form');
                 	 
        $this->setElementDecorators(array(
            'ViewHelper',
		    'Errors',
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'width' => '590', 'class' => 'element')),
		    array('Label', array('tag' => 'td')),
		    array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
        ));

        // buttons do not need labels
        $submit->setDecorators(array(
            'ViewHelper',
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'align'=>'center', 'class' => 'element')),
		    array(array('label' => 'HtmlTag'), array('tag' => 'td', 'placement' => 'prepend')),
		    array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
        ));
    } 
}
