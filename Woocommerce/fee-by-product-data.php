<?php
class DongLanh_Options_Meta_Box {

    /**
     * Constructor.
     */
    public function __construct() {
        if ( is_admin() ) {
            add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
            add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
        }

        add_action( 'woocommerce_cart_calculate_fees', array( $this, 'add_fee') );

    }

    function add_fee() {
        $has_donglanh = false;

        $items = WC()->cart->get_cart();

        foreach($items as $item => $values) {
            $sp_donglanh = (int) get_post_meta( $values['data']->get_id(), 'sp_donglanh', true );
            if($sp_donglanh) {
                $has_donglanh = true;
                break;
            }
        }

        if ( $has_donglanh ) {
            WC()->cart->add_fee( 'Phí gửi hàng lạnh', 590 );
        }
    }

    /**
     * Meta box initialization.
     */
    public function init_metabox() {
        add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
        add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );
    }

    /**
     * Adds the meta box.
     */
    public function add_metabox($post_type) {
        $post_types = array( 'product' );
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'donglanh-meta-box',
                __('Tùy chỉnh sản phẩm', 'devvn'),
                array($this, 'render_metabox'),
                $post_type,
                'side',
                'high'
            );
        }

    }

    /**
     * Renders the meta box.
     */
    public function render_metabox( $post ) {
        wp_nonce_field( 'donglanh_nonce_action', 'donglanh_nonce' );
        $value = (int) get_post_meta( $post->ID, 'sp_donglanh', true );
        ?>
        <label for="sp_donglanh">
            <input type="checkbox" id="sp_donglanh" name="sp_donglanh" value="1" <?php checked(1, $value, true);?> size="25" />
            <?php _e( 'Sản phẩm đông lạnh', 'devvn' ); ?>
        </label>
        <?php
    }

    /**
     * Handles saving the meta box.
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     * @return null
     */
    public function save_metabox( $post_id, $post ) {
        // Add nonce for security and authentication.
        $nonce_name   = isset( $_POST['donglanh_nonce'] ) ? $_POST['donglanh_nonce'] : '';
        $nonce_action = 'donglanh_nonce_action';

        // Check if nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return $post_id;
        }

        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }

        // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return $post_id;
        }

        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return $post_id;
        }

        // Sanitize the user input.
        $mydata = intval( $_POST['sp_donglanh'] );

        // Update the meta field.
        update_post_meta( $post_id, 'sp_donglanh', $mydata );

    }
}

new DongLanh_Options_Meta_Box();
