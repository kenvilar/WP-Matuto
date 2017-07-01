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

function get_pw() {
    $res = "";
    $max_pw_length = 8; //Maximum length of pw characters
    $pw = "#b675hFJc$)wxTUyz+{[</?CDE89S";
    $pw .= "-_=MN10A>BadmnoPGHIefg4tpWV]";
    $pw .= "rsqK%^&*(klijQ23}RLOuv~!@XYZ";
    $pwlen = strlen($pw);

    for ($i = 0; $i < $max_pw_length; $i += 1) {
        $res .= $pw[rand(0, $pwlen - 1)];
    }

    return $res;
}

function stylePW() {
    $lrrl = is_rtl() ? 'left' : 'right';
    echo "<style>#shpw{float:$lrrl;padding-$lrrl:15px;padding-top:5px;margin:0;font-size:11px;}</style>";
}

add_action('admin_head', 'stylePW');

function showpw() {
    $shpw = get_pw();
    echo "<p id='shpw'>Generated&nbsp;Password:&nbsp;<strong>$shpw</strong></p>";
}

add_action('admin_notices', 'showpw');

// Custom Taxonomy for Tutorial
function wpmatuto_register_taxonomy_tutorial() {
    // either use [] or array() depending on your PHP version you're using
    $labels = array(
        'name'              => _x('Tutorials', 'taxonomy general name'),
        'singular_name'     => _x('Tutorial', 'taxonomy singular name'),
        'search_items'      => __('Search Tutorials'),
        'all_items'         => __('All Tutorials'),
        'parent_item'       => __('Parent Tutorial'),
        'parent_item_colon' => __('Parent Tutorial:'),
        'edit_item'         => __('Edit Tutorial'),
        'update_item'       => __('Update Tutorial'),
        'add_new_item'      => __('Add New Tutorial'),
        'new_item_name'     => __('New Tutorial Name'),
        'menu_name'         => __('Tutorial'),
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'wpmatuto-tutorial'),
    );
    register_taxonomy('wpmatuto-tutorial', array('post'), $args);
}

add_action('init', 'wpmatuto_register_taxonomy_tutorial');
