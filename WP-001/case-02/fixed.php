<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: shopperapproved-reviews 2.2.1, shopperapproved.php (excerpt; elided context marked '...')
// CVE-2025-3063 (fixed). Copyright the shopperapproved-reviews authors.
function ajax_callback_update_sa_option()
{
    //verify nonce to prevent cross-site scripting attacks
    check_ajax_referer('sa_nonce','sa_csrf_check');

    if ( ! current_user_can( 'manage_options') ) {
        wp_send_json_error( array('msg' => 'Unauthorized User') );
    }

    //ensure that the option is set
    if ( ! isset($_POST['option_value']) ) {
        wp_send_json_error( array('msg' => 'Missing option value') );
    }

    // get the post data
    $option_key = isset($_POST["option_key"]) ? sanitize_text_field(wp_unslash($_POST["option_key"])) : '';
    $option_value = sanitize_text_field(wp_unslash($_POST["option_value"]));

    //Allowed option keys
    $allowed_keys = array(
        'sa_survey_status',
        'sa_rp_status',
        'sa_seal_status',
        'sa_seal_status2',
        'sa_rotating_widget_status',
        'sa_pwidgets_status',
        'sa_cstars_status',
        'sa_feed_gtin'
    );
    if ( ! in_array ( $option_key,$allowed_keys, true) ){
        wp_send_json_error( array('msg'=> 'Invalid option key') );
    }

    // create the array we send back to javascript here
    $array_to_send_back = array( 'msg' => 'Option updated successfully!' );

    if (empty($_POST["not_boolean"])) {
        // dealing with boolean value (true or false)
        $option_value = filter_var($option_value, FILTER_VALIDATE_BOOLEAN);
    }

    // if user disabled Thank you page then step 3 status should show failed
    if ($option_key == 'sa_survey_status' && ! $option_value) {
        update_option('sa_step3_status', false);
    }

    $admin_menus = new Shopper_Approved_Admin_Menus();
    $site_id = get_option('sa_site_id');

    // check if something additional needs to be done before updating option
    if ($option_key == 'sa_rp_status') {
        // update review page code
        $admin_menus->update_review_page_code($site_id);

        // handle Review page widget if needed
        $admin_menus->handle_review_page_widget($option_value); // add or remove Reviews page based on user's choice
        $array_to_send_back['show_rp_code'] = get_option('rp_already_exists');

    } else if ($option_key == 'sa_seal_status') {
        // update seal code
        $domain = get_option('sa_domain');
        $admin_menus->update_seal_code($site_id, $domain);
    }

    // update SA option
    update_option( $option_key, $option_value );

    // json encode the output because that's what it is expecting
    wp_send_json_success( $array_to_send_back );

}
add_action( 'wp_ajax_' . 'update_sa_option', 'ajax_callback_update_sa_option' );
