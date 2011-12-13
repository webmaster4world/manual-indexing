<?php
class Antalis_Service
{
    
    protected $_xml;
    protected $_idoc;
    protected $_tabnam;
    protected $_articlesqualifier;
    
    protected function formatDate($date) {
    
       $dateArr = date_parse_from_format("d.m.y", $date);
       $month = str_pad($dateArr['month'], 2, '00', STR_PAD_LEFT);
       $day = str_pad($dateArr['day'], 2, '00', STR_PAD_LEFT);       
       return $dateArr['year'] . $month . $day;
       
    }
    
    public function __construct()
    {

    }

    
    public function prepareXml() 
    {
        $this->_xml = new SimpleXMLElement($this->returnXmlString());
        
        $this->_tabnam = $this->cVal('tabnam');
        $this->_idoc   = $this->_xml->IDOC;
        $xmlref = $this->_idoc->{$this->_tabnam};
        $xmlref->TABNAM   = $this->_tabnam;
        $xmlref->IDOCTYP  = $this->cVal('idoctyp');
        
        $xmlref->SNDPOR = $this->cVal('sndpor');
        $xmlref->SNDPRN = $this->cVal('sndprn');
        
        $xmlref->RCVPOR   = $this->cVal('rcvpor'); 
        $xmlref->CREDAT   = date("Ymd"); 
        $xmlref->CRETIM   = date("His");
        
        $this->_idoc->E1EDK03[0]->IDDAT = $this->cVal('orderiddat');
          
        $this->_idoc->E1EDK03[0]->DATUM = date("Ymd");  
        $this->_idoc->E1EDK03[0]->UZEIT = date("His");  
        
        $this->_idoc->E1EDK03[1]->IDDAT = $this->cVal('deliveryiddat');  
        
        $xmlref = $this->_idoc->E1EDKA1[0]; 
        $xmlref->PARVW            = $this->cVal('ordererparvw'); 
        $xmlref->PARTN            = $this->cVal('ordererpartn');  
        $xmlref->E1EDKA3->QUALP   = $this->cVal('qualp');  
        $xmlref->E1EDKA3->STDPN   = $this->cVal('stdpn');
          
        $this->_idoc->E1EDKA1[1]->PARVW  = $this->cVal('receiverparvw');
        $this->_idoc->E1EDKA1[1]->PARTN  = $this->cVal('receiverpartn');
        $this->_idoc->E1EDK02->QUALF     = $this->cVal('orderqualf');
        $this->_idoc->E1EDKT1->TDID      = $this->cVal('tdid');
        
        $this->_articlesqualifier = $this->cVal('articlesqualf');        
    }
    
    public function addId($id = '') {
        $this->_idoc->E1EDK02->BELNR = $id;
        $this->_idoc->{$this->_tabnam}->DOCNUM = $id;
    }
    
    public function addUser($userData, $billingAddress, $shippingAddress) {
    
        $billingFullname = $billingAddress->getFirstname() . ' ' . $billingAddress->getLastname();
        $xmlref = $this->_idoc->E1EDKA1[0];
        $xmlref->NAME1 = $billingAddress->getCompany();        
        $xmlref->NAME2 = $billingFullname;        
        $xmlref->STRAS = $billingAddress->getStreet1();
        $xmlref->LAND1 = $billingAddress->getCountry();
        $xmlref->BNAME = $billingFullname;
        
        $shippingFullname = $shippingAddress->getFirstname() . ' ' . $shippingAddress->getLastname();
        $xmlref = $this->_idoc->E1EDKA1[1];
        $xmlref->NAME1 = $shippingAddress->getCompany();        
        $xmlref->NAME2 = $shippingFullname;        
        $xmlref->STRAS = $shippingAddress->getStreet1();        
        $xmlref->ORT01 = $shippingAddress->getCity();        
        $xmlref->PSTLZ = $shippingAddress->getPostcode();        
        $xmlref->LAND1 = $shippingAddress->getCountry();
        $xmlref->BNAME = $shippingFullname;        

        $xmlref->SPRAS_ISO = $userData['locale'];
          
        $this->_idoc->E1EDK03[1]->DATUM = $this->formatDate($userData['requested_date']);

//         if ($shippingAddress->getCountry() != 'CH') {
//             $this->_idoc->E1EDKT1->E1EDKT2[0]->TDLINE = 'EORI-Nummer: ' . $userData['eori_number'];
//         }        
    }
    
    public function addItems($arr = array())
    {        
        $idoc   = $this->_xml->IDOC;
        
        foreach ($arr as $item) {
            
            $newItem = $idoc->addChild('E1EDP01');
            $newItem->addAttribute('SEGMENT', '1');
            $newItem->addChild('POSEX', $item['POSEX']);
            $newItem->addChild('MENGE', $item['MENGE']);
//             $newItem->addChild('MENEE', $item['MENEE']);   // ???
            $newItem->addChild('MENEE', 'ST');  // ???
            
            $newChild = $newItem->addChild('E1EDP02');
            $newChild->addAttribute('SEGMENT', '1');
            
            $newChild = $newItem->addChild('E1EDP19');
            $newChild->addAttribute('SEGMENT', '1');
            $newChild->addChild('QUALF', $this->_articlesqualifier);
            $newChild->addChild('IDTNR', $item['IDTNR']);
            
        }
    }
    
    public function doOrder()
    {    
        try {
            
            $xml     = $this->_xml->asXML(); 
            $server  = $this->getServiceUrl(); 
            Mage::log($server);
            $options = array             
            (                          
                CURLOPT_URL            => $server,            
                CURLOPT_HEADER         => false,               
                CURLOPT_POST           => 1,            
                CURLOPT_RETURNTRANSFER => 1,             
                CURLOPT_POSTFIELDS     => $xml             
            );
            
            $curl = curl_init();
            curl_setopt_array($curl, $options);            
            $result = curl_exec($curl); 
            Mage::log($result);
            if(!curl_errno($curl)){
                $info = curl_getinfo($curl); 
            } else {
				Mage::log('PHP curl error no: ' . curl_error($curl));
                return 'PHP curl error no: ' . curl_error($curl); 
            } 
                        
            curl_close($curl);
                
        } catch (Exception $e) {
            Mage::log(__METHOD__ . PHP_EOL . $e->getMessage());
            return 'Antalis order status: ' .$e->getMessage();
        }
        
        if (isset($info) && ($info['http_code'] > 199) && ($info['http_code'] < 300)) {
			foreach($info as $key => $val) {
				Mage::log($key.": ".$val);
			}
            return 'ok';
        } elseif (isset($info)) {
            Mage::log(__METHOD__ . PHP_EOL . 'Antalis order status: ' . $info['http_code']);
            return 'Antalis order status: ' . $info['http_code'];
        } else {
			Mage::log('Antalis order failed.');
            return 'Antalis order failed.';
        }
    }
    
    protected function getServiceUrl()
    {
        if (!$this->cVal('debug')) {
            $serviceurl = $this->cVal('serviceurl');
        } else {
            $serviceurl = $this->cVal('debugserviceurl'); 
        }
        
        $serviceurl .= '?Supplier=' . $this->cVal('sup') 
                       . '&Document=' . $this->cVal('doc') 
                       . '&sys=' . $this->cVal('sys');
                       
        return $serviceurl;        
    }
    
    protected function cVal($str = '')
    {
        return Mage::getStoreConfig('antalisorder/antalisconf/' . $str);
    }
    
    protected function returnXmlString()
    {
        return <<<XML
<?xml version="1.0" encoding="utf-8"?>
<ORDERS05>
   <IDOC BEGIN="1">
      <EDI_DC40 SEGMENT="1">		
         <TABNAM></TABNAM>
         <MANDT>200</MANDT>
         <DOCNUM></DOCNUM>	
         <DOCREL></DOCREL> 	
         <STATUS></STATUS>
         <DIRECT></DIRECT>
         <OUTMOD></OUTMOD>
         <EXPRSS></EXPRSS>
         <TEST></TEST>	
         <IDOCTYP>ORDERS05</IDOCTYP>	
         <CIMTYP></CIMTYP>
         <MESTYP>ORDERS</MESTYP>
         <MESCOD></MESCOD>
         <MESFCT></MESFCT>
         <STD></STD>
         <STDVRS></STDVRS>
         <STDMES></STDMES>
         <SNDPOR></SNDPOR>	
         <SNDPRT></SNDPRT>
         <SNDPFC></SNDPFC>
         <SNDPRN></SNDPRN>
         <SNDSAD></SNDSAD>
         <SNDLAD></SNDLAD>
         <RCVPOR>ANTALIS</RCVPOR>      	 
         <RCVPRT></RCVPRT>
         <RCVPFC></RCVPFC>
         <RCVPRN></RCVPRN>
         <RCVSAD></RCVSAD>
         <RCVLAD></RCVLAD>
         <CREDAT></CREDAT>	
         <CRETIM></CRETIM>	
         <REFINT></REFINT>
         <REFGRP></REFGRP>
         <REFMES></REFMES>
         <ARCKEY></ARCKEY>
         <SERIAL></SERIAL>
      </EDI_DC40>
      <E1EDK01 SEGMENT="1">
         <CURCY>CHF</CURCY>	
         <VSART></VSART>
         <LIFSK></LIFSK>	
      </E1EDK01>  	
      <E1EDK03 SEGMENT="1">
         <IDDAT>022</IDDAT>	
         <DATUM></DATUM>
         <UZEIT></UZEIT>
      </E1EDK03>
      <E1EDK03 SEGMENT="1">
         <IDDAT>002</IDDAT>	
         <DATUM>20060627</DATUM> 	   
      </E1EDK03>
      <E1EDKA1 SEGMENT="1">
         <PARVW>AG</PARVW>	
         <PARTN>BZFN</PARTN> 	  
         <LIFNR></LIFNR>	
         <NAME1></NAME1>   
         <NAME2></NAME2>  
         <NAME3></NAME3> 	
         <NAME4></NAME4> 	
         <STRAS></STRAS>
         <STRS2></STRS2>
         <PFACH></PFACH>
         <ORT01></ORT01>
         <COUNC> </COUNC>
         <PSTLZ></PSTLZ>
         <PSTL2></PSTL2>
         <LAND1></LAND1>
         <ABLAD></ABLAD>
         <TELF1></TELF1>
         <HAUSN></HAUSN>
         <IHREZ></IHREZ>
         <BNAME>hans muster</BNAME> 		
         <SPRAS_ISO>DE</SPRAS_ISO>
         <E1EDKA3 SEGMENT="1">
            <QUALP>998</QUALP>	
            <STDPN>abc@antalis.ch</STDPN>	
         </E1EDKA3>
      </E1EDKA1>
      <E1EDKA1 SEGMENT="1">
         <PARVW>WE</PARVW>
         <PARTN>BZFN</PARTN>	
         <LIFNR></LIFNR>
         <NAME1></NAME1>
         <NAME2></NAME2>
         <NAME3></NAME3>
         <NAME4></NAME4>
         <STRAS></STRAS>
         <STRS2></STRS2>
         <PFACH></PFACH>
         <ORT01></ORT01>
         <COUNC></COUNC>
         <PSTLZ></PSTLZ>
         <PSTL2></PSTL2>
         <LAND1></LAND1>
         <ABLAD></ABLAD>
         <TELF1></TELF1>
         <HAUSN></HAUSN>
         <IHREZ></IHREZ>
         <BNAME></BNAME>
         <SPRAS_ISO>DE</SPRAS_ISO>
      </E1EDKA1>
      <E1EDK02 SEGMENT="1">
         <QUALF>001</QUALF>	
         <BELNR></BELNR>
      </E1EDK02>
      <E1EDKT1 SEGMENT="1">
         <TDID></TDID>
         <TSSPRAS_ISO>DE</TSSPRAS_ISO>
         <E1EDKT2 SEGMENT="1">
            <TDLINE></TDLINE>
         </E1EDKT2>
      </E1EDKT1>
   </IDOC>
</ORDERS05>
XML;
    }
}