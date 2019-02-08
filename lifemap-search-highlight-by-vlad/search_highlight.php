<?php
/*
    Plugin Name: LM Search Hightlight
    Description: Підсвітка пошукових запитів
    Version: 1.1
    Author: Vlad Salabun
    Author URI: http://salabun.com/
*/

   ## Функция для подсветки слов поиска в WordPress
    add_filter('the_content', 'kama_search_backlight');
    add_filter('the_excerpt', 'kama_search_backlight');
    add_filter('the_title', 'kama_search_backlight');

    function kama_search_backlight( $text ){
        
        // ------------ Настройки -----------
        $styles = array('',
            'color: #000; background: #99ff66;',
            'color: #000; background: #ffcc66;',
            'color: #000; background: #99ccff;',
            'color: #000; background: #ff9999;',
            'color: #000; background: #FF7EFF;',
        );

        // только для страниц поиска...
        if ( ! is_search() or is_admin() ) {
            return $text;
        } else {
            $query_terms = get_query_var('search_terms');
            
            // Якщо передано порожній пошуковий запит, то повертаємо текст як є:
            if( empty($query_terms) ) {
                $query_terms = array(get_query_var('s'));
                return $text;
            }
            
            // Фікс, якщо передано порожній пошуковий запит, або пробіл, то повертаємо текст як є:
            $stringQuery = implode($query_terms,',');
            if( strlen($stringQuery) < 1 or $stringQuery == ' '  ) {
                return $text;
            }


            $n = 0;
            foreach( $query_terms as $term ){
                $n++;

                $term = preg_quote( $term, '/' );
                $text = preg_replace_callback( "/$term/iu", function($match) use ($styles,$n){
                    return '<span style="'. $styles[ $n ] .'">'. $match[0] .'</span>';
                }, $text );
            }
        }

        return $text;
    }