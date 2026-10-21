<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: eds-font-awesome 2.0, eds_font_awesome.php (excerpt; elided context marked '...')
// CVE-2026-2496. Copyright the eds-font-awesome authors.
function efap_register_shortcodes()
{
    add_shortcode('eds_font_awesome', 'eds_font_awesome_shortcode');
    add_shortcode('eds_fa_mask', 'eds_font_awesome_mask_shortcode');
    add_shortcode('eds_fa_mask_circle', 'eds_font_awesome_mask_circle_shortcode');
    add_shortcode('eds_font_awesome_rotate', 'eds_font_awesome_rotate_icon_shortcode');
}																														// !SECTION end of 2.1																															
    // ...
function eds_font_awesome_shortcode($atts, $content = '')
{
    // Attributes
    $att = shortcode_atts(
        array(
            'style' => '',
            'name' => '',
            'size' => '',
        ),
        $atts,
        'eds_font_awesome'
    );

    $output = '<i class="' . $att['style'] . ' fa-' . $att['name'] . ' fa-' . $att['size'] .
        '"></i>';
    //$output = '<i class="fas fa-camera fa-3x"></i>';
    //$output = 'This is a test';
    return $output;
}																														//!SECTION END OF 2.2
