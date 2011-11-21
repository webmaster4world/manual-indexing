<?php

class App_Form_CercaNewsForm extends Zend_Form  
{ 
    public function __construct($user = 7, $options = null) 
    {     	
        parent::__construct($options);
        $this->setName('Cerca News');
        $this->setEnctype('multipart/form-data'); 
        
        $news = new moduloNews();
        if($user!=1){
        	$news = $news->fetchAll("tipo =".$user);
        }
        else $news = $news->fetchAll();
        
        $news = $news->toArray();
        $news_array = array(); 
        
        foreach ($news as  $news_row_value) {        	
        	$news_array[$news_row_value['id']] = $news_row_value['titolo'];
        }        	
        
    	$utenze = array();
    	$utenze[2]="home";
    	$utenze[3]="carsystem";
    	$utenze[4]="energy";
    	$utenze[5]="electronics";
    	$utenze[6]="industry";
    	
        
        $id = new Zend_Form_Element_Hidden('id');
        
        $titolo = new Zend_Form_Element_Text('titolo');
        $titolo->setLabel('Cerca nel titolo:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
		
              
        $corpo = new Zend_Form_Element_Select('corpo');
        $corpo->setLabel('Cerca nel corpo:')
        	->addMultiOption(0,"nessuno")
			->addMultiOptions($news_array,$news_array)
        	->setRequired(false);
 
        
        if($user == 1):
        $tipo = new Zend_Form_Element_Select('tipo');
        $tipo->setLabel('Tipo:')
        	//->addMultiOption(1,"nessuno")
			->addMultiOptions($utenze, $utenze)
        	->setRequired(true);
        endif;
        	
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Cerca');
        
        if($user == 1){
        $this->addElements(array($id, $titolo, $corpo, $tipo, $submit));}
        else 
    	$this->addElements(array($id, $titolo, $corpo, $submit));
                
                                    
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
