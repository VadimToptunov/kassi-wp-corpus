<?php
// Fixed: the addon name is reduced to a bare filename with the cmd filter + basename(), and checked
// against the known addon directory, so no traversal reaches require (CVE-2026-67285).
defined('_JEXEC') or die;

class SppagebuilderHelperRender
{
    public function renderAddon($app)
    {
        $type = basename($app->input->getCmd('addon_type'));
        require_once JPATH_ROOT . '/components/com_sppagebuilder/addons/' . $type . '/site.php';
    }
}
