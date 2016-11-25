<?php
/*
* Remove slug taxonomy in URL 
* Slug for example: danh-muc, category, product-cat
* http://levantoan.com
*/
add_filter('request', 'devvn_change_term_request', 1, 1 ); 
function devvn_change_term_request($query){ 
	$tax_name = 'category'; // specify you taxonomy name here, it can be also 'category' or 'post_tag'
 
	// Request for child terms differs, we should make an additional check
	if( $query['attachment'] ) :
		$include_children = true;
		$name = $query['attachment'];
	else:
		$include_children = false;
		$name = $query['name'];
	endif;
 
 
	$term = get_term_by('slug', $name, $tax_name); // get the current term to make sure it exists
 
	if (isset($name) && $term && !is_wp_error($term)): // check it here
 
		if( $include_children ) {
			unset($query['attachment']);
			$parent = $term->parent;
			while( $parent ) {
				$parent_term = get_term( $parent, $tax_name);
				$name = $parent_term->slug . '/' . $name;
				$parent = $parent_term->parent;
			}
		} else {
			unset($query['name']);
		}
 
		switch( $tax_name ):
			case 'category':{
				$query['category_name'] = $name; // for categories
				break;
			}
			case 'post_tag':{
				$query['tag'] = $name; // for post tags
				break;
			}
			default:{
				$query[$tax_name] = $name; // for another taxonomies
				break;
			}
		endswitch;
 
	endif;
 
	return $query; 
} 
add_filter('request', 'devvn_change_term_product_cat_request', 1, 1 ); 
function devvn_change_term_product_cat_request($query){ 
	$tax_name = 'product_cat'; // specify you taxonomy name here, it can be also 'category' or 'post_tag'
 
	// Request for child terms differs, we should make an additional check
	if( $query['attachment'] ) :
		$include_children = true;
		$name = $query['attachment'];
	else:
		$include_children = false;
		$name = $query['name'];
	endif;
 
 
	$term = get_term_by('slug', $name, $tax_name); // get the current term to make sure it exists
 
	if (isset($name) && $term && !is_wp_error($term)): // check it here
 
		if( $include_children ) {
			unset($query['attachment']);
			$parent = $term->parent;
			while( $parent ) {
				$parent_term = get_term( $parent, $tax_name);
				$name = $parent_term->slug . '/' . $name;
				$parent = $parent_term->parent;
			}
		} else {
			unset($query['name']);
		}
 
		switch( $tax_name ):
			case 'category':{
				$query['category_name'] = $name; // for categories
				break;
			}
			case 'post_tag':{
				$query['tag'] = $name; // for post tags
				break;
			}
			default:{
				$query[$tax_name] = $name; // for another taxonomies
				break;
			}
		endswitch;
 
	endif;
 
	return $query; 
} 
add_filter('request', 'devvn_change_term_danhmuc_videos_request', 1, 1 ); 
function devvn_change_term_danhmuc_videos_request($query){ 
	$tax_name = 'danhmuc_videos'; // specify you taxonomy name here, it can be also 'category' or 'post_tag'
 
	// Request for child terms differs, we should make an additional check
	if( $query['attachment'] ) :
		$include_children = true;
		$name = $query['attachment'];
	else:
		$include_children = false;
		$name = $query['name'];
	endif;
 
 
	$term = get_term_by('slug', $name, $tax_name); // get the current term to make sure it exists
 
	if (isset($name) && $term && !is_wp_error($term)): // check it here
 
		if( $include_children ) {
			unset($query['attachment']);
			$parent = $term->parent;
			while( $parent ) {
				$parent_term = get_term( $parent, $tax_name);
				$name = $parent_term->slug . '/' . $name;
				$parent = $parent_term->parent;
			}
		} else {
			unset($query['name']);
		}
 
		switch( $tax_name ):
			case 'category':{
				$query['category_name'] = $name; // for categories
				break;
			}
			case 'post_tag':{
				$query['tag'] = $name; // for post tags
				break;
			}
			default:{
				$query[$tax_name] = $name; // for another taxonomies
				break;
			}
		endswitch;
 
	endif;
 
	return $query; 
} 
/*Remove product_cat in URL*/
add_filter( 'term_link', 'devvn_term_permalink', 10, 3 ); 
function devvn_term_permalink( $url, $term, $taxonomy ){
		
	switch ($taxonomy):
		case 'product_cat':
			$taxonomy_slug = 'danh-muc';
			if(strpos($url, $taxonomy_slug) === FALSE) break;
			$url = str_replace('/' . $taxonomy_slug, '', $url);
			break;
		case 'category':
			$taxonomy_slug = 'category';
			if(strpos($url, $taxonomy_slug) === FALSE) break;
			$url = str_replace('/' . $taxonomy_slug, '', $url);
			break;
		case 'danhmuc_videos':
			$taxonomy_slug = 'danhmuc-videos';
			if(strpos($url, $taxonomy_slug) === FALSE) break;
			$url = str_replace('/' . $taxonomy_slug, '', $url);
			break;
	endswitch;
	
	return $url;	
}
