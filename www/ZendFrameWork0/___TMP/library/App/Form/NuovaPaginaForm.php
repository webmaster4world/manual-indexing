<?php

class App_Form_NuovaPaginaForm extends Zend_Form  
{ 
    public function __construct($lingua_id=1, $id_eliminare=0 ,$options = null) 
    { 
    	
    	// recupera dalla tabella "menu" tutti i menu disponibili da associare al link di una nuova pagina e usali per popolare la relativa select box
    	
    	$menu_table = new VociMenu();
    	($id_eliminare) ? $and = " AND id <>".$id_eliminare : $and = "";
        $menu_rows = $menu_table->fetchAll('Home != \'Si\' AND Prodotto != \'Si\' AND menu_id = 1 AND lingua_id = '.$lingua_id.$and);
        $menu_rows = $menu_rows->toArray(); // converti l'oggetto rowset in un array in modo da passarlo come parametro all'oggetto selectbox
        $menu_rows_array = array();
        $menu_rows_array[0] = '/';
        
        $info = new Info();
        $info = $info->fetchAll();
        $info = $info->toArray();
        $info_array = array(); 
        
        foreach ($menu_rows as  $menu_row_value) {        	
        	$menu_rows_array[$menu_row_value['id']] = $menu_row_value['nome'];
        }            	
        
    	foreach ($info as  $info_row) {        	
        	$info_array[$info_row['id']] = $info_row['societa'];
        }
        
        $men = new Menu();
        $men = $men->fetchAll();
        $men = $men->toArray();
        $men_array = array(); 
        
        foreach ($men as  $men_row_value) {        	
        	$men_array[$men_row_value['id']] = $men_row_value['nome'];
        }  
        
        parent::__construct($options);
        $this->setName('Nuova Pagina');
           
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
                    
        $link_pagina = new Zend_Form_Element_Text('link_pagina');
        $link_pagina->setLabel('Link pagina:')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
          	  ->addValidator('Regex', false, array('/^[a-zA-Z\-\_]*$/'))
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
              
        $nome_pagina = new Zend_Form_Element_Text('nome_pagina');
        $nome_pagina->setLabel('Nome voce menu:')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));

        $menu = new Zend_Form_Element_Select('menu');
        $menu->setLabel('Seleziona menu parente:')
        	 ->addMultiOptions($menu_rows_array)
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
	    
        $menus = new Zend_Form_Element_Select('secondo_menu');
        $menus->setLabel('Seleziona tipologia menu:')
        	 ->addMultiOptions($men_array)
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));	          
              
        $check = new VociMenu();
        $check = $check->fetchRow("id=".$id_eliminare);   
        if(isset($check->home))
	        if($check->home!="No"){      		                     	       
	        	$menu = new Zend_Form_Element_Hidden('menu');
	        	$menu = $menu->setValue("0");        	
	        }
	        
        $info = new Zend_Form_Element_Select('info');
        $info->setLabel('Seleziona società:')
        	 ->addMultiOptions($info_array)
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));             
              
              
        $pubblicata = new Zend_Form_Element_Radio('pubblicata');     
        $pubblicata->setLabel('Pagina visibile?')
        			->addMultiOptions(array('Si'=>'Si','No'=>'No'))
        			->setRequired(false)->addFilter('StripTags')
              		->addFilter('StringTrim')
              		->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
              		
        $colore = new Zend_Form_Element_Text('colore');
        $colore->setLabel('Colore voce di menu:')
              ->setRequired(false)
              ->addFilter('StringTrim');
         
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Inserisci');
        
        $this->addElements(array($titolo, $corpo, $nome_pagina, $link_pagina, $menu, $menus, $info, $pubblicata, /*$colore,*/ $submit));        
                                    
        $this->clearDecorators();		
		
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
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'align'=>'right', 'class' => 'element')),
		    array(array('label' => 'HtmlTag'), array('tag' => 'td', 'placement' => 'prepend')),
		    array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
        ));
    } 
}
