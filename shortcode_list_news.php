<?php
/*
Shortcode hiển thị tin tức dạng list
Author: Lê Văn Toản - http://levantoan.com
Demo hình ảnh: https://goo.gl/photos/iyW84LdeB2jA6UX56
*** Các sử dụng:
[devvn_list_news number_post="" cat=""]
*** Trong đó
number_post: là số bài viết hiển thị. VD: number_post="5" (mặc định là 5 bài)
cat: là danh sách category. VD: cat="1,2,3,4" (mặc định sẽ lấy bài viết mới nhất)
*/
add_shortcode('devvn_list_news','devvn_list_news_func');
function devvn_list_news_func($atts){
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
        ?>
        <style>
        .devvn_list_news_wrap, .devvn_list_news_wrap * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
        }
        .devvn_list_news_wrap:after,.devvn_list_news_row:after,.devvn_list_news_box:after{
            content: "";
            display: table;
            clear: both;
        }
        .devvn_list_news_box .devvn_list_news_thumb {
            width: 110px;
            height: auto;
            float: left;
        }
        .devvn_list_news_box .devvn_list_news_thumb img {
            max-width: 100%;
            width: 100%;
            height: auto;
            display: block;
            margin: 0 0 10px 0;
        }
        .devvn_list_news_infor {
            margin: 0 0 10px 0;
        }
        .devvn_list_news_has_thumbnail .devvn_list_news_infor {
            margin: 0 0 10px 120px;
        }
        .devvn_list_news_wrap a {
            color: #333;
            text-decoration: none;
        }
        .devvn_list_news_wrap a:hover {
            color: #0060AF;
            text-decoration: none;
        }
        .devvn_list_news_wrap {
            font-size: 14px;
            line-height: 19px;
        }
        .devvn_list_news_more {
            color: #919191;
            font-size: 11px;
        }
        .devvn_list_news_box {
            margin: 0 0 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .devvn_list_news_wrap .devvn_list_news_box:last-child {
            margin: 0;
            border-bottom: 0;
        }
        .devvn_list_news_date:before {
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
        .devvn_list_news_comment:before {
            display: inline-block;
            font: normal normal normal 12px/1 FontAwesome;
            font-size: inherit;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            content: "\f086";
            margin: 0 5px 0 0;
            font-weight: 400;
        }
        .devvn_list_news_more > span {
            white-space: nowrap;
            margin: 0 5px 0 0;
        }
        .devvn_list_news_more > span:last-child {
            margin: 0;
        }
        </style>
        <div class="devvn_list_news_wrap">
            <div class="devvn_list_news_row">
                <?php while ($news->have_posts()):$news->the_post();?>
                <div class="devvn_list_news_box <?php if(has_post_thumbnail()):?>devvn_list_news_has_thumbnail<?php endif;?>">
                    <?php if(has_post_thumbnail()):?>
                        <div class="devvn_list_news_thumb"><a href="<?php the_permalink();?>" title="<?php the_title()?>"><?php the_post_thumbnail('thumbnail');?></a></div>
                    <?php endif;?>
                    <div class="devvn_list_news_infor">
                        <a href="<?php the_permalink();?>" title="<?php the_title()?>" class="devvn_list_news_title"><?php the_title();?></a>
                        <div class="devvn_list_news_more">
                            <span class="devvn_list_news_date"><?php echo get_the_date('d/m/Y');?></span>
                            <span class="devvn_list_news_comment"><?php comments_number( '0', '1', '%' ); ?></span>
                        </div>
                    </div>
                </div>
                <?php endwhile;?>
            </div>
        </div>
        <?php
    endif;wp_reset_query();
    return ob_get_clean();
}
