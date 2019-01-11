<?php
/*
Plugin Name: LM Youtube Iframe
Plugin URI: http://salabun.com/
Description: Вирівнювання всіх відео з iframe під розмір 16:9.
Version: 1.1
Author: Salabun Vlad
Author URI: http://salabun.com/
*/

    use youtubeParsing as youtubeParsing;

    // Підключаю парсер:
    include "lib/simple_html_dom.php";
    
    // Підключення стилів:
    add_action( 'wp_enqueue_scripts', 'lm_youtube_scripts_and_styles' );
    
    // Підключення скриптів у шапку сайта:
    function lm_youtube_scripts_and_styles() {
        wp_enqueue_style( 'youtube', plugin_dir_url( __FILE__ ) . 'youtube.css' );
    }
    
    // Обробка контенту перед виведенням на екран:
    add_filter( 'the_content', 'filter_the_content_in_the_main_loop_3265454' );
 
    function filter_the_content_in_the_main_loop_3265454( $content ) {
     
        // Модифікую тільки в циклах і постах:
        if ( is_single() or in_the_loop() ) {
            
            // Паршу:
            $html = youtubeParsing\str_get_html($content);
            
            // Шукаю всі абзаци:
            $ret = $html->find('iframe');
            
            // Якщо є хоч 1 абзац, то показую нумерацію абзаців:
            if(count($ret) > 0) {
                 
                // Переглядаю всі теги:
                foreach ($ret as $id => $value) {

                    // Додаю bootstrap embed-responsive:
                    $tmpString = '<div class="embed-responsive embed-responsive-16by9 my-video">'.$value->outertext.'</div>';
                    // Заміняю:
                    $html = str_replace($value->outertext,$tmpString, $html);

                }
            }

        }
        
        return $html;

    }