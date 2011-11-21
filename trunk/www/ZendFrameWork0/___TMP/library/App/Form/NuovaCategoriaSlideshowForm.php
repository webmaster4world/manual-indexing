<?php

class App_Form_NuovaCategoriaSlideshowForm extends Zend_Form {
	public function __construct($options = null) 
    {         
        parent::__construct($options);
        $this->setName('GestioneCategorieSlideshow');
        
        //$id = new Zend_Form_Element_Hidden('id');
    
     	$info = new Info();
        $info = $info->fetchAll();
        $info = $info->toArray();
        $info_array = array(0=>NULL);                     	
        
    	foreach ($info as  $info_row) {        	
        	$info_array[$info_row['id']] = $info_row['societa'];
        }
        
        $categoria = new Zend_Form_Element_Text('nome');
        $categoria->setLabel('Nome Categoria:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')              
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));

        $info = new Zend_Form_Element_Select('info_id');
        $info->setLabel('Seleziona società:')
        	 ->addMultiOptions($info_array)
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));                           
              
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        
        $this->addElements(array($categoria, $info, $submit));
                                    
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