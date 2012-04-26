<?php
class Digiswiss_Lexuscustomer_Model_EntitySetup extends Mage_Customer_Model_Entity_Setup
{

  public function getDefaultEntities()
    {
        return array(
            'customer' => array(
                'entity_model'          =>'customer/customer',
                'table'                 => 'customer/entity',
                'increment_model'       => 'eav/entity_increment_numeric',
                'increment_per_store'   => false,
                'attributes' => array(
//                    'entity_id'         => array('type'=>'static'),
//                    'entity_type_id'    => array('type'=>'static'),
//                    'attribute_set_id'  => array('type'=>'static'),
//                    'increment_id'      => array('type'=>'static'),
//                    'created_at'        => array('type'=>'static'),
//                    'updated_at'        => array('type'=>'static'),
//                    'is_active'         => array('type'=>'static'),

                    'website_id' => array(
                        'type'          => 'static',
                        'label'         => 'Associate to Website',
                        'input'         => 'select',
                        'source'        => 'customer/customer_attribute_source_website',
                        'backend'       => 'customer/customer_attribute_backend_website',
                        'sort_order'    => 10,
                    ),
                    'store_id' => array(
                        'type'          => 'static',
                        'label'         => 'Create In',
                        'input'         => 'select',
                        'source'        => 'customer/customer_attribute_source_store',
                        'backend'       => 'customer/customer_attribute_backend_store',
                        'visible'       => false,
                        'sort_order'    => 20,
                    ),
                    'created_in' => array(
                        'type'          => 'varchar',
                        'label'         => 'Created From',
                        'sort_order'    => 30,
                    ),
                    'cust_type' => array(
                        'type'          => 'varchar',
                        'label'         => 'Kundentyp',
                        'required'      => true,
                        'sort_order'    => 31,
                    ),
                    'is_agvj' => array(
                        'type'          => 'varchar',
                        'label'         => 'SMJ',
                        'required'      => false,
                        'sort_order'    => 32,
                    ),
                    'medien' => array(
                        'type'          => 'varchar',
                        'label'         => 'Medien',
                        'required'      => false,
                        'sort_order'    => 33,
                    ),
                    'prefix' => array(
                        'label'         => 'Prefix',
                        'required'      => false,
                        'sort_order'    => 40,
                    ),
                    'firstname' => array(
                        'label'         => 'First Name',
                        'sort_order'    => 43,
                    ),
                    'middlename' => array(
                        'label'         => 'Middle Name/Initial',
                        'required'      => false,
                        'sort_order'    => 50,
                    ),
                    'lastname' => array(
                        'label'         => 'Last Name',
                        'sort_order'    => 53,
                    ),
                    'suffix' => array(
                        'label'         => 'Suffix',
                        'required'      => false,
                        'sort_order'    => 60,
                    ),
                    'email' => array(
                        'type'          => 'static',
                        'label'         => 'Email',
                        'class'         => 'validate-email',
                        'sort_order'    => 70,
                    ),
                    'group_id' => array(
                        'type'          => 'static',
                        'input'         => 'select',
                        'label'         => 'Customer Group',
                        'source'        => 'customer/customer_attribute_source_group',
                        'sort_order'    => 80,
                    ),
                    'dob' => array(
                        'type'          => 'datetime',
                        'input'         => 'date',
                        'backend'       => 'eav/entity_attribute_backend_datetime',
                        'required'      => false,
                        'label'         => 'Date Of Birth',
                        'sort_order'    => 90,
                    ),
                    'password_hash' => array(
                        'input'         => 'hidden',
                        'backend'       => 'customer/customer_attribute_backend_password',
                        'required'      => false,
                    ),
                    'default_billing' => array(
                        'type'          => 'int',
                        'visible'       => false,
                        'required'      => false,
                        'backend'       => 'customer/customer_attribute_backend_billing',
                    ),
                    'default_shipping' => array(
                        'type'          => 'int',
                        'visible'       => false,
                        'required'      => false,
                        'backend'       => 'customer/customer_attribute_backend_shipping',
                    ),
                    'taxvat' => array(
                        'label'         => 'Tax/VAT number',
                        'visible'       => true,
                        'required'      => false,
                        'position'      => 1,
                    ),
                    'confirmation' => array(
                        'label'         => 'Is confirmed',
                        'visible'       => false,
                        'required'      => false,
                    ),
                ),
            ),

            'customer_address'=>array(
                'entity_model'  =>'customer/customer_address',
                'table' => 'customer/address_entity',
                'attributes' => array(
//                    'entity_id'         => array('type'=>'static'),
//                    'entity_type_id'    => array('type'=>'static'),
//                    'attribute_set_id'  => array('type'=>'static'),
//                    'increment_id'      => array('type'=>'static'),
//                    'parent_id'         => array('type'=>'static'),
//                    'created_at'        => array('type'=>'static'),
//                    'updated_at'        => array('type'=>'static'),
//                    'is_active'         => array('type'=>'static'),
                    
                    'prefix' => array(
                        'label'         => 'Prefix',
                        'required'      => false,
                        'sort_order'    => 7,
                    ),
                    'firstname' => array(
                        'label'         => 'First Name',
                        'sort_order'    => 10,
                    ),
                    'middlename' => array(
                        'label'         => 'Middle Name/Initial',
                        'required'      => false,
                        'sort_order'    => 13,
                    ),
                    'lastname' => array(
                        'label'         => 'Last Name',
                        'sort_order'    => 20,
                    ),
                    'suffix' => array(
                        'label'         => 'Suffix',
                        'required'      => false,
                        'sort_order'    => 23,
                    ),
                    'company' => array(
                        'label'         => 'Company',
                        'required'      => false,
                        'sort_order'    => 30,
                    ),
                    'street' => array(
                        'type'          => 'text',
                        'backend'       => 'customer_entity/address_attribute_backend_street',
                        'input'         => 'multiline',
                        'label'         => 'Street Address',
                        'sort_order'    => 40,
                    ),
                    'city' => array(
                        'label'         => 'City',
                        'sort_order'    => 50,
                    ),
                    'country_id' => array(
                        'type'          => 'varchar',
                        'input'         => 'select',
                        'label'         => 'Country',
                        'class'         => 'countries',
                        'source'        => 'customer_entity/address_attribute_source_country',
                        'sort_order'    => 60,
                    ),
                    'region' => array(
                        'backend'       => 'customer_entity/address_attribute_backend_region',
                        'label'         => 'State/Province',
                        'class'         => 'regions',
                        'sort_order'    => 70,
                    ),
                    'region_id' => array(
                        'type'          => 'int',
                        'input'         => 'hidden',
                        'source'        => 'customer_entity/address_attribute_source_region',
                        'required'      => 'false',
                        'sort_order'    => 80,
                    ),
                    'postcode' => array(
                        'label'         => 'Zip/Postal Code',
                        'sort_order'    => 90,
                    ),
                    'telephone' => array(
                        'label'         => 'Telephone',
                        'sort_order'    => 100,
                    ),
                    'fax' => array(
                        'label'         => 'Fax',
                        'required'      => false,
                        'sort_order'    => 110,
                    ),
                ),
            ),
        );
    }
}


