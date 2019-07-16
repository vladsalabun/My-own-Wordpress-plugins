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





// Добавим подменю в меню админ-панели "Инструменты" (tools):
add_action('admin_menu', 'cloud_backup_config_page');

function cloud_backup_config_page() {
	add_submenu_page( 
        'cloud_backup', 
        'Тестування хмари', 
        'Тестування хмари', 
        'manage_options', 
        'cloud_backup_testing', // url 
        'cloud_backup_test_function'
    ); 
}

function cloud_backup_test_function() {
	
    global $wpdb;
    global $cloud_backup_tables;
    
    echo '<h2>Testing:</h2>';

    // Створюю бекап бази даних:
    $db_dump = v_make_db_dump(); echo 'after: v_make_db_dump()<br>';
    
    // Копія файлів за цей рік:
    $files_dump = v_make_one_year_zip(); echo 'after: v_make_one_year_zip()<br>';

    #                   #
    #   CLOUD.MAIL.RU   #
    #                   #
    
    // Дізнаюсь параметри хмари:
    $cloud_mailru_config = get_one_cloud_config('cloud_mailru'); echo 'after: get_one_cloud_config()<br>';
    
    // Якщо активовано бекап бази даних:
    if($cloud_mailru_config->db_switch == 1) {

        // Підключаюсь до хмари:
        $m_cloud = m_cloud_ini($cloud_mailru_config->login, $cloud_mailru_config->password);

        // Завантажую файл:
        $cloud_file_location = m_cloud_upload_file($m_cloud,$db_dump['location'], $db_dump['file_name']);
        
        if($cloud_file_location != false) {
            // Якщо успіх:
            $cloud_mailru_db_backup_info = array(
               "cloud_name" => 'cloud_mailru',
               "db_backup_size" => $db_dump['size'],
               "db_backup_time" => $db_dump['time'],
               "backup_date" => current_time('timestamp'),
               "download_db_link" => $cloud_file_location,
            ); echo 'after: cloud_mailru_db_backup_info<br>';
    
        } else {
            // TODO: дебаг, що робити, якщо не завантажилось?
        }
        
        // TODO: Якщо активовано бекап файлів:
        if($cloud_mailru_config->files_switch == 1) {

            // Завантажую файл:
            $cloud_file_location = m_cloud_upload_file($m_cloud,$files_dump['location'], $files_dump['file_name']);
        
            if($cloud_file_location != false) {
                
                // 
                $cloud_mailru_db_backup_info += array(
                   "files_backup_size" => $files_dump['size'],
                   "files_backup_time" => $files_dump['time'],
                   "last_post_id" => null,
                   "deleted_time" => null,
                   "download_files_link" => $cloud_file_location,
                ); echo 'after: cloud_mailru_db_backup_info 2<br>';
            }
            
        } else {
            
        }
        
        echo '<pre>';
        var_dump($cloud_mailru_db_backup_info);
        echo '</pre>';
        
        // Вставляю запис про бекап у БД:
        $wpdb->insert($wpdb->get_blog_prefix().$cloud_backup_tables['backups'], $cloud_mailru_db_backup_info);
        echo 'after: Вставляю запис про бекап у БД<br>';
        
    } // кінець бекапу бази даних 
    
    #                   #
    #   /CLOUD.MAIL.RU  #
    #                   #
    
    
    
    
    // TODO: загрузка в Яндекс и Гугл - це на потім!
    echo 'after: TODO: загрузка в Яндекс и Гугл - це на потім!<br>';
    
    // Кінець програми:
    if(isset($db_dump)) {
        // Якщо є бекап бази даних, то видаляю файл:
        unlink($db_dump['path_to_file']);
        unlink($files_dump['path_to_file']);
        
        echo 'after: unlink<br>';
    }

}
