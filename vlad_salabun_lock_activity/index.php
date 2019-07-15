<?php
/*
    Plugin Name: Activity Freezer
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
    require_once 'freeze_activity_admin_page.php';
    
    // Шифрування:
    require_once 'v_vigenere.php';

    
    
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
        option_value varchar(255) NULL,
        PRIMARY KEY  (id),
        KEY id (id)
        )
        {$charset_collate};";

        dbDelta($sql);
       
        // Записую дефолтні налаштування:
        $config_values = array(
            'max_time_without_activity' => 1800,
            'unlock_activity_password' => '1111',
            'max_unlock_password_try' => 5,
            'max_log_lines_count' => 1000,
        );
        
        foreach ($config_values as $config_name => $config_value) {
            // вставити, якщо нема:
            if(get_freeze_activity_config($config_name) == false) {
                $wpdb->insert($freeze_activity_config_table, array(
                    'option_name' => $config_name,
                    'option_value' => $config_value,
                ));
            }
        }
        
        $sql = "CREATE TABLE {$freeze_activity_log_table} (
        id  bigint(20) unsigned NOT NULL auto_increment,
        user_id int NOT NULL default 0,
        time int NOT NULL default 0,
        ip varchar(255) NOT NULL default '',
        password varchar(18) NOT NULL default '',
        frozen_cookie varchar(255) NULL,
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
    
    if(count($myrows) > 0) {
        return $myrows[0]->option_value;
    } else {
        return false;
    }
}




/**
 *  Хук при завантаженні системи:
 */
add_action( 'wp_loaded', 'hook_log_user_activity' );

function hook_log_user_activity() {

    // Якщо відправлений запит Ajax, то в цей момент не блокую доступ%
    if (defined('DOING_AJAX') && DOING_AJAX) {
        //
    } else {
        // У всіх інших випадках - доступ блокую:
    
        // Якщо користувач не увійшов:
        if ( is_user_logged_in() ) {
            
            // Якщо немає куків, логаут:
            if(!isset($_COOKIE['frozen_cookie'])) {
                wp_logout();
                die();
            }
            
            // Дізнаюсь чи є останній лог:
            $last_activity_time = get_last_user_activity();
            
            // Якщо активність була
            if($last_activity_time != false) {
                
                // Дізнаюсь чи не надто давно:
                $max_time_without_activity = get_freeze_activity_config('max_time_without_activity');
                
                $delay = current_time('timestamp') - $last_activity_time;
                
                if($max_time_without_activity != null) {
                    
                    // Якщо надто довго:
                    if($delay > $max_time_without_activity) {
                        
                        // Кількість спроб з даного пристрою:
                        $frozen_tries = get_frozen_tries();
                        
                        // Пропоную ввести пароль:
                        require_once 'freeze_activity_front_lock_page.php';
                        die();
                        
                    } else {
                        
                        // Вивожу в футер приховані поля зі значеннями
                        add_filter( 'wp_footer', 'freeze_param_to_footer', 10, 3 );
                        
                        // Записати новий лог:
                        log_activity_now();
                    }
                    
                }
                
            } else {
                // Якщо раніше не було логів - записую новий:
                log_activity_now();
            }
            
            // кінець програми
            
        }
    
    }
    
}








/**
 *  Записати новий лог:
 */

function log_activity_now() {
    
    global $wpdb;
    global $freeze_activity_config_table;
    global $freeze_activity_log_table;
    
    $cookie = $_COOKIE['frozen_cookie'];

    if($cookie == null) {
        setcookie(
            "frozen_cookie", 
            freeze_generateRandomString(), 
            current_time('timestamp') + 31536000, 
            '/'
        ); 
        $cookie = $_COOKIE['frozen_cookie'];
    }

    $freeze_activity_config_table = $wpdb->get_blog_prefix().'freeze_activity_config';
    $freeze_activity_log_table = $wpdb->get_blog_prefix().'freeze_activity_log';
    
    $user_id = get_current_user_id();
    
    $wpdb->insert($freeze_activity_log_table, array(
       "user_id" => $user_id,
       "time" => current_time('timestamp'),
       "ip" => $_SERVER['REMOTE_ADDR'],
       "password" => 'log',
       "frozen_cookie" => $cookie,
    ));
    
    // Самоочищення після X строк:
    $myrows = $wpdb->get_results("SELECT * FROM $freeze_activity_log_table");
    $last_id = $myrows[(count($myrows) - 1)]->id; 
    
    $to_del = $last_id - get_freeze_activity_config('max_log_lines_count');
    
    $wpdb->query("DELETE FROM $freeze_activity_log_table 
    WHERE id < '".$to_del."' AND user_id = $user_id AND password = 'log'");

}

/**
 *  Дізнаюсь останню активність:
 */

function get_last_user_activity() {
    
    // Якщо користувач увійшов:
    if ( is_user_logged_in() ) {
    
        global $wpdb;
        global $freeze_activity_config_table;
        global $freeze_activity_log_table;
        
        $freeze_activity_config_table = $wpdb->get_blog_prefix().'freeze_activity_config';
        $freeze_activity_log_table = $wpdb->get_blog_prefix().'freeze_activity_log';
        
        $user_id = get_current_user_id();
        
        $myrows = $wpdb->get_results("SELECT * FROM $freeze_activity_log_table 
        WHERE frozen_cookie = '".$_COOKIE['frozen_cookie']."' AND user_id = $user_id AND password = 'log' ORDER BY id DESC LIMIT 1");
        
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
 *  Дізнаюсь кількість спроб введення пароля:
 */

function get_frozen_tries() {
    
    // Якщо користувач увійшов:
    if ( is_user_logged_in() ) {
    
        global $wpdb;
        global $freeze_activity_config_table;
        global $freeze_activity_log_table;
        
        $freeze_activity_config_table = $wpdb->get_blog_prefix().'freeze_activity_config';
        $freeze_activity_log_table = $wpdb->get_blog_prefix().'freeze_activity_log';
        
        $user_id = get_current_user_id();
        $time = current_time('timestamp') - get_freeze_activity_config('max_time_without_activity');
        
        $myrows = $wpdb->get_results("SELECT * FROM $freeze_activity_log_table 
        WHERE frozen_cookie = '".$_COOKIE['frozen_cookie']."' 
        AND user_id = $user_id 
        AND password != 'log'
        AND time >= $time      
        ORDER BY id DESC");
        
        return count($myrows);
        
    }
    
    return false;
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
    
    $wpdb->query("DELETE FROM $freeze_activity_log_table 
    WHERE frozen_cookie = '".$_COOKIE['frozen_cookie']."'
    AND user_id = $user_id");
    
    // Встановлюю frozen_cookie:
    setcookie(
        "frozen_cookie", 
        freeze_generateRandomString(), 
        current_time('timestamp') + 31536000, 
        '/'
    ); 

}


/** 
 *  Перевірка введеного пароля:
 */
function freezeHandler() {
    
    global $wpdb;
    global $freeze_activity_config_table;
    global $freeze_activity_log_table;

    $freeze_activity_config_table = $wpdb->get_blog_prefix().'freeze_activity_config';
    $freeze_activity_log_table = $wpdb->get_blog_prefix().'freeze_activity_log';
    
    $user_id = get_current_user_id();
    $cookie = $_COOKIE['frozen_cookie'];
    
    // Якщо введений пароль співпадає з налаштуваннями:
    if($_POST['password'] == get_freeze_activity_config('unlock_activity_password')) {
        
        // Дозволяю перезавантажити сторінку:
        echo 'access granted';
        
        // Видаляю логи поведінки і логи спроб введення пароля:
        $wpdb->query("DELETE FROM $freeze_activity_log_table 
        WHERE frozen_cookie = '".$_COOKIE['frozen_cookie']."'
        AND user_id = $user_id");
        
        // Записую активність одразу:
        log_activity_now();
        
        // TODO: сповістити в телеграмі і на почту.
        
    } else {

        $user_id = get_current_user_id();
        
        // Записую лог:
        $wpdb->insert($freeze_activity_log_table, array(
           "user_id" => $user_id,
           "time" => current_time('timestamp'),
           "ip" => $_SERVER['REMOTE_ADDR'],
           "password" => $_POST['password'],
           "frozen_cookie" => $_COOKIE['frozen_cookie'],
        ));
        
        // Дізнаюсь кількість спроб з даного пристрою:
        $tries = get_frozen_tries();

        // Якщо кількість спроб надто велика:
        if($tries > get_freeze_activity_config('max_unlock_password_try')) {
            
            // Вийди з системи:
            echo 'access denied';
            setcookie("frozen_cookie", '', 0); 
            wp_logout();
            
            // TODO: сповістити в телеграмі
            
        } else {
            // Повертаю кількість спроб:
            echo $tries;
        }
    }
    
    die();
    
}
add_action('wp_ajax_freeze_handler', 'freezeHandler'); 


/**
 *  Час заморозки для даного пристрою:
 */
function nextFreezeTime() {
    
    // Дізнаюсь останню активність пристрою у системі:
    $last_user_activity = get_last_user_activity();
            
    // Якщо активність була
    if($last_activity_time != false) {
        
        return $last_user_activity;
        
    } else {
        // Якщо активнсті взагалі ще не було: як таке може бути?
        return -1;
    }
}

add_action('wp_ajax_get_next_freeze_time', 'nextFreezeTime'); 

/**
 *  Вивести в футер приховані поля зі значеннями
 */
function freeze_param_to_footer() {

    echo '<span id="max_time_without_activity" style="display: none; ">
    ' . get_freeze_activity_config('max_time_without_activity').'
    </span>';
    echo '<span id="freeze_current_time" style="display: none; ">' . current_time('timestamp') . '</span></div>';
    
    // Таймер, і якщо час прийшов - введення паролю
?>

<script>

    // Дізнаюсь параметри фріза:
    var max_time_without_activity = parseInt(document.getElementById('max_time_without_activity').innerText);
    var freeze_current_time = parseInt(document.getElementById('freeze_current_time').innerText);
    
    // Дізнаюсь коли слід заблокувати доступ +3 секунди:
    var freeze_activation = freeze_current_time + max_time_without_activity + 3;
    
    // Запускаю таймер:
    function freeze_activation_func() {
        
        // Збільшую поточний час на 1 сек:
        freeze_current_time = freeze_current_time + 1;
        //console.log(freeze_current_time);
        
        // Перезавантажую сторінку:
        if(freeze_current_time > freeze_activation) {
            // Що може статись, якщо перезавантажувати сторінку раз в 30 хвилин?
            // Цю сторінку залишили без активності на 30 хвилин
            document.location.reload(true);
        }
        
        // Кожну секунду:
        setTimeout(function() {
            freeze_activation_func();
        }, 1000); 
    }
    
    // Запуск блокувальника:
    freeze_activation_func(); 
    
</script>


<?php  
    
}

// Додаю код в футер адмінки:
// TODO: В адмінці не так вже й багато даних. А проблем з втратою і дублюванням даних може бути багато. 
// Тому відключено. При переході - все одно відключиться. Втрати мінімальні.
// add_filter( 'admin_footer', 'freeze_param_to_footer' );
 

/**
 *  Оновлення налаштувань:
 */
add_action( 'admin_post_update_freeze_options',    'update_freeze_options' );
function update_freeze_options() {
    
    global $wpdb;
    global $freeze_activity_config_table;
    global $freeze_activity_log_table;

    $freeze_activity_config_table = $wpdb->get_blog_prefix().'freeze_activity_config';
    $freeze_activity_log_table = $wpdb->get_blog_prefix().'freeze_activity_log';
    
    $old_password = get_freeze_activity_config('unlock_activity_password');
    
    // Якщо введено пароль:
    if(isset($_POST['password']) and $old_password == $_POST['password']) {
        
        // Оновлюю конфіги:
        $wpdb->query("UPDATE $freeze_activity_config_table 
            SET option_value = '".$_POST['max_time_without_activity']."'
            WHERE option_name = 'max_time_without_activity'");
        
        $wpdb->query("UPDATE $freeze_activity_config_table 
            SET option_value = '".$_POST['max_unlock_password_try']."'
            WHERE option_name = 'max_unlock_password_try'");
        
        $wpdb->query("UPDATE $freeze_activity_config_table 
            SET option_value = '".$_POST['max_log_lines_count']."'
            WHERE option_name = 'max_log_lines_count'");
        
        
        // Якщо вказано новий пароль і паролі співпадають:
        if(strlen($_POST['password1']) > 0 and $_POST['password1'] == $_POST['password2']) {
            // оновлюю пароль:
            $wpdb->query("UPDATE $freeze_activity_config_table 
                SET option_value = '".$_POST['password1']."'
                WHERE option_name = 'unlock_activity_password'");
            
        }
    }
    
    exit( wp_redirect( admin_url( 'admin.php?page=freeze_activity_config' ) ) );
}


 