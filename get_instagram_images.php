/*
Hiển thị tối đa 12 ảnh mới nhất trong instagram cho user
Cách dùng: [instagram_widget user="_Your User Here_" show="_Your Number show (max 12)_"]
by www.levantoan.com
*/
function scrape_instagram($username, $slice = 9) {
	if (false === ($instagram = get_transient('instagram-photos-'.sanitize_title_with_dashes($username)))) {
		
		$remote = wp_remote_get('http://instagram.com/'.trim($username));

		if (is_wp_error($remote)) 
			return new WP_Error('site_down', __('Unable to communicate with Instagram.', 'devvn'));

		if ( 200 != wp_remote_retrieve_response_code( $remote ) ) 
			return new WP_Error('invalid_response', __('Instagram did not return a 200.', 'devvn'));

		$shards = explode('window._sharedData = ', $remote['body']);
		$insta_json = explode(';</script>', $shards[1]);
		$insta_array = json_decode($insta_json[0], TRUE);

		if (!$insta_array)
			return new WP_Error('bad_json', __('Instagram has returned invalid data.', 'devvn'));

		$images = $insta_array['entry_data']['ProfilePage'][0]['user']['media']['nodes'];
		$usercheck = $insta_array['entry_data']['ProfilePage'][0]['user']['username'];	
			
		$instagram = array();		
		foreach ($images as $image) {

			if (!($image['is_video']) && $usercheck == $username) {

				$instagram[] = array(
					'code' 			=> $image['code'],
					'description' 	=> $image['caption'],
					'link' 			=> 'https://www.instagram.com/p/'.$image['code'],
					'time'			=> $image['date'],
					'comments' 		=> $image['comments']['count'],
					'likes' 		=> $image['likes']['count'],
					'thumbnail' 	=> $image['thumbnail_src'],
					'large' 		=> $image['display_src']
				);
			}
		}

		$instagram = base64_encode( serialize( $instagram ) );
		set_transient('instagram-photos-'.sanitize_title_with_dashes($username), $instagram, apply_filters('null_instagram_cache_time', HOUR_IN_SECONDS*2));
	}

	$instagram = unserialize( base64_decode( $instagram ) );

	return array_slice($instagram, 0, $slice);
}
add_shortcode('instagram_widget', 'instagram_widget_func');
function instagram_widget_func($atts){
	extract(shortcode_atts(array(
		'user'	=>	'vickyheiler',
		'show'	=>	20
	), $atts));
	
	if(!function_exists('scrape_instagram')) return;
	
	$listInstagram = scrape_instagram($user,$show);
	
	if ( is_wp_error( $listInstagram ) ) {		
		echo $listInstagram->get_error_message();
		return;
	}
	
	if(!is_array($listInstagram) || empty($listInstagram)) return;
	
	?>
	<div class="instagram_title"><a href="https://www.instagram.com/<?=sanitize_title_with_dashes($user)?>">INSTAGRAM @<?=sanitize_title_with_dashes($user)?></a></div>
	<div class="instagram_list">
	<?php foreach ($listInstagram as $image){?>
		<div class="instagram_list_box"><a href="<?=esc_url($image['link'])?>" target="_blank"><img src="<?=$image['thumbnail']?>"></a></div>
	<?php }?>
	</div>
	<?php
}
