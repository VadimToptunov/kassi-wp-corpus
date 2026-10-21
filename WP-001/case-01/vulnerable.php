<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: the-moneytizer 9.6.3, core/core_ajax.php (excerpt; elided context marked '...')
// CVE-2023-6966. Copyright the the-moneytizer authors.
function themoneytizer_update_language() {
    if(isset($_POST['language'])){
        update_option('themoneytizer_data_language', $_POST['language']);
        $response = array('status' => true);
        echo json_encode($response);
        return;
    }
    $response = array('status' => false);
    echo json_encode($response);
}
add_action('wp_ajax_update_language', 'themoneytizer_update_language');
