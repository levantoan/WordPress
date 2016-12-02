<?php if(is_user_logged_in()){
	wp_safe_redirect(home_url());
	exit();
}
$activeCode = isset($_GET['active-acc']) ? esc_attr($_GET['active-acc']) : '';
$activeEmail = isset($_GET['email']) ? sanitize_email($_GET['email']) : '';
?>
<?php
/*
 * Template Name:	User Register
 */
?>
<?php get_header(); ?>
<?php while (have_posts()):the_post();?>
<div class="user_register">
	<div class="container">
		<div class="row">
			<?php 
			if($activeCode && $activeEmail && is_email($activeEmail) && email_exists($activeEmail)):
				$user_info = get_user_by( 'email', $activeEmail );
				$activation_code = '';
				$mess = '';
				if( !empty( $user_info ) && !is_wp_error($user_info)){
					$devvn_status = get_user_meta( $user_info->ID, 'devvn_status', true );
					if($devvn_status != 'active'){ 
						$activation_code = get_user_meta( $user_info->ID, 'devvn_activekey', true );
						if($activation_code == $activeCode){
							update_user_meta($user_info->ID,'devvn_status','active');
							do_action('after_active_ok', $user_info->ID);
							$mess = 'Account is ready to login.';
						}else{
							$mess = 'Your account not activated.';
						}
					}else{
						$mess = 'Your account was activated.';
					}
					?>
					<div class="col-xs-12 user_register_content">
						<h1><?php echo $mess;?></h1>
					</div>
					<?php 
				}
				?>			
			<?php else :?>
			<div class="col-sm-4 user_register_content">
				<h1><?php the_title();?></h1>
				<?php the_content();?>
			</div>
			<div class="col-sm-8">
				<div class="register_form">
					<form action="" method="post" class="register_form_action">
						<div class="row">
							<div class="col-sm-6">
								<p><label>
									<span>Password</span>
									<input type="password" name="password" class="password"/>
								</label></p>
							</div>		
							<div class="col-sm-6">
								<p><label>
									<span>Repeat Password</span>
									<input type="password" name="re_password" class="re_password"/>
								</label></p>
							</div>
							<div class="col-sm-6">
								<p><label>
									<span>First name</span>
									<input type="text" name="firstname" class="firstname"/>
								</label></p>
							</div>
							<div class="col-sm-6">
								<p><label>
									<span>Last name</span>
									<input type="text" name="lastname" class="lastname"/>
								</label></p>
							</div>
							<div class="col-sm-6">
								<p><label>
									<span>Company</span>
									<input type="text" name="company" class="company"/>
								</label></p>
							</div>
							<div class="col-sm-6">
								<p><label>
									<span>Address</span>
									<select name="address" class="address">
										<option value="">Address</option>
										<option value="1">English</option>
										<option value="2">Vietnam</option>
									</select>
								</label></p>
							</div>
							<div class="col-sm-6">
								<p><label>
									<span>City</span>
									<input type="text" name="city" class="city"/>
								</label></p>
							</div>
							<div class="col-sm-6">
								<div class="row">
									<div class="col-xs-6">
										<p><label>
											<span>State/province</span>
											<select name="state_province" class="state_province">
												<option value="">State province</option>
												<option value="1">English</option>
												<option value="2">Vietnam</option>
											</select>
										</label></p>										
									</div>
									<div class="col-xs-6">
										<p><label>
											<span>Post code</span>
											<input type="text" name="postcode" class="postcode"/>
										</label></p>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<p><label>
									<span>Country</span>
									<select name="country" class="country">
										<option value="">Country</option>
										<option value="1">English</option>
										<option value="2">Vietnam</option>
									</select>
								</label></p>
							</div>
							<div class="col-sm-6">
								<p><label>
									<span>Email</span>
									<input type="email" name="email" class="email"/>
								</label></p>
							</div>
							<div class="col-sm-6">
								<p><label>
									<span>Telephone</span>
									<input type="text" name="tel" class="tel"/>
								</label></p>
							</div>
							<div class="col-sm-6">
								<p><label>
									<span>Fax</span>
									<input type="text" name="fax" class="fax"/>
								</label></p>
							</div>
							<div class="col-sm-6">
								<p><label>
									<span>Industry</span>
									<select name="industry" class="industry">
										<option value="">Industry</option>
										<option value="1">English</option>
										<option value="2">Vietnam</option>
									</select>
								</label></p>
							</div>
							<div class="col-sm-6">
								<p><label>
									<span>Preferred language</span>
									<select name="preferred_language" class="preferred_language">
										<option value="1">English</option>
										<option value="2">Vietnamese</option>
									</select>
								</label></p>
							</div>	
							<div class="col-xs-12 submit_wrap">
								<?php wp_nonce_field('nonce_register_action','nonce_register');?>
								<input type="hidden" name="action" value="devvn_user_register"/>
								<input value="Register" name="submit" type="submit" class="submit"/>
								<i class="fa fa-spinner fa-spin"></i>
							</div>	
							<div class="col-xs-12 register_mess"></div>
						</div>
					</form>
				</div>
			</div>
			<?php endif;?>
		</div>
	</div>
</div>
<?php endwhile;?>
<?php get_footer(); ?>