<?php
/**
 * Fixed form (Plainview Activity Monitor 20180826): the request value is passed through
 * escapeshellarg() before it reaches the shell, so metacharacters can no longer break out of the
 * single argument.
 */
function plainview_activity_monitor_activity_tools() {
    $ip = $_POST['ip'];
    $output = shell_exec( 'host ' . escapeshellarg( $ip ) );
    echo esc_html( $output );
}
