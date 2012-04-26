<?php
class Digiswiss_Lexusnewsletter_Model_Mysql4_Subscriber_Collection extends Mage_Newsletter_Model_Mysql4_Subscriber_Collection
{
    protected function _construct()
    {
        parent::_construct();
    }
    
    public function filterChannel($channel)
    {
        if ($channel !=='is_subscribed')
        {
          $this->_select->where("main_table.$channel > 0");
        }
        return $this;
    }
}