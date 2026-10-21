<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: sassy-social-share 3.3.24, admin/class-sassy-social-share-admin.php (excerpt; elided context marked '...')
// CVE-2021-39321 (fixed). Copyright the sassy-social-share authors.
class Sassy_Social_Share_Admin {
    // ...
	public function import_config() {
		
		if ( current_user_can( 'manage_options' ) ) {
		    if ( check_ajax_referer( 'heateor_sss_admin_options_script', 'nonce' ) === false ) {
				die;
			}
			if ( isset( $_POST['config'] ) && strlen( trim( $_POST['config'] ) ) > 0 ) {
				$config = json_decode( stripslashes( trim( $_POST['config'] ) ), true );
				if ( is_array( $config ) && count( $config ) > 0 ) {
					update_option( 'heateor_sss', $config );
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
}
