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
 * Stored Date Ranges Queue Helper
 */
class AW_Advancedreports_Helper_Queue extends Mage_Core_Helper_Data
{
    /**
     * Path to config. filter count
     */
    const XML_PATH_RECENTLY_FILTER_COUNT = 'advancedreports/configuration/recently_filter_count';

    /**
     * Path to enabled crossreport filters option
     */
    const XML_PATH_CROSSREPORT_FILTERS = 'advancedreports/configuration/crossreport_filters';

    /**
     * Last filters stored in session path
     */
    const DATA_KEY_LAST_FILTERS = 'aw_advancedreports_last_filters';

    /**
     * Date ranges stored in session path
     */
    const DATA_KEY_STORED_RANGES = 'aw_advancedreports_stored_ranges';

    /**
     * Default value of stored ranges
     */
    const RECENTLY_FILTER_DEFAULT_COUNT = 5;

    /**
     * Default period value
     */
    const DEFAULT_PERIOD = 'day';

    /**
     * Retriives adminhtml session
     * @return Mage_Adminhtml_Model_Session
     */
    protected function _session()
    {
        return Mage::getSingleton('adminhtml/session');
    }

    /**
     * Retrives helper
     * @return AW_Advancedreports_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('advancedreports');
    }

    public function confRecentFilterCount($gridType = null)
    {
        if ($gridType) {
            $customOptions = Mage::getModel('advancedreports/option');
            $adminSession = Mage::getSingleton('admin/session');
            $adminId = $adminSession->isLoggedIn() ? $adminSession->getUser()->getId() : null;
            $smth = $customOptions->load3params($gridType, $adminId, self::XML_PATH_RECENTLY_FILTER_COUNT);
            if ($smth->getData('value')) {
                return $smth->getData('value');
            }
        }
        return Mage::getStoreConfig(self::XML_PATH_RECENTLY_FILTER_COUNT) ?
            Mage::getStoreConfig(self::XML_PATH_RECENTLY_FILTER_COUNT) :
            self::RECENTLY_FILTER_DEFAULT_COUNT;
    }

    public function confCrossreportFilters()
    {
        $setup = $this->_helper()->getSetup()->getCustomConfig(self::XML_PATH_CROSSREPORT_FILTERS);
        return ($setup !== null) ? $setup : Mage::getStoreConfig(self::XML_PATH_CROSSREPORT_FILTERS);
    }

    protected function _preparedQueueItem($from, $to, $period = null)
    {
        return array(
            'report_from' => $from,
            'report_to' => $to,
            'report_period' => $period ? $period : self::DEFAULT_PERIOD
        );
    }

    /**
     * Compare two queue items. Retrives 0 if them equales.
     *
     * @param array $a
     * @param array $b
     * @return integer
     */
    protected function _compareQueueItems($a, $b)
    {
        $result = 0;
        foreach ($a as $key => $value) {
            if ($a[$key] !== $b[$key]) {
                $result++;
            }
        }
        return $result;
    }

    /**
     * Store date range for report in session
     *
     * @param string $from
     * @param string $to
     * @param string $period
     * @return AW_Advancedreports_Helper_Queue
     */
    public function pushDateRange($from, $to, $period = null)
    {
        if (!($storedQueue = $this->_session()->getData(self::DATA_KEY_STORED_RANGES)) || !is_array($storedQueue)) {
            $storedQueue = array($this->_preparedQueueItem($from, $to, $period));
            $this->_session()->setData(self::DATA_KEY_STORED_RANGES, $storedQueue);
        } else {
            $newItem = $this->_preparedQueueItem($from, $to, $period);
            $equalIndex = null;
            $newQueue = array();
            foreach ($storedQueue as $item) {
                if ($this->_compareQueueItems($item, $newItem) !== 0) {
                    $newQueue[] = $item;
                }
            }
            array_push($newQueue, $newItem);
            $this->_session()->setData(self::DATA_KEY_STORED_RANGES, $newQueue);
        }
        return $this;
    }

    /**
     * Retrives stored date ranges for report
     *
     * @return array
     */
    public function getStoredRanges($gridType = null)
    {
        $result = array();
        if ($storedQueue = $this->_session()->getData(self::DATA_KEY_STORED_RANGES)) {
            $i = 0;
            $lastElement = end($storedQueue);
            while ($lastElement && ($i < $this->confRecentFilterCount($gridType))) {
                $result[] = $lastElement;
                $lastElement = prev($storedQueue);
                $i++;
            }
        }
        return $result;
    }

    /**
     * Retrives ast filter value
     * @return $mixed | null
     */
    public function getLastFilter($name)
    {
        if ($storedValues = $this->_session()->getData(self::DATA_KEY_LAST_FILTERS)) {
            if (isset($storedValues[$name])) {
                return $storedValues[$name];
            }
        }
        return null;
    }

    /**
     * Save las filter to session
     * @param array $filters
     * @return AW_Advancedreports_Helper_Queue
     */
    public function saveLastFilters($filters)
    {
        $storedFilters = array();
        if ($this->_session()->getData(self::DATA_KEY_LAST_FILTERS)) {
            $storedFilters = $this->_session()->getData(self::DATA_KEY_LAST_FILTERS);
        }
        foreach ($filters as $key => $value) {
            $storedFilters[$key] = $value;
        }
        $this->_session()->setData(self::DATA_KEY_LAST_FILTERS, $storedFilters);

        # Store date range
        if (isset($storedFilters['custom_date_range']) && $storedFilters['custom_date_range'] == 'custom') {
            $this->pushDateRange($storedFilters['report_from'], $storedFilters['report_to'], isset($storedFilters['report_period']) ? $storedFilters['report_period'] : null);
        }

        return $this;
    }
}
