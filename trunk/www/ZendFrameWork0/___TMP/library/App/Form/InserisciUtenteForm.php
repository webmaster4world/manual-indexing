<?php

class App_Form_InserisciUtenteForm extends Zend_Form {
	public function __construct($options = null) 
    {         
        parent::__construct($options);
        $this->setName('GestioneUtente');
        
        $id = new Zend_Form_Element_Hidden('id');
    
        $nomeutente = new Zend_Form_Element_Text('login');
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

        $ruolo = new Zend_Form_Element_Select('ruolo');
        $ruolo->addMultiOptions(array('superadmin'=>'Amministratore',
        							  'home'=>'Home electronics',
        							  'carsystem'=>'Carsystem',        							  
        							  'electronics'=>'Elettronica',
        							  'industry'=>'Industria',
        							  'energy'=>'Energy',
                                                                  'assistenza'=>'Assistenza'))
        		->setLabel('Ruolo:')
        		->addFilter('StripTags')
                ->addFilter('StringTrim')
        		->setRequired(true);
              
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        
        $this->addElements(array($id, $nomeutente, $password, $ruolo, $submit));
                                    
        $this->clearDecorators();
        $this->addDecorator('FormElements')
         ->addDecorator('HtmlTag', array('tag' => '<ul>'))
         ->addDecorator('Form');
        
        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors',array('escape' => false)),
            array('Description'),
            array('Label', array('separator'=>' ')),
            array('HtmlTag', array('tag' => 'li', 'class'=>'element-group')),
        ));

        // buttons do not need labels
        $submit->setDecorators(array(
            array('ViewHelper'),
            array('Description'),
            array('HtmlTag', array('tag' => 'li', 'class'=>'submit-group')),
        ));
    } 
}