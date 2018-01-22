<?php
/*
Plugin Name: Post List Featured Image
Plugin URI: https://github.com/liamstewart23/WordPressPostListFeaturedImage
Description: Post List Featured Image Status Icon
Version: 1.0.0
Author: Liam Stewart
Author URI: https://liamstewart.ca
*/

/**
 * WordPress Version 4.0 or greater
 */
$requiredVersion = "4.0";
if (version_compare(get_bloginfo('version'), $requiredVersion, '<')) {
    wp_die("<h1>You must update WordPress to use this plugin! </h1><br>
    You are currently running WordPress version <strong>" . get_bloginfo('version') . "</strong><br> This plugin requires <strong>" . $requiredVersion . "</strong> or greater");
}

// Adding theme support.
add_theme_support('post-thumbnails');

function ls_plfi_column_width()
{
    echo '<style type="text/css">';
    echo '.column-ls_plfi_column { text-align: center; width:20px !important; overflow:hidden }';// Custom Column styles
    echo '.ls_plfi_icon {height:12px;width:12px;border-radius:50%;transform:translateX(5px);margin-top:3px;}';// Circle icon styles
    echo '.ls_plfi_hidden {display:none;}';// Hidden class for screen options text
    echo '</style>';
}
add_action('admin_head', 'ls_plfi_column_width');


/**
 * @param $defaults
 * @return mixed
 */
function ls_plfi_columns_head($defaults)
{
    $defaults['ls_plfi_column'] = '<div class="ls_plfi_hidden">Featured Image Status</div><span class="dashicons dashicons-format-image"></span>';
    return $defaults;
}

/**
 * @param $column_name
 * @param $post_ID
 */
function ls_plfi_columns_content($column_name, $post_ID)
{
    if ($column_name === 'ls_plfi_column') {// If column exists
        $post_featured_image = get_post_thumbnail_id($post_ID); // Check for Featured Image
        $status = 'dc3232';//Red
        $link = '';
        $onclick = '';
        $target = '';
        if ($post_featured_image) {// Has Featured Image
            $status = '7ad03a';//Green
            $link = wp_get_attachment_url($post_featured_image);
            $onclick = '';
            $target = 'target="_blank"';
        }
        // Display circle status icon
        echo '<a href="' . $link . '" ' . $onclick . ' ' . $target . '>
            <div class="ls_plfi_icon" style="background:#' . $status . ';"></div>
        </a>';
    }
}

// Pages
add_filter('manage_pages_columns', 'ls_plfi_columns_head');
add_action('manage_pages_custom_column', 'ls_plfi_columns_content', 10, 2);

// All Post Types
add_filter('manage_posts_columns', 'ls_plfi_columns_head');
add_action('manage_posts_custom_column', 'ls_plfi_columns_content', 10, 2);
