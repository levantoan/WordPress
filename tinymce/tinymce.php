<?php
// Enable font size & font family selects in the editor
if ( ! function_exists( 'wpex_mce_buttons' ) ) {
	function wpex_mce_buttons( $buttons ) {
		array_unshift( $buttons, 'fontselect' ); // Add Font Select
		array_unshift( $buttons, 'fontsizeselect' ); // Add Font Size Select
		return $buttons;
	}
}
add_filter( 'mce_buttons_2', 'wpex_mce_buttons' );

// Customize mce editor font sizes
if ( ! function_exists( 'wpex_mce_text_sizes' ) ) {
	function wpex_mce_text_sizes( $initArray ){
		$initArray['fontsize_formats'] = "9px 10px 12px 13px 14px 15px 16px 18px 20px 21px 24px 28px 30px 32px 36px";
		return $initArray;
	}
}
add_filter( 'tiny_mce_before_init', 'wpex_mce_text_sizes' );

// Add custom Fonts to the Fonts list
if ( ! function_exists( 'wpex_mce_google_fonts_array' ) ) {
	function wpex_mce_google_fonts_array( $initArray ) {
	    $initArray['font_formats'] = 'Mistral=Mistral2;Lato=Lato;Avenir=Avenir95;Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats';
            return $initArray;
	}
}
add_filter( 'tiny_mce_before_init', 'wpex_mce_google_fonts_array' );

// Add Google Scripts for use with the editor
if ( ! function_exists( 'wpex_mce_google_fonts_styles' ) ) {
	function wpex_mce_google_fonts_styles() {
	   $font_url = 'http://fonts.googleapis.com/css?family=Lato:300,400,700';
           add_editor_style( str_replace( ',', '%2C', $font_url ) );
	}
}
add_action( 'init', 'wpex_mce_google_fonts_styles' );

// Add Formats Dropdown Menu To MCE
if ( ! function_exists( 'wpex_style_select' ) ) {
	function wpex_style_select( $buttons ) {
		array_push( $buttons, 'styleselect' );
		return $buttons;
	}
}
add_filter( 'mce_buttons', 'wpex_style_select' );

// Add new styles to the TinyMCE "formats" menu dropdown
if ( ! function_exists( 'wpex_styles_dropdown' ) ) {
	function wpex_styles_dropdown( $settings ) {

		// Create array of new styles
		$new_styles = array(
			array(
				'title'	=> __( 'Text custom', 'wpex' ),
				'items'	=> array(
					array(
						'title'		=> __('Uppercase','wpex'),
						'inline'	=> 'span',
						'styles'	=> array(
							'text-transform' => 'uppercase',
						),
						//'classes' => 'button'
					),
				),
			),
		);

		// Merge old & new styles
		$settings['style_formats_merge'] = true;

		// Add new styles
		$settings['style_formats'] = json_encode( $new_styles );

		// Return New Settings
		return $settings;

	}
}
add_filter( 'tiny_mce_before_init', 'wpex_styles_dropdown' );

//Add new button
// Hooks your functions into the correct filters
function my_add_mce_button() {
	// check user permissions
	if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
		return;
	}
	// check if WYSIWYG is enabled
	if ( 'true' == get_user_option( 'rich_editing' ) ) {
		add_filter( 'mce_external_plugins', 'my_add_tinymce_plugin' );
		add_filter( 'mce_buttons', 'my_register_mce_button' );
	}
}
add_action('admin_head', 'my_add_mce_button');

// Declare script for new button
function my_add_tinymce_plugin( $plugin_array ) {
	$plugin_array['my_mce_button'] = get_template_directory_uri() .'/inc/tinymce/tinymce.js';
	return $plugin_array;
}

// Register new button in the editor
function my_register_mce_button( $buttons ) {
	array_push( $buttons, 'my_mce_button' );
	return $buttons;
}

function my_shortcodes_mce_css() {
	wp_enqueue_style('symple_shortcodes-tc', get_template_directory_uri() .'/inc/tinymce/tinymce.css' );
}
add_action( 'admin_enqueue_scripts', 'my_shortcodes_mce_css' );

//Shorcode
function paypal_button_func($atts){	
	extract(shortcode_atts(array(
		'email'		=>	'timbond@truevinepublishing.org',
		'name'		=>	'',
		'amount'	=>	''		
	), $atts));
	
	ob_start();
	?>
	<form method="post" action="https://www.paypal.com/cgi-bin/webscr" target="paypal">
		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="business" value="<?=$email?>">
		<input type="hidden" name="item_name" value="<?=$name?>">
		<input type="hidden" name="item_number" value="">
		<input type="hidden" name="amount" value="<?=$amount?>">
		<input type="hidden" name="currency_code" value="USD">
		<input type="hidden" name="undefined_quantity" value="1">
		<input type="hidden" name="shipping" value="0.00">
		<input type="hidden" name="handling" value="0.00">
		<input type="hidden" name="bn" value="godaddy_hosting_WPS_US">
		<input type="image" name="add" src="<?=get_template_directory_uri() .'/inc/tinymce/images/buy_now.gif'?>">
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode('paypal_button', 'paypal_button_func');