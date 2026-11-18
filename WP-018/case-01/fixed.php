<?php
// Ajaxify Comments 3.2.0 — functions.php, wpac_init() (excerpt).
// Fix for CVE-2026-2811: each GET parameter is passed through sanitize_text_field() (which strips line
// breaks) before it reaches header(), so no CR/LF can enter the response header.
function wpac_init() {
	if ( isset( $_GET['WPACUnapproved'] ) ) {
		$wpac_unapproved = sanitize_text_field( wp_unslash( $_GET['WPACUnapproved'] ) );
		if ( $wpac_unapproved === '0' || $wpac_unapproved === '1' ) {
			header( 'X-WPAC-UNAPPROVED: ' . esc_html( $wpac_unapproved ) );
		}
	}
	if ( isset( $_GET['WPACUrl'] ) ) {
		$wpac_url = sanitize_text_field( wp_unslash( $_GET['WPACUrl'] ) );
		$wpac_url = str_replace( array( "\r", "\n", "\0" ), '', $wpac_url );
		$wpac_url = esc_url_raw( $wpac_url );
		if ( '' !== $wpac_url && wp_validate_redirect( $wpac_url, false ) !== false ) {
			header( 'X-WPAC-URL: ' . $wpac_url );
		}
	}
}
