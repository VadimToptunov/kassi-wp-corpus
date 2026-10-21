<?php
// SPDX-License-Identifier: GPL-3.0-or-later
// Source: mybrain-utilities 1.1.0, public/class-mybrain-utilities-public.php (excerpt; elided context marked '...')
// CVE-2025-10126 (fixed). Copyright the mybrain-utilities authors.
class Mybrain_Utilities_Public
{
    // ...
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        //20240901 - settings
        add_action('admin_init', array($this, 'mybrain_utilities_settings_init'));

        $options = get_option('mybrain_utilities_options', array());
        if (isset($options['htaccesskeeperenabled']) && ($options['htaccesskeeperenabled'] == 'yes')) {
            // 20250326 - to prevent "Call to undefined function is_plugin_active()" - if (is_plugin_active('htaccess-keeper.php') == false) {
            if (!in_array('htaccess-keeper.php', (array) get_option('active_plugins', array()))) {
                //20240904 - htaccess keeper!
                add_action('init', array($this, 'mybrain_utilities_run_htaccess_keeper'), 1);
            }
        }
        if (isset($options['mapenabled']) && ($options['mapenabled'] == 'yes')) {
            //20240904 - map!
            add_shortcode('mbumap', array($this, 'mybrain_utilities_extra_leaflet_map'));
        }
        if (isset($options['keeploginenabled']) && ($options['keeploginenabled'] == 'yes')) {
            //20240904 - keeplogin!
            add_filter('auth_cookie_expiration', array($this, 'mybrain_utilities_keep_me_logged_in_for_1_year'));
        }
    }
    // ...
    public function mybrain_utilities_extra_leaflet_map($atts)
    {
        $atts = shortcode_atts(
            array(
                'center' => '52.145634,5.04855',
                'coords' => '',
                'zoom' => '15',
                'class' => 'mbu-map',
            ),
            $atts,
            'mbu_map'
        );
        //20250909 - sanitize!
        return '<div id="mbu-map" center="'.esc_attr($atts['center']).'" coords="'.esc_attr($atts['coords']).'" zoom="'.esc_attr($atts['zoom']).'" class="'.esc_attr($atts['class']).'"></div>';
    }
}
