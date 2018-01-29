<?php

/* Add field to edit account form
* Author by levantoan.com
* Add to functions.php
* Change "khu_vuc" at line 16 and 37 to your name field
*/

add_action('woocommerce_edit_account_form','devvn_woocommerce_edit_account_form');
function devvn_woocommerce_edit_account_form(){
    $user_id = get_current_user_id();
    $user = get_userdata( $user_id );

    if ( !$user ) return;

    $khu_vuc = get_field_object('khu_vuc', $user);
    if(is_array($khu_vuc) && $khu_vuc && !empty($khu_vuc)){
    ?>
    <fieldset>
        <legend><?php _e( 'Thông tin thêm', 'woocommerce' ); ?></legend>

        <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
            <label for="account_first_name"><?php _e( 'Chọn vùng niềm bạn quan tâm', 'woocommerce' ); ?></label>
            <?php foreach ($khu_vuc['choices'] as $k=>$v):?>
                <input type="checkbox" class="" name="user_khuvuc[]" id="user_khuvuc" value="<?php echo esc_attr( $k ); ?>" <?php echo (in_array($k, $khu_vuc['value'])) ? 'checked="checked"' : ''?>/> <?php echo $v;?><br>
            <?php endforeach;?>
        </p>
    </fieldset>
    <div class="clear"></div>
    <?php
    }
}

add_action( 'woocommerce_save_account_details', 'devvn_woocommerce_save_account_details' );
function devvn_woocommerce_save_account_details($user_id){
    $user_khuvuc = ! empty( $_POST['user_khuvuc'] ) ? wc_clean( $_POST['user_khuvuc'] ) : array();
    update_user_meta( $user_id, 'khu_vuc', $user_khuvuc );
}
