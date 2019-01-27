<?php
/*
    Plugin Name: LM Hotkey 
    Description: Гарячі клавіші для управління блогом
    Version: 1.0
    Author: Vlad Salabun
    Author URI: http://salabun.com/
*/


    // Підключення хоткеїв для фронт-енду:
    add_action( 'wp_enqueue_scripts', 'lm_hotkey_scripts_and_styles' );
    
    // Підключення скриптів у шапку сайта:
    function lm_hotkey_scripts_and_styles() {
        wp_enqueue_script( 'js_keymaster', plugin_dir_url( __FILE__ ) . 'js_keymaster.js');
        wp_enqueue_style( 'css_hotkey', plugin_dir_url( __FILE__ ) . 'css_hotkey.css' );
    }

    
    // Підключення скриптів у футер:
    add_action( 'wp_print_footer_scripts', 'hotkey_lm' );
     
    function hotkey_lm(){
        
?> 
<script>

    /* 
        TODO: 
        1) перевір чи існує посилання
        2) Функція переходу по навігації має спрацьовувати тільки 1 раз, в JS ф-я debounce дозволяє управляти запуском функцій
    */

    // Змінна, яка прийме значення true, коли буде натиснута керуюча клавіша:
    var oneKey = false;
    
    // Новий пост:
    key('n', function(){
        if(!oneKey) {
            document.location.href = '<?php echo get_site_url();?>/wp-admin/post-new.php'; 
            return false 
            oneKey = true;
        }
    });
    // Рандомний пост:
    key('r', function(){ 
        document.location.href = '<?php echo get_site_url();?>/random'; 
        return false 
    });
    
    key('right', function(){ 
        if(!oneKey) {
            // наступний пост:
            document.location.href = document.getElementsByClassName('nextpostslink')[0].href;
            oneKey = true;
        }
    });  
    
    key('left', function(){ 
        if(!oneKey) {
            // попередній пост:
            document.location.href = document.getElementsByClassName('previouspostslink')[0].href;
            oneKey = true;
        }
    });     
    key('enter', function(){ 
        // попередній пост:
        document.location.href = document.getElementsByClassName('title_link')[0].href;
    });  
    // Вхід в адмінку:
    key('l', function(){ 
        document.location.href = '<?php echo get_site_url();?>/wp-admin'; 
        return false 
    });
    // Головна сторінка:
    key('i', function(){ 
        document.location.href = '<?php echo get_site_url();?>'; 
        return false 
    });
   
    // Редагувати пост:
    key('e', function(){ 
        document.location.href = document.getElementsByClassName('editPostHiddenLink')[0].href;
        return false 
    });

    // Рандомний пост з 1 категорії:
    key('m', function(){ 
        document.location.href = document.getElementsByClassName('getOneMore')[0].href;
        return false 
    }); 

    //debounce(key, 1000);
    // id: #sample-permalink
    
</script>
    <?php edit_post_link( $link, $before, $after, $id, 'editPostHiddenLink' ); ?>
<?php
    }
    
    # Підключення хоткеїв для адмінки:
    add_action('admin_enqueue_scripts', 'load_admin_hotkey_js');
    
    function load_admin_hotkey_js() {
        wp_enqueue_script('js_keymaster', plugin_dir_url( __FILE__ ) . 'js_keymaster.js');
        
        //Перестає працювати js в адмінці:
        //wp_enqueue_script('jQuery_v1.12.4', get_template_directory_uri().'/js/jQuery_v1.12.4.js');
    } 
    
    
    // Підключення скриптів у футер:
    add_action( 'admin_print_footer_scripts', 'admin_hotkey_lm' );
     
    function admin_hotkey_lm(){
        
?> 
<script>
     
    // Новий пост. Хоткей не працює, коли курсор знаходиться в інпуті:
    key('n', function(){ 
        document.location.href = '<?php echo get_site_url();?>/wp-admin/post-new.php'; 
        return false 
    });
    
    // Хоткеї в адмінці головна сторінка:
    key('i', function(){ 
        document.location.href = '<?php echo get_site_url();?>'; 
        return false 
    });
    
    // Опублікувати пост:
    key('enter', function(){ 
        $('#publish').click();
        return false 
    });    

    // Переглянути пост:
    key('right', function(){ 
        document.location.href = document.getElementById('sample-permalink').href; 
        return false 
    });
    
    // Видалити пост:
    key('del', function(){ 
        document.location.href = document.getElementsByClassName('submitdelete')[0].href; 
        return false 
    });    
   

    // Скопіювати заголовок у контент:
    key('alt+down', function(){ 
        document.location.href = '<?php echo get_site_url();?>/random'; 
        return false 
    });   
 /*   
    key.filter = function(event){
      var tagName = (event.target || event.srcElement).tagName;
        key.setScope(/^(INPUT|TEXTAREA|textarea|SELECT|iframe|IFRAME)$/.test(tagName) ? 'title' : 'oo');
      return true;
    }
*/
 
   
    
</script>
<?php
    }