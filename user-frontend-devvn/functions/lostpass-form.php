<?php 
if ( ! defined( 'ABSPATH' ) ) {exit;}
echo '<div class="devvn-status">';
if ( isset( $_POST['userdevvn_reset_password'] ) && isset( $_POST['user_login'] ) && isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'lost_password' ) ) {
	devvn_retrieve_password();
}
echo '</div>';
// arguments to pass to template
$args = array( 'form' => 'lost_password' );

// process reset key / login from email confirmation link
if ( isset( $_GET['key'] ) && isset( $_GET['login'] ) ) {

	$user = userdevvn_check_password_reset_key( $_GET['key'], $_GET['login'] );

	// reset key / login is correct, display reset password form with hidden key / login values
	if( is_object( $user ) ) {
		$args['form'] = 'reset_password';
		$args['key'] = esc_attr( $_GET['key'] );
		$args['login'] = esc_attr( $_GET['login'] );
	}
} elseif ( isset( $_GET['reset'] ) ) {
	_e( 'Your password has been reset. <a href="">Log in</a>', 'devvn' );
}
?>


<form method="post" class="lost_reset_password">

	<?php if( 'lost_password' === $args['form'] ) : ?>

		<p><?php _e( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'devvn' ); ?></p>

		<p class="form-row form-row-first"><label for="user_login"><?php _e( 'Username or email', 'devvn' ); ?></label>
		<input class="input-text" type="text" name="user_login" id="user_login" /></p>

	<?php else : ?>
		<?php userdevvn_process_reset_password();?>

		<p><?php _e( 'Enter a new password below.', 'devvn'); ?></p>

		<p class="form-row form-row-first">
			<label for="password_1"><?php _e( 'New password', 'devvn' ); ?> <span class="required">*</span></label>
			<input type="password" class="input-text" name="password_1" id="password_1" value=""/>
		</p>
		<p class="form-row form-row-last">
			<label for="password_2"><?php _e( 'Re-enter new password', 'devvn' ); ?> <span class="required">*</span></label>
			<input type="password" class="input-text" name="password_2" id="password_2" value=""/>
		</p>

		<input type="hidden" name="reset_key" value="<?php echo isset( $args['key'] ) ? $args['key'] : ''; ?>" />
		<input type="hidden" name="reset_login" value="<?php echo isset( $args['login'] ) ? $args['login'] : ''; ?>" />

	<?php endif; ?>

	<div class="clear"></div>

	<p class="form-row">
		<input type="hidden" name="userdevvn_reset_password" value="true" />
		<input type="submit" class="button" value="<?php echo 'lost_password' === $args['form'] ? __( 'Reset Password', 'devvn' ) : __( 'Save', 'devvn' ); ?>" />
	</p>

	<?php wp_nonce_field( $args['form'] ); ?>

</form>