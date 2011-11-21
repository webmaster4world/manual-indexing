<?php

class App_Form_NuovoCentroAssistenzaForm extends Zend_Form
{ 
    public function __construct(/*$lingua_id=1, $id_eliminare=0 ,*/$options = null) 
    { 
    	
    	parent::__construct($options);
        $this->setName('Nuovo Centro Assistenza');
        $this->setAttrib('enctype', 'multipart/form-data');
        
        
        
        $regione_array=(array('Abruzzo'=>'Abruzzo', 'Basilicata'=>'Basilicata', 'Calabria'=>'Calabria', 'Campania'=>'Campania', 'Emilia-Romagna'=>'Emilia-Romagna', 'Friuli-Venezia Giulia'=>'Friuli-Venezia Giulia', 'Lazio'=>'Lazio', 'Liguria'=>'Liguria', 'Lombardia'=>'Lombardia', 'Marche'=>'Marche', 'Molise'=>'Molise', 'Piemonte'=>'Piemonte', 'Puglia'=>'Puglia', 'Sardegna'=>'Sardegna', 'Sicilia'=>'Sicilia', 'Toscana'=>'Toscana', 'Trentino-Alto Adige'=>'Trentino-Alto Adige', 'Umbria'=>'Umbria', 'Valle d\'Aosta'=>'Valle d\'Aosta', 'Veneto'=>'Veneto'));
	
	$decors=(array(
            'ViewHelper',
		    'Errors',
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
		    array('Label', array('tag' => 'td')),
		    array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
        ));         
        
        
        $ragione_sociale = new Zend_Form_Element_Text('ragione_sociale');
        $ragione_sociale->setLabel('Ragione Sociale:')
              ->setDecorators($decors)
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));

        $indirizzo = new Zend_Form_Element_Text('indirizzo');
        $indirizzo->setLabel('Indirizzo:')
              ->setDecorators($decors)
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));

        $cap = new Zend_Form_Element_Text('cap');
        $cap->setLabel('Cap:')
              ->setAttrib("maxlength",5)
              ->setDecorators($decors)
              ->setRequired(true)->addFilter('StripTags')
              ->addValidator(new Zend_Validate_Regex("/^[0-9]{5}$/"), true, array('messages' => array(Zend_Validate_Regex::NOT_MATCH => 'Il cap è solo numerico', 'stringEmpty' => 'Il campo è obbligatorio')))
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => 'Il campo è obbligatorio')));

        $regione = new Zend_Form_Element_Select('regione');
        $regione->setLabel("Regione:")
        			->setDecorators($decors)
        			->addMultiOptions($regione_array)
                                ->setRegisterInArrayValidator(false)
        			->setRequired(true);

        $provincia = new Zend_Form_Element_Text('provincia');
        $provincia->setLabel('Provincia:')
              ->setDecorators($decors)
              ->setAttrib("maxlength",2)
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));


        $citta = new Zend_Form_Element_Text('citta');
        $citta->setLabel('Città:')
               ->setDecorators($decors)
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));

              
         
        $telefono = new Zend_Form_Element_Text('telefono');
        $telefono->setDecorators($decors)
        	->setLabel('Telephone:')
              	->setRequired(true)->addFilter('StripTags')
              	->addFilter('StringTrim')
              	->setAttrib('size', '30')
              	->addValidator(new Zend_Validate_Regex("/^[0-9]{7,30}$/"), true, array('messages' => array(Zend_Validate_Regex::NOT_MATCH => 'Il cap è solo numerico', 'stringEmpty' => 'Il campo è obbligatorio')))
              	->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => "Il campo e' obbligatorio")));

         $email = new Zend_Form_Element_Text('email');
         $email->setDecorators($decors)
        	->setLabel('Email:')
              	->setRequired(true)->addFilter('StripTags')
              	->addFilter('StringTrim')
              	->setAttrib('size', '30')
                ->addValidator("EmailAddress", true, array('messages' => array(Zend_Validate_EmailAddress::INVALID => 'Il campo deve contenere un indirizzo valido')))
              	->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => "Il campo e' obbligatorio")));


        $codice = new Zend_Form_Element_Text('codice');
        $codice->setLabel('Codice accesso area riservata:')
              ->setDecorators($decors)
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');
             // ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));

      	
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Inserisci');
        
              
        
	$this->addElements(array($ragione_sociale, $indirizzo, $cap,  $regione, $provincia, $citta, $telefono, $email, $codice, $submit));
       
        $this->clearDecorators();        
        $this->addDecorator('FormElements')
         	 ->addDecorator('HtmlTag', array('tag' => 'table', 'style' => 'width:700px', 'align'=>'center'))
         	 ->addDecorator('Form');   
         
        
        $this->getElement('submit')
        	->setDecorators(array(
            'ViewHelper',
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'align'=>'right', 'class' => 'element')),
		    array(array('label' => 'HtmlTag'), array('tag' => 'td', 'placement' => 'prepend')),
		    array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
        ));
    } 
}
