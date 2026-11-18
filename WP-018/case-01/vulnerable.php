<?php
// Ajaxify Comments 3.1.2 — functions.php, wpac_init() (excerpt).
// CVE-2026-2811: a GET parameter is concatenated into a response header with no CR/LF removal, so a
// crafted value injects extra HTTP response headers / splits the response (HTTP header injection).
function wpac_init() {
	if ( isset( $_GET['WPACUnapproved'] ) ) {
		header( 'X-WPAC-UNAPPROVED: ' . $_GET['WPACUnapproved'] );
	}
	if ( isset( $_GET['WPACUrl'] ) ) {
		header( 'X-WPAC-URL: ' . $_GET['WPACUrl'] );
	}
}
