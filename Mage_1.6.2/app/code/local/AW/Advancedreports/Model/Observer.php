<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 * 
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Advancedreports
 * @copyright  Copyright (c) 2010-2011 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE-COMMUNITY.txt
 */
/**
 * Advanced Reports Observer
 */
class AW_Advancedreports_Model_Observer
{
    /**
     * Retrives helper
     * @return AW_Advancedreports_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('advancedreports');
    }

    /**
     * Retrives cache collection
     * @return AW_Advancedreports_Model_Mysql4_Aggregation_Collection
     */
    protected function _getCacheCollection()
    {
        return Mage::getModel('advancedreports/aggregation')->getCollection();
    }

    public function orderSaveAfter($event)
    {                       
        $order = $event->getOrder();                              
        $origData = new Varien_Object($order->getOrigData());
        $this->_getCacheCollection()->expirePeriodFor($origData->getCreatedAt(), $origData->getUpdatedAt(), $order->getUpdatedAt());
    }

    /**
     * Handle product delete
     *
     * @param $event
     * @return AW_Advancedreports_Model_Observer
     */
    public function productDeleteBefore($event)
    {
        /** @var Mage_Catalog_Model_Product $product  */
        $product = $event->getProduct();

        $searchSku = $product->getSku();
        $sku = $product->getSku;

        /** @var AW_Advancedreports_Model_Sku $skuRelevance  */
        $sku = Mage::getModel('advancedreports/sku');

        $tableName = $sku->getResource()->getMainTable();
        $writeAdapter = $this->_helper()->getWriteAdapter();

        try {
            $writeAdapter->beginTransaction();
            $tableConnection = new Zend_Db_Table(array(
                                                      Zend_Db_Table::ADAPTER => $writeAdapter,
                                                      Zend_Db_Table::NAME => $tableName,
                                                 ));
            $tableConnection->delete("sku = '{$searchSku}'");
            $writeAdapter->commit();
        } catch (Exception $e) {
            Mage::logException($e);
        }
        return $this;
    }

    /**
     * Handle sku update
     *
     * @param $event
     * @return void
     */
    public function productSaveAfter($event)
    {
        /** @var Mage_Catalog_Model_Product $product  */
        $product = $event->getProduct();

        /** @var string $origSku Old sku */
        $sku = $product->getData('sku');
        /** @var string $origSku New sku */
        $origSku = $product->getOrigData('sku');
        if ($origSku && ($sku !== $origSku)){
            /** @var AW_Advancedreports_Model_Sku $skuRelevance  */
            $skuModel = Mage::getModel('advancedreports/sku')->load($origSku, 'sku');
            if ($skuModel->getId()){
                $skuModel->setSku($sku);
                $skuModel->save();
            }
        }
    }
    

}



