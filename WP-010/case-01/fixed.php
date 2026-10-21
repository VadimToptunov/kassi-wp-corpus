<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: frontis-blocks 1.1.7, includes/Admin/Admin.php (excerpt; elided context marked '...')
// CVE-2026-0807 (fixed). Copyright the frontis-blocks authors.
class Admin
{
    // ...
    public function template_proxy()
    {
        global $wp_query;

        $is_proxy = get_query_var('template_proxy');
        $remote_url = get_query_var('url');

        // fallback: check raw $_GET if query_vars fail
        if (empty($is_proxy) && isset($_GET['template_proxy'])) {
            $is_proxy = sanitize_text_field($_GET['template_proxy']);
        }
        if (empty($remote_url) && isset($_GET['url'])) {
            $remote_url = sanitize_text_field($_GET['url']);
        }

        if ($is_proxy && $remote_url) {
            // Security: Only allow admins to use this proxy
            if (!current_user_can('manage_options')) {
                wp_die('Unauthorized', 403);
            }

            $remote_url = urldecode($remote_url);

            // Security: Validate URL format and scheme
            $parsed_url = parse_url($remote_url);
            if (!$parsed_url || !isset($parsed_url['scheme']) || !in_array($parsed_url['scheme'], ['http', 'https'], true)) {
                wp_die('Invalid URL scheme', 400);
            }

            // Security: Strict Domain Allowlist
            $allowed_hosts = [];
            if (defined('PORTAL_API')) {
                $allowed_hosts[] = parse_url(PORTAL_API, PHP_URL_HOST);
            }
            if (defined('WORDPRESS_SYNC_API')) {
                $allowed_hosts[] = parse_url(WORDPRESS_SYNC_API, PHP_URL_HOST);
            }

            // Helper to get host from URL even if scheme is missing or slight differences
            $remote_host = $parsed_url['host'] ?? '';

            if (!in_array($remote_host, $allowed_hosts, true)) {
                 // Fallback or Strict? 
                 // If the plugin ONLY needs these two, strict is better.
                 wp_die('Domain not allowed', 403);
            }

            // Security: Use wp_safe_remote_get to prevent SSRF to private IPs
            $response = wp_safe_remote_get($remote_url);

            if (is_wp_error($response)) {
                wp_die('Proxy request failed: ' . esc_html($response->get_error_message()));
            }

            $body = wp_remote_retrieve_body($response);
            $content_type = wp_remote_retrieve_header($response, 'content-type');
            header("Content-Type: " . ($content_type ?: 'text/html; charset=UTF-8'));

            // Fix relative asset URLs
            $body = preg_replace_callback(
                '/(src|href)=["\'](\/[^"\']+)["\']/i',
                function ($matches) use ($remote_url) {
                    $base = preg_replace('#/[^/]*$#', '/', $remote_url);
                    return $matches[1] . '="' . rtrim($base, '/') . $matches[2] . '"';
                },
                $body
            );

            echo $body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Binary image data
            exit;
        }
    }
}
