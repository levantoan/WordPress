<form id="devvn_lost_password" class="devvn_form2" action="" method="post">
	<h1>Lost Password</h1>
    <div class="devvn-status"></div>
    <?php wp_nonce_field('ajax-lostpass-nonce', 'lostpasssecurity'); ?> 
    <input name="action" value="devvn_user_lostpass" type="hidden"/> 
    <div class="devvn-row">       
	    <?php _e('<p>Lost your password? Please enter your email address. You will receive a link to create a new password via email.</p>','devvn');?>
    </div>
    <div class="devvn-row">
	    <label for="user_login">Email</label>
	    <input id="user_login" type="email" name="user_login" class="required">    
    </div>
    <div class="devvn-submit">
    	<input class="submit_button" type="submit" value="Reset Password">
    </div>
</form>