<?php
/*
Plugin Name: Фріз адмін
Plugin URI: https://salabun.com
Description: Заморозити адмінку у випадку бездіяльності.
Author: Vlad Salabun
Version: 1.0
Author URI: https://salabun.com
*/
    
    # Налаштування:
    $freeze_activity_config_table = 'freeze_activity_config';
    $freeze_activity_log_table = 'freeze_activity_log';
    
    // Сторінка налаштувань:
    require_once 'freeze_activity_page.php';
    

/**
 *  При деактивації плагіна:
 */    
register_deactivation_hook( __FILE__, 'freeze_activity_deactivation_notify' );

function freeze_activity_deactivation_notify() {
    // TODO: сповіщення
}
   
    
/**
 *  При активації плагіна:
 */
register_activation_hook( __FILE__, 'create_freeze_activity_table' ); 

function create_freeze_activity_table() {
    
    global $wpdb;
    global $freeze_activity_config_table;
    global $freeze_activity_log_table;
    
    $freeze_activity_config_table = $wpdb->get_blog_prefix().'freeze_activity_config';
    $freeze_activity_log_table = $wpdb->get_blog_prefix().'freeze_activity_log';
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    
    $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";

	$sql = "CREATE TABLE {$freeze_activity_config_table} (
	id  bigint(20) unsigned NOT NULL auto_increment,
	option_name varchar(255) NOT NULL default '',
	option_value varchar(255) NOT NULL default '',
    PRIMARY KEY  (id),
    KEY id (id)
	)
	{$charset_collate};";

	dbDelta($sql);
   
    // Записую дефолтні налаштування:
    $config_values = array(
        'max_time_without_activity' => 1800,
        'unlock_activity_password' => '',
        'max_unlock_password_try' => 5,
        'max_log_lines_count' => 1000,
    );
    
    foreach ($config_values as $config_name => $config_value) {
        $wpdb->insert($freeze_activity_config_table, array(
            'option_name' => $config_name,
            'option_value' => $config_value,
        ));
    }
    
	$sql = "CREATE TABLE {$freeze_activity_log_table} (
	id  bigint(20) unsigned NOT NULL auto_increment,
    user_id int NOT NULL default 0,
    time int NOT NULL default 0,
    ip varchar(255) NOT NULL default '',
	password varchar(18) NOT NULL default '',
	PRIMARY KEY  (id),
	KEY ip (ip)
	)
	{$charset_collate};";

	dbDelta($sql);
   
}



/**
 *  Дізнаюсь налаштування системи:
 */
function get_freeze_activity_config($option_name) {
    
    global $wpdb;
    global $freeze_activity_config_table;
    global $freeze_activity_log_table;
    
    $freeze_activity_config_table = $wpdb->get_blog_prefix().'freeze_activity_config';
    $freeze_activity_log_table = $wpdb->get_blog_prefix().'freeze_activity_log';
    
    $myrows = $wpdb->get_results("SELECT * FROM $freeze_activity_config_table 
    WHERE option_name = '".$option_name."' LIMIT 1");
    
    return $myrows[0]->option_value;
}




/**
 *  Хук при завантаженні системи:
 */
add_action( 'init', 'hook_log_user_activity' );

function hook_log_user_activity() {

    // Якщо користувач не увійшов:
    if ( is_user_logged_in() ) {
        
        // Дізнаюсь чи є останній лог:
        $last_activity_time = get_last_user_activity();
        
        // Якщо активність була
        if($last_activity_time != false) {
            
            // Дізнаюсь чи не надто давно:
            $max_time_without_activity = get_freeze_activity_config('max_time_without_activity');
            
            $delay = time() - $last_activity_time;
            
            // Якщо надто довго:
            if($delay > $max_time_without_activity) {
                // TODO: Пропоную ввести пароль:
                
                die('Введіть пароль. Активність: ' . $delay . ' / ' . $max_time_without_activity);
            } else {
                // Вивожу в футер приховані поля зі значеннями
                add_filter( 'wp_footer', 'freeze_param_to_header', 10, 3 );
                
                // Записати новий лог:
                log_activity_now();
            }
            
        } else {
            // Якщо раніше не було логів - записую новий:
            log_activity_now();
        }
        
        // кінець програми
        
    }
    
}

/**
 *  Вивести в футер приховані поля зі значеннями
 */
function freeze_param_to_header() {

    echo '<span id="freeze_freeze_activity_config" class="invisible">
    ' . get_freeze_activity_config('max_time_without_activity').'
    </span>';
    echo '<span id="time" class="invisible">' . time() . '</span></div>';
    
    // TODO: Ajax: таймер на 1800 секунд, і якщо час прийшов - видалити вміст і показати введення паролю
    
}








/**
 *  Записати новий лог:
 */

function log_activity_now() {
    
    global $wpdb;
    global $freeze_activity_config_table;
    global $freeze_activity_log_table;
    
    $freeze_activity_config_table = $wpdb->get_blog_prefix().'freeze_activity_config';
    $freeze_activity_log_table = $wpdb->get_blog_prefix().'freeze_activity_log';
    
    $user_id = get_current_user_id();
    
    $wpdb->insert($freeze_activity_log_table, array(
       "user_id" => $user_id,
       "time" => time(),
       "ip" => $_SERVER['REMOTE_ADDR'],
       "password" => 'log',
    ));
    
    /*
    *   Самоочищення після X строк:
    */
    $myrows = $wpdb->get_results("SELECT * FROM $freeze_activity_log_table");
    $last_id = $myrows[count($myrows) - 1]->id; 
    
    $to_del = $last_id - get_freeze_activity_config('max_log_lines_count');
    
    $wpdb->query("DELETE FROM $freeze_activity_log_table 
    WHERE id < '".$to_del."' AND user_id = $user_id AND password = 'log'");

}

/**
 *  Дізнаюсь останню активність:
 */

function get_last_user_activity() {
    
    // Якщо користувач не увійшов:
    if ( is_user_logged_in() ) {
    
        global $wpdb;
        global $freeze_activity_config_table;
        global $freeze_activity_log_table;
        
        $freeze_activity_config_table = $wpdb->get_blog_prefix().'freeze_activity_config';
        $freeze_activity_log_table = $wpdb->get_blog_prefix().'freeze_activity_log';
        
        $user_id = get_current_user_id();
        
        $myrows = $wpdb->get_results("SELECT * FROM $freeze_activity_log_table 
        WHERE ip = '".$_SERVER['REMOTE_ADDR']."' AND user_id = $user_id AND password = 'log' ORDER BY id DESC LIMIT 1");
        
        //  Дізнаюсь час останнього логу:
        if(count($myrows) > 0) {
            $myrows = $myrows[0]->time;
        } else {
            $myrows = null;
        }
        
        return $myrows;
        
    } else {
        return false;
    }

}



/**
 *  Срабатывает после того, как пользователь успешно авторизован (залогинен, вошел на сайт).
 */
add_action( 'wp_login', 'freeze_activity_clear_user_log', 10, 2 );

function freeze_activity_clear_user_log( $user_login, $user ) {
    
    global $wpdb;
    global $freeze_activity_config_table;
    global $freeze_activity_log_table;

    $freeze_activity_config_table = $wpdb->get_blog_prefix().'freeze_activity_config';
    $freeze_activity_log_table = $wpdb->get_blog_prefix().'freeze_activity_log';
    
    $user_id = $user->id;
    
    $wpdb->query("DELETE FROM $freeze_activity_log_table WHERE user_id = $user_id AND password = 'log'");
    
}

/**
 *  TODO: при деактивації плагіну - сповіщення
 */



   /*
        TODO: 
        Пароль, який зашифрований віженером.
        Логи невдалих авторизацій.
        Якщо 3 неправильні - логаут.
        
        Якщо логаут, то сповістити в телеграмі.
        Якщо успішне розблокування - сповістити в телеграмі і на почту.
    */