<?php
// SPDX-License-Identifier: GPL-2.0-only
// Source: advanced-booking-calendar 1.7.0, backend/seasons-calendars.php (excerpt; elided context marked '...')
// CVE-2022-1006. Copyright the advanced-booking-calendar authors.
function abc_booking_editCalendar() {
	global $wpdb;

	if ( !current_user_can( abc_booking_admin_capabilities() ) ) {
		wp_die("You don't have access to this page.");
	}
	$roomLimit = '&setting=changeSaved';
	if ( isset($_POST["id"]) && isset($_POST["name"]) && isset($_POST["maxAvailabilities"]) 
		&& isset($_POST["maxUnits"]) && isset($_POST["page_id"]) && intval($_POST["minimumStayPreset"]) > 0) {
		$er = $wpdb->get_row('SELECT SUM(maxAvailabilities) as rooms FROM '.$wpdb->prefix.'abc_calendars', ARRAY_A);
		$rooms = $er["rooms"];
		$er = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'abc_calendars WHERE id = '.$_POST["id"], ARRAY_A);
		// ...
	}
}
