<?php

class App_Form_FamiglieForm extends Zend_Form  
{ 
    public function __construct($id_lingua=1, $id_eliminare=0, $flag=true,$options = null) 
    { 		
        $info = new Info();
        $info = $info->fetchAll();
        $info = $info->toArray();
        $info_array = array();
        
    	foreach ($info as  $info_row) {        	
        	$info_array[$info_row['id']] = $info_row['societa'];
        }
        
        $menu_table = new moduloProdottiTestiFamiglie();
        if(!$id_eliminare)        
        	$menu_rows = $menu_table->fetchAll("lingua_id=".$id_lingua);
        else
        	$menu_rows = $menu_table->fetchAll("lingua_id=".$id_lingua." AND id !=".$id_eliminare); 
        $menu_rows = $menu_rows->toArray(); // converti l'oggetto rowset in un array in modo da passarlo come parametro all'oggetto selectbox
        $menu_rows_array = array();
        $menu_rows_array[0] = '/';
        
    	foreach ($menu_rows as  $info_row) {        	
        	$menu_rows_array[$info_row['famiglia_id']] = $info_row['nome'];
        }
        
        parent::__construct($options);
        $this->setName('Nuova Famiglia');
        $this->setAttrib('enctype', 'multipart/form-data');
           
        $nome = new Zend_Form_Element_Text('nome');
        $nome->setLabel('Nome famiglia:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('Regex', false, array('/^[a-zA-Z0-9\-\_]*$/'))
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
    
     	$corpo = new Zend_Form_Element_Textarea('descrizione', array('class' => 'fck'));
       	$corpo->setLabel('Descrizione:')
              ->setRequired(false)
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));		 		        
                    
        $menu = new Zend_Form_Element_Select('padre_id');
        $menu->setLabel('Seleziona famiglia parente:')
        	 ->addMultiOptions($menu_rows_array)
             ->setRequired(true)->addFilter('StripTags')
             ->addFilter('StringTrim')
             ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
	    
        $info = new Zend_Form_Element_Select('info_id');
        $info->setLabel('Seleziona società:')
        	 ->addMultiOptions($info_array)
             ->setRequired(true)->addFilter('StripTags')
             ->addFilter('StringTrim')
             ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));             
              
        $immagine = new Zend_Form_Element_File('immagine','immagine');
        $immagine->setLabel('Inserisci immagine:')
             	 ->setRequired(false)             	       	 
             	 ->addValidator('Extension', false, 'jpeg,jpg,png,gif');

        $banner = new Zend_Form_Element_File('banner','banner');
        $banner->setLabel('Inserisci banner:')
             	 ->setRequired(false)  	       	 
             	 ->addValidator('Extension', false, 'jpeg,jpg,png,gif');     	 

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Inserisci');
        
        if($flag)
        	$this->addElements(array($nome, $corpo, $menu, $info, $immagine, $banner, $submit));
        else 
        	$this->addElements(array($nome, $corpo, $submit));	        
                                    
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
