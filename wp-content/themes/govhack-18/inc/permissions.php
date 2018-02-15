<?php

//
// For Author permission types, give them access to Tiles
// Run on the load themes hook (activation)
// 
function gh_admin_tiles_permission() {
    
    if ( isset( $_GET['activated'] ) ){
        foreach (['administrator', 'editor'] as $roleName){
            $role = get_role($roleName); 
            $role->add_cap('read');
            $role->add_cap('read_tile');
            $role->add_cap('read_tiles');
            $role->add_cap('edit_tile');
            $role->add_cap('edit_tiles');
            $role->add_cap('edit_private_tiles');
            $role->add_cap('edit_published_tiles');
            $role->add_cap('edit_others_tiles');
            $role->add_cap('delete_tile');
            $role->add_cap('delete_tiles');
            $role->add_cap('delete_private_tiles');
            $role->add_cap('delete_published_tiles');
            $role->add_cap('delete_others_tiles');
            $role->add_cap('publish_tiles');
            $role->add_cap('read_private_tiles');
            $role->add_cap('create_tiles');
        }
    }
}
add_action('load-themes.php', 'gh_admin_tiles_permission');

//
// For Author permission types, give them access to access widget
// 
function gh_author_menus_permission() {
    
    $role = get_role('author'); 
    $role->add_cap('edit_theme_options');

    $user = new WP_User(get_current_user_id());     
    if ( !empty( $user->roles) && is_array($user->roles) ) {
        foreach ($user->roles as $role){
            if ( $role == "author" ) { 
               remove_submenu_page( 'themes.php', 'customize.php' );
               remove_submenu_page( 'themes.php', 'themes.php' );
               remove_submenu_page( 'themes.php', 'widgets.php' );
               // remove_submenu_page( 'themes.php', 'nav-menus.php' ); 
            }
        }
    }

}
add_action('admin_menu', 'gh_author_menus_permission');

// CSS to hide the customize menu. Out of sight out of mind?
function gh_author_hide_customize_submenu(){
    
    $user = new WP_User(get_current_user_id());
    if ( !empty( $user->roles) && is_array($user->roles) ) {
        foreach ($user->roles as $role){
            if ( $role == "author" ) { 
                echo '<style>.hide-if-no-customize { display: none; /** Hide Customize entries in Appearance toolbar **/ }</style> ';
            }
        }
    }

}
add_action('admin_head', 'gh_author_hide_customize_submenu');