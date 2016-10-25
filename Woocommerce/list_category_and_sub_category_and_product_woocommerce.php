<?php
	$taxonomyName = "product_cat";
	//This gets top layer terms only.  This is done by setting parent to 0.
    $parent_terms = get_terms(array(
    	'taxonomy' 		=> $taxonomyName,
    	'parent' 		=> 0,
    	'orderby' 		=> 'slug',
    	'hide_empty' 	=> false
    ));
    echo '<div class="list_all_category">';
    foreach ($parent_terms as $pterm) {
	echo '<div class="list_main_category" id="cat_main_'.$pterm->term_id.'">';
        //show parent categories
        echo '<div class="title_readmore"><h2><a href="' . get_term_link($pterm, $taxonomyName) . '">' . $pterm->name . '</a></h2><a rel="nofollow" href="' . get_term_link($pterm, $taxonomyName) . '">' . __('Xem thêm >>','onego') . '</a></div>';
        //Get the Child terms
        $terms = get_terms(array(
        	'taxonomy'		=> $taxonomyName,
        	'parent' 		=> $pterm->term_id,
        	'orderby' 		=> 'slug',
        	'hide_empty' 	=> false
        ));
        if($terms){
        echo '<div class="list_child_category">';
	        foreach ($terms as $term) {
        	$thumbnail_id = get_woocommerce_term_meta($term->term_id, 'thumbnail_id', true);
            $thumb = wp_get_attachment_image_src( $thumbnail_id, 'shop_catalog' );
			$image = $thumb['0'];
			$termLink = get_term_link($term, $taxonomyName);
	        ?>
	        <div class="category_child_wrap">
	        	<div class="category_child_box">
	        		<?php if($image):?>
	        		<div class="category_child_box_thumb">
	        			<a href="<?=$termLink?>" rel="nofollow"><img src="<?=$image?>" alt="<?=$term->name?>" /></a>
	        			<a href="<?=$termLink?>" class="overlay_icon" rel="nofollow"></a>
	        		</div>
	        		<?php endif;?>
	        		<h3><a href="<?=$termLink?>"><?=$term->name?></a></h3>
	        	</div>
	        </div>
	        <?php
	        }
	    echo '</div>';
        }else{
        	$product_term = new WP_Query(array(
        		'post_type'			=>	'product',
        		'posts_per_page'	=>	6,
	        	'tax_query' => array(
					array(
						'taxonomy' => $taxonomyName,
						'field'    => 'term_id',
						'terms'    => array($pterm->term_id),
					),
				),        		
        	));
        	if($product_term->have_posts()):
	        	woocommerce_product_loop_start();
	        		while ($product_term->have_posts()):$product_term->the_post();
	        			wc_get_template_part( 'content', 'product' );
	        		endwhile;
	        	woocommerce_product_loop_end();
        	endif;wp_reset_query();
        }
    echo '</div>';
    }
    echo '</div>';
?>
