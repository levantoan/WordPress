<?php
/*
Cách sử dụng
[devvn_news number_post="" cat_id="" title""]

number_post: Số bài hiển thị (Mặc định là 5)
cat_id: Danh sách category ID. Ví dụ: cat_id="1,2,3,4" (mặc định hiển thị bài viết mới nhất)
title: Tiêu đề chính (Mặc định là Tin tức)

*/
add_shortcode('devvn_news','toan_news_stick');
function toan_news_stick($atts){
    extract(shortcode_atts(array(
        'number_post'   =>  '',
        'cat_id'        =>  '',
        'title'         =>  ''
    ),$atts,'devvn_news'));
    $number_post = ($number_post)?intval($number_post):5;
    if($cat_id) {
        $cat_id = explode(',', $cat_id);
        $cat_id = array_map('intval', $cat_id);
    }
    $news = new WP_Query(array(
        'post_type'         => 'post',
        'posts_per_page'    => $number_post,
        'category__in'      => $cat_id
    ));
    ob_start();
    if($news->have_posts()):
    ?>
    <style>
        .devvn_news_wrap {
            display: table;
            width: 100%;
            overflow: hidden;
        }
        .devvn_news_title,.devvn_news_content {
            display: table-cell;
            vertical-align: middle;
        }
        .devvn_news_title {
            width: 80px;
            white-space: nowrap;
            text-align: left;
            text-transform: uppercase;
            color: #f58220;
        }
        .devvn_marquee{
            margin: 0px !important;
            white-space: nowrap;
        }
        .devvn_news_content marquee {
            float: left;
        }
        .devvn_news_content marquee a {
            padding-right: 20px;
        }
    </style>
    <div class="devvn_news_wrap">
        <div class="devvn_news_title"><?php echo ($title)?esc_attr($title):'Tin tức';?></div>
        <div class="devvn_news_content">
            <marquee hspace="10px" style="margin-left:0px;margin-right:0px;" class="devvn_marquee" scrollamount="4">
                <?php while ($news->have_posts()):$news->the_post();?>
                    <a href="<?php the_permalink();?>"><?php the_title();?></a>
                <?php endwhile;?>
            </marquee>
        </div>
    </div>
    <?php
    endif;wp_reset_query();
    return ob_get_clean();
}
