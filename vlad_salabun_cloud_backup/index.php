<?php
/*
    Plugin Name: Автоматичні бекапи у хмару
    Plugin URI: https://salabun.com
    Description: Резервне копіювання блогу у хмарі.
    Author: Vlad Salabun
    Version: 1.0
    Author URI: https://salabun.com
*/
    
    require_once 'vendor/autoload.php';
    require_once 'mySQL_backup_tool.php';

    // Максимальное время выполнения скрипта:
    ini_set('max_execution_time', 3600); // в секундах
    
    // Максимальная память, доступная скрипту:
    ini_set('memory_limit','512M');
    
/**
 *      Налаштування:
 */
    // Щоб при активації плагіну ці змінні були глобальними, їх необхідно оголосити такими тут:
    global $cloud_backup_tables;
    global $current_plugin_prefix;
    
    $current_plugin_prefix = 'cloud_backup_';
    
    $cloud_backup_tables = array(
        'config' => $current_plugin_prefix.'config',
        'clouds' => $current_plugin_prefix.'clouds',
        'backups' => $current_plugin_prefix.'backups',
    );

    
/**
 *      Функції:
 */
    require_once 'functions.php';

  
    
/**
 *      При активації плагіна:
 */
    register_activation_hook( __FILE__, 'cloud_backup_activation' ); 

    function cloud_backup_activation() {
        
        // таблиці і налаштування
        global $wpdb;
        global $cloud_backup_tables;
 
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        $config_table = $wpdb->get_blog_prefix().$cloud_backup_tables['config'];
        $clouds_table = $wpdb->get_blog_prefix().$cloud_backup_tables['clouds'];
        $backups_table = $wpdb->get_blog_prefix().$cloud_backup_tables['backups'];
        
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";

        $sql = "CREATE TABLE {$config_table} (
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
            'download_password' => '1111',
        );
        
        foreach ($config_values as $config_name => $config_value) {
            // вставити, якщо нема:
            if(get_cloud_backup_config($config_name) == false) {
                $wpdb->insert($config_table, array(
                    'option_name' => $config_name,
                    'option_value' => $config_value,
                ));
            }
        }
        
        //
        $default_clouds = array(
            'cloud_mailru' => 
                array(
                    'token' => '',
                    'login' => '',
                    'password' => '',
                    'remote_disk_space' => 0,
                    'backup_frequency' => 3,
                    'simultaneously_stored_quantity' => 30,
                    'db_switch' => 1,
                    'files_switch' => 1,
                ),
            'yandex_disk' =>
                array(
                    'token' => '',
                    'login' => '',
                    'password' => '',
                    'remote_disk_space' => 0,
                    'backup_frequency' => 3,
                    'simultaneously_stored_quantity' => 30,
                    'db_switch' => 1,
                    'files_switch' => 1,
                ),
            'google_drive' =>
                array(
                    'token' => '',
                    'login' => '',
                    'password' => '',
                    'remote_disk_space' => 0,
                    'backup_frequency' => 3,
                    'simultaneously_stored_quantity' => 30,
                    'db_switch' => 1,
                    'files_switch' => 1,
                ),
        );
        
        
        $sql = "CREATE TABLE {$clouds_table} (
        id  bigint(20) unsigned NOT NULL auto_increment,
        cloud_name varchar(255) NOT NULL default '',
        token varchar(255) NULL ,
        login varchar(255) NULL ,
        password varchar(255) NULL ,
        remote_disk_space int(11) default '0',
        backup_frequency int(11) default '0',
        simultaneously_stored_quantity int(11) default '0',
        db_switch int(11) default '0',
        files_switch int(11) default '0',
        PRIMARY KEY  (id),
        KEY id (id)
        )
        {$charset_collate};";

        dbDelta($sql);
        
        
        foreach ($default_clouds as $cloud_name => $cloud_params) {
            // вставити, якщо нема:
            if(get_one_cloud_config($cloud_name) == false) {
                $wpdb->insert($clouds_table, array(
                    'cloud_name' => $cloud_name,
                    'token' => $cloud_params['token'],
                    'login' => $cloud_params['login'],
                    'password' => $cloud_params['password'],
                    'remote_disk_space' => $cloud_params['remote_disk_space'],
                    'backup_frequency' => $cloud_params['backup_frequency'],
                    'simultaneously_stored_quantity' => $cloud_params['simultaneously_stored_quantity'],
                    'db_switch' => $cloud_params['db_switch'],
                    'files_switch' => $cloud_params['files_switch'],
                ));
            }
        }
        
        $sql = "CREATE TABLE {$backups_table} (
        id  bigint(20) unsigned NOT NULL auto_increment,
        cloud_name varchar(255) NOT NULL default '',
        files_backup_size int(11) default '0',
        files_backup_time int(11) default '0',
        db_backup_size int(11) default '0',
        db_backup_time int(11) default '0',
        last_post_id int(11) default '0',
        backup_date int(11) default '0',
        deleted_time int(11) default '0',
        download_db_link varchar(255) NULL,
        download_files_link varchar(255) NULL,
        PRIMARY KEY  (id),
        KEY id (id)
        )
        {$charset_collate};";

        dbDelta($sql);
        
        
        
    }

    
    
/**
 *      При деактивації плагіна: 
 */    
    register_deactivation_hook( __FILE__, 'cloud_backup_deactivation_notify' );

    function cloud_backup_deactivation_notify() {
        // TODO: сповіщення
    }
    


/**
 *      Сторінки:
 */
    require_once 'admin_pages/pages.php';

    
/**
 *      Програма:
 */
    require_once 'mainController.php';










    /*
        TODO:
        Cloud Mail.ru, Yandex, Google Disk
        
        Токен зашифрований віженером?
        Чекер токена
        
        Пароль на скачування бекапів - захищений віженером.
        
        Таблиця з адресами на скачування бекапів.
        
        Видаляти старі бекапи, якщо їх більше, ніж 30 шт.
        Записувати обєм MB.
        
        Нагадування про бекап.
        
        wp-cron!
        
    */
    
    