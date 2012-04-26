<?php
class Digiswiss_Lexusnewsletter_Model_Queue extends Mage_Newsletter_Model_Queue
{
    protected $_channel;
    
    protected function _construct()
    {
        parent::_construct();
    }

    public function setStores(array $storesIds, $channel = '')
    {
        $this->setSaveStoresFlag(true);
        $this->_channel = $channel; // added
        $this->_stores = $storesIds;        
        return $this;
    }
    
    public function getChannel()
    {
        return $this->_channel;
    }
//     public function setStores(array $storesIds)
//     {
//         $this->setSaveStoresFlag(true);
//         $this->_stores = $storesIds;
//         return $this;
//     }
}
