<?php
/*instagram shortcode
 * Get access_token to http://instagram.pixelunion.net/
 * Or place [CLIENT_ID_HERE] to your Client ID => https://instagram.com/oauth/authorize/?client_id=[CLIENT_ID_HERE]&redirect_uri=http://localhost&response_type=token
 
 Used: [instagram_widget access_token="_Your Access Token_" show="20"]
 * */
function scrape_instagram($access_token = '', $slice = 20, $type = "image") {	
	$nametransient = 'instagram-photos-'.sanitize_title_with_dashes($access_token.$type);
	delete_transient($nametransient);
	if (false === ($instagram = get_transient($nametransient))) {
		
		$remote = wp_remote_get('https://api.instagram.com/v1/users/self/media/recent/?access_token='.$access_token);		

		if ( 200 != wp_remote_retrieve_response_code( $remote ) ) {
			$error_message = wp_remote_retrieve_response_message($remote);
			$error_code =  wp_remote_retrieve_response_code( $remote );
			return new WP_Error($error_code, __($error_message, 'devvn'));
		}

		if (!$remote)
			return new WP_Error('bad_json', __('Instagram has returned invalid data.', 'devvn'));
		
		$result	= json_decode( $remote['body'] );
				
		if( 200 != $result->meta->code) 
			return new WP_Error('bad_json', __('Instagram has returned invalid data.', 'devvn'));
				
		$instagram = array();		
		foreach ($result->data as $image) {
			if (($image->type == $type)) {
				$instagram[] = array(
					'description' 	=> (isset($image->caption->text))?$image->caption->text:'',
					'link' 			=> (isset($image->link))?$image->link:'',
					'time'			=> (isset($image->created_time))?$image->created_time:'',
					'comments' 		=> (isset($image->comments->count))?$image->comments->count:'',
					'likes' 		=> (isset($image->likes->count))?$image->likes->count:'',
					'thumbnail' 	=> (isset($image->images->thumbnail->url))?$image->images->thumbnail->url:'',
					'large' 		=> (isset($image->images->standard_resolution->url))?$image->images->standard_resolution->url:'',
					'medium' 		=> (isset($image->images->low_resolution->url))?$image->images->low_resolution->url:'',
				);
			}
		}

		$instagram = base64_encode( serialize( $instagram ) );
		set_transient($nametransient, $instagram, apply_filters('null_instagram_cache_time', HOUR_IN_SECONDS*2));
	}

	$instagram = unserialize( base64_decode( $instagram ) );

	return array_slice($instagram, 0, $slice);
}
add_shortcode('instagram_widget', 'instagram_widget_func');
function instagram_widget_func($atts){
	extract(shortcode_atts(array(
		'access_token'	=>	'',
		'show'		=>	20,
		'type'		=>	'image',
		'user'		=>	''
	), $atts));
	
	if(!function_exists('scrape_instagram')) return;
	
	$listInstagram = scrape_instagram($access_token,$show,$type);
	
	if ( is_wp_error( $listInstagram ) ) {		
		echo '<p style="text-align:center;">'.$listInstagram->get_error_message().' - INSTAGRAM</p>';
		return;
	}
	
	if(!is_array($listInstagram) || empty($listInstagram)) return;
	
	?>
	<div class="instagram_title">
	<a <?=($user)?'href="https://www.instagram.com/'.$user.'" target="_blank"':'';?>>INSTAGRAM <?=($user)?'@'.$user:'';?></a>
	</div>
	<div class="instagram_list">
	<?php foreach ($listInstagram as $image){?>
		<div class="instagram_list_box"><a href="<?=esc_url($image['link'])?>" target="_blank"><img src="<?=$image['thumbnail']?>"></a></div>
	<?php }?>
	</div>
	<?php
}
