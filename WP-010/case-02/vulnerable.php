<?php
// SPDX-License-Identifier: GPL-2.0-only
// Source: auto-post-thumbnail 4.1.7, includes/class-apt.php (excerpt; elided context marked '...')
// CVE-2023-7073. Copyright the auto-post-thumbnail authors.
class AutoPostThumbnails {
    // ...
	public function upload_to_library() {

		if ( ! wp_verify_nonce( $_POST['wpnonce'], 'apt_api' ) ) {
			die( 'Error: Invalid request.' );
		}
		if ( isset( $_POST['is_upload'] ) ) {
			$postid = intval( $_POST['postid'] );

			// get image file
			$response = wp_remote_get( $_POST['image_url'], [ 'timeout' => 100 ] );
			if ( is_wp_error( $response ) ) {
				die( 'Error: ' . esc_html( $response->get_error_message() ) );
			}

			$file_ext    = '';
			$image_title = '';
			switch ( $_POST['service'] ) {
				case 'pixabay':
					$path_info   = pathinfo( esc_url_raw( $_POST['image_url'] ) );
					$file_ext    = $path_info['extension'];
					$image_title = sanitize_text_field( $_POST['q'] );
					break;
				case 'unsplash':
					parse_str( parse_url( $_POST['image_url'], PHP_URL_QUERY ), $url_query );
					$file_ext = $url_query['fm'];
					if ( ! $file_ext ) {
						$file_ext = 'jpg';
					}

					$image_title = sanitize_text_field( $_POST['title'] );
					break;
				case 'google':
					$path_info = pathinfo( esc_url_raw( $_POST['image_url'] ) );
					$file_ext  = $path_info['extension'];
					if ( $file_ext !== 'jpg' && $file_ext !== 'jpeg' && $file_ext !== 'png' && $file_ext !== 'gif' ) {
						$file_ext = 'jpg';
					}
					if ( empty( $file_ext ) ) {
						$file_ext = 'jpg';
					}
					$image_title = sanitize_text_field( wp_unslash( $_POST['title'] ?? '' ) );
					break;
			}

			if ( ! $this->is_allowed_file_ext( $file_ext ) ) {
				die( 'Error: File extension is not allowed' );
			}

			$q                 = sanitize_text_field( wp_unslash( $_POST['q'] ?? '' ) );
			$file_name         = sanitize_file_name( implode( '_', explode( ' ', $q ) ) . '_' . time() . '.' . $file_ext );
			$wp_upload_dir     = wp_upload_dir();
			$image_upload_path = $wp_upload_dir['path'];

			if ( ! is_dir( $image_upload_path ) ) {
				// phpcs:disable WordPress.PHP.NoSilencedErrors.Discouraged
				if ( ! @mkdir( $image_upload_path, 0777, true ) ) {
					die( 'Error: Failed to create upload folder ' . esc_html( $image_upload_path ) );
				}
				// phpcs:enable
			}

			global $wp_filesystem;
			if ( ! $wp_filesystem ) {
				if ( ! function_exists( 'WP_Filesystem' ) ) {
					require_once ABSPATH . 'wp-admin/includes/file.php';
				}
				WP_Filesystem();
			}

			$target_file_name = $image_upload_path . '/' . $file_name;
			$result           = $wp_filesystem->put_contents( $target_file_name, $response['body'] );
			//$result           = @file_put_contents( $target_file_name, $response['body'] );
			unset( $response['body'] );
			if ( false === $result ) {
				die( 'Error: Failed to write file ' . esc_html( $target_file_name ) );
			}

			// are we dealing with an image
			require_once ABSPATH . 'wp-admin/includes/image.php';
			if ( ! wp_read_image_metadata( $target_file_name ) ) {
				unlink( $target_file_name );
				die( 'Error: File is not an image.' );
			}

			$attachment_caption = '';

			// insert attachment
			$wp_filetype = wp_check_filetype( basename( $target_file_name ), null );
			$attachment  = [
				'guid'           => $wp_upload_dir['url'] . '/' . basename( $target_file_name ),
				'post_mime_type' => $wp_filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', $image_title ),
				'post_status'    => 'inherit',
			];

			$attach_id = wp_insert_attachment( $attachment, $target_file_name, $postid );
			if ( 0 === $attach_id ) {
				die( 'Error: File attachment error' );
			}

			$attach_data = wp_generate_attachment_metadata( $attach_id, $target_file_name );
			$result      = wp_update_attachment_metadata( $attach_id, $attach_data );
			update_attached_file( $attach_id, $target_file_name );

			if ( ! $result ) {
				// die( 'Error: File attachment metadata error' );
			}

			$image_data                 = [];
			$image_data['ID']           = $attach_id;
			$image_data['post_excerpt'] = sanitize_text_field( wp_unslash( $_POST['excerpt'] ?? '' ) );
			wp_update_post( $image_data );

			echo (int) $attach_id;

			exit;
		}
	}
}
