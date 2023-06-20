<?php
add_filter( 'woocommerce_structured_data_product_offer', 'devvn_woocommerce_structured_data_product_offer' );
add_filter( 'wpseo_schema_product', 'devvn_wpseo_schema_product' );
add_filter( 'rank_math/snippet/rich_snippet_product_entity', 'devvn_rich_snippet_product_entity' );

function devvn_hasMerchantReturnPolicy(){
    return '{
	"@type": "MerchantReturnPolicy",
	"applicableCountry": "vi",
	"returnPolicyCategory": "https://schema.org/MerchantReturnFiniteReturnWindow",
	"merchantReturnDays": "7",
	"returnMethod": "https://schema.org/ReturnByMail",
	"returnFees": "https://schema.org/FreeReturn"
}';
}

function devvn_shippingDetails(){
    return '{
  "@type": "OfferShippingDetails",
  "shippingRate": {
    "@type": "MonetaryAmount",
    "value": "0",
    "currency": "VND"
  },
  "deliveryTime": {
    "@type": "ShippingDeliveryTime",
    "businessDays": {
        "@type": "OpeningHoursSpecification",
         "dayOfWeek": [
            "https://schema.org/Monday",
            "https://schema.org/Tuesday",
            "https://schema.org/Wednesday",
            "https://schema.org/Thursday",
            "https://schema.org/Friday"
        ]
    },
    "handlingTime": {
      "@type": "QuantitativeValue",
      "minValue": "0",
      "maxValue": "3",
      "samedaydelivery" : "Yes",
      "unitCode": "DAY"
      
    },
    "transitTime": {
      "@type": "QuantitativeValue",
      "minValue": "0",
      "maxValue": "3",
      "samedaydelivery" : "Yes",
      "unitCode": "DAY"
    }					
  },
  "shippingDestination": [
    {
      "@type": "DefinedRegion",
      "addressCountry": "VN",
      "addressRegion": ["VN"]
    }
  ]
}';
}

function wpseo_schema_product($data){
    if(isset($data['offers'])){
        $hasMerchantReturnPolicy = devvn_hasMerchantReturnPolicy();
        $shippingDetails = devvn_shippingDetails();
        foreach ($data['offers'] as $key => $offer){
            if(!isset($offers['hasMerchantReturnPolicy']) && $hasMerchantReturnPolicy){
                $data['offers'][$key]['hasMerchantReturnPolicy'] = json_decode($hasMerchantReturnPolicy, true);
            }
            if(!isset($offers['shippingDetails']) && $shippingDetails){
                $data['offers'][$key]['shippingDetails'] = json_decode($shippingDetails, true);
            }
        }
    }
    return $data;
}

function rich_snippet_product_entity($entity){
    global $product;
    if(!is_singular('product') || !$product || is_wp_error($product)) return $entity;
    $hasMerchantReturnPolicy = devvn_hasMerchantReturnPolicy();
    $shippingDetails = devvn_shippingDetails();
    if(!isset($entity['offers']['hasMerchantReturnPolicy']) && $hasMerchantReturnPolicy){
        $entity['offers']['hasMerchantReturnPolicy'] = json_decode($hasMerchantReturnPolicy, true);
    }
    if(!isset($entity['offers']['shippingDetails']) && $shippingDetails){
        $entity['offers']['shippingDetails'] = json_decode($shippingDetails, true);
    }
    return $entity;
}

function woocommerce_structured_data_product_offer($offers){

    $hasMerchantReturnPolicy = devvn_hasMerchantReturnPolicy();
    $shippingDetails = devvn_shippingDetails();

    if(!isset($offers['hasMerchantReturnPolicy']) && $hasMerchantReturnPolicy){
        $offers['hasMerchantReturnPolicy'] = json_decode($hasMerchantReturnPolicy, true);
    }

    if(!isset($offers['shippingDetails']) && $shippingDetails){
        $offers['shippingDetails'] = json_decode($shippingDetails, true);
    }

    return $offers;
}
