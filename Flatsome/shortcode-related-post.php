/*
Author: levantoan.com
Insert to functions.php
Use Shortcode [devvn_posts_related]
*/
add_shortcode('devvn_posts_related','flatsome_related_posts');
function flatsome_related_posts(){
    ob_start();
    $categories = get_the_category(get_the_ID());
    if ($categories){
        $ids = array();
        echo '<div class="relatedcat">';
        $category_ids = array();
        foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
        $args=array(
            'category__in' => $category_ids,
            'post__not_in' => array(get_the_ID()),
            'posts_per_page' => 6
        );
        $my_query = new wp_query($args);
        if( $my_query->have_posts() ):
            echo '<h3>Có thể bạn quan tâm?</h3>';
            while ($my_query->have_posts()):$my_query->the_post();
                array_push($ids, get_the_ID());
            endwhile;
            $ids = implode(',', $ids);
            echo '<div class="devvn_cat_tv">';
            //echo do_shortcode('[blog_posts type="row" image_hover="zoom" excerpt="false" show_date="text" depth="' . flatsome_option('blog_posts_depth') . '" depth_hover="' . flatsome_option('blog_posts_depth_hover') . '" text_align="' . get_theme_mod( 'blog_posts_title_align', 'center' ) . '" columns="3" ids="' . $ids . '"]');
            echo do_shortcode('[blog_posts style="normal" type="row" show_date="false" excerpt="false" comments="false" columns="3" columns__md="2" posts="6" image_height="56.25%" text_align="left" ids="' . $ids . '"]');
            echo '</div>';
        endif; wp_reset_query();
        echo '</div>';
    }
    return ob_get_clean();
}
