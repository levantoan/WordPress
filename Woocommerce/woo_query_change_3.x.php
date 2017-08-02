/*
Query woocommerce changed 3.x
**********
old featured_product
**********
'meta_query' => array(
  array(
    'key' => '_featured',
    'value' => 'yes'
  )
),
**********
new featured_product
**********
'tax_query' =>  array(
  array(
      'taxonomy' => 'product_visibility',
      'field'    => 'name',
      'terms'    => 'featured',
      'operator' => 'IN',
  )
)
**********
old category visibility
**********
$args['meta_query'][] = array(
    'key' => '_visibility',
    'value' => array( 'catalog', 'visible' ),
    'compare' => 'IN'
);
**********
new category visibility
**********
'tax_query' =>  array(
    array(
        'taxonomy' => 'product_visibility',
        'field' => 'name',
        'terms' => 'exclude-from-catalog',
        'operator' => 'NOT IN',
    )
)
