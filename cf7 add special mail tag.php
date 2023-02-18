<?php
/*
Ex [_tai_lieu] with acf field
*/
add_filter( 'wpcf7_special_mail_tags', 'tai_lieu_mail_tag', 10, 4 );
function tai_lieu_mail_tag( $output, $name, $html, $mail_tag = null ) {
    if ( ! $mail_tag instanceof WPCF7_MailTag ) {
		wpcf7_doing_it_wrong(
			sprintf( '%s()', __FUNCTION__ ),
			__( 'The fourth parameter ($mail_tag) must be an instance of the WPCF7_MailTag class.', 'contact-form-7' ),
			'5.2.2'
		);
	}

	$submission = WPCF7_Submission::get_instance();

	if ( ! $submission ) {
		return $output;
	}

	$post_id = (int) $submission->get_meta( 'container_post_id' );

	if ( ! $post_id
	or ! $post = get_post( $post_id ) ) {
		return '';
	}

    
    if(have_rows('tai_lieu', $post->ID)):
        ob_start();
        ?>
            <strong><?php _e('Tài liệu kỹ thuật:','devvn');?></strong><br>
            <ul>
            <?php while(have_rows('tai_lieu', $post->ID)):the_row();
            $ten_file = get_sub_field('ten_file');
            $chon_file = get_sub_field('chon_file');
            if($chon_file){
            $filename = $ten_file ? $ten_file : (isset($chon_file['filename']) ? $chon_file['filename'] : '');
            $url = isset($chon_file['url']) ? $chon_file['url'] : '';
            ?>
            <li><a href="<?php echo $url;?>" target="_blank" download><?php echo $filename;?></a></li>
            <?php
            }
            endwhile;?>
            </ul>
        <?php
        $tailieu = ob_get_clean();
    else:
    $tailieu = '';
    endif;
    
	if ( '_tai_lieu' == $name && $tailieu) {
		return !$html ? esc_html( $tailieu ) : $tailieu;
	}
	
	return $output;
}
