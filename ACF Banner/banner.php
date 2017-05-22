<?php
global $post;
if ( is_front_page() ) {
    $postID = get_option('page_on_front');
} elseif ( is_home() || is_singular('post') ) {
    $postID = get_option('page_for_posts');
} else {
    $postID = ($post)?$post->ID:'';
}
$banner_type = get_field('banner_type',$postID);
$upload_image_banner = get_field('upload_image_banner',$postID);
$shortcode_slider = get_field('shortcode_slider',$postID);
$active_title = get_field('active_title',$postID);
$title_overview = get_field('title_overview',$postID);
$title_small = get_field('title_small',$postID);
if($banner_type != 'none' && $banner_type):
    ?>
    <div class="banner_section">
        <div class="banner_container">
            <?php if($banner_type == 'image'):?>
                <div class="banner_image" style="background: url(<?php echo wp_get_attachment_url($upload_image_banner,'full')?>) no-repeat center center;">
                    <?php echo wp_get_attachment_image($upload_image_banner,'full');?>
                    <?php if($active_title):?>
                    <div class="title_banner">
                        <?php if($title_small):?><div class="sub_title_banner"><?php echo $title_small;?></div><?php endif;?>
                        <?php if(is_home()):?>
                            <h1><?php echo ($title_overview)?$title_overview:get_the_title($postID);?></h1>
                        <?php else:?>
                            <div class="title_h1_banner"><?php echo ($title_overview)?$title_overview:get_the_title($postID);?></div>
                        <?php endif;?>
                    </div>
                    <?php endif;?>
                </div>
            <?php elseif(have_rows('banner_slider',$postID) && $banner_type == 'slider'):?>
                <div class="banner_slider_wrap">
                    <div class="owl-carousel owl-theme banner_slider">
                        <?php while (have_rows('banner_slider',$postID)):the_row();
                            $slider_type = get_sub_field('slider_type');
                            $images = get_sub_field('images');
                            $youtube_video_id = get_sub_field('youtube_video_id');
                            $link_to_image = get_sub_field('link_to_image');
                            ?>
                            <div class="item">
                                <?php if($slider_type == 'image' && $images):?>
                                    <?php if($link_to_image):?><a href="<?php echo esc_url($link_to_image);?>" title="" target="_blank"><?php endif;?>
                                    <?php echo wp_get_attachment_image($images,'full')?>
                                    <?php if($link_to_image):?></a><?php endif;?>
                                <?php elseif ($slider_type == 'video' && $youtube_video_id):?>
                                    <div class="video_slider"><iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $youtube_video_id;?>?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe></div>
                                <?php endif;?>
                            </div>
                        <?php endwhile;?>
                    </div>
                </div>
            <?php elseif ($shortcode_slider && $banner_type == 'slider_sc'):?>
                <?php echo do_shortcode($shortcode_slider);?>
            <?php endif;?>
        </div>
    </div>
<?php else:?>
    <div class="none_banner"></div>
<?php endif;?>