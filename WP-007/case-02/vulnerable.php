<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: wc-purchase-orders 1.0.2, includes/class-bbpo-purchase-orders-files.php (excerpt; elided context marked '...')
// CVE-2025-5391. Copyright the wc-purchase-orders authors.
class BBPO_Purchase_Orders_Files {
	// ...
	public function delete_file() {
		if ( isset( $_REQUEST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'wcpo-nonce' ) ) {
			if ( empty( $_POST['file_path'] ) ) {
				wp_send_json_error(
					[
						'code'    => 'file_path_required',
						'message' => esc_html__( 'File path is required', 'wc-purchase-orders' )
					]
				);
			}
			if ( file_exists( wp_upload_dir()['basedir'] . sanitize_text_field( $_POST['file_path'] ) ) ) {
				//delete file
				unlink( wp_upload_dir()['basedir'] . sanitize_text_field( $_POST['file_path'] ) );
				wp_send_json_success( [
					'message' => esc_html__( 'File deleted', 'wc-purchase-orders' )
				] );
			} else {
				wp_send_json_error(
					[
						'code'    => 'file_not_found',
						'message' => esc_html__( 'File requested is not exists!', 'wc-purchase-orders' )
					]
				);
			}
		} else {
			wp_send_json_error(
				[
					'code'    => 'nonce_failed',
					'message' => esc_html__( 'Failed to pass security check!', 'wc-purchase-orders' )
				]
			);
		}
	}
}
