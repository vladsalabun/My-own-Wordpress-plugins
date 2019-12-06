<?php
/*
    Plugin Name: Швидкий кеш HTML елементів
    Plugin URI: https://salabun.com
    Description: Швидкий кеш HTML елементів
    Author: Vlad Salabun
    Version: 1.0
    Author URI: https://salabun.com
*/

    # Інструкція по налаштуванню:
    // таблиця vlad_cache
    // 
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    function getVladCache($name) {
        
        global $wpdb;
        
        $expire = 12 * 3600;
        
        $html = $wpdb->get_row( "SELECT * FROM vlad_cache WHERE name = '".$name."'" );
        
        $delta = time() - $html->last_update;
        
        if($delta > $expire) {
            return false;
        } else {
            return $html->html;
        }

    }
    
    function addVladCache($name, $value) {
        
        global $wpdb;
        
        $expire = 12 * 3600;
        
        $wpdb->query( "DELETE FROM vlad_cache 
            WHERE name = '".$name."'
        ");
      
        $wpdb->insert(
            'vlad_cache', 
            array(
                'html' => $value,
                'last_update' => time() + $expire,
                'name' => $name
            ), 
            array( '%s', '%d', '%s' )
        );

    }
    