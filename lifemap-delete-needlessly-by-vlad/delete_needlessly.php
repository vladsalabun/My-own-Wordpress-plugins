<?php
/*
    Plugin Name: LM Needlessly
    Description: Видаляємо зайві посилання: "Спасибо вам за творчество с вордпрес", Удаление версии WP из футера админки, Удаление сообщений "Спасибо, что выбрали WordPress"
    Version: 1.1
    Author: Vlad Salabun
    Author URI: http://salabun.com/
*/

    # Удаляет "Спасибо вам за творчество с вордпрес"
    add_filter('admin_footer_text', 'wph_admin_footer_text');
    
    function wph_admin_footer_text () {
       echo '';
    }
    
    # Удаление версии WP из футера админки
    add_filter ('update_footer', 'kill_footer_version', 999);
    
    function kill_footer_version ($default) {
        return '';
    }

    # Удаление сообщений "Спасибо, что выбрали WordPress"
    add_filter ('admin_footer_text', 'kill_footer_filter');
     
    function kill_footer_filter ($default) {
        return '';
    }

    # Видаленна атрибутів ширини і висоти для зображень:
    add_filter( 'post_thumbnail_html', 'remove_width_attribute', 10 );
    add_filter( 'image_send_to_editor', 'remove_width_attribute', 10 );
    
    function remove_width_attribute( $html ) {
        $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
        return $html;
    }
    
    /**
     * Выводит данные о кол-ве запросов к БД, время выполнения скрипта и размер затраченной памяти.
     *
     * @param boolean [$visible = true] Выводить как есть или спрятать в HTML комментарий, чтобы данные
     *                                   не было видно в браузере, но их можно было посмотреть в HTML коде.
     * Функцию performance() нужно использовать в конце страницы. 
     * Чтобы автоматически добавить вывод этих данных, предлагаю воспользоваться хуками:
     */
    add_action( 'admin_footer_text', 'php_performance' ); // в подвале админки
    add_action( 'wp_footer', 'php_performance' );         // в подвале сайта
    function php_performance(){
        echo sprintf(
            __( 'SQL: %d за %s сек. %s MB', 'km' ),
            get_num_queries(),
            timer_stop( 0, 3 ),
            round( memory_get_peak_usage()/1024/1024, 2 )
        );
    }
    
    
/**
 *  Приховуємо версію ВП:
 */ 
function true_remove_wp_version_wp_head_feed() {
	return '';
}
 
add_filter('the_generator', 'true_remove_wp_version_wp_head_feed');