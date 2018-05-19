<?php
// Get all blog ids in network except main site (id 1)
$blogs = $wpdb->get_results("
   SELECT blog_id
   FROM {$wpdb->blogs}
   WHERE site_id = '{$wpdb->siteid}'
   AND spam = '0'
   AND deleted = '0'
   AND archived = '0'
   AND blog_id != 1
");
// New empty arrays
$blog_ids;
$blogusers;
$blogusers_ids;
// Only save blog id numbers into the new array, also save all blogusers in network
foreach ( $blogs as $bloggers ) {
  $blog_ids[] = $bloggers->blog_id;
  $blogusers[] = get_users( 'blog_id='.$bloggers->blog_id.'');
}
// Save blog user ids in network
foreach ( $blogusers as $user ) {
  $blogusers_ids[] = $user->user_id;
}
// Save latest post from every blog, ordered by date. Add to a array.
$posts = array();
foreach ( $blog_ids as $blog_id ) {
    switch_to_blog( $blog_id );
    $query = new WP_Query(
        array(
      'post_type'      => 'post',
      'posts_per_page' => 1,
      'orderby'        => 'date',
      'order'          => 'DESC'
        )
    );
    while ( $query->have_posts() ) {
        $query->next_post();
        $posts[] = $query->post;
    }
    restore_current_blog();
}
// Compare dates ASC
function sort_objects_by_date($a, $b) {
  if($a->post_date == $b->post_date){ return 0 ; }
  return ($a->post_date > $b->post_date) ? -1 : 1;
}
// Sort array of posts by date
usort($posts, 'sort_objects_by_date');
// Remove duplicate authors, only display 1 posts from 1 author
$got = array();
$i = 0;
foreach($posts as $i => $d):
    if(!in_array($d->post_author, $got)):
        $got[] = $d->post_author;
        $out[] = $d;
    else:
      unset($posts[$i]);
    endif;
    $i++;
endforeach;
# Our main loop now sorted and unique authors.
global $post;
foreach( $posts as $post ) :
  $post_title = get_the_title();
  $post_date = get_the_date();
  $author_meta = get_the_author_meta('color'); // Just an example of a custom field added to the users profile page
  # Get meta data depending on context i.e use switch_to_blog()
  foreach( $blog_ids as $blog ) :
    switch_to_blog($blog);
    setup_postdata($post);
      if(get_post_thumbnail_id($id)):
         $attachment_image     = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'featured_post_image' );
         $attachment_image_url = $attachment_image[0];
         $postlink             = get_permalink();
         $bloglink             = get_bloginfo('url');
         $avatar               = get_wp_user_avatar(get_the_author_meta('ID'), 42);
         $author_id            = get_the_author_meta( 'ID' );
         $author_show          = get_the_author_meta( 'use' );
         $author_guest         = get_the_author_meta( 'guest' );
         $author_color         = get_the_author_meta('color'); // Just an example of a custom field added to a users profilepage
      endif;
    endforeach;
    
    # Do something wihth the data now here! ...
endforeach;
wp_reset_postdata();
