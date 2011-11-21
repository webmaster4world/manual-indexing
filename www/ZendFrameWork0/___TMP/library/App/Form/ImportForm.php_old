<?php

class App_Form_ImportForm extends Zend_Form  
{ 
    public function __construct($lingua_id=1, $id_eliminare=0 ,$options = null) 
    { 
    	
    	// recupera dalla tabella "menu" tutti i menu disponibili da associare al link di una nuova pagina e usali per popolare la relativa select box
    	
    	$menu_table = new VociMenu();
    	($id_eliminare) ? $and = " AND id <>".$id_eliminare : $and = "";
        $menu_rows = $menu_table->fetchAll('Home != \'Si\' AND Prodotto != \'Si\' AND menu_id = 1 AND lingua_id = '.$lingua_id.$and);
        $menu_rows = $menu_rows->toArray(); // converti l'oggetto rowset in un array in modo da passarlo come parametro all'oggetto selectbox
        $menu_rows_array = array();
        $menu_rows_array[0] = '/';
        
        $info = new Info();
        $info = $info->fetchAll();
        $info = $info->toArray();
        $info_array = array(); 
        
        foreach ($menu_rows as  $menu_row_value) {        	
        	$menu_rows_array[$menu_row_value['id']] = $menu_row_value['nome'];
        }            	
        
    	foreach ($info as  $info_row) {        	
        	$info_array[$info_row['id']] = $info_row['societa'];
        }
        
        $men = new Menu();
        $men = $men->fetchAll();
        $men = $men->toArray();
        $men_array = array(); 
        
        foreach ($men as  $men_row_value) {        	
        	$men_array[$men_row_value['id']] = $men_row_value['nome'];
        }  
        
        parent::__construct($options);
        $this->setName('Import');
        $this->setAttrib('enctype', 'multipart/form-data');
           
 		$file = new Zend_Form_Element_File('path','path');
        $file->setLabel('Inserisci un file Excel da importare:') 
                 ->setRequired(false)
                 ->addValidator('Extension', false, 'xls');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
                 
		$this->addElements(array($file, $submit));
		               
    } 
}
