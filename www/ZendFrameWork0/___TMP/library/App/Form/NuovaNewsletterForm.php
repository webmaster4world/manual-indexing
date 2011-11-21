<?php

class App_Form_NuovaNewsletterForm extends Zend_Form  
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
        $this->setName('Nuova Newsletter');
           
        $titolo = new Zend_Form_Element_Text('titolo');
        $titolo->setLabel('Titolo:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));

        $model = new moduloNewsletterModello();
        $model = $model->fetchAll();
        $model = $model->toArray();
        $modelSelect = array();
        foreach ($model as $m){
        	$modelSelect[$m['id']]="Modello ".$m['id'];
        }
        $modello = new Zend_Form_Element_Select('id_modello');
        $modello->setLabel('Modello:')
		      ->addMultiOptions($modelSelect)
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
              
        $area = new moduloNewsletterArea();
        $area = $area->fetchAll();
        $area = $area->toArray();
        $areaSelect = array();
        foreach ($area as $a){
        	$areaSelect[$a['id']]=$a['descrizione'];
        }
        $areaList = new Zend_Form_Element_Select('id_area');
        $areaList->setLabel('Area d\'interesse:')
		      ->addMultiOptions($areaSelect)
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
              
        $data = new Zend_Form_Element_Text('data', array('id'=>'datepicker'));
        $data->setLabel('Data:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));		     
                    
        $testoL1 = new Zend_Form_Element_Textarea('testo_libero1', array('class' => 'fck'));
       	$testoL1->setLabel('Testo Libero 1:')
              ->setRequired(true)
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));		 		        
                            
        $testoL2 = new Zend_Form_Element_Textarea('testo_libero2', array('class' => 'fck'));
       	$testoL2->setLabel('Testo Libero 2:')
              	->addFilter('StringTrim');
              		 		        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Inserisci');
        
		$this->addElements(array($titolo, $modello, $areaList, $data, $testoL1, $testoL2, $submit));                                    
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
