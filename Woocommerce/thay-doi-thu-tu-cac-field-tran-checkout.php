<?php
/*Sắp xếp lại thứ tự các field*/
add_filter("woocommerce_checkout_fields", "order_fields");

function order_fields($fields) {

    //Billing
	$order = array(
        "billing_first_name",
    	"billing_address_1",
        "billing_last_name", 
	"billing_address_2",
	"billing_company",
        "billing_city",
        "billing_email",
	"billing_county",
	"billing_email-2",
	"billing_country",
	"billing_phone",
	"billing_postcode",
	//billing_address_3
    );
    foreach($order as $field)
    {
        $ordered_fields[$field] = $fields["billing"][$field];
    }

    $fields["billing"] = $ordered_fields;
	
    //Shipping
	$order_shipping = array(
        "shipping_first_name", 
    	"shipping_address_1",
        "shipping_last_name",
	"shipping_address_2",
	"shipping_company",
	"shipping_city",
        "shipping_phone",	
        "shipping_county",
        "shipping_postcode",
        "shipping_country",

    );
    foreach($order_shipping as $field_shipping)
    {
        $ordered_fields2[$field_shipping] = $fields["shipping"][$field_shipping];
    }

    $fields["shipping"] = $ordered_fields2;
    return $fields;

}
