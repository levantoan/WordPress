<?php
/*
Shortcode hiển thị tin tức dạng box. 1 tin to và những tin nhỏ bên cạnh
Author: Lê Văn Toản - http://levantoan.com
Demo hình ảnh: https://goo.gl/photos/oc6C3BfaNLrqSTJ56
*** Các sử dụng:
[devvn_box_news number_post="" cat=""]
*** Trong đó
number_post: là số bài viết hiển thị. VD: number_post="5" (mặc định là 5 bài)
cat: là danh sách category. VD: cat="1,2,3,4" (mặc định sẽ lấy bài viết mới nhất)
*/
add_shortcode('devvn_box_news','devvn_box_news_func');
function devvn_box_news_func($atts){
    extract(shortcode_atts(array(
        'number_post'   =>  5,
        'cat'        =>  '',
    ),$atts,'devvn_box_news'));
    $number_post = ($number_post)?intval($number_post):5;
    if($cat) {
        $cat_id = explode(',', $cat);
        $cat_id = array_map('intval', $cat_id);
    }
    $args = array(
        'post_type'         => 'post',
        'posts_per_page'    => $number_post
    );
    if($cat_id && is_array($cat_id)){
        $args['category__in'] = $cat_id;
    }
    $news = new WP_Query($args);
    ob_start();
    if($news->have_posts()):
    $max_post_count = $news->post_count;
    ?>
    <style>
        .devvn_box_news_wrap, .devvn_box_news_wrap * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
        }
        .devvn_box_news_wrap:after,.devvn_box_news_row:after,.devvn_box_news_col2 .devvn_box_news_box:after {
            content: "";
            display: table;
            clear: both;
        }
        .devvn_box_news_wrap .devvn_box_news_col1 {
            width: 60.71%;
            float: left;
        }
        .devvn_box_news_wrap .devvn_box_news_col2 {
            width: 37.09%;
            float: right;
        }
        .devvn_box_news_thumb {
            width: 76px;
            height: auto;
            float: left;
            margin: 0 0 10px 0;
        }
        .devvn_box_news_col1 .devvn_box_news_thumb {
            width: 100%;
            overflow: hidden;
        }
        .devvn_box_news_col1 .devvn_box_news_thumb a {
            width: 100%;
            height: 0;
            padding: 50.90% 0 0 0;
            display: block;
            float: left;
            background-size: cover !important;
            -moz-background-size: cover !important;
            -webkit-background-size: cover !important;
            position: relative;
        }
        .devvn_box_news_thumb img{
            display: block;
        }
        .devvn_box_news_col1 .devvn_box_news_thumb img {
            opacity: 0;
            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";
            filter: alpha(opacity=100);
            -moz-opacity: 0;
            -khtml-opacity: 0;
            position: absolute;
            top: 0;
            left: 0;
        }
        .devvn_box_news_infor{
            margin: 0 0 10px 0;
            font-size: 14px;
            line-height: 19px;
        }
        .devvn_box_news_box.devvn_box_news_has_thumbnail .devvn_box_news_infor {
            margin: 0 0 10px 86px;
        }
        .devvn_box_news_col2 .devvn_box_news_box {
            border-bottom: 1px solid #ddd;
            margin: 0 0 10px 0;
        }
        .devvn_box_news_infor a {
            color: #333;
            text-decoration: none;
        }
        .devvn_box_news_infor a:hover {
            color: #0060AF;
            text-decoration: none;
        }
        .devvn_box_news_date {
            display: block;
            color: #919191;
            font-size: 12px;
        }
        .devvn_box_news_date:before {
            display: inline-block;
            font: normal normal normal 12px/1 FontAwesome;
            font-size: inherit;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            content: "\f017";
            margin: 0 5px 0 0;
            font-weight: 400;
        }
        .devvn_box_news_col2 .devvn_box_news_box:last-child {
            margin-bottom: 0;
            border-bottom: 0;
        }
        .devvn_box_news_col1 .devvn_box_news_box.devvn_box_news_has_thumbnail .devvn_box_news_infor {
            margin: 0 0 10px 0;
        }
        .devvn_box_news_col1 .devvn_box_news_infor a.devvn_box_news_title {
            font-size: 18px;
            display: inline-block;
            line-height: 25px;
        }
        @media (max-width: 767px){
            .devvn_box_news_wrap .devvn_box_news_col1,
            .devvn_box_news_wrap .devvn_box_news_col2{
                width: 100%;
            }
        }
    </style>
    <div class="devvn_box_news_wrap">
        <div class="devvn_box_news_row">
            <?php $stt = 1; while ($news->have_posts()):$news->the_post();?>
            <?php if($stt == 1):?><div class="devvn_box_news_col1"><?php endif;?>
            <?php if($stt == 2):?><div class="devvn_box_news_col2"><?php endif;?>
                    <?php
                    $thumb = 'thumbnail';
                    if($stt == 1) $thumb = 'full';
                    $thumb_img = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), $thumb );
                    $urlThumb = $thumb_img['0'];
                    ?>
                    <div class="devvn_box_news_box <?php if(has_post_thumbnail()):?>devvn_box_news_has_thumbnail<?php endif;?>">
                        <?php if(has_post_thumbnail()):?>
                            <div class="devvn_box_news_thumb"><a href="<?php the_permalink();?>" title="<?php the_title()?>" <?php if($stt == 1):?>style="background: url(<?php echo $urlThumb;?>) no-repeat center center"<?php endif;?>><?php the_post_thumbnail($thumb);?></a></div>
                        <?php endif;?>
                        <div class="devvn_box_news_infor">
                            <a href="<?php the_permalink();?>" title="<?php the_title()?>" class="devvn_box_news_title"><?php the_title();?></a>
                            <?php if($stt != 1):?><div class="devvn_box_news_date"><?php printf( _x( '%s trước', '%s = human-readable time difference', 'devvn' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></div><?php endif;?>
                            <?php if($stt == 1):?><div class="devvn_box_news_excerpt"><?php the_excerpt();?></div><?php endif;?>
                        </div>
                    </div>
                <?php if($stt == 1 || $stt == $max_post_count):?></div><?php endif;?>
            <?php $stt++; endwhile;?>
        </div>
    </div>
    <?php
    endif;wp_reset_query();
    return ob_get_clean();
}
