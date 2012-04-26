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
 * Reports view helper
 */
class AW_Advancedreports_Helper_View extends AW_Advancedreports_Helper_Queue
{
    /**
     * View Stock Data Key
     */
    const DATA_KEY_VIEW_STOCK = 'aw_advancedreports_view_stock';

    const REPORT_ID = 'report_id';
    const IS_CHANGED = 'is_changed';

    /**
     * Retrives view stock
     * @return array
     */
    protected function _getViewStock()
    {
        if ($stock = $this->_session()->getData(self::DATA_KEY_VIEW_STOCK)){          
            return $stock;
        }
        return array();
    }

    protected function _setViewStock($stock)
    {
        $this->_session()->setData(self::DATA_KEY_VIEW_STOCK, $stock);
    }

    /**
     * Retrives View
     * @param string $reportId
     * @param string $viewKey
     * @return Varien_Object
     */
    public function getView($reportId, $viewKey)
    {
        $stock = $this->_getViewStock();
        if (isset($stock[$viewKey])){
            return new Varien_Object($stock[$viewKey]);
        }
        return new Varien_Object(array(
            'report_id' => $reportId,
            'is_changed' => true
        ));
    }

    public function getNewKey($reportId)
    {
        return base64_encode(md5(time().$reportId));
    }

    public function setCurrentReportId($reportId, $viewKey)
    {
        $stock = $this->_getViewStock();
        if (isset($stock[$viewKey])){            
            $view = $stock[$viewKey];
            if (isset($view[self::REPORT_ID]) && ($view[self::REPORT_ID] ==  $reportId) ){
                $view[self::IS_CHANGED] = false;
            } else {
                $view[self::IS_CHANGED] = true;
            }
            $view[self::REPORT_ID] =  $reportId;
        } else {
            $view = array(
                'report_id' => $reportId,
                'is_changed' => true
            );
        }     
        $stock[$viewKey] = $view;
        $this->_setViewStock($stock);
        return $this;
    }

    public function isReportChanged($reportId, $viewKey)
    {                
        return $this->getView($reportId, $viewKey)->getIsChanged();
    }


    
}