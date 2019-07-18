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

/**
 *  Дізнаюсь папку на диску, у якій встановлений WP:
 */
function get_wp_installation_path()
{
    $full_path = getcwd(); 
    $ar = explode("wp-", $full_path);
    return $ar[0];
}

/**
 *  Дізнаюсь в яку папку буде записаний наступний бекап:
 */
function next_backup_cloud_folder()
{
    $site_url = get_site_url();

    $project_name = str_replace('https://','', $site_url);
    $project_name = str_replace('http://','', $project_name);

    return $project_name.'/backups/'.date('Y-m-d').'/';
}

/**
 *  Створити дамп бази даних:
 */
function v_make_db_dump()
{

    global $wpdb;
    global $cloud_backup_tables;
    
    $config_table = $wpdb->get_blog_prefix().$cloud_backup_tables['config'];
    
    $password = $wpdb->get_results("SELECT * FROM $config_table 
    WHERE option_name = 'download_password'");

    $time = -microtime(true);
    
    $location = $_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/';
    
    $backup_name = 'sql_' . date('Y.m.d_H-i-s', current_time('timestamp'));
    
    $file_name = $backup_name . '.sql.gz';
    $file = $location . $file_name;
    
    try {
        $dump = new MySQLDump(new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME));
        $dump->save($file);
        
            // Створюю архів:
            $zipFile = new \PhpZip\ZipFile();
            
            try{
                $zipFile
                    ->addFile($file, $file_name) // add an entry from the file
                    ->setPassword($password[0]->option_value) // set password for all entries
                    ->saveAsFile($location.$backup_name.'.zip')
                    ->close();
            }
            catch(\PhpZip\Exception\ZipException $e){
                // handle exception
            }
            finally{
                $zipFile->close();
            }
        

    } catch (Exception $e) {
        return 'Mysql error.';
    }
    
    $time += microtime(true);
    
    // Видаляю бекап без пароля:
    unlink($location.$file_name);
    
    return array(
        'time' => round($time,2),
        'path_to_file' => $location . $backup_name . '.zip',
        'file_name' => $backup_name.'.zip',
        'location' => $location,
        'size' => filesize($location . $backup_name . '.zip'),
    );
    
}

/**
 *  Створити архів файлів за 1 рів:
 */
function v_make_one_year_zip($year = null)
{
    global $wpdb;
    global $cloud_backup_tables;
    
    $config_table = $wpdb->get_blog_prefix().$cloud_backup_tables['config'];
    
    $password = $wpdb->get_results("SELECT * FROM $config_table 
    WHERE option_name = 'download_password'");
    
    if ($year == null) {
        $year = date('Y');
    };
    
    $time = -microtime(true);
    
    // Папка, яку архівуємо:
    $location = $_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/'.$year.'/';
    
    // Архів:
    $file_name = 'uploads_'.$year.'.zip';
    $location_to_zip = $_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/';

    // Створюю архів:
    $zipFile = new \PhpZip\ZipFile();
    
    try{
        $zipFile
            ->addDirRecursive($location, "")
            ->setPassword($password[0]->option_value) // set password for all entries
            ->saveAsFile($location_to_zip.$file_name)
            ->close();
    }
    catch(\PhpZip\Exception\ZipException $e){
        // handle exception
    }
    finally{
        $zipFile->close();
    }
    
    $time += microtime(true);
    
    return array(
        'time' => round($time,2),
        'path_to_file' => $location_to_zip.$file_name,
        'file_name' => $file_name,
        'location' => $location_to_zip,
        'size' => filesize($location_to_zip.$file_name),
    );
    
}
 

/**
 *  Завантаження бекапів у хмару майл ру:
 */
function do_upload_backups_to_cloud_mail_ru($db_dump, $files_dump) {
    
    global $wpdb;
    global $cloud_backup_tables;
    
    #                   #
    #   CLOUD.MAIL.RU   #
    #                   #
    
    // Дізнаюсь параметри хмари:
    $cloud_mailru_config = get_one_cloud_config('cloud_mailru');
    
    // Якщо активовано бекап бази даних:
    if($cloud_mailru_config->db_switch == 1) {

        // Підключаюсь до хмари:
        $m_cloud = m_cloud_ini($cloud_mailru_config->login, $cloud_mailru_config->password);

        // Завантажую файл:
        $cloud_file_location = m_cloud_upload_file($m_cloud,$db_dump['location'], $db_dump['file_name']);
        
        $link_to_download = m_cloud_share_file($m_cloud,$cloud_file_location);

        if($cloud_file_location != false) {
            // Якщо успіх:
            $cloud_mailru_db_backup_info = array(
               "cloud_name" => 'cloud_mailru',
               "db_backup_size" => $db_dump['size'],
               "db_backup_time" => $db_dump['time'],
               "backup_date" => current_time('timestamp'),
               "download_db_link" => $link_to_download,
            ); 
    
        } else {
            // TODO: дебаг, що робити, якщо не завантажилось?
        }
        
        // Якщо активовано бекап файлів:
        if($cloud_mailru_config->files_switch == 1) {

            // Завантажую файл:
            $cloud_file_location = m_cloud_upload_file($m_cloud,$files_dump['location'], $files_dump['file_name']);
            $link_to_download = m_cloud_share_file($m_cloud,$cloud_file_location);
        
            if($cloud_file_location != false) {
                
                // 
                $cloud_mailru_db_backup_info += array(
                   "files_backup_size" => $files_dump['size'],
                   "files_backup_time" => $files_dump['time'],
                   "last_post_id" => null,
                   "deleted_time" => null,
                   "download_files_link" => $link_to_download,
                );
            }
            
        } else {
            $cloud_mailru_db_backup_info += array(
               "files_backup_size" => 0,
               "files_backup_time" => 0,
               "last_post_id" => null,
               "deleted_time" => null,
               "download_files_link" => null,
            );
        }
        
        // Вставляю запис про бекап у БД:
        $wpdb->insert($wpdb->get_blog_prefix().$cloud_backup_tables['backups'], $cloud_mailru_db_backup_info);
        
    } // кінець бекапу бази даних 
    
    #                   #
    #   /CLOUD.MAIL.RU  #
    #                   #
    
}

/**
 *  Завантаження бекапів у хмару yandex_disk:
 */
function do_upload_backups_to_yandex_disk($db_dump, $files_dump) {
    
}

/**
 *  Завантаження бекапів у хмару google_drive:
 */
function do_upload_backups_to_google_drive($db_dump, $files_dump) {
    
}


/**
 *  Оновлення налаштувань:
 */
add_action( 'admin_post_update_cloud_backup_options', 'update_cloud_backup_options' );
function update_cloud_backup_options() {
    
    global $wpdb;
    global $cloud_backup_tables;
    
    $clouds_table = $wpdb->get_blog_prefix().$cloud_backup_tables['clouds'];

    //
    $wpdb->query("UPDATE $clouds_table 
    
            SET 
                token = '".$_POST['token']."',
                login = '".$_POST['login']."',
                password = '".$_POST['password']."',
                backup_frequency = '".$_POST['backup_frequency']."',
                simultaneously_stored_quantity = '".$_POST['simultaneously_stored_quantity']."',
                db_switch = '".$_POST['db_switch']."',
                files_switch = '".$_POST['files_switch']."'
                
            WHERE cloud_name = '".$_POST['cloud_name']."'");
    
    exit( wp_redirect( admin_url( 'admin.php?page=cloud_backup_config' ) ) );
}


/**
 *  Оновлення налаштувань:
 */
add_action( 'admin_post_update_cloud_backup_password', 'update_cloud_backup_password' );
function update_cloud_backup_password() {
    
    global $wpdb;
    global $cloud_backup_tables;
    
    $config_table = $wpdb->get_blog_prefix().$cloud_backup_tables['config'];

    //
    $wpdb->query("UPDATE $config_table 
        SET option_value = '".$_POST['password']."'
        WHERE option_name = 'download_password'");
    
    exit( wp_redirect( admin_url( 'admin.php?page=cloud_backup_config' ) ) );
}

