<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function userdevvn_login_func(){
	$action = $_GET['action'];
	ob_start();
	?>
	<div class="devvn-form">
		<?php if( $action == 'register' && !is_user_logged_in() ):?>
			<?php include_once dirname(__FILE__) . '/register-form.php';?>			
		<?php elseif(($action == 'lost_password' || $action == 'rp') && !is_user_logged_in()):?>
			<?php include_once dirname(__FILE__) . '/lostpass-form.php';?>
		<?php else:?>
			<?php if(!is_user_logged_in()):?>
				<?php include_once dirname(__FILE__) . '/login-form.php';?>
			<?php else:?>
				<?php include_once dirname(__FILE__) . '/dashboard-form.php';?>
			<?php endif;?>
		<?php endif;?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode('userdevvn_login', 'userdevvn_login_func');