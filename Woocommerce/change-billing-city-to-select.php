<?php
add_filter( 'woocommerce_billing_fields', 'custom_woocommerce_billing_fields' );

function custom_woocommerce_billing_fields( $fields ) {

   $fields['billing_city']  = array(
      'type'            => 'select',
      'label'          => __('City', 'woothemes'),
      'placeholder'    => __('City', 'woothemes'),
      'required'       => true,
      'class'          => array('billing-city'),
      'options'     => array(
            'paris' => __('Paris', 'woothemes' ),
            'london' => __('London', 'woothemes' ),
            'algiers' => __('Algiers', 'woothemes' )
        )
   );

 return $fields;
}
