<?php
// SPDX-License-Identifier: GPL-2.0-only
// Source: auto-post-thumbnail 4.2.0, includes/class-apt.php (excerpt; elided context marked '...')
// CVE-2023-7073 (fixed). Copyright the auto-post-thumbnail authors.
class AutoPostThumbnails {
    // ...
	public function upload_to_library() {
		$nonce = isset( $_POST['wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wpnonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'apt_api' ) ) {
			die( 'Error: Invalid request.' );
		}

		// Check user capabilities
		if ( ! current_user_can( 'upload_files' ) ) {
			die( 'Error: You do not have permission to upload files.' );
		}

		$is_upload = isset( $_POST['is_upload'] ) ? absint( $_POST['is_upload'] ) : 0;
		
		if ( ! $is_upload ) {
			return;
		}

		$postid = isset( $_POST['postid'] ) ? absint( $_POST['postid'] ) : 0;
		if ( $postid && ! current_user_can( 'edit_post', $postid ) ) {
			die( 'Error: You cannot attach media to this post.' );
		}

		$image_url = isset( $_POST['image_url'] ) ? wp_unslash( $_POST['image_url'] ) : '';
		$image_url = wp_http_validate_url( $image_url );
		
		if ( ! $image_url ) {
			die( 'Error: Invalid image URL.' );
		}

		// Require HTTPS for security
		if ( 0 !== strpos( $image_url, 'https://' ) ) {
			die( 'Error: Image URL must use HTTPS.' );
		}

		$image_url = esc_url_raw( $image_url );

		$parsed_url = wp_parse_url( $image_url );
		$host       = isset( $parsed_url['host'] ) ? $parsed_url['host'] : '';

		// Block private/internal IPs (SSRF secondary defense)
		$ip = gethostbyname( $host );
		if ( false === filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) ) {
			die( 'Error: Invalid image source.' );
		}

		$max_size = 10 * 1024 * 1024; // 10MB

		// Pre-download check via Content-Length header
		$headers_response = wp_remote_head( $image_url, array(
			'timeout'     => 10,
			'redirection' => 3,
			'sslverify'   => true,
		) );

		if ( ! is_wp_error( $headers_response ) ) {
			$content_length = wp_remote_retrieve_header( $headers_response, 'content-length' );
			if ( $content_length && intval( $content_length ) > $max_size ) {
				die( 'Error: Image file is too large.' );
			}
		}

		$response = wp_remote_get( $image_url, array(
			'timeout'     => 30,
			'redirection' => 3,
			'sslverify'   => true,
		) );

		if ( is_wp_error( $response ) ) {
			die( 'Error: Failed to download image.' );
		}

		$status_code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $status_code ) {
			die( 'Error: Server returned error.' );
		}

		$content_type = wp_remote_retrieve_header( $response, 'content-type' );
		$content_type = explode( ';', $content_type );
		$content_type = trim( $content_type[0] );

		$allowed_types = get_allowed_mime_types();

		if ( ! in_array( $content_type, $allowed_types, true ) ) {
			die( 'Error: Invalid image type.' );
		}

		$response_body = wp_remote_retrieve_body( $response );
		$actual_size = strlen( $response_body );

		if ( $actual_size > $max_size || empty( $response_body ) ) {
			die( 'Error: Image file is invalid.' );
		}

		$mime_to_ext = array(
			'image/jpeg' => 'jpg',
			'image/png'  => 'png',
			'image/gif'  => 'gif',
			'image/webp' => 'webp',
		);

		$file_ext = isset( $mime_to_ext[ $content_type ] ) ? $mime_to_ext[ $content_type ] : 'jpg';

		if ( ! $this->is_allowed_file_ext( $file_ext ) ) {
			die( 'Error: File extension not allowed.' );
		}

		$search_query = isset( $_POST['q'] ) ? wp_unslash( $_POST['q'] ) : '';
		$file_name    = sanitize_file_name( trim( $search_query ) . '_' . time() . '.' . $file_ext );

		if ( empty( $file_name ) || '.' === $file_name[0] ) {
			$file_name = str_replace( '_', ' ', $search_query ) . '_' . time() . '.' . $file_ext;
		}

		$image_title = isset( $_POST['title'] ) ? wp_unslash( $_POST['title'] ) : '';
		if ( ! $image_title ) {
			$image_title = ! empty( $search_query ) ? $search_query : 'Imported Image';
		}

		$image_caption = isset( $_POST['excerpt'] ) ? sanitize_textarea_field( wp_unslash( $_POST['excerpt'] ) ) : '';

		$upload = wp_upload_bits( $file_name, null, $response_body );

		if ( $upload['error'] ) {
			die( 'Error: Failed to upload file.' );
		}

		$target_file_path = $upload['file'];

		$image_info = @getimagesize( $target_file_path );

		if ( false === $image_info ) {
			@unlink( $target_file_path );
			die( 'Error: File is not a valid image.' );
		}

		$image_width  = $image_info[0];
		$image_height = $image_info[1];

		// Decompression bomb protection
		$max_dimension = 10000;
		if ( $image_width > $max_dimension || $image_height > $max_dimension ) {
			@unlink( $target_file_path );
			die( 'Error: Image dimensions are too large.' );
		}

		$wp_upload_dir = wp_upload_dir();
		$file_url = $wp_upload_dir['url'] . '/' . basename( $target_file_path );

		$attachment_data = array(
			'guid'           => $file_url,
			'post_mime_type' => $content_type,
			'post_title'     => sanitize_text_field( $image_title ),
			'post_excerpt'   => $image_caption,
			'post_status'    => 'inherit',
		);

		$attachment_id = wp_insert_attachment( $attachment_data, $target_file_path, $postid );

		if ( 0 === $attachment_id ) {
			@unlink( $target_file_path );
			die( 'Error: Failed to create attachment.' );
		}

		// Generate thumbnails and metadata
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$attachment_metadata = wp_generate_attachment_metadata( $attachment_id, $target_file_path );

		if ( $attachment_metadata ) {
			wp_update_attachment_metadata( $attachment_id, $attachment_metadata );
		}

		echo (int) $attachment_id;
		exit;
	}
}
