<?php
/*
Author: levantoan.com
Insert this code to functions.php, then update permalink Setting -> Permalink
*/

add_filter('register_taxonomy_args','devvn_remove_parent_slug_category', 10, 2);
function devvn_remove_parent_slug_category($args, $name){
    if($name == 'category'){
        $args['rewrite']['hierarchical'] = false;
    }
    return $args;
}
