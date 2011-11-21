<?php

class App_Form_NuovaFotoSlideshowForm extends Zend_Form {
	public function __construct($options = null) 
    {         
        parent::__construct($options);
        $this->setName('GestioneFotoSlideshow');
        $this->setEnctype('multipart/form-data'); 
        
    	$cat = new moduloSlideshowCategorie();
    	$cat = $cat->fetchAll();
    	$categoria_id = "";
    	foreach ($cat as $row)    	
    		$categoria_id[$row["id"]] = $row["nome"];
    	        
        $categoria = new Zend_Form_Element_Text('nome');
        $categoria->setLabel('Nome Foto:')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')              
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));

        $immagine = new Zend_Form_Element_File('path','path');
        $immagine->setLabel('Inserisci immagine:') 
        		 ->setRequired(false)
        		 ->addValidator('Extension', false, 'jpg,png,gif')
        		 ->addValidator('Size',
					                      false,
					                      array('min' => '10kB',
					                            'max' => '4MB',
					                            'bytestring' => false))
        		 ->addValidator('ImageSize', false,
								                      array('minwidth' => 100,
								                            /*'maxwidth' => 1024,*/
								                            'minheight' => 100/*,
								                            'maxheight' => 768*/)
								                      );

        $menu = new Zend_Form_Element_Select('categoria_id');
        $menu->setLabel('Seleziona categoria da associare:')
        	 ->addMultiOptions($categoria_id)
             ->setRequired(true)->addFilter('StripTags')
             ->addFilter('StringTrim')
             ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
            	              	 
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        
        $this->addElements(array($categoria, $immagine, $menu, $submit));
                                    
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