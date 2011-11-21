<?php

class App_Form_NuovaNewsForm extends Zend_Form  
{ 
    public function __construct($user = 7, $options = null) 
    {     	
        parent::__construct($options);
        $this->setName('Nuova news');
        $this->setEnctype('multipart/form-data'); 

        $id = new Zend_Form_Element_Hidden('id');
        
        $titolo = new Zend_Form_Element_Text('titolo');
        $titolo->setLabel('Titolo:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
		
        $data_pub = new Zend_Form_Element_Text('publication', array('class'=>'datepicker'));
        $data_pub->setLabel('Data per la pubblicazione:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
        
        $data_exp = new Zend_Form_Element_Text('expire', array('class'=>'datepicker'));
        $data_exp->setLabel('Data di scadenza:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));		     
              
        $desc = new Zend_Form_Element_Textarea('descrizione_breve', array('id' => 'corpo_min'));
              
        $desc = new Zend_Form_Element_Textarea('descrizione_breve', array('id' => 'corpo_min'));
       	$desc->setLabel('Descrizione breve:')
              ->setRequired(true)
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));		 		        
                
              
     	$corpo = new Zend_Form_Element_Textarea('testo', array('class' => 'fck'));
       	$corpo->setLabel('Testo:')
              ->setRequired(true)
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));		 		        
                     
      /*$pubblicata = new Zend_Form_Element_Hidden('tipo');     
        $pubblicata->addFilter('StripTags')
              		->addFilter('StringTrim');*/
        $immagine = new Zend_Form_Element_File('foto');
        $immagine->setLabel('Inserisci immagine:') 
        		 ->setRequired(false)
        		 ->addValidator('Extension', false, 'jpg,png,gif')
        		 ->addValidator('Size',
					                      false,
					                      array('min' => '1kB',
					                            'max' => '4MB',
					                            'bytestring' => false))
        		 ->addValidator('ImageSize', false,
								                      array('minwidth' => 30,
								                            /*'maxwidth' => 1024,*/
								                            'minheight' => 30/*,
								                            'maxheight' => 768*/)
								                      );      
        
								                      
		$utenze = array();
    	$utenze[2]="Home Electronics";
    	$utenze[3]="Car System";
    	$utenze[4]="Energy";
    	$utenze[5]="Electronics";
    	$utenze[6]="Industry";			                      
								                      
								                      
		//if($user == 1):
        $tipo = new Zend_Form_Element_Select('tipo');
        $tipo->setLabel('Divisione:')
        	//->addMultiOption(1,"nessuno")
			->addMultiOptions($utenze, $utenze)
        	->setRequired(true);
        //endif;						                      
								                      
								                      
								                      
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Inserisci');
        
        $this->addElements(array($id, $titolo, $data_pub,$data_exp, $desc, $corpo, $immagine,$tipo,/*$pubblicata,*/ $submit));        
                                    
        $this->addDecorator('FormElements')
         	 ->addDecorator('HtmlTag', array('tag' => 'table', 'style' => 'width:700px'))
         	 ->addDecorator('Form')
                 ->addDecorator('File');
                 	 
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
