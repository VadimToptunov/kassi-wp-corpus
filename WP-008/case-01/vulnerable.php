<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: event-tickets 5.2.1, src/Tribe/Redirections.php (excerpt; elided context marked '...')
// CVE-2021-25028. Copyright the event-tickets authors.
/**
 * Class Tribe__Tickets__Redirections
 *
 * @since 4.7
 */
class Tribe__Tickets__Redirections {
	/**
	 * Conditionally redirects the user if a URL is specified in the GET request.
	 *
	 * @since 4.7.3
	 */
	public function maybe_redirect() {
		if ( empty( $_GET['tribe_tickets_redirect_to'] ) ) {
			return;
		}

		$url = rawurldecode( $_GET['tribe_tickets_redirect_to'] );
		wp_redirect( $url );
		die();
	}
}
