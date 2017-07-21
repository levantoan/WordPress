<?php
/*
 * Code hiển thị bài viết liên quan cho custom post type trong cùng 1 custom taxonomy
 * Author: levantoan.com
 */
$postType = 'devvn_quotes';
$taxonomyName = 'category_quotes';
$taxonomy = get_the_terms(get_the_ID(), $taxonomyName);
if ($taxonomy){
	echo '<div class="relatedcat">';
	$category_ids = array();
	foreach($taxonomy as $individual_category) $category_ids[] = $individual_category->term_id;
	$args = array(	
		'post_type' =>  $postType,
		'post__not_in' => array(get_the_ID()),
		'posts_per_page' => 3,
		'tax_query' => array(
			array(
				'taxonomy' => $taxonomyName,
				'field'    => 'term_id',
				'terms'    => $category_ids,
			),
		)
	);
	$my_query = new wp_query($args);
	if( $my_query->have_posts() ):
		echo '<p>Bài viết liên quan:</p><ul>';
		while ($my_query->have_posts()):$my_query->the_post();
			echo '<li><a href="'.get_the_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></li>';
		endwhile;
		echo '</ul>';
	endif; wp_reset_query();
	echo '</div>';
}