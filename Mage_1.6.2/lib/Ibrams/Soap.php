<?php

/**
 * misystems Ibrams SOAP
 *
 * LICENSE
 *
 * This source file is subject to Misystems-Ibrams-FTP interface that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.misystems.ch/license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@misystems.ch so we can send you a copy immediately.
 *
 * @category   SOAP
 * @package    SOAP
 * @copyright  Copyright (c) 2010-2015 Misystems Lmtd Switzerland
 * @license    http://misystems.ch/license/
 * @version    $Id: 1.1
 * @author     Dominik Wyss, Peter B�thig
 * @date       11.09.2010
 */

/**
 * @desc contains ibrams SOAP methods
 */
class Ibrams_Soap {

    /**
     *
     * @var string
     */
    private $ibramsURL;
    /**
     * @var array
     */
    private $CONFIG;
    /**
     * @var int
     */
    private $documentid;
    /**
     *  sessionid for soap calls
     */
    private $soapsession = false;
    /**
     * @var string 
     */
    private $usersession = false;
    /**
     * @var string
     */
    private $importsession = false;
    /**
     * @var string
     */
    private $soapuser = false;
    /**
     *
     * @var callbacks for dynamicly call getter methods on listiteration in addresses
     */
    private $_callBacks = array('Street' => 'getStreet1', 'Pobox' => 'getStreet2', 'Company_ext' => 'getStreet3', 'Email' => 'getStreet4', 'Website' => 'getStreet5');


    public function __construct() {
        $this->init();
    }

    public function changeLanguage($locale) {
        $userclient = getSoapClient('user');
        /** user get */
        $user = makeSoapCall($userclient, 'user_get', array('login' => $this->soapuser, 'sessionid' => $this->usersession));
        $user->item->language = $locale;
        $user = makeSoapCall($userclient, 'user_set', array($user, 'sessionid' => $this->usersession));
        $this->usersession = $this->startSession(Mage::getModel('customer/session')->getSoapUser(), '', $this->usersession, $locale);
        return $this->usersession;
    }

    /**
     * Starts a new iBrams session
     *
     * @param string $userlogin
     * @param string $password
     * @param string $sessionid
     * @param string $language
     * @return string sessionid
     */
    public function startSession($userlogin, $password, $sessionid = '', $language = '') {
        $sessionclient = getSoapClient('session');
        $res = makeSoapCall($sessionclient, 'session_startsession', array('userlogin' => $userlogin, 'password' => $password, 'sessionid' => $sessionid, 'language' => $language));
        if ($language != '')
            Mage::log("Started new session with userlogin:$userlogin, locale:$language. new sessionid:$res");
        return $res;
    }

    /**
     *
     *  
     */
    private function init($user = 'cdo_admin') {

        $iBramsConf = Mage::getStoreConfig('ibramsproduct/ibramssaopconf');

        $this->iBramsURL = $iBramsConf['ibramsurl'];

        $this->CONFIG = new Zend_Config(array('soap_service_baseurl' => $this->iBramsURL,
                    'session_userlogin'=>$iBramsConf['ibramsuser'],
                    'session_password'=>'admin.00cdo',
                    'media_foldername' => 'Fototheke',
                    'templates_categories' => false));
        Zend_Registry::set('soapconfig', $this->CONFIG);

      



// Mage::log(__CLASS__ . '/' . __METHOD__ . PHP_EOL . 'before SOAP');     
        $this->soapsession = $this->getSessionID($this->CONFIG->session_userlogin, $this->CONFIG->session_password);

      


// Mage::log(__CLASS__ . '/' . __METHOD__ . PHP_EOL . 'after SOAP'); 
        if (Mage::getModel('customer/session')->getSoapUser()) {
            $this->soapuser = Mage::getModel('customer/session')->getSoapUser();
            $this->usersession = $this->getSessionID($this->soapuser);
        }
        Mage::log("admin soapsession:" . $this->soapsession . "\nuser soapsession:" . $this->usersession . "\nMagentoSession SoapUser:" . Mage::getModel('customer/session')->getSoapUser());
    }

    public function getSearchFields() {
        $mediaclient = getSoapClient('media');
        $searchlist = makeSoapCall($mediaclient, 'media_getAvailableSearchFields', array('sessionid' => $this->soapsession));
        return $searchlist;
    }

    public function searchMedia($searchlist) {
        $mediaclient = getSoapClient('media');
        $searchresult = makeSoapCall($mediaclient, 'media_search',
                        array('searchlist' => $searchlist, 'sessionid' => $this->soapsession));
        return $searchresult;
    }

    public function getMediaList() {
        $mediaclient = getSoapClient('media');

        try {
            $medialist = makeSoapCall($mediaclient, 'media_getMediaList',
                            array('foldername ' => '06_Shop-Artikel', 'sessionid' => $this->soapsession, 'nometadata' => false));
        } catch (Exception $e) {
            Mage::log($e->getMessage());
        }
        return $medialist;
    }

    /**
     * Gets a list of all available templates, editable by current user.
     * Set category to limit search.
     *
     * @param string $category
     * @return mixed
     *
     */
    public function getTemplateList($categories = '') {
        $this->templatesession = $this->getSessionID('soap');
        $templateclient = getSoapClient('template');
        $templatelist = makeSoapCall($templateclient, 'template_getTemplateList', array('categories' => $categories, 'sessionid' => $this->templatesession));
        return $templatelist;
    }

    public function editdocumentURL($templateid, $title, $description, $checkouturl, $cancelurl, $lSave = 'In den Warenkorb', $lPrev = 'Vorschau') {
        $documentclient = getSoapClient('document');
        $lang = Mage::getModel('customer/session')->getUserLocale();
        /** document create */
        $ticket = makeSoapCall($documentclient, 'document_create',
                        array('templateid' => $templateid, 'title' => $title,
                            'description' => $description, 'sessionid' => $this->usersession,
                            'language' => $lang,
                ));

        $ticketclient = getSoapClient('ticket');

        /** ticket getStatus */
        $status = $this->_checkTicket($ticket, $this->usersession);

        if ($status == 'READY') {
            /** ticket getResult */
            $ticketresult = makeSoapCall($ticketclient, 'ticket_getResult',
                            array('ticketlist' => array($ticket->id), 'sessionid' => $this->usersession));
        } else {
            // Mage::log($status . 'ticketresult');
            return false;
        }
        $documentid = $ticketresult->item->result;
        $this->documentid = $documentid;
        $checkouturlArr = explode('?', $checkouturl);
        /** save GET vars after ? for returnurl */
        $checkoutend = (count($checkouturlArr) > 1) ? '?' . $checkouturlArr[1] : '';
        $res = $this->startSession(Mage::getModel('customer/session')->getSoapUser(), '', $this->usersession, $lang);
        /** document getEditUrl */
        $documentEditUrl = makeSoapCall($documentclient, 'document_getEditUrl', array(
                    'documentid' => $documentid,
                    'optionlist' => array(
                        array('identifier' => 'RETURN_URL',
                            'value' => $checkouturlArr[0] . 'documentid/' . $documentid . '/' . $checkoutend),
                        array('identifier' => 'CANCEL_URL',
                            'value' => $cancelurl),
                        array('identifier' => 'CANCEL_TARGET', 'value' => 'top'),
                        array('identifier' => 'RETURN_TARGET', 'value' => 'top'),
                        array('identifier' => 'HIDE_ACTION_PDFPREVIEW', 'value' => '1'),
                        array('identifier' => 'HIDE_ACTION_FINISHEDITING', 'value' => '1'),
                        array('identifier' => 'HIDE_ACTION_CORRECTIONSREQUIRED', 'value' => '1'),
                        array('identifier' => 'HIDE_TAB_PROPERTIES', 'value' => '1'),
                        array('identifier' => 'HIDE_TAB_HISTORY', 'value' => '1'),
                        array('identifier' => 'LABEL_ACTION_SAVE', 'value' => $lSave),
                        array('identifier' => 'LABEL_ACTION_PREVIEW', 'value' => $lPrev),
                    ),
                    'sessionid' => $this->usersession
                        )
        );
        return $this->CONFIG->soap_service_baseurl . $documentEditUrl;
    }

    public function reeditdocumentURL($documentid, $title, $description, $checkouturl, $cancelurl) {
        $documentclient = getSoapClient('document');
        /** document getEditUrl */
        $documentEditUrl = makeSoapCall($documentclient, 'document_getEditUrl', array(
                    'documentid' => $documentid,
                    'optionlist' => array(
                        array('identifier' => 'RETURN_URL', 'value' => $checkouturl),
                        array('identifier' => 'CANCEL_URL', 'value' => $cancelurl),
                        array('identifier' => 'CANCEL_TARGET', 'value' => 'top'),
                        array('identifier' => 'RETURN_TARGET', 'value' => 'top'),
                        array('identifier' => 'HIDE_ACTION_PDFPREVIEW', 'value' => '1'),
                        array('identifier' => 'HIDE_ACTION_FINISHEDITING', 'value' => '1'),
                        array('identifier' => 'HIDE_ACTION_CORRECTIONSREQUIRED', 'value' => '1'),
                        array('identifier' => 'HIDE_TAB_PROPERTIES', 'value' => '1'),
                        array('identifier' => 'HIDE_TAB_HISTORY', 'value' => '1'),
                        array('identifier' => 'LABEL_ACTION_SAVE', 'value' => 'In den Warenkorb'),
                        array('identifier' => 'LABEL_ACTION_PREVIEW', 'value' => 'Vorschau'),
                    ),
                    'sessionid' => $this->usersession
                        )
        );

        return $this->CONFIG->soap_service_baseurl . $documentEditUrl;
    }

    /**
     * produce document by given document id
     *
     * @param string $_documentid
     * @return mixed
     *
     */
    public function produceDocument($_documentid) {
        $documentid = (int) $_documentid;
        if (!is_numeric($documentid)) {
//         Mage::log('No or no valid documentid given!');
        }
        $documentclient = getSoapClient('document');

        $ticket = makeSoapCall($documentclient, 'document_produce', array('documentid' => $documentid, 'sessionid' => $this->usersession));
        /** check status of ticket */
        $status = $this->_checkTicket($ticket, $this->usersession); //
        $ticketclient = getSoapClient('ticket');

        if ($status == 'READY') {
            /** ticket getResult */
            $ticketresult = makeSoapCall($ticketclient, 'ticket_getResult',
                            array('ticketlist' => array($ticket->id), 'sessionid' => $this->usersession));
        } else {
//  		  Mage::log("lib soap: not ready yet!-->");      
        }

        $orderid = $ticketresult->item->result;
//     Mage::log($orderid, 'orderid');    
        return $orderid;
    }

    /**
     * create document  preview  by given document id
     *
     *  @param string $_documentid
     *  @param int $_page
     *  @param int $_width
     *  @return mixed
     *
     */
    public function createPreview($documentid, $page = 1, $width = 75) {
        $documentclient = getSoapClient('document');
        $ticket = makeSoapCall($documentclient, 'document_createPreview', array('documentid' => $documentid, 'page' => $page, 'width' => $width, 'sessionid' => $this->usersession));
        // check status of ticket
        $status = $this->_checkTicket($ticket, $this->usersession); //
        $ticketclient = getSoapClient('ticket');
        if ($status == 'READY') {
            // ticket getResult
            $ticketresult = makeSoapCall($ticketclient, 'ticket_getResult',
                            array('ticketlist' => array($ticket->id), 'sessionid' => $this->usersession));
        } else {
            return false;
        }
        $prevpath = $ticketresult->item->result;
        return $this->iBramsURL . $prevpath;
    }

    /**
     * create PDF from document by given document id
     *
     * @param string $_documentid
     * @return mixed
     *
     */
    public function createPDF($documentid, $user, $quality = 'Preview') {
        $this->usersession = $this->getSessionID($user);
        $documentclient = getSoapClient('document');
        $ticket = makeSoapCall($documentclient, 'document_createPDF', array('documentid' => $documentid, 'quality' => $quality, 'sessionid' => $this->usersession));

        // check status of ticket
        $status = $this->_checkTicket($ticket, $this->usersession); //
        $ticketclient = getSoapClient('ticket');
        if ($status == 'READY') {
            // ticket getResult
            $ticketresult = makeSoapCall($ticketclient, 'ticket_getResult',
                            array('ticketlist' => array($ticket->id), 'sessionid' => $this->usersession));
        } else {
            return false;
        }
        return $ticketresult;
    }

    public function getAvailablePdfStyles() {
        $this->usersession = $this->getSessionID('DWYSS');
        $documentclient = getSoapClient('document');
        $styles = makeSoapCall($documentclient, 'document_getAvailablePdfStyles', array('sessionid' => $this->usersession));
//     	var_dump($styles);
        Mage::log('pdfStyles' . serialize($styles));
//     	exit;
    }

    public function getOrderID($documentid) {
        $documentclient = getSoapClient('document');
        $orderid = makeSoapCall($documentclient, 'document_getOrderID', array('documentid' => $documentid, 'sessionid' => $this->usersession));
        return $orderid;
    }

    /** order setorderinfo */
    public function setOrderInfo($documentid, $orderinfo) {
        $documentclient = getSoapClient('document');
        $document = makeSoapCall($documentclient, 'document_get', array('documentidlist' => array('item' => $documentid), 'sessionid' => $this->usersession, 'nometadata' => false));
        if ($metadataArr = $document->item->metadatalist->item) {
            foreach ($metadataArr as $k => $value) {
                $newval = $orderinfo[$document->item->metadatalist->item[$k]->identifier];
                $document->item->metadatalist->item[$k]->value = (is_array($newval)) ? implode('<br />', $newval) : $newval;
            }
            try {
                $res = makeSoapCall($documentclient, 'document_set', array('documentlist' => array('item' => $document->item), 'sessionid' => $this->usersession));
            } catch (Exception $e) {
                Mage::log($e->getMessage);
            }
        } else {
            Mage::log("error metadatalist: " . serialize($document->item->metadatalist));
        }
        return 'ok';
    }

    /** order addorderaddress
     *
     *
     *
     */
    public function addOrderAddress($documentid, $addresslist) {
        $documentclient = getSoapClient('document');
        try {
            $addorderaddress = makeSoapCall($documentclient, 'document_addAddressList', array('documentid' => $documentid, 'addresslist' => $addresslist, 'sessionid' => $this->usersession));
            return $addorderaddress;
        } catch (Exception $e) {

        }
    }

    /** list order addresstypes */
    public function listOrderAddressTypes() {
        $orderclient = getSoapClient('order');
        $orderaddresstypes = makeSoapCall($orderclient, 'order_getOrderAddressTypelist', array('sessionid' => $this->usersession));
        // Zend_Debug::dump($orderaddresstypes, 'orderaddresstypes');
    }

    /**
     * Short description for function
     *
     * Long description for function (if any)...
     *
     * @throws EXCEPTION
     * @param string $ticket
     * @returns string
     */
    private function _checkTicket($ticket, $soapsession) {
        if ($ticket !== false) {
            $ticketclient = getSoapClient('ticket');
            for ($i = 0; $i < 45; $i++) {
                $ticketstatus = makeSoapCall($ticketclient, 'ticket_getStatus',
                                array('ticketid' => $ticket->id, 'sessionid' => $soapsession));
                if ($ticketstatus == 'READY') {
                    return $ticketstatus;
                    break;
                }
                //Zend_Debug::dump($ticketstatus, 'ticketstatus '.date('h:m:i'));
                sleep(2);
            }
        }
        return false;
    }

    /**
     * get a valid session id from session service
     *
     * @param string $sUserLogin
     * @param string $sUserPassword
     * @return mixed
     *
     */
    private function getSessionID($sUserLogin, $sUserPassword = '') {

        $sSessionID = false;
        $sessionclient = getSoapClient('session');

     

        $sessionlist = makeSoapCall($sessionclient, 'session_getSessionList', array('userlogin' => $sUserLogin));



        /** no active session found -> start a new one */
        if ($sessionlist === false) {
            $sSessionID = makeSoapCall($sessionclient, 'session_startSession', array('userlogin' => $sUserLogin, 'password' => $sUserPassword));
    
        } else if (is_string($sessionlist->item)) {



            $sSessionID = $sessionlist->item;
        } else if ($sessionlist->item[0]) {
            $sSessionID = $sessionlist->item[0];
        }

        if ($sSessionID == false) {
            die("no soapsession in ".__METHOD__);
        }


        return $sSessionID;
    }

    public function getUser($sLogin) {

        $userclient = getSoapClient('user');

        $user = makeSoapCall($userclient, 'user_get', array('login' =>$sLogin, 'sessionid' =>$this->soapsession));
        return $user;
    }

    public function setUser($user) {
        $userclient = getSoapClient('user');
        $userres = makeSoapCall($userclient, 'user_set', array('user' => $user, 'sessionid' =>$this->soapsession));
        //return $userres;
        //Zend_Loader::loadClass('Zend_Debug');
        //Zend_Debug::dump($user, 'user');
    }

    /**
     * @param string $sLogin
     * @param magentoaddress $address
     * @param addresstype $type
     * @return userid
     */
    public function setAddress($sLogin, $address, $type = 'MT') {

        $user = $this->getUser($sLogin);

        foreach ($user->item->metadata->item as $item) {

            $m_identifier = $type . "_" . $item->identifier;

            if ($m_identifier == $item->identifier) {

                if ($this->_callBacks[$identifier] != "") {
                    $method_name = $this->_callBacks[$identifier];
                } else {
                    $method_name = "get" . $identifier();
                }

                //call getter if exists
                if (method_exists($address, $method_name)) {
                    $item->value = $address->$method_name;
                }
            }
        }

        //return userid
        return $this->setUser($user);
    }

    public function createFZinfo($fzData, $cinputs, $noenergie = false) {
        $session = $this->soapsession;
        $templateclient = getSoapClient('template');
        $documentclient = getSoapClient('document');
        $sessionclient = getSoapClient('session');
        $ticketclient = getSoapClient('ticket');
        if ($noenergie == false) {
            $eObj = $fzData[1];
            $co2val = (strlen($eObj->co2) < 3) ? '0' . $eObj->co2 : $eObj->co2;
            $eArr = array(
                'Marke' => $eObj->markeBez,
                'Typ' => $eObj->typBez,
                'Treibstoff' => $eObj->treibstoff,
                'Getriebeart' => $eObj->getriebeart,
                'Leergewicht' => $eObj->leergewicht,
                'Verbrauch' => $eObj->verbrauch,
                'CO2' => $eObj->co2,
                'Emissionsbild' => 'CO2_' . $co2val . '.eps',
                'Kategoriebild' => 'Kategorie' . $eObj->energieeffizienz . '.eps'
            );

            $iSessionArr = array();
            foreach ($eArr as $item => $value) {
                $eArr[$item] = urldecode($value);
                //         Mage::log("$item: " . urldecode($value));
                $res = makeSoapCall($sessionclient, 'session_setValue', array($session, $item, urldecode($value)));
            }
//     $res = makeSoapCall($sessionclient, 'session_setValueList', array($session, $iSessionArr));
//     exit;
        }

        $dObj = $fzData[0];
        $dArr = array(
            'Typencode' => $dObj->markeBezeichnung,
            'Modell' => $dObj->basisModellInfo->modellBezeichnung, // modellBezeichnung
            'Motor' => $dObj->basisModellInfo->motorBezeichnung,
            'Getriebe' => $dObj->basisModellInfo->getriebeBezeichnung,
            'Bauart' => $dObj->basisModellInfo->motorBauart,
            'LeistungKw' => $dObj->basisModellInfo->leistungKw,
            'LeistungPs' => $dObj->basisModellInfo->leistungPs,
            'Basismodell' => $dObj->basisModellInfo->modellBezeichnung,
            'PreisModellBasis' => setQuotes($dObj->basisModellInfo->grundpreis),
            'ModellAM' => $dObj->modellInfo->modellLinie, // modellBezeichnung
            'MotorAM' => $dObj->modellInfo->motorBezeichnung,
            'GetriebeAM' => $dObj->modellInfo->getriebeBezeichnung,
            'BauartAM' => $dObj->modellInfo->motorBauart,
            'LeistungKwAM' => $dObj->modellInfo->leistungKw,
            'LeistungPsAM' => $dObj->modellInfo->leistungPs,
            'PreisModellAM' => setQuotes($dObj->modellInfo->grundpreis)
        );
        $i = 1;
        if (isset($dObj->iconInfos->array) && is_array($dObj->iconInfos->array)) {
            foreach ($dObj->iconInfos->array as $item) {
                $dArr['Picto' . $i] = str_replace(' ', '', $item->iconName) . '_' . strtolower($cinputs['lang']) . '.psd';
                $i++;
            }
        }
        $i = 1;
        $nStr = '';
        $preisam = $dObj->modellInfo->grundpreis;
        if (!isset($dObj->highlights->array))
            $dObj->highlights->array = array();
        foreach ($dObj->highlights->array as $item) {
            $nStr = leadingZero($i);
            $fussnote = ($item->fussnote) ? '|' . $item->fussnote : '';
            $dArr['Ausstattung' . $nStr] = $item->text . $fussnote;
            $i++;
        }
        while ($i < 21) {
            $nStr = leadingZero($i);
            $dArr['Ausstattung' . $nStr] = 'KEINE';
            $i++;
        }
        $i = 1;

        if (!isset($dObj->options->array))
            $dObj->options->array = array();
        if (!is_array($dObj->options->array))
            $dObj->options->array = array($dObj->options->array);

        foreach ($dObj->options->array as $item) {
            $nStr = leadingZero($i);
            $fussnote = ($item->fussnote) ? '|' . $item->fussnote : '';
            $dArr['Option' . $nStr] = $item->bezeichnung . $fussnote;
            $dArr['PreisOp' . $nStr] = setQuotes($item->preis);
            $preisam = $preisam + $item->preis;
            $i++;
        }
        for ($a = 1; $a < 5; $a++) {
            $nStr = leadingZero($i);
            if ($cinputs['opt' . $a] == '') {
                continue;
            }
            $dArr['Option' . $nStr] = $cinputs['opt' . $a];
            if ($cinputs['val' . $a] != '') {
                $dArr['PreisOp' . $nStr] = setQuotes((int) $cinputs['val' . $a]);
                try {
                    $preisam = $preisam + (int) $cinputs['val' . $a];
                } catch (Exception $e) {

                }
            }
            $i++;
        }

//     while ($i < 21) {
//         $nStr = leadingZero($i);
//         $dArr['Option' . $nStr] = 'KEINE';
//         $i++;
//     } 
        /**
         *    missing in Obj: PreisLieferung, PreisLeasing
         *    $dArr['PreisLieferung'] = ???;
         *    $dArr['PreisLeasing'] = ???;
         */
        if ((isset($cinputs['auslieferung'])) && ($cinputs['auslieferung'] !== '')) {
            $dArr['Ablieferungspauschale'] = $cinputs['Ablieferungspauschale'];
            $dArr['PreisLieferung'] = 'CHF ' . setQuotes($cinputs['auslieferung']);
            try {
                $preisam = $preisam + (int) $cinputs['auslieferung'];
            } catch (Exception $e) {

            }
        }
        $dArr['PreisTotal'] = setQuotes($preisam);
        $dArr['Währung'] = 'CHF';
        if ((isset($cinputs['leasing_monatlich'])) && ($cinputs['leasing_monatlich'] !== '')) {
            $dArr['St'] = '*';
            $dArr['PreisLeasing'] = 'CHF ' . setQuotes($cinputs['leasing_monatlich']);
            $dArr['MonateLeasing'] = $cinputs['leasing_dauer'];
            $dArr['KmMaxLeasing'] = $cinputs['leasing_kilometer'];
            $dArr['RestwertLeasing'] = setQuotes($cinputs['leasing_restwert']);
            $dArr['SonderLeasing'] = setQuotes($cinputs['leasing_anzahlung']);
            $dArr['ZsLeasing'] = $cinputs['leasing_zinssatz'];
            $dArr['ZinsEffLeasing'] = $cinputs['leasing_jahreszins'];
            $dArr['Leasing'] = $cinputs['Leasing'];
            $dArr['Leasingtext'] = $cinputs['Leasingtext'];
//         $dArr['Leasing'] = 'Leasing ' . $cinputs['leasing_dauer'] . ' Monate, ' . $cinputs['leasing_zinssatz'] . '%, bereits ab ';
//         $dArr['Leasingtext'] = 'Leasingzins CHF ' . setQuotes(number_format($cinputs['leasing_monatlich'], 2, '.', '')) . ' mtl., inkl. MwSt. bei ' 
//                               . $cinputs['leasing_dauer'] . ' Monaten Laufzeit und ' . $cinputs['leasing_kilometer']
//                               . 'km/Jahr. Sonderzahlung CHF ' . setQuotes(number_format($cinputs['leasing_anzahlung'], 2, '.', '')) 
//                               . ', Restwert CHF ' . setQuotes(number_format($cinputs['leasing_restwert'], 2, '.', '')) 
//                               . ', Kaution vom Finanzierungsbetrag 5% (mindestens CHF 1000.-), effektiver Jahreszins '
//                               . $cinputs['leasing_jahreszins'] . '%, Vollkaskoversicherung obligatorisch. Weitere Berechnungsvarianten auf Anfrage. '
//                               . 'Eine Leasingvergabe wird nicht gewährt, falls Sie zur Überschuldung des Konsumenten führt.';
        }
//     var_dump($dArr);
//     exit;

        foreach ($dArr as $item => $value) {
//         Mage::log("$item: " . serialize($value));
//         $dArr[$item] = urldecode($value);
            $res = makeSoapCall($sessionclient, 'session_setValue', array($session, $item, urldecode($value)));
        }
//     $res = makeSoapCall($sessionclient, 'session_setValueList', array($session, $dArr));
        $lang = 'de_DE';
        switch ($cinputs['lang']) {
            case 'DE':
                $lang = 'de_DE';
                break;
            case 'FR':
                $lang = 'fr_FR';
                break;
            case 'IT':
                $lang = 'it_IT';
        }
        $templatelist = makeSoapCall($templateclient, 'template_getTemplateList', array('categories' => '', 'sessionid' => $session));
        if (is_object($templatelist) && is_array($templatelist->item)) {
            $templatename = ($noenergie) ? 'Preisblatt_Toyota_' : 'Preisblatt_energieEtikette_Toyota_';
            foreach ($templatelist->item as $template) {
                if ($template->title == $templatename . $cinputs['lang']) {
                    $templateid = $template->id;
                }
            }
        }
        if (!isset($templateid)) {
            Mage::log('no templateid found for name: ' . $templatename . $cinputs['lang']);
            return false;
        }
        $ticket = makeSoapCall($documentclient, 'document_create',
                        array('templateid' => $templateid, 'title' => '',
                            'description' => '', 'sessionid' => $session,
                            'language' => $lang
                ));
        /** ticket getStatus */
        $status = $this->_checkTicket($ticket, $session);
        if ($status == 'READY') {
            /** ticket getResult */
            $ticketresult = makeSoapCall($ticketclient, 'ticket_getResult',
                            array('ticketlist' => array($ticket->id), 'sessionid' => $session));
            //Zend_Debug::dump($ticketresult, 'ticketresult');
        } else {
            // Mage::log($status . 'ticketresult');
            return false;
        }
        $documentid = $ticketresult->item->result;
        $ticket = makeSoapCall($documentclient, 'document_createPDF', array('documentid' => $documentid, 'quality' => 'Preview', 'sessionid' => $session));
        // check status of ticket
        $status = $this->_checkTicket($ticket, $session); //
        if ($status == 'READY') {
            // ticket getResult
            $ticketresult = makeSoapCall($ticketclient, 'ticket_getResult',
                            array('ticketlist' => array($ticket->id), 'sessionid' => $session));
        } else {
            return false;
        }
//     $res = makeSoapCall($sessionclient, 'session_unsetValueList', array($session, array_merge($eArr, $dArr))); 
        if (!$noenergie) {
            foreach ($eArr as $item => $value) {
                $res = makeSoapCall($sessionclient, 'session_unsetValue', array($session, $item));
            }
        }
        foreach ($dArr as $item => $value) {
            $res = makeSoapCall($sessionclient, 'session_unsetValue', array($session, $item));
        }
        return $ticketresult;
    }

    public function getExportFileformatList() {
        $this->soapsession = $this->getSessionID('soap');
        $mediaclient = getSoapClient('media');
        $res = makeSoapCall($mediaclient, 'media_getExportFileformatList', array('sessionid' => $this->soapsession));
        //var_dump($res);
    }

    public function downloadmedia($aMediaIDs, $aMediaNames, $sPath, $dpi = 72, $width = '400', $height = '400') {
//       echo '<br />ids: ' . implode(',', $aMediaIDs);
//       echo '<br />names: ' . implode(',', $aMediaNames) . '<br />';

        $mediaclient = getSoapClient('media');
        foreach ($aMediaIDs as $id) {
            $ticket = makeSoapCall($mediaclient, 'media_export', array('medialist' => array($id),
                        'mediaexportsetting' => array(
                            'fileformat' => 'jpg',
                            'dpi' => $dpi,
                            'width' => $width,
                            'height' => $height,
                            'colorspace' => 'RGB'
                        ), 'sessionid' => $this->soapsession
                            )
            );
            $status = false;
            if ($ticket) {
                $status = $this->_checkTicket($ticket->item, $this->soapsession);
            } else {
                Mage::log('Could not export id: ' . $id);
            }
            /** check status of ticket */
            if ($status == 'READY') {
                /** ticket getResult */
                $ticketclient = getSoapClient('ticket');
                $ticketresult = makeSoapCall($ticketclient, 'ticket_getResult', array('ticketlist' => array($ticket->item->id), 'sessionid' => $this->soapsession));
                $filePath = $ticketresult->item->result;
                /** store media in fs * */
                $sFilePath = $this->CONFIG->soap_service_baseurl . $filePath;

                if ($fp = fopen($sFilePath, 'rb')) {
                    $fpx = fopen($sPath . $aMediaNames[$id], 'wb');
                    while (!feof($fp)) {
                        fwrite($fpx, fread($fp, 1024 * 8), 1024 * 8);
                    }
                    fclose($fp);
                    fclose($fpx);
                } else {
                    Mage::log('failed opening ' . $sFilePath);
                }
            }
        }
    }

    public function clearFilecache() {
        $filecacheclient = getSoapClient('filecache');
        return makeSoapCall($filecacheclient, 'filecache_clear', array('sessionid' => $this->soapsession));
    }

    public function get_callBacks() {
        return $this->_callBacks;
    }

    public function set_callBacks($_callBacks) {
        $this->_callBacks = $_callBacks;
    }

    public function getConfig() {
        return $this->CONFIG;
    }

    public function getDocumentId() {
        return $this->documentid;
    }

    public function getIbramsUrl() {
        return $this->iBramsURL;
    }

}

/**
 * Get a configured SOAP Client for a specific iBrams SOAP-Service
 *
 * @param string $sType
 * @return soap client
 */
function getSoapClient($sType) {

    if (!Zend_Registry::isRegistered('soapclient_' . $sType)) {
        $soapconfig = Zend_Registry::get('soapconfig');

        return new SoapClient($soapconfig->soap_service_baseurl . 'service/' . $sType . '.php?style=rpc_encoded',
                array('trace' => 1,
                    'exceptions' => 1,
                    'login' => $soapconfig->session_userlogin,
                    'password' => $soapconfig->session_password
        ));

        Zend_Registry::set('soapclient_' . $sType, 'xx');
    }

    return Zend_Registry::get('soapclient_' . $sType);
}

/**
 * make a call to iBrams SOAP-Services
 *
 * @param object $soapclient
 * @param string $soapfunction
 * @param array $parameters
 * @return object
 */
function makeSoapCall($soapclient, $soapfunction, $parameters) {
    try {
        $res = $soapclient->__soapCall($soapfunction, $parameters);
//     Mage::log("--->Last Request($soapfunction)\n" . $soapclient->__getLastRequest());
//     Mage::log("--->Last Response($soapfunction)\n" . $soapclient->__getLastResponse());
        return $res;
    } catch (SoapFault $e) {
        Mage::log("-------->Error @ Last Request\n" . $soapclient->__getLastRequest());
        Mage::log("-------->Error @ Last Response\n" . $soapclient->__getLastResponse());
        // Mage::log(get_class($e).': '.$e->getMessage());
        Mage::log(get_class($e) . ' ---------------> message:' . $e->getMessage());
        return false;
    }
}

// function setQuotes($str) {
// //     return strrev(substr(chunk_split(strrev($str), 3, "'"), 0, -1));
//     return $str;
// }

function leadingZero($n) {
    return ($n < 10) ? '0' . $n : $n;
}

class iBramsUserprofile {

    /**
     * Unique ID for userprofiles
     *
     * @var integer
     */
    public $id;
    /**
     * name of the userprofile
     *
     * @var string
     */
    public $name;
    /**
     * category of the userprofile
     *
     * @var string
     */
    public $category;
    /**
     * icon of the userprofile
     *
     * @var string
     */
    public $icon;
    /**
     * description of the userprofile
     *
     * @var string
     */
    public $description;
}

class iBramsMetadata {

    /**
     * identifier for metadatafield
     *
     * @var string
     */
    public $identifier;
    /**
     * name for metadafield
     *
     * @var string
     */
    public $name;
    /**
     * assigned value
     *
     * @var string
     */
    public $value;
}

class iBramsUser {

    /**
     * Unique ID for user
     *
     * @var integer
     */
    public $id;
    /**
     * unique login for user.
     *
     * @var string
     */
    public $login;
    /**
     * Password - it's only available on set commands. Getting user will always return empty password information.
     *
     * @var string
     */
    public $password;
    /**
     * firstname for user
     *
     * @var string
     */
    public $firstname;
    /**
     * lastname for user
     *
     * @var string
     */
    public $lastname;
    /**
     * email used by user
     *
     * @var string
     */
    public $email;
    /**
     * prefixed title for user
     *
     * @var string
     */
    public $title_pre;
    /**
     * postfixed title for user
     *
     * @var string
     */
    public $title_post;
    /**
     * gender of user, 'm' for male, 'f' for female and 'unknown' for unknown
     *
     * @var string
     */
    public $gender;
    /**
     * salutation for user.
     *
     * @var string
     */
    public $salutation;
    /**
     * company name
     *
     * @var string
     */
    public $company;
    /**
     * address information, street
     *
     * @var string
     */
    public $street;
    /**
     * postal code for city
     *
     * @var string
     */
    public $postalcode;
    /**
     * city
     *
     * @var string
     */
    public $city;
    /**
     * region [vcard standard for state information]
     *
     * @var string
     */
    public $region;
    /**
     * country
     *
     * @var string
     */
    public $country;
    /**
     * metadata fields
     *
     * @var array(iBrams_Type_SoapMetadata)
     */
    public $metadata;
    /**
     * userprofile field
     *
     * @var array(iBrams_Type_SoapUserprofile)
     */
    public $userprofile;
}

?>