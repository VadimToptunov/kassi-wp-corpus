<?php
// SPDX-License-Identifier: GPL-3.0-or-later
// Source: kiwi-social-share 2.1.1, includes/lib/helpers/class-kiwi-social-share-helper.php (excerpt; elided context marked '...')
// CVE-2021-4362 (fixed). Copyright the kiwi-social-share authors.
add_action( 'wp_ajax_kiwi_social_share_get_option', 'kiwi_social_share_get_option' );
add_action( 'wp_ajax_kiwi_social_share_set_option', 'kiwi_social_share_set_option' );
// ...
function kiwi_social_share_set_option() {

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Forbidden' );
	}

	$allowed_options = array( 'kiwi_registration' );

	if ( ! in_array( $_POST['args']['group'], $allowed_options ) ) {
		wp_die( 'Forbidden' );
	}

	if ( !empty( $_POST ) && $_POST[ 'action' ] === 'kiwi_social_share_set_option' ) {
		$option								 = get_option( $_POST[ 'args' ][ 'group' ] );
		$option[ $_POST[ 'args' ][ 'option' ] ]	 = $_POST[ 'args' ][ 'value' ];
		update_option( $_POST[ 'args' ][ 'group' ], $option );
		wp_die( 'Success' );
	}

	wp_die( 'Forbidden' );
}
