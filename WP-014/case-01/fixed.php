<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Synthetic WP illustration of CVE-2026-49286 (fixed). basename() strips the directory and any
// stream wrapper, so the path no longer derives from request input: no phar:// sink, no chain.
add_action( 'wp_ajax_nopriv_kassi_render', 'kassi_render_template' );
function kassi_render_template() {
	$tpl  = basename( $_GET['tpl'] );
	$path = KASSI_TPL_DIR . '/' . $tpl;  // confined to a fixed directory
	if ( getimagesize( $path ) ) {
		echo 'ok';
	}
	wp_die();
}

class Kassi_Temp_File {
	public $path;
	public function __destruct() {
		unlink( $this->path );
	}
}
