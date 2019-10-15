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
        if ( is_single() or in_the_loop() or is_page('multicat') ) {
            
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
                    
/*
                    $tmpString = '<a href="'.$value->src.'" data-toggle="modal" data-target="#modal_image_'.$id.'"><img src="'.$value->src.'" class="zz"></a>
                    <!-- Modal -->
<div class="modal fade" id="modal_image_'.$id.'" tabindex="-1" role="dialog" aria-labelledby="modal_image_'.$id.'_Title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="'.$value->src.'">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
                    ';
*/
                    // Заміняю:
                    $html = str_replace($value->outertext,$tmpString, $html);

                }
            }

        }
        
        return $html;

    }