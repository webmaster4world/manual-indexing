<?php

class App_Form_ModuloCalcoloImpiantoFotovoltaicoForm extends Zend_Form {
	public function __construct($options = null) 
    {         
        parent::__construct($options);
        $this->setName('ModuloCatalogoImpiantoFotovoltaico');
        
        $fase1 = new Zend_Form_Element_Hidden('fase1');
        $fase1->setLabel('1) dati anagrafici del cliente');
        
        $azienda_privato = new Zend_Form_Element_Radio('azienda_privato');
        $azienda_privato->setRequired(true)
        		->addMultiOptions(array('privato'=>'privato','azienda'=>'azienda'))
        		->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));	
        
        $azienda = new Zend_Form_Element_Text('azienda');
        $azienda->addFilter('StripTags')
        		->setAttrib('disabled',true)
              ->addFilter('StringTrim');
              
		$nome = new Zend_Form_Element_Text('nome');
        $nome->setLabel('Nome:')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
              
       	$cognome = new Zend_Form_Element_Text('cognome');
       	$cognome->setLabel('Cognome:')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));

             
		$via = new Zend_Form_Element_Text('via');
        $via->setLabel('Via:')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));   
     
        $citta = new Zend_Form_Element_Text('citta');
        $citta->setLabel('Città:')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));    
              
        $cap = new Zend_Form_Element_Text('cap');
        $cap->setLabel('CAP:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('Digits')
              ->setAttrib('size','3')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));      
              
        $provincia = new Zend_Form_Element_Text('provincia');
        $provincia->setLabel('Provincia:')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));        
        
        $telefono = new Zend_Form_Element_Text('telefono');
        $telefono->setLabel('Telefono:')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')           
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;'))); 
              
        $fax = new Zend_Form_Element_Text('fax');
        $fax->setLabel('Fax:')
              //->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim');           
              //->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));       
              
        $mobile = new Zend_Form_Element_Text('mobile');
        $mobile->setLabel('Mobile:')
              //->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim');           
              //->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));       
              
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('E-Mail:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('EmailAddress')         
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));      
              
        $pi = new Zend_Form_Element_Text('pi');
        $pi->setLabel('P.I.:')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')           
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));    
              
        $cf = new Zend_Form_Element_Text('cf');
        $cf->setLabel('C.F.:')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->setDescription('*(SE AZIENDA: indicare anche la ragione sociale giuridica SRL/SPA/etc.)')           
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));       
              
        $fase2 = new Zend_Form_Element_Hidden('fase2');
        $fase2->setLabel('2) Informazioni sullo stabile o area interessata dall\'istallazione');       
              
        $fase2_1 = new Zend_Form_Element_Hidden('fase2_1');
        $fase2_1->setLabel('2.1 Locazione');
              
        $indirizzo_locazione = new Zend_Form_Element_Text('idirizzo_locazione');
        $indirizzo_locazione->setLabel('Indirizzo:')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));      
              
        $citta_locazione = new Zend_Form_Element_Text('citta_locazione');
        $citta_locazione->setLabel('Città:')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));      
              
        $cap_locazione = new Zend_Form_Element_Text('cap_locazione');
        $cap_locazione->setLabel('CAP:')
              ->setRequired(true)->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('Digits')
              ->setAttrib('size','3')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));      
              
        $provincia_locazione = new Zend_Form_Element_Text('provincia_locazione');
        $provincia_locazione->setLabel('Provincia:')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));      
              
        $fase2_2 = new Zend_Form_Element_Hidden('fase2_2');
        $fase2_2->setLabel('2.2 Tipologia');      
              
        $tipologia = new Zend_Form_Element_Radio('tipologia');
        $tipologia->setRequired(true)
        		->addMultiOptions(array('edificio'=>'edificio','tettoia'=>'tettoia','giardino'=>'giardino','altro'=>'altro'))
        		->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));	
        
        $altro = new Zend_Form_Element_Text('altro');
        $altro->addFilter('StripTags')
        		->setAttrib('disabled',true)
              ->addFilter('StringTrim');      
              
        $foto = new Zend_Form_Element_File('foto');
        $foto->addFilter('Rename', getenv('DOCUMENT_ROOT').'/tmp')
        		->addValidator('Extension', false, 'jpg,png,gif,jpeg')
        		 ->addValidator('Size',
					                      false,
					                      array(
					                            'max' => '2MB',
					                            'bytestring' => false));
        	

        $nota_foto = new Zend_Form_Element_Hidden('nota_foto');
        $nota_foto->setLabel('se possibile ALLEGARE FOTO del tetto (Vedi Google Earth).');
        //->setDescription('se possibile ALLEGARE FOTO del tetto (Vedi Google Earth).');     
              
        $fase2_3 = new Zend_Form_Element_Hidden('fase2_3');
        $fase2_3->setLabel('2.3 La costruzione è:');
                
        $tipologia_costruzione = new Zend_Form_Element_Radio('tipologia_costruzione');
        $tipologia_costruzione->setRequired(true)
        		->addMultiOptions(array('in fase di ristrutturazione'=>'in fase di ristrutturazione','Termine lavori(data:)'=>'Termine lavori(data:)',"Esistente(data:)"=>"Esistente(data:)"))
				->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
        		
        $data_costruzione = new Zend_Form_Element_Text('data_costruzione');
        $data_costruzione->addFilter('StripTags')
        		->setAttrib('disabled',true)
              ->setAttrib('size','7')
              ->addFilter('StringTrim');		
        		
        $fase2_4 = new Zend_Form_Element_Hidden('fase2_4');
        $fase2_4->setLabel('2.4 Proprietario dell\'immobile');		

        $proprietario = new Zend_Form_Element_Radio('proprietario');
        $proprietario->setRequired(true)
        		->addMultiOptions(array('si'=>'si','no'=>'no'))
        		->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));		
        		
        $fase2_5 = new Zend_Form_Element_Hidden('fase2_5');
        $fase2_5->setLabel('2.5 Altre osservazione:');		
        		
        $osservazioni = new Zend_Form_Element_Textarea('osservazioni');
        $osservazioni->setAttrib('rows','3')
			->setAttrib('cols','40')
            ->addFilter('StripTags')
            ->addFilter('StringTrim');		

        $fase3 = new Zend_Form_Element_Hidden('fase3');
        $fase3->setLabel('3)Informazioni sulla fornitura elettrica:');	     
            
        $fase3_1 = new Zend_Form_Element_Hidden('fase3_1');
        $fase3_1->setLabel('3.1 L\'allacciamento alla rete elettrica è :');     

       $allacciamento = new Zend_Form_Element_Radio('allacciamento');
       $allacciamento->setRequired(true)
        		->addMultiOptions(array("Attivo"=>"Attivo",'Non ancora attivato'=>'Non ancora attivato', 'Contratto da stipulare '=>'Contratto da stipulare ','Attivo dal'=>'Attivo dal'))
       			->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
        		
       $data_attivo = new Zend_Form_Element_Text('data_attivo');
       $data_attivo->addFilter('StripTags')
       			->setAttrib('disabled',true)
       			->setAttrib('size','7')
              	->addFilter('StringTrim');
              
       $fase3_2 = new Zend_Form_Element_Hidden('fase3_2');
       $fase3_2->setLabel('3.2 Utenze (indicare le principali apparecchiature elettriche collegate alla rete *)');       
              
       $utenze = new Zend_Form_Element_MultiCheckbox('utenze');
       $utenze->setRequired(true)
        		->addMultiOptions(array('Lavastoviglie'=>'Lavastoviglie','Frigorifero'=>'Frigorifero', 'Congelatore'=>'Congelatore', 'Caldaia elettrica'=>'Caldaia elettrica', 'Computer.TV.Radio'=>'Computer.TV.Radio', 'Altro'=>'Altro'))
        		->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));       
              
       $altro_utenze = new Zend_Form_Element_Text('altro_utenze');
       $altro_utenze->addFilter('StripTags')
       			->setAttrib('disabled',true)
              	->addFilter('StringTrim');       

       $fase4 = new Zend_Form_Element_Hidden('fase4');
       $fase4->setLabel('4) Informazioni a cura del tecnico installatore');

       $potenza_fattibile = new Zend_Form_Element_Text('potenza_fattibile');
       $potenza_fattibile->setLabel('POTENZA FATTIBILE KW: ')
       			->addFilter('StripTags')
       			->setAttrib('size','4')
              	->addFilter('StringTrim');
              	
       $fase4_1 = new Zend_Form_Element_Hidden('fase4_1');
       $fase4_1->setLabel('4.1 Caratteristiche della fornitura:');       	
              	
//       $bolletta = new Zend_Form_Element_File('bolletta');
//       $bolletta->setLabel('Gestore distributrice rete locale:')
//       		->addFilter('Rename', getenv('DOCUMENT_ROOT').'/tmp')
//       		->addValidator('Size',
//					                      false,
//					                      array(
//					                            'max' => '2MB',
//					                            'bytestring' => false));
//       
//       $nota_bolletta = new Zend_Form_Element_Hidden('nota_bolletta');
//       $nota_bolletta->setLabel('(Allega Una o più bollette di energia elettrica fotocopia fronte retro, in caso di più file creare un file di archivio come zip,rar,ecc.)'); 	
       		//->setDescription('(Allega Una o più bollette di energia elettrica fotocopia fronte retro, in caso di più file creare un file di archivio come zip,rar,ecc.)');       	
              	
       $potenza_contrattuale = new Zend_Form_Element_Text('potenza_contrattuale');
       $potenza_contrattuale->setLabel('Potenza Contrattuale impegnata in kW*')
       			->addFilter('StripTags')
       			->setAttrib('size','4')
              	->addFilter('StringTrim');      	
              	
       $potenza_disponibile = new Zend_Form_Element_Text('potenza_disponibile');
       $potenza_disponibile->setLabel('Potenza disponibile in kW*')
       			->addFilter('StripTags')
       			->setAttrib('size','4')
              	->addFilter('StringTrim');      	
              	
       $tensione = new Zend_Form_Element_Text('tensione');
       $tensione->setLabel('Tensione*')
       			->addFilter('StripTags')
       			->setAttrib('size','4')
              	->addFilter('StringTrim');      	
              	
       $media_consumi = new Zend_Form_Element_Text('media_consumi');
       $media_consumi->setLabel('Media dei consumi annui in kWh/a (in scatti)*')
       			->addFilter('StripTags')
       			->setAttrib('size','4')
              	->addFilter('StringTrim');       	
              	
       $monotri = new Zend_Form_Element_Radio('monotri');
       $monotri->setRequired(true)
        		->addMultiOptions(array('Monofase'=>'Monofase','Trifase'=>'Trifase'))
        		->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));       	
              	
       $nota_fase4_1 = new Zend_Form_Element_Hidden('nota_fase4_1');
       $nota_fase4_1->setLabel('*Tutti elementi rilevabili dalla bolletta (Sigle per indicare la tensione [BT=BassaTensione[MT=MediaTensione])'); 

       $fase4_2 = new Zend_Form_Element_Hidden('fase4_2');
       $fase4_2->setLabel('4.2 Tipo di superficie su cui si installerà il campo fotovoltaico :');
       
       $tipo_superficie = new Zend_Form_Element_Radio('tipo_superficie');
       $tipo_superficie->setRequired(true)
        		->addMultiOptions(array('TETTO'=>'TETTO','STRUTTURA_frangisole'=>'STRUTTURA "frangisole"', 'STRUTTURA_gazebo'=>'STRUTTURA "gazebo" ', 'Altro'=>'Altro'));
       
       $altro_superficie  = new Zend_Form_Element_Text('altro_superficie');
       $altro_superficie->addFilter('StripTags')
       			->setAttrib('disabled',true)
              	->addFilter('StringTrim');
       
       $fase4_2_tetto = new Zend_Form_Element_Hidden('fase4_2_tetto');
       $fase4_2_tetto->setLabel('Se si tratta di tetto*,quale materiale è stato adottato per la copertura :');
       
       $tipo_tetto = new Zend_Form_Element_Radio('tipo_tetto');
       $tipo_tetto->addMultiOptions(array('Coppi in cotto'=>'Coppi in cotto', 'Tegole di cemento'=>'Tegole di cemento', 'Rame" '=>'Rame', 'Altro'=>'Altro'));
       
       $altro_tetto = new Zend_Form_Element_Text('altro_tetto');
       $altro_tetto->addFilter('StripTags')
       			->setAttrib('disabled',true)
              	->addFilter('StringTrim');
          	
       $fase4_2_eternit = new Zend_Form_Element_Hidden('fase4_2_eternit');
       $fase4_2_eternit->setLabel('Sotto la copertura vi è presenza di "ETERNIT" ?:');
       
       $eternit = new Zend_Form_Element_Radio('eternit');
       $eternit->addMultiOptions(array('si'=>'si', 'no'=>'no'));
       
       $fase4_3 = new Zend_Form_Element_Hidden('fase4_3');
       $fase4_3->setLabel('4.3 L\'area e/o lo stabile in cui verrà realizzato l\'impianto sono soggetti a vincoli speciali (ambientali, paesaggistici, artistici, idrogeologici, parchi, ecc..)?');
       
       $vincoli_ambientali = new Zend_Form_Element_Radio('vincoli_ambientali');
       $vincoli_ambientali->addMultiOptions(array('si'=>'si', 'no'=>'no'))
       		->setRequired(true)
       		->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
       
       $fase4_4 = new Zend_Form_Element_Hidden('fase4_4');
       $fase4_4->setLabel('Qual è l\'inclinazione sull\'orizzontale della superficie dove andranno installati i pannelli? (in gradi)');
       
       $inclinazione = new Zend_Form_Element_Radio('inclinazione');
       $inclinazione->setRequired(true)
       			->addMultiOptions(array('ORIZZONTALE'=>'ORIZZONTALE', '10'=>'10', '20'=>'20', '30'=>'30', '40'=>'40', 'Altro'=>'Altro'))
       			->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
       
       $altro_inclinazione = new Zend_Form_Element_Text('altro_inclinazione');
       $altro_inclinazione->addFilter('StripTags')
       			->setAttrib('disabled',true)
              	->addFilter('StringTrim');			
                    	
       $fase4_4_diverso = new Zend_Form_Element_Hidden('fase4_4_diverso');
       $fase4_4_diverso->setLabel('Se il grado di inclinazione è diverso da zero, indicare l\'orientamento della superficie dove andranno installati i pannelli:');     

       $grado_inclinazione = new Zend_Form_Element_Radio('grado_inclinazione');
       $grado_inclinazione
       			->addMultiOptions(array('SUD'=>'SUD', 'SUD-EST o SUD-OVEST'=>'SUD-EST o SUD-OVEST', 'SUD-SUD-EST o SUD-SUD-OVEST'=>'SUD-SUD-EST o SUD-SUD-OVEST', 'Altro'=>'Altro'))
       			->setRequired(true)
       			->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
       
       $altro_grado = new Zend_Form_Element_Text('altro_grado');
       $altro_grado->addFilter('StripTags')
       			->setAttrib('disabled',true)
              	->addFilter('StringTrim');
       
       $fase4_5 = new Zend_Form_Element_Hidden('fase4_5');
       $fase4_5->setLabel('4.5 Qual è la metratura della superficie (se non si è al corrente delle misure precise dare un indicazione di massima in mq');
       
       $superficie_disponibile = new Zend_Form_Element_Text('superficie_disponibile');
       $superficie_disponibile->setLabel('Superficie totale Disponibile mq:')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty', false, array('messages' => array('isEmpty' => '&uarr; Il campo è obbligatorio &uarr;')));
       
       $fase5 = new Zend_Form_Element_Hidden('fase5');
       $fase5->setLabel('5) Finanziamento sull\'impianto da realizzare');
       
       $fase5_1 = new Zend_Form_Element_Hidden('fase5_1');
       $fase5_1->setLabel('5.1 con Finanziamenti per la realizzazione di impianti fotovoltaici*:
Finanziamento con garanzia fidejussoria rilasciata da primario Istituto Bancario se cliente meritevole.');
       
       $fase5_2 = new Zend_Form_Element_Hidden('fase5_2');
       $fase5_2->setLabel('5.2 senza Finanziamenti:Pagamento Anticipato');
       
       $fase_invio = new Zend_Form_Element_Hidden('fase_invio');
       $fase_invio->setLabel('Invio in allegato la seguente documentazione:');
       
       $fase_invio_1 = new Zend_Form_Element_Hidden('fase_invio_1');
       $fase_invio_1->setLabel('1) Planimetria e/o fotografica del luogo ove s\'intende installare l\'impianto');
		
       $fase_invio_2 = new Zend_Form_Element_Hidden('fase_invio_2');
       $fase_invio_2->setLabel('2) Una o più bollette rappresentative del fornitore di energia elettrica fotocopia fronte retro');
		
       $fase_invio_3 = new Zend_Form_Element_Hidden('fase_invio_3');
       $fase_invio_3->setLabel('TRATTAMENTO DEI DATI PERSONALI');
		
       $fase_invio_4 = new Zend_Form_Element_Hidden('fase_invio_4');
       $fase_invio_4->setLabel('Ai sensi della legge 196 del 30/06/2003 , il sottoscritto fornisce il consenso al trattamento dei dati forniti per esaminare preliminarmente l\'istallazione di un impianto fotovoltaico e ricevere materiale informativo necessario.');

        		
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('INVIA DATI');

        //@todo
        $this->addElements(array($fase1, $azienda_privato, $azienda, $nome, $cognome, $via, $citta, $cap, $provincia, $telefono, $fax, $mobile, $email, $pi, $cf, 
        	$fase2, $fase2_1, $indirizzo_locazione, $citta_locazione, $cap_locazione, $provincia_locazione,
        	$fase2_2, $tipologia, $altro, $foto, $nota_foto,
        	$fase2_3, $tipologia_costruzione, $data_costruzione,
        	$fase2_4, $proprietario, $fase2_5, $osservazioni,
        	$fase3, $fase3_1, $allacciamento, $data_attivo,
        	$fase3_2, $utenze, $altro_utenze,
        	$fase4, $potenza_fattibile, $fase4_1, /*$bolletta, $nota_bolletta,*/ $potenza_contrattuale, $potenza_disponibile, $tensione, $media_consumi, $monotri, $nota_fase4_1,
        	$fase4_2, $tipo_superficie, $altro_superficie, $fase4_2_tetto, $tipo_tetto, $altro_tetto, $fase4_2_eternit, $eternit,
        	$fase4_3, $vincoli_ambientali, $fase4_4, $inclinazione, $altro_inclinazione, $fase4_4_diverso, $grado_inclinazione, $altro_grado,
        	$fase4_5, $superficie_disponibile,
        	$fase5, $fase5_1, $fase5_2, $fase_invio, $fase_invio_1, $fase_invio_2, $fase_invio_3, $fase_invio_4, $submit));
   

        	
        $this->clearDecorators();
        $this->addDecorator('FormElements')
         ->addDecorator('HtmlTag', array('tag' => '<ul>'))
         ->addDecorator('Form');
         $this->setAttrib('enctype', 'multipart/form-data');
        
        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors',array('escape' => false)),
            array('Description'),
            array('Label', array('tag'=>'div', 'class'=>'label-modulo-privati')),
            array('HtmlTag', array('tag' => 'div', 'class'=>'modulo-privati')),
        ));
        
        $fase1->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'class'=>'fase-modulo-privati')),
        ));
        $fase2->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'class'=>'fase-modulo-privati')),
        ));
        $fase3->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'class'=>'fase-modulo-privati')),
        ));
        $fase4->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'class'=>'fase-modulo-privati')),
        ));
        $fase5->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'class'=>'fase-modulo-privati')),
        ));
        $azienda_privato->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'id'=>'azienda_privato', 'class'=>'nolabel-modulo-privati')),
        ));
        $azienda->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'class'=>'nolabel-modulo-privati')),
        ));
        $tipologia->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'id'=>'tipologia', 'class'=>'nolabel-modulo-privati')),
        ));
        $altro->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'class'=>'nolabel-modulo-privati')),
        ));
        $tipologia_costruzione->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'id'=>'tipologia_costruzione', 'class'=>'nolabel-modulo-privati')),
        ));
        $data_costruzione->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'class'=>'nolabel-modulo-privati')),
        ));
        $proprietario->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'id'=>'proprietario', 'class'=>'nolabel-modulo-privati')),
        ));
        $osservazioni->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'class'=>'nolabel-modulo-privati')),
        ));
        $allacciamento->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'id'=>'allacciamento', 'class'=>'nolabel-modulo-privati')),
        ));
        $data_attivo->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'class'=>'nolabel-modulo-privati')),
        ));
        $utenze->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'id'=>'utenze', 'class'=>'nolabel-modulo-privati')),
        ));
        $altro_utenze->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'class'=>'nolabel-modulo-privati')),
        ));
        $monotri->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'class'=>'nolabel-modulo-privati')),
        ));
        $tipo_superficie->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'id'=>'tipo_superficie', 'class'=>'nolabel-modulo-privati')),
        ));
        $altro_superficie->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'class'=>'nolabel-modulo-privati')),
        ));
        $tipo_tetto->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'id'=>'tipo_tetto', 'class'=>'nolabel-modulo-privati')),
        ));
        $altro_tetto->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'class'=>'nolabel-modulo-privati')),
        ));
        $eternit->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'class'=>'nolabel-modulo-privati')),
        ));
        $vincoli_ambientali->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'class'=>'nolabel-modulo-privati')),
        ));
        $inclinazione->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'id'=>'inclinazione', 'class'=>'nolabel-modulo-privati')),
        ));
        $altro_inclinazione->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'class'=>'nolabel-modulo-privati')),
        ));
        $grado_inclinazione->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'id'=>'grado_inclinazione', 'class'=>'nolabel-modulo-privati')),
        ));
        $altro_grado->addDecorators(array(
        	array('HtmlTag', array('tag'=>'div', 'class'=>'nolabel-modulo-privati')),
        ));

        // buttons do not need labels
        $submit->setDecorators(array(
            array('ViewHelper'),
            array('Description'),
            array('HtmlTag', array('tag' => 'li', 'class'=>'submit-group')),
        ));
    } 
}