<?php
/*
    Plugin Name: LM Random post
    Description: Випадковий допис буде доступний за посиланням site.com/random. Якщо не працює, то слід зайти у налаштування -> постійні посилання і натиснути зберегти
    Version: 1.0
    Author: Vlad Salabun
    Author URI: http://salabun.com/
*/

    /* 
     * Випадковий допис буде доступний за посиланням site.com/random
     * Якщо не працює, то слід зайти у налаштування -> постійні посилання і натиснути зберегти
     */
    function random_rewrite_rule() {
        $GLOBALS['wp']->add_query_var('random');
        add_rewrite_rule('random/?$', 'index.php?random=random', 'top');
    }
    add_action('init', 'random_rewrite_rule');

    function template_redirect_to_random() {
        if( get_query_var('random') != 'random' )
            return;

        $random_post = get_posts('orderby=rand&numberposts=1');
        $random_post = array_shift( $random_post );
        $link = get_permalink( $random_post );

        wp_redirect( $link, 307 );

        exit;
    }
    add_action('template_redirect', 'template_redirect_to_random');