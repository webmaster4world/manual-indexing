<?php

/**
 * MISYSTEMS
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * misystems
 *
 * @category   Miystems
 * @package    Miystems Catalog
 * @copyright  Copyright (c) 2009-2011 Misystems
 * @license    http://misytems.ch/licence.txt
 */
class Antalis_Helper_Product {

    private $_Response = NULL;
    /**
     * produtattribute for identifiing producttype
     * @var string 
     */
    public static $_attribute = "bzp_antalis_no";
    /**
     * @var object the inventory object
     */
    private $_inventory = NULL;
    /**
     * csimpy checks Antalisproduct by given ordernumber
     * @param Mage_Core_ $product
     * @return bool
     */
    protected $_Helper = NULL;
    /**
     * @var object
     */
    protected $_products = NULL;
    protected $_product = NULL;
    /**
     * serviceresponse
     * @param xml $_product 
     */
    private static $_response = '';
    /**
     * @var stockinfo
     */
    private $_stockInfo = NULL;
    protected $_AntalisLagerID = 6;

    /**
     *
     * @param product $_product
     */
    public function __construct($_products) {

        if(is_null($_products))
        {
            die("no antalisproducts given in:".__METHOD__);
        }

        $this->_products = $_products;

        //todo: programm better for strictmode. Lazy pic :-)
        ini_set('error_reporting', E_ALL ^ E_NOTICE ^ E_WARNING);
        Mage::log(__METHOD__ . PHP_EOL . "Disabled Error Reporting here for runtime", "err");
    }

    /**
     * returns latest order of the product
     * @return result
     */
    protected function getLatestProductOrderDate($product) {
        $sql = "SELECT * FROM sales_flat_order_item WHERE sku='{$product['sku']}' order by created_at DESC limit 0,1";

        try {

            $write = Mage::getSingleton('core/resource')->getConnection('core_write');

            $result = $write->fetchAll($sql);
        } catch (Exception $e) {
            die("" . $e->getMessage());
        }
        return $result[0];
    }

    /**
     * decides whether the product is an antalis product
     * @return bool 
     */
    public function isProduct($product = NULL) {

        if (is_null($product)) {
            die(__METHOD__ . " <br> no product given.");
        }

        if ($product['bzp_lager'] == $this->_AntalisLagerID){
            return true;
        }
        return false;
    }

    /**
     * calls the service
     */
    public function call() {

        foreach ($this->_products['childs'] as $product) {
            if ($this->isProduct($product)) {
                $antalis_products['childs'][] = $product;
            }
        }

        if (is_array($antalis_products)) {
			$this->setStockInfo($antalis_products);
        }
    }

    /**
     * returns service stockinfo quantity
     * Notice: The service takes 5-10 minutes for refresh datas from orderdatabase.
     * in that time the local stock is used
     * @return int 
     */
    public function getQuantityInStock($product = NULL) {

        $this->call();
       
        $lastOrderItem = $this->getLatestProductOrderDate($product);

        $qty_ordered = (int) $lastOrderItem['qty_ordered'];

        $ServiceStock = $this->getStockInfoResponse($product);

        //solange der servicestock dem Stock vor der letzten Bestellung entspricht verwenden wir den Stock nach der letzten Bestellung
        if ($ServiceStock->stockLevel == (int) $qty_ordered + (int) $product['stock_item']['qty']) {
            $ServiceStock->stockLevel = $product['stock_item']['qty'];
        }

        return $ServiceStock->stockLevel;
    }

    /**
     * returns product stock from serviceresponse for product
     * @param int $bzp_antalis_no
     * @return object
     */
    protected function getStockInfoResponse($product = NULL) {
        try {

            if ($product['bzp_antalis_no'] == "" || $product['sku'] == "" ) {
                throw new Exception("no antalis number or sku set in " . __METHOD__);
            }
        } catch (Exception $e) {
            
            mail("peter.boethig@misystems.ch", "Antalis Error", $e->getMessage());

            echo"<h1>Produkt mit ID {$product->getID()} falsch konfiguriert</h1>";
            echo"<br>Message:".$e->getMessage();

            Zend_Debug::dump($product);


            die("".__METHOD__);
        }


        return self::$_response[$product['bzp_antalis_no']];
    }

    /**
     * @return object
     */
    public function getProcessedStockInfo() {
        return $this->_stockInfo;
    }

    /**
     * gets attibute
     * @return string 
     */
    public function get_attribute() {
        return self::$_attribute;
    }

        /**
     * returns emailtemplate id
     * @param string $template
     * @return int
     */
    public function getMessageTempate($template='') {

        $templateConf = Mage::getStoreConfig("Stocknotification/notificationconf");

       

   
        try {
            if (strtolower($templateConf["antalis".$template] == "")) {
                throw new Exception("no messagetemplate defiened in core_config_data for antalis$template");
            }
        } catch (Exception $e) {

            die("" . $e->getMessage());

        }

        return $templateConf["antalis".$template];
    }


    /**
     * returns service stockinfo
     * @param object $product
     * @return array
     */
    public function setStockInfo($products = NULL) {

        $detailRow = array();


        $Antalis_Service_Stock = new Antalis_Service_Stock($products);

        $Antalis_Service_Stock->call();

        self::$_response = Antalis_Service_Stock::get_response();
//Zend_Debug::dump(self::$_response);die();
        try {
            self::$_response = @simplexml_load_string(self::$_response);

            if (!is_object(self::$_response)) {
                throw new Exception("No valid Response from Service:");
            }

            self::$_response = self::$_response->xpath('//productStockLevelOutput/detailRow');
        } catch (Exception $e) {

            Mage::log(__METHOD__ . PHP_EOL . $e->getMessage(), "err", 'Antalis.log');
        }

        self::$_response = Antalis_Service_Stock::handleError(self::$_response, $products);
    }


      /**
     * returns needet emailtemplatevars
     * @todo find better solution for mapping
     * @return array
     */
    public function getTemplateVars($product = NULL) {

        $templateVars = Array('antalisnr' => $product[self::$_attribute]);

        return $templateVars;
    }




    /**
     * @return xml 
     */
    public function get_response() {
        return self::$_response;
    }

}