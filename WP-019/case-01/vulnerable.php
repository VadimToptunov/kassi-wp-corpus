<?php
// Advanced Woo Labels 2.36 — includes/admin/class-awl-admin-ajax.php, get_select_option_values() (excerpt).
// CVE-2026-1929: the AJAX handler passes a request-controlled 'callback' straight into
// call_user_func_array() with no allow-list, so a (Contributor+) attacker runs an arbitrary PHP function.
// Note: sanitize_text_field() cleans the string but does NOT restrict which function may be named.
function get_select_option_values() {

	check_ajax_referer( 'awl_admin_ajax_nonce' );

	$callback = sanitize_text_field( $_POST['callback'] );
	$callback_params = isset( $_POST['param'] ) ? (array) $_POST['param'] : array();

	$term = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
	$term = (string) wc_clean( wp_unslash( $term ) );

	if ( $term ) {
		$callback_params = array( $term );
	}

	$values_arr = call_user_func_array( $callback, $callback_params );

	wp_send_json( array( 'results' => $values_arr ) );
}
