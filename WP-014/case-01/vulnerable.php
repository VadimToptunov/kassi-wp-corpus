<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Synthetic WP illustration of CVE-2026-49286 (PhpWeasyPrint PHAR deserialization, CWE-502).
// A request-derived path reaches a file op (phar:// → object injection) + a file-deleting gadget.
add_action( 'wp_ajax_nopriv_kassi_render', 'kassi_render_template' );
function kassi_render_template() {
	$tpl = $_GET['tpl'];                 // attacker-controlled; may be phar://evil.phar/x
	if ( getimagesize( $tpl ) ) {        // PHAR deserialization sink on a request-derived path
		echo 'ok';
	}
	wp_die();
}

// POP gadget: its __destruct deletes an attacker-chosen file once the object is instantiated.
class Kassi_Temp_File {
	public $path;
	public function __destruct() {
		unlink( $this->path );
	}
}
