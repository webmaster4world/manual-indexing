<?php
require_once 'Mage/Adminhtml/controllers/CustomerController.php';
class Digiswiss_Lexuscustomer_Override_Admin_CustomerController extends Mage_Adminhtml_CustomerController
{
    
    public function indexAction()
    {
        parent::indexAction();
    }
    
    /**
     * Save customer action
     */
    
    
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $data = $this->_filterPostData($data);
	    print_r($data);
	    ///echo Zend_Debug::dump($files, null, true);
	    die(__FILE__);
	    
            $redirectBack   = $this->getRequest()->getParam('back', false);
            $this->_initCustomer('customer_id');
            /** @var Mage_Customer_Model_Customer */
            $customer = Mage::registry('current_customer');
            // Prepare customer saving data
            if (isset($data['account'])) {
                if (isset($data['account']['email'])) {
                    $data['account']['email'] = trim($data['account']['email']);
                }
                $customer->addData($data['account']);
            }
            // unset template data
            if (isset($data['address']['_template_'])) {
                unset($data['address']['_template_']);
            }

            $modifiedAddresses = array();

            if (! empty($data['address'])) {
                foreach ($data['address'] as $index => $addressData) {
                    if (($address = $customer->getAddressItemById($index))) {
                        $addressId           = $index;
                        $modifiedAddresses[] = $index;
                    } else {
                        $address   = Mage::getModel('customer/address');
                        $addressId = null;
                        $customer->addAddress($address);
                    }

                    $address->setData($addressData)
                            ->setId($addressId)
                            ->setPostIndex($index); // We need set post_index for detect default addresses
                }
            }
            // not modified customer addresses mark for delete
            foreach ($customer->getAddressesCollection() as $customerAddress) {
                if ($customerAddress->getId() && ! in_array($customerAddress->getId(), $modifiedAddresses)) {
                    $customerAddress->setData('_deleted', true);
                }
            } 
//             if(isset($data['subscription'])) {
//                 $customer->setIsSubscribed(true);
//             } else {
//                 $customer->setIsSubscribed(false);
//             }
            $customer->setIsSubscribed(false);
            
            $subscriber = Mage::getSingleton('newsletter/subscriber');            
            $subscriber->loadByCustomer($customer);
            $subscriber
                ->setNewsModelle((boolean)$data['account']['news_modelle'])
                ->setNewsTechnologie((boolean)$data['account']['news_technologie'])
                ->setNewsUnternehmen((boolean)$data['account']['news_unternehmen'])
                ->setNewsNachhaltigkeit((boolean)$data['account']['news_nachhaltigkeit'])
                ->setNewsEvents((boolean)$data['account']['news_events']);
            $subscriber->save();
            
            $isNewCustomer = !$customer->getId();
            try {
                if ($customer->getPassword() == 'auto') {
                    $sendPassToEmail = true;
                    $customer->setPassword($customer->generatePassword());
                }

                // force new customer active
                if ($isNewCustomer) {
                    $customer->setForceConfirmed(true);
                }

                Mage::dispatchEvent('adminhtml_customer_prepare_save',
                    array('customer' => $customer, 'request' => $this->getRequest())
                );

                $customer->save();
                // send welcome email
                if ($customer->getWebsiteId() && ($customer->hasData('sendemail') || isset($sendPassToEmail))) {
                    $storeId = $customer->getSendemailStoreId();
                    if ($isNewCustomer) {
                        $customer->sendNewAccountEmail('registered', '', $storeId);
                    }
                    // confirm not confirmed customer
                    elseif ((!$customer->getConfirmation())) {
                        $customer->sendNewAccountEmail('confirmed', '', $storeId);
                    }
                }

                // TODO? Send confirmation link, if deactivating account

                if ($newPassword = $customer->getNewPassword()) {
                    if ($newPassword == 'auto') {
                        $newPassword = $customer->generatePassword();
                    }
                    $customer->changePassword($newPassword);
                    $customer->sendPasswordReminderEmail();
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Customer was successfully saved'));
                Mage::dispatchEvent('adminhtml_customer_save_after',
                    array('customer' => $customer, 'request' => $this->getRequest())
                );

                if ($redirectBack) {
                    $this->_redirect('*/*/edit', array(
                        'id'    => $customer->getId(),
                        '_current'=>true
                    ));
                    return;
                }
            }
            catch (Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setCustomerData($data);
                $this->getResponse()->setRedirect($this->getUrl('*/customer/edit', array('id'=>$customer->getId())));
                return;
            }
        }
        $this->getResponse()->setRedirect($this->getUrl('*/customer'));
    }
//     public function saveAction()
//     {
//         if ($data = $this->getRequest()->getPost()) {
//             $redirectBack   = $this->getRequest()->getParam('back', false);
//             $this->_initCustomer('customer_id');
//             $customer = Mage::registry('current_customer');
// 
//             // Prepare customer saving data
//             if (isset($data['account'])) {
//                 if (isset($data['account']['email'])) {
//                     $data['account']['email'] = trim($data['account']['email']);
//                 }
//                 $customer->addData($data['account']);
//             }
// 
//             if (isset($data['address'])) {
//                 // unset template data
//                 if (isset($data['address']['_template_'])) {
//                     unset($data['address']['_template_']);
//                 }
// 
//                 foreach ($data['address'] as $index => $addressData) {
//                     $address = Mage::getModel('customer/address');
//                     $address->setData($addressData);
// 
//                     if ($addressId = (int) $index) {
//                         $address->setId($addressId);
//                     }
//                     /**
//                      * We need set post_index for detect default addresses
//                      */
//                     $address->setPostIndex($index);
//                     $customer->addAddress($address);
//                 }
//             }
// 
//             if(isset($data['subscription'])) {
//                 $customer->setIsSubscribed(true);
//             } else {
//                 $customer->setIsSubscribed(false);
//             }
//             
//             $subscriber = Mage::getSingleton('newsletter/subscriber');            
//             $subscriber->loadByCustomer($customer);
//             $subscriber
//                 ->setNewsModelle((boolean)$data['account']['news_modelle'])
//                 ->setNewsTechnologie((boolean)$data['account']['news_technologie'])
//                 ->setNewsUnternehmen((boolean)$data['account']['news_unternehmen'])
//                 ->setNewsNachhaltigkeit((boolean)$data['account']['news_nachhaltigkeit'])
//                 ->setNewsEvents((boolean)$data['account']['news_events']);
//             $subscriber->save();
//             
// //             Mage::log('news_modelle: ' . $data['account']['news_modelle']);
//             
//             $isNewCustomer = !$customer->getId();
//             try {
//                 if ($customer->getPassword() == 'auto') {
//                     $sendPassToEmail = true;
//                     $customer->setPassword($customer->generatePassword());
//                 }
// 
//                 // force new customer active
//                 if ($isNewCustomer) {
//                     $customer->setForceConfirmed(true);
//                 }
// 
//                 Mage::dispatchEvent('adminhtml_customer_prepare_save',
//                     array('customer' => $customer, 'request' => $this->getRequest())
//                 );
// 
//                 $customer->save();
//                 // send welcome email
//                 if ($customer->getWebsiteId() && ($customer->hasData('sendemail') || isset($sendPassToEmail))) {
//                     $storeId = $customer->getSendemailStoreId();                    
//                     if ($isNewCustomer) {
//                         $customer->sendNewAccountEmail('registered', '', $storeId);
//                     }
//                     // confirm not confirmed customer
//                     elseif ((!$customer->getConfirmation())) {
//                         $customer->sendNewAccountEmail('confirmed', '', $storeId);
//                     }
//                 }
// 
//                 // TODO? Send confirmation link, if deactivating account
// 
//                 if ($newPassword = $customer->getNewPassword()) {
//                     if ($newPassword == 'auto') {
//                         $newPassword = $customer->generatePassword();
//                     }
//                     $customer->changePassword($newPassword);
//                     $customer->sendPasswordReminderEmail();
//                 }
// 
//                 Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Customer was successfully saved'));
//                 Mage::dispatchEvent('adminhtml_customer_save_after',
//                     array('customer' => $customer, 'request' => $this->getRequest())
//                 );
// 
//                 if ($redirectBack) {
//                     $this->_redirect('*/*/edit', array(
//                         'id'    => $customer->getId(),
//                         '_current'=>true
//                     ));
//                     return;
//                 }
//             }
//             catch (Exception $e){
//                 Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
//                 Mage::getSingleton('adminhtml/session')->setCustomerData($data);
//                 $this->getResponse()->setRedirect($this->getUrl('*/customer/edit', array('id'=>$customer->getId())));
//                 return;
//             }
//         }
//         $this->getResponse()->setRedirect($this->getUrl('*/customer'));
//     }

}

