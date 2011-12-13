<?php

/**
 * MISYSTEMS
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 *
 * @category   Miystems
 * @package    Miystems Catalog
 * @copyright  Copyright (c) 2009-2011 Misystems
 * @license    http://misytems.ch/licence.txt
 */
class Antalis_Service_Stock extends Zend_View_Helper_Abstract {

    /**
     * conf
     * @var object
     */
    private $_conf;
    /**
     * servicelocation
     * @var string
     */
    private $_location;
    /**
     * string
     * @var array
     */
    private $_tracesoap;
    /**
     * soap client
     * @var object
     */
    private $_client;
    /**
     * the main product
     */
    private $_products = NULL;
    /**
     * produtattribute for identifiing producttype
     * @var string
     */
    protected $_attribute = "bzp_antalis_no";
    public static $_response = NULL;
    /**
     * @var <type>
     */
    private static $_errorCodes = array("00" => "no error", "01" => "error found", "04" => "unknown product", "16" => "Product not available", "99" => "backend down");

    /**
     * emty for now
     */
    public function __construct($products = NULL) {

        $this->_conf = $this->_getConfValues();

        $this->_products = $products;
    }

    /**
     * Returns store configuration values
     * return array
     */
    public function _getConfValues() {
        return Mage::getStoreConfig('Stocknotification/antalisstockinfoservice');
    }

    /**
     * gets info
     */
    public function get_response() {
        return self::$_response;
    }

    /**
     * inits the soap request4
     * return object $curl
     */
    public function call() {

        $xml_request = new Antalis_Service_XML_Request();

        $productIDs_XML_Tag = $xml_request->getProductIDTag($this->_products);

        $this->_conf = $this->_getConfValues();

        $this->_conf['enquirytype'] = $this->getBzpMengenEinheit($this->_products['childs'][0]);



        $xml_request = $xml_request->productStockLevelService($this->_conf, $this->_product[$this->_attribute], $productIDs_XML_Tag);

        //init curl
        $ch = curl_init();

        //configure curl
        curl_setopt($ch, CURLOPT_URL, $this->_conf['serviceurl']);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->_conf['usercode']}:{$this->_conf['password']}");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_request);

        try {

            $curl = curl_exec($ch);

            //Zend_Debug::dump($curl);
            //http://stage.webshop-zweiplus.ch/c21/checkout/cart/add/product/271/
            if ($_REQUEST['dump'] == "true") {
                
                echo"<div style='width:800px'>";
                echo"<h2>Request complete</h2>";
                echo"<p>the productstock was refreshed. Please close window with the link above</p>";
                echo"<h1>Request</h1>";
                Zend_Debug::dump($xml_request);
                echo"<h1>Response</h1>";
                Zend_Debug::dump($curl);
                echo"</div>";
            }

            if (!$curl || is_null($curl)) {
                throw new Exception("Antalis StockInfoservice not available or wrong configured");
            }

            self::$_response = $curl;
        } catch (Exception $e) {

            //only log error. Errorcode triggers Email in Module ProductHelper
            Mage::log(__METHOD__ . PHP_EOL . $e->getMessage(), "err", 'Antalis.log');

            curl_close($curl);
        }
    }

    /**
     * returns BZP Mengeneinheit
     * @param <type> $product
     * @return <type> 
     */
    public static function getBzpMengeneinheit($product=NULL) {

        if ($product['bzp_mengeneinheit_iso'] != "") {



            $attributeValue = Mage::getModel('catalog/product')
                            ->load($product->getId())
                            ->getAttributeText('bzp_mengeneinheit_iso');

            return $attributeValue;
        }



        return "PCE";
    }

    /**
     * handle service errors and sends emails
     * @param type $detailRow
     * @return stdClass 
     */
    public static function handleError($detailRows = NULL, $products) {

        $stockInfo = new stdClass();

        $arr = array();

        // no serviceresponse avaliable. Backend down
        if (!is_object($detailRows[0]->item) or !isset($detailRows[0]->item)) {

            foreach ($products as $product) {
                $stockInfo->stockLevel = "err";

                $stockInfo->errorMessage = "99";

                $arr[$product['bzp_antalis_no']] = $stockInfo;
            }

            return $arr;
        }

        foreach ($detailRows[0]->item as $item) {

            $stockInfo->stockLevel = $item->stockLevel;

            $stockInfo->stockLevelUnit = $item->stockLevelUnit;

            $stockInfo->errorID = $item->errorID;

            $stockInfo->errorMessage = self::$_errorCodes[(string) $stockInfo->errorID];

            if ((int) $stockInfo->errorID != "00") {

                $stockInfo->stockLevel = "err";
            }

            $arr[(string) $item->productID] = $stockInfo;

            unset($stockInfo);
        }

        return $arr;
    }

}
