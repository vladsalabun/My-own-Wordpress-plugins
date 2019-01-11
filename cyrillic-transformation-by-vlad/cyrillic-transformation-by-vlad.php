<?php
/*
    Plugin Name: LM Filename Cyrillic Transformation
    Description: Цей плагін знімає головну біль при завантаженні файлів з кириличними іменами.

    Author: Władysław Sałabun
    Version: 1.0
    Author URI: http://salabun.com/
*/


    add_filter('sanitize_file_name', 'fileNameToLatin', 10, 3);
    
    function fileNameToLatin($name) {
        
        preg_match('%\.[^.\\\\/:*?"<>|\r\n]+$%i', $name, $matches);
        $ext = $matches[0];
        $base_name = sanitaze(basename($name, $ext));
        return $base_name . $ext;
        
    }
 
    function sanitaze($name) {

        $cyr = array(
                'а', 'б', 'в', 
                'г', 'д', 'е', 
                'ё', 'ж', 'з', 
                'и', 'й', 'к', 
                'л', 'м', 'н', 
                'о', 'п', 'р', 
                'с', 'т', 'у', 
                'ф', 'х', 'ц', 
                'ч', 'ш', 'щ', 
                'ъ', 'ы', 'ь', 
                'э', 'ю', 'я',
                'А', 'Б', 'В', 
                'Г', 'Д', 'Е', 
                'Ё', 'Ж', 'З', 
                'И', 'Й', 'К', 
                'Л', 'М', 'Н', 
                'О', 'П', 'Р', 
                'С', 'Т', 'У', 
                'Ф', 'Х', 'Ц', 
                'Ч', 'Ш', 'Щ', 
                'Ъ', 'Ы', 'Ь', 
                'Э', 'Ю', 'Я',
                'і', 'ї', 'ґ',
                'є', 'І', 'Ї', 
                'Ґ', 'Є',
            );

        $lat = array(
                'a', 'b', 'v', 
                'g', 'd', 'e', 
                'e', 'zh', 'z', 
                'i', 'j', 'k', 
                'l', 'm', 'n', 
                'o', 'p', 'r', 
                's', 't', 'u', 
                'f', 'h', 'ts', 
                'ch', 'sh', 'sch', 
                '', 'y', '', 
                'e', 'ju', 'ya',
                'a', 'b', 'v', 
                'g', 'd', 'e', 
                'e', 'zh', 'z', 
                'i', 'j', 'k', 
                'l', 'm', 'n', 
                'o', 'p', 'r', 
                's', 't', 'u', 
                'f', 'h', 'ts', 
                'ch', 'sh', 'sch', 
                '', 'y', '', 
                'e', 'ju', 'ya',
                'i', 'ji', 'g',
                'je', 'i', 'ji',
                'g', 'je'
            );

        $output = str_replace($cyr, $lat, $name);
        $output = preg_replace('%[^\-_a-zA-Z0-9]%i', '-', $output);

        return $output;
        
    }
