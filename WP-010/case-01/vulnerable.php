<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: frontis-blocks 1.1.6, includes/Admin/Admin.php (excerpt; elided context marked '...')
// CVE-2026-0807. Copyright the frontis-blocks authors.
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
            $remote_url = urldecode($remote_url);

            $response = wp_remote_get($remote_url);
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
