<?php

class App_Form_NuovaSchedaForm extends Zend_Form  
{ 
    public function __construct($flag = false, $options = null) 
    { 
    	
    	// recupera dalla tabella "menu" tutti i menu disponibili da associare al link di una nuova pagina e usali per popolare la relativa select box
    	
    	$menu_table = new moduloProdottiOggetti();
        $menu_rows = $menu_table->fetchAll();
        $menu_rows = $menu_rows->toArray(); // converti l'oggetto rowset in un array in modo da passarlo come parametro all'oggetto selectbox
        $menu_rows_array = array();      
        
        foreach ($menu_rows as  $menu_row_value) {        	
        	$menu_rows_array[$menu_row_value['id']] = $menu_row_value['nome'];
        }            	
                
        parent::__construct($options);
        $this->setName('Nuova Scheda');
           
        $titolo = new Zend_Form_Element_Text('titolo');
        $titolo->setLabel('Titolo:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
              
     	$corpo = new Zend_Form_Element_Textarea('corpo', array('class' => 'fck'));
       	$corpo->setLabel('Corpo:')
              ->setRequired(true)
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));		 		        
                    
        $menu = new Zend_Form_Element_Select('oggetto_id');
        $menu->setLabel('Seleziona oggetto a cui associare la scheda:')
        	 ->addMultiOptions($menu_rows_array)
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
        
        $link_pagina = new Zend_Form_Element_Text('link');
        $link_pagina->setLabel('Link pagina:')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
          	  ->addValidator('Regex', false, array('/^[a-zA-Z\-\_]*$/'))
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));              	    
              
        $pubblicata = new Zend_Form_Element_Radio('pubblicata');     
        $pubblicata->setLabel('Pagina visibile?')
        			->addMultiOptions(array('Si'=>'Si','No'=>'No'))
        			->setRequired(false)->addFilter('StripTags')
              		->addFilter('StringTrim')              		
              		->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Inserisci');
        
        if(!$flag)
        	$this->addElements(array($titolo, $corpo, $link_pagina, $menu, $pubblicata, $submit));
        else 
        	$this->addElements(array($titolo, $corpo, $link_pagina, $pubblicata, $submit));        
                                    
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
