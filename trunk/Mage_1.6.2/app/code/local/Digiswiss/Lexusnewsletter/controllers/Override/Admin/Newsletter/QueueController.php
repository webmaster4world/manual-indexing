<?php

/**
 * Adminhtml newsletter queue controller
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
require_once 'Mage/Adminhtml/controllers/Newsletter/QueueController.php';

class Digiswiss_Lexusnewsletter_Override_Admin_Newsletter_QueueController extends Mage_Adminhtml_Newsletter_QueueController
{
      
    public function indexAction()
    {
        parent::indexAction();
    }
    
    public function sendingAction()
    {
        // Todo: put it somewhere in config!
        $countOfQueue  = 3;
        $countOfSubscritions = 20;

        $collection = Mage::getResourceModel('newsletter/queue_collection')
            ->setPageSize($countOfQueue)
            ->setCurPage(1)
            ->addOnlyForSendingFilter()
            ->load();
        Mage::log(__CLASS__ .'/' . __METHOD__ . PHP_EOL . 'sending que was called...');
        $collection->walk('sendPerSubscriber', array($countOfSubscritions));  
        /* corehack */
//         $this->_redirect('*/*');
    }

    public function saveAction()
    {
//         Mage::log(__CLASS__ .'/' . __METHOD__ . PHP_EOL . 'que was saved...');
        try {
            // create new queue from template, if specified
            $templateId = $this->getRequest()->getParam('template_id');
            if ($templateId) {
                $template = Mage::getModel('newsletter/template')->load($templateId);
                if (!$template->getId() || $template->getIsSystem()) {
                    Mage::throwException($this->__('Wrong newsletter template.'));
                }
                $template->preprocess();
                $queue = Mage::getModel('newsletter/queue')
                    ->setTemplateId($template->getId())
                    ->setQueueStatus(Mage_Newsletter_Model_Queue::STATUS_NEVER);
                $template->save();
            }
            else {
                $queue = Mage::getSingleton('newsletter/queue')
                    ->load($this->getRequest()->getParam('id'));
            }

            if (!in_array($queue->getQueueStatus(),
                          array(Mage_Newsletter_Model_Queue::STATUS_NEVER,
                                 Mage_Newsletter_Model_Queue::STATUS_PAUSE))) {
                   $this->_redirect('*/*');
                return;
            }

            $format = Mage::app()->getLocale()->getDateTimeFormat(
                Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM
            );

            if ($queue->getQueueStatus()==Mage_Newsletter_Model_Queue::STATUS_NEVER) {
                if ($this->getRequest()->getParam('start_at')) {
                    $date = Mage::app()->getLocale()->date($this->getRequest()->getParam('start_at'), $format);
                    $time = $date->getTimestamp();
                    $queue->setQueueStartAt(
                        Mage::getModel('core/date')->gmtDate(null, $time)
                    );
                } else {
                    $queue->setQueueStartAt(null);
                }
            }
            
            /* Hackpoint */
//     	    $queue->setStores($this->getRequest()->getParam('stores', array()));
            $queue->setStores($this->getRequest()->getParam('stores', array()), $this->getRequest()->getParam('channel'));            

            $queue->addTemplateData($queue);
            $queue->getTemplate()
                ->setTemplateSubject($this->getRequest()->getParam('subject'))
                ->setTemplateSenderName($this->getRequest()->getParam('sender_name'))
                ->setTemplateSenderEmail($this->getRequest()->getParam('sender_email'))
                ->setTemplateTextPreprocessed($this->getRequest()->getParam('text'));

            if ($queue->getQueueStatus() == Mage_Newsletter_Model_Queue::STATUS_PAUSE
                && $this->getRequest()->getParam('_resume', false)) {
                $queue->setQueueStatus(Mage_Newsletter_Model_Queue::STATUS_SENDING);
            }

            $queue->setSaveTemplateFlag(true);
            $queue->save();
            $this->_redirect('*/*');
        }
        catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $this->_redirect('*/*/edit', array('id' => $id));
            }
            else {
                $this->_redirectReferer();
            }
        }
    }

    
}

?>