<?php
// Fixed: the stored filename is reduced to a basename and canonicalised with JPath::clean(), so a
// guest cannot traverse outside the tmp directory (CVE-2026-102424).
defined('_JEXEC') or die;

class BalboaFormsControllerSubmit
{
    public function readUploadState($app)
    {
        $stored = basename($app->input->getString('upload_state_file'));
        return readfile(JPath::clean(JPATH_ROOT . '/tmp/balbooa/' . $stored));
    }
}
