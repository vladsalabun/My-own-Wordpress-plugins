<?php
/*
    Plugin Name: LM Admin Bar 
    Description: Модифікація адмін панелі
    Version: 1.0
    Author: Vlad Salabun
    Author URI: http://salabun.com/
*/


## Удаление базовых элементов (ссылок) из тулбара
    add_action('add_admin_bar_menus', function(){
        /* доступно для удаления:

        remove_action( 'admin_bar_menu', 'wp_admin_bar_my_account_menu', 0 );  // Внутренние ссылки меню профиля
        remove_action( 'admin_bar_menu', 'wp_admin_bar_search_menu', 4 );      // поиск
        */
        
        remove_action( 'admin_bar_menu', 'wp_admin_bar_my_account_item', 7 );  // Полностью меню профиля
        
        /*
        // Связанное с сайтом
        remove_action( 'admin_bar_menu', 'wp_admin_bar_sidebar_toggle', 0 );   // 
        remove_action( 'admin_bar_menu', 'wp_admin_bar_wp_menu', 10 );         // WordPress ссылки (WordPress лого)
        remove_action( 'admin_bar_menu', 'wp_admin_bar_my_sites_menu', 20 );   // мои сайты
        remove_action( 'admin_bar_menu', 'wp_admin_bar_site_menu', 30 );       // сайты
        remove_action( 'admin_bar_menu', 'wp_admin_bar_customize_menu', 40 );  // настроить тему
        */
        
        remove_action( 'admin_bar_menu', 'wp_admin_bar_updates_menu', 50 );    // обновления
        
        /*
        // Content related.
        */

        if ( ! is_network_admin() && ! is_user_admin() ) {
            remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );    // комментарии
            // добавить пост:
            remove_action( 'admin_bar_menu', 'wp_admin_bar_new_content_menu', 70 ); // новые комментарии
        }

        
        remove_action( 'admin_bar_menu', 'wp_admin_bar_edit_menu', 80 ); // редактировать
        /*
        remove_action( 'admin_bar_menu', 'wp_admin_bar_add_secondary_groups', 200 ); // вся дополнительная группа (поиск и аккаунт) расположена справа в меню
        */

        // удаляем
        remove_action( 'admin_bar_menu', 'wp_admin_bar_customize_menu', 40); // Настроить тему
        //remove_action( 'admin_bar_menu', 'wp_admin_bar_search_menu', 4 );    // поиск
        remove_action( 'admin_bar_menu', 'wp_admin_bar_wp_menu', 10 );      // WordPress ссылки (WordPress лого)
        
    });

    add_action( 'admin_bar_menu', 'my_admin_bar_menu', 30 );
    function my_admin_bar_menu( $wp_admin_bar ) {
        $wp_admin_bar->add_menu( array(
            'id'    => 'menu_id',
            // тут морковка:
            'title' => '<img src="'.plugin_dir_url( __FILE__ ).'/heart.png" id="admin_heart">',
            'href'  => get_site_url(),
        ) );
        //$this->remove_node( 'wp-admin-bar-dashboard' );
    }



/**
 * toolbar nav menu - Менюшка в адмінбарі
 * v 0.3
 */
    add_action( 'after_setup_theme', function(){
        register_nav_menu( 'toolbar', 'Панель инструментов' );
    });
    add_action( 'admin_bar_menu', 'kama_add_toolbar_menu', 999 );
    function kama_add_toolbar_menu( $toolbar ){
        $locations = get_nav_menu_locations();

        if( ! isset($locations['toolbar']) ) return;

        $items = wp_get_nav_menu_items( $locations['toolbar'] );

        if( ! $items ) return;

        foreach( $items as $item ){
            $args = array(
                'parent' => $item->menu_item_parent ? 'id_' . $item->menu_item_parent : false,
                'id'     => 'id_'. $item->ID,
                'title'  => $item->title, 
                'href'   => $item->url, 
                'meta'   => array(
                    // 'html' - The html used for the node.
                    // 'class' - The class attribute for the list item containing the link or text node.
                    // 'rel' - The rel attribute.
                    // 'onclick' - The onclick attribute for the link. This will only be set if the 'href' argument is present.
                    // 'target' - The target attribute for the link. This will only be set if the 'href' argument is present.
                    // 'title' - The title attribute. Will be set to the link or to a div containing a text node.
                    // 'tabindex' - The tabindex attribute. Will be set to the link or to a div containing a text node. 

                    'class'  => implode(' ', $item->classes ),
                    'title'  => esc_attr( $item->description ),
                    'target' => $item->target,
                )
            );

            $toolbar->add_node( $args );            
        }
    }
    
    //  Колір адмін бара в адмінці:
    add_action('admin_head', 'custom_colors');
    
    function custom_colors() {
?>
    <style type="text/css">
	#wpadminbar { 
        background: #31343E;
    }
    a.ab-item {
        color: #FED570 !important;
    }
    #admin_heart {
        width: 20px;
        margin: 7px 0 7px 5px;
    }
	</style>
<?php
    }  
    
    // Колір адмін бара на фронті:
    add_action( 'wp_enqueue_scripts', 'admin_bar_front_css' );
    
    function admin_bar_front_css(){
        wp_enqueue_style( 'style-name', plugin_dir_url( __FILE__ ) . 'css_adminbar.css' );
    }    
    

    
    
    // Удаляем версию скриптов
    add_filter( 'script_loader_src', '_remove_script_version' );
    // Удаляем версию стилей
    add_filter( 'style_loader_src', '_remove_script_version' );
    function _remove_script_version( $src ){
        $parts = explode( '?', $src );
        return $parts[0];
    } 