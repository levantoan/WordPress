/**
 * Copy this to functions.php
 * change "videos" to slug custom post type
 *//
add_action('init', function () {
     add_rewrite_rule('videos/?$','index.php?pagename=videos', 'top');
     flush_rewrite_rules();
}, 1000);
