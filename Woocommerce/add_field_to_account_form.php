/*
Add this to functions.php
Edit account form
Add tab to account page: https://wpbeaches.com/change-rename-woocommerce-endpoints-accounts-page/
*/
add_action( 'woocommerce_edit_account_form', 'my_woocommerce_edit_account_form' );
add_action( 'woocommerce_save_account_details', 'my_woocommerce_save_account_details' );
 
function my_woocommerce_edit_account_form() { 
  $user_id = get_current_user_id();
  $user = get_userdata( $user_id ); 
  if ( !$user )
    return; 
  $twitter = get_user_meta( $user_id, 'twitter', true );
  $url = $user->user_url; 
  ?> 
  <fieldset>
    <legend>Social information</legend>
    <p>Fill in this information about your social media accounts.</p>
    <p class="form-row form-row-thirds">
      <label for="twitter">Twitter Username:</label>
      <input type="text" name="twitter" value="<?php echo esc_attr( $twitter ); ?>" class="input-text" />
    </p>
  </fieldset> 
  <fieldset>
    <legend>Additional Information</legend>
    <p class="form-row form-row-thirds">
      <label for="url">Website:</label>
      <input type="text" name="url" value="<?php echo esc_attr( $url ); ?>" class="input-text" />
    </p>
  </fieldset> 
  <?php 
} 
function my_woocommerce_save_account_details( $user_id ) { 
  update_user_meta( $user_id, 'twitter', htmlentities( $_POST[ 'twitter' ] ) ); 
  $user = wp_update_user( array( 'ID' => $user_id, 'user_url' => esc_url( $_POST[ 'url' ] ) ) ); 
}
