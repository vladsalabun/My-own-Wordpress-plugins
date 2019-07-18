<?php

// Додаю свій інтервал виконання задачі:
add_filter( 'cron_schedules', 'my_own_delay'); 
 
function my_own_delay( $array ) {

    // Додаю у масив:
	$array['every_1_min'] = array(
		'interval' => 60, // інтервал в секундах
		'display' => 'Каждую минуту' // назва, як відображати?
	);
	return $array;
}
   

   
   
// Аргументи, які будуть передані в хук:   
$cloud_args = array(
    'some_arg',
);
 
// Якщо аргументи зміняться, то буде заплановано задачу:
if( !wp_next_scheduled('cloud_backup_hook', $cloud_args ) ) {
	wp_schedule_event( time(), 'hourly', 'cloud_backup_hook', $cloud_args );
}
 
// 
add_action( 'cloud_backup_hook', 'cloud_backup_cron_func', 10, 3 );
 
function cloud_backup_cron_func($some_arg) {
	
    global $wpdb;
    global $cloud_backup_tables;
    
    $clouds_table = $wpdb->get_blog_prefix().$cloud_backup_tables['clouds'];
    $backups_table = $wpdb->get_blog_prefix().$cloud_backup_tables['backups'];
   
    
    // Беру всі хмари:
    $myrows = $wpdb->get_results("SELECT * FROM $clouds_table");
    
    // ПРОГРАМА БЕКАПУВАННЯ:
    foreach ($myrows as $key => $cloud) {

        // Беру останній бекап:
        $myrows_backups = $wpdb->get_results("SELECT * FROM $backups_table
        WHERE deleted_time is NULL AND cloud_name = '".$cloud->cloud_name."' ORDER BY id DESC LIMIT 1");
        
        // Якщо раніше вже були бекапи:
        if(count($myrows_backups) > 0) {
            
            // Скільки часу пройшло з попереднього бекапу:
            $passed_time = current_time('timestamp') - $myrows_backups[0]->backup_date;

            // Якщо більше, ніж вказано у конфігах:
            if($passed_time > ($cloud->backup_frequency * 24 * 3600)) {
                
                // Якщо бекапів ще немає, то роблю їх:
                if(!isset($db_dump) and !isset($files_dump)) {
                    // Створюю бекап бази даних:
                    $db_dump = v_make_db_dump();
                    
                    // Копія файлів за цей рік:
                    $files_dump = v_make_one_year_zip();
                }
                
                // Завантажую бекап у хмару:
                if($cloud->cloud_name == 'cloud_mailru') {
                    
                    do_upload_backups_to_cloud_mail_ru($db_dump, $files_dump);
                    
                } else if($cloud->cloud_name == 'yandex_disk') {
                    
                    // TODO: do_upload_backups_to_yandex_disk($db_dump, $files_dump);
                    
                } else if($cloud->cloud_name == 'google_drive') {
                    
                    // TODO: do_upload_backups_to_google_drive($db_dump, $files_dump);
                    
                }
                
            }
            
        } else {
            
            if($cloud->db_switch == 1) {

                // Якщо бекапів ще немає, то роблю їх:
                if(!isset($db_dump) and !isset($files_dump)) {
                    // Створюю бекап бази даних:
                    $db_dump = v_make_db_dump();
                    
                    // Копія файлів за цей рік:
                    $files_dump = v_make_one_year_zip();
                }
                
                // Завантажую бекап у хмару:
                if($cloud->cloud_name == 'cloud_mailru') {
                    do_upload_backups_to_cloud_mail_ru($db_dump, $files_dump);
                }
                
            }
            
        }
        
    }
    // КІНЕЦЬ ПРОГРАМИ БЕКАПУВАННЯ
    
    if(isset($db_dump)) {
        // Якщо є бекап, то видаляю файл:
        unlink($db_dump['path_to_file']);
    }
    if(isset($files_dump)) {
        // Якщо є бекап, то видаляю файл:
        unlink($files_dump['path_to_file']);
    }

}
    
    