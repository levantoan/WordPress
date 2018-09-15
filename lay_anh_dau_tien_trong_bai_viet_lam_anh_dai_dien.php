<?php
function _l_save_post($post_id) {
	if (!defined('DOING_AUTOSAVE')||!DOING_AUTOSAVE) {
		if (!($id = wp_is_post_revision($post_id)))
			$id = $post_id;
		if (isset($_POST['content'])&&!get_post_thumbnail_id($id)) {
			$match=array();
			preg_match('/"[^"]*wp\-image\-(\d+)/',$_POST['content'],$match);
			if (isset($match[1])) set_post_thumbnail($post_id, intval($match[1]));
		}
	}
}
add_action('save_post','_l_save_post');
