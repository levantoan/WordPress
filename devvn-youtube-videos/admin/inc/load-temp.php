<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function devvn_ytvideos_load_temp($filename){
	if ( $overridden_template = locate_template( 'ytvideos/'.$filename ) ) {
		locate_template( $overridden_template );
	} else {
		load_template( YTVIDEOS_PATH . '/views/'.$filename );
	}
}

function ytvideos_get_template_part( $slug, $name = '' ) {
	$template = '';

	// Look in yourtheme/slug-name.php and yourtheme/ytvideos/slug-name.php
	if ( $name ) {
		$template = locate_template( array( "{$slug}-{$name}.php", "ytvideos/{$slug}-{$name}.php" ) );
	}

	// Get default slug-name.php
	if ( ! $template && $name && file_exists( YTVIDEOS_PATH ."/views/{$slug}-{$name}.php" ) ) {
		$template = YTVIDEOS_PATH . "/views/{$slug}-{$name}.php";
	}

	// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/ytvideos/slug.php
	if ( ! $template ) {
		$template = locate_template( array( "{$slug}.php", "ytvideos/{$slug}.php" ) );
	}

	// Allow 3rd party plugins to filter template file from their plugin.
	$template = apply_filters( 'ytvideos_get_template_part', $template, $slug, $name );

	if ( $template ) {
		load_template( $template, false );
	}
}

add_filter( 'template_include', 'devvn_ytvideos_template_loader',100 );
function devvn_ytvideos_template_loader($template ){	
	if(is_post_type_archive( 'videos' )){
		devvn_ytvideos_load_temp('archive-videos.php');
		exit();
	}
	if(is_singular( 'videos' ) && is_single() && get_post_type() == 'videos'){
		devvn_ytvideos_load_temp('single-videos.php');
		exit();
	}
	return $template;
}