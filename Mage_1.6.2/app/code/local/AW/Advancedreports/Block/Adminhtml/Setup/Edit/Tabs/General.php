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
class AW_Advancedreports_Block_Adminhtml_Setup_Edit_Tabs_General extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Setup Instace
     * @return AW_Advancedreports_Helper_Setup
     */
    public function getSelect()
    {
        return Mage::helper('advancedreports/setup');
    }

    protected function _setTypes($fieldset)
    {
        $fieldset->addType('ar_select', 'AW_Advancedreports_Model_Form_Element_Select');
        $fieldset->addType('ar_multiselect', 'AW_Advancedreports_Model_Form_Element_Multiselect');
        $fieldset->addType('ar_text', 'AW_Advancedreports_Model_Form_Element_Text');
    }


    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        
        $yesno = Mage::getModel('adminhtml/system_config_source_yesno');
        $statuses = Mage::getModel('advancedreports/system_config_source_statuses');
        $datefields = Mage::getModel('advancedreports/system_config_source_datefilter');

        /**
         * Select options
         */
        $fieldset = $form->addFieldset('advancedreports_select', array('legend'=>Mage::helper('advancedreports')->__('Select options')));

        $this->_setTypes($fieldset);

        $fieldset->addField('process_orders', 'ar_multiselect', array(
            'label'     => Mage::helper('advancedreports')->__('Process orders'),
            'title'     => Mage::helper('advancedreports')->__('Process orders'),
            'name'      => 'process_orders',
            'values'    => $statuses->toOptionArray(),
        ));

        $fieldset->addField('order_datefilter', 'ar_select', array(
            'label'     => Mage::helper('advancedreports')->__('Select orders by'),
            'title'     => Mage::helper('advancedreports')->__('Select orders by'),
            'name'      => 'order_datefilter',
            'values'    => $datefields->toOptionArray(),
        ));

        if (count($options = $this->getSelect()->getGrid()->getCustomOptionsRequired())){
            foreach ($options as $option){
                if (!isset($option['hidden']) || !$option['hidden']){
                    $fieldset->addField($option['id'], $option['type'], $option['args']);
                }
            }
        }

        /**
         * Filter Options
         */
        $fieldset = $form->addFieldset('advancedreports_filter', array('legend'=>Mage::helper('advancedreports')->__('Filter options')));

        $this->_setTypes($fieldset);
        
        # Commented because customization of this is unlogical
//        $fieldset->addField('crossreport_filters', 'ar_select', array(
//            'label'     => Mage::helper('advancedreports')->__('Save date range when navigating between reports'),
//            'title'     => Mage::helper('advancedreports')->__('Save date range when navigating between reports'),
//            'name'      => 'crossreport_filters',
//            'values'    => $yesno->toOptionArray(),
//            
//        ));

        $fieldset->addField('recently_filter_count', 'ar_text', array(
            'label'     => Mage::helper('advancedreports')->__('Number of latest custom date ranges'),
            'title'     => Mage::helper('advancedreports')->__('Number of latest custom date ranges'),
            'name'      => 'recently_filter_count',
            'class'     => 'validate-number',
        ));
       
        $form->setValues(Mage::registry('setup_data'));

        $this->setForm($form);

        parent::_prepareForm();
    }


}