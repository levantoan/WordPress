<form id="devvn_lost_password" class="devvn_form" action="" method="post">
	<h1>Lost Password</h1>
    <div class="devvn-status"></div>
    <?php wp_nonce_field('ajax-lostpass-nonce', 'lostpasssecurity'); ?>  
    <div class="devvn-row">       
	    <?php _e('<p>Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.</p>','devvn');?>
    </div>
    <div class="devvn-row">
	    <label for="email">Email</label>
	    <input id="register_email" type="email" name="register_email" class="required email">    
    </div>
    <div class="devvn-submit">
    	<input class="submit_button" type="submit" value="Reset Password">
    </div>
</form>