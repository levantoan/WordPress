<?php
/*********************************
 * Author: levantoan.com
 * Gallery metabox
*********************************/
/*Add metbox*/
if(!class_exists('DevVN_Gallery_Meta_Box')){
    class DevVN_Gallery_Meta_Box {
        public function __construct() {
            if ( is_admin() ) {
                add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
                add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
            }
        }
        public function init_metabox() {
            add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
            add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );
        }
        public function add_metabox() {
            add_meta_box(
                'devvn-gallery-metabox',
                __( 'Gallery images', 'devvn' ),
                array( $this, 'render_metabox' ),
                'post', //your post type
                'side', //side or advanced or normal
                'default'
            );

        }
        public function render_metabox( $post ) {
            wp_nonce_field( 'devvn_gallery_images_action', 'devvn_gallery_images' );
            ?>
            <style>
                .devvn_gm_wrap,.devvn_gm_wrap * {
                    box-sizing: border-box;
                    -moz-box-sizing: border-box;
                    -webkit-box-sizing: border-box;
                }
                .devvn_gm_wrap {
                    overflow: hidden;
                }
                .devvn_gm_box:after {
                    content: "";
                    display: table;
                    clear: both;
                }
                .devvn_gm_wrap ul.devvn_gm_images {
                    margin: 0 -2px;
                }
                .devvn_gm_wrap ul.devvn_gm_images li{
                    float: left;
                    width: 25%;
                    padding: 0 2px;
                    margin-bottom: 4px;
                    position: relative;
                    cursor: move;
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
            </style>
            <div class="devvn_gm_wrap">
                <div class="devvn_gm_box">
                    <ul class="devvn_gm_images"></ul>
                    <input type="hidden" id="devvn_image_gallery" name="devvn_image_gallery" value="">
                </div>
                <a href="#" class="devvn_gm_addimages" data-choose="<?php _e('Add Images','devvn');?>" data-update="<?php _e('Add Images','devvn');?>" data-edit="<?php _e('Edit image','devvn');?>" data-delete="<?php _e('Remove','devvn');?>" data-text="<?php _e('Delete','devvn');?>"><?php _e('Add gallery images','devvn');?></a>
            </div>
            <script>
                (function($){
                    $(document).ready(function(){
                        $('body').on( 'click', '.devvn_gm_addimages', function( event ) {
                            event.preventDefault();
                            var product_gallery_frame;
                            var $el = $(this);
                            var thisParents = $el.closest('.devvn_gm_wrap');
                            var $image_gallery_ids = thisParents.find('#devvn_image_gallery');
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

                                        $product_images.append( '<li class="image" data-attachment_id="' + attachment.id + '"><div class="li_img_box"><img src="' + attachment_image + '" /><ul class="actions"><li><a href="#" class="gm_edit" title="' + $el.data('edit') + '"><span class="dashicons dashicons-edit"></span></a></li><li><a href="#" class="gm_delete" title="' + $el.data('delete') + '"><span class="dashicons dashicons-no-alt"></span></a></li></ul></div></li>' );
                                    }
                                });

                                $image_gallery_ids.val( attachment_ids );
                            });

                            // Finally, open the modal.
                            product_gallery_frame.open();
                        });
                        // Remove images
                        $( 'body' ).on( 'click', '.devvn_gm_images a.gm_delete', function() {
                            var parentsThis = $(this).closest('.devvn_gm_wrap');
                            var $image_gallery_ids = parentsThis.find('#devvn_image_gallery');

                            $( this ).closest( 'li.image' ).remove();

                            var attachment_ids = '';

                            parentsThis.find('ul li.image' ).each( function() {
                                var attachment_id = jQuery( this ).attr( 'data-attachment_id' );
                                attachment_ids = (attachment_ids) ? attachment_ids + ',' + attachment_id : attachment_id;
                            });

                            $image_gallery_ids.val( attachment_ids );

                            return false;
                        });
                        //Edit images
                        $( 'body' ).on( 'click', '.devvn_gm_images a.gm_edit', function() {
                            event.preventDefault();
                            var product_gallery_frame;
                            var $el = $(this);

                            // If the media frame already exists, reopen it.
                            if ( product_gallery_frame ) {
                                product_gallery_frame.open();
                                return;
                            }

                            // Create the media frame.
                            product_gallery_frame = wp.media({
                                frame     : 'edit-attachments',
                                controller: {
                                    // Needed to trick Edit modal to think there is a gridRouter
                                    gridRouter: {
                                        navigate: function ( destination )
                                        {
                                        },
                                        baseUrl : function ( url )
                                        {
                                        }
                                    }
                                },
                                /*library   : '',
                                model     : ''*/
                            });
                            product_gallery_frame.open();
                        });

                    });
                })(jQuery);
            </script>
            <?php
        }
        public function save_metabox( $post_id, $post ) {
            $nonce_name   = isset( $_POST['custom_nonce'] ) ? $_POST['custom_nonce'] : '';
            $nonce_action = 'custom_nonce_action';
            if ( ! isset( $nonce_name ) ) {
                return;
            }
            if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
                return;
            }
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
            if ( wp_is_post_autosave( $post_id ) ) {
                return;
            }
            if ( wp_is_post_revision( $post_id ) ) {
                return;
            }
        }
    }
    new DevVN_Gallery_Meta_Box();
}
