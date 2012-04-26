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
class AW_Advancedreports_Model_Mysql4_Collection_Sales extends AW_Advancedreports_Model_Mysql4_Collection_Abstract
{
    /**
     * Reinitialize select
     *
     * @return AW_Advancedreports_Model_Mysql4_Collection_Sales
     */
    public function reInitSelect()
    {
        $filterField = $this->_helper()->confOrderDateFilter();
        if ($this->_helper()->checkSalesVersion('1.4.0.0')) {
            $orderTable = $this->_helper()->getSql()->getTable('sales_flat_order');
        } else {
            $orderTable = $this->_helper()->getSql()->getTable('sales_order');
        }

        $this->getSelect()->reset();

        $this->getSelect()->from(array($this->_getSalesCollectionTableAlias() => $orderTable), array(
            'order_created_at' => $filterField,
            'order_id' => 'entity_id',
            'order_increment_id' => 'increment_id',

        ));

        $this->getSelect()
        # sku
            ->columns(array('xsku' => "IFNULL(realP.sku, item.sku)"))
        # price
            ->columns(array('base_xprice' => "IFNULL(item2.base_price, item.base_price)"))
        # subtotal
            ->columns(array('base_row_subtotal' => "( IFNULL(item2.qty_ordered, item.qty_ordered) * IFNULL(item2.base_price, item.base_price) )"))
        # total
            ->columns(array('base_row_xtotal_incl_tax' => "( IFNULL(item2.qty_ordered, item.qty_ordered) * IFNULL(item2.base_price, item.base_price) - ABS( IFNULL(item2.base_discount_amount,item.base_discount_amount) ) + IFNULL(item2.base_tax_amount, item.base_tax_amount) )"))
            ->columns(array('base_row_xtotal' => "( IFNULL(item2.qty_ordered, item.qty_ordered) * IFNULL(item2.base_price, item.base_price) - ABS( IFNULL( item2.base_discount_amount, item.base_discount_amount ) ) )"))
        # invoiced
            ->columns(array('base_row_xinvoiced' => "( IFNULL(item2.qty_invoiced, item.qty_invoiced) * IFNULL(item2.base_price, item.base_price) - ABS(IFNULL(item2.base_discount_amount, item2.base_discount_amount) ) )"))
            ->columns(array('base_row_xinvoiced_incl_tax' => "( IFNULL(item2.qty_invoiced, item.qty_invoiced) * IFNULL(item2.base_price, item.base_price) - ABS( IFNULL(item2.base_discount_amount, item.base_discount_amount) )  + IFNULL(item2.base_tax_invoiced, item.base_tax_invoiced) )"))
        # refunded
            ->columns(array('base_row_xrefunded' => "( (IF((IFNULL(item2.qty_refunded, item.qty_refunded) > 0), 1, 0) * (  (IFNULL(item2.qty_refunded, item.qty_refunded) / IFNULL(item2.qty_invoiced, item.qty_invoiced)) * ( IFNULL(item2.qty_invoiced, item.qty_invoiced) * IFNULL(item2.base_price, item.base_price) - ABS( IFNULL(item2.base_discount_amount, item.base_discount_amount) ) )  ) ) )"))
            ->columns(array('base_tax_xrefunded' => "IF(( IFNULL(item2.qty_refunded, item.qty_refunded) > 0), ( IFNULL(item2.qty_refunded, item.qty_refunded) / IFNULL(item2.qty_invoiced, item.qty_invoiced) *  IFNULL(item2.base_tax_invoiced, item.base_tax_invoiced) ), 0)"))
            ->columns(array('base_row_xrefunded_incl_tax' => "((IF(( IFNULL(item2.qty_refunded, item.qty_refunded) > 0), 1, 0) * (  (IFNULL(item2.qty_refunded, item.qty_refunded)  * ( IFNULL(item2.qty_invoiced, item.qty_invoiced) * IFNULL(item2.base_price, item.base_price) - ABS( IFNULL(item2.base_discount_amount, item.base_discount_amount) ) ) / IFNULL(item2.qty_invoiced, item.qty_invoiced) ) + IF((IFNULL(item2.qty_refunded, item.qty_refunded) > 0) , ( IFNULL(item2.qty_refunded, item.qty_refunded) / IFNULL(item2.qty_invoiced, item.qty_invoiced)  *  IFNULL(item2.base_tax_invoiced, item.base_tax_invoiced) ), 0) )  ))"))
            ->columns(array('xqty_ordered' => 'IFNULL(item2.qty_ordered, item.qty_ordered)'))
            ->columns(array('xqty_invoiced' => 'IFNULL(item2.qty_invoiced, item.qty_invoiced)'))
            ->columns(array('xqty_shipped' => 'IFNULL(item2.qty_shipped, item.qty_shipped)'))
            ->columns(array('xqty_refunded' => 'IFNULL(item2.qty_refunded, item.qty_refunded)'));

        return $this;
    }

    /**
     * Exclude refunded
     *
     * @return AW_Advancedreports_Model_Mysql4_Collection_Sales
     */
    public function excludeRefunded()
    {
        $this->getSelect()
            ->where('? > 0', new Zend_Db_Expr('(item.qty_ordered - item.qty_refunded)'));
        return $this;
    }

    /**
     * Add order items
     *
     * @param $skuType
     * @return AW_Advancedreports_Model_Mysql4_Collection_Sales
     */
    public function addOrderItems($skuType)
    {
        $productTable = $this->_helper()->getSql()->getTable('catalog_product_entity');
        $filterField = $this->_helper()->confOrderDateFilter();
        $itemTable = $this->_helper()->getSql()->getTable('sales_flat_order_item');
        $orderTable = $this->_helper()->getSql()->getTable('sales_order');
        $notSimple = "'configurable','bundle'";
        $tableAlias = $this->_getSalesCollectionTableAlias();

        $this->getSelect()
            ->join(array('item' => $itemTable), "(item.order_id = {$tableAlias}.entity_id AND item.parent_item_id IS NULL)");
        $this->getSelect()
            ->joinLeft(array('item2' => $itemTable), "(item.parent_item_id IS NOT NULL AND item.parent_item_id = item2.item_id AND item2.product_type = 'configurable')", array())
            ->joinLeft(array('realP' => $productTable), "item.product_id = realP.entity_id", array('real_sku' => 'realP.sku'))
            ->order("{$tableAlias}.{$filterField} DESC");
        return $this;
    }

    /**
     * Add customer info
     *
     * @return AW_Advancedreports_Model_Mysql4_Collection_Sales
     */
    public function addCustomerInfo()
    {
        $customerEntity = $this->_helper()->getSql()->getTable('customer_entity');
        $customerGroup = $this->_helper()->getSql()->getTable('customer_group');
        $tableAlias = $this->_getSalesCollectionTableAlias();

        $this->getSelect()
            ->joinLeft(array('c_entity' => $customerEntity), "{$tableAlias}.customer_id = c_entity.entity_id", array())
            ->joinLeft(array('c_group' => $customerGroup), "IFNULL(c_entity.group_id, 0) = c_group.customer_group_id", array('customer_group' => "c_group.customer_group_code"));

        return $this;
    }

    /**
     * Add manufacturer
     *
     * @return AW_Advancedreports_Model_Mysql4_Collection_Sales
     */
    public function addManufacturer()
    {
        $entityProduct = $this->_helper()->getSql()->getTable('catalog_product_entity');
        $entityValuesVarchar = $this->_helper()->getSql()->getTable('catalog_product_entity_varchar');
        $entityValuesInt = $this->_helper()->getSql()->getTable('catalog_product_entity_int');
        $entityAtribute = $this->_helper()->getSql()->getTable('eav_attribute');
        $eavAttrOptVal = $this->_helper()->getSql()->getTable('eav_attribute_option_value');
        $this->getSelect()
            ->join(array('_product' => $entityProduct), "_product.entity_id = item.product_id", array('p_product_id' => 'item.product_id'))
            ->joinLeft(array('_manAttr' => $entityAtribute), "_manAttr.attribute_code = 'manufacturer'", array())
            ->joinLeft(array('_manValVarchar' => $entityValuesVarchar), "_manValVarchar.attribute_id = _manAttr.attribute_id AND _manValVarchar.entity_id = _product.entity_id", array())
            ->joinLeft(array('_manValInt' => $entityValuesInt), "_manValInt.attribute_id = _manAttr.attribute_id AND _manValInt.entity_id = _product.entity_id", array())
            ->joinLeft(array('_optVal' => $eavAttrOptVal), "_optVal.option_id = IFNULL(_manValInt.value, _manValVarchar.value) AND _optVal.store_id = 0", array('product_manufacturer' => 'value'));
        return $this;
    }

    public function addAddress()
    {
        if ($this->_helper()->checkSalesVersion('1.4.0.0')) {
            $salesFlatOrderAddress = $this->_helper()->getSql()->getTable('sales_flat_order_address');
            $this->getSelect()
                ->joinLeft(array('flat_order_addr_ship' => $salesFlatOrderAddress), "flat_order_addr_ship.parent_id = main_table.entity_id AND flat_order_addr_ship.address_type = 'shipping'", array(
                'order_ship_postcode' => 'postcode',
                'order_ship_country_id' => 'country_id',
                'order_ship_region' => 'region',
                'order_ship_city' => 'city',
                'order_ship_email' => 'email',
            ))
                ->joinLeft(array('flat_order_addr_bil' => $salesFlatOrderAddress), "flat_order_addr_bil.parent_id = main_table.entity_id AND flat_order_addr_bil.address_type = 'billing'", array(
                'order_bil_postcode' => 'postcode',
                'order_bil_country_id' => 'country_id',
                'order_bil_region' => 'region',
                'order_bil_city' => 'city',
                'order_bil_email' => 'email',
            ));
        } else {
            $entityValues = $this->_helper()->getSql()->getTable('sales_order_int');
            $entityAtribute = $this->_helper()->getSql()->getTable('eav_attribute');
            $entityType = $this->_helper()->getSql()->getTable('eav_entity_type');
            $salesFlatQuote = $this->_helper()->getSql()->getTable('sales_flat_quote');
            $salesFlatQuoteAddress = $this->_helper()->getSql()->getTable('sales_flat_quote_address');
            $this->getSelect()
                ->joinLeft(array('a_type_order' => $entityType), "a_type_order.entity_type_code='order'", array())
                ->joinLeft(array('a_attr_quote' => $entityAtribute), "a_type_order.entity_type_id=a_attr_quote.entity_type_id AND a_attr_quote.attribute_code = 'quote_id'", array())
                ->joinLeft(array('a_value_quote' => $entityValues), "a_value_quote.entity_id = e.entity_id AND a_value_quote.attribute_id = a_attr_quote.attribute_id", array())
                ->joinLeft(array('flat_quote' => $salesFlatQuote), "flat_quote.entity_id = a_value_quote.value", array())
                ->joinLeft(array('flat_quote_addr_ship' => $salesFlatQuoteAddress), "flat_quote_addr_ship.quote_id = flat_quote.entity_id AND flat_quote_addr_ship.address_type = 'shipping'", array(
                'order_ship_postcode' => 'postcode',
                'order_ship_country_id' => 'country_id',
                'order_ship_region' => 'region',
                'order_ship_city' => 'city',
                'order_ship_email' => 'email',
            ))
                ->joinLeft(array('flat_quote_addr_bil' => $salesFlatQuoteAddress), "flat_quote_addr_bil.quote_id = flat_quote.entity_id AND flat_quote_addr_bil.address_type = 'billing'", array(
                'order_bil_postcode' => 'postcode',
                'order_bil_country_id' => 'country_id',
                'order_bil_region' => 'region',
                'order_bil_city' => 'city',
                'order_bil_email' => 'email',
            ));
        }
        return $this;
    }
}
