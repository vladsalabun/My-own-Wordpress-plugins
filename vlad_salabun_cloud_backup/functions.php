<?php


/**
 *  Дізнаюсь налаштування системи:
 */
function get_cloud_backup_config($option_name) {
    
    global $wpdb;
    global $cloud_backup_tables;
    
    $config_table = $wpdb->get_blog_prefix().$cloud_backup_tables['config'];
    
    $myrows = $wpdb->get_results("SELECT * FROM $config_table 
    WHERE option_name = '".$option_name."' LIMIT 1");
    
    if(count($myrows) > 0) {
        return $myrows[0]->option_value;
    } else {
        return false;
    }
    
}

/**
 *  Дізнаюсь налаштування хмари:
 */
function get_one_cloud_config($cloud_name) {
    
    global $wpdb;
    global $cloud_backup_tables;
    
    $clouds_table = $wpdb->get_blog_prefix().$cloud_backup_tables['clouds'];
    
    $myrows = $wpdb->get_results("SELECT * FROM $clouds_table 
    WHERE cloud_name = '".$cloud_name."' LIMIT 1");

    if(count($myrows) > 0) {
        return $myrows[0];
    } else {
        return false;
    }
    
}


