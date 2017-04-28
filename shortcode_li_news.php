<?php
/*
Cách sử dụng
[devvn_li_news number_post="" cat_id=""]
number_post: Số bài hiển thị (Mặc định là 5)
cat_id: Danh sách category ID. Ví dụ: cat_id="1,2,3,4" (mặc định hiển thị bài viết mới nhất)
*/
add_shortcode('devvn_li_news','devvn_li_news_func');
function devvn_li_news_func($atts){
    extract(shortcode_atts(array(
        'number_post'   =>  '',
        'cat_id'        =>  ''
    ),$atts,'devvn_li_news'));
    $number_post = ($number_post)?intval($number_post):5;
    $args = array(
        'post_type'         => 'post',
        'posts_per_page'    => $number_post,
    );
    if($cat_id) {
        $cat_id = explode(',', $cat_id);
        $cat_id = array_map('intval', $cat_id);
    }
    if($cat_id && is_array($cat_id)){
        $args['category__in'] = $cat_id;
    }
    $news = new WP_Query($args);
    ob_start();
    if($news->have_posts()):
        ?>
        <style>
            .devvn_li_news_wrap {
                border: 1px solid #ddd;
                overflow: hidden;
            }
            .devvn_li_news_wrap ul.devvn_li_news_content {
                margin: 0 0 0 25px !important;
                padding: 0 !important;
            }
            .devvn_li_news_wrap ul.devvn_li_news_content li {
                margin: 0;
                padding: 0;
                color: #c00;
                list-style: disc;
            }
            .devvn_li_news_wrap ul.devvn_li_news_content li a {
                display: block;
            }
        </style>
        <div class="devvn_li_news_wrap">
            <ul class="devvn_li_news_content">
                <?php while ($news->have_posts()):$news->the_post();?>
                    <li><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a></li>
                <?php endwhile;?>
            </ul>
        </div>
        <?php
    endif;wp_reset_query();
    return ob_get_clean();
}
