<?php

class Digiswiss_Soapsync_Model_Ibramssync extends Mage_Core_Model_Abstract
{
	  protected $_mimearr = array('.JPG','.jpeg','.JPEG','.psd','.gif','.tif','.png','.bmp','.BMP','.ai','.eps');
    public function _construct()
    {
        parent::_construct();
        //Mage::log("Ibramssync model constructor called");
        $this->_init('soapsync/ibramssync');
    }
    
//     public function getConnection() {
//         $config = Mage::getConfig()->getResourceConnectionConfig('core_write');
//         $dbConfig = array(
//         		'host'      => $config->host,
//         		'username'  => $config->username,
//         		'password'  => $config->password,
//         		'dbname'    => $config->dbname,
//         		'driver_options'=> array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8')
//         );
//         return Zend_Db::factory('Pdo_Mysql', $dbConfig);    
//     }
    
    public function getImageName($efit_id) {
        $db_magento = Mage::helper('soapsync')->getConnection();
        $query = $db_magento->query("SELECT filename FROM aa_ibrams_media WHERE efit_id='$efit_id'");
        if ($result = $query->fetchObject()) {
            return $result->filename;
        } 
        return false;       
    }
    
    public function getAllImages($item_id) {
        $item = $this->load($item_id);
        $jpg = str_replace($this->_mimearr, '.jpg', $item->filename);
        if(!file_exists(Mage::getBaseDir('media') . DS . 'import/' . $jpg)){
            $ibramssoap = new Ibrams_Soap();
            try {
                $ibramssoap->downloadmedia(array($item->id), array($item->id => $jpg), Mage::getBaseDir('media') . DS . 'import/');
            } catch (Exception $e) {
//                 return $e;
            }
        } 
//         if(!file_exists(Mage::getBaseDir('media') . DS . 'import/jpg150/' . $jpg)){
//             if (!isset($ibramssoap)) $ibramssoap = new Ibrams_Soap();
//             try {
//                 $ibramssoap->downloadmedia(array($item->id), array($item->id => $jpg), Mage::getBaseDir('media') . DS . 'import/jpg150/', 150, '', '');
//             } catch (Exception $e) {
// //                 return $e;
//             }
//         }  
        if(!file_exists(Mage::getBaseDir('media') . DS . 'import/jpg300/' . $jpg)){
            if (!isset($ibramssoap)) $ibramssoap = new Ibrams_Soap();
            try {
                $ibramssoap->downloadmedia(array($item->id), array($item->id => $jpg), Mage::getBaseDir('media') . DS . 'import/jpg300/', 300, '', '');
            } catch (Exception $e) {
//                 return $e;
            }
        } 
                   
    }
    public function getProductImage($item_id) {
        $item = $this->load($item_id);
        $jpg = str_replace($this->_mimearr, '.jpg', $item->filename);
        if(!file_exists(Mage::getBaseDir('media') . DS . 'import/' . $jpg)){
            $ibramssoap = new Ibrams_Soap();
            try {
                $ibramssoap->downloadmedia(array($item->id), array($item->id => $jpg), Mage::getBaseDir('media') . DS . 'import/');
            } catch (Exception $e) {
                Mage::log('dlmedia error: ' . $e->getMessage());
//                 return $e;
            }
        } 
    }
}