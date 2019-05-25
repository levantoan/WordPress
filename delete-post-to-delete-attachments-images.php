<?php
function delete_post_attachments($post_id) {
    global $wpdb;
    $sql = "SELECT ID FROM $wpdb->posts";
    $sql .= " WHERE post_parent = ".$post_id;
    $sql .= " AND post_type = 'attachment'";

    $ids = $wpdb->get_results($sql);
    foreach ( $ids as $id ) {
        wp_delete_attachment($id->ID, true);
    }
}
add_action('before_delete_post', 'delete_post_attachments');
