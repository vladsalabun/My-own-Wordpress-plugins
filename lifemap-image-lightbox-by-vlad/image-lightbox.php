<?php
/*
Plugin Name: LM Image Lightbox
Plugin URI: http://salabun.com/
Description: Швидкий і легкий лайтбокс для зображень.
Version: 1.1
Author: Salabun Vlad
Author URI: http://salabun.com/
*/

    use lmImageLightBox as lmImageLightBox;
    
    // Підключаю парсер:
    include "lib/simple_html_dom.php";
    
    // Підключення стилів:
    add_action( 'wp_enqueue_scripts', 'lm_image_lightbox_scripts_and_styles' );
    
    // Підключення скриптів у шапку сайта:
    function lm_image_lightbox_scripts_and_styles() {
        wp_enqueue_style( 'image_lightbox', plugin_dir_url( __FILE__ ) . 'image_lightbox.css' );
        wp_enqueue_script( 'zooming', plugin_dir_url( __FILE__ ) . 'zooming.js' );
    }
  
    // Підключення скриптів у футер:
    add_action( 'wp_print_footer_scripts', 'lm_image_lightbox' );
     
    function lm_image_lightbox() {         
?> 
<script>
    const defaultZooming = new Zooming();
    const customZooming = new Zooming();
    const config = customZooming.config();

    document.addEventListener('DOMContentLoaded', function () {
        customZooming.listen('#lm_lightbox');
    })
    
</script>
<?php       
    }

  
  
    // Обробка контенту перед виведенням на екран:
    add_filter( 'the_content', 'filter_the_content_in_the_main_loop_378745' );
 
    function filter_the_content_in_the_main_loop_378745( $content ) {
     
        // Модифікую тільки в циклах і постах:
        if ( is_single() or in_the_loop() ) {
            
            // Паршу:
            $html = lmImageLightBox\str_get_html($content);
            
            // Шукаю всі зображення:
            $ret = $html->find('img');
            
           
            // Якщо є хоч 1 зображення:
            if(count($ret) > 0) {
                 
                // Переглядаю всі теги:
                foreach ($ret as $id => $value) {

                    // Обертаю зображення у посилання для лайтбокса:
                    $tmpString = '<a href="'.$value->src.'"><img id="lm_lightbox" src="'.$value->src.'" alt="journey_thumbnail" class="zz"></a>';
                    
                    // Заміняю:
                    $html = str_replace($value->outertext,$tmpString, $html);

                }
            }

        }
        
        return $html;

    }