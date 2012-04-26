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
 * Advanced Grid Abstract Class
 */
class AW_Advancedreports_Block_Advanced_Grid extends Mage_Adminhtml_Block_Report_Grid
{
    /**
     * Array of custom data that using to build chart
     * @var array
     */
    protected $_customData = array();

    /**
     * Array of Custom Data converted. Each element is Varien_Object
     * @var array
     */
    protected $_customVarData;

    /**
     * Current timezone
     * @var string
     */
    protected $_ctz;

    /**
     * Possible filters to save
     * @var array
     */
    protected $_filtersToSave = array(
        'custom_date_range',
        'report_from',
        'report_to',
        'report_period',
//        'reload_key',
//        'detail_key',
//        'product_sku',
        # etc...
    );

    protected $_customOptions = array();

    /**
     * Saved Grand Totals
     * @var array
     */
    protected $_grandTotals;

    /**
     * Key to sort Custom Data
     * @var string
     */
    protected $_sortBy;

    /**
     * StoreIds cache
     * @var array
     */
    protected $_storeIds;

    /**
     * Table names cache
     * @deprecated
     * @var array
     */
    protected $_tables = array();

    protected $_columnConfigEnabled = true;

    /**
     * Retrives flag to reload grid after setting up of filter
     * @return boolean
     */
    public function getNeedReload()
    {
        return Mage::helper('advancedreports')->getNeedReload( $this->_routeOption );
    }

    /**
     * Retrives name of table in DB
     * @deprecated
     * @param string $tableName
     * @return string
     */
    public function getTable($tableName)
    {
        if (!isset($this->_tables[$tableName])) {
            $this->_tables[$tableName] = Mage::getSingleton('core/resource')->getTableName($tableName);
        }
        return $this->_tables[$tableName];
    }
        
    /**
     * Retrives Advanced Store Switcher
     * @return string
     */
    public function getStoreSwitcherHtml()
    {
        $block = $this->getLayout()->createBlock('advancedreports/store_switcher', 'advancedreports.store.switcher');
        if ($block){
            return $block->toHtml();
        }
        return parent::getStoreSwitcherHtml();
    }
    
    /**
     * Retrives flag to calculate
     * @return boolean
     */
    public function getNeedTotal()
    {
        return Mage::helper('advancedreports')->getNeedTotal( $this->_routeOption );
    }

    /**
     * Retrives current report option
     * @return string
     */
    public function getRoute()
    {
        return $this->_routeOption;
    }

    public function getOptionsValues()
    {
        $hash = array();

        # Getting custom options
        $custom = $this->getCustomOptionsRequired();
        foreach ($custom as $option){
            if ( ($id = $option['id']) != 'custom_columns'){
                $hash[] = $this->getCustomOption($id);
            }
        }

        # Getting order status values
        $hash[] = $this->_helper()->confProcessOrders();
        return str_replace(",","_",(implode("_", $hash)));
    }

    /**
     * Retrives array with prepared data
     * <ul>
     *  <li>No filter</li>
     *  <li>No sort</li>
     * </ul>
     * @param datetime $from
     * @param datetime $to
     * @return array
     */
    public function getPreparedData($from, $to)
    {
        return array();
    }

    /**
     * Retrives aggregator
     * @return AW_Advancedreports_Helper_Tools_Aggregator
     */
    public function getAggregator()
    {
        return $this->_helper()->getAggregator();
    }

    /**
     * Set up column filters
     */
    protected function _setColumnFilters()
    {
        if ( count( $this->getColumns() ) ){
            foreach ( $this->getColumns() as $column ){
                if ( $filter = $this->getFilter($column->getId()) ){
                    $column->getFilter()->setValue($filter);
                    if ($this->hasAggregation()){
                        $this->_addColumnFilterToCollection($column);                        
                    }
                }
            }
        }
    }

    /**
     * Check version of Mage_Sales
     *  - Synonym of Mage::helper('advancedreports')->checkSalesVersion($version)
     * 
     * @param string $version Version to check
     * @return boolean
     */
    protected function _checkSVer($version)
    {
        return $this->_helper()->checkSalesVersion($version);
    }

    public function getSetupHtml()
    {
        return $this->_helper()->getSetup()->setReportId($this->getId())->getHtml();
    }

    /**
     * Retrives formatted datetime
     * Implements standart strptime() for crossplatformed use
     * @param Datetime $sDate
     * @param string $sFormat
     * @return string
     */
    protected function _strptime($sDate, $sFormat)
    {
        return $this->_helper()->getDate()->strptime($sDate, $sFormat);
    }

    /**
     * Retrives custom option value
     *
     * @param string $path
     * @return string
     */
    public function getCustomOption($path)
    {
        if (!isset($this->_customOptions[$path])){
            if ( ($value = $this->_helper()->getSetup()->getCustomConfig($path)) !== null ){
                return $this->_customOptions[$path] = $value;
            } elseif (is_array($options = $this->getCustomOptionsRequired())) {
                foreach ($options as $option){
                    if ($option['id'] == $path){
                        return $this->_customOptions[$path] = $option['default'];
                    }
                }
            }
        }
        return isset($this->_customOptions[$path]) ? $this->_customOptions[$path] : null;
    }

    /**
     * Convert stdObj to array
     * @param stdObj $d
     * @return array
     */
    protected function _objectToArray($d)
    {
        if (is_object($d)) {
            $d = get_object_vars($d);
        }
        return $d;
    }

    /**
     * Retrives array with options
     *
     * @param string $columnId
     * @return array
     */
    public function findColumnCustomOptions($columnId)
    {
        try {
            $customOptions = unserialize($this->getCustomOption('custom_columns'));

            if (is_array($customOptions)){
                foreach($customOptions as $cOption){
                    $option = $this->_objectToArray($cOption);
                    if ($option['column_id'] == $columnId){
                        return $option;
                    }
                }
            }

        } catch (Exception $e){
            # Nothing to do
            Mage::throwException($e->getMessage());
        }
        return null;
    }


    public function getColumns()
    {
        if (!$this->getData('fetched_custom_columns')){            
            Varien_Profiler::start('aw::advancedreports::grid::retrives_columns');
            $columns = parent::getColumns();
            $showColumns = array();
            foreach ($columns as $column){
                $options = $this->findColumnCustomOptions($column->getId());
                if ($options && is_array($options)){
                    $column->setCustomHeader($options['custom_header']);
                    $column->setCustomVisible($options['checked']);
                } else {
                    $column->setCustomHeader($column->getHeader());
                    $column->setCustomVisible(true);
                }

                if ($column->getCustomVisible()){
                    $column->setHeader($column->getCustomHeader());
                    $showColumns[] = $column;
                }
            }
            Varien_Profiler::stop('aw::advancedreports::grid::retrives_columns');
            $this->setData('fetched_custom_columns', $showColumns);
        }
        return $this->getData('fetched_custom_columns');
        

    }

    /**
     * Retrives "Custom Columns" enabled flag
     * @return boolean
     */
    public function getCustomColumnConfigEnabled()
    {
        return $this->_columnConfigEnabled;
    }


    
    public function getSetupColumns()
    {
        $this->_prepareColumns();

        $columns = parent::getColumns();
        foreach ($columns as $column){
            $options = $this->findColumnCustomOptions($column->getId());
            if ($options && is_array($options)){
                $column->setCustomHeader($options['custom_header']);
                $column->setCustomVisible($options['checked']);
            } else {
                $column->setCustomHeader($column->getHeader());
                $column->setCustomVisible(true);
            }

        }
        return $columns;
    }

    /**
     * Retrives options required for report
     *
     * @return array
     */
    public function getCustomOptionsRequired()
    {
        $arr = array();

        if ($this->_columnConfigEnabled){
            $arr[] = array(
                'id'=>'custom_columns',
                'default' => serialize(array()),
                'prepare_value' => 'json2serialize',
                'hidden'  => true,
            );
        }

        return $arr;
    }

    /**
     * Retrives filter semafor for Row of Data
     * @param Varien_Object $row
     * @return boolean
     */
    protected function _filterPass($row)
    {
        $result = true;
        if ($this->getFilterVisibility()){
            if (count($this->getColumns()))    {
                foreach ($this->getColumns() as $column){
                    if ( ($filter = $this->getFilter($column->getId())) && (isset($row[$column->getId()])) ){
                        if ($column->getType() == 'text' || $column->getType() == 'country'){
                            # Filter by string
                            if ($filter && $filter != ""){
                                if (strpos(strtolower($row[$column->getId()]), strtolower($this->getFilter($column->getId()))) === false){
                                    $result = false;
                                }
                            }
                        } elseif($column->getType() == 'datetime' || $column->getType() == 'date') {
                            # Filter by Datetime
                            if ($filter && is_array($filter) ){
                                if ( isset( $row[$column->getId()] ) ){
                                    $val = $row[$column->getId()];
                                    if ( isset( $filter['from'] ) ){
                                        $date = new Zend_Date($filter['from'], Zend_Date::DATE_SHORT, $filter['locale']);
                                        if ($val <= date('Y-m-d 00:00:00', $date->getTimestamp())){
                                            $result = false;
                                        }
                                    }
                                    if ( isset( $filter['to'] ) ){
                                        $date = new Zend_Date($filter['to'], Zend_Date::DATE_SHORT, $filter['locale']);
                                        if ($val >= date('Y-m-d 23:59:59', $date->getTimestamp())){
                                            $result = false;
                                        }
                                    }
                                }
                            }
                        } elseif($column->getType() == 'number' || $column->getType() == 'currency') {
                            # filter by Number and Currency
                            if ($filter && is_array($filter)){
                                if ( isset( $row[$column->getId()] ) ){
                                    $val = $row[$column->getId()];
                                    if ( isset( $filter['from'] ) ){
                                        if ($val < $filter['from']){
                                            $result = false;
                                        }
                                    }
                                    if ( isset( $filter['to'] ) ){
                                        if ($val > $filter['to']){
                                            $result = false;
                                        }
                                    }
                                }
                            }
                        } elseif($column->getType() == 'store' || $column->getType() == 'select') {
                            # filter by Number and Currency
                            if ($filter && $filter != ""){
                                if ( isset( $row[$column->getId()] ) ){
                                    $val = $row[$column->getId()];
                                    if ( !is_array($val) ){
                                        $val = explode(",", $val);
                                    }
                                    if ( !in_array($filter, $val) ){
                                        $result = false;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Retrives refresh button html
     * @return string
     */
    public function getResetButtonHtml()
    {
        return $this->getLayout()->createBlock('adminhtml/widget_button')
                                ->setLabel(Mage::helper('reports')->__('Reset Filter'))
                                ->setType('button')
                                ->setOnClick($this->getJsObjectName().'.resetFilter();')
                                ->toHtml();
    }

    /**
     * Retrives report view session key
     * @return string
     */
    public function getViewKey()
    {
        if (!$this->getData('stored_view_key')){
            if (Mage::app()->getRequest()->getParam('view_key')){
                $viewKey = Mage::app()->getRequest()->getParam('view_key');
            } else {
                $viewKey = $this->_helper()->getView()->getNewKey($this->getId());
            }
            $this->setData('stored_view_key', $viewKey);
        }
        return $this->getData('stored_view_key');
    }

    public function setId($value)
    {
        parent::setId($value);
        $this->setData('id', $value);
        $this->_helper()->getView()->setCurrentReportId($value, $this->getViewKey());
        Mage::register(AW_Advancedreports_Helper_Setup::DATA_KEY_REPORT_ID, $value, true);
        return $this;
    }

    /**
     * Retrives helper
     * @return AW_Advancedreports_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('advancedreports');
    }

    /**
     * Format datetime from string to Mysql format
     * @param string $date
     * @return Datetime
     */
    protected function _getMysqlFromFormat($date)
    {
        $format = $this->getLocale()->getDateStrFormat( Mage_Core_Model_Locale::FORMAT_TYPE_SHORT );
        $arr = $this->_strptime($date, $format);
        $tme = mktime( 0,0,0,$arr['tm_mon']+1,$arr['tm_mday'],$arr['tm_year'] + 1900 );
        return $this->_helper()->timezoneFactory(date('Y-m-d 00:00:00', $tme ));
    }

    /**
     * Format datetime from string to Mysql format
     * @param string $date
     * @return Datetime
     */
    protected function _getMysqlToFormat($date)
    {
        $format = $this->getLocale()->getDateStrFormat( Mage_Core_Model_Locale::FORMAT_TYPE_SHORT );
        $arr = $this->_strptime($date, $format);
        $tme = mktime( 23,59,59,$arr['tm_mon']+1,$arr['tm_mday'],$arr['tm_year'] + 1900 );
        return $this->_helper()->timezoneFactory(date('Y-m-d 23:59:59', $tme ));
    }


    /**
     * Prepare abstract collection
     *  - Set date filters
     *  - Set State filter
     *  - set Store filter
     *
     * Notice: Use if your colleciton is instance of AW_Advancedreports_Model_Mysql4_Collection_Abstract
     *
     * @return AW_Advancedreports_Block_Advanced_Grid
     */
    public function _prepareAbstractCollection()
    {
        $date_from = $this->_getMysqlFromFormat($this->getFilter('report_from'));
        $date_to = $this->_getMysqlToFormat($this->getFilter('report_to'));

        $this->getCollection()->setDateFilter($date_from, $date_to);
        $this->getCollection()->setState();

        $storeIds = $this->getStoreIds();
        if (count($storeIds))
        {
            $this->getCollection()->setStoreFilter($storeIds);
        }
        return $this;
    }

    /**
     * Retrieves store ids for filter a collection
     *
     * @return array
     */
    public function getStoreIds()
    {
        if (!$this->_storeIds){
            $storeIds = array();
            if ($this->getRequest()->getParam('store')) {
                $storeIds = array($this->getParam('store'));
            } else if ($this->getRequest()->getParam('website')){
                $storeIds = Mage::app()->getWebsite($this->getRequest()->getParam('website'))->getStoreIds();
            } else if ($this->getRequest()->getParam('group')){
                $storeIds = Mage::app()->getGroup($this->getRequest()->getParam('group'))->getStoreIds();
            }
            $this->_storeIds = $storeIds;
        }
        return $this->_storeIds;
    }

    /**
     * Retrives filter string
     * @deprecated
     * @return string
     */
    protected function _getProcessStates()
    {
        $states = explode( ",", Mage::helper('advancedreports')->confProcessOrders() );
        $is_first = true;
        $filter = "";
        foreach ($states as $state)
        {
            if (!$is_first)
            {
            $filter .= " OR ";
            }
            $filter .= "val.value = '".$state."'";
            $is_first = false;
        }
        return "(".$filter.")";
    }

    /**
     * Prepare filter query
     * @param array $data Associative array
     * @return string
     */
    protected function _createQuery($data = array())
    {
        $str = array();
        foreach ($data as $key=>$value){
            $str[] = $key."=".$value;
        }        
        return implode("&", $str);
    }

    /**
     * Prepare filters before
     * @return AW_Advancedreports_Block_Advanced_Grid 
     */
    protected function _prepareFiltersBefore()
    {
        
//        var_dump("Yo!"); die;
        
        $filterStr = base64_decode($this->getRequest()->getParam($this->getVarNameFilter()));
        $filterStr = str_replace("%26", "XXXDUMMYAMPERSANDXXX", $filterStr);        
                
        # Preapre default date range        
        parse_str($filterStr, $data);        
        if (!isset($data['custom_date_range']) && !$this->getFilter('custom_date_range')){
            $data['custom_date_range'] = $this->_helper()->getDefaultCustomDateRange();
        }
        if (!isset($data['report_from']) && !$this->getFilter('report_from')) {
            $date = new Zend_Date(strtotime( date('m/01/y') ));
            $data['report_from'] = $date->toString($this->getLocale()->getDateFormat('short'));
        }
        if (!isset($data['report_to']) && !$this->getFilter('report_to')) {
            $date = new Zend_Date();
            $data['report_to'] = $date->toString($this->getLocale()->getDateFormat('short'));
        }                               
        if (!isset($data['reload_key']) && !$this->getFilter('reload_key')) {            
            foreach ($this->_helper()->getReloadKeys() as $key){
                $data['reload_key'] = $key['value'];
                break;
            }
        }                                       
        if (!isset($data['report_period']) && !$this->getFilter('report_period')) {      
            foreach ($this->_helper()->getReportPeriods() as $key=>$value){
                $data['report_period'] = $key;
                break;
            }
        }                               
        $filterStr = $this->_createQuery($data);        
        # finish preporation
                
        $filterStr = base64_encode($filterStr);
        $this->getRequest()->setParam($this->getVarNameFilter(), $filterStr);
        return $this;
    }
    
    /**
     * Prepare filters after
     * @return AW_Advancedreports_Block_Advanced_Grid 
     */
    protected function _prepareFiltersAfter()
    {
        foreach ($this->_filters as $key => &$value){
            $value = str_replace("XXXDUMMYAMPERSANDXXX", "&", $value);
        }
        return $this;
    }
    
    protected function _setUpFilters()
    {
        
        $filter = $this->getParam($this->getVarNameFilter(), null);

        if (is_null($filter)) {
            $filter = $this->_defaultFilter;
        }
                
        if (is_string($filter)) {
            $data = array();
            $filter = base64_decode($filter);                                  
            parse_str(urldecode($filter), $data);
            if (!isset($data['report_from'])) {
                // getting all reports from 2001 year
                $date = new Zend_Date(strtotime(date('m/01/y')));
                $data['report_from'] = $date->toString($this->getLocale()->getDateFormat('short'));
            }

            if (!isset($data['report_to'])) {
                // getting all reports from 2001 year
                $date = new Zend_Date();
                $data['report_to'] = $date->toString($this->getLocale()->getDateFormat('short'));
            }

            $this->_setFilterValues($data);
        } else if ($filter && is_array($filter)) {
            $this->_setFilterValues($filter);
        } else if(0 !== sizeof($this->_defaultFilter)) {
            $this->_setFilterValues($this->_defaultFilter);
        }

        return $this;
    }


    /**
     * Prepare collection to use
     * @return AW_Advancedreports_Block_Advanced_Grid
     */
    protected  function _prepareCollection()
    {
        $this->_setUpReportKey();
        $this->_prepareFiltersBefore();
        parent::_prepareCollection();
        $this->_prepareFiltersAfter();        
        
        $this->setCollection( Mage::getModel('advancedreports/order')->getCollection() );
        $date_from = $this->_getMysqlFromFormat($this->getFilter('report_from'));
        $date_to = $this->_getMysqlToFormat($this->getFilter('report_to'));

        $this->setDateFilter($date_from, $date_to)->setState();

        if ($this->getRequest()->getParam('store')) {
            $storeIds = array($this->getParam('store'));
        } else if ($this->getRequest()->getParam('website')){
            $storeIds = Mage::app()->getWebsite($this->getRequest()->getParam('website'))->getStoreIds();
        } else if ($this->getRequest()->getParam('group')){
            $storeIds = Mage::app()->getGroup($this->getRequest()->getParam('group'))->getStoreIds();
        }
        if (isset($storeIds))
        {
            $this->setStoreFilter($storeIds);
        }
        //$this->_prepareData();
               
        $this->_saveFilters();
        return $this;
    }

    /**
     * Collection
     *
     * @return AW_Advancedreports_Model_Mysql4_Collection_Abstract
     */
    public function getCollection()
    {
        return $this->_collection;
    }

    public function getFilter($name)
    {       
        if ($this->_helper()->getQueue()->confCrossreportFilters() && !$this->getIsExport()){            
            if ($this->_helper()->getQueue()->getLastFilter($name) && $this->_helper()->getView()->isReportChanged($this->getId(), $this->getViewKey())) {
                $value = $this->_helper()->getQueue()->getLastFilter($name);
            } else {
                $value = parent::getFilter($name);
            }            
            return $value;
        } else {
            return parent::getFilter($name);
        }
    }

    protected function _saveFilters()
    {
        $filterValues = array();
        foreach ($this->_filtersToSave as $filterKey){
            if ($this->getFilter($filterKey)){
                $filterValues[$filterKey] = $this->getFilter($filterKey);
            }
        }
        $this->_helper()->getQueue()->saveLastFilters($filterValues);
        return $filterValues;
    }

    protected function _setUpReportKey()
    {
        Mage::register(AW_Advancedreports_Helper_Setup::DATA_KEY_REPORT_ID, $this->getId(), true);
        Mage::register(AW_Advancedreports_Block_Adminhtml_Setup::DATA_KEY_REPORT_ROUTE, $this->getRoute(), true);
        return $this;
    }

    /**
     * Prepare collection to use by older method of prepare
     * @return AW_Advancedreports_Block_Advanced_Grid
     */
    protected function _prepareOlderCollection()
    {
        $this->_setUpReportKey();
        $this->_prepareFiltersBefore();
        parent::_prepareCollection();
        $this->_prepareFiltersAfter();
        $this->_saveFilters();
        return $this;
    }

//    /**
//     * Retrives ids separated by comma
//     * @param array $ids
//     * @return string
//     */
//    public function getStoreIds($ids = array())
//    {
//        if (count($ids))
//        {
//            $res = '';
//            $is_first = true;
//            foreach ($ids as $id)
//            {
//                $res .= $is_first ? $id : ",".$id;
//                $is_first = false;
//            }
//        }
//        return $res;
//    }

    /**
     * Retrives chart params to build line chart
     * @return array
     */
    public function getChartParams()
    {
        return Mage::helper('advancedreports')->getChartParams( $this->_routeOption );
    }

    /**
     * Retrives flag to show chart
     * @return boolean
     */
    public function hasRecords()
    {
        return (count( $this->_customData ) > 1)
               && Mage::helper('advancedreports')->getChartParams( $this->_routeOption )
               && count( Mage::helper('advancedreports')->getChartParams( $this->_routeOption ) );
    }

    public function getShowCustomGrid()
    {
        return true;
    }

    public function getHideNativeGrid()
    {
        return true;
    }

    public function getHideShowBy()
    {
        return true;
    }

    /**
     * Set up sort direction
     * @param string $id
     * @param string $dir
     */
    protected function _setColumnDir($id, $dir)
    {
        if (count($this->_columns)){
            foreach ($this->_columns as $column){
                if ($column->getId() == $id){
                    $column->setDir($dir);
                    $this->_sortBy = $id;
                    return;
                }
            }
        }
    }

    /**
     * Retrives sort key
     * @return string
     */
    protected function _getSort()
    {
        return $this->getRequest()->getParam('sort');
    }

    /**
     * Retrives sort direction
     * @return string
     */
    protected function _getDir()
    {
        return $this->getRequest()->getParam('dir');
    }

    /**
     * Prepare data to build grid
     */
    protected function _prepareData()
    {
        if ($this->_getDir()){
            $this->_setColumnDir($this->_getSort(), $this->_getDir());
        }
    }

    /**
     * Compare two objects for current sorting column and current direction
     *
     * @param Varien_Object $a
     * @param Varien_Object $b
     * @return integer
     */
    protected function _compareVarDataElements($a, $b)
    {
        $key = "get".Mage::helper('advancedreports')->getDataKey($this->_getSort());
        if ($a->$key() == $b->$key())
        {
            return 0;
        }

        # Custom percent sortage
        if ($this->_getSort() && $this->getColumn($this->_getSort()) && $this->getColumn($this->_getSort())->getData('custom_sorting_percent')){

            if ($this->_getDir() == "asc"){
                return ( (int)str_replace(' %', '', $a->$key()) < (int)str_replace(' %', '', $b->$key())) ? -1 : 1;
            } else {
                return ( (int)str_replace(' %', '', $a->$key()) > (int)str_replace(' %', '', $b->$key())) ? -1 : 1;
            }
        # Sorting by position (required for periods sorting)
        } elseif($this->_getSort() && $this->getColumn($this->_getSort()) && $this->getColumn($this->_getSort())->getData('is_position_sorting')) {
            if ($this->_getDir() == "asc"){
                return ($a->getData('sort_position') < $b->getData('sort_position')) ? -1 : 1;
            } else {
                return ($a->getData('sort_position') > $b->getData('sort_position')) ? -1 : 1;
            }
        } else {

            if ($this->_getDir() == "asc"){
                return ($a->$key() < $b->$key()) ? -1 : 1;
            } else {
                return ($a->$key() > $b->$key()) ? -1 : 1;
            }
        }
    }

    /**
     * Retrives is aggregated report type
     * @return boolean
     */
    public function hasAggregation()
    {
        return false;
    }


    public function getGridData()
    {
        # Aggregate data before this statement
        if ($this->hasAggregation()){
            return $this->getCollection();
        } else {
            return $this->getCustomVarData();
        }
    }

    /**
     * Retrives Custom Data array with Varien_Object converted data rows
     * @return array
     */
    public function getCustomVarData()
    {
        # Old method
        if ($this->_customVarData){
            return $this->_customVarData;
        }
        foreach ($this->_customData as $d){
            $obj = new Varien_Object();
            $obj->setData( $d );
            $this->_customVarData[] = $obj;
        }
        if (!$this->hasAggregation()){
            if ($this->_customVarData && is_array($this->_customVarData) && $this->_getSort() && $this->_getDir()){
                usort($this->_customVarData, array(&$this, "_compareVarDataElements") );
            }
        }
        return $this->_customVarData;
    }

    /**
     * Retrives empty Periods array
     * @return array
     */
    public function getPeriods()
    {
        return array();
    }

    /**
     * Retrives old version of getPeriods()
     * @return array
     */
    protected function _getOlderPeriods()
    {
        return parent::getPeriods();
    }

    /**
     * Retrives Excel file content
     * @param string $filename
     * @return string
     */
    public function getOldExcel($filename = '')
    {
        return parent::getExcel($filename);
    }

    /**
     * Retrives CSV file content
     * @return string
     */
    public function getOldCsv()
    {
        return parent::getCsv();
    }

    /**
     * Retrives Grand Totals array
     * @return array
     */
    public function getGrandTotals()
    {
        if (!$this->_grandTotals){
            $this->_grandTotals = new Varien_Object();
            if (count($this->_customData) || $this->hasAggregation()){
                foreach ($this->_columns as $column){
                    if (($column->getType() == "currency" || $column->getType() == "number") && !$column->getDisableTotal()){
                        $sum = 0;
                        if ($this->hasAggregation()){
                            $sum = $this->getCollection()->getTotal($column->getindex());
                        } else {
                            foreach ($this->_customData as $data){
                                if (isset($data[$column->getIndex()])){
                                    $sum += $data[$column->getIndex()];
                                }
                            }
                        }
                        $this->_grandTotals->setData($column->getIndex(), $sum);
                    }
                }
            }
        }
        return $this->_grandTotals;
    }

    /**
     * Retrives count of totals
     * @return int
     */
    public function getCountTotals()
    {
        $count = 0;
        foreach ($this->_columns as $column){
            if (($column->getType() == "currency" || $column->getType() == "number") && !$column->getDisableTotal()){
                $count ++;
            }
        }
        return $count;
    }

    /**
     * Retrives Excel file content
     * @param string $filename
     * @return string
     */
    public function getExcel($filename = '')
    {
        $this->_prepareGrid();

        $data = array();
        foreach ($this->getColumns() as $column)
        {
            if (!$column->getIsSystem() && $column->getIndex() != 'stores')
            {
                $row[] = $column->getHeader();
            }
        }
        $data[] = $row;
        if (count($this->getGridData())){
            foreach ($this->getGridData() as $obj)
            {
                $row = array();
                foreach ($this->getColumns() as $column)
                {
                    if (!$column->getIsSystem() && $column->getIndex() != 'stores')
                    {
                        $row[] = $column->getRowField($obj);
                    }
                }
                $data[] = $row;
            }
            if ($this->getNeedTotal() && $this->getCountTotals() && count( $this->getGridData() ))
            {
                $_isFirst = true;
                $row = array();
                foreach ($this->getColumns() as $_column){
                    if ($_isFirst){
                        $row[] = $this->getTotalText();
                    } elseif ($_column->getType() == "action" || $_column->getDisableTotal()) {
                        $row[] = "";
                    } else {
                        $row[] = $_column->getRowField($this->getGrandTotals());
                    }
                    $_isFirst = false;
                }
                $data[] = $row;
            }
        }
        $xmlObj = new Varien_Convert_Parser_Xml_Excel();
        $xmlObj->setVar('single_sheet', $filename);
        $xmlObj->setData($data);
        $xmlObj->unparse();
        return $xmlObj->getData();
    }

    /**
     * Retrives CSV file content
     * @return string
     */
    public function getCsv($filename = '')
    {
        $csv = '';
        $this->_prepareGrid();
        foreach ($this->getColumns() as $column) {
            if (!$column->getIsSystem() && $column->getIndex() != 'stores') {
                $data[] = '"'.$column->getHeader().'"';
            }
        }
        $csv.= implode(',', $data)."\n";

        if (!count($this->getGridData())){
            return $csv;
        }
        foreach ($this->getGridData() as $obj)
        {
            $data = array();
            foreach ($this->getColumns() as $column) {
                if (!$column->getIsSystem() && $column->getIndex() != 'stores') {
                    $data[] = '"'.str_replace(array('"', '\\'), array('""', '\\\\'), $column->getRowField($obj)).'"';
                }
            }
            $csv.= implode(',', $data)."\n";
        }

        if ($this->getNeedTotal() && $this->getCountTotals() && count( $this->getGridData() ))
        {
            $_isFirst = true;
            $data = array();
            foreach ($this->getColumns() as $_column){
                if ($_isFirst){
                    $data[] = '"'.str_replace(array('"', '\\'), array('""', '\\\\'), $this->getTotalText()).'"';
                } elseif ($_column->getType() == "action" || $_column->getDisableTotal()) {
                    $data[] = '"'.str_replace(array('"', '\\'), array('""', '\\\\'), "").'"';
                } else {
                    $data[] = '"'.str_replace(array('"', '\\'), array('""', '\\\\'), $_column->getRowField($this->getGrandTotals())).'"';
                }
                $_isFirst = false;
            }
            $csv.= implode(',', $data)."\n";
        }

        return $csv;
    }
}
