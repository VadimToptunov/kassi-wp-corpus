<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: frontend-post-submission-manager-lite 1.2.8, includes/classes/class-fpsml-shortcode.php (excerpt; elided context marked '...')
// CVE-2026-1296 (fixed). Copyright the frontend-post-submission-manager-lite authors.
        function login_failed() {
            if (isset($_POST['requested_page'])) {
                $login_page = esc_url($_POST['requested_page']);
                wp_safe_redirect($login_page . '?login=failed');
                exit;
            }
        }
