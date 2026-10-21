<?php
// SPDX-License-Identifier: GPL-3.0-or-later
// Source: wc-carta-docente 1.5.0, includes/class-wccd-admin.php (excerpt; elided context marked '...')
// CVE-2026-2421. Copyright the wc-carta-docente authors.
class WCCD_Admin {
	// ...
	public function delete_certificate_callback() {

		if ( isset( $_POST['wccd-delete'], $_POST['delete-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['delete-nonce'] ) ), 'wccd-del-cert-nonce' ) ) {

			$cert = isset( $_POST['cert'] ) ? sanitize_text_field( wp_unslash( $_POST['cert'] ) ) : '';

			if ( $cert ) {

				unlink( WCCD_PRIVATE . $cert );

			}
		}

		exit;

	}
}
