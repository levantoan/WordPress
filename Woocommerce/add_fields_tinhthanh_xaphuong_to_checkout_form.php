<?php
/*
Add fields after country field to checkout form woocommerce
Show/hiden field when select country
Ajax change
*/
function array_insert_after($key, array &$array, $new_key, $new_value) {
  if (array_key_exists ($key, $array)) {
    $new = array();
    foreach ($array as $k => $value) {
      $new[$k] = $value;
      if ($k === $key) {
        $new[$new_key] = $new_value;
      }
    }
    return $new;
  }
  return FALSE;
}

add_filter('woocommerce_get_country_locale', 'devvn_change_field_checkout_by_vietnam');
function devvn_change_field_checkout_by_vietnam($default){
	$vn = array(
		'VN' => array(
			'postcode_before_city' => true,
			'state' => array(
				'required' => false
			),
			'postcode' => array(
				'required' => false,
				'hidden'   => false
			),
			'address_2' => array(
				'required' => false,
				'hidden'   => true
			),
			'country2'	=> array(
				'hidden'   => true
			)
		)
	);
	unset($default['VN']);
	$default = array_merge($default,$vn);
	return $default;
}

add_filter('woocommerce_default_address_fields', 'devvn_woocommerce_default_address_fields');
function devvn_woocommerce_default_address_fields($fields){
	$new_field = array(
		'type'     => 'select',
		'label'    => __( 'Country 2', 'woocommerce' ),
		'required' => false,
		'class'    => array( 'form-row-wide', 'address-field' ),
		'options'     => array(
	        'eat-meat' => __('I eat maet', 'woocommerce' ),
	        'not-meat' => __('Meat is gross', 'woocommerce' )
	    )
	);
	$fields = array_insert_after('country',$fields,'country2',$new_field);
	return $fields;
}

/*add_filter('woocommerce_country_locale_field_selectors', 'devvn_woocommerce_country_locale_field_selectors');
function devvn_woocommerce_country_locale_field_selectors($locale_fields){
	$locale_fields['country2'] = '#billing_country2_field, #shipping_country2_field';
	return $locale_fields;
}*/
