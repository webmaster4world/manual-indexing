<?php

class App_Form_InserisciResponsabileForm extends Zend_Form {
	public function __construct($options = null) 
    {         
        parent::__construct($options);
        $this->setName('GestioneResponsabile');
        
        $id = new Zend_Form_Element_Hidden('id');
    
        $nome = new Zend_Form_Element_Text('nome');
        $nome->setLabel('Nome:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));              
        
        $cognome = new Zend_Form_Element_Text('cognome');
        $cognome->setLabel('Cognome:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
        
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('E-mail:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));

        $telefono = new Zend_Form_Element_Text('telefono');
        $telefono->setLabel('Telefono:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));      
          
        $cell = new Zend_Form_Element_Text('cellulare');
        $cell->setLabel('Cellulare:')
              ->addFilter('StripTags')
              ->addFilter('StringTrim');

        $note = new Zend_Form_Element_Textarea('note');
        $note->setLabel('Note:')
        		->setAttrib('rows','4')
				->setAttrib('cols','40')
              ->addFilter('StripTags')
              ->addFilter('StringTrim');
		
        $tipologia = new Zend_Form_Element_Select('tipologia');
        $tipologia->addMultiOptions(array('fae'=> 'fae', 'marketing'=>'marketing'))
        			->setLabel('Tipologia:');      
              
        $marchiTBL = new moduloMarchi();
        $fetch = $marchiTBL->fetchAll();
        foreach ($fetch as $value){
        	$list[$value->id_marchi] = $value->nome;      
        }
        $marchi = new Zend_Form_Element_Multiselect('marchi');
        $marchi->setLabel('Marchi:')
        	->setAttrib('size','10')
        	->setDescription('Tenere premuto ctrl per selezionarne più di uno.')
        	->addMultiOptions($list);
              
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        
        $this->addElements(array($nome, $cognome, $email, $telefono, $cell, $note, $tipologia, $marchi, $submit));
        	
                                    
        $this->clearDecorators();
        $this->addDecorator('FormElements')
         ->addDecorator('HtmlTag', array('tag' => '<ul>'))
         ->addDecorator('Form')
         ->setAttrib('enctype', 'multipart/form-data');
        
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