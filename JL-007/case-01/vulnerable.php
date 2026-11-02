<?php
// Synthetic LFI fixture for CVE-2026-67285 (SP Page Builder, unauth LFI -> code execution).
// A request-controlled layout/addon name is concatenated into a require path, so an attacker can
// traverse to and execute an arbitrary local PHP file.
defined('_JEXEC') or die;

class SppagebuilderHelperRender
{
    public function renderAddon($app)
    {
        $type = $app->input->getString('addon_type');
        require_once JPATH_ROOT . '/components/com_sppagebuilder/addons/' . $type . '/site.php';
    }
}
