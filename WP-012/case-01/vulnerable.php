<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: woo-wholesale-pricing 2.0.5, inc/class-wwp-wholesale-requests.php (excerpt; elided context marked '...')
// CVE-2026-12144. Copyright the woo-wholesale-pricing authors.
	class WWP_Easy_Wholesale_Requests {
    // ...
		public function save_requests_meta( $post_id ) {
			// Autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			// AJAX
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				return;
			}
			if ( ! isset( $_POST['request_user_role_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['request_user_role_nonce'] ) ), 'request_user_role_nonce' ) ) {
				return;
			}

			if ( isset( $_POST['user_status'] ) ) {
				$status = sanitize_text_field( wp_unslash( $_POST['user_status'] ) );
				if ( isset( $_POST['user_role_set'] ) ) {
					$user_role_set = sanitize_text_field( wp_unslash( $_POST['user_role_set'] ) );
				} else {
					$user_role_set = 'wwp_wholesaler';
				}
				if ( isset( $_POST['rejected_note'] ) ) {
					$rejected_note = sanitize_text_field( wp_unslash( $_POST['rejected_note'] ) );
					update_post_meta( $post_id, '_user_status', $status );
				}
				$term_list = wp_get_post_terms( $post_id, 'wholesale_user_roles', array( 'fields' => 'all' ) );

				$allterms = get_terms( array(
					'taxonomy'   => 'wholesale_user_roles',
					'hide_empty' => false,
				) );

				$user_id = get_post_meta( $post_id, '_user_id', true );
				update_user_meta( $user_id, '_user_status', $status );
				update_user_meta( $user_id, 'rejected_note', $rejected_note );
				
				$u = new WP_User( $user_id );
				if ( 'active' === $status ) {

						$wp_roles = new WP_Roles();
						$names    = $wp_roles->get_names();

					foreach ( $names as $key => $value ) {
						$u->remove_role( $key );
					}
					$u->add_role( $user_role_set );

					foreach ( $term_list as $term_remove ) {

						wp_remove_object_terms( $post_id, $term_remove->slug, 'wholesale_user_roles', true );
					}

					wp_set_object_terms( $post_id, $user_role_set, 'wholesale_user_roles', true );

					if ( 'sent' !== get_post_meta( $post_id, '_approval_notification', true ) ) {
						/**
						 * Hook
						 * 
						 * @since 1.0.0
						 */ 
						do_action( 'wwp_wholesale_user_request_approved', $user_id );
						update_post_meta( $post_id, '_approval_notification', 'sent' );
					}
				} elseif ( 'rejected' === $status ) {
						 
						$u->remove_role( 'wwp_wholesaler' );
						 
						$u->add_role( get_option( 'default_role' ) );
						/**
						 * Hook
						 * 
						 * @since 1.0.0
						 */ 
						do_action( 'wwp_wholesale_user_rejection_notification', $user_id );
				}
			}
		}
}
