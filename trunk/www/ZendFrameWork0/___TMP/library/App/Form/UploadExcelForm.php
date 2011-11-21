<?php

class App_Form_UploadExcelForm extends Zend_Form  
{ 
    public function __construct($lingua_id=1, $id_eliminare=0 ,$options = null) 
    { 
    	
    	// recupera dalla tabella "menu" tutti i menu disponibili da associare al link di una nuova pagina e usali per popolare la relativa select box
        
        parent::__construct($options);
        $this->setName('Import');
        $this->setAttrib('enctype', 'multipart/form-data');
        
        $sel = new Zend_Form_Element_Select('dove');
        $sel->setLabel('Dove lo inserisco ?')
            ->addMultiOptions(array("prodotti"=>"Prodotti","famiglie"=>"Famiglie"));
        
        $file = new Zend_Form_Element_File('path','path');
        $file->setLabel('Inserisci un file Excel da importare:') 
                 ->setRequired(false)
                 ->addValidator('Extension', false, 'xls');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
                 
		$this->addElements(array($sel,$file, $submit));
		               
    } 
}
