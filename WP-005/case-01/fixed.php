<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: events-manager 5.9.8, events-manager.php (excerpt; elided context marked '...')
// CVE-2020-35012 (fixed). Copyright the events-manager authors.
function em_load_event(){
	// ...
			global $wpdb;
			$matches = array();
			if( preg_match('/\-([0-9]+)$/', $_REQUEST['event_slug'], $matches) ){
				$event_id = $matches[1];
			}else{
				$query = $wpdb->prepare('SELECT event_id FROM '.EM_EVENTS_TABLE.' WHERE event_slug = %s AND blog_id != %d', $_REQUEST['event_slug'], get_current_blog_id());
				$event_id = $wpdb->get_var($query);
			}
}
