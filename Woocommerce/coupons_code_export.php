<?php
function coupons_code_export_custom_menu_page() {
    add_submenu_page(
        'woocommerce',
        'Coupons Code Export',
    	'Coupons Code Export',
        'manage_options',
        'coupons-code-export',
        'coupons_code_export_func'
    );
}
add_action( 'admin_menu', 'coupons_code_export_custom_menu_page' );

function coupons_code_export_func(){
	?>
	<div class="wrap">
		<h1>Coupons Code Export - V1.0</h1>
		<form action="">
			<?php wp_nonce_field('coupons_code_nonce','coupons_nonce');?>
			<input name="export" type="submit" class="button button-primary button-large export_coupons" id="publish" value="Export Coupons Code">
		</form>
	</div>
	<script>
	(function($){
		$(".export_coupons").click(function(){
			var nonce = $('#coupons_nonce').val();
			window.location.href = '<?php echo admin_url('admin-ajax.php?action=export_coupons_code&nonce=')?>'+nonce;
			return false;
		});
	})(jQuery);
	</script>
	<?php
}

function cleanData(&$str,$key){
	$str = preg_replace("/\t/", "\\t", $str);
	$str = preg_replace("/\r?\n/", "\\n", $str);
	
	/*if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
      $str = "'$str";
    }*/
    
    if($key == 'Phone number') $str = "'$str";
	
	if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

function export_coupons_code_func(){
	if ( !wp_verify_nonce( $_REQUEST['nonce'], "coupons_code_nonce")) {
    	exit("No naughty business please");
	}
	$coupons = new WP_Query(array(
		'post_type'			=>	'shop_coupon',
		'posts_per_page'	=>	-1		
	));
	if($coupons->have_posts()):
		while ($coupons->have_posts()):$coupons->the_post();
			$data[] = array(
				"Code" 			=> get_the_title(), 
				"Coupon amount" => esc_html( get_post_meta( get_the_ID(), 'coupon_amount', true ) ), 
				"Coupon type"	=> esc_html( wc_get_coupon_type( get_post_meta( get_the_ID(), 'discount_type', true ) ) ),
				"Name" 			=> esc_html(get_post_meta( get_the_ID(), 'name_customer', true )),
				"Email"			=> esc_html(get_post_meta( get_the_ID(), 'email_customer', true )),
				"Phone number"	=> esc_html(get_post_meta( get_the_ID(), 'phone_number_customer', true )),
			);
		endwhile;
	else:
		$data = array();
	endif;wp_reset_query();

	// file name for download
	$filename = "coupons_code_" . date('Ymd') . ".xls";

	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel");

	$flag = false;
	foreach($data as $row) {
	if(!$flag) {
	  echo implode("\t", array_keys($row)) . "\n";
	  $flag = true;
	}
	array_walk($row, 'cleanData');
	echo implode("\t", array_values($row)) . "\n";
	}
	exit;
}
add_action( 'wp_ajax_export_coupons_code', 'export_coupons_code_func' );