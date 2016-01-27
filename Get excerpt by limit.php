<?php
/*
Get excerpt by limit
*/
function excerpt($limit = 20) {
      $excerpt = explode(' ', get_the_excerpt(), $limit);
      $permalink = get_the_permalink();
      if (count($excerpt)>=$limit) {
        array_pop($excerpt);
        $excerpt = implode(" ",$excerpt).' <a href="'.$permalink.'">... View More</a>';
      } else {
        $excerpt = implode(" ",$excerpt);
      } 
      $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
      return $excerpt;
}
