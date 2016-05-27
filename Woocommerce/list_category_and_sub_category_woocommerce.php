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
        echo '<h2><a href="' . get_term_link($pterm, $taxonomyName) . '">' . $pterm->name . '</a></h2>';     

        //Get the Child terms
        $terms = get_terms(array(
        	'taxonomy'		=> $taxonomyName,
        	'parent' 		=> $pterm->term_id, 
        	'orderby' 		=> 'slug', 
        	'hide_empty' 	=> false
        ));
        if($terms){
        echo '<div class="row list_child_category">';
	        foreach ($terms as $term) {
        	$thumbnail_id = get_woocommerce_term_meta($term->term_id, 'thumbnail_id', true);
            $thumb = wp_get_attachment_image_src( $thumbnail_id, 'shop_catalog' );
			$image = $thumb['0'];
			$termLink = get_term_link($term, $taxonomyName);
	        ?>
	        <div class="col-sm-3 col-xs-6">
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
        }
    echo '</div>';
    }
    echo '</div>';
?>
