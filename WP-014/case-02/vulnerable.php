<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Synthetic WP illustration of CVE-2026-55220 (Pimcore unrestricted unserialize, CWE-502).
// Request input reaches unserialize() without allowed_classes + a SQL-executing gadget completes it.
add_action( 'wp_ajax_nopriv_kassi_import', 'kassi_import' );
function kassi_import() {
	$data = unserialize( base64_decode( $_POST['blob'] ) );   // object injection sink
	return $data;
}

// POP gadget: __wakeup runs attacker-chosen SQL once the object is deserialized.
class Kassi_Logger {
	public $query;
	public function __wakeup() {
		global $wpdb;
		$wpdb->query( $this->query );
	}
}
