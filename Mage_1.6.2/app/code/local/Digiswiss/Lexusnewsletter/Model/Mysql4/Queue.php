<?php
class Digiswiss_Lexusnewsletter_Model_Mysql4_Queue extends Mage_Newsletter_Model_Mysql4_Queue
{
        
    protected function _construct() 
    {
        parent::_construct();
    }
    
    public function setStores(Mage_Newsletter_Model_Queue $queue) 
    {
        $this->_getWriteAdapter()
            ->delete(
                $this->getTable('queue_store_link'), 
                $this->_getWriteAdapter()->quoteInto('queue_id = ?', $queue->getId())
            );
        
        if (!is_array($queue->getStores())) { 
            $stores = array(); 
        } else {
            $stores = $queue->getStores();
        }
        
        foreach ($stores as $storeId) {
            $data = array();
            $data['store_id'] = $storeId;
            $data['queue_id'] = $queue->getId();
            $this->_getWriteAdapter()->insert($this->getTable('queue_store_link'), $data);
        }
         
        $this->removeSubscribersFromQueue($queue);

        if(count($stores)==0) {
            return $this;
        }

        $subscribers = Mage::getResourceSingleton('newsletter/subscriber_collection')
            ->addFieldToFilter('store_id', array('in'=>$stores))
            ->useOnlySubscribed()
            ->filterChannel($queue->getChannel())
            ->load();
         
        $subscriberIds = array();
        
        foreach ($subscribers as $subscriber) {
            $subscriberIds[] = $subscriber->getId();
        }
        
        if (count($subscriberIds) > 0) {
            $this->addSubscribersToQueue($queue, $subscriberIds);
        }
        
        return $this;
    }
}