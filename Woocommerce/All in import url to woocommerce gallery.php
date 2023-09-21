<?php

function change_sideload_file_extension( $file ) {
    switch ($file['type']){
        case 'image/png':
            $ext = '.png';
            break;
        case 'image/svg+xml':
            $ext = '.svg';
            break;
        case 'image/gif':
            $ext = '.gif';
            break;
        case 'image/bmp':
            $ext = '.bmp';
            break;
        case 'image/tiff':
            $ext = '.tiff';
            break;
        case 'image/vnd.adobe.photoshop':
            $ext = '.psd';
            break;
        default:
            $ext = '.jpg';
            break;
    }
    $ext = apply_filters('devvn_sideload_file_extension_ext', $ext, $file);
    $file['name'] = $file['name'] . $ext;
    return apply_filters('devvn_sideload_file_extension_file', $file);
}

function import_media_by_url($url = '', $post_id = 0){
    $file = array();
    $file['name'] = basename( $url );
    $file['tmp_name'] = download_url( $url );

    if ( is_wp_error( $file['tmp_name'] ) ) {
        @unlink( $file['tmp_name'] );
        return false;
    }

    $filetype = wp_check_filetype( $url );
    $ext = isset($filetype['ext']) ? $filetype['ext'] : '';
    if(!$ext){
        $response = wp_remote_head($url);
        if (!is_wp_error($response) && isset($response['headers']['content-type'])) {
            $ext = $response['headers']['content-type'];
            $file['type'] = $ext;
            if ( ! defined( 'ALLOW_UNFILTERED_UPLOADS' ) ) {
                define( 'ALLOW_UNFILTERED_UPLOADS', TRUE );
            }
            add_filter( 'wp_handle_sideload_prefilter', 'change_sideload_file_extension');
        }
    }

    $attachment_id = media_handle_sideload( $file, $post_id );
    if(!is_wp_error($attachment_id)) {
        $attach_data = wp_generate_attachment_metadata($attachment_id, get_attached_file($attachment_id));
        wp_update_attachment_metadata($attachment_id, $attach_data);
    }
    remove_filter( 'wp_handle_sideload_prefilter', 'change_sideload_file_extension');
    return $attachment_id;
}
function woo_url_to_gallery($value){
    if($value){
        $imgs_args = array();
        foreach ($value as $img){
            $img_id = import_media_by_url(esc_url(trim($img)));
            if($img_id && !is_wp_error($img_id)){
                $imgs_args[] = $img_id;
            }elseif(is_wp_error($img_id)){
                error_log('All import - Error upload img: ' . $img_id->get_error_message());
            }
        }
        if($imgs_args){
            return implode(',', $imgs_args);
        }
    }
}
