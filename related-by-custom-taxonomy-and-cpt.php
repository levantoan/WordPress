<?php
/*
Insert to single-{cpt}.php
Author: https://levantoan.com
*/
$categories = get_the_terms(get_the_ID(), 'video_category');
if ($categories && is_array($categories)){
    $category_ids = array();
    foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
    $args=array(
        'post__not_in' => array(get_the_ID()),
        'post_type' =>  'videos',
        'posts_per_page' => 8,
        'tax_query' => array(
            array(
                'taxonomy' => 'video_category',
                'field'    => 'term_id',
                'terms'    => $category_ids,
            )
        )
    );
    $my_query = new wp_query($args);
    if( $my_query->have_posts() ):
        echo '<div class="relatedcat_videos">';
            echo '<h3>Video liên quan</h3><div class="row">';
            while ($my_query->have_posts()):$my_query->the_post();
                ?>
                <div class="col-sm-3 col-xs-6">
                    <?php get_template_part('content', 'video');?>
                </div>
                <?php
            endwhile;
            echo '</div>';
        echo '</div>';
    endif; wp_reset_query();
}
?>
