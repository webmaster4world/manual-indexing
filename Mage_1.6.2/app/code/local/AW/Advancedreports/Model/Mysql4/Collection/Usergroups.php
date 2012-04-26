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
class AW_Advancedreports_Model_Mysql4_Collection_Usergroups
    extends AW_Advancedreports_Model_Mysql4_Collection_Abstract
{
    /**
     * Add groups
     *
     * @return AW_Advancedreports_Model_Mysql4_Collection_Usergroups
     */
    public function addCustomerGroups()
    {
        $itemTable = $this->_helper()->getSql()->getTable('sales_flat_order_item');
        $customerEntityTable = $this->_helper()->getSql()->getTable('customer_entity');
        $customerGroupTable = $this->_helper()->getSql()->getTable('customer_group');

        if ($this->_helper()->checkSalesVersion('1.4.0.0')){
            $this->getSelect()
                    ->join( array('item'=>$itemTable), "main_table.entity_id = item.order_id AND item.parent_item_id IS NULL", array('sum_qty' => 'SUM(item.qty_ordered)',  'sum_total' => 'SUM(item.base_row_total)', 'name' => 'name', 'sku'=>'sku' ) )
                    ->joinLeft( array('cust'=>$customerEntityTable), "main_table.customer_id = cust.entity_id AND main_table.customer_id IS NOT NULL", array() )
                    ->joinLeft( array('grp'=>$customerGroupTable), "grp.customer_group_id = IFNULL(cust.group_id, '0')", array('group_name'=>'customer_group_code', 'group_id' => 'customer_group_id') )
                    ->group('grp.customer_group_id');
        } else {
            $this->getSelect()
                    ->join( array('item'=>$itemTable), "e.entity_id = item.order_id AND item.parent_item_id IS NULL", array('sum_qty' => 'SUM(item.qty_ordered)',  'sum_total' => 'SUM(item.base_row_total)', 'name' => 'name', 'sku'=>'sku' ) )
                    ->joinLeft( array('cust'=>$customerEntityTable), "e.customer_id = cust.entity_id AND e.customer_id IS NOT NULL", array() )
                    ->joinLeft( array('grp'=>$customerGroupTable), "grp.customer_group_id = IFNULL(cust.group_id, '0')", array('group_name'=>'customer_group_code', 'group_id' => 'customer_group_id') )
                    ->group('grp.customer_group_id');
        }
        return $this;
    }

}