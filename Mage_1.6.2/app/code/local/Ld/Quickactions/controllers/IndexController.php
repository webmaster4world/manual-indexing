<?php

/*
 * Quick action controller for additional functions
 * 
 * - easy to understand
 * - no new objects
 * - no core behaviour changes
 *
 * Author: Loaden Development
 * Website: http://www.loaden-development.com
 */

class Ld_Quickactions_IndexController extends Mage_Core_Controller_Front_Action {

    // manages incoming actions for controller
    // ***MAGE_URL***/index.php/quickactions/?action=***ACTIONNAME*** 
    public function indexAction() {

        // also access via $this->getRequest();
        $action = $_GET['action'];

        if (!empty($action)) {
            if ($action != 'indexAction') {
                if (method_exists($this, $action)) {
                    $reflection = new ReflectionMethod($this, $action);
                    if ($reflection->isPublic()) {
                        $this->{$action}();
                    } else {
                        throw new RuntimeException($this->__('The called method is not public.'));
                    }
                } else {
                    throw new RuntimeException($this->__('The called method not exist.'));
                }
            } else {
                throw new RuntimeException($this->__('indexAction method call not allowed.'));
            }
        } else {
            $this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
        }
    }

    // add related products without viewed product to cart logic fix 
    public function relatedtocart() {

        // also access via $this->getRequest();
        $relatedproducts = ($_POST['related_products']) ? $_POST['related_products'] : $_GET['related_products'];
        $currentproduct = ($_POST['current_product']) ? $_POST['current_product'] : $_GET['current_product'];

        if ((!empty($relatedproducts)) && (!empty($currentproduct))) {

            if (is_array($relatedproducts)) {

                try {

                    $session = Mage::getSingleton('core/session', array('name' => 'frontend'));
                    $cart = Mage::helper('checkout/cart')->getCart();

                    foreach ($relatedproducts as $productid => $productqty) {

                        $product = Mage::getModel('catalog/product')->load($productid);

                        if (!isset($productqty)) {

                            $qty = 1;
                        } else {

                            $qty = $productqty;
                        }
                        $links = Mage::getModel('downloadable/product_type')->getLinks($product);

                        if (is_array($links)) {

                            $linksforcart = array();

                            foreach ($links as $link)
                                $linksforcart[] = $link->getLinkId();

                            $qty = array('qty' => $qty, 'links' => $linksforcart);

                            $request = new Varien_Object();
                            $request->setData($qty);
                        }

                        $cart->addProduct($product, $qty);
                        $session->setLastAddedProductId($product->getId());
                    }
                    $session->setCartWasUpdated(true);
                    $cart->save();
                    Mage::getSingleton('checkout/session')->addSuccess($this->__('Ausgewählte Medien erfolgreich in den Medienkorb gelegt'));
                    $this->_redirectUrl(Mage::helper('catalog/product')->getProductUrl(Mage::getModel('catalog/product')->load($currentproduct)));
                } catch (Exception $e) {

                    Mage::getSingleton('checkout/session')->addError($this->__($e->getMessage()));
                    $this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
                }
            }
        }
    }

    // one click checkout, redirect to my downloads on success requires one page checkout
    public function directdownloadcheckout() {




        try {

            $customer = Mage::getSingleton('checkout/session')->getQuote()->getCustomer();

            if ($billig = $customer->getDefaultBilling())
                $billig = Mage::getModel('customer/address')->load($billig);

            if ($shipping = $customer->getDefaultShipping()) {

                $shipping = Mage::getModel('customer/address')->load($shipping);
            } else {

                $shipping = $billig;
            }
            $checkout = Mage::getSingleton('checkout/type_onepage');
            $checkout->initCheckout();
            $checkout->saveCheckoutMethod('guest');
            $checkout->saveShipping($shipping, false);
            $checkout->saveBilling($billig, false);
            $checkout->saveShippingMethod('download');
            $checkout->savePayment(array('method' => 'free'));
            $checkout->saveOrder();
            $items = Mage::getSingleton('checkout/session')->getQuote()->getItemsCollection();

            foreach ($items as $item) {

                Mage::getSingleton('checkout/cart')->removeItem($item->getId())->save();
            }
            Mage::getSingleton('checkout/session')->clear();
            Mage::getSingleton('customer/session')->addSuccess($this->__('Click on Download to download your Media cart as ZIP'));
            $this->_redirectUrl(Mage::helper('core/url')->getHomeUrl() . 'downloadable/customer/products/');
        } catch (Exception $e) {

            Mage::getSingleton('checkout/session')->addError($this->__($e->getMessage()));
            $this->_redirectUrl(Mage::helper('core/url')->getHomeUrl() . 'checkout/cart/');
        }
    }

    // create a archive from file selection, start download
    public function downloadfileselection() {
        $_mark = date('U');
        $file_basedir_download = Mage::getBaseDir() . '/media/downloadable/files/links';
	
	/////change-1.txt:
        //////if (!$zip->addFile(Mage::getBaseDir('media').'/downloadable/files/links'.$link->getLink_file())) {


        Mage::log('Start ' . __METHOD__, null, 'zip_basket.log');
        // also access via $this->getRequest();
        $files = ($_POST['download_files']) ? $_POST['download_files'] : $_GET['download_files'];
        Mage::log('New File list: ' . Zend_Debug::dump($files, null, false), null, 'zip_basket.log');

        if (is_array($files)) {

            try {
                if (chdir(Mage::getBaseDir() . '/var/tmp/')) {
                    Mage::log('cdir ok   ' . __METHOD__, null, 'zip_basket.log');
                    $zip = new ZipArchive();
                    $tmpfile = 'MediaDir_' . date('U') . '.zip';
                    @touch($tmpfile);
                    /* original write on file and dir */
                    /* permission to write here! */
                    if (!is_writable($tmpfile)) {
                        die('unable to write zip on dir Basedir->  var/tmp!');
                    }
                    Mage::log('Prepare new file zip ' . $tmpfile, null, 'zip_basket.log');
                    $_pack_desktop_ok = @mkdir($_mark, 0777, true);
                    $res = $zip->open($tmpfile, ZipArchive::CREATE);
                    if ($res === TRUE) {
                        Mage::log('Zip class ok responder  on ' . $tmpfile, null, 'zip_basket.log');
                    } else {
                        die('Can not create temporary Zip file.');
                    }

                    $_filepacker = array();
                    $error_link = 0;
                    /* append & mode file  to tmp dir */
                    foreach ($files as $fileid => $check) {
                        $_filedisk = 'OhneAngaben';
                        Mage::log('check on loop ->' . $check, null, 'zip_basket.log');
                        Mage::log('fileid on loop ->' . $fileid, null, 'zip_basket.log');
                        $_filedisk = $file_basedir_download . Mage::getModel('downloadable/link')->load($fileid)->getLink_file();

                        if (is_file($_filedisk)) {
                            /*  move & pack file */
                            Mage::log('file is ok  ->' . $_filedisk, null, 'zip_basket.log');
                            $_dest_tmp = $_mark . '/' . basename($_filedisk);
                            @copy($_filedisk, $_dest_tmp);
                            if (is_file($_dest_tmp)) {
                                if (!$zip->addFile($_dest_tmp))
                                    throw new RuntimeException('Can not add file to Archive.');
                            }
                        } else {
                            $error_link++;
                            $_dest_tmp = $_mark . '/' . $error_link . '.txt';
                            @file_put_contents($_dest_tmp, 'Unable to find file: ' . $_filedisk);
                            Mage::log('file not !!!!   ok  ->' . $_filedisk, null, 'zip_basket.log');
                            if (is_file($_dest_tmp)) {
                                if (!$zip->addFile($_dest_tmp))
                                    throw new RuntimeException('Can not add file to Archive.');
                            }
                        }

                        unset($_filedisk);
                    }



                    $zip->close();
                    /*  remove tmp dir */

                    $rmok = $this->CleanTmpDir($_mark);
                    Mage::log('Remove dir->' . $rmok . '  ' . __METHOD__, null, 'zip_basket.log');

                    /* check file size ?? */
                    $file = file_get_contents($tmpfile);
                    $this->getResponse()
                            ->setBody($file)
                            ->setHeader('Content-Type', 'application/zip')
                            ->setHeader('Content-Disposition', 'attachment; filename="' . basename($tmpfile) . '"')
                            ->setHeader('Content-Length', strlen($file));
                    /* to debug here uncomment the next 2 line */
                    @unlink($tmpfile);

                    /* recursive remote tmp dir */
                }
            } catch (Exception $e) {
                Mage::log('Exeption ->e()' . $e->getMessage(), null, 'zip_basket.log');
                echo $this->__($e->getMessage());
                $this->_redirectUrl(Mage::helper('core/url')->getHomeUrl() . 'downloadable/customer/products/');
            }
        } else {
            Mage::log('No array files incomming!  ' . __METHOD__, null, 'zip_basket.log');
            die('No files selected!  ' . __FILE__);
        }
        @chdir(Mage::getBaseDir());
        Mage::log('Ende  ' . __METHOD__, null, 'zip_basket.log');
    }

    /**
     * 
     * clean tmp dir from zip 
     */
    private function CleanTmpDir($unixtime) {

        $dir = Mage::getBaseDir() . '/var/tmp/' . $unixtime . '/';
        $i = 0;
        if (is_dir($dir)) {
            foreach (scandir($dir) as $item) {
                if ($item == '.' || $item == '..')
                    continue;
                
                if (filetype($dir."/".$item) != "dir") {
                    if (unlink($dir."/".$item)) {
                        $i++;
                    }
                }
                ////  no dir! 
            }
            @rmdir($dir);
        }
        
        return $i;
    }

    //
    // EXAMPLE
    // call ***MAGE_URL***/index.php/quickactions/?action=ExampleAction
    // method name must be equal to action name for execution
    // use public methods to extend more actions
    //
	public function ExampleAction() {
        
    }

    //
    // not public actions, not available via this action manager
    //
	private function NotAvailableMethod() {
        
    }

}

?>