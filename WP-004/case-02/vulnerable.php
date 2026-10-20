<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: plezi 1.0.2, rest-api/class-plz-rest-api-configuration.php (excerpt)
// CVE-2022-0680. Copyright the plezi authors.
register_rest_route(
  $this->tools->get_plugin_namespace(),
  '/configuration/set-tracking-api',
  array(
    array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => array( $this, 'set_tracking_api' ),
      'permission_callback' => '__return_true',
    ),
  )
);
