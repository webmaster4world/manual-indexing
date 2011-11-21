<?php

class App_Form_NuovoCatalogoCarsystemForm extends Zend_Form
{ 
    public function __construct( $options = null) 
    {     	
        parent::__construct($options);
        $this->setName('Nuova famiglia');
        $this->setEnctype('multipart/form-data'); 
        
        $id = new Zend_Form_Element_Hidden('id');

        $db = Zend_Registry::get('db');
        $tipi_famiglia = $db->fetchAll("select DISTINCT(famiglia) from modulo_catalogo_carsystem order by famiglia ASC");
        $famiglia_array=array();
        foreach($tipi_famiglia as $t)
            foreach($t as $t1)
                $famiglia_array[$t1]=$t1;
        
        $famiglia = new Zend_Form_Element_Select('famiglia');
        $famiglia->setLabel("Famiglia:")
                
                ->addMultiOptions($famiglia_array)
                ->setRegisterInArrayValidator(false)
                ->setRequired(true);

        //$categoria_array=(array('INDUSTRIALE'=>'INDUSTRIALE', 'VETTURE / COMMERCIALE'=>'VETTURE / COMMERCIALE', 'VETTURE/COMMERCIALI'=>'VETTURE/COMMERCIALI'));
        $tipi_categoria = $db->fetchAll("select DISTINCT(categoria) from modulo_catalogo_carsystem order by categoria ASC");
        $categoria_array=array();
        foreach($tipi_categoria as $p)
            foreach($p as $p1)
                $categoria_array[$p1]=$p1;
        
        $categoria = new Zend_Form_Element_Select('categoria');
        $categoria->setLabel("Categoria:")
                
                ->addMultiOptions($categoria_array)
                ->setRegisterInArrayValidator(false)
                ->setRequired(true);

        /*$marca_array=(array(''=>'', 'ALFA ROMEO'=>'ALFA ROMEO', 'AUDI'=>'AUDI', 'AUTOBIANCHI'=>'AUTOBIANCHI', 'BEDFORD IND.'=>'BEDFORD IND.', 'BMW'=>'BMW',
            'CHEVROLET/ DAEWOO'=>'CHEVROLET/ DAEWOO', 'CHRYSLER'=>'CHRYSLER', 'CITROEN'=>'CITROEN', 'DACIA'=>'DACIA', 'DAF IND.'=>'DAF IND.', 'DAIHATSU'=>'DAIHATSU',
            'FIAT'=>'FIAT', 'FIAT IND.'=>'FIAT IND.', 'FORD'=>'FORD', 'FORD  IND.'=>'FORD  IND.', 'HONDA'=>'HONDA', 'HYUNDAI'=>'HYUNDAI',
            'INNOCENTI'=>'INNOCENTI', 'ISUZU'=>'ISUZU', 'IVECO IND.'=>'IVECO IND.', 'KIA'=>'KIA', 'LANCIA'=>'LANCIA', 'LAND ROVER'=>'LAND ROVER',
            'LEYLAND IND.'=>'LEYLAND IND.', 'MAGIRUS IND.'=>'MAGIRUS IND.', 'MAN IND.'=>'MAN IND.', 'MAZDA'=>'MAZDA', 'MERCEDES'=>'MERCEDES', 'MERCEDES IND.'=>'MERCEDES IND.',
            'MINI'=>'MINI', 'MITSUBISHI'=>'MITSUBISHI', 'NISSAN'=>'NISSAN', 'NISSAN IND.'=>'NISSAN IND.', 'OM IND.'=>'OM IND.', 'OPEL'=>'OPEL',
            'PEUGEOT'=>'PEUGEOT', 'PIAGGIO'=>'PIAGGIO', 'RENAULT'=>'RENAULT', 'RENAULT IND.'=>'RENAULT IND.', 'ROVER'=>'ROVER', 'SAAB'=>'SAAB',
            'SCANIA IND.'=>'SCANIA IND.', 'SEAT'=>'SEAT', 'SKODA'=>'SKODA', 'SMART'=>'SMART', 'SUBARU'=>'SUBARU', 'SUZUKI'=>'SUZUKI',
            'TOYOTA'=>'TOYOTA', 'TRATTORI'=>'TRATTORI', 'UNIVERSALE'=>'UNIVERSALE', 'UNIVERSALE IND.'=>'UNIVERSALE IND.', 'UNIVERSALI'=>'UNIVERSALI', 'VOLKSWAGEN'=>'VOLKSWAGEN',
            'VOLVO'=>'VOLVO', 'VOLVO IND.'=>'VOLVO IND.'));*/

        $tipi_marca = $db->fetchAll("select DISTINCT(marca) from modulo_catalogo_carsystem order by marca ASC");
        $marca_array=array();
        foreach($tipi_marca as $m)
            foreach($m as $m1)
                $marca_array[$m1]=$m1;
        
        $marca = new Zend_Form_Element_Select('marca');
        $marca->setLabel("Marca:")
                
                ->addMultiOptions($marca_array)
                ->setRegisterInArrayValidator(false)
                ->setRequired(false);

        $modello = new Zend_Form_Element_Text('modello');
        $modello->setLabel('Modello:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');
             

        $cod_art_st = new Zend_Form_Element_Text('cod_art_st');
        $cod_art_st->setLabel('Codice Melchioni:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));

              
        $cod_pos = new Zend_Form_Element_Text('cod_pos');
        $cod_pos->setLabel('Cod_pos:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $lungo = new Zend_Form_Element_Text('lungo');
        $lungo ->setLabel('Lungo:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');
 
        $indicod = new Zend_Form_Element_Text('indicod');
        $indicod ->setLabel('Indicod:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');
        
        $cod_fornitore = new Zend_Form_Element_Text('cod_fornitore');
        $cod_fornitore ->setLabel('Codice fornitore:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $nome_fornitore = new Zend_Form_Element_Text('nome_fornitore');
        $nome_fornitore ->setLabel('Nome Fornitore:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $cod_sx_st = new Zend_Form_Element_Text('cod_sx_st');
        $cod_sx_st ->setLabel('Cod_sx_st:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $cod_pos_sx = new Zend_Form_Element_Text('cod_pos_sx');
        $cod_pos_sx->setLabel('Cod_pos_sx:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $lungo_sx = new Zend_Form_Element_Text('lungo_sx');
        $lungo_sx ->setLabel('lungo_sx:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $indicod_sx = new Zend_Form_Element_Text('indicod_sx');
        $indicod_sx ->setLabel('indicod_sx:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $cod_fornitore_sx = new Zend_Form_Element_Text('cod_fornitore_sx');
        $cod_fornitore_sx ->setLabel('Codice fornitore_sx:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $nome_fornitore_sx = new Zend_Form_Element_Text('nome_fornitore_sx');
        $nome_fornitore_sx ->setLabel('Nome Fornitore_sx:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $descr = new Zend_Form_Element_Textarea('descr', array('class' => 'fck'));
        $descr ->setLabel('Descrizione:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $tipo = new Zend_Form_Element_Text('tipo');
        $tipo->setLabel('Tipo:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $tipo_lampada = new Zend_Form_Element_Text('tipo_lampada');
        $tipo_lampada ->setLabel('Tipo lampada:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $azionamento = new Zend_Form_Element_Text('azionamento');
        $azionamento ->setLabel('Azionamento:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $sottotipo = new Zend_Form_Element_Text('sottotipo');
        $sottotipo->setLabel('Sottotipo:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $colore = new Zend_Form_Element_Text('colore');
        $colore ->setLabel('Colore:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $montaggio = new Zend_Form_Element_Text('montaggio');
        $montaggio ->setLabel('Montaggio:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $corpo = new Zend_Form_Element_Text('corpo');
        $corpo ->setLabel('Corpo:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $con = new Zend_Form_Element_Text('con');
        $con ->setLabel('Con:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $senza = new Zend_Form_Element_Text('senza');
        $senza->setLabel('Senza:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $specchio = new Zend_Form_Element_Text('specchio');
        $specchio ->setLabel('Specchio:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $opz_specchio = new Zend_Form_Element_Text('opz_specchio');
        $opz_specchio ->setLabel('Opzioni specchio:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $nota_fine = new Zend_Form_Element_Text('nota_fine');
        $nota_fine ->setLabel('Nota fine:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $opzioni = new Zend_Form_Element_Text('opzioni');
        $opzioni ->setLabel('Opzioni:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $misure = new Zend_Form_Element_Text('misure');
        $misure->setLabel('Misure:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $descr_simboli = new Zend_Form_Element_Text('descr_simboli');
        $descr_simboli ->setLabel('Descrizione simboli:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $extra_testo = new Zend_Form_Element_Text('extra_testo');
        $extra_testo ->setLabel('Extra testo:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $rif_marca = new Zend_Form_Element_Text('rif_marca');
        $rif_marca ->setLabel('Rif marca:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $rif_modello = new Zend_Form_Element_Text('rif_modello');
        $rif_modello->setLabel('Rif modello:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $new = new Zend_Form_Element_Text('new');
        $new ->setLabel('New:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $nnew = new Zend_Form_Element_Text('nnew');
        $nnew ->setLabel('Nnew:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $note = new Zend_Form_Element_Text('note');
        $note ->setLabel('Note:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $oe = new Zend_Form_Element_Text('oe');
        $oe ->setLabel('Codice Originale:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $foto = new Zend_Form_Element_Text('foto');
        $foto ->setLabel('Codice foto:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $foto2 = new Zend_Form_Element_Text('foto2');
        $foto2->setLabel('Codice foto 2:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $data_x_new = new Zend_Form_Element_Text('data_x_new');//,array('id'=>'datepicker',"size"=>20));
        $data_x_new ->setLabel('Data_x_new:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator(new Zend_Validate_Regex("/^[0-9]{4}\\-[0-9]{2}\\-[0-9]{2}$/"), true, array('messages' => array(Zend_Validate_Regex::NOT_MATCH => 'Data in formato [AAAA-MM-DD]')));

        $data_agg= new Zend_Form_Element_Text('data_agg', array('id'=>'datepicker_a',"size"=>20));
        $data_agg ->setLabel('Data_agg:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $data_app= new Zend_Form_Element_Text('data_app', array('id'=>'datepicker_c',"size"=>20));
        $data_app ->setLabel('Data_app:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $revi = new Zend_Form_Element_Text('revi');
        $revi ->setLabel('Revi:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $altro = new Zend_Form_Element_Text('altro');
        $altro ->setLabel('Altro:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $cod_cli1 = new Zend_Form_Element_Text('cod_cli1');
        $cod_cli1 ->setLabel('Codice cliente 1:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $cod_cli2 = new Zend_Form_Element_Text('cod_cli2');
        $cod_cli2 ->setLabel('Codice cliente 2:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $cod_cli3 = new Zend_Form_Element_Text('cod_cli3');
        $cod_cli3 ->setLabel('Codice cliente 3:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $cod_cli4 = new Zend_Form_Element_Text('cod_cli4');
        $cod_cli4 ->setLabel('Codice cliente 4:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $cod_cli5 = new Zend_Form_Element_Text('cod_cli5');
        $cod_cli5 ->setLabel('Codice cliente 5:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $cod_cli6 = new Zend_Form_Element_Text('cod_cli6');
        $cod_cli6 ->setLabel('Codice cliente 6:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $layout = new Zend_Form_Element_Text('layout');
        $layout ->setLabel('Layout:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $tipo_tabella= new Zend_Form_Element_Text('tipo_tabella');
        $tipo_tabella->setLabel('Tipo tabella:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $colore_tabella = new Zend_Form_Element_Text('colore_tabella');
        $colore_tabella ->setLabel('Colore tabella:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $peso_modello = new Zend_Form_Element_Text('peso_modello');
        $peso_modello ->setLabel('Peso modello:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $oe2 = new Zend_Form_Element_Text('oe2');
        $oe2 ->setLabel('Codice Originale 2:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $revi2 	= new Zend_Form_Element_Text('revi2');
        $revi2 	->setLabel('Revi 2:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $cod_cli1_2 = new Zend_Form_Element_Text('cod_cli1_2');
        $cod_cli1_2 ->setLabel('Codice cliente 1_2:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $cod_cli2_2 = new Zend_Form_Element_Text('cod_cli2_2');
        $cod_cli2_2 ->setLabel('Codice cliente 2_2:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $cod_cli3_2 = new Zend_Form_Element_Text('cod_cli3_2');
        $cod_cli3_2 ->setLabel('Codice cliente 3_2:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $cod_cli4_2 = new Zend_Form_Element_Text('cod_cli4_2');
        $cod_cli4_2 ->setLabel('Codice cliente 4_2:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $cod_cli5_2 = new Zend_Form_Element_Text('cod_cli5_2');
        $cod_cli5_2 ->setLabel('Codice cliente 5_2:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $cod_cli6_2 = new Zend_Form_Element_Text('cod_cli6_2');
        $cod_cli6_2 ->setLabel('Codice cliente 6_2:')
              ->setRequired(false)->addFilter('StripTags')
              ->addFilter('StringTrim');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Inserisci');
        
     
        $this->addElements(array($id, $famiglia, $categoria, $marca, $modello, $cod_art_st,
            $cod_pos, $lungo, $indicod, $cod_fornitore, $nome_fornitore, $cod_sx_st, $cod_pos_sx,
            $lungo_sx, $indicod_sx, $cod_fornitore_sx, $nome_fornitore_sx, $descr, $tipo, $tipo_lampada,
            $azionamento, $sottotipo, $colore, $montaggio, $corpo, $con, $senza,  $specchio, $opz_specchio,
            $nota_fine, $opzioni, $misure, $descr_simboli, $extra_testo, $rif_marca, $rif_modello, $new,
            $nnew, $note, $oe, $foto, $foto2, $data_x_new, $data_agg, $data_app, $revi, $altro, $cod_cli1,
            $cod_cli2, $cod_cli3, $cod_cli4, $cod_cli5, $cod_cli6, $layout, $tipo_tabella, $colore_tabella,
            $colore_tabella, $peso_modello, $oe2, $revi2, $cod_cli1_2, $cod_cli2_2, $cod_cli3_2, $cod_cli4_2,
            $cod_cli5_2, $cod_cli6_2, $submit));

          
        
                                    
        $this->addDecorator('FormElements')
         	 ->addDecorator('HtmlTag', array('tag' => 'table', 'style' => 'width:700px'))
         	 ->addDecorator('Form');
                 	 
         	 
        $this->setElementDecorators(array(
            'ViewHelper',
		    'Errors',
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'width' => '490', 'class' => 'element')),
		    array('Label', array('tag' => 'td')),
		    array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
        ));

        // buttons do not need labels
        $submit->setDecorators(array(
            'ViewHelper',
		    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'align'=>'right', 'class' => 'element')),
		    array(array('label' => 'HtmlTag'), array('tag' => 'td', 'placement' => 'prepend')),
		    array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
        ));
    } 
}
