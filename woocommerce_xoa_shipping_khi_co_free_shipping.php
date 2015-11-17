<?php
/**
 * woocommerce_package_rates is a 2.1+ hook
 */
add_filter( 'woocommerce_package_rates', 'hide_shipping_when_free_is_available', 10, 2 );
  
/**
 * Hide shipping rates when free shipping is available
 *
 * @param array $rates Array of rates found for the package
 * @param array $package The package array/object being shipped
 * @return array of modified rates
 */
function hide_shipping_when_free_is_available( $rates, $package ) {
  
 // Only modify rates if free_shipping is present
 if ( isset( $rates['free_shipping'] ) ) {
  
 // To unset a single rate/method, do the following. This example unsets flat_rate shipping
 unset( $rates['flat_rate'] );
  
 // To unset all methods except for free_shipping, do the following
 $free_shipping = $rates['free_shipping'];
 $rates = array();
 $rates['free_shipping'] = $free_shipping;
 }
  
 return $rates;
}