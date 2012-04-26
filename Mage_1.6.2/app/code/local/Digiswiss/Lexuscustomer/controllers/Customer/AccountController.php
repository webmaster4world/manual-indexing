<?php
require_once('Mage/Customer/controllers/AccountController.php');
class Digiswiss_Lexuscustomer_Customer_AccountController extends Mage_Customer_AccountController
{

    protected function _loginPostRedirect()
    {
        $session = $this->_getSession();                                  
        $session->setBeforeAuthUrl(Mage::getBaseUrl());
        $this->_redirectUrl($session->getBeforeAuthUrl(true));
    }

    
    /**
     * Create customer account action
     */
    public function createPostAction()
    {
        $session = $this->_getSession();
        if ($session->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $session->setEscapeMessages(true); // prevent XSS injection in user input
        if ($this->getRequest()->isPost()) {
            $errors = array();

            if (!$customer = Mage::registry('current_customer')) {
                $customer = Mage::getModel('customer/customer')->setId(null);
            }

            foreach (Mage::getConfig()->getFieldset('customer_account') as $code=>$node) {
                if ($node->is('create') && ($value = $this->getRequest()->getParam($code)) !== null) {
                    if ($code == 'email') {
                        $value = trim($value);
                    }
                    $customer->setData($code, $value);
                }
            }

            /*
            if ($this->getRequest()->getParam('is_subscribed', false)) {
                $customer->setIsSubscribed(1);
            }
            */
            
            /* Hackpoint */
            $customer->setNewsModelle((boolean)$this->getRequest()->getParam('news_modelle', false));
            $customer->setNewsTechnologie((boolean)$this->getRequest()->getParam('news_technologie', false));
            $customer->setNewsUnternehmen((boolean)$this->getRequest()->getParam('news_unternehmen', false));
            $customer->setNewsNachhaltigkeit((boolean)$this->getRequest()->getParam('news_nachhaltigkeit', false));
            $customer->setNewsEvents((boolean)$this->getRequest()->getParam('news_events', false));
            // subscribe any customers 
            $customer->setIsSubscribed(1);
            /* end hackpoint */


            /**
             * Initialize customer group id
             */
            $customer->getGroupId();

            if ($this->getRequest()->getPost('create_address')) {
                $address = Mage::getModel('customer/address')
                    ->setData($this->getRequest()->getPost())
                    ->setIsDefaultBilling($this->getRequest()->getParam('default_billing', false))
                    ->setIsDefaultShipping($this->getRequest()->getParam('default_shipping', false))
                    ->setId(null);
                $customer->addAddress($address);

                $errors = $address->validate();
                if (!is_array($errors)) {
                    $errors = array();
                }
            }

            try {
                $validationCustomer = $customer->validate();
                if (is_array($validationCustomer)) {
                    $errors = array_merge($validationCustomer, $errors);
                }
                $validationResult = count($errors) == 0;

                if (true === $validationResult) {
                    $customer->save();

                    if ($customer->isConfirmationRequired()) {
                        $customer->sendNewAccountEmail('confirmation', $session->getBeforeAuthUrl());
                        $session->addSuccess($this->__('Account confirmation is required. Please, check your e-mail for confirmation link. To resend confirmation email please <a href="%s">click here</a>.', Mage::helper('customer')->getEmailConfirmationUrl($customer->getEmail())));
                        $this->_redirectSuccess(Mage::getUrl('*/*/index', array('_secure'=>true)));
                        return;
                    }
                    else {
                        $session->setCustomerAsLoggedIn($customer);
                        $url = $this->_welcomeCustomer($customer);
                        $this->_redirectSuccess($url);
                        return;
                    }
                } else {
                    $session->setCustomerFormData($this->getRequest()->getPost());
                    if (is_array($errors)) {
                        foreach ($errors as $errorMessage) {
                            $session->addError($errorMessage);
                        }
                    }
                    else {
                        $session->addError($this->__('Invalid customer data'));
                    }
                }
            }
            catch (Mage_Core_Exception $e) {
                $session->setCustomerFormData($this->getRequest()->getPost());
                if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
                    $url = Mage::getUrl('customer/account/forgotpassword');
                    $message = $this->__('There is already an account with this emails address. If you are sure that it is your email address, <a href="%s">click here</a> to get your password and access your account.', $url);
                    $session->setEscapeMessages(false);
                }
                else {
                    $message = $e->getMessage();
                }
                $session->addError($message);
            }
            catch (Exception $e) {
                $session->setCustomerFormData($this->getRequest()->getPost())
                    ->addException($e, $this->__('Can\'t save customer'));
            }
        }
        $this->_redirectError(Mage::getUrl('*/*/create', array('_secure' => true)));
    }
    
    /**
     * Add welcome message and send new account email.
     * Returns success URL
     *
     * @param Mage_Customer_Model_Customer $customer
     * @param bool $isJustConfirmed
     * @return string
     */
    protected function _welcomeCustomer(Mage_Customer_Model_Customer $customer, $isJustConfirmed = false)
    {
        $this->_getSession()->addSuccess($this->__('Thank you for registering with %s', Mage::app()->getStore()->getFrontendName()));

//         $customer->sendNewAccountEmail($isJustConfirmed ? 'confirmed' : 'registered');

//         $successUrl = Mage::getUrl('*/*/index', array('_secure'=>true));
        $successUrl = Mage::getUrl('*/*/login/', array('_secure'=>true));
//         if ($this->_getSession()->getBeforeAuthUrl()) {
//             $successUrl = $this->_getSession()->getBeforeAuthUrl(true);
//         }
        return $successUrl;
    }
     /**
     * Login post action
     */
    public function loginPostAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $session = $this->_getSession();

        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    $session->login($login['username'], $login['password']);
                    if ($session->getCustomer()->getIsJustConfirmed()) {
                        $this->_welcomeCustomer($session->getCustomer(), true);
                    }
                } catch (Mage_Core_Exception $e) {
                    switch ($e->getCode()) {
                        case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
                            $message = Mage::helper('customer')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', Mage::helper('customer')->getEmailConfirmationUrl($login['username']));
                            break;
                        case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                            $message = $e->getMessage();
                            break;
                        default:
                            $message = $e->getMessage();
                    }
                    $session->addError($message);
                    $session->setUsername($login['username']);
                } catch (Exception $e) {
                    // Mage::logException($e); // PA DSS violation: this exception log can disclose customer password
                }
            } else {
                $session->addError($this->__('Login and password are required'));
            }
        }
        if($forwardTo = Mage::getSingleton( 'customer/session' )->getForwardTo()) {
           Mage::getSingleton( 'customer/session' )->setForwardTo(''); 
           $this->_redirectUrl(Mage::getBaseUrl() . $forwardTo);
        } else {
            $this->_loginPostRedirect();
        }
    }

}

