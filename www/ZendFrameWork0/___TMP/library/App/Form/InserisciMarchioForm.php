<?php

class App_Form_InserisciMarchioForm extends Zend_Form {
	public function __construct($options = null) 
    {         
        parent::__construct($options);
        $this->setName('GestioneMarchio');
        
        $id = new Zend_Form_Element_Hidden('id');
    
        $nome = new Zend_Form_Element_Text('nome');
        $nome->setLabel('Nome:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => ' Il campo è obbligatorio')));
              
        $tipologia = new Zend_Form_Element_Select('tipologia');
        $tipologia->setLabel('Tipologia:')
        	->addMultiOptions(array(
        	'attivi' => 'attivi',
        	'pemco' => 'pemco',
        	'batterie' => 'batterie',
        	'display' => 'display',
        	'strumentazione' => 'strumentazione'
        ));
        
        $continente = new Zend_Form_Element_Select('continente');
        $continente->setLabel('Provenienza:')
        	->addMultiOptions(array(
        	'europa' => 'europa',
        	'america' => 'america',
        	'cina' => 'cina',
        	'corea' => 'corea',
        	'giappone' => 'giappone'
        ));

        $db = Zend_Registry::get('db');
        $tipi_prodotti = $db->fetchAll("select DISTINCT(nome) from marchi_prodotti order by nome ASC");
        $prodotti_array=array();
        foreach($tipi_prodotti as $t)
            foreach($t as $t1)
                $prodotti_array[$t1]=$t1;

        $prodotti = new Zend_Form_Element_Select('prodotti');
        $prodotti->setLabel('Prodotti:')
        	->addMultiOptions($prodotti_array);

        $anno = new Zend_Form_Element_Text('anno');
        $anno->setLabel('Anno:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('Digits')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => ' Il campo è obbligatorio')));
        
        $link = new Zend_Form_Element_Text('link');
        $link->setLabel('Link:')
              ->addFilter('StripTags')
              ->addFilter('StringTrim');
             
        $image = new Zend_Form_Element_File('image');
        $image->setLabel('Immagine')
        	  ->setRequired(false)
        		//->setDestination(getenv('DOCUMENT_ROOT').'/public/images/industry/big')
        		->addFilter('Rename', getenv('DOCUMENT_ROOT').'/tmp')        		
        		->addValidator('Count', false, 1);
        		
        //validate file
		//for example, this checks there is exactly 1 file, it is a jpeg and is less than 512KB
//		$image = new Zend_File_Transfer_Adapter_Http('image');
//		$image
//				->addValidator('Count', false, array('min' =>1, 'max' => 1))
//		       ->addValidator('IsImage', false, 'jpg,png,gif')
//		       ->addValidator('Size', false, array('max' => '1MB'))
//		       ->setDestination(getenv('DOCUMENT_ROOT').'/public/images/industry/big');
              
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        
        if($options != 1){
        	$this->addElements(array($nome, $tipologia, $continente, $prodotti, $anno, $link, $image, $submit));
        }
        else{
        	$this->addElements(array($nome, $tipologia, $continente, $prodotti, $anno, $link, $submit));
        }
                                    
        $this->clearDecorators();
        $this->addDecorator('FormElements')
         ->addDecorator('HtmlTag', array('tag' => '<ul>'))
         ->addDecorator('Form')
         ->addDecorator('File')
         ->setAttrib('enctype', 'multipart/form-data');
        
//        $this->setElementDecorators(array(
//            array('ViewHelper'),
//            array('Errors',array('escape' => false)),
//            array('Description'),
//            array('Label', array('separator'=>' ')),
//            array('HtmlTag', array('tag' => 'li', 'class'=>'element-group')),
//        ));

        // buttons do not need labels
        $submit->setDecorators(array(
            array('ViewHelper'),
            array('Description'),
            array('HtmlTag', array('tag' => 'li', 'class'=>'submit-group')),
        ));
    } 
}