<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: wc-purchase-orders 1.0.3, includes/class-bbpo-purchase-orders-files.php (excerpt; elided context marked '...')
// CVE-2025-5391 (fixed). Copyright the wc-purchase-orders authors.
class BBPO_Purchase_Orders_Files {
	// ...
	public function delete_file() {
		if ( isset( $_REQUEST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'wcpo-nonce' ) ) {
			if ( empty( $_POST['file_name'] ) ) {
				$this->send_error_signal(
					array(
						'code'    => 'file_name_required',
						'message' => esc_html__( 'File name is required', 'wc-purchase-orders' ),
					)
				);
			}

			$upload_dir    = wp_upload_dir();
			$current_year  = gmdate( 'Y' );
			$current_month = gmdate( 'm' );
			$dir           = DIRECTORY_SEPARATOR . 'wc-purchase-orders' . DIRECTORY_SEPARATOR . $current_year . DIRECTORY_SEPARATOR . $current_month . DIRECTORY_SEPARATOR;
			$file_path     = $upload_dir['basedir'] . $dir . sanitize_file_name( wp_unslash( $_POST['file_name'] ) );
			if ( $this->validate_file_path( $file_path ) ) {
				// delete file.
				wp_delete_file( $file_path );
				$this->send_success_signal(
					array(
						'message' => esc_html__( 'File deleted', 'wc-purchase-orders' ),
					)
				);
			} else {
				$this->send_error_signal(
					array(
						'code'    => 'file_not_found',
						'message' => esc_html__( 'File requested is not exists!', 'wc-purchase-orders' ),
					)
				);
			}
		} else {
			$this->send_error_signal(
				array(
					'code'    => 'nonce_failed',
					'message' => esc_html__( 'Failed to pass security check!', 'wc-purchase-orders' ),
				)
			);
		}
	}
}
