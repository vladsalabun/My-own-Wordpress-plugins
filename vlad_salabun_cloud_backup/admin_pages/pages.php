<?php

add_action( 'admin_menu', 'cloud_backup_page' );

// 
function cloud_backup_page(){
	add_menu_page( 
        'Автобекапи', 
        'Автобекапи', 
        'edit_others_posts', 
        'cloud_backup', 
        'cloud_backup_function', 
        '',
        100 
    );  // cloud_backup
}

function cloud_backup_function() {
   
    global $wpdb;
    global $cloud_backup_tables;
    
    $config_table = $wpdb->get_blog_prefix().$cloud_backup_tables['config'];

    echo $config_table;
}


/*


// Добавим подменю в меню админ-панели "Инструменты" (tools):
add_action('admin_menu', 'freeze_activity_config_page');

function freeze_activity_config_page() {
	add_submenu_page( 
        'freeze_activity', 
        'Налаштування логу активності', 
        'Налаштування', 
        'manage_options', 
        'freeze_activity_config', 
        'freeze_activity_config_function'
    ); 
}

function freeze_activity_config_function() {
	// контент страницы
}
*/