<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );?>
<form id="devvn_login" class="devvn_form" action="" method="post">
	<h1>Login</h1>
    <div class="devvn-status">
    <?php 
    if ( isset( $_GET['reset'] ) && $_GET['reset'] ) {
		_e( '<p class="devvn-success">Your password has been reset.</p>', 'devvn' );
	}
    ?>
    </div>
    <?php wp_nonce_field('ajax-login-nonce', 'security'); ?>
    <input name="action" value="devvn_user_login" type="hidden"/>
    <div class="devvn-row">
	    <label for="username">Username</label>
	    <input id="username" type="text" class="required" name="username">
    </div>
    <div class="devvn-row">
	    <label for="password">Password</label>
	    <input id="password" type="password" class="required" name="password">
    </div>
    <div class="devvn-submit">
    	<input class="submit_button" type="submit" value="LOGIN">
    	<p class="login-remember"><label><input name="rememberme" type="checkbox" id="" value="forever" /> <?php _e('Remember Me','devvn');?></label></p>
    </div>
    <div class="devvn-row">
    <?php if ( get_option( 'users_can_register' ) ):?>New to site? <a href="<?=wp_registration_url()?>">Create an Account</a> | <?php endif;?><a class="text-link" href="<?=wp_lostpassword_url(get_the_permalink())?>">Lost password?</a>
    </div>			
</form>