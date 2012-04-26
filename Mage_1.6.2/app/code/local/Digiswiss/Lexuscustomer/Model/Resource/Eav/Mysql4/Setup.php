<?php
class Digiswiss_Lexuscustomer_Model_Resource_Eav_Mysql4_Setup extends Mage_Eav_Model_Entity_Setup
{
    /**
     * @return array
     */
    public function getDefaultEntities()
    {
        return array(
            'customer' => array(
                'entity_model'                   => 'customer/customer',
                'attribute_model'                => 'customer/attribute',
                'table'                          => 'customer/entity',
                'increment_model'                => 'eav/entity_increment_numeric',
                'additional_attribute_table'     => 'customer/eav_attribute',
                'entity_attribute_collection'    => 'customer/attribute_collection',	       
                'attributes'        => array(
                    'cust_type' => array(
                        'label'             => 'Customer Type',
                        'type'              => 'varchar',
                        'input'     => 'select',
                        'source'            => 'digiswiss_lexuscustomer_model_custtype_attribute_source_unit',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'visible'           => true,
                        'required'          => false,
                        'user_defined'      => false,
                        'searchable'        => false,
                        'filterable'        => false,
                        'comparable'        => false,
                        'visible_on_front'  => false,
                        'visible_in_advanced_search' => false,
                        'unique'            => false,
                        'is_configurable'   => true
                    ),
                    'is_agvj' => array(
                        'label'             => 'SMJ Member',
                        'type'              => 'int',
                        'input'             => 'select',
                        'source'            => 'eav/entity_attribute_source_boolean',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'visible'           => true,
                        'required'          => false,
                        'user_defined'      => false,
                        'searchable'        => false,
                        'filterable'        => false,
                        'comparable'        => false,
                        'visible_on_front'  => false,
                        'visible_in_advanced_search' => false,
                        'unique'            => false,
                        'is_configurable'   => true
                    ),
                    'medien' => array(
                        'label'             => 'Medien',
                        'type'              => 'varchar',
                        'input'             => 'text',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'visible'           => true,
                        'required'          => false,
                        'user_defined'      => false,
                        'searchable'        => false,
                        'filterable'        => false,
                        'comparable'        => false,
                        'visible_on_front'  => false,
                        'visible_in_advanced_search' => false,
                        'unique'            => false,
                        'is_configurable'   => true
                    )/*
                    ,
                    'news_modelle' => array(
                        'label'             => 'Modelle',
                        'type'              => 'int',
                        'input'             => 'select',
                        'source'            => 'eav/entity_attribute_source_boolean',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'visible'           => true,
                        'required'          => false,
                        'user_defined'      => false,
                        'searchable'        => false,
                        'filterable'        => false,
                        'comparable'        => false,
                        'visible_on_front'  => false,
                        'visible_in_advanced_search' => false,
                        'unique'            => false,
                        'is_configurable'   => true
                    ),
                    'news_technologie' => array(
                        'label'             => 'Technologie',
                        'type'              => 'int',
                        'input'             => 'select',
                        'source'            => 'eav/entity_attribute_source_boolean',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'visible'           => true,
                        'required'          => false,
                        'user_defined'      => false,
                        'searchable'        => false,
                        'filterable'        => false,
                        'comparable'        => false,
                        'visible_on_front'  => false,
                        'visible_in_advanced_search' => false,
                        'unique'            => false,
                        'is_configurable'   => true
                    )
                    'news_unternehmen' => array(
                        'label'             => 'Unternehmen',
                        'type'              => 'int',
                        'input'             => 'select',
                        'source'            => 'eav/entity_attribute_source_boolean',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'visible'           => true,
                        'required'          => false,
                        'user_defined'      => false,
                        'searchable'        => false,
                        'filterable'        => false,
                        'comparable'        => false,
                        'visible_on_front'  => false,
                        'visible_in_advanced_search' => false,
                        'unique'            => false,
                        'is_configurable'   => true
                    ),
                    'news_nachhaltigkeit' => array(
                        'label'             => 'Nachhaltigkeit',
                        'type'              => 'int',
                        'input'             => 'select',
                        'source'            => 'eav/entity_attribute_source_boolean',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'visible'           => true,
                        'required'          => false,
                        'user_defined'      => false,
                        'searchable'        => false,
                        'filterable'        => false,
                        'comparable'        => false,
                        'visible_on_front'  => false,
                        'visible_in_advanced_search' => false,
                        'unique'            => false,
                        'is_configurable'   => true
                    ),
                    'news_events' => array(
                        'label'             => 'Events',
                        'type'              => 'int',
                        'input'             => 'select',
                        'source'            => 'eav/entity_attribute_source_boolean',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'visible'           => true,
                        'required'          => false,
                        'user_defined'      => false,
                        'searchable'        => false,
                        'filterable'        => false,
                        'comparable'        => false,
                        'visible_on_front'  => false,
                        'visible_in_advanced_search' => false,
                        'unique'            => false,
                        'is_configurable'   => true
                 		) */
               )
           ),
      );
    }
}

