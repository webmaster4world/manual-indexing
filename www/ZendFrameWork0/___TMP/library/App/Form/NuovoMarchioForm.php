<?php

class App_Form_NuovoMarchioForm extends Zend_Form {
	public function __construct($options = null) 
    {         
        parent::__construct($options);
        $this->setName('GestioneMarchio');
        
        $id = new Zend_Form_Element_Hidden('id');
    
        $nome = new Zend_Form_Element_Text('nome');
        $nome->setLabel('Nome:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
              
        
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        
        
        $this->addElements(array($nome, $submit));
        
                                    
        $this->clearDecorators();
        $this->addDecorator('FormElements')
         ->addDecorator('HtmlTag', array('tag' => '<ul>'))
         ->addDecorator('Form')
         ->addDecorator('File')
         ->setAttrib('enctype', 'multipart/form-data');


        // buttons do not need labels
        $submit->setDecorators(array(
            array('ViewHelper'),
            array('Description'),
            array('HtmlTag', array('tag' => 'li', 'class'=>'submit-group')),
        ));
    } 
}