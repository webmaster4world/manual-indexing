<?php
class App_Form_RichiesteQualitaForm extends Zend_Form
{ 
    public function __construct($options = null) 
    { 
        
        parent::__construct($options);
        $this->setName('Form per la misurazione diretta della soddisfazione del cliente');
		$this->setAttrib('enctype', 'multipart/form-data');
        
        $application = new Zend_Session_Namespace('myApplication');
        
        $decors_multi=(array(
            'ViewHelper',
		    'Errors',
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'multiselect','align'=> 'left')),
		    array('Label', array('tag' => 'td','align'=>'center','escape'=>false)),
		    array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'closeOnly'=>'true', 'placement' => Zend_Form_Decorator_Abstract::APPEND)), 
        ));
        
        $decpri=array('ViewHelper',
		    'Errors',
			array('Description', array('tag' => 'div', 'class' => 'description','style'=>'border:1px solid #adadac; padding:5px;height:150px;overflow-y:scroll;','align'=>'left' ,'escape'=>false)),
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'accesso-a','colspan'=>10,'style'=>'padding-bottom:10px;')),
		   array(array('td-label' => 'HtmlTag'),array('tag' => null)),
		    array(array('row' => 'HtmlTag'), array('tag' => 'tr'))

		);
        $decorscheck=(array(
            'ViewHelper',
		    'Errors',
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element','colspan'=>6,'align'=> 'right', 'style'=>'padding:5px;')), //,'width' =>'70'
		    array('Label', array('tag' => 'td','align'=>'left', 'escape'=>false)),
		    array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => 'row_form')),
        ));
        
        $decors=(array(
            'ViewHelper',
		    'Errors',
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element','colspan'=>2,'align'=> 'left', 'style'=>'padding:5px;')),
		    array('Label', array('tag' => 'td','align'=>'left','style' =>'text-align:left;float:left;', 'escape'=>false)),
		    array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => 'row_form')),
        )); 
        
        $decors_impar=(array(
            'ViewHelper',
		    'Errors',
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element','colspan'=>2,'align'=> 'left', 'style'=>'padding:5px;')),
		    array('Label', array('tag' => 'td','align'=>'left','style' =>'text-align:left;float:left;', 'escape'=>false)),
		    array(array('row' => 'HtmlTag'), array('tag' => 'tr')), //, 'style'=>'color:#2C529F;'
        )); 
        
        $decors_pre=(array(
            'ViewHelper',
		    'Errors',
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element','colspan'=>0,'align'=> 'left', 'style'=>'padding-top:10px;padding-left:5px;padding-right:5px;padding-bottom:10px;')),
		    array('Label', array('escape'=>false, 'tag' => 'td','align'=>'left','style' =>'text-align:left;float:left;')),
		    array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => 'row_form', 'openOnly'=>'true', 'placement' => Zend_Form_Decorator_Abstract::PREPEND)),
        )); 
        
        $decors_pre_impar=(array(
            'ViewHelper',
		    'Errors',
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element','colspan'=>0,'align'=> 'left', 'style'=>'padding-top:10px;padding-left:5px;padding-right:5px;padding-bottom:10px;')),
		    array('Label', array('escape'=>false, 'tag' => 'td','align'=>'left','style' =>'text-align:left;float:left;')),
		    array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'openOnly'=>'true', 'placement' => Zend_Form_Decorator_Abstract::PREPEND)), //, 'style'=>'color:#2C529F;'
        )); 
        
        $decors_pre_ext=(array(
            'ViewHelper',
		    'Errors',
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element','colspan'=>0,'align'=> 'left', 'style'=>'padding-top:5px;padding-left:5px;padding-right:5px;padding-bottom:5px;')),
		    array('Label', array('escape'=>false, 'tag' => 'td','align'=>'left','style' =>'text-align:left;float:left;')),
		    array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => 'row_form', 'openOnly'=>'true', 'placement' => Zend_Form_Decorator_Abstract::PREPEND)),
        )); 
        
        $decors_pre_impar_ext=(array(
            'ViewHelper',
		    'Errors',
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element','colspan'=>0,'align'=> 'left', 'style'=>'padding-top:5px;padding-left:5px;padding-right:5px;padding-bottom:5px;')),
		    array('Label', array('escape'=>false, 'tag' => 'td','align'=>'left','style' =>'text-align:left;float:left;')),
		    array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'openOnly'=>'true', 'placement' => Zend_Form_Decorator_Abstract::PREPEND)), //, 'style'=>'color:#2C529F;'
        )); 
        
        $dec=array('ViewHelper',
		    'Errors',
			array('Description', array('tag' => '', 'class' => 'description','style'=>'padding-top:5px;','align'=>'left' ,'escape'=>false)),
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'accesso-a', 'style'=>'padding-top:20px;padding-left:5px;padding-right:5px;padding-bottom:20px;', 'colspan'=>4)),
		    array(array('td-label' => 'HtmlTag'),array('tag' => null)),
		    array(array('row' => 'HtmlTag'), array('tag' => 'tr')), 
		);                

		$field[] = new Zend_Form_Element_Hidden('desc0');	  
		$field[count($field)-1]
		->setDecorators($dec)
		->setDescription(Zend_Registry::get("translate")->_('<center>I campi contrassegnati con <span style="color:#ff0000;">*</span> sono obbligatori</center>'));
		
                $field[] = new Zend_Form_Element_Text('societa');
                $field[count($field)-1]
                ->setDecorators($decors)
        	->setLabel('<span style="color:#ff0000;">*</span><b>'.Zend_Registry::get("translate")->_('SOCIETÀ:').'</b>')
              	->setRequired(true)->addFilter('StripTags')
              	->addFilter('StringTrim')
              	->setAttrib('size', '66')
              	->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => "Il campo e' obbligatorio")));
		
   
                $field[] = new Zend_Form_Element_Text('sede:');
                $field[count($field)-1]
                ->setDecorators($decors)
        		->setLabel('<span style="color:#ff0000;">*</span><b>'.Zend_Registry::get("translate")->_('SEDE:').'</b>')
              	->setRequired(true)->addFilter('StripTags')
              	->addFilter('StringTrim') 
              	->setAttrib('size', '66')
              	->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => Zend_Validate_Abstract::getDefaultTranslator()->_("Il campo e' obbligatorio"))))             	
		->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => "Il campo e' obbligatorio")));
				
                $field[] = new Zend_Form_Element_Text('paritita_iva');
                $field[count($field)-1]
                ->setDecorators($decors)
        	->setLabel('<span style="color:#ff0000;">*</span><b>'.Zend_Registry::get("translate")->_('PARTITA IVA:').'</b>')
              	->setRequired(true)->addFilter('StripTags')
              	->addFilter('StringTrim')
              	->setAttrib('size', '66')
              	->addValidator(new Zend_Validate_Regex("/^[0-9]{11}$/"), true, array('messages' => array(Zend_Validate_Regex::NOT_MATCH => 'Il cap è solo numerico', 'stringEmpty' => 'Il campo è obbligatorio')))
              	->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => "Il campo e' obbligatorio")));

             	$field[] = new Zend_Form_Element_Text('codice_cliente:');
                $field[count($field)-1]
                ->setDecorators($decors)
        	->setLabel('<span style="color:#ff0000;">*</span><b>'.Zend_Registry::get("translate")->_('CODICE CLIENTE:').'</b>')
              	->setRequired(true)->addFilter('StripTags')
              	->addFilter('StringTrim')
              	->setAttrib('size', '66')
              	->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => Zend_Validate_Abstract::getDefaultTranslator()->_("Il campo e' obbligatorio"))))
		->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => "Il campo e' obbligatorio")));


                $field[] = new Zend_Form_Element_Radio('divisione_di_riferimento');
                $field[count($field)-1]
                ->setDecorators($decors_pre)
	        ->setLabel('<span style="color:#ff0000;">*</span><b>'.Zend_Registry::get("translate")->_('DIVISIONE DI RIFERIMENTO:'.'</b>'))
        	->addMultiOptions(array('RETAIL' => Zend_Registry::get("translate")->_('RETAIL'),'INDUSTRIA' => Zend_Registry::get("translate")->_('INDUSTRIA')))
      		->setSeparator(' ')
              	->setRequired(true);

	
                $field[] = new Zend_Form_Element_Hidden('desc1');
		$field[count($field)-1]
		->setDecorators($dec)
		->setDescription(Zend_Registry::get("translate")->_('Al fine di fornire i prodotti da Voi richiesti in modo da soddisfare le Vostre aspettative, Vi invitiamo ad indicare i requisiti e le esigenze per la loro realizzazione.<br><br>
                    <b>LEGENDA: <br>• A=molto soddisfatto
                                <br>• B=soddisfatto
                                <br>• C=poco soddisfatto
                                <br>• D=non soddisfatto
                                <br>• E=non applicabile</b><br>'));
		
		

		$field[] = new Zend_Form_Element_Radio('qualita_del_prodotto');
                $field[count($field)-1]
                ->setDecorators($decors_pre)
		->setLabel(Zend_Registry::get("translate")->_('Qualità del prodotto:'))
        	->addMultiOptions(array('A' => Zend_Registry::get("translate")->_('A'),'B' => Zend_Registry::get("translate")->_('B'), 'C' => Zend_Registry::get("translate")->_('C'), 'D' => Zend_Registry::get("translate")->_('D'), 'E' => Zend_Registry::get("translate")->_('E')))
      		->setSeparator(' ')
              	->setRequired(false);

                $field[]= new Zend_Form_Element_Textarea('commenti_qualita_del_prodotto');
                $field[count($field)-1]
       		->setDecorators($decors)
       		->setAttrib('rows', 5)
		->setAttrib('cols', 76)
       		->setLabel(Zend_Registry::get("translate")->_('Commenti (se presenti):'))
                ->addFilter('StringTrim');

                $field[] = new Zend_Form_Element_Hidden('desc2');
		$field[count($field)-1]
		->setDecorators($dec)
		->setDescription(Zend_Registry::get("translate")->_("<center><b>----------------------------------------------------------------------------------------------------------------------------------------</b></center>"));

                $field[] = new Zend_Form_Element_Radio('tempi_di_consegna');
                $field[count($field)-1]
                ->setDecorators($decors_pre)
		->setLabel(Zend_Registry::get("translate")->_('Tempi di consegna:'))
        	->addMultiOptions(array('A' => Zend_Registry::get("translate")->_('A'),'B' => Zend_Registry::get("translate")->_('B'), 'C' => Zend_Registry::get("translate")->_('C'), 'D' => Zend_Registry::get("translate")->_('D'), 'E' => Zend_Registry::get("translate")->_('E')))
      		->setSeparator(' ')
              	->setRequired(false);

                $field[]= new Zend_Form_Element_Textarea('comment_tempi_di_consegna');
                $field[count($field)-1]
       		->setDecorators($decors)
       		->setAttrib('rows', 5)
		->setAttrib('cols', 76)
       		->setLabel(Zend_Registry::get("translate")->_('Commenti (se presenti):'))
                ->addFilter('StringTrim');

                $field[] = new Zend_Form_Element_Hidden('desc3');
		$field[count($field)-1]
		->setDecorators($dec)
		->setDescription(Zend_Registry::get("translate")->_("<center><b>----------------------------------------------------------------------------------------------------------------------------------------</b></center>"));


                 $field[] = new Zend_Form_Element_Radio('assistenza_tecnica');
                $field[count($field)-1]
                ->setDecorators($decors_pre)
		->setLabel(Zend_Registry::get("translate")->_('Assistenza tecnica:'))
        	->addMultiOptions(array('A' => Zend_Registry::get("translate")->_('A'),'B' => Zend_Registry::get("translate")->_('B'), 'C' => Zend_Registry::get("translate")->_('C'), 'D' => Zend_Registry::get("translate")->_('D'), 'E' => Zend_Registry::get("translate")->_('E')))
      		->setSeparator(' ')
              	->setRequired(false);

                $field[]= new Zend_Form_Element_Textarea('commenti_assistenza_tecnica');
                $field[count($field)-1]
       		->setDecorators($decors)
       		->setAttrib('rows', 5)
		->setAttrib('cols', 76)
       		->setLabel(Zend_Registry::get("translate")->_('Commenti (se presenti):'))
                ->addFilter('StringTrim');

                $field[] = new Zend_Form_Element_Hidden('desc4');
		$field[count($field)-1]
		->setDecorators($dec)
		->setDescription(Zend_Registry::get("translate")->_("<center><b>----------------------------------------------------------------------------------------------------------------------------------------</b></center>"));

                $field[] = new Zend_Form_Element_Radio('customer_service');
                $field[count($field)-1]
                ->setDecorators($decors_pre)
		->setLabel(Zend_Registry::get("translate")->_('Customer service:'))
        	->addMultiOptions(array('A' => Zend_Registry::get("translate")->_('A'),'B' => Zend_Registry::get("translate")->_('B'), 'C' => Zend_Registry::get("translate")->_('C'), 'D' => Zend_Registry::get("translate")->_('D'), 'E' => Zend_Registry::get("translate")->_('E')))
      		->setSeparator(' ')
              	->setRequired(false);

                $field[]= new Zend_Form_Element_Textarea('commenti_customer_service');
                $field[count($field)-1]
       		->setDecorators($decors)
       		->setAttrib('rows', 5)
		->setAttrib('cols', 76)
       		->setLabel(Zend_Registry::get("translate")->_('Commenti (se presenti):'))
                ->addFilter('StringTrim');



	
			  	
        $field[] = new Zend_Form_Element_Submit('submit');
        $field[count($field)-1]->setLabel(Zend_Registry::get("translate")->_('INVIA IL MODULO'));
                        
       $this->addElements($field);
       
                       
        $this->clearDecorators();
        $this->addDecorator('FormElements')
         	 ->addDecorator('HtmlTag', array('tag' => 'table', 'style' => 'width:930px;margin-top:25px;', 'align'=>'center','cellspacing'=>0,'cellpadding'=>0, 'cols'=>'4'))
         	 ->addDecorator('Form');     

        // buttons do not need labels
      $this->getElement('submit')
        	->setDecorators(array(
            'ViewHelper',
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'align'=>'left', 'class' => 'element','style'=>'padding-top:20px;padding-left:5px;padding-bottom:20px;padding-right:5px;')),
		    array(array('label' => 'HtmlTag'), array('tag' => 'td', 'placement' => 'prepend')),
		    array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
        ));
    } 
}
