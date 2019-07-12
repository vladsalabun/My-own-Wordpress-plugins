<?php

add_action( 'admin_menu', 'freeze_activity_page' );

// 
function freeze_activity_page(){
	add_menu_page( 
        'Фріз активності', 
        'Лог активності', 
        'edit_others_posts', 
        'freeze_activity', 
        'freeze_activity_function', 
        '',
        100 
    ); 
}

function freeze_activity_function() {
    echo 1111;
}





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
	echo '<h2>'. get_admin_page_title() .'</h2>';

}