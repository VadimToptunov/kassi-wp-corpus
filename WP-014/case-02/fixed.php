<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Synthetic WP illustration of CVE-2026-55220 (fixed). allowed_classes => false builds no object,
// so there is no POP chain even though the gadget class is still present.
add_action( 'wp_ajax_nopriv_kassi_import', 'kassi_import' );
function kassi_import() {
	$data = unserialize( base64_decode( $_POST['blob'] ), array( 'allowed_classes' => false ) );
	return $data;
}

class Kassi_Logger {
	public $query;
	public function __wakeup() {
		global $wpdb;
		$wpdb->query( $this->query );
	}
}
