<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: shopperapproved-reviews 2.1, shopperapproved.php (excerpt; elided context marked '...')
// CVE-2025-3063. Copyright the shopperapproved-reviews authors.
function ajax_callback_update_sa_option()
{
    // get the post data
    $option_key = $_POST["option_key"];
    $option_value = sanitize_text_field($_POST["option_value"]);

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
    echo json_encode( $array_to_send_back );

    // die when finished doing ajax output.
    die();
}
add_action( 'wp_ajax_' . 'update_sa_option', 'ajax_callback_update_sa_option' );
