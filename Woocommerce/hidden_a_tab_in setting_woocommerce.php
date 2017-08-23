<?php
add_filter( 'woocommerce_settings_tabs_array', 'remove_woocommerce_setting_tabs', 200, 1 );
function remove_woocommerce_setting_tabs( $tabs ) {
    $tabs_to_hide = array(
        'Shipping',
        'Tax',
        'Checkout',
        'Emails',
        'API',
        'Accounts',
    );
    $tabs = array_diff($tabs, $tabs_to_hide);
    return $tabs;
}
