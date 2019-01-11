<?php
/*
    Plugin Name: LM Category 
    Description: Модифікація рубрик
    Version: 1.4
    Author: Vlad Salabun
    Author URI: http://salabun.com/
*/


/*-----------------------------------------------------------------------------------------*/ 

    # Додавання стилей:
    add_action('admin_enqueue_scripts', 'load_category_css');
    
    function load_category_css() {
        wp_register_style('css_category', plugin_dir_url( __FILE__ ) . 'css_category.css');
        wp_enqueue_style('css_category');
    } 
    
/*-----------------------------------------------------------------------------------------*/    

    # Фікс для відображення категорій у вигляді дерева навіть після публікації:
    add_filter( 'wp_terms_checklist_args', 'set_checked_ontop_default', 10 );
    
    function set_checked_ontop_default($args) {
        if( ! isset($args['checked_ontop']) )
            $args['checked_ontop'] = false;
        return $args;
    }
    
/*-----------------------------------------------------------------------------------------*/ 
 
    # Ця функція додає пошукову форму для рубрик в адмінці
    add_action('admin_print_scripts', 'lm_category_search_box');
    
    function lm_category_search_box() {
        wp_enqueue_script( 'js_category_search', plugin_dir_url( __FILE__ ) . 'js_category_search.js');
    }
    
/*-----------------------------------------------------------------------------------------*/     

    # Ця функція дозволяє виділяти батьківські категорії, якщо виділяється дочірня категорія:   
    add_action('admin_enqueue_scripts', 'lm_category_scripts_and_styles');
    
    // Підключення скриптів у шапку сайта:
    function lm_category_scripts_and_styles() {
        wp_enqueue_script( 'js_category', plugin_dir_url( __FILE__ ) . 'js_category.js');
    }
    
/*-----------------------------------------------------------------------------------------*/ 

    // Якщо батьківська категорія не вибрана, то приховуємо її
    // Якщо вибрана, то показуємо завжди