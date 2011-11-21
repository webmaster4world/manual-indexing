<?php

class App_Form_NuovoDownloadMegastoreForm extends Zend_Form
{ 
    public function __construct($user = 7, $options = null) 
    {     	
        parent::__construct($options);
        $this->setName('Nuovo file per caricare');
        $this->setEnctype('multipart/form-data'); 
        

        $nome = new Zend_Form_Element_Text('titolo');
        $nome->setLabel('Nome file:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
		
        $contesto = new Zend_Form_Element_Select('contesto');
        $contesto->setLabel('Tipologia:')
                ->addMultiOptions(array(0=>"Esclusivo",1=>"Condiviso"))
                ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
        
        $data = new Zend_Form_Element_Text('data',array("id"=>'datepicker'));
        $data->setLabel('Data:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));

 	
        $file = new Zend_Form_Element_File('file');
        $file->setLabel('Inserisci file:')
        		 ->setRequired(false);
        		// ->addValidator('Extension', false, 'zip,pdf,xls,doc');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Inserisci');
        
       
        $this->addElements(array($nome,$contesto, $data, $file, $submit));
                
                                    
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
        
        $file->setDecorators(array( 
   			array('File'), 
    		array(array('data' => 'HtmlTag'), array('tag' => 'td', 'width' => '590', 'class' => 'element')),
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
