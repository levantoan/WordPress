<?php
/*
Dùng ACF để thêm field nhập banner vào mỗi danh mục
Author: levantoan.com
*/
add_action( 'flatsome_after_header', 'flatsome_category_banner_slide' );
function flatsome_category_banner_slide(){
    if(is_product_category() || is_product_taxonomy()):
        $queried_object = get_queried_object();
        if(have_rows('banner', $queried_object)):
            ?>
            <div class="single_slider">
                <div class="carousel carousel-main slider slider-nav-circle slider-nav-large slider-nav-light slider-style-normal" data-flickity>
                    <?php while(have_rows('banner', $queried_object)):the_row();
                    $hinh_anh = get_sub_field('hinh_anh');
                    $link_href = get_sub_field('link_href');
                    ?>
                    <div class="carousel-cell">
                        <?php if($link_href):?><a href="<?php echo esc_url($link_href)?>" title=""><?php endif;?>
                            <div class="devvn_img_resp"><?php echo wp_get_attachment_image( $hinh_anh, 'full' ); ?></div>
                        <?php if($link_href):?></a><?php endif;?>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php
        endif;
    endif;
}
