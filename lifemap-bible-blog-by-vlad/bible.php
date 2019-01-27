<?php
/*
Plugin Name: LM Bible
Plugin URI: http://salabun.com/
Description: Нумерація абзаців у постах.
Version: 1.1
Author: Salabun Vlad
Author URI: http://salabun.com/
*/

    use bibleLifeMap as bibleLifeMap;
    
    // Підключаю парсер:
    include "lib/simple_html_dom.php";

    // Підключення хоткеїв для фронт-енду:
    add_action( 'wp_enqueue_scripts', 'lm_bible_scripts_and_styles' );
    
    // Підключення скриптів у шапку сайта:
    function lm_bible_scripts_and_styles() {
        wp_enqueue_style( 'bible', plugin_dir_url( __FILE__ ) . 'bible.css' );
    }
    
    // Обробка контенту перед виведенням на екран:
    add_filter( 'the_content', 'filter_the_content_in_the_main_loop' );
 
    function filter_the_content_in_the_main_loop( $content ) {
     
        $tag = array(
            'h1' => 'outertext',
            'h2' => 'outertext',
            'h3' => 'outertext',
            'h4' => 'outertext',
            'h5' => 'outertext',
            'h6' => 'outertext',
            'p' => 'innertext',
            'ul' => 'outertext',
            'ol' => 'outertext',
            'div' => 'innertext',
            'table' => 'outertext',
            'pre' => 'outertext',
        );
        
        // Модифікую тільки в циклах і постах:
        if ( is_single() or in_the_loop() or is_page('multicat') ) {
            
            // Підключаю парсер:
            
            // Паршу:
            $html = bibleLifeMap\str_get_html($content);
            
            
            // Шукаю всі абзаци:
            $ret = $html->find(implode(array_keys($tag),','));
            
            // TODO: Ігнорувати вміст таблиць. Відображай як є
            
            // Якщо є хоч 1 абзац, то показую нумерацію абзаців:
            if(count($ret) > 1) {
                // Переглядаю всі теги:
                foreach ($ret as $id => $value) {
                    // Якщо тег не пустий:
                    if(strlen($value->innertext)) {
                        // Додаю Нумерацію:
                        $tmpString = '
                        <div class="container-fluid">
                            <div class="row bible">
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1 numbering">'.($id + 1).'</div>
                                <div class="col-sm-9 col-md-9 col-lg-9 col-xl-9 paragraph">'.$value->{$tag[$value->tag]}.'</div>
                            </div>
                        </div>';
                        // Заміняю:
                        $html = str_replace($value->outertext,$tmpString, $html);
                    } 
                }
            }
  
        }
        
        return $html;

    }