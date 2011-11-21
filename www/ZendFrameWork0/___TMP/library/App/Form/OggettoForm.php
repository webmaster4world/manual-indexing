<?php

class App_Form_OggettoForm extends Zend_Form  
{ 
    public function __construct($flag=false,$options = null) 
    { 		        
        $menu_table = new moduloProdottiTestiFamiglie();
      
        $menu_rows = $menu_table->fetchAll("lingua_id=1");
        $menu_rows = $menu_rows->toArray(); 
        $menu_rows_array = array();
        //$menu_rows_array[0] = '/';
        
    	foreach ($menu_rows as  $info_row) {        	
        	$menu_rows_array[$info_row['famiglia_id']] = $info_row['nome'];
        }
        
        parent::__construct($options);
        $this->setName('Nuovo Oggetto');
        $this->setAttrib('enctype', 'multipart/form-data');
           
        $nome = new Zend_Form_Element_Text('nome');
        $nome->setLabel('Nome oggetto:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
    	
        $titolo = new Zend_Form_Element_Text('titolo');
        $titolo->setLabel('titolo:')
               ->setRequired(true)->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
                        
     	$corpo = new Zend_Form_Element_Textarea('testo', array('class' => 'fck'));
       	$corpo->setLabel('testo:')
              ->setRequired(false)
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));		 		        
                    
        $menu = new Zend_Form_Element_Select('famiglia_id');
        $menu->setLabel('Seleziona famiglia d\'appartenenza:')
        	 ->addMultiOptions($menu_rows_array)
             ->setRequired(true)->addFilter('StripTags')
             ->addFilter('StringTrim')
             ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
	    
        $immagine = new Zend_Form_Element_File('immagine','immagine');
        $immagine->setLabel('Inserisci immagine:')
             	 ->setRequired(false)             	       	 
             	 ->addValidator('Extension', false, 'jpg,png,gif');           

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Inserisci');
        
        if(!$flag)
        	$this->addElements(array($nome, $titolo, $corpo, $menu, $immagine,  $submit));
        else         
            $this->addElements(array($titolo, $corpo, $submit));
                                    
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
