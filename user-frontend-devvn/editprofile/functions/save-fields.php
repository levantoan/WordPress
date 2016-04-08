<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
function userdevvn_save_fields( $tabs, $user_id ) {
	
	/* check the nonce */
	if( ! isset( $_POST[ 'userdevvn_nonce_name' ] ) || ! wp_verify_nonce( $_POST[ 'userdevvn_nonce_name' ], 'userdevvn_nonce_action' ) )
		return;
	
	/* set an array to store messages in */
	$messages = array();
	
	/* get the POST data */
	$tabs_data = $_POST;
	
	/**
	 * remove the following array elements from the data
	 * password
	 * nonce name
	 * wp refere - sent with nonce
	 */
	unset( $tabs_data[ 'password' ] );
	unset( $tabs_data[ 'userdevvn_nonce_name' ] );
	unset( $tabs_data[ '_wp_http_referer' ] );
	
	/* lets check we have some data to save */
	if( empty( $tabs_data ) )
		return;
		
	/**
	 * setup an array of reserved meta keys
	 * to process in a different way
	 * they are not meta data in wordpress
	 * reserved names are user_url and user_email as they are stored in the users table not user meta
	 */
	
	$reserved_ids = apply_filters(
		'userdevvn_reserved_ids',
		array(
			'user_email',
			'user_url',
		)
	);

	/* loop through the data array - each element of this will be a tabs data */
	foreach( $tabs_data as $tab_data ) {
		
		/**
		 * loop through this tabs array
		 * the ket here is the meta key to save to
		 * the value is the value we want to actually save
		 */
		foreach( $tab_data as $key => $value ) {
			
			/* if the key is the save sumbit - move to next in array */
			if( $key == 'userdevvn_save' || $key == 'userdevvn_nonce_action' )
				continue;
			
			/* check whether the key is reserved - handled with wp_update_user */
			if( in_array( $key, $reserved_ids ) ) {
				
				$user_id = wp_update_user(
					array(
						'ID' => $user_id,
						$key => $value
					)
				);
				
				/* check for errors */
				if ( is_wp_error( $user_id ) ) {
					
					/* update failed */
					$messages[ 'update_failed' ] = '<p class="error">There was a problem with updating your profile.</p>';
				
				}
			
			/* just standard user meta - handle with update_user_meta */
			} else {
				
				/* update the user meta data */
				$meta = update_user_meta( $user_id, $key, $value );
				
				/* check the update was succesfull */
				if( $meta == false ) {
					
					/* update failed */
					$messages[ 'update_failed' ] = '<p class="error">There was a problem with updating your profile.</p>';
					
				}
				
			}
			
		} // end tab loop
		
	} // end data loop
	
	/* check if we have an messages to output */
	if( empty( $messages ) ) {
		?>
		<div class="messages">
		<?php
			foreach( $messages as $message ) {
				echo $message;
			}
		?>
		</div><!-- // messages -->
		<?php
	} else {
		?>
			<div class="messages"><p class="updated">Your profile was updated successfully!</p></div>
		<?php
	}
}
add_action( 'userdevvn_before_tabs', 'userdevvn_save_fields', 5, 2 );

function userdevvn_save_password( $tabs, $user_id ) {
	
	$messages = array();
	
	$data = $_POST[ 'password' ];
	
	$password = $data[ 'user_pass' ];
	$password_check = $data[ 'user_pass_check' ];
	
	if( empty( $password ) )
		return;
	
	if( $password != $password_check ) {
		$messages[ 'password_mismatch' ] = '<p class="error">Please make sure the passwords match.</p>';		
	}
	
	$pass_length = strlen( $password );
	
	if( $pass_length < apply_filters( 'userdevvn_password_length', 12 ) ) {
		$messages[ 'password_length' ] = '<p class="error">Please make sure your password is a minimum of ' . apply_filters( 'userdevvn_password_length', 12 ) . ' characters long.</p>';		
	}
	
	$pass_complexity = preg_match( apply_filters( 'userdevvn_password_regex', '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d,.;:]).+$/' ), $password );
	
	if( $pass_complexity == false ) {		
		$messages[ 'password_complexity' ] = '<p class="error">Your password must contain at least 1 uppercase, 1 lowercase letter and at least 1 number</p>';
	}
	
	if( empty( $messages ) ) {
		wp_set_password( $password, $user_id );
		echo '<div class="messages"><p class="updated">You\'re password was successfully changed and you have been logged out. Please <a href="' . esc_url( wp_login_url() ) . '">login again here</a>.</p></div>';
	} else {		
		?>
		<div class="messages">
		<?php
		foreach( $messages as $message ) {
			echo $message;
		}
		?>
		</div><!-- // messages -->
		<?php		
	}	
}

add_action( 'userdevvn_before_tabs', 'userdevvn_save_password', 10, 2 );