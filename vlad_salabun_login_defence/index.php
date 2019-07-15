<?php
/*
    Plugin Name: Антибрутфорс
    Plugin URI: https://salabun.com
    Description: Захист сторінки входу. Логує спроби входу і блокує доступ після невірних 10 спроб.
    Author: Vlad Salabun
    Version: 1.0
    Author URI: https://salabun.com
*/
    
    # Налаштування:
    $login_defence_table = 'login_defence_log';
    $login_defence_max_log_count = 300;
    $login_defence_max_log_try = 10;
    $login_defence_max_delay = 1800; // секунд


    
    
/**
 *  При активації плагіна:
 */
register_activation_hook( __FILE__, 'create_login_defence_table' ); 

function create_login_defence_table() {
    
    global $wpdb;
    global $login_defence_table;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	$table_name = $wpdb->get_blog_prefix() . $login_defence_table;
    
    $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";

	$sql = "CREATE TABLE {$table_name} (
	id  bigint(20) unsigned NOT NULL auto_increment,
	ip varchar(255) NOT NULL default '',
	time int default 0,
	PRIMARY KEY  (id),
	KEY ip (ip)
	)
	{$charset_collate};";

	dbDelta($sql);
    
}



/**
 *  При вводі неправильного пароля записую лог: 
 */
add_action( 'wp_login_failed', 'wrong_login_and_password_handler' );

function wrong_login_and_password_handler( $username ){
	
    global $wpdb;
    global $login_defence_table;
    global $login_defence_max_log_count;
    
    $table_name = $wpdb->get_blog_prefix() . $login_defence_table;
    
    $wpdb->insert($table_name, array(
       "ip" => $_SERVER['REMOTE_ADDR'],
       "time" => time(),
    ));
    
    /*
    *   Самоочищення після X строк:
    */
    $myrows = $wpdb->get_results("SELECT * FROM $table_name");
    $last_id = $myrows[count($myrows) - 1]->id;
    
    $to_del = $last_id - $login_defence_max_log_count;
    
    $wpdb->query("DELETE  FROM $table_name WHERE id < '".$to_del."'");
    
}



/**
 *  Срабатывает после того, как пользователь успешно авторизован (залогинен, вошел на сайт).
 */
add_action( 'wp_login', 'action_function_name_9084', 10, 2 );

function action_function_name_9084( $user_login, $user ){
	// TODO: сповістити в телеграм і на почту
    
}




/**
 *  Хук при завантаженні системи:
 */
add_action( 'init', 'hook_antibrootforce' );

function hook_antibrootforce() {

    global $login_defence_max_log_try;

    // Якщо користувач не увійшов:
    if ( !is_user_logged_in() ) {
        
        // Скільки було спроб з цього ІР:
        $login_count = lm_getLastLoginCount($_SERVER['REMOTE_ADDR']);

        if($login_count > $login_defence_max_log_try) {
            // Відключаю сторінку логіну:
            add_filter( 'wp_login_errors', 'my_login_form_lock_down2', 90, 2 );
            // Зупиняю програму для цього ІР і не записую більше його спроби:
            require_once 'def_page.php';
            die();
        }
    }
}


/*
*   Відключена сторінка логіну:
*/
function my_login_form_lock_down2( $errors, $redirect_to ) {
    die();
}

/*
*   Кількість минулих логінів:
*/
function lm_getLastLoginCount( $ip ) {
    
    global $wpdb;
    global $login_defence_table;
    global $login_defence_max_delay;
    
    $table_name = $wpdb->get_blog_prefix() . $login_defence_table;
    $delay = time() - $login_defence_max_delay;
    
    $myrows = $wpdb->get_results("SELECT * FROM $table_name WHERE ip = '".$ip."' AND time > $delay");

    return count($myrows);
    
}
