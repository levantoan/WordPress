<?php
function title_query_search($tag_text = null, $postType = 'product'){
	global $wpdb;
	$listPostID = $listPostbyTitle = $listPostbyContent = array();
	
	//List post serach by title
	$sql  = "SELECT ID
			 FROM ".$wpdb->prefix."posts
			 WHERE post_type = '".$postType."'
			 AND post_status = 'publish'
			 AND post_title LIKE '%".$tag_text."%'";
	$arrayTag =  $wpdb->get_results($sql);
	if($arrayTag && !is_wp_error($arrayTag)){
		foreach ($arrayTag as $v){
			$listPostbyTitle[] = $v->ID;
		}
	}
	
	//List post serach by content
	$sql  = "SELECT ID
			 FROM ".$wpdb->prefix."posts
			 WHERE post_type = '".$postType."'
			 AND post_status = 'publish'
			 AND post_content LIKE '%".$tag_text."%'";
	$arrayTag =  $wpdb->get_results($sql);
	if($arrayTag && !is_wp_error($arrayTag)){
		foreach ($arrayTag as $v){
			$listPostbyContent[] = $v->ID;
		}
	}
	
	$allPost = array_unique(array_merge($listPostID, $listPostbyTitle, $listPostbyContent));
	
	return $allPost;
}
function search_order_by_title($query){
	if(!isset($query->query_vars['post_type'])) return;
	if($query->query_vars['post_type'] != 'product') return;
	
	if ( !is_admin() && $query->is_main_query() && is_search() ) {
		$query->set('post__in', title_query_search($query->query_vars['s']));
		$query->set('orderby', 'post__in');
	}
	return $query;
}
add_filter('pre_get_posts','search_order_by_title');
