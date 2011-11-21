<?php

class App_Form_InfoForm extends Zend_Form {
	public function __construct($options = null) 
    {         
        parent::__construct($options);
        $this->setName('GestioneOpzioni');
        
        $id = new Zend_Form_Element_Hidden('id');
    
        $societa = new Zend_Form_Element_Text('societa');
        $societa->setLabel('Nome Società:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              //->addValidator('Alpha', true, array('messages' => array('notAlpha' => '&uarr; Il campo deve contenere solo lettere &uarr;', 'stringEmpty' => '&uarr; Il campo è obbligatorio &uarr;')))
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
        
        $header = new Zend_Form_Element_Textarea('header');
        $header->setLabel('Header:')
                  ->setRequired(false)
                  ->addFilter('StripTags')
                  ->addFilter('StringTrim')
                  ->setAttrib('rows','5')
                  ->setAttrib('cols','45')
                  ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
		
        $footer = new Zend_Form_Element_Textarea('footer');
        $footer->setLabel('Footer:')
                  ->setRequired(true)
                  ->addFilter('StripTags')
                  ->addFilter('StringTrim')
                  ->setAttrib('rows','5')
                  ->setAttrib('cols','45')
                  ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
                  
                  
        $template = new Zend_Form_Element_Select('template');
        $template->addMultiOptions(array('layout'=>'Default',
        									'homelectronics'=>'Home electronics',
        									'carsystem'=>'Car system',
        									'energy'=>'Energy',
        									'electronics'=>'Electronics',
        									'industry'=>'Industry' ))
        		->setLabel('Template:')
        		->addFilter('StripTags')
                ->addFilter('StringTrim')
        		->setRequired(true);
		
        $seo_descrizione = new Zend_Form_Element_Text('seo_descrizione');
        $seo_descrizione->setLabel('Descrizione sito/società:')
                  ->setRequired(true)
                  ->addFilter('StripTags')
                  ->addFilter('StringTrim')
                  ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));

        $seo_keyword = new Zend_Form_Element_Text('seo_keyword');
        $seo_keyword->setLabel('Keyword sito/società (separate da virgola):')
                  ->setRequired(true)
                  ->addFilter('StripTags')
                  ->addFilter('StringTrim')
                  ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
                  
        		
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        
        $this->addElements(array($id, $societa, $template, $seo_descrizione, $seo_keyword, $header, $footer, $submit));
                                    
        $this->clearDecorators();		
		
        $this->addDecorator('FormElements')
         	 ->addDecorator('HtmlTag', array('tag' => 'table', 'style' => 'width:500px'))
         	 ->addDecorator('Form');
                 	 
        $this->setElementDecorators(array(
            'ViewHelper',
		    'Errors',
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'width' => '250', 'class' => 'element')),
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