<?php
/**
 * Synthetic reconstruction of CVE-2018-15877 (Plainview Activity Monitor <= 20161228, before 20180826):
 * the "Activity Tools" admin page runs a network command built from the `ip` request parameter, so shell
 * metacharacters in `ip` are injected into the OS command. See meta.yml for the NVD/advisory references.
 */
function plainview_activity_monitor_activity_tools() {
    $ip = $_POST['ip'];
    // host/traceroute-style lookup; $ip reaches the shell unescaped.
    $output = shell_exec( 'host ' . $ip );
    echo esc_html( $output );
}
