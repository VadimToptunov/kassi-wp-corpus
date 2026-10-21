<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: sassy-social-share 3.3.23, admin/class-sassy-social-share-admin.php (excerpt; elided context marked '...')
// CVE-2021-39321. Copyright the sassy-social-share authors.
class Sassy_Social_Share_Admin {
    // ...
	public function import_config() {
		
		if ( isset( $_POST['config'] ) && strlen( trim( $_POST['config'] ) ) > 0 ) {
			$config = maybe_unserialize( base64_decode( trim( $_POST['config'] ) ) );
			if ( is_array( $config ) && count( $config ) > 0 ) {
				update_option( 'heateor_sss', $config );
				header( 'Content-Type: application/json' );
				die( json_encode(
					array(
						'success' => 1
					)
				) );
			}
		}
		die;
	
	}
}
