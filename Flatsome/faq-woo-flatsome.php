<?php
add_action('woocommerce_after_single_product_summary', 'add_faq_to_product');
function add_faq_to_product(){
    if(have_rows('cau_hoi_thuong_gap')){
        $mainEntity = array();
        ?>
        <div class="devvn_faq">
            <div class="devvn_faq_title">Câu hỏi thường gặp</div>
            <div class="devvn_faq_content">
                <?php $html = '[accordion]';?>
                <?php while(have_rows('cau_hoi_thuong_gap')):the_row();
                    $cau_hoi = get_sub_field('cau_hoi');
                    $cau_tra_loi = get_sub_field('cau_tra_loi');
                    ?>
                    <?php $html .= '[accordion-item title="'.esc_attr($cau_hoi).'"]';?>
                    <?php $html .= $cau_tra_loi;?>
                    <?php $html .= '[/accordion-item]';?>
                    <?php
                    $mainEntity[] = array(
                        "@type" => "Question",
                        "name" => esc_attr($cau_hoi),
                        "acceptedAnswer" => array(
                            "@type" => "Answer",
                            "text" => wp_strip_all_tags($cau_tra_loi),
                        ),
                    )
                    ?>
                <?php endwhile;?>
                <?php $html .= '[/accordion]';?>
                <?php echo do_shortcode($html);?>
            </div>
        </div>
        <?php if($mainEntity):?>
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "FAQPage",
                "mainEntity": <?php echo json_encode($mainEntity);?>
            }
        </script>
        <?php endif;?>
        <?php
    }
}
