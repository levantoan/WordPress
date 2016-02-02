<?php
/*
Get excerpt by limit
*/
function get_excerpt($limit = 130){
	$excerpt = get_the_excerpt();
	if(!$excerpt) $excerpt = get_the_content();
	$excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
	$excerpt = strip_shortcodes($excerpt);
	$excerpt = strip_tags($excerpt);
	$excerpt = substr($excerpt, 0, $limit);
	$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
	$excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
	$permalink = get_the_permalink();
	$excerpt = $excerpt.'... <a href="'.$permalink.'" title="">View more</a>';
	return $excerpt;
}
