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
if(!function_exists('wpmatuto_generate_password')):
    function wpmatuto_generate_password($extra_special_chars = false) {
        $chars = 'abcdefghijklmnopqrstuvwxyz';
        $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $chars .= '0123456789';
        $chars .= '!@#$%^&*()';

        if($extra_special_chars):
            $chars .= '-_ []{}<>~`+=,.;:/?|';
        endif;

        $wpmatuto_password = ''; // Initialize the password string

        for ($i = 0; $i < 12; $i += 1) {    // 12 is the length of the password
            $wpmatuto_password .= substr($chars, wp_rand(0, strlen($chars) - 1), 1);
        }

        return apply_filters('random_password', $wpmatuto_password);
    }
endif;

function wpmatuto_style_password() {
    $wpmatuto_is_rtl = is_rtl() ? 'left' : 'right';
    echo "
    <style>
    .wpmatuto_show_password {
        float: $wpmatuto_is_rtl;
        padding-$wpmatuto_is_rtl: 15px;
        padding-top: 5px;
        margin: 0;
        font-size: 11px; }
    </style>
    ";
}

add_action('admin_head', 'wpmatuto_style_password');

function wpmatuto_show_generated_password() {
    /*
     * Set the $inc_extra_special_chars to true if you want to include
     * the extra special characters
    */
    $show_password = wpmatuto_generate_password($inc_extra_special_chars = false);
    // Do not use _e, just use __ when using printf or sprintf
    printf(
        __('<p class=\'wpmatuto_show_password\'>Generated&nbsp;Password:&nbsp;<strong>%s</strong></p>', 'wpmatuto'),
        $show_password
    );
}

add_action('admin_notices', 'wpmatuto_show_generated_password');

// Custom Taxonomy for Tutorial
function wpmatuto_register_taxonomy_tutorial() {
    // either use [] or array() depending on your PHP version you're using
    $labels = array(
        'name'              => _x('Tutorials', 'taxonomy general name', 'wpmatuto'),
        'singular_name'     => _x('Tutorial', 'taxonomy singular name', 'wpmatuto'),
        'search_items'      => __('Search Tutorials', 'wpmatuto'),
        'all_items'         => __('All Tutorials', 'wpmatuto'),
        'parent_item'       => __('Parent Tutorial', 'wpmatuto'),
        'parent_item_colon' => __('Parent Tutorial:', 'wpmatuto'),
        'edit_item'         => __('Edit Tutorial', 'wpmatuto'),
        'update_item'       => __('Update Tutorial', 'wpmatuto'),
        'add_new_item'      => __('Add New Tutorial', 'wpmatuto'),
        'new_item_name'     => __('New Tutorial Name', 'wpmatuto'),
        'menu_name'         => __('Tutorial', 'wpmatuto'),
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
    <h3><?php echo esc_html__('Your Birthday', 'wpmatuto'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="birthday"><?php echo esc_html__('Birthday', 'wpmatuto'); ?></label></th>
            <td>
                <input type="date"
                       class="regular-text ltr"
                       id="birthday"
                       name="birthday"
                       value="<?= esc_attr(get_user_meta($user->ID, 'birthday', true)); ?>"
                       title="<?php echo esc_html__('Please use YYYY-MM-DD as the date format.', 'wpmatuto'); ?>"
                       pattern="(19[0-9][0-9]|20[0-9][0-9])-(1[0-2]|0[1-9])-(3[01]|[21][0-9]|0[1-9])"
                       required>
                <p class="description"><?php echo esc_html__('Please enter your birthday date.', 'wpmatuto'); ?></p>
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

function wpmatuto_custom_cron_interval() {
    $schedules['one_day'] = array(
        'interval' => 86400,
        'display' => esc_html__('Daily', 'wpmatuto'),
    );
}

add_filter('cron_schedules', 'wpmatuto_custom_cron_interval');
