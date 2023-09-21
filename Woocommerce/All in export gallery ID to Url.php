<?php
function woo_gallery_to_url($value){
    if($value){
        $arg_url = array();
        $value = explode(',', $value);
        foreach ($value as $item){
            $arg_url[] = wp_get_attachment_image_url($item, 'full');
        }
        $value = implode(',', $arg_url);
    }
    return $value;
}
