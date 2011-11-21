<?php

class App_Form_IscrizioneNewsletterForm extends Zend_Form {
	public function __construct($options = null) 
    {         
        parent::__construct($options);
        $this->setName('Iscrizionenewsletter');

		$decors = array(
		    'ViewHelper',
		    'Errors',
		    array('Description', array('tag' => 'p', 'class' => 'description')),
		    array('HtmlTag', array('tag' => 'dd', 'class' => 'text')),
		    array(array('labelDtClose' => 'HtmlTag'), 
		          array('tag' => 'dt', 'closeOnly' => true, 'placement' => 'prepend')),
		    'Label',
		    array(array('labelDtOpen' => 'HtmlTag'), 
		          array('tag' => 'dt', 'openOnly' => true, 'placement' => 'prepend', 'class' => 'text'))
		);
		
		$decors1 = array(
		    'ViewHelper',
		    'Errors',
		    array('Description', array('tag' => 'p', 'class' => 'description')),
		    array('HtmlTag', array('tag' => 'dd', 'class' => 'bottom')),
		    array(array('labelDtClose' => 'HtmlTag'), 
		          array('tag' => 'dt', 'closeOnly' => true, 'placement' => 'prepend')),
		    'Label',
		    array(array('labelDtOpen' => 'HtmlTag'), 
		          array('tag' => 'dt', 'openOnly' => true, 'placement' => 'prepend', 'class' => 'bottom'))
		);		
		
		$nome = Zend_Form::createElement(
		    'text',
		    'nome',
		    array('label' => 'Nome', 'decorators' => $decors)
		)->setRequired(true)->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('Alpha', true, array('messages' => array('notAlpha' => 'Il campo deve contenere solo lettere', 'stringEmpty' => 'Il campo è obbligatorio')))
		->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => 'Il campo è obbligatorio')));
		       
		$cognome = Zend_Form::createElement(
		    'text',
		    'cognome',
		    array('label' => 'Cognome', 'decorators' => $decors)
		)->setRequired(true)->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('Alpha', true, array('messages' => array('notAlpha' => 'Il campo deve contenere solo lettere', 'stringEmpty' => 'Il campo è obbligatorio')))
		->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => 'Il campo è obbligatorio')));
		                            
		$mail = Zend_Form::createElement(
		    'text',
		    'email',
		    array('label' => 'Email', 'decorators' => $decors)
		)->setRequired(true)->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator("EmailAddress", true, array('messages' => array(Zend_Validate_EmailAddress::INVALID => 'Il campo deve contenere un indirizzo valido')))
		->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => 'Il campo è obbligatorio')));
		              
        $area = new moduloNewsletterArea();
        $area = $area->fetchAll();
        $area = $area->toArray();
        $areaSelect = array();
        foreach ($area as $a){
        	$areaSelect[$a['id']]=$a['descrizione'];
        }
        
		$areaList = Zend_Form::createElement(
		    'select',
		    'id_area',
		    array('label' => 'Area d\'interesse', 'decorators' => $decors1)
		)->setRequired(true)->addFilter('StripTags')
		->addMultiOptions($areaSelect)
		->addFilter('StringTrim')
        ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => 'Il campo è obbligatorio')));

        $privacy = Zend_Form::createElement(
		    'checkbox',
		    'privacy',
		    array('label' => 'Dichiaro di aver letto l\'informativa sulla privacy e autorizzo il trattamento dei miei dati personali.', 'decorators' => $decors1)
        )->setRequired(true)
  		->addValidator('Regex', true, array('/1/','messages' => array(Zend_Validate_Regex::NOT_MATCH => 'Il campo è obbligatorio')));
       
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $submit->setName('Invia');
        
        $this->addElements(array($nome, $cognome, $mail, $areaList, $privacy, $submit));
                                    
        /*$this->clearDecorators();*/
        $this->addDecorator('FormElements')
         ->addDecorator('HtmlTag', array('tag' => '<dl>', 'id'=>'newsletter-form'))
         ->addDecorator('Form');
        
        /*$this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors',array('escape' => false)),
            array('Description'),
            array('Label', array('separator'=>' ')),
            array('HtmlTag', array('tag' => 'li', 'class'=>'element-group')),
        ));
        
        $cognome->getDecorator('Label')->setOption('class', 'text');
        $mail->getDecorator('Label')->setOption('class', 'text');*/
        
        //buttons do not need labels
        $submit->setDecorators(array(
            array('ViewHelper'),
            array('Description'),
            array('HtmlTag', array('tag' => 'dd', 'class'=>'submit-group')),
        ));
    } 
}