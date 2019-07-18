<?php 
 
	// https://github.com/Friday14/mailru-cloud-php

    function m_cloud_ini($cloud_login, $cloud_password) {
        
        $cloud_folder = next_backup_cloud_folder();

        // Пытаюсь подключится:
        try {
            $cloud = new \Friday14\Mailru\Cloud($cloud_login, $cloud_password, 'mail.ru');
            
            // Пытаюсь получить файлы из папки:
            try {
                $cloud->files('/'.$cloud_folder);
            } catch (Exception $e) {
                // Если папки нет, создаю ее:
                $cloud->createFolder('/' . $cloud_folder);
            }

            return $cloud;
            
        } catch (Exception $e) {
            // Если папки нет, создаю ее:
            echo 'Mail.ru connection failed. Check credentials, please.';
        }

        return false;

    }
/*
    function m_cloud_files_listing($cloud) {
        
        global $cloud_folder;
        
        // Пытаюсь получить файлы из папки:
        try {
            return $cloud->files('/'.$cloud_folder); 
        } catch (Exception $e) {
            // Если папки нет, создаю ее:
            $cloud->createFolder('/' . $cloud_folder);
        }
        return false;
    }
*/

    function m_cloud_upload_file($cloud,$path_to_file, $file_name) {

        // Дізнаюсь папку, в які слід завантажити бекап:
        $cloud_folder = next_backup_cloud_folder();
        $cloud_file_location = '/' . $cloud_folder . $file_name;
        
        // Создать файл:
        $file = new SplFileObject($path_to_file.$file_name);

        // Пытаюсь загрузить файл в облако:
        try {
            $cloud->upload($file, $cloud_file_location);

        } catch (Exception $e) {
            // Если не удалось загрузить файл:
            return false;
        }
      
        return $cloud_file_location;
        
    }
    
    
    
    
    
    
    
    
    function m_cloud_delete_file($cloud,$cloud_file_location) {
         // Удалить файл:
        try {
            return $cloud->delete($cloud_file_location);
        } catch (Exception $e) {
            // Если не Удалить файл:
            new_debug('Не удалось удалить файл в облаке.');
            return false;
        }
    }
    
    
    /** 
     *  Тут костиль для ссилок майл ру:
     */
    function m_cloud_share_file($cloud,$cloud_file_location) {

        // Сделать файл общедоступным и получить ссылку на файл
        try {
            $string = $cloud->getLink($cloud_file_location);
            //$string = str_replace('thumb.','',$string);
            //$string = str_replace('weblink/thumb/xw1','public',$string);
            $parts = explode('/',$string);
            $string = 'https://cloud.mail.ru/public/'.$parts[count($parts) - 2].'/'.$parts[count($parts) - 1];
            
            return $string;
        } catch (Exception $e) {
            // Если не расшарить файл:
            return false;
        }
         
    }
 
    function m_cloud_rename_file($cloud,$cloud_file_location,$new_name) {

        // Переименовать файл:
        try {
            return $cloud->rename($cloud_file_location, $new_name);
        } catch (Exception $e) {
            // 
            new_debug('Не удалось переименовать файл в облаке.');
            return false;
        }
 
    }
 
    
    
    