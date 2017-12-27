<?php
/*
Change Page to custom text
*/
function re_rewrite_rules() {
  global $wp_rewrite;
  $wp_rewrite->pagination_base = 'trang';
  $wp_rewrite->flush_rules();
}
add_action('init', 're_rewrite_rules');
