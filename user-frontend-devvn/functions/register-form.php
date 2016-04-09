<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );?>
<form id="devvn_register" class="devvn_form" action="" method="post">
	<h1>Register</h1>
    <div class="devvn-status"></div>
    <?php wp_nonce_field('ajax-register-nonce', 'registersecurity'); ?>  
    <input name="action" value="devvn_user_register" type="hidden"/>
    <div class="devvn-row">       
	    <label for="username">Username</label>
	    <input id="username" type="text" name="username" class="required">
    </div>
    <div class="devvn-row">
	    <label for="register_email">Email</label>
	    <input id="register_email" type="email" name="register_email" class="required email">    
    </div>
    <div class="devvn-submit">
    	<input class="submit_button" type="submit" value="SIGNUP">
    </div>
    <div class="devvn-row">Already have an account? <a href="<?=wp_login_url(get_permalink())?>">Login</a></div>   
</form>