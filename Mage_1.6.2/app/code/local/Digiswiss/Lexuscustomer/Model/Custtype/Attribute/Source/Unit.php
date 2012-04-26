<?php
class Digiswiss_Lexuscustomer_Model_Custtype_Attribute_Source_Unit extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = array(
                array(
                    'value' => '',
                    'label' => '',
                ),
                array(
                    'value' => 'Journalist',
                    'label' => 'Journalist',
                ),
                array(
                    'value' => 'Händler',
                    'label' => 'Händler',
                ),
                array(
                    'value' => 'EFAG-Mitarbeiter',
                    'label' => 'EFAG-Mitarbeiter',
                ),
                array(
                    'value' => 'Student',
                    'label' => 'Student',
                ),
                array(
                    'value' => 'Anderes',
                    'label' => 'Anderes',
                )
            );
        }
        return $this->_options;
    }
}