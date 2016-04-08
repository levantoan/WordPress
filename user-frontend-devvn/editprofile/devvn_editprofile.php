<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
require_once dirname( __FILE__ ) . '/functions/scripts.php';
require_once dirname( __FILE__ ) . '/functions/default-fields.php';
require_once dirname( __FILE__ ) . '/functions/tabs.php';
require_once dirname( __FILE__ ) . '/functions/userdevvn-functions.php';
require_once dirname( __FILE__ ) . '/functions/save-fields.php';

function userdevvn_show_profile() {
	
	if( ! is_user_logged_in() )
		return;

	?>
	<div class="before_wrapper_userdevvn">
		<?php do_action('before_wrapper_userdevvn');?>
	</div>
	<div class="userdevvn-wrapper">
		
		<?php
			
			$userdevvn_tabs = apply_filters(
				'userdevvn_tabs',
				array()	
			);
			
			do_action( 'userdevvn_before_tabs', $userdevvn_tabs, get_current_user_id() );	
			
		?>
		
		<ul class="userdevvn-tabs" id="userdevvn-tabs">
			
			<?php
				
				/**
				* set an array of tab titles and ids
				* the id set here should match the id given to the content wrapper
				* which has the class tab-content included in the callback function
				* @hooked userdevvn_add_profile_tab - 10
				* @hooked userdevvn_add_password_tab - 20
				*/
				$userdevvn_tabs = apply_filters(
					'userdevvn_tabs',
					array()
				);
				
				if( ! empty( $userdevvn_tabs ) ) {
					foreach( $userdevvn_tabs as $userdevvn_tab ) {						
						userdevvn_tab_list_item( $userdevvn_tab );
					}
				}
				
			?>	
			
		</ul><!-- // userdevvn-tabs -->
		
		<?php
			foreach( $userdevvn_tabs as $userdevvn_tab ) {
				
				$content_class = '';
				
				if( $userdevvn_tab[ 'content_class' ] != '' ) {					
					$content_class .= ' ' . $userdevvn_tab[ 'content_class' ];					
				}
				
				do_action( 'userdevvn_before_tab_content', $userdevvn_tab[ 'id' ], get_current_user_id() );				
				?>				
								
				<div class="tab-content<?php echo esc_attr( $content_class ); ?>" id="<?php echo esc_attr( $userdevvn_tab[ 'id' ] ); ?>">
					
					<form method="post" action="#<?=esc_attr( $userdevvn_tab[ 'id' ] )?>" class="userdevvn-form-<?php echo esc_attr( $userdevvn_tab[ 'id' ] ); ?>">
						
						<?php							
							if( function_exists( $userdevvn_tab[ 'callback' ] ) ) {
								$userdevvn_tab[ 'callback' ]( $userdevvn_tab );
							} else {
								userdevvn_default_tab_content( $userdevvn_tab );
							}						
						?>						
						<?php							
							wp_nonce_field(
								'userdevvn_nonce_action',
								'userdevvn_nonce_name'
							);						
						?>					
					</form>					
				</div>				
				<?php
						
				/**
				 * @hook userdevvn_after_tab_content
				 * fires after the contents of the tab are outputted
				 * @param (string) $tab_id the id of the tab being displayed. This can be used to target a particular tab.
				 * @param (int) $current_user_id the user if of the current user to add things targetted to a specific user only.
				 */
				do_action( 'userdevvn_after_tab_content', $userdevvn_tab[ 'id' ], get_current_user_id() );		
				
			} // end tabs loop

		?>
	
	</div><!-- // userdevvn-wrapper -->
	<?php do_action('after_wrapper_userdevvn');?>
	<?php	
}