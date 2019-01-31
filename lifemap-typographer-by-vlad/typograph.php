<?php
/*
Plugin Name: LM Typographer
Plugin URI: http://salabun.com/
Description: Инструмент для форматирования текста с использованием норм, правил и специфики русского языка и экранной типографики.
Version: 1.1.5
Author: Salabun Vlad
Author URI: http://salabun.com/
*/


    // Хук при збереженні/оновленні поста:
    add_filter( 'wp_insert_post_data', 'action_function_name_85245', 10, 2 );
    
    function action_function_name_85245( $post, $postOriginal ) {
        
        // Підключаю типограф:
        require_once("EMT.php");
        
        // Створюю обєкт:
        $typograf = new EMTypograph();
        
        // Обробляю контект з налаштуваннями по замовчуванню:
        $post['post_content'] = 
            upFirstLetter(
                EMTypograph::fast_apply(
                    $post['post_content'],
                    array(
                        'Text.paragraphs' => 'off',
                        'OptAlign.all' => 'off',
                        'OptAlign.layout' => 'off',
                        'OptAlign.oa_oquote_extra' => 'off',
                        'OptAlign.oa_obracket_coma' => 'off',
                        'OptAlign.oa_oquote' => 'off',
                        'Abbr.nbsp_money_abbr' => 'off',
                        'Etc.split_number_to_triads' => 'off',
                    )
                )
            );
   
        // Для титула встановлюю особливі параметри:        
        $post['post_title'] = 
            upFirstLetter(
                html_entity_decode(
                    EMTypograph::fast_apply(
                        $post['post_title'],
                        array(
                            'Text.paragraphs' => 'off',
                            'OptAlign.oa_oquote' => 'off',
                            'OptAlign.oa_obracket_coma' => 'off',
                            'Quote.quotes' => 'off',
                            'Symbol.arrows_symbols' => 'off',
                            'Symbol.apostrophe' => 'off',
                        )
                    )
                )
            );
        
        return $post;

    }

    
    /**
     * Uppercase first letter. Working with multi-byte encodings.
     *
     * @param $str
     * @param string $encoding
     * @return string
     */
    function upFirstLetter($str, $encoding = 'UTF-8')
    {
        return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding)
        . mb_substr($str, 1, null, $encoding);
    }
     