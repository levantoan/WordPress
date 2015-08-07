<?php
//.html link for custom post type
function rewrite_rules($rules) {
	$new_rules = array();
	foreach (get_post_types() as $t)
	$new_rules[$t . '/(.+?)\.html$'] = 'index.php?post_type=' . $t . '&name=$matches[1]';
	return $new_rules + $rules;
}
add_action('rewrite_rules_array', 'rewrite_rules');
 
function custom_post_permalink ($post_link) {
	global $post;
	if ( $post ) {
		$type = get_post_type($post->ID);
		return home_url() . '/' . $type . '/' . $post->post_name . '.html';
	}
}
add_filter('post_type_link', 'custom_post_permalink');
//End .html link for custom post type