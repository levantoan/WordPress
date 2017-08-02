/*
Query woocommerce changed 3.x
**********
old
**********
'meta_query' => array(
  array(
    'key' => '_featured',
    'value' => 'yes'
  )
),
**********
new
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
old
**********
$args['meta_query'][] = array(
    'key' => '_visibility',
    'value' => array( 'catalog', 'visible' ),
    'compare' => 'IN'
);
**********
new
**********
'tax_query' =>  array(
    array(
        'taxonomy' => 'product_visibility',
        'field' => 'name',
        'terms' => 'exclude-from-catalog',
        'operator' => 'NOT IN',
    )
)
