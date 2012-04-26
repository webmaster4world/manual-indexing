<?php
class Digiswiss_Lexuscustomer_Model_Customer extends Mage_Customer_Model_Customer
{
    public function sendNewAccountEmail($type = 'registered', $backUrl = '', $storeId = '0')
    {
        /* corehack */
        // welcome email disabled!
        return $this;
    }
}