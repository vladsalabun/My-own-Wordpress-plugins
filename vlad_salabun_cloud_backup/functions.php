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

    $time = -microtime(true);
    
    $location = $_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/';
    
    $file_name = 'sql_' . date('Y.m.d_H-i-s') . '.sql.gz';
    $file = $location . $file_name;
    
    try {
        $dump = new MySQLDump(new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME));
        $dump->save($file);
    } catch (Exception $e) {
        return 'Mysql error.';
    }
    
    $time += microtime(true);
    
    return array(
        'time' => round($time,2),
        'path_to_file' => $file,
        'file_name' => $file_name,
        'location' => $location,
        'size' => filesize($file),
    );
    
}

/**
 *  Створити архів файлів за 1 рів:
 */
function v_make_one_year_zip($year = null)
{

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
 




















$outputFilename = 'backUP_outputFilename.zip';
    
    
    
    
    
    
/*
// create new archive
$zipFile = new \PhpZip\ZipFile();
try{
    $zipFile
        ->addDirRecursive(get_wp_installation_path(), "") // add files from the directory
        ->saveAsFile($outputFilename) // save the archive to a file
        ->close(); // close archive
}
catch(\PhpZip\Exception\ZipException $e){
    // handle exception
}
finally{
    $zipFile->close();
}
*/






