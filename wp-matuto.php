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

/* This is the function where the password is generated */
if(!function_exists('wpmatuto_generate_pw')):
    function wpmatuto_generate_pw($extra_special_chars = false) {
        $chars = 'dYZ012wx3eno&*(vyzpqK)$%LMNrstuA';
        $chars .= 'BCDfghijkIJlmEF67GHOPabcQRST^!@#U89VWX45';

        if($extra_special_chars):
            $chars .= '\'-_ []{}<>~`+=,.;:/?|';
        endif;

        $pw = ''; // Initialize the password string

        for ($i = 0; $i < 12; $i += 1) {    // 12 is the length of the password
            $pw .= substr($chars, wp_rand(0, strlen($chars) - 1), 1);
        }

        return apply_filters('random_password', $pw);
    }
endif;

function stylePW() {
    $lrrl = is_rtl() ? 'left' : 'right';
    echo "<style>#shpw{float:$lrrl;padding-$lrrl:15px;padding-top:5px;margin:0;font-size:11px;}</style>";
}

add_action('admin_head', 'stylePW');

function showpw() {
    /*
     * Set the $inc_extra_special_chars to true if you want to include
     * the extra special characters
    */
    $shpw = wpmatuto_generate_pw($inc_extra_special_chars = false);
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

// Sample field
function wpmatuto_usermeta_form_field_birthday() {
    ?>
    <h3>Your Birthday</h3>
    <table class="form-table">
        <tr>
            <th><label for="birthday"></label></th>
            <td>
                <input type="date"
                       class="regular-text ltr"
                       id="birthday"
                       name="birthday"
                       value="<?= esc_attr(get_user_meta($user->ID, 'birthday', true)); ?>"
                       title="Please use YYYY-MM-DD as the date format."
                       pattern="(19[0-9][0-9]|20[0-9][0-9])-(1[0-2]|0[1-9])-(3[01]|[21][0-9]|0[1-9])"
                       required>
                <p class="description">Please enter your birthday date.</p>
            </td>
        </tr>
    </table>
    <?php
}

function wpmatuto_usermeta_form_field_birthday_update($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    return update_user_meta($user_id, 'birthday', $_POST['birthday']);
}

add_action('edit_user_profile', 'wpmatuto_usermeta_form_field_birthday');

add_action('show_user_profile', 'wpmatuto_usermeta_form_field_birthday');

add_action('personal_options_update', 'wpmatuto_usermeta_form_field_birthday_update');

add_action('edit_user_profile_update', 'wpmatuto_usermeta_form_field_birthday_update');

function myap() {
}