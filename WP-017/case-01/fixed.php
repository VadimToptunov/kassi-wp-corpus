<?php
// Pagelayer 2.0.8 — main/ajax.php, pagelayer_contact_send() (excerpt).
// Fix for CVE-2026-2442: each field is passed through pagelayer_esc_crlf() and sanitize_text_field()
// (which strips line breaks) before it can become the Reply-To address, so no CR/LF reaches the header.
function pagelayer_contact_send() {

	$formdata = $_POST;
	unset($formdata['pagelayer_nonce']);

	$reply_to = '';
	$headers = '';

	foreach ($formdata as $k => $i) {
		if (is_array($i)) {
			$i = pagelayer_flat_join($i);
		}

		$i = pagelayer_esc_crlf($i);
		$i = sanitize_text_field($i);

		// Record a reply to if it is to be used
		if (is_email($i) && empty($reply_to)) {
			$reply_to = $i;
		}
	}

	if (!preg_match('/reply\-to/is', $headers) && !empty($reply_to)) {
		$headers .= "Reply-To: $reply_to\n";
	}

	$to_mail = get_option('admin_email');
	$subject = 'New form entry';
	$body = 'A new form was submitted.';

	$r = wp_mail($to_mail, $subject, $body, $headers);
}
