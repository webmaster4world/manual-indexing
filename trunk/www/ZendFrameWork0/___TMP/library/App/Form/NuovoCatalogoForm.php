<?php

class App_Form_NuovoCatalogoForm extends Zend_Form  
{ 
    public function __construct($user = 7, $options = null) 
    {     	
        parent::__construct($options);
        $this->setName('Nuova famiglia');
        $this->setEnctype('multipart/form-data'); 
        
        $articoli = new moduloCatalogo();
        if($user!=1){
        $articoli = $articoli->fetchAll("tipo =".$user);
        }
        else $articoli = $articoli->fetchAll();
        
        $articoli = $articoli->toArray();
        $articoli_array = array(); 
        
        foreach ($articoli as  $articoli_row_value) {        	
        	$articoli_array[$articoli_row_value['id']] = $articoli_row_value['oggetto'];
        }        	
        
    	$utenze = array();
    	$utenze[2]="Home Electronics";
    	$utenze[3]="Car System";
    	$utenze[4]="Energy";
    	$utenze[5]="Electronics";
    	$utenze[6]="Industry";
    	
        
        $id = new Zend_Form_Element_Hidden('id');
        
        $oggetto = new Zend_Form_Element_Text('oggetto');
        $oggetto->setLabel('Famiglia:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
		
              
        $padre_id = new Zend_Form_Element_Select('padre_id');
        $padre_id->setLabel('Padre:')
        	->addMultiOption(0,"nessuno")
			->addMultiOptions($articoli_array,$articoli_array)
        	->setRequired(false);
 
        
        if($user == 1):
        $tipo = new Zend_Form_Element_Select('tipo');
        $tipo->setLabel('Divisione:')
        	//->addMultiOption(1,"nessuno")
			->addMultiOptions($utenze, $utenze)
        	->setRequired(true);
        endif;
        	
        $pdf = new Zend_Form_Element_File('pdf');
        $pdf->setLabel('Inserisci pdf:') 
        		 ->setRequired(false)
        		 ->addValidator('Extension', false, 'pdf');      
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Inserisci');
        
        if($user == 1){
        $this->addElements(array($id, $oggetto, $padre_id, $tipo, $pdf, $submit));}
        else 
    	$this->addElements(array($id, $oggetto, $padre_id, $pdf, $submit));
                
                                    
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
