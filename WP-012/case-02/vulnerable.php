<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: memberglut 1.1.0, includes/class-memberglut-forms.php (excerpt; elided context marked '...')
// CVE-2026-12394. Copyright the memberglut authors.
class MemberGlut_Forms {
    // ...
    public function ajax_register() {
        check_ajax_referer('memberglut_ajax_nonce', 'nonce');
        
        if (!get_option('users_can_register')) {
            wp_send_json_error(array('message' => __('User registration is currently not allowed.', 'memberglut')));
        }
        
        $username = sanitize_user($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']);
        $default_role = sanitize_text_field($_POST['default_role']);
        
        // Validate required fields
        if (empty($username) || empty($email) || empty($password)) {
            wp_send_json_error(array('message' => __('Please fill in all required fields.', 'memberglut')));
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
        
        // Set user role
        if (!empty($default_role) && get_role($default_role)) {
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
            'message' => __('Registration successful!', 'memberglut'),
            'redirect_url' => apply_filters('memberglut_register_redirect_url', $redirect_url)
        ));
    }
}
