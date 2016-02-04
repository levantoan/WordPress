<?php
function mySearchPostsFilter($query){
	if ( !is_admin() && $query->is_main_query() ) {
		if ($query->is_search){
			$query->set('post_type','post');
		}
	}
return $query;
}
add_filter('pre_get_posts','mySearchPostsFilter');
