<?php

/*
  Plugin Name: Availability Image Map
  Description: Apartment Complex Map, Business Center Map, Apartment Community Map, Lot Availability Map, Residence Complex Map
  Author: azexo
  Author URI: http://azexo.com
  Version: 1.27.2
  Text Domain: azh
 */

define('AIM_DIR', trailingslashit(dirname(__FILE__)));

if (is_admin()) {
    include_once(AIM_DIR . 'tgm/class-tgm-plugin-activation.php' );
}

add_action('tgmpa_register', 'aim_tgmpa_register');

function aim_tgmpa_register() {
    tgmpa(array(
        array(
            'name' => esc_html__('Page builder by AZEXO', 'azh'),
            'slug' => 'page-builder-by-azexo',
            'required' => true,
        ),
        array(
            'name' => esc_html__('Mynx Page builder', 'azh'),
            'slug' => 'mynx-page-builder',
            'required' => true,
        ),
        array(
            'name' => esc_html__('Admin Columns', 'azh'),
            'slug' => 'codepress-admin-columns',
            'required' => true,
        ),
        array(
            'name' => esc_html__('WP-LESS', 'azh'),
            'slug' => 'wp-less',
        ),
    ));
}

register_activation_hook(__FILE__, 'aim_activate');

function aim_activate() {
    update_option('azh-library', array());
    update_option('azh-all-settings', array());
    update_option('azh-get-content-scripts', array());
    update_option('azh-content-settings', array());
}

add_action('admin_notices', 'aim_notices');

function aim_notices() {
    $plugin_data = get_plugin_data(__FILE__);
    if (defined('AZH_VERSION')) {
        $plugin_version = explode('.', $plugin_data['Version']);
        $plugin_version = $plugin_version[1];
        $azh_version = explode('.', AZH_VERSION);
        $azh_version = $azh_version[1];
        if ((int) $plugin_version !== (int) $azh_version) {
            print '<div id="azh-version" class="notice-error settings-error notice is-dismissible"><p>' . esc_html__('AZEXO Builder version does not correspond with library version. Please update library plugin or builder plugin', 'azh') . '</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">' . esc_html__('Dismiss this notice.', 'azh') . '</span></button></div>';
        }
        $library = azh_get_library();
        if (!isset($library['sections_categories']['mynx'])) {
            print '<div class="updated notice error is-dismissible"><p>' . sanitize_text_field($plugin_data['Name']) . ': ' . esc_html__('please install', 'azh') . ' <a href="https://codecanyon.net/item/mynx-page-builder/22870998">Mynx Page Builder</a> ' . esc_html__('plugin', 'azh') . '.</p><button class="notice-dismiss" type="button"><span class="screen-reader-text">' . esc_html__('Dismiss this notice.', 'azh') . '</span></button></div>';
        }
    } else {
        print '<div class="updated notice error is-dismissible"><p>' . sanitize_text_field($plugin_data['Name']) . ': ' . esc_html__('please install', 'azh') . ' <a href="https://codecanyon.net/item/azexo-html-customizer/16350601">Page Builder by AZEXO</a> ' . esc_html__('plugin', 'azh') . '</p><button class="notice-dismiss" type="button"><span class="screen-reader-text">' . esc_html__('Dismiss this notice.', 'azh') . '</span></button></div>';
    }
}

add_filter('azh_directory', 'aim_directory');

function aim_directory($dir) {
    $dir[untrailingslashit(dirname(__FILE__)) . '/azh'] = plugins_url('', __FILE__) . '/azh';
    return $dir;
}

add_filter('azh_default_category', 'aim_default_category', 12);

function aim_default_category($default_category) {
    return 'image-map';
}

add_action('plugins_loaded', 'aim_plugins_loaded');

function aim_plugins_loaded() {
    load_plugin_textdomain('azh', FALSE, basename(dirname(__FILE__)) . '/languages/');
}

function aim_create_page($title, $content) {
    $post_id = wp_insert_post(array(
        'post_title' => $title,
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_author' => get_current_user_id(),
        'post_content' => $content,
            ), true);
    if (!is_wp_error($post_id)) {
        add_post_meta($post_id, 'azh', 'azh', true);
        add_post_meta($post_id, '_wp_page_template', 'azexo-html-template.php', true);
        return $post_id;
    }
    return false;
}

add_action('init', 'aim_init');

function aim_init() {
    if (defined('AZH_VERSION')) {
        $settings = get_option('azh-settings');
        if (!isset($settings['templates']['enable']) || !$settings['templates']['enable']) {
            $settings = get_option('azh-settings');
            $settings['templates']['enable'] = 1;
            update_option('azh-settings', $settings);
        }
    }
    if (function_exists('azt_init') && function_exists('azt_get_item_fields') && function_exists('azh_filesystem') && function_exists('azh_set_post_content') && !get_option('aim-demo-imported')) {
        update_option('aim-demo-imported', true);
        azh_filesystem();
        global $wp_filesystem;
        $table = $wp_filesystem->get_contents(plugin_dir_path(__FILE__) . 'demo-table.json');
        if ($table) {
            $table = json_decode($table, true);
            if (is_array($table) && isset($table['table'])) {
                $table = $table['table'];
                foreach ($table as $item) {
                    $post_id = wp_insert_post(array(
                        'post_title' => '',
                        'post_type' => 'azt_item',
                        'post_status' => 'publish',
                        'post_author' => get_current_user_id(),
                        'post_content' => '',
                            ), true);
                    if (!is_wp_error($post_id)) {
                        $post_id = (int) $post_id;
                        global $wpdb;
                        $values = array();
                        foreach ($item as $key => $value) {
                            $values[] = "($post_id, '" . esc_sql("$key") . "', '" . esc_sql("$value") . "')";
                        }
                        $values = implode(',', $values);
                        $wpdb->query("REPLACE INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES {$values}");

                        wp_update_post(array(
                            'ID' => $post_id,
                            'post_content' => wp_slash(json_encode(azt_get_item_fields($id))),
                        ));
                    }
                }
            }
        }
        $options = $wp_filesystem->get_contents(plugin_dir_path(__FILE__) . 'options.json');
        if ($options) {
            $options = json_decode($options, true);
            if (is_array($options)) {
                foreach ($options as $name => $option) {
                    update_option($name, $option);
                }
            }
        }


        $complex_page_id = aim_create_page('Apartments complex', '');
        $building_page_id = aim_create_page('Building', '');
        $level_page_id = aim_create_page('Level floor plan', '');
        $apartment_page_id = aim_create_page('Apartment floor plan', '');

        $replaces = array(
            '{{azh-uri}}' => plugins_url('azh', __FILE__),
            'http://azexo.com/luxevelin/apartments-complex/' => get_permalink($complex_page_id),
            'http://azexo.com/luxevelin/building/' => get_permalink($building_page_id),
            'http://azexo.com/luxevelin/level-floor-plan/' => get_permalink($level_page_id),
            'http://azexo.com/luxevelin/apartment-floor-plan/' => get_permalink($apartment_page_id),
        );
        $complex = $wp_filesystem->get_contents(plugin_dir_path(__FILE__) . 'azh/image-map/complex.html');
        $complex = str_replace(array_keys($replaces), array_values($replaces), $complex);
        azh_set_post_content('<div data-section="content">' . $complex . '</div>', $complex_page_id);

        $building = $wp_filesystem->get_contents(plugin_dir_path(__FILE__) . 'azh/image-map/building.html');
        $building = str_replace(array_keys($replaces), array_values($replaces), $building);
        azh_set_post_content('<div data-section="content">' . $building . '</div>', $building_page_id);

        $level = $wp_filesystem->get_contents(plugin_dir_path(__FILE__) . 'azh/image-map/level.html');
        $level = str_replace(array_keys($replaces), array_values($replaces), $level);
        azh_set_post_content('<div data-section="content">' . $level . '</div>', $level_page_id);

        $apartment = $wp_filesystem->get_contents(plugin_dir_path(__FILE__) . 'azh/image-map/apartment.html');
        $apartment = str_replace(array_keys($replaces), array_values($replaces), $apartment);
        azh_set_post_content('<div data-section="content">' . $apartment . '</div>', $apartment_page_id);
    }
}
