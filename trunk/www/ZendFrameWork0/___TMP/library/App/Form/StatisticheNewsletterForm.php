<?php

class App_Form_StatisticheNewsletterForm extends Zend_Form {
	public function __construct($options = null) 
    {         
        parent::__construct($options);
        $this->setName('Statistichenewsletter');
		

		$select[0]="Qualsiasi";
        for($i=1;$i<=12;$i++){
        	$select[$i]=date_format(date_create("2000-".($i)."-01"),"F");
        }
        
		$listM = Zend_Form::createElement(
		    'select',
		    'mese',
		    array('label' => 'Mese')
		)->addMultiOptions($select)
		->addFilter('StringTrim');

		$select1[0]="Qualsiasi";
		$d=date("Y");
        for($i=0;$i<3;$i++){
        	$select1[$i+1]=$d-$i;
        }
        
		$listY = Zend_Form::createElement(
		    'select',
		    'anno',
		    array('label' => 'Anno')
		)->addMultiOptions($select1)
		->addFilter('StringTrim');
		
		
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $submit->setName('Invia');
        $submit->setLabel("Filtra");
        
        $this->addElements(array($listM, $listY, $submit));
        
        $this->clearDecorators();
        $this->addDecorator('FormElements')
         ->addDecorator('HtmlTag', array('tag' => '<tr>'))
         ->addDecorator('Form');
        
        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors',array('escape' => false)),
            array('Description'),
            array('Label', array('separator'=>' ')),
            array('HtmlTag', array('tag' => '<td>', 'class'=>'element-group')),
        ));

        // buttons do not need labels
        $submit->setDecorators(array(
            array('ViewHelper'),
            array('Description'),
            array('HtmlTag', array('tag' => '<td>', 'class'=>'submit-group')),
        ));
        
                                    
    } 
}