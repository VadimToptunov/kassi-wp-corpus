<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: devs-accounting 1.2.1, classes/class-devs-accounting-accounts.php (excerpt)
// CVE-2026-9172 (fixed). Copyright the devs-accounting authors.
register_rest_route('devs-accounting/v1', '/delete-account/(?P<id>\d+)', array(
    'methods' => 'DELETE',
    'callback' => [$this, 'delete_single_account'],
    'permission_callback' => [$this, 'verify_accounts_nonce']
));
