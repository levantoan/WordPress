<?php
class MenuOrder_Options_Meta_Box {

    /**
     * Constructor.
     */
    public function __construct() {
        if ( is_admin() ) {
            add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
            add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
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
        $post_types = array( 'post', 'page', 'product' );
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'menuorder-meta-box',
                __('Options Menu Order', 'devvn-menuorder'),
                array($this, 'render_metabox'),
                $post_type,
                'normal',
                'high'
            );
        }

    }

    /**
     * Renders the meta box.
     */
    public function render_metabox( $post ) {
        wp_nonce_field( 'menuorder_nonce_action', 'menuorder_nonce' );
        $value = get_post_meta( $post->ID, '_my_meta_value_key', true );
        ?>
        <label for="myplugin_new_field">
            <?php _e( 'Description for this field', 'textdomain' ); ?>
        </label>
        <input type="text" id="myplugin_new_field" name="myplugin_new_field" value="<?php echo esc_attr( $value ); ?>" size="25" />
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
        $nonce_name   = isset( $_POST['menuorder_nonce'] ) ? $_POST['menuorder_nonce'] : '';
        $nonce_action = 'menuorder_nonce_action';

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
        $mydata = sanitize_text_field( $_POST['myplugin_new_field'] );

        // Update the meta field.
        update_post_meta( $post_id, '_my_meta_value_key', $mydata );

    }
}

new MenuOrder_Options_Meta_Box();
