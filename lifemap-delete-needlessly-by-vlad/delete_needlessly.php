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






/*
 *  Отключаем emoji
 *  
 *  https://sheensay.ru/?p=2044
 */
add_action( 'init', 'sheensay_disable_emojis' );
 
function sheensay_disable_emojis() {
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
 
  add_filter( 'tiny_mce_plugins', 'sheensay_disable_emojis_tinymce' );
  add_filter( 'wp_resource_hints', 'sheensay_disable_emojis_remove_dns_prefetch', 10, 2 );
}
 
function sheensay_disable_emojis_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  } else {
    return array();
  }
}
 
function sheensay_disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
  if ( 'dns-prefetch' == $relation_type ) {
    /** This filter is documented in wp-includes/formatting.php */
    $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2.2.1/svg/' );
 
    $urls = array_diff( $urls, array( $emoji_svg_url ) );
  }
 
  return $urls;
}



/*
 *  Удаляем остальные ненужные meta из head
 *
 * https://sheensay.ru/?p=2044
 */
// Удаляем код meta name="generator"
remove_action( 'wp_head', 'wp_generator' );
 
// Удаляем link rel="canonical" // Этот тег лучше выводить с помощью плагина Yoast SEO или All In One SEO Pack
remove_action( 'wp_head', 'rel_canonical' );
 
// Удаляем link rel="shortlink" - короткую ссылку на текущую страницу
remove_action( 'wp_head', 'wp_shortlink_wp_head' ); 
 
// Удаляем link rel="EditURI" type="application/rsd+xml" title="RSD"
// Используется для сервиса Really Simple Discovery 
remove_action( 'wp_head', 'rsd_link' ); 
 
// Удаляем link rel="wlwmanifest" type="application/wlwmanifest+xml"
// Используется Windows Live Writer
remove_action( 'wp_head', 'wlwmanifest_link' );
 
// Удаляем различные ссылки link rel
// на главную страницу
remove_action( 'wp_head', 'index_rel_link' ); 
// на первую запись
remove_action( 'wp_head', 'start_post_rel_link', 10 );  
// на предыдущую запись
remove_action( 'wp_head', 'parent_post_rel_link', 10 ); 
// на следующую запись
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10 );
 
// Удаляем связь с родительской записью
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 ); 
 
// Удаляем вывод /feed/
remove_action( 'wp_head', 'feed_links', 2 );
// Удаляем вывод /feed/ для записей, категорий, тегов и подобного
remove_action( 'wp_head', 'feed_links_extra', 3 );
 
// Удаляем ненужный css плагина WP-PageNavi
remove_action( 'wp_head', 'pagenavi_css' ); 




/**
 * Plugin Name: My Revisions Config
 */
function my_revisions_to_keep( $revisions ) {
    return 2;
}
add_filter( 'wp_revisions_to_keep', 'my_revisions_to_keep' );




/* The OS_Disable_WordPress_Updates class */
class OS_Disable_WordPress_Updates {
    private $__pluginsFiles;
    private $__themeFiles;

    function __construct() {
        $this->__pluginsFiles = array();
        $this->__themeFiles = array();

        add_action( 'admin_init', array(&$this, 'admin_init') );

        if( !function_exists( 'get_plugins' ) ) require_once ABSPATH . 'wp-admin/includes/plugin.php';

        if( count( get_plugins() ) > 0 ) foreach( get_plugins() as $file => $pl ) $this->__pluginsFiles[$file] = $pl['Version'];
        if( count( wp_get_themes() ) > 0 ) foreach( wp_get_themes() as $theme ) $this->__themeFiles[$theme->get_stylesheet()] = $theme->get('Version');

        add_filter( 'pre_transient_update_themes', array($this, 'last_checked_themes') );

        add_filter( 'pre_site_transient_update_themes', array($this, 'last_checked_themes') );

        add_action( 'pre_transient_update_plugins', array(&$this, 'last_checked_plugins') );

        add_filter( 'pre_site_transient_update_plugins', array($this, 'last_checked_plugins') );

        add_filter( 'pre_transient_update_core', array($this, 'last_checked_core') );

        add_filter( 'pre_site_transient_update_core', array($this, 'last_checked_core') );

        add_filter( 'auto_update_translation', '__return_false' );
        add_filter( 'automatic_updater_disabled', '__return_true' );
        add_filter( 'allow_minor_auto_core_updates', '__return_false' );
        add_filter( 'allow_major_auto_core_updates', '__return_false' );
        add_filter( 'allow_dev_auto_core_updates', '__return_false' );
        add_filter( 'auto_update_core', '__return_false' );
        add_filter( 'wp_auto_update_core', '__return_false' );
        add_filter( 'auto_core_update_send_email', '__return_false' );
        add_filter( 'send_core_update_notification_email', '__return_false' );
        add_filter( 'auto_update_plugin', '__return_false' );
        add_filter( 'auto_update_theme', '__return_false' );
        add_filter( 'automatic_updates_send_debug_email', '__return_false' );
        add_filter( 'automatic_updates_is_vcs_checkout', '__return_true' );


        add_filter( 'automatic_updates_send_debug_email ', '__return_false', 1 );
        if( !defined( 'AUTOMATIC_UPDATER_DISABLED' ) ) define( 'AUTOMATIC_UPDATER_DISABLED', true );
        if( !defined( 'WP_AUTO_UPDATE_CORE') ) define( 'WP_AUTO_UPDATE_CORE', false );

        add_filter( 'pre_http_request', array($this, 'block_request'), 10, 3 );
    }

    function OS_Disable_WordPress_Updates() {
        $this->__construct();
    }

    function admin_init() {
        if ( !function_exists("remove_action") ) return;

        remove_action( 'admin_notices', 'update_nag', 3 );
        remove_action( 'network_admin_notices', 'update_nag', 3 );
        remove_action( 'admin_notices', 'maintenance_nag' );
        remove_action( 'network_admin_notices', 'maintenance_nag' );

        remove_action( 'load-themes.php', 'wp_update_themes' );
        remove_action( 'load-update.php', 'wp_update_themes' );
        remove_action( 'admin_init', '_maybe_update_themes' );
        remove_action( 'wp_update_themes', 'wp_update_themes' );
        wp_clear_scheduled_hook( 'wp_update_themes' );

        remove_action( 'load-update-core.php', 'wp_update_themes' );
        wp_clear_scheduled_hook( 'wp_update_themes' );

        remove_action( 'load-plugins.php', 'wp_update_plugins' );
        remove_action( 'load-update.php', 'wp_update_plugins' );
        remove_action( 'admin_init', '_maybe_update_plugins' );
        remove_action( 'wp_update_plugins', 'wp_update_plugins' );
        wp_clear_scheduled_hook( 'wp_update_plugins' );

        remove_action( 'load-update-core.php', 'wp_update_plugins' );
        wp_clear_scheduled_hook( 'wp_update_plugins' );

        add_action( 'init', create_function( '', 'remove_action( \'init\', \'wp_version_check\' );' ), 2 );
        add_filter( 'pre_option_update_core', '__return_null' );

        remove_action( 'wp_version_check', 'wp_version_check' );
        remove_action( 'admin_init', '_maybe_update_core' );
        wp_clear_scheduled_hook( 'wp_version_check' );

        wp_clear_scheduled_hook( 'wp_version_check' );
        wp_clear_scheduled_hook( 'wp_privacy_delete_old_export_files' );
        wp_clear_scheduled_hook( 'wp_scheduled_auto_draft_delete' );
        wp_clear_scheduled_hook( 'delete_expired_transients' );
        wp_clear_scheduled_hook( 'wp_scheduled_delete' );
        wp_clear_scheduled_hook( 'recovery_mode_clean_expired_keys' );

        remove_action( 'wp_maybe_auto_update', 'wp_maybe_auto_update' );
        remove_action( 'admin_init', 'wp_maybe_auto_update' );
        remove_action( 'admin_init', 'wp_auto_update_core' );
        wp_clear_scheduled_hook( 'wp_maybe_auto_update' );
    }


    public function block_request($pre, $args, $url) {}

    public function last_checked_core() {}

    public function last_checked_themes() {}

    public function last_checked_plugins() {}
}

if ( class_exists('OS_Disable_WordPress_Updates') ) {
    $OS_Disable_WordPress_Updates = new OS_Disable_WordPress_Updates();
}