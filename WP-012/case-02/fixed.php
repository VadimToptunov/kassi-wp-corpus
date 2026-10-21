<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: memberglut 1.1.5, includes/class-memberglut-forms.php (excerpt; elided context marked '...')
// CVE-2026-12394 (fixed). Copyright the memberglut authors.
class MemberGlut_Forms {
    // ...
    public function ajax_register() {
        check_ajax_referer('memberglut_ajax_nonce', 'nonce');

        if (!get_option('users_can_register')) {
            wp_send_json_error(array('message' => esc_html__('User registration is currently not allowed.', 'memberglut')));
        }

        $username = isset($_POST['username']) ? sanitize_user(wp_unslash($_POST['username'])) : '';
        $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Password should not be sanitized to avoid altering the actual value.
        $password = isset($_POST['password']) ? wp_unslash($_POST['password']) : '';
        $first_name = isset($_POST['first_name']) ? sanitize_text_field(wp_unslash($_POST['first_name'])) : '';
        $last_name = isset($_POST['last_name']) ? sanitize_text_field(wp_unslash($_POST['last_name'])) : '';
        $default_role = isset($_POST['default_role']) ? sanitize_key($_POST['default_role']) : '';

        // Validate required fields
        if (empty($username) || empty($email) || empty($password)) {
            wp_send_json_error(array('message' => esc_html__('Please fill in all required fields.', 'memberglut')));
        }

        // Create user
        $user_id = wp_create_user($username, $password, $email);

        if (is_wp_error($user_id)) {
            wp_send_json_error(array('message' => $user_id->get_error_message()));
        }

        // Update user meta
        if (!empty($first_name)) {
            update_user_meta($user_id, 'first_name', $first_name);
        }
        if (!empty($last_name)) {
            update_user_meta($user_id, 'last_name', $last_name);
        }

        // Set user role - SECURITY: Only allow safe roles for registration
        // Allowlist of roles that unprivileged users can register for
        $allowed_registration_roles = array('subscriber', 'memberglut_basic', 'memberglut_premium', 'memberglut_vip');
        if (!empty($default_role) && in_array($default_role, $allowed_registration_roles, true) && get_role($default_role)) {
            $user = new WP_User($user_id);
            $user->set_role($default_role);
        }

        // Send notification emails
        wp_new_user_notification($user_id, null, 'both');

        // Auto-login if enabled
        if (get_option('memberglut_auto_login_after_register', true)) {
            $creds = array(
                'user_login' => $username,
                'user_password' => $password,
                'remember' => false
            );
            wp_signon($creds, false);
        }

        // Determine redirect URL
        $redirect_url = get_option('memberglut_login_redirect', '');
        if (empty($redirect_url)) {
            $redirect_url = home_url();
        }

        wp_send_json_success(array(
            'message' => esc_html__('Registration successful!', 'memberglut'),
            'redirect_url' => apply_filters('memberglut_register_redirect_url', $redirect_url)
        ));
    }
}
