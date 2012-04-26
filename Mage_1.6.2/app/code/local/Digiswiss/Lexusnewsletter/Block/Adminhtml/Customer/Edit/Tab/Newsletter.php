<?php
class Digiswiss_Lexusnewsletter_Block_Adminhtml_Customer_Edit_Tab_Newsletter extends Mage_Adminhtml_Block_Widget_Form
{

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('customer/tab/newsletter.phtml');
    }

    public function initForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('_newsletter');
        $customer = Mage::registry('current_customer');
        $subscriber = Mage::getModel('newsletter/subscriber')->loadByCustomer($customer);
        Mage::register('subscriber', $subscriber);

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('customer')->__('Newsletter Information')));

        $fieldset->addField('subscription', 'checkbox',
             array(
                    'label' => Mage::helper('customer')->__('Subscribed to Newsletter?'),
                    'name'  => 'subscription'
             )
        );

        if ($customer->isReadonly()) {
            $form->getElement('subscription')->setReadonly(true, true);
        }

        $form->getElement('subscription')->setIsChecked($subscriber->isSubscribed());

        if($changedDate = $this->getStatusChangedDate()) {
             $fieldset->addField('change_status_date', 'label',
                 array(
                        'label' => $subscriber->isSubscribed() ? Mage::helper('customer')->__('Last date subscribed') : Mage::helper('customer')->__('Last date unsubscribed'),
                        'value' => $changedDate,
                        'bold'  => true
                 )
            );
        }

        $form->getElement('subscription')->setDisabled('true');
        
        $this->setForm($form);
        return $this;
    }

    public function getStatusChangedDate()
    {
        $subscriber = Mage::registry('subscriber');
        if($subscriber->getChangeStatusAt()) {
            return $this->formatDate($subscriber->getChangeStatusAt(), Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM, true);
        }

        return null;
    }

    protected function _prepareLayout()
    {
        $this->setChild('grid',
            $this->getLayout()->createBlock('adminhtml/customer_edit_tab_newsletter_grid','newsletter.grid')
        );
        return parent::_prepareLayout();
    }

}
