<?php

class App_Form_NuovoAgenteForm extends Zend_Form  
{ 
    public function __construct($options = null) 
    {     	
        parent::__construct($options);
        $this->setName('Nuova agente');
		
        $paese = new moduloListagentiCountry();
        $select = $paese->select();
        $select = $select->order("Name ASC");
        $paese = $paese->fetchAll($select);
        $paese = $paese->toArray();
        $paese_row = array();
        
        foreach($paese as $row)
        	$paese_row[$row["Code"]] = $row["Name"];
        	
        $info = new Info();
        $info = $info->fetchAll();
        $info = $info->toArray();
        $info_array = array();                     	
        
    	foreach ($info as  $info_row) {        	
        	$info_array[$info_row['id']] = $info_row['societa'];
        }
        	
        $id = new Zend_Form_Element_Hidden('id');
        
        $nome = new Zend_Form_Element_Text('nome');
        $nome->setLabel('Nome:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
		
        $cognome = new Zend_Form_Element_Text('cognome');
        $cognome->setLabel('Cognome:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));		         
              
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('E-Mail:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('EmailAddress', false, array('messages' => array(Zend_Validate_EmailAddress::INVALID => '&uarr; Indirizzo email non corretto &uarr;')))
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));		     

		$tele = new Zend_Form_Element_Text('telefono');
        $tele->setLabel('Telefono:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('int', false, array('messages' => array(Zend_Validate_Int::NOT_INT => '&uarr; Il campo è solo numerico &uarr;')))
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));		     
                            
              
		$menu = new Zend_Form_Element_Select('codice_paese');
        $menu->setLabel('Seleziona paese da associare all\'agente:')
        	 ->addMultiOptions($paese_row)
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
        $submit->setLabel('Inserisci');
        
        $this->addElements(array($id, $nome, $cognome, $email, $tele, $menu, $info, $submit));        
                                    
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
