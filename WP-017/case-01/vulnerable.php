<?php
// Pagelayer 2.0.7 — main/ajax.php, pagelayer_contact_send() (excerpt).
// CVE-2026-2442: a submitted form field is recorded as the Reply-To address and concatenated into the
// mail headers without CR/LF removal, so a crafted field value injects extra headers (Bcc/Cc).
function pagelayer_contact_send() {

	$formdata = $_POST;
	// NOTE : NEVER add anything to $formdata except $_POST vars
	unset($formdata['pagelayer_nonce']);

	$reply_to = '';
	$headers = '';

	foreach ($formdata as $k => $i) {
		if (is_array($i)) {
			$i = pagelayer_flat_join($i);
		}

		// Record a reply to if it is to be used
		if (is_email(trim($i)) && empty($reply_to)) {
			$reply_to = trim($i);
		}
	}

	// Do we have a reply to in the headers ?
	if (!preg_match('/reply\-to/is', $headers) && !empty($reply_to)) {
		$headers .= "Reply-To: $reply_to\n";
	}

	$to_mail = get_option('admin_email');
	$subject = 'New form entry';
	$body = 'A new form was submitted.';

	$r = wp_mail($to_mail, $subject, $body, $headers);
}
