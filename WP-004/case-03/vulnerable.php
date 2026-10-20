<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: devs-accounting 1.2.0, classes/class-devs-accounting-accounts.php (excerpt)
// CVE-2026-9172. Copyright the devs-accounting authors.
register_rest_route('devs-accounting/v1', '/delete-account/(?P<id>\d+)', array(
    'methods' => 'get', // Change to POST method to handle data insertion
    'callback' => [$this, 'delete_single_account'],

));
