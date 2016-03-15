<?php
function remove_admin_menu()
{
    $admins = array( 
        'SinhVienLife',
    );
    $current_user = wp_get_current_user();
    if( !in_array( $current_user->user_login, $admins ) )
    {
        remove_menu_page('edit.php?post_type=acf');
        remove_menu_page('tools.php');
        remove_menu_page('edit-comments.php');
        remove_menu_page('plugins.php');
        //remove_menu_page('users.php');
        
        //Remove menu post type
        remove_menu_page('itsec');
        remove_menu_page( 'wpcf7' );

        //Remove Sub menu
        global $submenu;
        unset($submenu['themes.php'][6]); // Customize
        unset($submenu['options-general.php'][30]);
        unset($submenu['options-general.php'][40]);
        unset($submenu['options-general.php'][41]);
        unset($submenu['options-general.php'][42]);
        unset($submenu['options-general.php'][43]);
        unset($submenu['users.php'][5]);
        unset($submenu['users.php'][10]);
    }
}
add_action( 'admin_menu', 'remove_admin_menu', 999 );
