<?php

defined( 'ABSPATH' ) || exit;

if(!class_exists('Schema_Product_Cat')) {
    class Schema_Product_Cat
    {
        public function __construct(){
            //add_action( 'product_cat_add_form_fields', array( $this, 'add_category_fields' ) );
            add_action( 'product_cat_edit_form_fields', array( $this, 'edit_category_fields' ), 20 );
            //add_action( 'created_term', array( $this, 'save_category_fields' ), 10, 3 );
            add_action( 'edit_term', array( $this, 'save_category_fields' ), 10, 3 );

            add_action('wp_head', array($this, 'schema_render'));
        }

        public function edit_category_fields($term){
            $schema_prod_cat = get_term_meta( $term->term_id, 'schema_prod_cat', true );
            $active = isset($schema_prod_cat['active']) ? $schema_prod_cat['active'] : 0;
            $name = isset($schema_prod_cat['name']) ? $schema_prod_cat['name'] : '';
            $desc = isset($schema_prod_cat['desc']) ? $schema_prod_cat['desc'] : '';
            $images = isset($schema_prod_cat['images']) ? $schema_prod_cat['images'] : '';
            $images_arg = '';
            if($images) $images_arg = explode(',', $images);
            $brand = isset($schema_prod_cat['brand']) ? $schema_prod_cat['brand'] : '';
            $rating = isset($schema_prod_cat['rating']) ? $schema_prod_cat['rating'] : '';
            $sameas = isset($schema_prod_cat['sameas']) ? $schema_prod_cat['sameas'] : '';
            $price = isset($schema_prod_cat['price']) ? $schema_prod_cat['price'] : '';
            ?>
            <tr class="form-field term-display-type-wrap">
                <th scope="row" valign="top"><label><?php esc_html_e( 'Schema custom', 'devvn' ); ?></label></th>
                <td>
                    <style>
                        table.devvn_table_prodcat {
                            border: 1px solid #ccc;
                            border-collapse: collapse;
                            margin-bottom: 10px;
                        }

                        table.devvn_table_prodcat td {
                            border: 1px solid #ccc;
                            padding: 3px 5px;
                            vertical-align: top;
                        }

                        table.devvn_table_prodcat input[type="text"],table.devvn_table_prodcat textarea {
                            width: 100%;
                        }
                        .devvn_gm_wrap ul.devvn_gm_images {
                            margin: 0 -2px;
                            overflow: hidden;
                        }
                        .devvn_gm_wrap ul.devvn_gm_images > li{
                            float: left;
                            width: 10%;
                            padding: 0 2px;
                            margin-bottom: 4px;
                            position: relative;
                        }
                        .devvn_gm_wrap ul.devvn_gm_images li img {
                            width: 100%;
                            height: auto;
                            display: block;
                        }
                        .devvn_gm_wrap ul.devvn_gm_images li ul.actions {
                            position: absolute;
                            top: 2px;
                            right: 2px;
                            visibility: hidden;
                            opacity: 0;
                            z-index: 5;
                        }
                        .devvn_gm_wrap ul.devvn_gm_images li ul.actions a {
                            font-size: 20px;
                            width: 20px;
                            height: 20px;
                            text-decoration: none;
                            color: #fff;
                            display: block;
                        }
                        .devvn_gm_wrap ul.devvn_gm_images li:hover .li_img_box:after {
                            position: absolute;
                            width: 100%;
                            height: 100%;
                            content: "";
                            background: rgba(0, 0, 0, .6);
                            z-index: 3;
                            left: 0;
                            top: 0;
                            visibility: hidden;
                            opacity: 0;
                        }
                        .li_img_box{
                            position: relative;
                        }
                        .devvn_gm_wrap ul.devvn_gm_images li:hover ul.actions,
                        .devvn_gm_wrap ul.devvn_gm_images li:hover .li_img_box:after{
                            visibility: visible;
                            opacity: 1;
                        }
                        .devvn_gm_addimages{
                            margin-bottom: 10px !important;
                            display: inline-block;
                        }
                        @media (max-width: 991px){
                            .devvn_gm_wrap ul.devvn_gm_images > li {
                                width: 20%;
                            }
                        }
                        @media (max-width: 767px){
                            .devvn_gm_wrap ul.devvn_gm_images > li {
                                width: 33.333%;
                            }
                        }
                    </style>
                    <table class="devvn_table_prodcat">
                        <tbody>
                            <tr>
                                <td>Kích hoạt</td>
                                <td>
                                    <label>
                                        <input type="checkbox" name="schema_prod_cat[active]" value="1" <?php checked(1, $active, true);?>> Kích hoạt schema
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>Tên</td>
                                <td><input type="text" placeholder="Mặc định sẽ lấy tên danh mục" name="schema_prod_cat[name]" value="<?php echo esc_attr($name);?>"></td>
                            </tr>
                            <tr>
                                <td>Mô tả</td>
                                <td><textarea placeholder="Mặc định sẽ lấy mô tả danh mục" name="schema_prod_cat[desc]"><?php echo esc_textarea($desc);?></textarea></td>
                            </tr>
                            <tr>
                                <td>Hình ảnh</td>
                                <td>
                                    <div class="devvn_gm_wrap">
                                        <a href="#" class="button devvn_gm_addimages" data-choose="<?php _e('Thêm ảnh','devvn');?>" data-update="<?php _e('Thêm ảnh','devvn');?>" data-edit="<?php _e('Sửa ảnh','devvn');?>" data-delete="<?php _e('Xoá','devvn');?>" data-text="<?php _e('Xoá','devvn');?>"><?php _e('Thêm ảnh vào gallery','devvn');?></a>
                                        <div class="devvn_gm_box">
                                            <ul class="devvn_gm_images">
                                                <?php if($images_arg):?>
                                                    <?php foreach ($images_arg as $img):?>
                                                        <li class="image" data-attachment_id="<?php echo $img;?>">
                                                            <div class="li_img_box">
                                                                <?php echo wp_get_attachment_image($img, 'thumbnail')?>
                                                                <ul class="actions">
                                                                    <li><a href="#" class="gm_delete" title="Remove"><span class="dashicons dashicons-no-alt"></span></a></li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                    <?php endforeach;?>
                                                <?php endif;?>
                                            </ul>
                                            <input type="hidden" id="attachment_img" name="schema_prod_cat[images]" value="<?php echo $images;?>">
                                        </div>
                                    </div>
                                    <br><small>Nếu bỏ trống hệ thống sẽ tự lấy các ảnh của sản phẩm trong danh mục này để hiển thị danh sách ảnh</small>
                                </td>
                            </tr>
                            <tr>
                                <td>Thương hiệu</td>
                                <td><input type="text" placeholder="Mặc định: <?php echo get_option('blogname');?>" name="schema_prod_cat[brand]" value="<?php echo esc_attr($brand);?>"></td>
                            </tr>
                            <tr>
                                <td>Đánh giá sao</td>
                                <td><input type="text" placeholder="Nhập đánh giá sao" name="schema_prod_cat[rating]" value="<?php echo esc_attr($rating);?>">
                                <br><small>Định dạng [Số điểm]-[Số đánh giá]. Ví dụ 4.9-60. Ví dụ này là 4.9 sao và có 60 đánh giá</small></td>
                            </tr>
                            <tr>
                                <td>Link thông tin thêm</td>
                                <td>
                                    <input type="text" name="schema_prod_cat[sameas]" value="<?php echo esc_attr($sameas);?>" >
                                    <br><small>Các link này là các trang web nổi tiếng nói về sản phẩm tương tự. Mỗi link cách nhau dấu phẩy (,)</small>
                                </td>
                            </tr>
                            <tr>
                                <td>Giá sản phẩm</td>
                                <td>
                                    <input type="text" placeholder="Nhập giá trong khoảng" name="schema_prod_cat[price]" value="<?php echo esc_attr($price);?>">
                                    <br><small>Hãy nhập giá trong khoảng. Ví dụ: 110000-3000000</small>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <img style="max-width: 485px;width: 100%;" src="data:image/png;base64,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" alt="">
                    <script type="text/javascript">
                        jQuery('body').on( 'click', '.devvn_gm_addimages', function( event ) {
                            event.preventDefault();
                            var product_gallery_frame;
                            var $el = jQuery(this);
                            var thisParents = $el.closest('.devvn_gm_wrap');
                            var $image_gallery_ids = thisParents.find('#attachment_img');
                            var $product_images    = thisParents.find('ul.devvn_gm_images');

                            // If the media frame already exists, reopen it.
                            if ( product_gallery_frame ) {
                                product_gallery_frame.open();
                                return;
                            }

                            // Create the media frame.
                            product_gallery_frame = wp.media.frames.product_gallery = wp.media({
                                // Set the title of the modal.
                                title: $el.data( 'choose' ),
                                button: {
                                    text: $el.data( 'update' )
                                },
                                states: [
                                    new wp.media.controller.Library({
                                        title: $el.data( 'choose' ),
                                        filterable: 'all',
                                        multiple: true
                                    })
                                ]
                            });

                            // When an image is selected, run a callback.
                            product_gallery_frame.on( 'select', function() {
                                var selection = product_gallery_frame.state().get( 'selection' );
                                var attachment_ids = $image_gallery_ids.val();

                                selection.map( function( attachment ) {
                                    attachment = attachment.toJSON();

                                    if ( attachment.id ) {
                                        attachment_ids   = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
                                        var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                                        var attachment_title = attachment.filename ? attachment.filename : '';

                                        $product_images.append( '<li class="image" data-attachment_id="' + attachment.id + '"><div class="li_img_box"><img src="' + attachment_image + '" /><ul class="actions"><li><a href="#" class="gm_delete" title="' + $el.data('delete') + '"><span class="dashicons dashicons-no-alt"></span></a></li></ul></div></li>' );
                                    }
                                });

                                $image_gallery_ids.val( attachment_ids );
                            });

                            // Finally, open the modal.
                            product_gallery_frame.open();
                        });

                        jQuery( 'body' ).on( 'click', '.devvn_gm_images a.gm_delete', function() {
                            var parentsThis = jQuery(this).closest('.devvn_gm_wrap');
                            var $image_gallery_ids = parentsThis.find('#attachment_img');

                            jQuery( this ).closest( 'li.image' ).remove();

                            var attachment_ids = '';

                            parentsThis.find('ul li.image' ).each( function() {
                                var attachment_id = jQuery( this ).attr( 'data-attachment_id' );
                                attachment_ids = (attachment_ids) ? attachment_ids + ',' + attachment_id : attachment_id;
                            });

                            $image_gallery_ids.val( attachment_ids );

                            return false;
                        });
                    </script>
                </td>
            </tr>
            <?php
        }

        public function save_category_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
            if ( isset( $_POST['schema_prod_cat'] ) && 'product_cat' === $taxonomy ) {
                update_term_meta( $term_id, 'schema_prod_cat', array_map('wc_clean', $_POST['schema_prod_cat']));
            }
        }

        public function schema_render(){
            if(is_product_category()){
                $term = get_queried_object();
                $schema_prod_cat = get_term_meta( $term->term_id, 'schema_prod_cat', true );
                $active = isset($schema_prod_cat['active']) ? $schema_prod_cat['active'] : 0;
                $name = isset($schema_prod_cat['name']) && $schema_prod_cat['name'] ? $schema_prod_cat['name'] : $term->name;
                $desc = isset($schema_prod_cat['desc']) && $schema_prod_cat['desc'] ? $schema_prod_cat['desc'] : $term->description;
                $images = isset($schema_prod_cat['images']) ? $schema_prod_cat['images'] : '';
                $images_link = array();
                if($images) {
                    $images_arg = explode(',', $images);
                    if($images_arg){
                        foreach ($images_arg as $img){
                            $images_link[] = wp_get_attachment_image_url($img, 'full');
                        }
                    }
                }else{
                    $args = array(
                        'category' => array( $term->slug ),
                        'limit' => 10
                    );
                    $products = wc_get_products( $args );
                    foreach ($products as $prod){
                        $images_link[] = wp_get_attachment_image_url($prod->get_image_id(), 'full');
                    }
                }

                $brand = isset($schema_prod_cat['brand']) && $schema_prod_cat['brand'] ? $schema_prod_cat['brand'] : get_option('blogname');
                $sameas = isset($schema_prod_cat['sameas']) && $schema_prod_cat['sameas'] ? $schema_prod_cat['sameas'] : '';
                $rating = isset($schema_prod_cat['rating']) && $schema_prod_cat['rating'] ? $schema_prod_cat['rating'] : '';
                if($rating){
                    $rating = explode('-', $rating);
                    $ratingValue = array_shift($rating);
                    $ratingCount = end($rating);
                }else{
                    $ratingValue = 5;
                    $ratingCount = 20;
                }
                $price = isset($schema_prod_cat['price']) ? $schema_prod_cat['price'] : '';
                if($price){
                    $price = explode('-', $price);
                    $lowPrice = array_shift($price);
                    $highPrice = end($price);
                }
                $term_url = get_term_link($term);
                if($schema_prod_cat && $active) {
                    ob_start();
                    ?>
                    <script type="application/ld+json">
                        {
                            "@context": "https://schema.org/",
                            "@type": "Product",
                            "url": "<?php echo esc_url($term_url);?>",
                            "name": "<?php echo esc_attr($name);?>",
                            "description": "<?php echo esc_attr($desc);?>",
                            "mainEntityOfPage":{
                                "@type":"WebPage",
                                "@id":"<?php echo esc_url($term_url);?>"
                            },

                            "image":<?php echo json_encode($images_link);?>,
                            "brand": {
                                "@type": "Brand",
                                "name": "<?php echo esc_attr($brand);?>"
                            },
                            "aggregateRating": {
                                "@type": "AggregateRating",
                                "ratingValue": "<?php echo esc_attr($ratingValue);?>",
                                "ratingCount": "<?php echo esc_attr($ratingCount);?>"
                            },
                            <?php if($sameas):?>
                            "sameas":<?php echo json_encode(explode(',', $sameas));?>,
                            <?php endif;?>
                            <?php if($price){?>
                            "offers": {
                                "@type": "AggregateOffer",

                                "offerCount": "<?php echo $lowPrice;?>",

                                "lowPrice": "<?php echo $lowPrice;?>",
                                "highPrice": "<?php echo $highPrice;?>",
                                "priceCurrency": "VND"
                            }
                            <?php }?>
                        }
                    </script>
                    <?php
                    echo ob_get_clean();
                }
            }
        }
    }
    new Schema_Product_Cat();
}
