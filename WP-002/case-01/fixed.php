<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: quick-restaurant-menu 2.1.0, includes/admin/ajax-functions.php (excerpt; elided context marked '...')
// CVE-2023-0554 (fixed). Copyright the quick-restaurant-menu authors.
function erm_delete_menu_item() {

	if (!wp_verify_nonce(sanitize_text_field($_REQUEST['nonce']), 'erm_menu_actions' ))
	{
		wp_send_json_error();
	}

	if (isset($_POST['post_id'])) {

		if (get_post_type(absint($_POST['post_id'])) != 'erm_menu_item')
		{
			wp_send_json_error();
		}

		wp_delete_post( absint($_POST['post_id']), true);
		wp_send_json_success();
	}
	exit();
}
add_action( 'wp_ajax_erm_delete_menu_item', 'erm_delete_menu_item' );
