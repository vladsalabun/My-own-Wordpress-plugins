<?php
/*
    Plugin Name: LM Post Submitbox
    Description: Плагін дозволяє змінити текст у віджеті вище кнопки публікації
    Version: 1.0
    Author: Vlad Salabun
    Author URI: http://salabun.com/
*/

    // Приховую стандартні надписи у віджеті:
	add_action('admin_head', 'wpseNoVisibility');
    
    function wpseNoVisibility() {
?>
        <style>
            .misc-pub-post-status,.misc-pub-visibility,.misc-pub-curtime,.misc-pub-revisions{
                display:none !important;
            }
            #save-post,#post-preview,#minor-publishing-actions {
                display:none !important;
            }

        </style>';
<?php    
    }
    
    // Додаю свійт текст у пост-метабокс:
    add_action( 'post_submitbox_misc_actions', 'action_function_name_3962' );
    function action_function_name_3962( $post ){
        // action...
        echo '<div class="misc-pub-section">      
<b>I</b> - на сайт<br>
<b>N</b> - нова публікація<br>
<b>Enter</b> - опублікувати<br>
<b>Right</b> - переглянути публікацію<br>
<b>Del</b> - видалити публікацію<br>
        
        </div>';
    }


/*
    // Текст перед кнопкою "посмотреть":
    add_action( 'post_submitbox_minor_actions', 'action_function_name_6381' );
    function action_function_name_6381( $post ){
        echo '1111';
    }
*/

/*    
    // Текст перед самою кнопкою "опубликовать":
    add_action( 'post_submitbox_start', 'action_function_name_11' );
    function action_function_name_11() {
        echo "<p>Текст перед кнопкой. Вместо этого текста мы можем, например,
        добавить дополнительную кнопку, по нажатию на которую 
        как-то по особенному обрабатывать публикацию.</p>";
    }
*/
