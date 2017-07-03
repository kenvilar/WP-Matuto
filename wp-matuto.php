<?php
/*
Plugin Name: WP Matuto
Plugin URI: http://kenvilar.com/
Description: WP Matuto is a plugin that generates password when adding a new user and display the generated pasword in the header of admin page.
Version:     0.0.1
Author:      Ken Vilar
Author URI:  http://kenvilar.com/
Text Domain: wpmatuto
Domain Path: /languages
License:     GPL2

WP Matuto is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

WP Matuto is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WP Matuto. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

// This is the function where the password is generated
if ( ! function_exists( 'wpmatuto_generate_password' ) ):
    function wpmatuto_generate_password( $extra_special_chars = false ) {
        $chars  = 'abcdefghijklmnopqrstuvwxyz';
        $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $chars .= '0123456789';
        $chars .= '!@#$%^&*()';

        if ( $extra_special_chars ):
            $chars .= '-_ []{}<>~`+=,.;:/?|';
        endif;

        $wpmatuto_password = ''; // Initialize the password string
        $password_length = 12;
        for ( $i = 0; $i < $password_length; $i += 1 ) {
            $wpmatuto_password .= substr( $chars, wp_rand( 0, strlen( $chars ) - 1 ), 1 );
        }

        return apply_filters( 'random_password', $wpmatuto_password );
    }
endif;

function wpmatuto_style_password() {
    $wpmatuto_is_rtl = function_exists( 'is_rtl' ) && is_rtl() ? 'left' : 'right';
    echo "
    <style>
    .wpmatuto-show-password {
        float: $wpmatuto_is_rtl;
        font-size: 11px;
        margin: 0;
        padding-top: 5px;
        padding-$wpmatuto_is_rtl: 15px;
    }
    </style>
    ";
}

add_action( 'admin_head', 'wpmatuto_style_password' );

function wpmatuto_show_generated_password() {
    /*
     * Set the $inc_extra_special_chars to true if you want to include
     * the extra special characters.
    */
    $show_password = wpmatuto_generate_password( $inc_extra_special_chars = false );
    // Do not use _e, just use __ when using printf or sprintf
    printf(
        __( '<p class=\'wpmatuto-show-password\'>Generated&nbsp;Password:&nbsp;<strong>%s</strong></p>', 'wpmatuto' ),
        $show_password
    );
}

add_action( 'admin_notices', 'wpmatuto_show_generated_password' );

// Custom Taxonomy for Tutorial
function wpmatuto_register_taxonomy_tutorial() {
    /*
     * either use [] or array() depending on your PHP version you're using.
     * [] can be used when creating in JavaScript
    */
    $labels = array(
        'name'              => _x( 'Tutorials', 'taxonomy general name', 'wpmatuto' ),
        'singular_name'     => _x( 'Tutorial', 'taxonomy singular name', 'wpmatuto' ),
        'search_items'      => __( 'Search Tutorials', 'wpmatuto' ),
        'all_items'         => __( 'All Tutorials', 'wpmatuto' ),
        'parent_item'       => __( 'Parent Tutorial', 'wpmatuto' ),
        'parent_item_colon' => __( 'Parent Tutorial:', 'wpmatuto' ),
        'edit_item'         => __( 'Edit Tutorial', 'wpmatuto' ),
        'update_item'       => __( 'Update Tutorial', 'wpmatuto' ),
        'add_new_item'      => __( 'Add New Tutorial', 'wpmatuto' ),
        'new_item_name'     => __( 'New Tutorial Name', 'wpmatuto' ),
        'menu_name'         => __( 'Tutorial', 'wpmatuto' ),
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'wpmatuto-tutorial' ),
    );
    register_taxonomy( 'wpmatuto-tutorial', array( 'post' ), $args );
}

add_action( 'init', 'wpmatuto_register_taxonomy_tutorial' );
