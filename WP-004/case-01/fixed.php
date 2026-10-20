<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: wp-html-mail 3.1, includes/class-template-designer.php, rest_api_init() (excerpt)
// CVE-2022-0218 (fixed). Copyright the wp-html-mail authors.
register_rest_route($this->api_base, '/themesettings', array(
    'methods' => 'GET',
    'callback' => [$this, 'getThemeSettings'],
    'permission_callback' => function () {
        return current_user_can('manage_options');
    }
));

register_rest_route($this->api_base, '/themesettings', array(
    'methods' => 'POST',
    'callback' => [$this, 'saveThemeSettings'],
    'permission_callback' => function () {
        return current_user_can('manage_options');
    }
));
