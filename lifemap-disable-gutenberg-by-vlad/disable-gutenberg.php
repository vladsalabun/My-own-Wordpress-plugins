<?php
/*
    Plugin Name: LM Delete Gutenberg
    Description: Плагін повертає класичний редактор замість Гутенберга
    Version: 1.0
    Author: Vlad Salabun
    Author URI: http://salabun.com/
*/

    ## Отключает новый редактор блоков Гутенберг.
    ## ver: 1.0
    add_filter( 'use_block_editor_for_post_type', '__return_false', 100 );

    // Move the Privacy Policy help notice back under the title field.
    add_action( 'admin_init', function(){
        remove_action( 'admin_notices', [ 'WP_Privacy_Policy_Content', 'notice' ] );
        add_action( 'edit_form_after_title', [ 'WP_Privacy_Policy_Content', 'notice' ] );
    } );
