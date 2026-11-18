<?php
// Advanced Woo Labels 2.37 — includes/admin/class-awl-admin-ajax.php, get_select_option_values() (excerpt).
// Fix for CVE-2026-1929: the callback is validated against the plugin's known callbacks (an allow-list),
// and the handler now requires manage_options, before call_user_func_array() is reached. The real fix
// builds the allowed set by iterating the plugin's option rules; it is reduced here to an in_array()
// allow-list, which is the same security property (only a known callback may be invoked).
function get_select_option_values() {

	check_ajax_referer( 'awl_admin_ajax_nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( 'Insufficient permissions.' );
	}

	$callback = sanitize_text_field( $_POST['callback'] );
	$callback_params = isset( $_POST['param'] ) ? (array) $_POST['param'] : array();

	$allowed = array( 'awl_get_product_cats', 'awl_get_product_tags', 'awl_get_product_attributes' );
	if ( ! in_array( $callback, $allowed, true ) ) {
		wp_send_json_error( 'Invalid callback.' );
	}

	$values_arr = call_user_func_array( $callback, $callback_params );

	wp_send_json( array( 'results' => $values_arr ) );
}
