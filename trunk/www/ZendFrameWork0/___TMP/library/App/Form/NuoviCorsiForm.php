<?php

class App_Form_NuoviCorsiForm extends Zend_Form  
{ 
    public function __construct($options = null) 
    {     	
        parent::__construct($options);
        $this->setName('Nuova corso');

        $id = new Zend_Form_Element_Hidden('id');
        
        $titolo = new Zend_Form_Element_Text('corso');
        $titolo->setLabel('Corso:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
		
        $sede = new Zend_Form_Element_Text('sede');
        $sede->setLabel('Sede:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));

        $durata = new Zend_Form_Element_Text('durata');
        $durata->setLabel('Durata:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
		      
              
        $data = new Zend_Form_Element_Text('data', array('id'=>'datepicker'));
        $data->setLabel('Data inizio:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));		     
              

     	$corpo = new Zend_Form_Element_Textarea('testo', array('class' => 'fck'));
       	$corpo->setLabel('Testo:')
              ->setRequired(true)
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));		 		        
                     

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Inserisci');
        
        $this->addElements(array($id, $titolo, $sede, $data, $durata, $corpo, $submit));        
                                    
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
