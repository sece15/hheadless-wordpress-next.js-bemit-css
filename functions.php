<?php

if(!defined('REDIRECT_URL')){
    define("REDIRECT_URL", '');
}

if(!function_exists('a_custom_redirect')){
    function a_custom_redirect(){
        header('Location: '. REDIRECT_URL);
        die();
    }
}

if(!function_exists('a_theme_setup')){
    function a_theme_setup() {
        add_theme_support('post-thumbnails');
    }
    add_action ('after_setup_theme', 'a_theme_setup');
}

//NEED INSTALLED ACF

if(class_exists('acf')){
    //ADD PAGES OF THEME SETTINGS
    //CUSTOM OPTIONS THEME
    if(function_exists('acf_add_options_page')){
        acf_add_options_page(array(
            'page_title' => 'Theme Settings',
            'menu_title' => 'Theme Settings',
            'menu_slug'  => 'theme-settings',
            'capability' => 'edit_posts',
            'redirect'   =>  true
        ));

        acf_add_options_sub_page(array(
            'page_title' => 'Theme General Settings',
            'menu_title' => 'General',
            'parent_slug' => 'theme-settings',
        ));

        acf_add_options_page(array(
            'page_title' => 'Blocks',
            'menu_title' => 'Blocks',
            'menu_slug'  => 'blocks',
            'capability' => 'edit_posts',
            'redirect'   =>  true
        ));

        acf_add_options_sub_page(array(
            'page_title' => 'Header',
            'menu_title' => 'Header',
            'parent_slug' => 'blocks',
        ));

        acf_add_options_sub_page(array(
            'page_title' => 'Footer',
            'menu_title' => 'Footer',
            'parent_slug' => 'blocks',
        ));

        acf_add_options_sub_page(array(
            'page_title' => 'Cookies',
            'menu_title' => 'Cookies',
            'parent_slug' => 'blocks',
        ));

        acf_add_options_sub_page(array(
            'page_title' => 'About',
            'menu_title' => 'About',
            'parent_slug' => 'blocks',
        ));
    }
}

//para usar svg 
if(!function_exists('a_mime_types')){
    function a_mime_types($mimes){
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }
}

//agregar una imagen customisable en size
if(!function_exists('a_add_image_size')){
    function a_add_image_size(){
        add_image_size('custom-medium',           300,    9999);
        add_image_size('custom-tablet',           600,    9999);
        add_image_size('custom-large',            1200,    9999);
        add_image_size('custom-large-crop',       1200,    1200, true);
        add_image_size('custom-desktop',          1600,    9999);
        add_image_size('custom-full',             2560,    9999);
    }
    add_action('after_setup_theme','a_add_image_size');
}


if(!function_exists('a_custom_image_size_names')){
    function a_custom_image_size_names($sizes){
        return array_merge($sizes, array(
            'custom_medium'                => __('Custom medium', 'wp-studio-15'),
            'custom_tablet'                => __('Custom tablet', 'wp-studio-15'),
            'custom_large'                 => __('Custom large', 'wp-studio-15'),
            'custom_large_crop'            => __('Custom large crop', 'wp-studio-15'),
            'custom_desktop'               => __('Custom desktop', 'wp-studio-15'),
            'custom_full'                  => __('Custom full', 'wp-studio-15'),
        ));
    }
    add_filter('image_size_names_chooose','a_custom_image_size_names');
}

//disable for posts
add_filter('use_block_editor_for_post','__return_false',10);
//disable for post types
add_filter('use_block_editor_for_post_type','__return_false',10);

/**Register Menus */
if(!function_exists('a_custom_navigation_menus')){
    function a_custom_navigation_menus(){
        $locations = array(
            'header-menu'    => __('Header Menu', 'wp-studio-15'),
            'footer-menu'    => __('Footer Menu', 'wp-studio-15'),
        );
        register_nav_menus( $locations );
    }
    add_action( 'init','a_custom_navigation_menus' );
}

if(!function_exists('a_register_custom_post_types')){
    function a_register_custom_post_types(){
        //CPT PROJECT
        $singular_name = __('Project','wp-studio-15');
        $plural_name   = __('Projects','wp-studio-15');
        $slug_name     = 'cpt-project';

        register_post_type( $slug_name, array(
            'label'            => $singular_name,
            'public'           => true,
            'capability_type'  => 'post',
            'map_meta_cap'     => true,
            'has_archive'      => false,
            'query_var'        => $slug_name,
            'supports'         => array('title','thumbnail','revisions'),
            'labels'           => a_get_custom_post_type_labels( $singular_name, $plural_name ),
            'menu_icon'        => 'dashicons-images-alt2',
            'show_in_rest'     => true
        ));
    }
    add_action('init','a_register_custom_post_types');
}

if(!function_exists('a_get_custom_post_type_labels')){
    function a_get_custom_post_type_labels($singular,$plural){
        $labels = array (
            'name'                 =>$plural,
            'singular_name'        =>$singular,
            'menu_name'            =>$plural,
            'add_new'              =>sprintf(__('Add %s', 'wp-studio-15'), $singular),
            'add_new_item'         =>sprintf(__('Add new %s', 'wp-studio-15'), $singular),
            'edit'                 =>__('Edit','wp-studio-15'),
            'edit_item'            =>sprintf(__('Edit %s', 'wp-studio-15'), $singular),
            'new_item'             =>sprintf(__('New %s', 'wp-studio-15'), $singular),
            'view'                 =>sprintf(__('View %s', 'wp-studio-15'), $singular),
            'view_item'            =>sprintf(__('View %s', 'wp-studio-15'), $singular),
            'search_items'         =>sprintf(__('Search %s', 'wp-studio-15'), $plural),
            'not_found'            =>sprintf(__('%s not found', 'wp-studio-15'), $plural),
            'not_found_in_trash'   =>sprintf(__('%s not found in trash', 'wp-studio-15'), $plural),
            'parent'               =>sprintf(__('Parent %s', 'wp-studio-15'), $singular),
        );
        return $labels;
    }
}