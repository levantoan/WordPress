remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);
add_filter('woocommerce_show_page_title', '__return_false');
remove_action('woocommerce_before_shop_loop','woocommerce_result_count',20,0);
remove_action('woocommerce_before_shop_loop','woocommerce_catalog_ordering',30,0);

//Số thumbnail hiển thị trong trang single product
add_filter('woocommerce_product_thumbnails_columns', 'xx_thumb_cols');
function xx_thumb_cols() {
     return 5;
 }
 
 
