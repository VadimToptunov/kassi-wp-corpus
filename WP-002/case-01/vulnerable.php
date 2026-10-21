<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: quick-restaurant-menu 2.0.2, includes/admin/ajax-functions.php (excerpt; elided context marked '...')
// CVE-2023-0554. Copyright the quick-restaurant-menu authors.
function erm_delete_menu_item() {

	if (isset($_POST['post_id'])) {
		wp_delete_post( absint($_POST['post_id']), true);
		wp_send_json_success();
	}
	exit();
}
add_action( 'wp_ajax_erm_delete_menu_item', 'erm_delete_menu_item' );
