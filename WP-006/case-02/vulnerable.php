<?php
// SPDX-License-Identifier: GPL-3.0-or-later
// Source: whmcs-bridge 6.3, bridge_cp.php (excerpt; elided context marked '...')
// CVE-2021-25112. Copyright the whmcs-bridge authors.
function cc_whmcs_bridge_admin() {

    global $cc_whmcs_bridge_shortname, $current_user;

    if (in_array('administrator', $current_user->roles)) {
        $controlpanelOptions = cc_whmcs_bridge_options();

        if (isset($_REQUEST['installed']))
            echo '<div id="message" class="updated fade"><p><strong>' . WHMCS_BRIDGE . ' installed.</strong></p></div>';
        if (isset($_REQUEST['error'])) {
            echo '<div id="message" class="updated fade"><p>The following error occured: <strong>' . $_REQUEST['error'] . '</strong></p></div>';
            if (strstr($_REQUEST['error'], 'parsing')) {
                echo '<div id="message" class="updated fade"><p>Parse errors occur when the bridge is unable to connect to your WHMCS API, for more information please <a href="http://i-plugins.com/whmcs/knowledgebase/1082/I-am-getting-intermittent-DOMDocument-or-loadXML-errors-showing-up-on-the-bridge.html" target="_blank"><strong>click here</strong></a></p></div>';

            }
        }
        // ...
    }
}
