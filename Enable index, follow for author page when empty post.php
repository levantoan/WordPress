<?php
/*
Enable index, follow for author page when empty post
Author: levantoan.com
Add this code to functions.php
*/
add_filter('wpseo_robots_array', function ($robots){
    if(is_author()){
        $robots['index'] = 'index';
        $robots['follow'] = 'follow';
    }
    return $robots;
});
