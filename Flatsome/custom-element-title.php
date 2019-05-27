<?php
function devvn_title_shortcode( $atts, $content = null ){
  extract( shortcode_atts( array(
    '_id' => 'title-'.rand(),
    'class' => '',
    'visibility' => '',
    'text' => 'Lorem ipsum dolor sit amet...',
    'tag_name' => 'h3',
    'sub_text' => '',
    'style' => 'normal',
    'size' => '100',
    'link' => '',
    'link_text' => '',
    'target' => '',
    'margin_top' => '',
    'margin_bottom' => '',
    'letter_case' => '',
    'color' => '',
    'width' => '',
    'icon' => '',
  ), $atts ) );

  $classes = array('container', 'devvn-section-title-container', 'section-title-container');
  if ( $class ) $classes[] = $class;
  if ( $visibility ) $classes[] = $visibility;
  $classes = implode(' ', $classes);

  $link_output = '';
  if($link) $link_output = '<a href="'.$link.'" target="'.$target.'">'.$link_text.get_flatsome_icon('icon-angle-right').'</a>';

  $small_text = '';
  if($sub_text) $small_text = '<small class="sub-title">'.$atts['sub_text'].'</small>';

  if($icon) $icon = get_flatsome_icon($icon);

  // fix old
  if($style == 'bold_center') $style = 'bold-center';

  $css_args = array(
   array( 'attribute' => 'margin-top', 'value' => $margin_top),
   array( 'attribute' => 'margin-bottom', 'value' => $margin_bottom),
  );

  if($width) {
    $css_args[] = array( 'attribute' => 'max-width', 'value' => $width);
  }

  $css_args_title = array();

  if($size !== '100'){
    $css_args_title[] = array( 'attribute' => 'font-size', 'value' => $size, 'unit' => '%');
  }
  if($color){
    $css_args_title[] = array( 'attribute' => 'color', 'value' => $color);
  }
  
  $titleText = '<span class="section-title-main" '.get_shortcode_inline_css($css_args_title).'>'.$icon.$text.$small_text.'</span>';
  
  if($link && $style == 'bold-center'){
      $titleText = '<a href="'.$link.'" target="'.$target.'">'.$titleText.'</a>';
      $link_output = '';
  }

  return '<div class="'.$classes.'" '.get_shortcode_inline_css($css_args).'><'. $tag_name . ' class="section-title section-title-'.$style.'"><b></b>' .$titleText. '<b></b>'.$link_output.'</' . $tag_name .'></div><!-- .section-title -->';
}
add_shortcode('title_devvn', 'devvn_title_shortcode');

function devvn_flatsome_ux_builder_thumbnail( $name ) {
  return get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . $name . '.svg';
}

function devvn_flatsome_ux_builder_template_thumb( $name ) {
  return get_template_directory_uri() . '/inc/builder/templates/thumbs/' . $name . '.jpg';
}
function devvn_flatsome_ux_builder_template( $path ) {
  ob_start();
  include get_template_directory() . '/inc/builder/shortcodes/templates/' . $path;
  return ob_get_clean();
}

function devvn_ux_builder_element(){
    add_ux_builder_shortcode( 'title_devvn', array(
    	'name'      => __( 'Title Devvn', 'ux-builder' ),
    	'category'  => __( 'Content' ),
    	'thumbnail' => devvn_flatsome_ux_builder_thumbnail( 'title' ),
    	'template'  => devvn_flatsome_ux_builder_template( 'title.html' ),
    	'info'      => '{{ text }}',
    	'wrap'      => false,
    
    	'options' => array(
    		'style' => array(
    			'type'    => 'select',
    			'heading' => 'Style',
    			'default' => 'normal',
    			'options' => array(
    				'normal'      => 'Normal',
    				'center'      => 'Center',
    				'bold'        => 'Left Bold',
    				'bold-center' => 'Center Bold',
    			),
    		),
    		'text' => array(
    			'type'       => 'textfield',
    			'heading'    => 'Title',
    			'default'    => 'Lorem ipsum dolor sit amet...',
    			'auto_focus' => true,
    		),
    		'tag_name' => array(
    			'type'    => 'select',
    			'heading' => 'Tag',
    			'default' => 'h3',
    			'options' => array(
    				'h1' => 'H1',
    				'h2' => 'H2',
    				'h3' => 'H3',
    				'h4' => 'H4',
    			),
    		),
    		'color' => array(
    			'type'     => 'colorpicker',
    			'heading'  => __( 'Color' ),
    			'alpha'    => true,
    			'format'   => 'rgb',
    			'position' => 'bottom right',
    		),
    		'icon' => array(
    			'type'    => 'select',
    			'heading' => 'Icon',
    			'options' => require( get_template_directory() . '/inc/builder/shortcodes/values/icons.php' ),
    		),
    		'width' => array(
    			'type'    => 'scrubfield',
    			'heading' => __( 'Width' ),
    			'default' => '',
    			'min'     => 0,
    			'max'     => 1200,
    			'step'    => 5,
    		),
    		'margin_top' => array(
    			'type'        => 'scrubfield',
    			'heading'     => __( 'Margin Top' ),
    			'default'     => '',
    			'placeholder' => __( '0px' ),
    			'min'         => - 100,
    			'max'         => 300,
    			'step'        => 1,
    		),
    		'margin_bottom' => array(
    			'type'        => 'scrubfield',
    			'heading'     => __( 'Margin Bottom' ),
    			'default'     => '',
    			'placeholder' => __( '0px' ),
    			'min'         => - 100,
    			'max'         => 300,
    			'step'        => 1,
    		),
    		'size' => array(
    			'type'    => 'slider',
    			'heading' => __( 'Size' ),
    			'default' => 100,
    			'unit'    => '%',
    			'min'     => 20,
    			'max'     => 300,
    			'step'    => 1,
    		),
    		'link_text' => array(
    			'type'    => 'textfield',
    			'heading' => 'Link Text',
    			'default' => '',
    		),
    		'link' => array(
    			'type'    => 'textfield',
    			'heading' => 'Link',
    			'default' => '',
    		),
    		'advanced_options' => require( get_template_directory() . '/inc/builder/shortcodes/commons/advanced.php'),
    	), 
    ) );
}
add_action('ux_builder_setup', 'devvn_ux_builder_element');
